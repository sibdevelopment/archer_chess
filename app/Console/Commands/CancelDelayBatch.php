<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\StudentBatch;
use App\Models\StudentFee;  
use App\Models\BatchSchedule;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Log;
use App\Models\DelayedBatch;
use App\Services\BatchOccurrenceService;

class CancelDelayBatch extends Command
{
    protected $signature = 'cancel:delay-batch';
    protected $description = 'Track delayed batches and cancel if no coach attendance after the allowed delay from start time';

    public function handle()
    {
        $occurrences = app(BatchOccurrenceService::class);
        $now = Carbon::now();
        $today = $now->format('l');

        Log::info("CancelDelayBatch command started at {$now->toDateTimeString()} for weekday: {$today}");
        // Get active batches scheduled for today
        $activeBatchIds = Batch::where('status', 'ACTIVE')
            ->whereDate('start_date', '<=', $now->toDateString())
            ->whereHas('batchSchedules', function ($query) use ($today) {
                $query->where('weekday', $today);
            })
            ->pluck('id');

        $schedules = BatchSchedule::whereIn('batch_id', $activeBatchIds)
            ->where('weekday', $today)
            ->get();

        foreach ($schedules as $schedule) {
            $date = $now->toDateString();
            $scheduledStart = Carbon::parse($date . ' ' . $schedule->from_time);
            $lateTime = $scheduledStart->copy()->addMinutes(3);
            $cutoffTime = $scheduledStart->copy()->addMinutes(8);
            $batchId = $schedule->batch_id;
            $delayedBatchKey = [
                'batch_id' => $batchId,
                'batchschedule_id' => $schedule->id,
                'date' => $date,
            ];
            
            $batch = Batch::find($batchId);

            if (! $batch) {
                continue;
            }

            $coverupExists = $occurrences->coverupForOccurrence($batchId, $schedule->id, $date) !== null;

            if ($coverupExists) {
                $this->info("Skipped batch {$schedule->batch_id} due to cover-up class.");
                continue;
            }

            if ($occurrences->holidayForBatch($batch, $date)) {
                $occurrences->markHolidayOccurrence($batch, $schedule, $date);
                $this->info("Marked HOLIDAY for batch {$schedule->batch_id}.");
                continue;
            }

            if ($occurrences->approvedLeaveForSchedule($batch->coach_id, $date, $schedule->from_time, $schedule->to_time)) {
                $this->info("Skipped batch {$schedule->batch_id} due to approved coach leave.");
                continue;
            }

            $coachAttendanceExists = CoachAttendance::where('batch_id', $batchId)
                ->whereDate('date', $date)
                ->exists();

            if (! $coachAttendanceExists && $now->greaterThan($lateTime)) {
                $cancelledPenaltyExists = DelayedBatch::where($delayedBatchKey)
                    ->where('penalty_type', 'CANCELLED')
                    ->exists();

                if (! $cancelledPenaltyExists) {
                    DelayedBatch::updateOrCreate(
                        $delayedBatchKey,
                        [
                            'coach_id' => $batch->coach_id,
                            'time' => $now->format('H:i:s'),
                            'batch_name' => $batch->name,
                            'country' => $batch->country,
                            'batch_status' => $batch->status,
                            'level_name' => optional($batch->level)->name,
                            'timeline' => sprintf(
                                'Scheduled start: %s | Marked late at: %s',
                                $scheduledStart->format('d-M-Y h:i:s A'),
                                $now->format('d-M-Y h:i:s A')
                            ),
                            'penalty_type' => 'LATE',
                            'fine_amount' => 150,
                            'fine_currency' => 'INR',
                            'late_popup_acknowledged_at' => null,
                            'canceled_date' => null,
                            'canceled_time' => null,
                        ]
                    );
                }
            }

            if ($now->greaterThanOrEqualTo($cutoffTime)) {
                $studentAttendanceExists = StudentAttendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->exists();

                if (! $studentAttendanceExists || ! $coachAttendanceExists) {
                    if (!$studentAttendanceExists) {
                        $occurrences->shiftCancelledOccurrence($batch, $schedule, $date, 'Batch Cancelled');
                        $this->info("Marked CANCELLED for students in batch $batchId");
                    }

                    if (! $coachAttendanceExists) {
                        $coachAttendance = CoachAttendance::create([
                            'coach_id' => $batch->coach_id,
                            'batch_id' => $batchId,
                            'type' => $schedule->type ?? null,
                            'level_id' => $schedule->level_id,
                            'date' => $date,
                            'status' => 'CANCELLED',
                        ]);

                        DelayedBatch::where($delayedBatchKey)
                            ->where('penalty_type', 'LATE')
                            ->delete();

                        DelayedBatch::updateOrCreate(
                            $delayedBatchKey,
                            [
                                'coach_id' => $batch->coach_id,
                                'coach_attendance_id' => $coachAttendance->id,
                                'time' => $now->format('H:i:s'),
                                'batch_name' => $batch->name,
                                'country' => $batch->country,
                                'batch_status' => $batch->status,
                                'level_name' => optional($batch->level)->name,
                                'timeline' => sprintf(
                                    'Scheduled start: %s | Auto-cancelled at: %s',
                                    $scheduledStart->format('d-M-Y h:i:s A'),
                                    $now->format('d-M-Y h:i:s A')
                                ),
                                'canceled_date' => $date,
                                'canceled_time' => $now->format('H:i:s'),
                                'penalty_type' => 'CANCELLED',
                                'fine_amount' => 350,
                                'fine_currency' => 'INR',
                                'late_popup_acknowledged_at' => null,
                            ]
                        );

                        $this->info("Marked CANCELLED for coach in batch $batchId");
                    }
                }
            }
        }

        $this->info('Batch auto-cancellation and extension completed.');
    }

}
