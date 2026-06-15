<?php

namespace App\Console\Commands\Email\Hrs24\Hrs3;

use App\Models\StudentFee;
use App\Models\Student;
use App\Mail\FeesDueMail;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Mail\Before6HrFeesDueMail;
use Illuminate\Support\Facades\Mail;

class FessDueReminderStudents extends Command
{
    protected $signature = 'set:reminder-onehour-other-countries';

    protected $description = 'Check the students from batch who have not paid the fees and remove them from the batch.';
 

    public function handle()
    {
        $now = Carbon::now();

        $students = Student::where('status', 'ACTIVE')
            ->whereNotIn('country', ['USA', 'CANADA', 'UK'])
            ->get();

        foreach ($students as $student) {
            $studentFee = StudentFee::where('student_id', $student->id)
                ->where('status', 'ACTIVE')
                ->latest('end_date')
                ->first();

            if (!$studentFee || !$studentFee->end_date) {
                continue;
            }

            // $targetTime = Carbon::parse($studentFee->end_date)->setTime(0, 0)->subHours(6);

            // $minutesDiff = $now->diffInMinutes($targetTime, false);

            // if ($minutesDiff >= 0 && $minutesDiff <= 10) {
            //     if (!empty($student->email)) {
            //         Mail::to($student->email)->send(new Before6HrFeesDueMail($student));
            //     }
            // }

            $targetTime = Carbon::parse($studentFee->end_date)->subHour();

            $minutesDiff = $now->diffInMinutes($targetTime, false);

            if ($minutesDiff >= 0 && $minutesDiff <= 10) {
                if (!empty($student->email)) {
                    // Mail::to($student->email)->send(new Before6HrFeesDueMail($student));
                }
            }

        }
    }



}
