<?php

namespace App\Console\Commands\Email\Reminder;

use Carbon\Carbon;
use App\Models\DemoLead;
use App\Models\DemoSession;
use App\Mail\DemoCompleteMail;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Mail;

class DemoClassMail extends Command
{
    protected $signature = 'demo:complete {demo_id?}';
    protected $description = 'Send mail 40 minutes after demo is completed';

    public function handle()
    {
        $now = Carbon::now();
        $todays_date = Carbon::now()->format('Y-m-d');

        $demos = CoachAttendance::where('type', 'Demo')
            ->whereDate('date', $todays_date)
            ->when($this->argument('demo_id'), function ($query) {
                $query->where('id', $this->argument('demo_id'));
            })
            ->where('status', 'COMPLETED')
            ->get();
        
        foreach ($demos as $demo) {
            try {
                $demolead = DemoLead::where('id', $demo->demolead_id)->with('user')->first();
                $studentAttendance = StudentAttendance::where('demolead_id', $demolead->id)->first();
                if(!empty($demolead->user->email)) {
                    Mail::to($demolead->user->email)->send(new DemoCompleteMail($demolead, $studentAttendance));
                }
            } catch (\Exception $e) {
                $this->error("❌ Error sending mail for DemoLead ID {$demolead->id}: " . $e->getMessage());
            }
        }

        $this->info('Demo complete mail job finished.');
    }
}
