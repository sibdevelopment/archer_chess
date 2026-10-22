<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchDstAdjustmentItem;
use App\Models\BatchDstAdjustmentRun;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BatchDstAdjustmentService
{
    private const TIME_PATTERN = '/(?<![0-9.])(\d{1,2})(?:(\.|:)(\d{2}))?(\s*)(AM|PM)\b/i';

    private const WEEKDAY_CODES = [
        'Monday' => 'M',
        'Tuesday' => 'T',
        'Wednesday' => 'W',
        'Thursday' => 'TH',
        'Friday' => 'F',
        'Saturday' => 'S',
        'Sunday' => 'SU',
    ];

    public function transition(string $region, string $transition): array
    {
        $regionConfig = config("batch_dst.regions.{$region}");
        $definition = $regionConfig['transitions'][$transition] ?? null;

        if (! $regionConfig || ! $definition) {
            throw new RuntimeException("Unknown DST transition {$region}:{$transition}.");
        }

        return array_merge($definition, [
            'key' => "{$region}:{$transition}",
            'region' => $region,
            'transition' => $transition,
            'label' => $regionConfig['label'],
            'countries' => $regionConfig['countries'],
        ]);
    }

    public function preview(string $region, string $transition): array
    {
        $definition = $this->transition($region, $transition);
        $rows = [];
        $blocked = [];
        $overlapSkipped = [];

        foreach ($this->candidateBatches($definition) as $batch) {
            $owner = $this->owningRegion($batch->country ?? []);

            if ($owner !== $region) {
                $overlapSkipped[] = [
                    'batch_id' => $batch->id,
                    'batch_name' => $batch->name,
                    'owner' => $owner,
                ];

                continue;
            }

            try {
                $before = $this->snapshot($batch);
                $after = $this->shiftedState($before, (int) $definition['minutes']);
                $rows[] = compact('batch', 'before', 'after');
            } catch (RuntimeException $exception) {
                $blocked[] = [
                    'batch_id' => $batch->id,
                    'batch_name' => $batch->name,
                    'reason' => $exception->getMessage(),
                ];
            }
        }

        return compact('definition', 'rows', 'blocked', 'overlapSkipped');
    }

    public function apply(string $region, string $transition, ?CarbonImmutable $now = null): BatchDstAdjustmentRun
    {
        $definition = $this->transition($region, $transition);
        $now = $now ?: CarbonImmutable::now(config('batch_dst.timezone'));

        if ($now->toDateString() !== $definition['date']) {
            throw new RuntimeException(
                "Date protection blocked {$definition['key']}: today is {$now->toDateString()}, expected {$definition['date']}."
            );
        }

        $lock = Cache::lock('batch-dst-adjustment', 900);
        if (! $lock->get()) {
            throw new RuntimeException('Another DST adjustment or rollback is already running.');
        }

        try {
            if (BatchDstAdjustmentRun::where('transition_key', $definition['key'])->exists()) {
                throw new RuntimeException("Transition {$definition['key']} has already been recorded and cannot run twice.");
            }

            $preview = $this->preview($region, $transition);
            if ($preview['blocked']) {
                throw new RuntimeException('Transition blocked because one or more batches could not be transformed safely. Run --dry-run for details.');
            }

            return DB::transaction(function () use ($definition, $preview, $now) {
                $run = BatchDstAdjustmentRun::create([
                    'transition_key' => $definition['key'],
                    'region' => $definition['region'],
                    'transition_date' => $definition['date'],
                    'minutes' => $definition['minutes'],
                    'status' => 'APPLIED',
                    'batch_count' => count($preview['rows']),
                    'applied_at' => $now,
                ]);

                foreach ($preview['rows'] as $row) {
                    BatchDstAdjustmentItem::create([
                        'run_id' => $run->id,
                        'batch_id' => $row['batch']->id,
                        'before_state' => $row['before'],
                        'after_state' => $row['after'],
                    ]);
                    $this->restoreState($row['after']);
                }

                return $run;
            }, 3);
        } finally {
            $lock->release();
        }
    }

    public function rollbackPreview(BatchDstAdjustmentRun $run): array
    {
        if ($run->status !== 'APPLIED') {
            throw new RuntimeException("Run {$run->id} is {$run->status}, not APPLIED.");
        }

        $conflicts = [];
        foreach ($run->items()->orderBy('id')->get() as $item) {
            $batch = Batch::find($item->batch_id);
            if (! $batch || $this->snapshot($batch) !== $item->after_state) {
                $conflicts[] = $item->batch_id;
            }
        }

        return ['run' => $run, 'count' => $run->items()->count(), 'conflicts' => $conflicts];
    }

    public function rollback(BatchDstAdjustmentRun $run): BatchDstAdjustmentRun
    {
        $lock = Cache::lock('batch-dst-adjustment', 900);
        if (! $lock->get()) {
            throw new RuntimeException('Another DST adjustment or rollback is already running.');
        }

        try {
            $preview = $this->rollbackPreview($run);
            if ($preview['conflicts']) {
                throw new RuntimeException('Rollback blocked because batches changed after this run: '.implode(', ', $preview['conflicts']));
            }

            return DB::transaction(function () use ($run) {
                foreach ($run->items()->orderByDesc('id')->get() as $item) {
                    $this->restoreState($item->before_state);
                }

                $run->update(['status' => 'ROLLED_BACK', 'rolled_back_at' => now()]);

                return $run->fresh();
            }, 3);
        } finally {
            $lock->release();
        }
    }

    private function candidateBatches(array $definition): Collection
    {
        return Batch::query()
            ->whereIn('status', config('batch_dst.eligible_batch_statuses'))
            ->where(function ($query) use ($definition) {
                foreach ($definition['countries'] as $country) {
                    $query->orWhereJsonContains('country', $country);
                }
            })
            ->orderBy('id')
            ->get();
    }

    private function owningRegion(array $countries): ?string
    {
        $countries = array_map(fn ($country) => strtoupper(trim((string) $country)), $countries);

        foreach (config('batch_dst.region_priority') as $region) {
            $targets = config("batch_dst.regions.{$region}.countries", []);
            if (array_intersect($countries, $targets)) {
                return $region;
            }
        }

        return null;
    }

    private function snapshot(Batch $batch): array
    {
        $schedules = $batch->batchSchedules()
            ->whereIn('status', config('batch_dst.eligible_schedule_statuses'))
            ->orderBy('id')
            ->get();

        return [
            'batch_id' => $batch->id,
            'name' => $batch->name,
            'schedules' => $schedules->map(fn ($schedule) => [
                'id' => $schedule->id,
                'weekday' => $schedule->weekday,
                'from_time' => $schedule->from_time,
                'to_time' => $schedule->to_time,
            ])->values()->all(),
        ];
    }

    private function shiftedState(array $state, int $minutes): array
    {
        if (! $state['schedules']) {
            throw new RuntimeException('No active schedules.');
        }

        $startTimes = collect($state['schedules'])->pluck('from_time')->unique();
        if ($startTimes->count() !== 1) {
            throw new RuntimeException('Active schedules have different start times.');
        }

        $shifted = $state;
        $shifted['schedules'] = collect($state['schedules'])
            ->map(fn (array $schedule) => $this->shiftSchedule($schedule, $minutes))
            ->all();

        $oldCode = $this->weekdayCode($state['schedules']);
        $newCode = $this->weekdayCode($shifted['schedules']);
        $name = (string) $state['name'];

        if ($oldCode !== $newCode) {
            $pattern = '/(?<![A-Z])'.preg_quote($oldCode, '/').'(?![A-Z])/i';
            $name = preg_replace($pattern, $newCode, $name, 1, $count);
            if ($count !== 1) {
                throw new RuntimeException("Batch name does not contain expected weekday code {$oldCode}.");
            }
        }

        if (! preg_match(self::TIME_PATTERN, $name, $match)) {
            throw new RuntimeException('Batch name does not contain a supported AM/PM time.');
        }

        $newStart = CarbonImmutable::createFromFormat('H:i:s', $shifted['schedules'][0]['from_time']);
        $replacement = $this->formatNameTime($newStart, $match);
        $shifted['name'] = preg_replace(self::TIME_PATTERN, $replacement, $name, 1);

        return $shifted;
    }

    private function shiftSchedule(array $schedule, int $minutes): array
    {
        if (! isset(self::WEEKDAY_CODES[$schedule['weekday']])) {
            throw new RuntimeException("Unsupported weekday {$schedule['weekday']}.");
        }

        $weekdays = array_keys(self::WEEKDAY_CODES);
        $dayOffset = array_search($schedule['weekday'], $weekdays, true);
        $base = CarbonImmutable::parse('2024-01-01 00:00:00')->addDays($dayOffset);
        $from = $base->setTimeFromTimeString($schedule['from_time']);
        $to = $base->setTimeFromTimeString($schedule['to_time']);
        if ($to->lessThanOrEqualTo($from)) {
            $to = $to->addDay();
        }

        $newFrom = $from->addMinutes($minutes);
        $newTo = $to->addMinutes($minutes);

        $schedule['weekday'] = $newFrom->format('l');
        $schedule['from_time'] = $newFrom->format('H:i:s');
        $schedule['to_time'] = $newTo->format('H:i:s');

        return $schedule;
    }

    private function weekdayCode(array $schedules): string
    {
        $weekdayOrder = array_flip(array_keys(self::WEEKDAY_CODES));

        return collect($schedules)
            ->sortBy(fn (array $schedule) => $weekdayOrder[$schedule['weekday']] ?? PHP_INT_MAX)
            ->map(fn (array $schedule) => self::WEEKDAY_CODES[$schedule['weekday']] ?? '')
            ->implode('');
    }

    private function formatNameTime(CarbonImmutable $time, array $match): string
    {
        $separator = $match[2] ?? '';
        $space = $match[4] ?? '';
        $format = $separator ? 'g:i' : 'g';
        $formatted = $time->format($format);

        if ($separator === '.') {
            $formatted = str_replace(':', '.', $formatted);
        }

        return $formatted.$space.$time->format('A');
    }

    private function restoreState(array $state): void
    {
        DB::table('batchs')->where('id', $state['batch_id'])->update([
            'name' => $state['name'],
            'updated_at' => now(),
        ]);

        foreach ($state['schedules'] as $schedule) {
            DB::table('batch_schedules')->where('id', $schedule['id'])->update([
                'weekday' => $schedule['weekday'],
                'from_time' => $schedule['from_time'],
                'to_time' => $schedule['to_time'],
                'updated_at' => now(),
            ]);
        }
    }
}
