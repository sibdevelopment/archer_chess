<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Coach;
use App\Models\DemoSession;
use Illuminate\Console\Command;
use App\Services\ZoomMeetingService;

class CreateBatchMeetingLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:meeting-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Zoom meeting links for batch where the meeting link is not set';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $batches = Batch::where('status','!=', 'INACTIVE')
            ->whereNull('start_url')
            ->whereNull('join_url')
            ->get();

        foreach ($batches as $batch) {

            // $coach = Coach::where('user_id', 4825)->first();
            $coach = $batch->coach;

            if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id || !$coach->zoom_user_id) {
                $this->error("Zoom credentials missing for Batch ID: {$batch->id}");
                continue;
            }

            try {
                $zoomMeetingService = new ZoomMeetingService(
                    $coach->zoom_api_key,
                    $coach->zoom_client_secret,
                    $coach->zoom_id 
                );
                
                $meetingData = [
                    'title' => 'Course - '.$coach->user->first_name.' - '.$batch->name,
                    'duration_in_minute' => 40,
                    'start_date_time' => now()->addMinutes(5)->toIso8601String(),
                ];
                
                // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);

                $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

                if (!empty($zoomResponse['start_url']) && !empty($zoomResponse['join_url'])) {
                    $batch->start_url = $zoomResponse['start_url'];
                    $batch->join_url = $zoomResponse['join_url'];
                    $batch->zoom_meeting_id = $zoomResponse['id'] ?? null;
                    $batch->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
                    $batch->save();

                    $this->info("Meeting created for Batch ID {$batch->id}");
                } else {
                    $this->warn("Zoom returned empty URLs for Batch ID {$batch->id}");
                }
            } catch (\Exception $e) {
                $this->error("Failed for Batch ID {$batch->id}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;

    }
}


