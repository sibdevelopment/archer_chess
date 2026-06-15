<?php

namespace App\Console\Commands\Email\Hrs24\Hrs3;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Mail\Before6HrFeesDueMail;
use Illuminate\Support\Facades\Mail;

class UsaCanadaSetFessDueReminder extends Command
{
    protected $signature = 'set:onehour-reminder-in-usa-canada';
    protected $description = 'Set the fees due students and send reminder mail 6 hours before their last session.';

    // public function handle()
    // {
    //     $now = Carbon::now();

    //     $students = Student::where('status', 'ACTIVE')
    //         ->whereIn('country', ['USA', 'CANADA'])
    //         ->get();

    //     foreach ($students as $student) {
    //         $studentBatch = StudentBatch::where('student_id', $student->id)
    //             ->where('status', 'ACTIVE')
    //             ->first();

            

    //         if (!$studentBatch || !$studentBatch->batch_id) {
    //             continue;
    //         }

    //         $schedules = BatchSchedule::where('batch_id', $studentBatch->batch_id)
    //             ->where('status', 'ACTIVE')
    //             ->whereNotNull('weekday')
    //             ->whereNotNull('end_date')
    //             ->get();

    //         if ($schedules->isEmpty()) {
    //             continue;
    //         }

    //         $finalSessions = [];

    //         foreach ($schedules as $schedule) {
    //             $endDate = Carbon::parse($schedule->end_date);
    //             $weekday = ucfirst(strtolower($schedule->weekday)); 

    //             $finalDate = $endDate->copy()->modify("last $weekday");

    //             if ($endDate->englishDayOfWeek === $weekday) {
    //                 $finalDate = $endDate->copy();
    //             }

    //             $finalSession = Carbon::parse($finalDate->format('Y-m-d') . ' ' . $schedule->from_time);
    //             $finalSessions[] = $finalSession;
    //         }


    //         if (empty($finalSessions)) {
    //             continue;
    //         }

    //         $lastSession = collect($finalSessions)->sortDesc()->first();

    //         $minutesDiff = $now->diffInMinutes($lastSession, false);

    //         if ($minutesDiff >= 355 && $minutesDiff <= 365) {
    //             if (!empty($student->email)) {
    //                 Mail::to($student->email)->send(new Before6HrFeesDueMail($student));
    //             }
    //         }
    //     }
    // }

    public function handle()
    {
        $now = Carbon::now();

        $students = Student::where('status', 'ACTIVE')
            ->whereIn('country', ['USA', 'CANADA'])
            ->get();

        foreach ($students as $student) {
            $studentFee = StudentFee::where('student_id', $student->id)
                ->where('status', 'ACTIVE')
                ->latest('end_date')
                ->first();

            if (!$studentFee || !$studentFee->end_date) {
                continue;
            }

            $targetTime = Carbon::parse($studentFee->end_date)->setTime(0, 0)->subHours(6);

            // Send email if now is within 10 minutes after the 6-hour-before mark
            $minutesDiff = $now->diffInMinutes($targetTime, false);

            if ($minutesDiff >= 0 && $minutesDiff <= 10) {
                if (!empty($student->email)) {
                    // Mail::to($student->email)->send(new Before6HrFeesDueMail($student));
                }
            }

        }
    }

}
