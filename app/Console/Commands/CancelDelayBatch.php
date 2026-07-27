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

class CancelDelayBatch extends Command
{
    protected $signature = 'cancel:delay-batch';
    protected $description = 'Track delayed batches and cancel if no coach attendance after the allowed delay from start time';

    public function handle()
    {
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
            
            $coverupExists = \App\Models\Coverupclass::where('batchschedule_id', $schedule->id)
                ->where('date', $date)
                ->exists();

            if ($coverupExists) {
                $this->info("Skipped batch {$schedule->batch_id} due to cover-up class.");
                continue;
            }

            $coachAttendanceExists = CoachAttendance::where('batch_id', $batchId)
                ->whereDate('date', $date)
                ->exists();

            $batch = null;

            if (! $coachAttendanceExists && $now->greaterThan($lateTime)) {
                $batch = Batch::find($batchId);

                if (! $batch) {
                    continue;
                }

                DelayedBatch::updateOrCreate(
                    [
                        'batch_id' => $batchId,
                        'date' => $date,
                    ],
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
                        'canceled_date' => null,
                        'canceled_time' => null,
                    ]
                );
            }

            if ($now->greaterThanOrEqualTo($cutoffTime)) {
                $studentAttendanceExists = StudentAttendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->exists();

                if (! $studentAttendanceExists || ! $coachAttendanceExists) {
                    $batch = $batch ?: Batch::find($batchId);

                    if (! $batch) {
                        continue;
                    }

                    $batchSchedules = BatchSchedule::where('batch_id', $batchId)->get();
                    $scheduledDays = $batchSchedules->pluck('weekday')->map(fn($day) => strtolower($day))->toArray();

                    $batchEndDate = Carbon::parse($batch->end_date);
                    $nextScheduledDay = null;
                    foreach ($scheduledDays as $day) {
                        $dayDifference = (Carbon::parse($day)->dayOfWeek - $batchEndDate->dayOfWeek + 7) % 7;
                        if ($dayDifference > 0) {
                            $nextScheduledDay = $batchEndDate->copy()->addDays($dayDifference);
                            break;
                        }
                    }
                    if (! $nextScheduledDay) {
                        $nextScheduledDay = $batchEndDate->copy()->addDays((Carbon::parse($scheduledDays[0])->dayOfWeek - $batchEndDate->dayOfWeek + 7) % 7);
                    }
                    $batch->end_date = $nextScheduledDay->toDateString();
                    $batch->save();

                    $studentIds = StudentBatch::where('batch_id', $batchId)->eligibleOn($date)->pluck('student_id');

                    if (!$studentAttendanceExists) {
                        foreach ($studentIds as $studentId) {
                            $studentAttendance = new StudentAttendance();
                            $studentAttendance->student_id = $studentId;
                            $studentAttendance->batch_id = $batchId;
                            $studentAttendance->level_id = $batch->level_id;
                            $studentAttendance->date = $date;
                            $studentAttendance->status = 'CANCELLED';
                            $studentAttendance->remark = 'Batch Cancelled';
                            $studentAttendance->type = $schedule->type ?? null;
                            $studentAttendance->coach_id = $schedule->coach_id;
                            $studentAttendance->homework_link = '';
                            $studentAttendance->recording_link = '';
                            $studentAttendance->chapter_name = '';
                            $studentAttendance->number_of_batch_sessions = $schedule->number_of_batch_sessions ?? 0;
                            $studentAttendance->save();

                            // Update StudentBatch end_date
                            $studentBatch = StudentBatch::where('student_id', $studentId)
                                ->where('batch_id', $batchId)
                                ->eligibleOn($date)
                                ->first();

                            if ($studentBatch) {
                                $studentBatch->end_date = $batch->end_date;
                                $studentBatch->save();
                            }

                            // Update StudentFee end_date
                            $studentLatestFee = StudentFee::where('student_id', $studentId)->orderBy('id', 'desc')->first();
                            if ($studentLatestFee) {
                                $feeEndDate = Carbon::parse($studentLatestFee->end_date);
                                $nextFeeDate = null;
                                foreach ($scheduledDays as $day) {
                                    $dayDiff = (Carbon::parse($day)->dayOfWeek - $feeEndDate->dayOfWeek + 7) % 7;
                                    if ($dayDiff > 0) {
                                        $nextFeeDate = $feeEndDate->copy()->addDays($dayDiff);
                                        break;
                                    }
                                }
                                if (! $nextFeeDate) {
                                    $nextFeeDate = $feeEndDate->copy()->addDays(
                                        (Carbon::parse($scheduledDays[0])->dayOfWeek - $feeEndDate->dayOfWeek + 7) % 7
                                    );
                                }
                                $studentLatestFee->end_date = $nextFeeDate->toDateString();
                                $studentLatestFee->save();
                            }
                        }
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

                        DelayedBatch::updateOrCreate(
                            [
                                'batch_id' => $batchId,
                                'date' => $date,
                            ],
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
