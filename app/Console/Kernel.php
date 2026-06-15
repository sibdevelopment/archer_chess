<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\Email\Reminder\MasterclassMail::class,
        \App\Console\Commands\Email\Reminder\DemoClassMail::class,
        \App\Console\Commands\Email\Reminder\TournamentMail::class,
    ];
        
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         // CoverUpClass Recording
        $schedule->command('get:coverupclass-recording')->hourly();

        // MasterClass Recording
        $schedule->command('get:masterclass-recording')->hourly();

        // Batch Attendance
        $schedule->command('get:meeting-recordings')->hourly();

        // Demo Recording
        $schedule->command('get:demo-recordings')->hourly();

        // Cancel Delay Class
        $schedule->command('cancel:delay-batch')->everyTenMinutes();

        $schedule->command('masterclass:reminder')->hourly();

        $schedule->command('tournament:reminder')->hourly();

        $schedule->command('check:payment')->everyMinute();


        // Reminder Emails
        // $schedule->command('masterclass:reminder')->hourly();
        // $schedule->command('demo:complete')->hourly();

        // $schedule->command('inspire')->hourly();
        // $schedule->command('BatchAttendance:not-marked-attendance')->hourly();

        // OTHER
        $schedule->command('check:fess-due-students')->dailyAt('00:30');

        // USA CANADA
        $schedule->command('set:fess-due-in-usa-canada')->dailyAt('21:05');

        // UK
        $schedule->command('set:fess-due-in-uk')->dailyAt('12:00');


        // OTHER 24Hrs REminder
        // $schedule->command('reminder:feesdue-24hr-students')->hourly();

        // // USA CANADA 24Hrs REminder
        // $schedule->command('reminder:feesdue-24hr-usa-canada')->hourly();

        // // UK 24Hrs REminder
        // $schedule->command('reminder:feesdue-24hr-uk')->hourly();

        // OTHER 1Hour Reminder
        // $schedule->command('set:reminder-onehour-other-countries')->everyTenMinutes();

        // // USA CANADA 1Hour Reminder
        // $schedule->command('set:onehour-reminder-in-usa-canada')->everyTenMinutes();
        
        // // UK 1Hour Reminder
        // $schedule->command('set:onehour-reminder-in-uk')->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
