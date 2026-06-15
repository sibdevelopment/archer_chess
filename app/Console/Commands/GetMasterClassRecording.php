<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Masterclass;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Services\ZoomMeetingService;

class GetMasterClassRecording extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:masterclass-recording';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    
    public function handle()
    {
        $todaysDate = date('Y-m-d');
        $masterclasses = Masterclass::whereDate('date', $todaysDate)->get();
        if ($masterclasses->isEmpty()) {
            $this->info("No masterclasses found for today.");
            return 0;
        }

        foreach ($masterclasses as $masterclass) {
            $coach = $masterclass->coach;
            if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
                $this->error("Coach Zoom credentials not set for Masterclass {$masterclass->id}");
                continue;
            }

            $latestCoachAttendance = CoachAttendance::where('coach_id', $coach->id)
                ->where('type', 'Masterclass')
                ->whereDate('date', $todaysDate)
                ->latest()
                ->first();
                
            if ($latestCoachAttendance && empty($latestCoachAttendance->recording_link)) {
                try {
                    $zoomMeetingService = new ZoomMeetingService(
                        $coach->zoom_api_key,
                        $coach->zoom_client_secret,
                        $coach->zoom_id
                    );

                    $recordingDataList = $zoomMeetingService->getRecordingLinks($coach->zoom_user_id);
                        // dd($recordingDataList);
                    if (empty($recordingDataList) || !is_array($recordingDataList)) {
                        $this->warn("No recording data found for Masterclass {$masterclass->id}");
                        continue;
                    }

                    // Format masterclass date and time
                    $expectedDate = Carbon::parse($masterclass->date)->format('Y-m-d');
                    $expectedTime = Carbon::parse($masterclass->time)->format('H:i');

                    $recordingFound = false;

                    foreach ($recordingDataList as $recordingData) {
                        if (empty($recordingData['start_time']) || empty($recordingData['play_url'])) {
                            continue;
                        }

                        $start = Carbon::parse($recordingData['start_time'])->timezone('Asia/Kolkata');
                        $recordingDate = $start->format('Y-m-d');
                        $recordingTime = $start->format('H:i');

                        $this->info("Checking recording:  $recordingTime vs  $expectedTime");

                        $isDateMatch = $recordingDate === $expectedDate;
                        $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($expectedTime))) <= 15;

                        if ($isDateMatch && $isTimeMatch) {
                            $latestCoachAttendance->recording_link = $recordingData['play_url'];
                            $latestCoachAttendance->save();

                            $this->info("Recording link saved for Masterclass {$masterclass->id}");
                            $recordingFound = true;
                            break;
                        }
                    }


                    if (!$recordingFound) {
                        $this->warn("No matching recording time found for Masterclass {$masterclass->id}");
                    }

                } catch (\Exception $e) {
                    $this->error("Zoom API error for Masterclass {$masterclass->id}: " . $e->getMessage());
                }
            }

        }
    }
}
