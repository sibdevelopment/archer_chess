<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BatchClassSessionService;
use Carbon\Carbon;

class CreateBatchMeetingLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:meeting-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare per-occurrence Zoom links for upcoming regular batch classes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(BatchClassSessionService $sessions)
    {
        $counts = $sessions->reconcile(
            Carbon::now(config('app.timezone'))->subMinutes(15),
            Carbon::now(config('app.timezone'))->addHours(24)
        );

        $this->info(sprintf(
            'Batch sessions prepared: %d ready, %d failed, %d blocked.',
            $counts['ready'],
            $counts['failed'],
            $counts['blocked']
        ));

        return $counts['failed'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}


