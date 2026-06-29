<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Batch;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Mail;

class BatchNotMarkedAttendance extends Command
{
    protected $signature = 'BatchAttendance:not-marked-attendance';
    protected $description = 'Saving student data if coach did not submit the data after completing the batch in one hour.';

    public function handle()
    {
        //dd(11);

        // Set the timezone to IST
        $now = Carbon::now('Asia/Kolkata');
        $oneHourAgo = $now->subHour()->format('H:i:s');
        $todayDate = $now->toDateString();
        $todayWeekday = $now->format('l'); // Get the full name of the weekday
    
        $batches = Batch::with([
            'batchSchedules' => function ($query) use ($todayWeekday, $oneHourAgo) {
                $query->where('weekday', $todayWeekday)
                      ->where('to_time', '<=', $oneHourAgo);
            },
            'studentBatches' => function ($query) use ($todayDate) {
                $query->eligibleOn($todayDate)->with('student');
            },
            'coach',
            'parent',
        ])
        ->where('status', 'ACTIVE')
        ->whereHas('batchSchedules', function ($query) use ($todayWeekday, $oneHourAgo) {
            $query->where('weekday', $todayWeekday)
                  ->where('to_time', '<=', $oneHourAgo);
        })
        ->get();
    
        $batches->each(function ($batch) use ($now, $todayDate) {
            $batchId = $batch->id;
            $coachId = $batch->coach_id;

            if ($batch->studentBatches->isEmpty()) {
                return;
            }
    
            // Fetch additional data for each batch
            $batchLevel = optional($batch->studentBatches->first())->level;
            $batchStartDate = optional($batch->studentBatches->first())->start_date;
            $batchEndDate = optional($batch->studentBatches->first())->end_date;
            $coachAttendance = CoachAttendance::where('batch_id', $batchId)
                ->where('coach_id', $coachId)
                ->whereDate('date', $todayDate)
                ->first();
            $latestCompletedSession = CoachAttendance::where('batch_id', $batchId)
                ->where('coach_id', $coachId)
                ->where('status', 'COMPLETED')
                ->orderByDesc('id')
                ->first();
            $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
            $studentAttendances = StudentAttendance::with(['student', 'batch'])
                ->whereHas('batch', function ($query) use ($batchId) {
                    $query->where('batch_id', $batchId);
                })
                ->whereDate('date', $todayDate)
                ->get();
            $activeSlot = $batch->batchSchedules->firstWhere('status', 'ACTIVE');
            $fromTime = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
            $toTime = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;
    
            // Add the additional data to the batch object
            $batch->batchLevel = $batchLevel;
            $batch->batchStartDate = $batchStartDate;
            $batch->batchEndDate = $batchEndDate;
            $batch->coachAttendance = $coachAttendance;
            $batch->totalSessionsCompleted = $totalSessionsCompleted;
            $batch->studentAttendances = $studentAttendances;
            $batch->fromTime = $fromTime;
            $batch->toTime = $toTime;
    
            // Check if coach attendance is submitted for this batch
            if (is_null($coachAttendance)) {
                // Automatically submit coach attendance with status 'NOTMARKED'
                $coachAttendance = new CoachAttendance();
                $coachAttendance->coach_id = $coachId;
                $coachAttendance->type = 'BATCH';
                $coachAttendance->batch_id = $batchId;
                $coachAttendance->date = $todayDate;
                $coachAttendance->time = $now->format('H:i:s');
                $coachAttendance->status = 'NOTMARKED';
                $coachAttendance->number_of_batch_sessions = $totalSessionsCompleted + 1;
                $coachAttendance->save();
    
                // Automatically submit student attendance with status 'NOTMARKED'
                $batch->studentBatches->each(function ($studentBatch) use ($batchId, $todayDate, $now) {
                    StudentAttendance::create([
                        'batch_id' => $batchId,
                        'student_id' => $studentBatch->student->id,
                        'date' => $todayDate,
                        'time' => $now->format('H:i:s'),
                        'status' => 'NOTMARKED',
                        'type' => 'BATCH',
                        'coach_id' => $studentBatch->batch->coach_id,
                        'number_of_batch_sessions' => $studentBatch->attendance ? $studentBatch->attendance->number_of_batch_sessions + 1 : 1,
                    ]);
                });
            }
    
            // Add student attendance data to each student batch
            $batch->studentBatches->each(function ($studentBatch) use ($studentAttendances) {
                $studentBatch->attendance = $studentAttendances->firstWhere('student_id', $studentBatch->student->id);
            });
        });
    }
}
