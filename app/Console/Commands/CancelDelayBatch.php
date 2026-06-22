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

class CancelDelayBatch extends Command
{
    protected $signature = 'cancel:delay-batch';
    protected $description = 'Check today\'s scheduled batches and cancel if no attendance after 10 minutes from start time';

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
            $fromTime = Carbon::parse($schedule->from_time);
            // $cutoffTime = $fromTime->copy()->addMinutes(10);
            $cutoffTime = $fromTime->copy()->addMinutes(8);

            $date = $now->toDateString();
            
            $coverupExists = \App\Models\Coverupclass::where('batchschedule_id', $schedule->id)
                ->where('date', $date)
                ->exists();

            if ($coverupExists) {
                $this->info("Skipped batch {$schedule->batch_id} due to cover-up class.");
                continue;
            }

            if ($now->greaterThanOrEqualTo($cutoffTime)) {
                $batchId = $schedule->batch_id;
                $date = $now->toDateString();

                $studentAttendanceExists = StudentAttendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->exists();

                $coachAttendanceExists = CoachAttendance::where('batch_id', $batchId)
                    ->whereDate('date', $date)
                    ->exists();

                if (! $studentAttendanceExists || ! $coachAttendanceExists) {
                    $batch = Batch::find($batchId);
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
                        CoachAttendance::create([
                            'coach_id' => $batch->coach_id,
                            'batch_id' => $batchId,
                            'type' => $schedule->type ?? null,
                            'level_id' => $schedule->level_id,
                            'date' => $date,
                            'status' => 'CANCELLED',
                        ]);
                        $this->info("Marked CANCELLED for coach in batch $batchId");
                    }
                }
            }
        }

        $this->info('Batch auto-cancellation and extension completed.');
    }

}
