<?php

namespace App\Console\Commands;

use App\Models\DemoSession;
use Illuminate\Console\Command;
use App\Services\ZoomMeetingService;

class CreateDemoSessionLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:demo-session-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $demosessions = DemoSession::where('status', 'ACTIVE')
            ->whereNull('start_url')
            ->whereNull('join_url')
            ->orderBy('id', 'desc')
            ->get();

        $skipped = 0;
        $created = 0;

        foreach ($demosessions as $demosession) {
            $coach = $demosession->coach;

            if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id || !$coach->zoom_user_id) {
                $this->error("Zoom credentials missing for Demo Session ID: {$demosession->id}");
                $skipped++;
                continue;
            }

            try {
                $zoomMeetingService = new ZoomMeetingService(
                    $coach->zoom_api_key,
                    $coach->zoom_client_secret,
                    $coach->zoom_id
                );

                $meetingData = [
                    'title' => 'Course - '.$coach->user->first_name.' - '.$demosession->name,
                    'duration_in_minute' => 40,
                    'start_date_time' => now()->addMinutes(5)->toIso8601String(),
                ];
                        
                // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);
                $zoomResponse = $zoomMeetingService->createDemoSessionMeeting($meetingData, $coach->zoom_user_id);

                if (!empty($zoomResponse['start_url']) && !empty($zoomResponse['join_url'])) {
                    $demosession->start_url = $zoomResponse['start_url'];
                    $demosession->join_url = $zoomResponse['join_url'];
                    $demosession->zoom_meeting_id = $zoomResponse['id'] ?? null;
                    $demosession->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
                    $demosession->save();

                    $this->info("Meeting created for Demo Session ID {$demosession->id}");
                    $created++;
                } else {
                    $this->warn("Zoom returned empty URLs for Demo Session ID {$demosession->id}");
                }
            } catch (\Exception $e) {
                $this->error("Failed for Demo Session ID {$demosession->id}: " . $e->getMessage());
            }
        }

        $this->info("Summary: Created = $created, Skipped = $skipped, Total = " . $demosessions->count());
        return Command::SUCCESS;
    }
}
