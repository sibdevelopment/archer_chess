<?php

namespace App\Console\Commands;

use App\Services\BatchClassSessionService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrepareBatchClassSessions extends Command
{
    protected $signature = 'batch-sessions:prepare {--hours=24 : How far ahead to create regular batch meetings}';

    protected $description = 'Create and reconcile per-occurrence Zoom meetings for regular batch classes';

    public function handle(BatchClassSessionService $sessions): int
    {
        $hours = max(1, min(168, (int) $this->option('hours')));
        $from = Carbon::now(config('app.timezone'))->subMinutes(15);
        $to = Carbon::now(config('app.timezone'))->addHours($hours);
        $counts = $sessions->reconcile($from, $to);

        $this->info(sprintf(
            'Batch sessions prepared: %d ready, %d failed, %d blocked.',
            $counts['ready'],
            $counts['failed'],
            $counts['blocked']
        ));

        return $counts['failed'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
