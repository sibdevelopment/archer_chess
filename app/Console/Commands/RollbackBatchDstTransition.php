<?php

namespace App\Console\Commands;

use App\Models\BatchDstAdjustmentRun;
use App\Services\BatchDstAdjustmentService;
use Illuminate\Console\Command;
use Throwable;

class RollbackBatchDstTransition extends Command
{
    protected $signature = 'batches:dst-rollback
        {run : Applied DST run ID}
        {--dry-run : Validate rollback without changing data}
        {--confirm : Restore the exact saved before-state}';

    protected $description = 'Preview or execute an exact DST run rollback';

    public function handle(BatchDstAdjustmentService $service): int
    {
        if ((bool) $this->option('dry-run') === (bool) $this->option('confirm')) {
            $this->error('Choose exactly one mode: --dry-run or --confirm.');

            return self::FAILURE;
        }

        try {
            $run = BatchDstAdjustmentRun::findOrFail($this->argument('run'));
            $preview = $service->rollbackPreview($run);

            if ($preview['conflicts']) {
                $this->error('Rollback conflicts: '.implode(', ', $preview['conflicts']));

                return self::FAILURE;
            }

            if ($this->option('dry-run')) {
                $this->info("Run {$run->id} can restore {$preview['count']} batches.");

                return self::SUCCESS;
            }

            $service->rollback($run);
            $this->info("Run {$run->id} rolled back successfully.");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
