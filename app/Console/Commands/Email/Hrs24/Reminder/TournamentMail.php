<?php

namespace App\Console\Commands\Email\Reminder;

use App\Models\Student;
use App\Models\Tournament;
use App\Mail\TournamentMail as TournamentMailTemplate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
                // Get relevant students
                $students = Student::where(function ($query) use ($tournament) {
                // Match country
                if (!empty($tournament->country)) {
                    $query->whereIn('country', $tournament->country);
                }

                // Match student_ids (if given)
                if (!empty($tournament->student_ids)) {
                    $query->whereIn('id', $tournament->student_ids);
                }

                // Match batch_ids (if given)
                if (!empty($tournament->batch_ids)) {
                    $query->whereHas('studentBatches', function ($q) use ($tournament) {
                        $q->whereIn('batch_id', $tournament->batch_ids)
                        ->where('status', 'ACTIVE');
                    });
                }

                // Match level_ids (if given)
                if (!empty($tournament->level_ids)) {
                    $query->whereHas('studentBatches', function ($q) use ($tournament) {
                        $q->whereIn('level_id', $tournament->level_ids)
                        ->where('status', 'ACTIVE');
                    });
                }
            })
            ->whereIn('status', ['ACTIVE', 'FEESDUE'])
            ->get();
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
