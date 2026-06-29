<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\CoachAvailability;
use App\Models\Coverupclass;
use App\Models\DemoSession;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class CoachAvailabilityService
{
    public function schedulesFromRequest(array $weekdays, array $fromTimes, array $toTimes): array
    {
        $schedules = [];

        foreach ($weekdays as $key => $weekday) {
            if (empty($weekday) || empty($fromTimes[$key]) || empty($toTimes[$key])) {
                continue;
            }

            $schedules[] = [
                'weekday' => $weekday,
                'from_time' => $this->normalizeTime($fromTimes[$key]),
                'to_time' => $this->normalizeTime($toTimes[$key]),
            ];
        }

        return $schedules;
    }

    public function availableCoachesForRawBatch(array $countries, array $schedules, ?int $currentBatchId = null): Collection
    {
        return Coach::where('status', 'ACTIVE')
            ->with('user')
            ->get()
            ->filter(function (Coach $coach) use ($countries, $schedules, $currentBatchId) {
                return $this->coachMatchesAnyCountry($coach, $countries)
                    && $this->validateRawBatchCoach($coach->id, $countries, $schedules, $currentBatchId)['ok'];
            })
            ->values();
    }

    public function validateRawBatchCoach(int $coachId, array $countries, array $schedules, ?int $currentBatchId = null): array
    {
        $coach = Coach::find($coachId);
        if (!$coach || $coach->status !== 'ACTIVE') {
            return $this->blocked('Selected coach is not active.');
        }

        if (!$this->coachMatchesAnyCountry($coach, $countries)) {
            return $this->blocked('Selected coach is not available for the selected country.');
        }

        foreach ($schedules as $schedule) {
            if (!$this->coachHasBaseAvailability($coachId, $schedule['weekday'], $schedule['from_time'], $schedule['to_time'])) {
                return $this->blocked("Selected coach is not available on {$schedule['weekday']} {$schedule['from_time']} - {$schedule['to_time']}.");
            }

            $conflict = $this->realBatchConflictByWeekday(
                $coachId,
                $schedule['weekday'],
                $schedule['from_time'],
                $schedule['to_time'],
                null,
                $currentBatchId
            );

            if ($conflict) {
                return $this->blocked("Coach already has reserved batch {$conflict->batch->name} on {$schedule['weekday']} {$conflict->from_time} - {$conflict->to_time}.");
            }
        }

        return $this->ok();
    }

    public function validateCoachForBatchAssignment(int $coachId, array $schedules, string $startDate, string $endDate, ?int $currentBatchId = null, array $countries = []): array
    {
        $coach = Coach::find($coachId);
        if (!$coach || $coach->status !== 'ACTIVE') {
            return $this->blocked('Selected coach is not active.');
        }

        if (!empty($countries) && !$this->coachMatchesAnyCountry($coach, $countries)) {
            return $this->blocked('Selected coach is not available for the selected country.');
        }

        $period = CarbonPeriod::create(Carbon::parse($startDate), Carbon::parse($endDate));

        foreach ($period as $date) {
            $weekday = $date->format('l');

            foreach ($schedules as $schedule) {
                if ($schedule['weekday'] !== $weekday) {
                    continue;
                }

                $validation = $this->validateCoachForSingleEvent(
                    $coachId,
                    $date->toDateString(),
                    $schedule['from_time'],
                    $schedule['to_time'],
                    [],
                    'batch',
                    $currentBatchId,
                    false
                );

                if (!$validation['ok']) {
                    return $validation;
                }
            }
        }

        return $this->ok();
    }

    public function validateCoachForSingleEvent(
        int $coachId,
        string $date,
        string $fromTime,
        string $toTime,
        array $countries = [],
        ?string $ignoreType = null,
        ?int $ignoreId = null,
        bool $useMidJoinerWindowForBatchConflict = true
    ): array {
        $date = Carbon::parse($date)->toDateString();
        $weekday = Carbon::parse($date)->format('l');
        $fromTime = $this->normalizeTime($fromTime);
        $toTime = $this->normalizeTime($toTime);

        $coach = Coach::find($coachId);
        if (!$coach || $coach->status !== 'ACTIVE') {
            return $this->blocked('Selected coach is not active.');
        }

        if (!empty($countries) && !$this->coachMatchesAnyCountry($coach, $countries)) {
            return $this->blocked('Selected coach is not available for the selected country.');
        }

        if (!$this->coachHasBaseAvailability($coachId, $weekday, $fromTime, $toTime)) {
            return $this->blocked("Selected coach is not available on {$weekday} {$fromTime} - {$toTime}.");
        }

        $batchConflict = $this->realBatchConflictByWeekday(
            $coachId,
            $weekday,
            $fromTime,
            $toTime,
            $date,
            $ignoreType === 'batch' ? $ignoreId : null,
            $useMidJoinerWindowForBatchConflict
        );
        if ($batchConflict) {
            return $this->blocked("Coach already has reserved batch {$batchConflict->batch->name} on {$date} {$batchConflict->from_time} - {$batchConflict->to_time}.");
        }

        $demoConflict = $this->demoConflict($coachId, $date, $fromTime, $toTime, $ignoreType === 'demo' ? $ignoreId : null);
        if ($demoConflict) {
            return $this->blocked("Coach already has demo class on {$date} {$demoConflict->slot}.");
        }

        $coverupConflict = $this->coverupConflict($coachId, $date, $fromTime, $toTime, $ignoreType === 'coverup' ? $ignoreId : null);
        if ($coverupConflict && $coverupConflict->batchSchedule) {
            return $this->blocked("Coach already has coverup class on {$date} {$coverupConflict->batchSchedule->from_time} - {$coverupConflict->batchSchedule->to_time}.");
        }

        return $this->ok();
    }

    public function parseSlot(?string $slot): ?array
    {
        if (!$slot || !str_contains($slot, ' - ')) {
            return null;
        }

        [$start, $end] = array_map('trim', explode(' - ', $slot));

        return [
            $this->normalizeTime($start),
            $this->normalizeTime($end),
        ];
    }

    public function coachHasBaseAvailability(int $coachId, string $weekday, string $fromTime, string $toTime): bool
    {
        $fromTime = $this->normalizeTime($fromTime);
        $toTime = $this->normalizeTime($toTime);

        return CoachAvailability::where('coach_id', $coachId)
            ->where('day_of_week', $weekday)
            ->where('status', 'ACTIVE')
            ->whereHas('periods', function ($query) use ($fromTime, $toTime) {
                $query->where('from_period', '<=', $fromTime)
                    ->where('to_period', '>=', $toTime);
            })
            ->exists();
    }

    public function coachMatchesAnyCountry(Coach $coach, array $countries): bool
    {
        $coachCountries = $this->normalizeCountries($coach->country);
        $batchCountries = $this->normalizeCountries($countries);

        if (empty($batchCountries)) {
            return true;
        }

        return count(array_intersect($coachCountries, $batchCountries)) > 0;
    }

    public function normalizeCountries($countries): array
    {
        if (is_string($countries)) {
            $decoded = json_decode($countries, true);
            $countries = is_array($decoded) ? $decoded : explode(',', $countries);
        }

        if (!is_array($countries)) {
            return [];
        }

        return collect($countries)
            ->filter()
            ->map(fn ($country) => strtoupper(trim($country)))
            ->unique()
            ->values()
            ->all();
    }

    private function realBatchConflictByWeekday(
        int $coachId,
        string $weekday,
        string $fromTime,
        string $toTime,
        ?string $date = null,
        ?int $currentBatchId = null,
        bool $useMidJoinerWindow = false
    ): ?BatchSchedule
    {
        return BatchSchedule::with('batch')
            ->where('weekday', $weekday)
            ->where('status', 'ACTIVE')
            ->whereTime('from_time', '<', $toTime)
            ->whereTime('to_time', '>', $fromTime)
            ->whereHas('batch', function ($query) use ($coachId, $date, $currentBatchId, $useMidJoinerWindow) {
                $query->where('coach_id', $coachId)
                    ->whereIn('status', ['ACTIVE', 'STANDBY'])
                    ->when($currentBatchId, fn ($q) => $q->where('id', '!=', $currentBatchId))
                    ->when($useMidJoinerWindow && $date, function ($q) use ($date) {
                        $q->whereHas('studentBatches', fn ($studentBatchQuery) => $studentBatchQuery->eligibleOn($date));
                    });
            })
            ->first();
    }

    private function demoConflict(int $coachId, string $date, string $fromTime, string $toTime, ?int $ignoreDemoId = null): ?DemoSession
    {
        return DemoSession::where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->whereDate('date', $date)
            ->when($ignoreDemoId, fn ($query) => $query->where('id', '!=', $ignoreDemoId))
            ->get()
            ->first(function (DemoSession $demo) use ($fromTime, $toTime) {
                $slot = $this->parseSlot($demo->slot);
                return $slot && $this->timeOverlaps($fromTime, $toTime, $slot[0], $slot[1]);
            });
    }

    private function coverupConflict(int $coachId, string $date, string $fromTime, string $toTime, ?int $ignoreCoverupId = null): ?Coverupclass
    {
        return Coverupclass::with('batchSchedule')
            ->where('new_coach_id', $coachId)
            ->whereDate('date', $date)
            ->when($ignoreCoverupId, fn ($query) => $query->where('id', '!=', $ignoreCoverupId))
            ->get()
            ->first(function (Coverupclass $coverup) use ($fromTime, $toTime) {
                if (!$coverup->batchSchedule) {
                    return false;
                }

                return $this->timeOverlaps($fromTime, $toTime, $coverup->batchSchedule->from_time, $coverup->batchSchedule->to_time);
            });
    }

    private function timeOverlaps(string $fromA, string $toA, string $fromB, string $toB): bool
    {
        return $this->normalizeTime($fromA) < $this->normalizeTime($toB)
            && $this->normalizeTime($toA) > $this->normalizeTime($fromB);
    }

    private function normalizeTime(string $time): string
    {
        return Carbon::parse($time)->format('H:i:s');
    }

    private function ok(): array
    {
        return ['ok' => true, 'message' => null];
    }

    private function blocked(string $message): array
    {
        return ['ok' => false, 'message' => $message];
    }
}
