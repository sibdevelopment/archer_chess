<?php

namespace App\Console\Commands\Email\Reminder;

use App\Models\Tournament;
use App\Mail\TournamentMail as TournamentMailTemplate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Services\StudentEventAudienceService;

class TournamentMail extends Command
{
    protected $signature = 'tournament:reminder';
    protected $description = 'Send email reminders to students 12 hours before a tournament';

    public function handle()
    {
        
        $now = Carbon::now();
        $tournaments = Tournament::where('status', 'ACTIVE')
        ->where('is_reminder_sent', 'NO')
        ->get();
        // dd($tournaments->toArray());
        
        foreach ($tournaments as $tournament) {

            $tournamentDateTime = Carbon::parse($tournament->date . ' ' . $tournament->time, 'Asia/Kolkata');

            $minutesDiff = $now->diffInMinutes($tournamentDateTime, false);

            if ($minutesDiff >= 150 && $minutesDiff <= 200) {
                $students = app(StudentEventAudienceService::class)
                    ->studentsForEvent($tournament, ['ACTIVE', 'FEESDUE']);
                foreach ($students as $student) {
                    if (!empty($student->user->email)) {
                        Mail::to($student->user->email)->send(new TournamentMailTemplate($student, $tournament));
                    }
                }
            }
        }   

        $this->info('Tournament reminder job completed.');
    }
}
