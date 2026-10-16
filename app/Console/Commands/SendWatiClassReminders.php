<?php

namespace App\Console\Commands;

use App\Models\BatchClassSession;
use App\Models\StudentBatch;
use App\Models\WatiNotification;
use App\Services\WatiNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWatiClassReminders extends Command
{
    protected $signature = 'wati:send-class-reminders';

    protected $description = 'Queue WATI reminders for upcoming regular batch class occurrences';

    public function handle(WatiNotificationService $notifications): int
    {
        if (! $notifications->availableFor(WatiNotification::TYPE_CLASS_REMINDER)) {
            $this->line('WATI class reminders are disabled or not fully configured.');
            return Command::SUCCESS;
        }

        $timezone = config('app.timezone', 'Asia/Kolkata');
        $leadMinutes = max(1, (int) config('services.wati.class_reminder_minutes', 60));
        $windowMinutes = max(1, (int) config('services.wati.class_reminder_window', 5));
        $from = Carbon::now($timezone)->addMinutes($leadMinutes);
        $to = $from->copy()->addMinutes($windowMinutes);
        $counts = ['queued' => 0, 'existing' => 0, 'skipped' => 0];

        BatchClassSession::with(['batch', 'coach.user'])
            ->where('status', BatchClassSession::STATUS_READY)
            ->whereNotNull('join_url')
            ->whereBetween('scheduled_start_at', [$from, $to])
            ->chunkById(100, function ($sessions) use ($notifications, &$counts) {
                foreach ($sessions as $session) {
                    $studentBatches = StudentBatch::with('student')
                        ->where('batch_id', $session->batch_id)
                        ->eligibleOn($session->class_date)
                        ->whereHas('student', fn ($query) => $query
                            ->where('status', 'ACTIVE')
                            ->whereNotNull('mobile'))
                        ->get()
                        ->unique('student_id');

                    foreach ($studentBatches as $studentBatch) {
                        try {
                            $notification = $notifications->classReminder($session, $studentBatch->student);
                            if ($notification->wasRecentlyCreated && $notifications->dispatch($notification)) {
                                $counts['queued']++;
                            } else {
                                $counts['existing']++;
                            }
                        } catch (\Throwable $exception) {
                            $counts['skipped']++;
                            Log::warning('Unable to queue WATI class reminder.', [
                                'batch_class_session_id' => $session->id,
                                'student_id' => $studentBatch->student_id,
                                'message' => $exception->getMessage(),
                            ]);
                        }
                    }
                }
            });

        $this->info(sprintf(
            'WATI class reminders: %d queued, %d already recorded, %d skipped.',
            $counts['queued'],
            $counts['existing'],
            $counts['skipped']
        ));

        return Command::SUCCESS;
    }
}
