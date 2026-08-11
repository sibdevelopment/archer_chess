<?php
namespace App\Http\Controllers\Admin;

use DateTime;
use DataTables;
use DatePeriod;
use DateInterval;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\StudentFee;
use App\Models\DemoSession;
use App\Models\Coverupclass;
use App\Models\LeaveRequest;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Auth;
use App\Services\BatchOccurrenceService;
use App\Services\CoachAvailabilityService;
use Illuminate\Validation\ValidationException;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.LeaveRequests.index', compact('coaches'));
    }

    public function data(Request $request)
    {
        $user  = auth()->user();
        $role  = $user->getRoleNames()->toArray();
        // $query = LeaveRequest::where('id', '!=', 0)->orderBy('from_date', 'desc');

        
        

        $query = LeaveRequest::with('coach.user') // <-- eager load here
        ->where('id', '!=', 0)
        ->orderBy('from_date', 'desc');


        if (! $user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->toArray();
        
            $mergedCountries = collect($countries)
                ->map(fn($item) => json_decode($item, true))  
                ->flatten()  
                ->filter()  
                ->unique()  
                ->values() 
                ->toArray();
        
            if (! empty($mergedCountries)) {
                $query->whereHas('coach', function ($q) use ($mergedCountries) {
                    $q->where('status', 'ACTIVE')->where(function ($q) use ($mergedCountries) {
                        foreach ($mergedCountries as $country) {
                            $q->orWhereJsonContains('country', $country);
                        }
                    });
                });
            }
        }
        


        if (in_array("Coach", $role)) {
            $coach = Coach::where('user_id', $user->id)->first();
            if ($coach) {
                $query->where('coach_id', $coach->id);
            } else {
                return DataTables::of([])->toJson();
            }
        }

        if ($request->has('coach') && $request->coach != '') {
            $query->where('coach_id', $request->coach);
        }
        if ($request->from_date) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        return DataTables::eloquent($query)
            ->addColumn('coach_name', function ($leaverequest) {
                return $leaverequest->coach->user->first_name . ' ' . $leaverequest->coach->user->last_name;
            })
            ->filterColumn('coach_name', function ($query, $keyword) {
                $query->whereHas('coach.user', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('timeline', function ($leaverequest) {
                $fromDate  = \Carbon\Carbon::parse($leaverequest->from_date)->format('d-M-Y');
                $toDate    = \Carbon\Carbon::parse($leaverequest->to_date)->format('d-M-Y');
                $fromTime  = $leaverequest->from_time ? \Carbon\Carbon::parse($leaverequest->from_time)->format('h:i A') : '';
                $toTime    = $leaverequest->to_time ? \Carbon\Carbon::parse($leaverequest->to_time)->format('h:i A') : '';
                $timeRange = ($fromTime && $toTime) ? " ({$fromTime} to {$toTime})" : '';
                if ($leaverequest->to_date) {
                    return '<img src="/backend/dist/images/svgs/icon-master-card-2.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . "{$fromDate} to {$toDate}{$timeRange}";
                } else {
                    return '<img src="/backend/dist/images/svgs/icon-master-card-2.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . "{$fromDate}{$timeRange}";
                }
            })
            ->editColumn('reason', function ($leaverequest) {
                return '<img src="/backend/dist/images/svgs/icon-dd-chat.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $leaverequest->reason;
            })
            ->editColumn('status', function ($leaverequest) use ($role) {
                $badgeColor = match ($leaverequest->status) {
                    'ACTIVE' => 'success',
                    'INACTIVE' => 'secondary',
                    'APPROVED' => 'primary',
                    'REJECTED' => 'danger',
                    default    => 'warning',
                };
                $isNotCoach = ! in_array("Coach", $role);
                if ($isNotCoach) {
                    if ($leaverequest->status === 'APPROVED') {
                        return '<span class="badge bg-' . $badgeColor . ' fs-1">' . $leaverequest->status . '</span>';
                    }
                    return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 leaverequest-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $leaverequest->id . '" data-id="' . $leaverequest->id . '">' . $leaverequest->status . '</button>';
                } else {
                    return '<span class="badge bg-' . $badgeColor . ' fs-1">' . $leaverequest->status . '</span>';
                }
            })
            ->addColumn('action', function ($leaverequest) {
                if ($leaverequest->status === 'APPROVED') {
                    $edit = '<a href="#" class="badge bg-primary  fs-1 disabled" aria-disabled="true"><i class="fa fa-edit"></i></a>';
                } else {
                    $edit = '<a href="' . route('admin.leaverequests.edit', ['leaverequest' => $leaverequest->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                }
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['coach_id', 'from_date', 'to_date', 'reason', 'status', 'action', 'timeline', 'reason'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        $user  = Auth::user();
        $coach = Coach::where('user_id', $user->id)->first();
        if (! $coach) {
            return redirect()->back()->withErrors('You are not authorized to create a leave request.');
        }

        return view('Admin.LeaveRequests.form', compact('coach'));
    }

    public function store(Request $request)
    {
        $this->validateLeaveTimeWindow($request);
        $request->validate($this->rules, $this->customMessages);

        $leaverequest = new LeaveRequest;
        $leaverequest->fill($request->all());
        $leaverequest->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'LeaveRequest Created Successfully',
            'slider'  => $leaverequest,
        ], 201);
    }

    public function edit(LeaveRequest $leaverequest)
    {
        $coach = $leaverequest->coach;
        if (! $coach) {
            return redirect()->back()->withErrors('Coach data not found.');
        }

        return view('Admin.LeaveRequests.form', compact('leaverequest', 'coach'));
    }

    public function update(Request $request, LeaveRequest $leaverequest)
    {
        $this->validateLeaveTimeWindow($request);
        $request->validate($this->rules, $this->customMessages);
        $leaverequest->fill($request->all());
        $leaverequest->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'LeaveRequest Created Successfully',
            'slider'  => $leaverequest,
        ], 201);
    }

    public function changeStatus(Request $request, CoachAvailabilityService $availability)
    {
        $leaverequest         = LeaveRequest::find($request->leaverequest_id);
        $leaverequest->status = $request->status;
        $occurrences = app(BatchOccurrenceService::class);
        $handledOccurrences = [];

        if (isset($request->affectedData)) {
            foreach ($request->affectedData as $data) {
                $batch          = Batch::find($data['batch_id']);
                if (!$batch) {
                    continue;
                }
                $batch_coach_id = $batch->coach_id;
                $schedule = BatchSchedule::find($data['schedule_id']);
                if (!$schedule) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Coverup schedule not found.',
                    ], 422);
                }

                $occurrenceKey = $this->leaveOccurrenceKey($batch->id, $schedule->id, $leaverequest->from_date);
                $handledOccurrences[] = $occurrenceKey;

                if ($data['coach_id']) {
                    $coach = Coach::find($data['coach_id']);
                    if ($coach && $this->checkCoachLeave($coach, $leaverequest->from_date, $schedule->from_time, $schedule->to_time)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Selected coach is on approved leave for the coverup date.',
                        ], 422);
                    }

                    $coachValidation = $availability->validateCoachForSingleEvent(
                        (int) $data['coach_id'],
                        $leaverequest->from_date,
                        $schedule->from_time,
                        $schedule->to_time,
                        $batch->country ?? [],
                        'coverup'
                    );

                    if (!$coachValidation['ok']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $coachValidation['message'],
                        ], 422);
                    }

                    $coverupclass = Coverupclass::firstOrNew([
                        'batch_id' => $data['batch_id'],
                        'batchschedule_id' => $data['schedule_id'],
                        'date' => $leaverequest->from_date,
                    ]);
                    $coverupclass->old_coach_id = $batch_coach_id;
                    $coverupclass->new_coach_id = $data['coach_id'];
                    $coverupclass->save();
                    $occurrences->clearDelayedPenalty($batch->id, $schedule->id, $leaverequest->from_date);


                    $coach = Coach::find($coverupclass->new_coach_id);

                    if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {

                        $zoomMeetingService = new ZoomMeetingService(
                            $coach->zoom_api_key,
                            $coach->zoom_client_secret,
                            $coach->zoom_id 
                        );
                        
                        $meetingData = [
                            'title' => 'Cover Up - '.$coach->user->first_name.' - '.$batch->name,
                            'duration_in_minute' => 40,
                            'start_date_time' => now()->addMinutes(5)->toIso8601String(),
                        ];

                        $zoomResponse = $zoomMeetingService->createCoverUpClassMeeting($meetingData, $coach->zoom_user_id);

                        // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);
                        $coverupclass->start_url = $zoomResponse['start_url'] ?? '';
                        $coverupclass->join_url  = $zoomResponse['join_url'] ?? '';
                        $coverupclass->zoom_meeting_id  = $zoomResponse['id'] ?? null;
                        $coverupclass->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
                        $coverupclass->save();
                    }

                } else {
                    $occurrences->markApprovedLeaveOccurrence($batch, $schedule, $leaverequest->from_date);
                }
            }
        }

        $leaverequest->save();

        if ($leaverequest->status === 'APPROVED') {
            $this->compensateApprovedLeaveWithoutCoverup($leaverequest, $occurrences, $handledOccurrences);
        }

        return response()->json([
            'status'       => 'success',
            'message'      => $leaverequest->name . ' Request has been marked ' . $leaverequest->status . ' successfully',
            'leaverequest' => $leaverequest,
        ], 201);
    }

    private function cancelBatchLogic($batch_id, $schedule_id, $date, $coach_id)
    {
        $batchschedule = BatchSchedule::find($schedule_id);

        $latestCoachAttendance = CoachAttendance::where('batch_id', $batch_id)
            ->orderBy('id', 'desc')
            ->first();

        // dd($latestCoachAttendance);

        $coachAttendance                           = new CoachAttendance();
        $coachAttendance->coach_id                 = $coach_id;
        $coachAttendance->type                     = 'BATCH';
        $coachAttendance->batch_id                 = $batch_id;
        $coachAttendance->date                     = $date;
        $coachAttendance->time                     = $batchschedule->from_time;
        $coachAttendance->status                   = 'CANCELLED';
        $coachAttendance->homework_link            = '';
        $coachAttendance->recording_link           = '';
        $coachAttendance->number_of_batch_sessions = ($latestCoachAttendance->number_of_batch_sessions ?? 0) + 1;
        $coachAttendance->save();

        $batch          = Batch::find($batch_id);
        $batchSchedules = BatchSchedule::where('batch_id', $batch_id)->get();
        $scheduledDays  = $batchSchedules->pluck('weekday')->map(function ($day) {
            return strtolower($day);
        })->toArray();
        $batchEndDate     = Carbon::parse($batch->end_date);
        $batch_end_day    = strtolower($batchEndDate->format('l'));
        $nextScheduledDay = null;
        foreach ($scheduledDays as $day) {
            $dayDifference = (Carbon::parse($day)->dayOfWeek - $batchEndDate->dayOfWeek + 7) % 7;
            if ($dayDifference > 0) {
                $nextScheduledDay = $batchEndDate->copy()->addDays($dayDifference);
                break;
            }
        }
        if (! $nextScheduledDay) {
            $nextScheduledDay = $batchEndDate->copy()->addDays((Carbon::parse($scheduledDays[0])->dayOfWeek - $batchEndDate->dayOfWeek + 7) % 7);
        }
        $batch->end_date = $nextScheduledDay->toDateString();
        $batch->save();

        $studentIds = StudentBatch::where('batch_id', $batch_id)->where('status', 'ACTIVE')->pluck('student_id')->toArray();

        ## ----------------- ## ----------------- Student Attendance Logic ----------------- ## -----------------
        foreach ($studentIds as $key => $studentId) {
            $studentAttendance                           = new StudentAttendance();
            $studentAttendance->student_id               = $studentId;
            $studentAttendance->batch_id                 = $batch_id;
            $studentAttendance->level_id                 = $batch->level_id;
            $studentAttendance->date                     = $date;
            $studentAttendance->time                     = $batchschedule->from_time;
            $studentAttendance->status                   = 'CANCELLED';
            $studentAttendance->remark                   = 'Batch Cancelled';
            $studentAttendance->type                     = 'BATCH';
            $studentAttendance->coach_id                 = $batch->coach_id;
            $studentAttendance->homework_link            = '';
            $studentAttendance->recording_link           = '';
            $studentAttendance->chapter_name             = '';
            $studentAttendance->number_of_batch_sessions = $coachAttendance->number_of_batch_sessions;
            $studentAttendance->save();

            $studentBatch = StudentBatch::where('student_id', $studentId)
                ->where('batch_id', $batch_id)
                ->where('status', 'ACTIVE')
                ->first();

            $studentBatch->end_date = $batch->end_date;
            $studentBatch->save();

            ## ----------------- ## ----------------- Student Fees Logic ----------------- ## -----------------

            $studentLatestFee        = StudentFee::where('student_id', $studentId)->orderBy('id', 'desc')->first();
            $studentLatestFeeEndDate = Carbon::parse($studentLatestFee->end_date);
            $studentLatestFeeEndDay  = strtolower($studentLatestFeeEndDate->format('l'));
            $nextScheduledDayDate    = null;
            foreach ($scheduledDays as $day) {
                $dayDifference = (Carbon::parse($day)->dayOfWeek - $studentLatestFeeEndDate->dayOfWeek + 7) % 7;
                if ($dayDifference > 0) {
                    $nextScheduledDayDate = $studentLatestFeeEndDate->copy()->addDays($dayDifference);
                    break;
                }
            }
            if (! $nextScheduledDayDate) {
                $nextScheduledDayDate = $studentLatestFeeEndDate->copy()->addDays(
                    (Carbon::parse($scheduledDays[0])->dayOfWeek - $studentLatestFeeEndDate->dayOfWeek + 7) % 7
                );
            }
            $studentLatestFee->end_date = $nextScheduledDayDate->toDateString();
            $studentLatestFee->save();
        }
    }

    private function adjustStudentBatchEndDate($combinedAffectedBatches, $leaverequest)
    {
        foreach ($combinedAffectedBatches as $batchId => $batchInfo) {
            $batch = Batch::find($batchId);

            // Get the scheduled weekdays in lowercase
            $batchSchedules = BatchSchedule::where('batch_id', $batchId)->get();
            $scheduledDays  = $batchSchedules->pluck('weekday')->map(function ($day) {
                return strtolower($day);
            })->toArray();

            // // Parse batch end date and get the day of the week
            $batchEndDate = Carbon::parse($batch->end_date);
            $missedCount  = $batchInfo['missedCount'];

            // // Adjust for multiple missed sessions
            // $nextScheduledDay = $batchEndDate->copy();
            // for ($i = 0; $i < $missedCount; $i++) {
            //     // Find the next scheduled day after the current `nextScheduledDay`
            //     $foundDay = false;
            //     foreach ($scheduledDays as $day) {
            //         $dayDifference = (Carbon::parse($day)->dayOfWeek - $nextScheduledDay->dayOfWeek + 7) % 7;
            //         if ($dayDifference > 0) {
            //             $nextScheduledDay = $nextScheduledDay->copy()->addDays($dayDifference);
            //             $foundDay = true;
            //             break;
            //         }
            //     }

            //     // Fallback: Roll over to the next week's first scheduled day if no day is found
            //     if (!$foundDay) {
            //         $nextScheduledDay = $nextScheduledDay->copy()->addDays(
            //             (Carbon::parse($scheduledDays[0])->dayOfWeek - $nextScheduledDay->dayOfWeek + 7) % 7
            //         );
            //     }
            // }
            // // dd($nextScheduledDay->toDateString());
            // // Update the batch end date
            // $batch->end_date = $nextScheduledDay->toDateString();
            // $batch->save();

            // Handle student fees
            $kkk            = 1;
            $studentBatches = StudentBatch::where('batch_id', $batchId)->where('status', 'ACTIVE')->get();
            foreach ($studentBatches as $studentBatch) {
                $kkk++;
                $student    = $studentBatch->student;
                $studentFee = StudentFee::where('student_id', $student->id)->orderBy('id', 'desc')->first();
                if ($studentFee) {
                    Log::info('Student Fee ID: ' . $studentFee->id);
                    // Start from the student's current fee end date
                    $currentFeeEndDate = Carbon::parse($studentFee->end_date);
                    $calculatedEndDate = $currentFeeEndDate->copy(); // New variable for clarity

                    // Adjust for multiple missed sessions for the student
                    for ($i = 0; $i < $missedCount; $i++) {
                        $kkk++;
                        $foundNextDay = false;

                        // Normalize scheduled days (convert all to lowercase for consistency)
                        $normalizedScheduledDays = collect($scheduledDays)->map(function ($day) {
                            return strtolower($day);
                        });

                        // Loop through scheduled days to find the next valid day
                        foreach ($normalizedScheduledDays as $day) {
                            $kkk++;
                            $targetDayOfWeek = Carbon::parse($day)->dayOfWeek;

                            // Calculate the difference between the current day and the target day
                            $dayDifference = ($targetDayOfWeek - $calculatedEndDate->dayOfWeek + 7) % 7;

                            // If the target day is in the future, adjust the date
                            if ($dayDifference > 0) {
                                $calculatedEndDate = $calculatedEndDate->copy()->addDays($dayDifference);
                                $foundNextDay      = true;
                                break; // Stop when the next valid day is found
                            }
                        }

                        // If no valid day is found (all scheduled days are in the past), roll over to the next week's first scheduled day
                        if (! $foundNextDay) {
                            $firstScheduledDayOfWeek = Carbon::parse($normalizedScheduledDays->first())->dayOfWeek;
                            $dayDifference           = ($firstScheduledDayOfWeek - $calculatedEndDate->dayOfWeek + 7) % 7;
                            $calculatedEndDate       = $calculatedEndDate->copy()->addDays($dayDifference);
                        }
                    }

                    // Log the calculated final date
                    Log::info('START: ' . $kkk);
                    Log::info('Student Fee Start: ' . $studentFee->end_date);
                    Log::info('Student Fee ID: ' . $studentFee->id);
                    Log::info('Calculated End: ' . $calculatedEndDate->toDateString());
                    $studentFee->end_date = $calculatedEndDate->toDateString();
                    $studentFee->save();
                    Log::info('Student Fee End Date Updated');
                    Log::info('Student Fee End: ' . $studentFee->end_date);
                    Log::info('Student Fee ID: ' . $studentFee->id);
                    Log::info('END: ' . $kkk);
                    Log::info('-----------------------------------');
                }
            }

        }
        exit;
    }

    public function getAffectedData(Request $request)
    {
        $leaverequest = LeaveRequest::find($request->leaverequest_id);
        $affectedData = [];
        if ($leaverequest && $request->status === 'APPROVED') {
            $affectedData = $this->handleApprovedLeaveRequest($leaverequest, true);
        }

        return response()->json([
            'status'       => 'success',
            'affectedData' => $affectedData,
        ], 200);

    }

    private function handleApprovedLeaveRequest($leaverequest, $returnData = false)
    {
        $coach_id = $leaverequest->coach_id;
        $leaveStartDate = Carbon::parse($leaverequest->from_date);
        $leaveEndDate = Carbon::parse($leaverequest->to_date ?? $leaverequest->from_date);
        $occurrences = app(BatchOccurrenceService::class);
        $combinedAffectedBatches = [];

        for ($date = $leaveStartDate->copy(); $date->lte($leaveEndDate); $date->addDay()) {
            $dateString = $date->toDateString();
            $weekday = $date->format('l');

            $batches = Batch::with(['batchSchedules' => function ($query) use ($weekday) {
                $query->where('status', 'ACTIVE')->where('weekday', $weekday);
            }])
                ->where('coach_id', $coach_id)
                ->where('status', 'ACTIVE')
                ->whereDate('start_date', '<=', $dateString)
                ->whereDate('end_date', '>=', $dateString)
                ->whereHas('batchSchedules', function ($query) use ($weekday) {
                    $query->where('status', 'ACTIVE')->where('weekday', $weekday);
                })
                ->get();

            foreach ($batches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    if (! $occurrences->timeRangesOverlap(
                        $schedule->from_time,
                        $schedule->to_time,
                        $leaverequest->from_time,
                        $leaverequest->to_time
                    )) {
                        continue;
                    }

                    $scheduleKey = strtolower($schedule->weekday);

                    if (! isset($combinedAffectedBatches[$batch->id])) {
                        $combinedAffectedBatches[$batch->id] = [
                            'id'          => $batch->id,
                            'name'        => $batch->name,
                            'schedules'   => [],
                            'missedCount' => 0,
                            'coaches'     => [],
                        ];
                    }

                    if (! isset($combinedAffectedBatches[$batch->id]['schedules'][$scheduleKey])) {
                        $combinedAffectedBatches[$batch->id]['schedules'][$scheduleKey] = [
                            'id'             => $schedule->id,
                            'weekday'        => $scheduleKey,
                            'from_time'      => $schedule->from_time,
                            'to_time'        => $schedule->to_time,
                            'missedSessions' => 0,
                            'coaches'        => $this->getAvailableCoaches(
                                $schedule->from_time,
                                $schedule->to_time,
                                $schedule->weekday,
                                $dateString,
                                $coach_id,
                                $batch->country ?? []
                            ),
                        ];
                    }

                    $combinedAffectedBatches[$batch->id]['missedCount']++;
                    $combinedAffectedBatches[$batch->id]['schedules'][$scheduleKey]['missedSessions']++;
                }
            }
        }

        if ($returnData) {
            return array_values($combinedAffectedBatches);
        }
    }

    public function getAvailableCoaches($from_time, $to_time, $weekday, $date, $coach_id, array $countries = [])
    {
        $availability = app(CoachAvailabilityService::class);
        $coach          = Coach::where('id', $coach_id)->with('user')->first();
        if (empty($countries) && $coach) {
            $countries = is_array($coach->country) ? $coach->country : explode(',', $coach->country);
        }

        $availableCoachIds = [];
        foreach (Coach::where('status', 'ACTIVE')->get() as $coach) {
            if ($this->checkCoachLeave($coach, $date, $from_time, $to_time)) {
                continue;
            }

            $validation = $availability->validateCoachForSingleEvent(
                $coach->id,
                $date,
                $from_time,
                $to_time,
                $countries,
                'coverup'
            );

            if ($validation['ok']) {
                $availableCoachIds[] = $coach->id;
            }
        }

        $coaches = Coach::whereIn('id', $availableCoachIds)->with('user')->get();
        return $coaches;
    }

    private function checkDemoAssign($coach, $date, $weekday, $from_time, $to_time)
    {
        $demo_sessions = DemoSession::where('coach_id', $coach->id)
            ->whereDate('date', '=', $date)
            ->where('time', '>=', $from_time)
            ->where('time', '<=', $to_time)
            ->where('status', 'ACTIVE')
            ->get();

        if ($demo_sessions->count() > 0) {
            return 1;
        }
        return 0;
    }

    private function checkBatchSchedule($coach, $date, $weekday, $from_time, $to_time)
    {
        // dd($from_time, $to_time, $weekday, $date, $coach->id);
        // "04:30:00" // app/Http/Controllers/Admin/LeaveRequestController.php:698
        // "05:30:00" // app/Http/Controllers/Admin/LeaveRequestController.php:698
        // "Friday" // app/Http/Controllers/Admin/LeaveRequestController.php:698
        // "2025-04-25" // app/Http/Controllers/Admin/LeaveRequestController.php:698
        // 55 // app/Http/Controllers/Admin/LeaveRequestController.php:698

        $isBatchScheduleExist = BatchSchedule::whereHas('batch', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id)->where('status', '!=', 'INACTIVE');
        })
            ->where('weekday', $weekday)
            ->where(function ($query) use ($from_time, $to_time) {
                $query->where(function ($q) use ($from_time, $to_time) {
                    $q->where('from_time', '<', $to_time)
                        ->where('to_time', '>', $from_time);
                });
            })
            ->get();

        // dd($isBatchScheduleExist);

        if ($isBatchScheduleExist->count() > 0) {
            return 1;
        }
        return 0;
    }

    private function checkCoachLeave($coach, $date, ?string $fromTime = null, ?string $toTime = null)
    {
        $leaves = LeaveRequest::where('coach_id', $coach->id)
            ->whereDate('from_date', '=', $date)
            ->where('status', 'APPROVED')
            ->get();

        if (! $fromTime || ! $toTime) {
            return $leaves->isNotEmpty() ? 1 : 0;
        }

        $occurrences = app(BatchOccurrenceService::class);
        return $leaves->contains(function ($leave) use ($occurrences, $fromTime, $toTime) {
            return $occurrences->timeRangesOverlap($fromTime, $toTime, $leave->from_time, $leave->to_time);
        }) ? 1 : 0;
    }

    private function compensateApprovedLeaveWithoutCoverup(LeaveRequest $leaverequest, BatchOccurrenceService $occurrences, array $handledOccurrences = []): void
    {
        $leaveStartDate = Carbon::parse($leaverequest->from_date);
        $leaveEndDate = Carbon::parse($leaverequest->to_date ?? $leaverequest->from_date);
        $handledOccurrences = array_flip($handledOccurrences);

        for ($date = $leaveStartDate->copy(); $date->lte($leaveEndDate); $date->addDay()) {
            $dateString = $date->toDateString();
            $weekday = $date->format('l');

            $batches = Batch::with(['batchSchedules' => function ($query) use ($weekday) {
                $query->where('status', 'ACTIVE')->where('weekday', $weekday);
            }])
                ->where('coach_id', $leaverequest->coach_id)
                ->where('status', 'ACTIVE')
                ->whereDate('start_date', '<=', $dateString)
                ->whereDate('end_date', '>=', $dateString)
                ->whereHas('batchSchedules', function ($query) use ($weekday) {
                    $query->where('status', 'ACTIVE')->where('weekday', $weekday);
                })
                ->get();

            foreach ($batches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    if (! $occurrences->timeRangesOverlap(
                        $schedule->from_time,
                        $schedule->to_time,
                        $leaverequest->from_time,
                        $leaverequest->to_time
                    )) {
                        continue;
                    }

                    $occurrenceKey = $this->leaveOccurrenceKey($batch->id, $schedule->id, $dateString);
                    if (isset($handledOccurrences[$occurrenceKey])) {
                        continue;
                    }

                    if ($occurrences->coverupForOccurrence($batch->id, $schedule->id, $dateString)) {
                        continue;
                    }

                    $occurrences->markApprovedLeaveOccurrence($batch, $schedule, $dateString);
                }
            }
        }
    }

    private function leaveOccurrenceKey(int $batchId, int $scheduleId, string $date): string
    {
        return $batchId . ':' . $scheduleId . ':' . Carbon::parse($date)->toDateString();
    }

    private function adjustBatchesEndDateAndStudentFees($combinedAffectedBatches, $leaverequest)
    {
        //dd($leaverequest);
        $leaveStartDate = new DateTime($leaverequest->from_date);
        $leaveEndDate   = new DateTime($leaverequest->to_date);
        $leavePeriod    = new DatePeriod($leaveStartDate, new DateInterval('P1D'), $leaveEndDate->modify('+1 day'));
        $leaveDaysCount = iterator_count($leavePeriod);
        //dd($leaveDaysCount);
        $affectedBatches = $combinedAffectedBatches;

        // Adjust end_date for each affected batch
        foreach ($affectedBatches as $batchId => $batchInfo) {
            $batch = Batch::find($batchId);
            if ($batch) {
                // Fetch the batch schedule
                $batchSchedules = BatchSchedule::where('batch_id', $batchId)->get();
                $scheduledDays  = $batchSchedules->pluck('weekday')->map(function ($day) {
                    return strtolower($day);
                })->toArray();

                $studentBatches = $batch->studentBatches;
                foreach ($studentBatches as $studentBatch) {
                    $currentEndDate = new Carbon($studentBatch->end_date);
                    $missedSessions = $batchInfo['missedCount'];
                    // Sort scheduled days to ensure correct order
                    usort($scheduledDays, function ($a, $b) {
                        return (new Carbon($a))->dayOfWeek <=> (new Carbon($b))->dayOfWeek;
                    });
                    // Add missed sessions one by one
                    for ($i = 0; $i < $missedSessions; $i++) {
                        do {
                            $currentEndDate->addDay();
                        } while (! in_array(strtolower($currentEndDate->format('l')), $scheduledDays));
                    }
                    // Ensure the end date is moved to the next scheduled day
                    while (! in_array(strtolower($currentEndDate->format('l')), $scheduledDays)) {
                        $currentEndDate->addDay();
                    }
                    $studentBatch->end_date = $currentEndDate->toDateString();
                    $studentBatch->save();

                    ##update student batch also
                    $student_batches = StudentBatch::where('batch_id', $batchId)->get();

                    foreach ($student_batches as $student_batch) {
                        $student_batch->end_date = $currentEndDate->toDateString();
                        $student_batch->save();

                        $student_fee = StudentFee::where('student_id', $student_batch->student_id)->orderBy('id', 'desc')->first();
                        if ($student_fee) {
                            if ($student_fee->end_date < $currentEndDate) {
                                $student_fee->end_date = $currentEndDate->toDateString();
                                $student_fee->save();
                            } else {

                            }
                        }

                    }
                }
            }
        }

        // Collect student IDs from affected batches
        foreach ($affectedBatches as $batchId => $batchInfo) {
            $batch = Batch::find($batchId);
            if ($batch) {
                $studentBatches = $batch->studentBatches;
                foreach ($studentBatches as $studentBatch) {
                    $affectedBatches[$batchId]['studentIds'][] = $studentBatch->student_id;
                }
            }
        }

        // Collect all unique student IDs
        $affectedStudentIds = [];
        foreach ($affectedBatches as $batchInfo) {
            $affectedStudentIds = array_merge($affectedStudentIds, $batchInfo['studentIds']);
        }
        $affectedStudentIds = array_unique($affectedStudentIds);
        //dd($affectedStudentIds);

        // Fetch and update StudentFee records

        // foreach ($affectedStudentIds as $studentId) {
        //     $studentFees = StudentFee::where('student_id', $studentId)->get();
        //     foreach ($studentFees as $studentFee) {
        //         $newEndDate = Carbon::parse($studentFee->end_date)->addDays($leaveDaysCount);
        //         $studentFee->end_date = $newEndDate->format('Y-m-d');
        //         $studentFee->save();
        //     }
        // }
    }

    public function destroy($id)
    {

    }

    private function validateLeaveTimeWindow(Request $request): void
    {
        if (!$request->from_time || !$request->to_time) {
            return;
        }

        $fromDate = $request->from_date ? Carbon::parse($request->from_date)->toDateString() : null;
        $toDate = $request->to_date ? Carbon::parse($request->to_date)->toDateString() : $fromDate;
        $fromTime = Carbon::parse($request->from_time)->format('H:i:s');
        $toTime = Carbon::parse($request->to_time)->format('H:i:s');

        if ($fromDate && $fromDate === $toDate && $toTime === '00:00:00') {
            throw ValidationException::withMessages([
                'to_time' => 'Please use 11:59 PM as the day-ending leave time. Do not use 12:00 AM for the same day.',
            ]);
        }

        if ($fromDate && $fromDate === $toDate && $toTime <= $fromTime) {
            throw ValidationException::withMessages([
                'to_time' => 'Leave end time must be later than start time for the same day.',
            ]);
        }
    }

    private $rules = [
        'coach_id'  => 'Required',
        'from_date' => 'Required',
        'from_time' => 'Required',
        'to_time'   => 'Required',
        'reason'    => '',
        'status'    => '',
        ''          => '',
    ];

    private $customMessages = [
        'coach_id.Required'  => 'Required',
        'from_date.Required' => 'Required',
        'from_time.Required' => 'Required',
        'to_time.Required'   => 'Required',
    ];
}
