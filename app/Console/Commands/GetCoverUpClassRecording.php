<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Coach;
use App\Models\Masterclass;
use App\Models\Coverupclass;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use App\Services\ZoomMeetingService;

class GetCoverUpClassRecording extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:coverupclass-recording';

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

    // public function handle()
    // {
    //     $todaysDate = date('Y-m-d');

    //     // Get all coach attendances marked as COVERUP for today
    //     $coverupAttendances = CoachAttendance::where('type', 'COVERUP')->whereDate('date', $todaysDate)->get();
    //     foreach ($coverupAttendances as $attendance) {
    //         $batchId = $attendance->batch_id;
    //         $coachId = $attendance->coach_id;
    //         // Find the corresponding CoverupClass for this batch, coach, and date
    //         $coverupClass = CoverupClass::where('batch_id', $batchId)
    //             ->where('new_coach_id', $coachId)
    //             ->whereDate('date', $todaysDate)
    //             ->whereNotNull('zoom_meeting_id')
    //             ->first();

    //         if (!$coverupClass) {
    //             $this->warn("No matching CoverupClass found for batch_id {$batchId}, coach_id {$coachId}, and date {$todaysDate}");
    //             continue;
    //         }

    //         $coach = Coach::find($coverupClass->new_coach_id);
            
    //         if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
    //             $this->error("Zoom credentials not set for coach ID {$coachId}");
    //             continue;
    //         }

    //         if (!empty($attendance->recording_link)) {
    //             $this->info("Recording already exists for coach attendance ID {$attendance->id}, skipping.");
    //             continue;
    //         }

    //         try {
    //             $zoomMeetingService = new ZoomMeetingService(
    //                 $coach->zoom_api_key,
    //                 $coach->zoom_client_secret,
    //                 $coach->zoom_id
    //             );

    //             $recordingData = $zoomMeetingService->getRecordingLinks($coverupClass->zoom_meeting_id);

    //             if (empty($recordingData) || empty($recordingData['start_time']) || empty($recordingData['play_url'])) {
    //                 $this->warn("Incomplete recording data for CoverupClass {$coverupClass->id}");
    //                 continue;
    //             }
    //             $recordingDate = Carbon::parse($recordingData['start_time'])
    //                 ->timezone('Asia/Kolkata')
    //                 ->format('Y-m-d');
    //             if ($recordingDate === $todaysDate) {
    //                 $recordingLink = $recordingData['play_url'];

    //                 // Save to CoachAttendance
    //                 $attendance->recording_link = $recordingLink;
    //                 $attendance->save();
    //                 $this->info("Recording link saved for coach attendance ID {$attendance->id}");

    //                 // Save to StudentAttendance as well
    //                 $studentAttendances = StudentAttendance::where('batch_id', $batchId)
    //                     ->where('type', 'COVERUP')
    //                     ->whereDate('date', $todaysDate)
    //                     ->get();

    //                 foreach ($studentAttendances as $studentAttendance) {
    //                     $studentAttendance->recording_link = $recordingLink;
    //                     $studentAttendance->save();
    //                     $this->info("Recording link saved for student attendance ID {$studentAttendance->id}");
    //                 }
    //             } else {
    //                 $this->warn("Recording date mismatch for CoverupClass {$coverupClass->id}, skipping.");
    //             }

    //         } catch (\Exception $e) {
    //             $this->error("Zoom API error for CoverupClass {$coverupClass->id}: " . $e->getMessage());
    //         }
    //     }
    // }

    public function handle()
    {
        $todaysDate = date('Y-m-d');

        $coverupAttendances = CoachAttendance::where('type', 'COVERUP')
                            ->whereDate('date', $todaysDate)
                            ->whereNull('recording_link') // Only process attendances without a recording link
                            ->get();

        foreach ($coverupAttendances as $attendance) {
            $batchId = $attendance->batch_id;
            $coachId = $attendance->coach_id;

            $coverupClass = Coverupclass::where('batch_id', $batchId)
                ->where('new_coach_id', $coachId)
                ->whereDate('date', $attendance->date)
                ->whereNotNull('zoom_meeting_id')
                ->first();

            if (!$coverupClass) {
                $this->warn("No matching CoverupClass found for batch_id {$batchId}, coach_id {$coachId}, and date {$todaysDate}");
                continue;
            }

            $coach = Coach::find($coverupClass->new_coach_id);
            
            if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
                $this->error("Zoom credentials not set for coach ID {$coachId}");
                continue;
            }

            try {
                $zoomMeetingService = new ZoomMeetingService(
                    $coach->zoom_api_key,
                    $coach->zoom_client_secret,
                    $coach->zoom_id
                );

                $recordingDataList = $zoomMeetingService->getRecordingLinks($coach->zoom_user_id);

                if (empty($recordingDataList) || !is_array($recordingDataList)) {
                    $this->warn("No recording data found for CoverupClass {$coverupClass->id}");
                    continue;
                }

                $expectedDate = Carbon::parse($attendance->date)->format('Y-m-d');
                $expectedTime = Carbon::parse($attendance->time, 'Asia/Kolkata')->format('H:i');

                $recordingFound = false;

                foreach ($recordingDataList as $recordingData) {
                    if (empty($recordingData['start_time']) || empty($recordingData['play_url'])) {
                        continue;
                    }

                    $start = Carbon::parse($recordingData['start_time'], 'UTC')->setTimezone('Asia/Kolkata');
                    $recordingDate = $start->format('Y-m-d');
                    $recordingTime = $start->format('H:i');

                    $this->info("Checking recording: $recordingDate $recordingTime vs $expectedDate $expectedTime");

                    $isDateMatch = $recordingDate === $expectedDate;
                    $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($expectedTime))) <= 15;

                    if ($isDateMatch && $isTimeMatch) {
                        $recordingLink = $recordingData['play_url'];

                        // Save to CoachAttendance
                        $attendance->recording_link = $recordingLink;
                        $attendance->save();
                        $this->info("Recording link saved for coach attendance ID {$attendance->id}");

                        // Save to StudentAttendance
                        $studentAttendances = StudentAttendance::where('batch_id', $batchId)
                            ->where('type', 'COVERUP')
                            ->whereDate('date', $todaysDate)
                            ->get();

                        foreach ($studentAttendances as $studentAttendance) {
                            $studentAttendance->recording_link = $recordingLink;
                            $studentAttendance->save();
                            $this->info("Recording link saved for student attendance ID {$studentAttendance->id}");
                        }

                        $recordingFound = true;
                        break;
                    }
                }


                if (!$recordingFound) {
                    $this->warn("No matching recording time found for CoverupClass {$coverupClass->id}");
                }

            } catch (\Exception $e) {
                $this->error("Zoom API error for CoverupClass {$coverupClass->id}: " . $e->getMessage());
            }
        }
    }

}
