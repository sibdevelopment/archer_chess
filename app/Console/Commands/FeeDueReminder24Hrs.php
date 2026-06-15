<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\BatchSchedule;
use Illuminate\Console\Command;
use App\Mail\Before24HrFeesDueMail;
use Illuminate\Support\Facades\Mail;


class FeeDueReminder24Hrs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:fee-due-reminder-24hrs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send fee due reminder to students 24 hours before due date at 05:00 PM at IST';

    public function handle()
    {
        $date = Carbon::tomorrow('Asia/Kolkata')->toDateString(); 

        $tomorrowWeekday = Carbon::tomorrow('Asia/Kolkata')->format('l');

        $students = Student::where('status', 'FEESDUE')->pluck('id');

        foreach ($students as $studentId) {
            $studentBatch = StudentBatch::where('student_id', 
            $studentId)
                ->latest('id')
                ->with(['batch.batchschedules'])
                ->first();

            if (!$studentBatch || !$studentBatch->batch) {
                continue;
            }

            $batch = $studentBatch->batch;

            // Ensure batch is ACTIVE
            if ($batch->status !== 'ACTIVE') {
                continue;
            }

            // Check if batch has a class tomorrow
            $hasTomorrowClass = $batch->batchschedules->contains(function ($schedule) use ($tomorrowWeekday) {
                return $schedule->weekday === $tomorrowWeekday && $schedule->status === 'ACTIVE';
            });
            if (!$hasTomorrowClass) {
                continue;
            }

            $student = Student::find($studentId);
            // Send email
            if($student && $student->email){
                Mail::to($student->email)->send(new Before24HrFeesDueMail($student, $date));
            }
        }


    }
}


