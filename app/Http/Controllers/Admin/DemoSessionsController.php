<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DemoScheduleMail;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\CoachAttendance;
use App\Models\CoachAvailability;
use App\Models\Coverupclass;
use App\Models\DemoLead;
use App\Models\DemoSession;
use App\Models\LeaveRequest;
use App\Models\Level;
use App\Models\Role;
use App\Services\CoachAvailabilityService;
use App\Services\ZoomMeetingService;
use Carbon\Carbon;
use DataTables;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DemoSessionsController extends Controller
{
    public function index(DemoLead $demolead)
    {
        $demoleads = DemoLead::all();
        return view('Admin.DemoSessions.index', compact('demoleads', 'demolead'));
    }

    public function data(DemoLead $demolead, Request $request)
    {
        $query = DemoSession::where('demolead_id', $demolead->id)->orderBy('created_at');
        //dd($demolead, $query);

        return DataTables::eloquent($query)
            ->editColumn('demolead_id', function ($demosessions) {
                $formattedTime = date('g:i A', strtotime($demosessions->demolead->time));
                $formattedKidsTime = date('g:i A', strtotime($demosessions->demolead->kids_time));
                $formattedDate = date('d-M-Y', strtotime($demosessions->date)); // Formats the date
                $weekday = date('l', strtotime($demosessions->date));

                $coachFirstName = '';
                $coachLastName = '';
                $zoomId = '';
                $zoomPassword = '';
                $zoomLink = '';

                $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $demosessions->demolead->mobile . "&text=" . urlencode($demosessions->getMessage());
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                return $demosessions->demolead->first_name . ' ' . $demosessions->demolead->last_name . ' &nbsp; &nbsp; ' . $whatsappLink;
            })
            ->editColumn('date', function ($demosessions) {
                return date('d-M-Y', strtotime($demosessions->date));
            })
            ->editColumn('time', function ($demosessions) {
                return date('h:i A', strtotime($demosessions->time));
            })
            ->editColumn('start_url', function ($demosessions) {
                if(!empty($demosessions->start_url)) {
                    return '<i class="ti ti-link cursor-pointer text-primary fs-5 copy-link" data-url="' . e($demosessions->start_url) . '" title="Copy Start URL"></i>';
                } 

                return '<span class="badge bg-secondary fs-1">N/A</span>';
            })
            ->editColumn('join_url', function ($demosessions) {
                if(!empty($demosessions->start_url)) {
                    return '<i class="ti ti-link cursor-pointer text-success fs-5 copy-link" data-url="' . e($demosessions->join_url) . '" title="Copy Start URL"></i>';
                } 
                return '<span class="badge bg-secondary fs-1">N/A</span>';
            })
            ->editColumn('recording', function ($demosessions) {

                // Parse the slot to get start and end times
                [$slotStartRaw, $slotEndRaw] = array_map('trim', explode('-', $demosessions->slot));

                // Create Carbon instances for slot start and end times
                $slotStart = Carbon::parse($demosessions->date.' '.$slotStartRaw);
                $slotEnd   = Carbon::parse($demosessions->date.' '.$slotEndRaw);

                // Define buffer time in minutes
                $bufferMinutes = 10;

                // Calculate the time window with buffer
                $windowStart = $slotStart->copy()->subMinutes($bufferMinutes)->format('H:i:s');
                $windowEnd   = $slotEnd->copy()->addMinutes($bufferMinutes)->format('H:i:s');

                // Query CoachAttendance within the time window
                $attendance = CoachAttendance::where('demolead_id', $demosessions->demolead_id)
                        ->where('coach_id', $demosessions->coach_id)
                        ->where('date', $demosessions->date)
                        ->whereBetween('time', [$windowStart, $windowEnd])
                        ->first();

                if (!empty($attendance?->recording_link)) {
                    return '<i class="ti ti-link cursor-pointer text-warning fs-5 copy-link" data-url="' . e($attendance->recording_link) . '" title="Copy Recording URL"></i>';
                }
                return '<span class="badge bg-secondary fs-1">N/A</span>';
            })
            ->editColumn('coach_id', function ($demosessions) {
                if (!empty($demosessions->coach) && isset($demosessions->coach->user)) {
                    return $demosessions->coach->user->first_name . ' ' . $demosessions->coach->user->last_name;
                } else {
                    return 'Not assigned';
                }
            })
            ->editColumn('slot', function ($demosessions) {
                if (is_null($demosessions->slot) || !str_contains($demosessions->slot, ' - ')) {
                    return 'slots not saved';
                }
                $times = explode(' - ', $demosessions->slot);
                $startTime = \Carbon\Carbon::parse($times[0])->format('g:i A');
                $endTime = \Carbon\Carbon::parse($times[1])->format('g:i A');
                return $startTime . ' - ' . $endTime;
            })
            ->editColumn('level', function ($demosessions) {
                return $demosessions->level ? $demosessions->level->name : ' ';
            })
            ->editColumn('status', function ($demosessions) {
                if ($demosessions->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input demosession-status-switch" type="checkbox" checked data-routekey="' . $demosessions->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input demosession-status-switch" type="checkbox" data-routekey="' . $demosessions->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($demosessions) use ($demolead) {
                $edit = '<a href="' . route('admin.demoleads.demo_sessions.edit', ['demolead' => $demolead->id, 'demo_session' => $demosessions->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i> &nbsp; Edit</a>';

                $delete = ''; 
                $status = '';
                if (!empty($demosessions->coach_attendance_status)) {
                    $badgeClass = $demosessions->coach_attendance_status === 'COMPLETED' ? 'bg-info' : 'bg-danger';
                    $status = '<span class="badge ' . $badgeClass . ' fs-1">From Coach : ' . $demosessions->coach_attendance_status . '</span>';
                }
                return $edit . '  ' . $delete . ' <br>' . $status;
            })
            ->addIndexColumn()
            ->rawColumns(['demolead_id', 'action', 'status', 'to_period', 'from_period', 'day_of_week', 'coach_id', 'start_url', 'join_url', 'slot', 'recording'])
            ->setRowId('id')
            ->make(true);
    }
    
    public function getCoachAvailability(Request $request)
    {
        $time = Carbon::parse($request->input('time'))->toTimeString();
        $date = Carbon::parse($request->input('date'))->toDateString();
        // dd($time, $date);

        $demolead = DemoLead::find($request->input('demolead_id'));
    
        $user = auth()->user();
        $countriesArray = [];
        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $roles = $user->roles()->pluck('name')->toArray();
            $roleCountries = Role::whereIn('name', $roles)->pluck('countries')->toArray();
            $uniqueCountries = [];
            foreach ($roleCountries as $countries) {
                if (is_string($countries)) {
                    $countriesArray = json_decode($countries, true);
                } else {
                    $countriesArray = $countries;
                }
                if (is_array($countriesArray)) {
                    $uniqueCountries = array_merge($uniqueCountries, $countriesArray);  
                }
            }
            $countriesArray = array_unique($uniqueCountries);
        }


        $dayOfWeek = Carbon::parse($date)->dayName;
        $coachAvailabilities = CoachAvailability::where('day_of_week', $dayOfWeek)
            ->whereHas('coach', function ($query) use ($countriesArray, $demolead) {
                if (auth()->user()->roles()->where('name', 'SuperAdmin')->exists()) {
                    $query->where('status', 'ACTIVE')->WhereJsonContains('country', $demolead->country);
                } else {
                    $query->where('status', 'ACTIVE')->where(function ($query) use ($countriesArray) {
                        if (isset($countriesArray) && !empty($countriesArray)) {
                            $query->where(function ($query) use ($countriesArray) {
                                foreach ($countriesArray as $country) {
                                    $query->orWhereJsonContains('country', $country);
                                }
                            });
                        }
                    });
                }
            })
            ->with('coach.user')
            ->get();
        // dd($coachAvailabilities);
        $availableCoaches = collect(); // empty collection to store available coaches
            
        foreach ($coachAvailabilities as $coachAvailability) {
            $coachId = $coachAvailability->coach_id;

            $batchSchedules = BatchSchedule::where('weekday', $dayOfWeek)
                ->whereHas('batch', function ($query) use ($coachId, $date) {
                    $query->where('coach_id', $coachId)
                        ->whereIn('status', ['ACTIVE', 'STANDBY'])
                        ->whereHas('studentBatches', function ($q) use ($date) {
                            $q->eligibleOn($date);
                        });
                })
                ->get(); 
            
            $isInBatchSchedule = false;
            foreach ($batchSchedules as $batchSchedule) {
                $start_time = Carbon::parse($batchSchedule->from_time)->format('H:i');
                $end_time = Carbon::parse($batchSchedule->to_time)->format('H:i');
                if ($time >= $start_time && $time <= $end_time) {
                    $isInBatchSchedule = true;
                    break;
                }
            }

            if ($isInBatchSchedule) {
                continue; // Skip this coach if the time is within their batch schedule
            }

            $coverupclass = Coverupclass::where('new_coach_id', $coachId)
                ->whereDate('date', $date)
                ->first();

            if ($coverupclass) {
                $batchSchedule = BatchSchedule::where('id', $coverupclass->batchschedule_id)->first();

                if ($batchSchedule) {
                    $start_time = Carbon::parse($batchSchedule->from_time)->format('H:i');
                    $end_time = Carbon::parse($batchSchedule->to_time)->format('H:i');

                    // Skip this coach if the time is within their batch schedule
                    if ($time >= $start_time && $time <= $end_time) {
                        continue;
                    }
                }
            }

            // Check if the coach has an approved leave request for the specified date and time
            $leaveRequest = LeaveRequest::where('coach_id', $coachId)
                ->where('status', 'APPROVED')
                ->where(function ($query) use ($date, $time) {
                    $query->where(function ($query) use ($date, $time) {
                        $query->where('from_date', '<=', $date)
                            ->where('to_date', '>=', $date)
                            ->where(function ($query) use ($time) {
                                $query->where(function ($query) use ($time) {
                                    $query->where('from_time', '<=', $time)
                                        ->where('to_time', '>', $time);
                                })
                                    ->orWhere(function ($query) use ($time) {
                                        $query->where('from_time', '<=', $time)
                                            ->where('to_time', '>=', $time);
                                    });
                            });
                    });
                })
                ->first();

            // If there is an approved leave request, check the time boundaries
            if ($leaveRequest) {
                $leaveEndTime = Carbon::createFromFormat('H:i:s', $leaveRequest->to_time)->format('H:i:s');
                if ($time < $leaveEndTime) {
                    continue; // Skip this coach if the current time is within the leave period
                }
            }

            $availableSlots = $this->calculateAvailableSlots($coachId, $date, $time);
            foreach ($availableSlots as $slot) {
                list($slotStart, $slotEnd) = explode(' - ', $slot);
                if ($time >= $slotStart && $time < $slotEnd) {
                    $availableCoaches->push($coachAvailability); // Add the coach availability to the collection
                    break; // No need to check further slots for this coach
                }
            }
        }

        // dd(11);

        // dd($availableCoaches);
        // "country" => "["USA","CANADA","UK","EUROPEAN UNION"]"
        // "country" => "["USA","CANADA","NEWZEALAND","INDIA","UAE","QATAR","BAHRAIN","KUWAIT","OMAN"]"
 

        return response()->json($availableCoaches); // Return the collection of available coaches
    }

    protected function getBookedSlotsForCoach($coachId, $date)
    {
        $dateObject = new DateTime($date);
        $bookedSlots = DemoSession::where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->whereDate('date', $dateObject->format('Y-m-d'))
            ->pluck('slot')->all();
        return $bookedSlots;
    }

    public function getAvailableSlots(Request $request)
    {
        $coachId = $request->input('coach_id');
        $date = $request->input('date');
        $time = $request->input('time');
        $availableSlots = $this->calculateAvailableSlots($coachId, $date, $time);
        // dd($availableSlots);
        $matchingSlot = null;
        foreach ($availableSlots as $slot) {
            list($slotStart, $slotEnd) = explode(' - ', $slot);
            $time00 = Carbon::parse($time)->toTimeString();
            if ($time00 == $slotStart) {
                $matchingSlot = $slot;
                break;
            }
        }
        // dd($matchingSlot);
        if ($matchingSlot) {
            return response()->json(['slot' => $matchingSlot]);
        } else {
            return response()->json(['slot' => '']);
        }
    }

    /* 
    * What: Calculate available time slots for a coach on a specific date and time.
    * Why: To determine when a coach is available for scheduling demo sessions, considering their
    *      availability, existing batch schedules, and already booked demo sessions.
    */
    private function calculateAvailableSlots($coachId, $date, $time)
    {
        $requestTime = Carbon::parse($time)->toTimeString();
        $dayOfWeek = Carbon::parse($date)->dayName;
        $dayName = Carbon::parse($date)->format('l');

        // --------------------------------------------------------------- ::
        // Using Coach's Coach Availability section we get, When actually the coach takes demo and batch .
        $availabilities = CoachAvailability::with('periods')
            ->where('coach_id', $coachId)
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'ACTIVE')
            ->get();

        $allPeriodsForAvailabilities = $availabilities->flatMap(function ($availability) {
            return $availability->periods;
        });

        $slots = $allPeriodsForAvailabilities->flatMap(function ($period) {
            return $this->generateSlotsFromPeriod($period);
        })->all();

        $batches = Batch::where('coach_id', $coachId)
            ->whereIn('status', ['ACTIVE', 'STANDBY'])
            ->whereHas('batchSchedules', function ($query) use ($dayName) {
                $query->where('weekday', $dayName);
            })
            ->whereHas('studentBatches', function ($query) use ($date) {
                $query->eligibleOn($date);
            })
            ->with(['batchSchedules' => function ($query) use ($dayName) {
                $query->where('weekday', $dayName);
            }])
            ->get();

        $generatedSlots = $batches->flatMap(function ($batch) {
            return $batch->batchSchedules->flatMap(function ($schedule) {
                return $this->generateSlotsFromSchedule($schedule);
            });
        })->all();

        // Filter available slots
        $availableSlots = collect($slots)->reject(function ($slot) use ($generatedSlots) {
            return in_array($slot, $generatedSlots);
        })->values()->all();

        $bookedSlots = $this->getBookedSlotsForCoach($coachId, $date);

        $finalAvailableSlots = collect($availableSlots)->reject(function ($slot) use ($bookedSlots) {
            return in_array($slot, $bookedSlots);
        })->values()->all();
        // dd($finalAvailableSlots);
        usort($finalAvailableSlots, function ($a, $b) {
            $aTime = Carbon::createFromFormat('H:i:s', explode(' - ', $a)[0]);
            $bTime = Carbon::createFromFormat('H:i:s', explode(' - ', $b)[0]);
            return $aTime->gt($bTime);
        });
        return $finalAvailableSlots;
    }

    /*
    * What: Generate 30-minute time slots from a given schedule.
    * Why: To create manageable time slots for scheduling purposes, ensuring that sessions can be booked
    *      within the defined availability of a coach or resource.
    */
    protected function generateSlotsFromSchedule($schedule)
    {
        if ($schedule->from_time == '' || $schedule->to_time == '') {
            return [];
        }

        $slots = [];
        $startTime = Carbon::createFromFormat('H:i:s', $schedule->from_time);
        $endTime = Carbon::createFromFormat('H:i:s', $schedule->to_time);
        while ($startTime->lessThan($endTime)) {
            $nextTime = (clone $startTime)->addMinutes(30);
            if ($nextTime->lessThanOrEqualTo($endTime)) {
                $slots[] = $startTime->format('H:i:s') . ' - ' . $nextTime->format('H:i:s');
            }
            $startTime->addMinutes(30);
        }
        return $slots;
    }

    /*
    * What: Generate 30-minute time slots from a given period.
    * Why: To create manageable time slots for scheduling purposes, ensuring that sessions can be booked
    *      within the defined availability of a coach or resource.
    */
    protected function generateSlotsFromPeriod($period)
    {
        $slots = [];
        $startTime = Carbon::createFromTimeString($period->from_period);
        $endTime = Carbon::createFromTimeString($period->to_period);

        while ($startTime->lessThan($endTime)) {
            $endSlotTime = $startTime->copy()->addMinutes(30);
            // Special case for 23:30:00 to 23:59:00
            if ($endSlotTime->greaterThan($endTime)) {
                if ($startTime->format('H:i:s') == '23:30:00' && $endTime->format('H:i:s') == '23:59:00') {
                    $slots[] = $startTime->format('H:i:s') . ' - ' . $endTime->format('H:i:s');
                }
                break;
            }
            $slots[] = $startTime->format('H:i:s') . ' - ' . $endSlotTime->format('H:i:s');
            $startTime->addMinutes(30);
        }

        return $slots;
    }

    public function create(DemoLead $demolead)
    {
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();
        // dd($coaches);
        $coachAvailabilities = collect();
        if (!$coaches->isEmpty()) {
            $coachAvailabilities = CoachAvailability::where('coach_id', $coaches->first()->id)->get();
        }
        $slots = [];
        $levels = Level::where('status', 'ACTIVE')->get();
        $saved_coach_name = null;
        $saved_slot = null;
        return view('Admin.DemoSessions.form', compact('demolead', 'coaches', 'coachAvailabilities', 'slots', 'levels', 'saved_coach_name', 'saved_slot'));
    }

    public function store(Request $request, CoachAvailabilityService $availability)
    {
        $request->validate($this->rules, $this->customMessages);

        $coach = Coach::find($request->coach_id);
        $demolead = DemoLead::find($request->demolead_id);
        $slot = $availability->parseSlot($request->slot);

        if (!$slot) {
            return response()->json([
                'message' => 'Selected slot is invalid.',
                'errors' => [
                    'slot' => ['Please select a valid slot.'],
                ],
            ], 422);
        }

        $coachValidation = $availability->validateCoachForSingleEvent(
            (int) $request->coach_id,
            $request->date,
            $slot[0],
            $slot[1],
            [$demolead?->country]
        );

        if (!$coachValidation['ok']) {
            return response()->json([
                'message' => 'Selected coach is not available.',
                'errors' => [
                    'coach_id' => [$coachValidation['message']],
                ],
            ], 422);
        }

        $start_url = null;
        $join_url = null;
        $zoom_meeting_id = null;
        $zoom_meeting_uuid = null;

        if(empty($coach->zoom_id) || empty($coach->zoom_api_key) || empty($coach->zoom_client_secret) || empty($coach->zoom_user_id)) {
            if (empty($zoom_meeting_id)) {
                return response()->json([
                    'message' => 'Meeting link not created',
                    'errors' => [
                        'zoom' => ['Please configure all Zoom credentials for the selected coach.']
                    ]
                ], 422);
            }
        }

        if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {
            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id 
            );

            $meetingData = [
                'title'              => 'Demo-Session - '.$coach->user->first_name,
                'duration_in_minute' => 40,
                'start_date_time'    => Carbon::tomorrow()->setTime(10, 0)->toIso8601String(), // make sure this is correct
            ];

            $zoomResponse = $zoomMeetingService->createDemoSessionMeeting( $meetingData, $coach->zoom_user_id);

            $start_url = $zoomResponse['start_url'] ?? null;
            $join_url  =  $zoomResponse['join_url'] ?? null;
            $zoom_meeting_id = $zoomResponse['id'] ?? null;
            $zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;

        } 

        if (empty($zoom_meeting_id)) {
            return response()->json([
                'message' => 'Meeting link not created',
                'errors' => [
                    'zoom' => ['Meeting link not created. Please try again.']
                ]
            ], 422);
        }

        $demosessions = new DemoSession;
        $demosessions->demolead_id = $demolead->id;
        $demosessions->fill($request->all());
        $demosessions->status = 'ACTIVE';
        $demosessions->start_url = $start_url;
        $demosessions->join_url = $join_url;
        $demosessions->zoom_meeting_id = $zoom_meeting_id;
        $demosessions->zoom_meeting_uuid = $zoom_meeting_uuid;
        $demosessions->save();
        $demolead->status = 'SCHEDULED';
        $demolead->save();
        $coach = Coach::find($request->coach_id);

        $date = $request->input('date');
        $time = $request->input('time');
        $targetTimeZone = $demolead->kids_time_zone;

        $indiaStartTime = $date . ' ' . $time;
        $starttime = Carbon::parse($indiaStartTime, 'Asia/Kolkata');
        $convertStartTimeInTimeZone = $starttime->setTimezone(convertTimeZoneString($targetTimeZone));

        // Don't format yet — keep it as Carbon
        $convertedStartTime = $convertStartTimeInTimeZone;

        $demolead->date = $request->date;
        $demolead->time = $request->time;
        $demolead->kids_date = $convertedStartTime->format('Y-m-d');
        $demolead->kids_time = $convertedStartTime->format('H:i:s');
        $demolead->save();


        // Mail::to($demolead->user->email)->send(new DemoScheduleMail($demolead, $demosessions));

        return response()->json([
            'status' => 'success',
            'message' => 'DemoSession Created Successfully',
            'demosession' => $demosessions,
        ], 201);
    }

    public function edit(Request $request, DemoLead $demolead, DemoSession $demosessions)
    {
        $demoleads = DemoLead::all();
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();
        $levels = Level::where('status', 'ACTIVE')->get();

        $slots = $this->getAvailableSlots(new Request([
            'coach_id' => $demosessions->coach_id,
            'time' => $demosessions->time,
            'date' => $demosessions->date,
        ]))->getData();

        $saved_coach_name = null;
        $saved_coach_id = null; // Initialize saved_coach_id
        $saved_slot = null;
        $saved_slot_normal = $demosessions->slot; // Assign original slot value to saved_slot_normal
        $coach = Coach::with('user')->find($demosessions->coach_id);
        if ($coach && $coach->user) {
            $saved_coach_name = $coach->user->first_name . ' ' . $coach->user->last_name;
            $saved_coach_id = $coach->id; // Assign coach_id
        }
        if ($demosessions->slot) {
            list($start_time, $end_time) = explode(' - ', $demosessions->slot);
            $start_dt = new DateTime($start_time);
            $end_dt = new DateTime($end_time);

            $saved_slot = $start_dt->format('g : i a') . ' to ' . $end_dt->format('g : i a');
        }
        return view('Admin.DemoSessions.form', compact('demosessions', 'demoleads', 'demolead', 'coaches', 'slots', 'levels', 'saved_coach_name', 'saved_slot', 'saved_coach_id', 'saved_slot_normal'));
    }

    public function update(DemoLead $demolead, Request $request, DemoSession $demosessions, CoachAvailabilityService $availability)
    {
        $rules = [
            'demolead_id' => 'required',
        ];
        $customMessages = [
            'demolead_id.required' => 'DemoSession ID is required',
        ];
        $request->validate($rules, $customMessages);
        $fieldsToUpdate = $request->only(['demolead_id', 'date', 'time', 'level_id']);
        $coachIdForValidation = $request->input('coach_id') ?: $request->input('saved_coach_id', $demosessions->coach_id);
        $slotForValidation = $request->input('slot') ?: $request->input('saved_slot_normal', $demosessions->slot);
        $dateForValidation = $request->input('date', $demosessions->date);
        $slot = $availability->parseSlot($slotForValidation);

        if (!$slot) {
            return response()->json([
                'message' => 'Selected slot is invalid.',
                'errors' => [
                    'slot' => ['Please select a valid slot.'],
                ],
            ], 422);
        }

        $coachValidation = $availability->validateCoachForSingleEvent(
            (int) $coachIdForValidation,
            $dateForValidation,
            $slot[0],
            $slot[1],
            [$demolead?->country],
            'demo',
            $demosessions->id
        );

        if (!$coachValidation['ok']) {
            return response()->json([
                'message' => 'Selected coach is not available.',
                'errors' => [
                    'coach_id' => [$coachValidation['message']],
                ],
            ], 422);
        }

        if (is_null($request->input('coach_id')) && is_null($request->input('slot'))) {
            $demosessions->coach_id = $request->input('saved_coach_id', $demosessions->coach_id);
            $demosessions->slot = $request->input('saved_slot_normal', $demosessions->slot);
        } else {
            $fieldsToUpdate = array_merge($fieldsToUpdate, $request->only(['coach_id', 'slot']));
        }
        $demosessions->fill($fieldsToUpdate);
        $demosessions->save();

        $date = $request->input('date');
        $time = $request->input('time');
        $targetTimeZone = $demolead->kids_time_zone;
        $indiaStartTime = $date . ' ' . $time;
        $starttime = Carbon::parse($indiaStartTime, 'Asia/Kolkata');
        $convertStartTimeInTimeZone = $starttime->setTimezone(convertTimeZoneString($targetTimeZone));

        // Don't format yet — keep it as Carbon
        $convertedStartTime = $convertStartTimeInTimeZone;
        $demolead->date = $request->date;
        $demolead->time = $request->time;
        $demolead->kids_date = $convertedStartTime->format('Y-m-d');
        $demolead->kids_time = $convertedStartTime->format('H:i:s');
        $demolead->save();  

        if (!is_null($request->input('level_id'))) {
            $demolead = DemoLead::find($request->input('demolead_id'));
            if ($demolead) {
                $demolead->status = 'DEMO DONE';
                $demolead->save();
            }
        }

        $coach = Coach::find($demosessions->coach_id);

        if(!empty($coach)) {
            // if(empty($demosessions->start_url)){
                if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {

                    $zoomMeetingService = new ZoomMeetingService(
                        $coach->zoom_api_key,
                        $coach->zoom_client_secret,
                        $coach->zoom_id 
                    );
                    
                    $meetingData = [
                        'title'              => 'Demo-Session - '.$coach->user->first_name,
                        'duration_in_minute' => 40,
                        'start_date_time'    => Carbon::tomorrow()->setTime(10, 0)->toIso8601String(), // make sure this is correct
                    ];
                    
                    $zoomResponse = $zoomMeetingService->createDemoSessionMeeting($meetingData, $coach->zoom_user_id);
                    $demosessions->start_url = $zoomResponse['start_url'] ?? '';
                    $demosessions->join_url  = $zoomResponse['join_url'] ?? '';
                    $demosessions->zoom_meeting_id  = $zoomResponse['id'] ?? null;
                    $demosessions->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
                    $demosessions->save();
                }
            // }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Coach not found or not assigned',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'DemoSession Updated Successfully',
            'demosession' => $demosessions,
        ], 201);
    }

    public function destroy($demoleadId, $demosessions)
    {
        if ($demosessions) {
            $demosessions->delete();
            return response()->json([
                'message' => 'Student fee deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Student fee not found',
            ], 404);
        }
    }

    public function changeStatus(Request $request)
    {
        $demosessions = DemoSession::findByKey($request->route_key);
        $demosessions->status = $request->status;
        $demosessions->save();

        $demoleadId = $demosessions->demolead_id;
        $demolead = DemoLead::find($demoleadId);

        if ($request->status === 'INACTIVE') {
            // Check if all DemoSessions for this DemoLead are INACTIVE
            $activeSessionsCount = DemoSession::where('demolead_id', $demoleadId)
                ->where('status', 'ACTIVE')
                ->count();
            if ($activeSessionsCount == 0) {
                // Update the status of the DemoLead to RESCHEDULED
                $demolead->status = 'RESCHEDULED';
                $demolead->save();
            }
        } elseif ($request->status === 'ACTIVE') {
            // Update the status of the DemoLead to SCHEDULED
            $demolead->status = 'SCHEDULED';
            $demolead->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => $demosessions->title . ' has been marked ' . $demosessions->status . ' successfully',
            'demosession' => $demosessions,
        ], 201);
    }
    

    private $rules = [
        'demolead_id' => 'required',
        'coach_id' => 'required',
        'slot' => 'required',
    ];

    private $customMessages = [
        'demolead_id.required' => 'DemoSession ID is required',
        'coach_id.required' => 'Coach ID is required',
        'slot.required' => 'Slot is required',
    ];
}
