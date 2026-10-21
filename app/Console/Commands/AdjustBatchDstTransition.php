<?php

namespace App\Console\Commands;

use App\Services\BatchDstAdjustmentService;
use Illuminate\Console\Command;
use Throwable;

class AdjustBatchDstTransition extends Command
{
    protected $signature = 'batches:dst-adjust
        {region : Region key from config/batch_dst.php}
        {transition : Transition key such as 2026-autumn}
        {--dry-run : Preview without changing data}
        {--confirm : Apply on the configured transition date}';

    protected $description = 'Preview or apply a protected regional DST batch adjustment';

    public function handle(BatchDstAdjustmentService $service): int
    {
        if ((bool) $this->option('dry-run') === (bool) $this->option('confirm')) {
            $this->error('Choose exactly one mode: --dry-run or --confirm.');

            return self::FAILURE;
        }

        try {
            if ($this->option('dry-run')) {
                $preview = $service->preview($this->argument('region'), $this->argument('transition'));
                $definition = $preview['definition'];
                $this->info("{$definition['label']} | {$definition['date']} | {$definition['minutes']} minutes");
                $this->table(
                    ['Batch ID', 'Current name', 'Proposed name', 'Current schedules', 'Proposed schedules'],
                    collect($preview['rows'])->map(fn ($row) => [
                        $row['batch']->id,
                        $row['before']['name'],
                        $row['after']['name'],
                        $this->scheduleSummary($row['before']['schedules']),
                        $this->scheduleSummary($row['after']['schedules']),
                    ])->all()
                );

                foreach ($preview['blocked'] as $blocked) {
                    $this->error("BLOCKED {$blocked['batch_id']} {$blocked['batch_name']}: {$blocked['reason']}");
                }
                foreach ($preview['overlapSkipped'] as $skipped) {
                    $this->warn("OVERLAP {$skipped['batch_id']} owned by {$skipped['owner']}");
                }

                $this->line(sprintf(
                    'Ready: %d | Blocked: %d | Overlap skipped: %d',
                    count($preview['rows']), count($preview['blocked']), count($preview['overlapSkipped'])
                ));

                return $preview['blocked'] ? self::FAILURE : self::SUCCESS;
            }

            $run = $service->apply($this->argument('region'), $this->argument('transition'));
            $this->info("Applied run {$run->id} to {$run->batch_count} batches.");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function scheduleSummary(array $schedules): string
    {
        return collect($schedules)
            ->map(fn ($schedule) => "{$schedule['weekday']} {$schedule['from_time']}-{$schedule['to_time']}")
            ->implode('; ');
    }
}
