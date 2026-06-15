<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Masterclass;
use App\Models\CoachAttendance;
use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Log;

class GetZoomMeetingRecordings extends Command
{
    protected $signature = 'get:meeting-recordings';
    protected $description = 'Fetch Zoom meeting recordings for a specific user';

   
    //for Today 
    // public function handle()
    // {
    //     $todaysDate = date('Y-m-d');
    //     $todays_day = date('l');

    //     $batches = Batch::where('start_date', '<=', $todaysDate)
    //         ->where('end_date', '>=', $todaysDate)
    //         ->whereHas('batchSchedules', function ($query) use ($todays_day) {
    //             $query->where('weekday', $todays_day);
    //         })
    //         ->where('status', 'ACTIVE')
    //         ->get();

    //     foreach ($batches as $batch) {
    //         $coach = $batch->coach;
    //         if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
    //             $this->error("Coach Zoom credentials not set for batch {$batch->id}");
    //             continue;
    //         }
    //         $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
    //             ->whereDate('date', $todaysDate)
    //             ->latest()
    //             ->first();

    //     if ($latestCoachAttendance && empty($latestCoachAttendance->recording_link)) {
    //         try {
    //             $zoomMeetingService = new ZoomMeetingService(
    //                 $coach->zoom_api_key,
    //                 $coach->zoom_client_secret,
    //                 $coach->zoom_id
    //             );

    //             $recordings = $zoomMeetingService->getRecordingLinks($batch->coach->zoom_user_id);
    //             if (empty($recordings) || !is_array($recordings)) {
    //                 $this->warn("No recordings found for coach {$coach->id} on {$todaysDate}");
    //                 return;
    //             }

    //             $batchSchedule = $batch->batchSchedules()
    //                 ->where('weekday', Carbon::now()->format('l'))
    //                 ->first();

    //             if (!$batchSchedule) {
    //                 $this->warn("No schedule found for today for batch {$batch->id}");
    //                 return;
    //             }

    //             $scheduleTime = Carbon::createFromFormat('H:i:s', $batchSchedule->from_time)
    //                 ->timezone('Asia/Kolkata')
    //                 ->format('H:i');

    //             foreach ($recordings as $recording) {
    //                 if (empty($recording['start_time']) || empty($recording['play_url'])) {
    //                     $this->warn("Incomplete recording data for batch {$batch->id}");
    //                     continue;
    //                 }

    //                 $recordingDateTime = Carbon::parse($recording['start_time'])->timezone('Asia/Kolkata');
    //                 $recordingDate = $recordingDateTime->toDateString();
    //                 $recordingTime = $recordingDateTime->format('H:i');

    //                 $isDateMatch = $recordingDate === Carbon::now()->toDateString();
    //                 $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($scheduleTime))) <= 15;

    //                 if ($isDateMatch && $isTimeMatch) {
    //                     $recordingLink = $recording['play_url'];

    //                     $latestCoachAttendance->recording_link = $recordingLink;
    //                     $latestCoachAttendance->save();
    //                     $this->info("Recording link saved for batch {$batch->id}");

    //                     // Update for students
    //                     $studentAttendances = StudentAttendance::where('batch_id', $batch->id)
    //                         ->whereDate('date', Carbon::now()->toDateString())
    //                         ->get();

    //                     foreach ($studentAttendances as $studentAttendance) {
    //                         $studentAttendance->recording_link = $recordingLink;
    //                         $studentAttendance->save();
    //                         $this->info("Recording link saved for student attendance ID {$studentAttendance->id}");
    //                     }

    //                     // Done after saving one matching recording
    //                     break;
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             $this->error("Zoom API error for batch {$batch->id}: " . $e->getMessage());
    //         }
    //     }

    //     }
    // }

    // For Yesterday
    // public function handle()
    // {
    //     //yesterday's date is not used in this command
    //     $todaysDate = Carbon::yesterday()->format('Y-m-d');
    //     // $todaysDate = date('Y-m-d');
    //     // yesterday date day
    //     $todays_day =  'Monday'; // date('l');

    //     $batches = Batch::where('start_date', '<=', $todaysDate)
    //         ->where('end_date', '>=', $todaysDate)
    //         ->whereHas('batchSchedules', function ($query) use ($todays_day) {
    //             $query->where('weekday', $todays_day);
    //         })
    //         ->where('status', 'ACTIVE')
    //         ->get();
    //     foreach ($batches as $batch) {
    //         $coach = $batch->coach;
    //         if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
    //             $this->error("Coach Zoom credentials not set for batch {$batch->id}");
    //             continue;
    //         }
    //         $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
    //             ->whereDate('date', $todaysDate)
    //             ->latest()
    //             ->first();
    //     if ($latestCoachAttendance && empty($latestCoachAttendance->recording_link)) {
    //         try {
    //             $zoomMeetingService = new ZoomMeetingService(
    //                 $coach->zoom_api_key,
    //                 $coach->zoom_client_secret,
    //                 $coach->zoom_id
    //             );

    //             $recordings = $zoomMeetingService->getRecordingLinks($batch->coach->zoom_user_id);
    //             // dd($recordings);
    //             if (empty($recordings) || !is_array($recordings)) {
    //                 $this->warn("No recordings found for coach {$coach->id} on {$todaysDate}");
    //                 return;
    //             }

    //             $batchSchedule = $batch->batchSchedules()
    //                 ->where('weekday', 'Monday') // Assuming you want to check for Monday
    //                 ->first();
    //             // dd($batchSchedule);
    //             if (!$batchSchedule) {
    //                 $this->warn("No schedule found for today for batch {$batch->id}");
    //                 return;
    //             }

    //             $scheduleTime = Carbon::createFromFormat('H:i:s', $batchSchedule->from_time)
    //                 ->timezone('Asia/Kolkata')
    //                 ->format('H:i');

    //             foreach ($recordings as $recording) {
    //                 if (empty($recording['start_time']) || empty($recording['play_url'])) {
    //                     $this->warn("Incomplete recording data for batch {$batch->id}");
    //                     continue;
    //                 }

    //                 $recordingDateTime = Carbon::parse($recording['start_time'])->timezone('Asia/Kolkata');
    //                 $recordingDate = $recordingDateTime->toDateString();
    //                 $recordingTime = $recordingDateTime->format('H:i');

    //                 $isDateMatch = $recordingDate === $todaysDate;
    //                 // dd($recordingDate, $todaysDate);
    //                 $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($scheduleTime))) <= 15;

    //                 if ($isDateMatch && $isTimeMatch) {
    //                     $recordingLink = $recording['play_url'];

    //                     $latestCoachAttendance->recording_link = $recordingLink;
    //                     $latestCoachAttendance->save();
    //                     $this->info("Recording link saved for batch {$batch->id}");

    //                     // Update for students
    //                     $studentAttendances = StudentAttendance::where('batch_id', $batch->id)
    //                         ->whereDate('date', $todaysDate)
    //                         ->get();

    //                     foreach ($studentAttendances as $studentAttendance) {
    //                         $studentAttendance->recording_link = $recordingLink;
    //                         $studentAttendance->save();
    //                         $this->info("Recording link saved for student attendance ID {$studentAttendance->id}");
    //                     }

    //                     // Done after saving one matching recording
    //                     break;
    //                 }
    //             }
    //         } catch (\Exception $e) {
    //             $this->error("Zoom API error for batch {$batch->id}: " . $e->getMessage());
    //         }
    //     }

    //     }
    // }

    // For a range of dates
    public function handle()
    {
        $startDateTime = Carbon::now()->subDays(7);
        $endDateTime = Carbon::now();

        $current = $startDateTime->copy();
        while ($current->lte($endDateTime)) {
            $todaysDate = $current->toDateString();
            $todays_day = $current->format('l'); // Monday, Tuesday, etc.

            $batches = Batch::where('start_date', '<=', $todaysDate)
                ->where('end_date', '>=', $todaysDate)
                ->whereHas('batchSchedules', function ($query) use ($todays_day) {
                    $query->where('weekday', $todays_day);
                })
                ->where('status', 'ACTIVE')
                ->get();
            
            foreach ($batches as $batch) {
                $coach = $batch->coach;
                if (!$coach || !$coach->zoom_api_key || !$coach->zoom_client_secret || !$coach->zoom_id) {
                    $this->error("Coach Zoom credentials not set for batch {$batch->id}");
                    continue;
                }

                $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
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

                        $recordings = $zoomMeetingService->getRecordingLinks($batch->coach->zoom_user_id);
                        if (empty($recordings) || !is_array($recordings)) {
                            $this->warn("No recordings found for coach {$coach->id} on {$todaysDate}");
                            continue;
                        }

                        $batchSchedule = $batch->batchSchedules()
                            ->where('weekday', $todays_day)
                            ->first();

                        if (!$batchSchedule) {
                            $this->warn("No schedule found for {$todays_day} for batch {$batch->id}");
                            continue;
                        }

                        $scheduleTime = Carbon::createFromFormat('H:i:s', $batchSchedule->from_time)
                            ->timezone('Asia/Kolkata')
                            ->format('H:i');

                        foreach ($recordings as $recording) {
                            if (empty($recording['start_time']) || empty($recording['play_url'])) {
                                $this->warn("Incomplete recording data for batch {$batch->id}");
                                continue;
                            }

                            $recordingDateTime = Carbon::parse($recording['start_time'])->timezone('Asia/Kolkata');
                            $recordingDate     = $recordingDateTime->toDateString();
                            $recordingTime     = $recordingDateTime->format('H:i');

                            $isDateMatch = $recordingDate === $todaysDate;
                            $isTimeMatch = abs(Carbon::parse($recordingTime)->diffInMinutes(Carbon::parse($scheduleTime))) <= 15;

                            if ($isDateMatch && $isTimeMatch) {
                                $recordingLink = $recording['play_url'];

                                $latestCoachAttendance->recording_link = $recordingLink;
                                $latestCoachAttendance->save();
                                $this->info("Recording link saved for batch {$batch->id} on {$todaysDate}");

                                $studentAttendances = StudentAttendance::where('batch_id', $batch->id)
                                    ->whereDate('date', $todaysDate)
                                    ->get();

                                foreach ($studentAttendances as $studentAttendance) {
                                    $studentAttendance->recording_link = $recordingLink;
                                    $studentAttendance->save();
                                    $this->info("Recording link saved for student attendance ID {$studentAttendance->id}");
                                }

                                // Done with this recording
                                break;
                            }
                        }
                    } catch (\Exception $e) {
                        $this->error("Zoom API error for batch {$batch->id} on {$todaysDate}: " . $e->getMessage());
                    }
                }
            }

            // Move to next date
            $current->addDay();
        }
    }



}
