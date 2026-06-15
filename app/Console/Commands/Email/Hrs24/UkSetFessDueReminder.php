<?php

namespace App\Console\Commands\Email\Hrs24;


use App\Models\StudentFee;
use App\Models\Student;
use App\Mail\FeesDueMail;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Mail\Before24HrFeesDueMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UkSetFessDueReminder extends Command
{
    protected $signature = 'reminder:feesdue-24hr-uk';

    protected $description = 'Set the fees due status for students in the UK';
    // public function handle()
    // {
    //     $targetCountries = ['UK']; // You can modify this list
    //     $now = Carbon::now();

    //     $students = Student::where('status', 'ACTIVE')
    //         ->whereIn('country', $targetCountries)
    //         ->get();

    //     foreach ($students as $student) {
    //         $studentBatch = StudentBatch::where('student_id', $student->id)
    //             ->where('status', 'ACTIVE')
    //             ->first();

    //         if (!$studentBatch || !$studentBatch->batch_id) continue;

    //         $schedules = BatchSchedule::where('batch_id', $studentBatch->batch_id)
    //             ->where('status', 'ACTIVE')
    //             ->whereNotNull('weekday')
    //             ->whereNotNull('end_date')
    //             ->get();

    //         $finalSessions = [];

    //         foreach ($schedules as $schedule) {
    //             $scheduleEndDate = Carbon::parse($schedule->end_date);
    //             $weekday = ucfirst(strtolower($schedule->weekday)); // e.g., "Monday"

    //             // Calculate the final occurrence of this weekday before or on the schedule's end date
    //             $finalSessionDate = $scheduleEndDate->copy()->modify("last $weekday");

    //             // If scheduleEndDate itself is the same weekday, use it
    //             if ($scheduleEndDate->englishDayOfWeek === $weekday) {
    //                 $finalSessionDate = $scheduleEndDate->copy();
    //             }

    //             // Combine with from_time
    //             $finalSessionDateTime = Carbon::parse($finalSessionDate->format('Y-m-d') . ' ' . $schedule->from_time);
    //             $finalSessions[] = $finalSessionDateTime;
    //         }

    //         if (empty($finalSessions)) continue;

    //         // Get the latest of all final session datetimes
    //         $lastBatchSession = collect($finalSessions)->sortDesc()->first();

    //         // Send reminder if we're exactly 24 hours before that session
    //         if ($lastBatchSession) {
    //             $minutesDiff = $now->diffInMinutes($lastBatchSession, false);
    //             if ($minutesDiff >= 1380 && $minutesDiff <= 1440) {
    //                 if (!empty($student->email)) {
    //                     Mail::to($student->email)->send(new Before24HrFeesDueMail($student));
    //                 }
    //             }
    //         }
    //     }
    // }

    public function handle()
    {
        $targetCountries = ['UK']; // You can modify this list
        $now = Carbon::now();

        $studentFees = StudentFee::with('student')
            ->whereHas('student', function ($query) use ($targetCountries) {
                $query->where('status', 'ACTIVE')
                    ->whereIn('country', $targetCountries);
            })
            ->where('status', 'ACTIVE')
            ->whereNotNull('end_date')
            ->get();

        foreach ($studentFees as $studentFee) {
            $endDate = Carbon::parse($studentFee->end_date);

            // Combine end_date with a default session time (e.g., 10:00 AM)
            $endDateTime = Carbon::parse($endDate->format('Y-m-d') . ' 10:00:00');

            $minutesDiff = $now->diffInMinutes($endDateTime, false);

            if ($minutesDiff >= 1380 && $minutesDiff <= 1440) {
                $student = $studentFee->student;
                // if ($student && !empty($student->email)) {
                //     Mail::to($student->email)->send(new Before24HrFeesDueMail($student));
                // }
            }
        }
    }

}



