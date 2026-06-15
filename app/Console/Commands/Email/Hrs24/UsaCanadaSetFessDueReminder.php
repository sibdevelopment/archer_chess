<?php

namespace App\Console\Commands\Email\Hrs24;


use App\Models\StudentFee;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Mail\Before24HrFeesDueMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsaCanadaSetFessDueReminder extends Command
{
    protected $signature = 'reminder:feesdue-24hr-usa-canada';

    protected $description = 'Set the fees due students and remove them from the batch.';

    // public function handle()
    // {
    //     $targetCountries = ['CANADA', 'USA'];
    //     $now = \Carbon\Carbon::now();

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

    //             // Calculate the final occurrence of that weekday on or before end_date
    //             $finalSessionDate = $scheduleEndDate->copy()->modify("last $weekday");

    //             // If end_date is same weekday, prefer it directly
    //             if ($scheduleEndDate->englishDayOfWeek === $weekday) {
    //                 $finalSessionDate = $scheduleEndDate->copy();
    //             }

    //             $finalSessionDateTime = Carbon::parse($finalSessionDate->format('Y-m-d') . ' ' . $schedule->from_time);
    //             $finalSessions[] = $finalSessionDateTime;
    //         }

    //         if (empty($finalSessions)) continue;

    //         // Get the latest session
    //         $lastBatchSession = collect($finalSessions)->sortDesc()->first();

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
        $targetCountries = ['CANADA', 'USA'];
        $now = \Carbon\Carbon::now();

        $students = Student::where('status', 'ACTIVE')
            ->whereIn('country', $targetCountries)
            ->get();

        foreach ($students as $student) {
            // Get the latest student fee end_date
            $studentFee = StudentFee::where('student_id', $student->id)
                ->latest('end_date')
                ->first();

            if (!$studentFee || !$studentFee->end_date) continue;

            $endDate = Carbon::parse($studentFee->end_date);
            $minutesDiff = $now->diffInMinutes($endDate, false);

            // Check if the fee is due in the next 24 hours (between 23h and 24h ahead)
            if ($minutesDiff >= 1380 && $minutesDiff <= 1440) {
                if (!empty($student->email)) {
                    // Mail::to($student->email)->send(new Before24HrFeesDueMail($student));
                }
            }
        }
    }





}
