<?php

namespace App\Console\Commands\Email\Hrs24;

use App\Models\Batch;
use App\Models\Student;
use App\Mail\FeesDueMail;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Mail\Before24HrFeesDueMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FessDueReminderStudents extends Command
{
    protected $signature = 'reminder:feesdue-24hr-students';

    protected $description = 'Check the students from batch who have not paid the fees and remove them from the batch.';
 
    public function handle()
    {   
        $excludedCountries = ['CANADA', 'USA', 'UK'];
        $now = Carbon::now();

        $students = Student::where('status', 'ACTIVE')
            ->whereNotIn('country', $excludedCountries)
            ->get();

        foreach ($students as $student) {
            $latestFee = StudentFee::where('student_id', $student->id)
                ->orderByDesc('end_date')
                ->first();

            if (!$latestFee || !$latestFee->end_date) {
                continue;
            }

            $feeEndDateTime = Carbon::parse($latestFee->end_date)->setTime(0, 0); // assume midnight end
            $minutesDiff = $now->diffInMinutes($feeEndDateTime, false);

            if ($minutesDiff >= 1380 && $minutesDiff <= 1440) {
                if (!empty($student->email)) {
                    // Mail::to($student->email)->send(new Before24HrFeesDueMail($student));
                }
            }
        }
    }

}
