<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Services\BatchStatusService;
use Illuminate\Console\Command;

class SyncEmptyBatchesToUpcoming extends Command
{
    protected $signature = 'batchs:sync-empty-to-upcoming {--dry-run : Show affected batches without updating them}';

    protected $description = 'Move active/standby batches with no active or fee-due students back to upcoming.';

    public function handle(BatchStatusService $batchStatusService): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $updated = 0;

        Batch::whereIn('status', ['ACTIVE', 'STANDBY'])
            ->orderBy('id')
            ->chunkById(100, function ($batches) use ($batchStatusService, $dryRun, &$updated) {
                foreach ($batches as $batch) {
                    if ($batchStatusService->hasActiveOrFeeDueStudents($batch->id)) {
                        continue;
                    }

                    $updated++;
                    $this->line(($dryRun ? '[DRY RUN] ' : '') . "Batch {$batch->id} {$batch->name} will move to UPCOMING.");

                    if (! $dryRun) {
                        $batch->status = 'UPCOMING';
                        $batch->save();
                    }
                }
            });

        $this->info("Empty batch sync finished. Batches moved to UPCOMING: {$updated}");

        return self::SUCCESS;
    }
}
