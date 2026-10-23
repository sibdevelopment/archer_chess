<?php

namespace App\Console\Commands;

use App\Models\BatchDstAdjustmentRun;
use App\Services\BatchDstAdjustmentService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Throwable;

class ProcessBatchDstTransitions extends Command
{
    protected $signature = 'batches:dst-process';

    protected $description = 'Apply DST transitions due today when automation is enabled';

    public function handle(BatchDstAdjustmentService $service): int
    {
        if (! config('batch_dst.automation_enabled')) {
            $this->line('Batch DST automation is disabled.');

            return self::SUCCESS;
        }

        $today = CarbonImmutable::now(config('batch_dst.timezone'))->toDateString();
        $failed = false;

        foreach (config('batch_dst.regions') as $region => $regionConfig) {
            foreach ($regionConfig['transitions'] as $transition => $definition) {
                if (! ($definition['automatic'] ?? false) || $definition['date'] !== $today) {
                    continue;
                }

                try {
                    $transitionKey = "{$region}:{$transition}";
                    if (BatchDstAdjustmentRun::where('transition_key', $transitionKey)->exists()) {
                        $this->line("Skipped {$transitionKey}; it is already recorded.");

                        continue;
                    }

                    $run = $service->apply($region, $transition);
                    $this->info("Applied {$run->transition_key} to {$run->batch_count} batches.");
                } catch (Throwable $exception) {
                    report($exception);
                    $this->error($exception->getMessage());
                    $failed = true;
                }
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
