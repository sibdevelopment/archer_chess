<?php

namespace App\Console\Commands\Email\Reminder;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Masterclass;
use App\Mail\MasterclassMail as MasterclassMailTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;


class MasterclassMail extends Command
{
    protected $signature = 'masterclass:reminder';
    protected $description = 'Send email reminders to students 8 hours before a masterclass';

    public function handle()
    {
        $now = Carbon::now();
        $masterclasses = Masterclass::where('status', 'ACTIVE')
        ->where('is_reminder_sent', 'NO')
        ->get();
        
        foreach ($masterclasses as $masterclass) {

            $masterclassDateTime = Carbon::parse($masterclass->date . ' ' . $masterclass->time, 'Asia/Kolkata');

            $minutesDiff = $now->diffInMinutes($masterclassDateTime, false);

            if ($minutesDiff >= 150 && $minutesDiff <= 200) {
                // Get relevant students
                $students = Student::where(function ($query) use ($masterclass) {
                // Match country
                if (!empty($masterclass->country)) {
                    $query->whereIn('country', $masterclass->country);
                }

                // Match student_ids (if given)
                if (!empty($masterclass->student_ids)) {
                    $query->whereIn('id', $masterclass->student_ids);
                }

                // Match batch_ids (if given)
                if (!empty($masterclass->batch_ids)) {
                    $query->whereHas('studentBatches', function ($q) use ($masterclass) {
                        $q->whereIn('batch_id', $masterclass->batch_ids)
                        ->where('status', 'ACTIVE');
                    });
                }

                // Match level_ids (if given)
                if (!empty($masterclass->level_ids)) {
                    $query->whereHas('studentBatches', function ($q) use ($masterclass) {
                        $q->whereIn('level_id', $masterclass->level_ids)
                        ->where('status', 'ACTIVE');
                    });
                }
            })
            ->whereIn('status', ['ACTIVE', 'FEESDUE'])
            ->get();

                foreach ($students as $student) {
                    if (!empty($student->user->email)) {
                        Mail::to($student->user->email)->send(new MasterclassMailTemplate($student, $masterclass));
                    }
                }
            }
        }   

        $this->info('Masterclass reminder job completed.');
    }
}
