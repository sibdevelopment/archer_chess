<?php

namespace App\Console\Commands\Email\Hrs24\Reminder;

use Carbon\Carbon;
use App\Models\Masterclass;
use App\Mail\MasterclassMail as MasterclassMailTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Services\StudentEventAudienceService;


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
                $students = app(StudentEventAudienceService::class)
                    ->studentsForEvent($masterclass, ['ACTIVE', 'FEESDUE']);

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
