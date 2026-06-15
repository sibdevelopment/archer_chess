<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Masterclass;
use App\Models\CoachAttendance;
use App\Models\DemoLead;
use App\Models\DemoSession;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Log;

class GetDemoRecording extends Command
{
    protected $signature = 'get:demo-recordings';
    protected $description = 'Fetch Zoom meeting recordings for a specific user';

    // $recordingData = [];
    //     $todaysDate = now();
    //     $tenDaysAgo = now()->subDays(10);

    //     $demoAttendances = CoachAttendance::whereBetween('date', [$tenDaysAgo->toDateString(), $todaysDate->toDateString()])
    //         ->where('type', 'Demo')
    //         ->get();
    //     foreach ($demoAttendances as $demo) {
    //         $coach = $demo->coach;
    //         if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
    //             continue;
    //         }

    //         try {
    //             $zoomMeetingService = new ZoomMeetingService(
    //                 $coach->zoom_api_key,
    //                 $coach->zoom_client_secret,
    //                 $coach->zoom_id
    //             );

    //             $recordings = $zoomMeetingService->getRecordingLinks($coach->zoom_user_id);
    //             if (empty($recordings) || !is_array($recordings)) {
    //                 continue;
    //             }

    //             $demoDate = Carbon::parse($demo->date)->toDateString();
    //             $demoTime = Carbon::parse($demo->from_time)->format('H:i'); // Assuming `from_time` is stored in `demo`

    //             foreach ($recordings as $recording) {
    //                 if (empty($recording['start_time']) || empty($recording['play_url'])) {
    //                     continue;
    //                 }

    //                 $recordingDateTime = Carbon::parse($recording['start_time'])->timezone('Asia/Kolkata');
    //                 $recordingDate = $recordingDateTime->toDateString();
    //                 $recordingTime = $recordingDateTime->format('H:i');

    //                 $isDateMatch = $recordingDate === $demoDate;
    //                 $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($demoTime))) <= 15;

    //                 if ($isDateMatch && $isTimeMatch) {
    //                     $recordingData[] = [
    //                         'demo_name' => $demo->demolead->first_name ?? 'Unnamed Demo',
    //                         'coach_name' => $coach->name,
    //                         'recording_link' => $recording['play_url'],
    //                     ];
    //                     break; // Done once one match is found
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             // Optionally log or ignore
    //             continue;
    //         }
    //     }

    //     dd($recordingData);


    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $demoAttendances = CoachAttendance::where('type', 'Demo')
            ->whereDate('date', $today)
            ->get();

        $this->info("🎥 Fetching recordings for " . $demoAttendances->count() . " demo sessions on $today");

        foreach ($demoAttendances as $attendance) {
            // Skip if recording already saved
            if (!empty($attendance->recording_link)) {
                $this->warn("⏩ Skipping attendance ID {$attendance->id}, already has recording.");
                continue;
            }

            $coach = $attendance->coach;
            $demolead = DemoLead::find($attendance->demolead_id);
            $session = DemoSession::where('demolead_id', $demolead->id)->first();

            if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_user_id || !$session) {
                $this->warn("⚠️ Missing coach/session/Zoom credentials for attendance ID {$attendance->id}");
                continue;
            }

            $zoomService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id
            );

            try {
                $recordings = $zoomService->getRecordingLinks($coach->zoom_user_id);

                $scheduleTime = Carbon::parse($session->time)->format('H:i');

                foreach ($recordings as $recording) {
                    if (empty($recording['start_time']) || empty($recording['play_url'])) {
                        $this->warn("⚠️ Incomplete recording data for session ID {$session->id}");
                        continue;
                    }

                    $recordingDateTime = Carbon::parse($recording['start_time'])->timezone('Asia/Kolkata');
                    $recordingDate = $recordingDateTime->toDateString();
                    $recordingTime = $recordingDateTime->format('H:i');

                    $isDateMatch = $recordingDate === Carbon::now('Asia/Kolkata')->toDateString();
                    $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($scheduleTime))) <= 15;

                    if ($isDateMatch && $isTimeMatch) {
                        $recordingLink = $recording['play_url'];

                        // ✅ Save in CoachAttendance
                        $attendance->recording_link = $recordingLink;
                        $attendance->save();
                        $this->info("✅ Saved recording for CoachAttendance ID {$attendance->id}");

                        // ✅ Save in StudentAttendance
                        // $studentAttendances = StudentAttendance::where('batch_id', $session->batch_id)
                        //     ->whereDate('date', $today)
                        //     ->get();

                        // foreach ($studentAttendances as $studentAttendance) {
                        //     $studentAttendance->recording_link = $recordingLink;
                        //     $studentAttendance->save();
                        //     $this->info("↳ Saved recording for StudentAttendance ID {$studentAttendance->id}");
                        // }

                        break; // only one match per session
                    }
                }
            } catch (\Exception $e) {
                $this->error("❌ Failed to fetch recordings for coach ID {$coach->id}: " . $e->getMessage());
            }
        }

        $this->info("✅ Done fetching and storing demo recordings.");
    }


}
