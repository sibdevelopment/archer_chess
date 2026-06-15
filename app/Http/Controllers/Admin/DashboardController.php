<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DemoCompleteMail;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\CoachAttendance;
use App\Models\CoachAvailability;
use App\Models\CoachAvailabilityPeriod;
use App\Models\Coverupclass;
use App\Models\DelayedBatch;
use App\Models\DemoLead;
use App\Models\DemoSession;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Level;
use App\Models\Masterclass;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBatch;
use App\Models\StudentFee;
use App\Models\User;
use App\Services\ZoomMeetingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;


class DashboardController extends Controller
{
    public function index1(Request $request): View
    {  
        return view('Admin.Dashboard.SuperAdmin.dashboard-index');
    }

    // private function markBackdatedAttendance()
    // {
    //     $startDateTime = Carbon::parse('2024-07-29 03:30:00');
    //     $endDateTime = Carbon::parse('2024-08-01 07:30:00');

    //     $dates = [];
    //     $current = $startDateTime->copy();
    //     while ($current->lte($endDateTime)) {
    //         $dates[] = $current->toDateString();
    //         $current->addDay();
    //     }
    //     foreach ($dates as $date) {
    //         $dayName = Carbon::parse($date)->format('l');

    //         $batches = Batch::with(['batchSchedules' => function ($q) use ($dayName) {
    //             $q->where('weekday', $dayName)->where('status', 'ACTIVE');
    //         }])->where('status', 'ACTIVE')->get();

    //         foreach ($batches as $batch) {
    //             foreach ($batch->batchSchedules as $schedule) {
    //                 $sessionDateTime = Carbon::parse($date . ' ' . $schedule->from_time);

    //                 if ($sessionDateTime->lt($startDateTime) || $sessionDateTime->gt($endDateTime)) {
    //                     continue;
    //                 }
    //                 $existingCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
    //                     ->whereDate('date', $date)
    //                     ->exists();

    //                 if ($existingCoachAttendance) {
    //                     continue;
    //                 }

    //                 CoachAttendance::create([
    //                     'coach_id' => $batch->coach_id,
    //                     'type'     => 'BATCH',
    //                     'batch_id' => $batch->id,
    //                     'date'     => $date,
    //                     'time'     => $schedule->from_time,
    //                     'status'   => 'COMPLETED',
    //                 ]);
    //                 $students = StudentBatch::where('batch_id', $batch->id)
    //                     ->where('status', 'ACTIVE')
    //                     ->whereDate('start_date', '<=', $date)
    //                     ->whereDate('end_date', '>=', $date)
    //                     ->get();

    //                 foreach ($students as $student) {
    //                     StudentAttendance::create([
    //                         'type'       => 'BATCH',
    //                         'student_id' => $student->student_id,
    //                         'batch_id'   => $batch->id,
    //                         'level_id'   => $student->level_id,
    //                         'date'       => $date,
    //                         'time'       => $schedule->from_time,
    //                         'status'     => 'ABSENT',
    //                         'remark'     => '',
    //                         'coach_id'   => $batch->coach_id,
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    //     return "Attendance marked successfully for all eligible batches.";
    // }

    private function markBackdatedAttendance()
    {
        $startDateTime = Carbon::parse('2024-07-29 03:30:00');
        $endDateTime = Carbon::parse('2024-08-01 07:30:00');

        // $startDateTime = Carbon::parse('2024-07-29');
        // $endDateTime = Carbon::parse('2024-07-29');

        $dates = [];
        $current = $startDateTime->copy();
        while ($current->lte($endDateTime)) {
            $dates[] = $current->toDateString();
            $current->addDay();
        }

        $markedAttendances = [];

        foreach ($dates as $date) {
            $dayName = Carbon::parse($date)->format('l');

            // $batches = Batch::with(['batchSchedules' => function ($q) use ($dayName) {
            //     $q->where('weekday', $dayName)->where('status', 'ACTIVE');
            // }])->where('status', 'ACTIVE')->count();
            $batches = Batch::where('status', 'ACTIVE')
                ->whereHas('batchSchedules', function ($q) use ($dayName) {
                    $q->where('weekday', $dayName)->where('status', 'ACTIVE');
                })
                ->get();
            $count = Batch::where('status', 'ACTIVE')
                ->whereHas('batchSchedules', function ($q) use ($dayName) {
                    $q->where('weekday', $dayName)->where('status', 'ACTIVE');
                })
                ->count();
            
            foreach ($batches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    $sessionDateTime = Carbon::parse($date . ' ' . $schedule->from_time);

                    if ($sessionDateTime->lt($startDateTime) || $sessionDateTime->gt($endDateTime)) {
                        continue;
                    }

                    $existingCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
                        ->whereDate('date', $date)
                        ->exists();

                    if ($existingCoachAttendance) {
                        continue;
                    }

                    CoachAttendance::create([
                        'coach_id' => $batch->coach_id,
                        'type'     => 'BATCH',
                        'batch_id' => $batch->id,
                        'date'     => $date,
                        'time'     => $schedule->from_time,
                        'status'   => 'COMPLETED',
                    ]);

                    $students = StudentBatch::where('batch_id', $batch->id)
                        ->where('status', 'ACTIVE')
                        ->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date)
                        ->get();

                    foreach ($students as $student) {
                        StudentAttendance::create([
                            'type'       => 'BATCH',
                            'student_id' => $student->student_id,
                            'batch_id'   => $batch->id,
                            'level_id'   => $student->level_id,
                            'date'       => $date,
                            'time'       => $schedule->from_time,
                            'status'     => 'ABSENT',
                            'remark'     => '',
                            'coach_id'   => $batch->coach_id,
                        ]);
                    }

                    // Record marked attendance
                    $markedAttendances[] = [
                        'batch_id' => $batch->id,
                        'batch_name' => $batch->name ?? 'N/A',
                        'date' => $date,
                        'time' => $schedule->from_time,
                    ];
                }
            }
        }

        // dd($markedAttendances);

        // return [
        //     'message' => 'Attendance marked successfully for all eligible batches.',
        //     'marked' => $markedAttendances
        // ];
    }



    public function index(Request $request): View|RedirectResponse
    { 
        // $user = Auth::user();
        // $roles = $user->roles->pluck('name')->toArray();
        // $permissionNames = $user->getPermissionNames();
        // $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        // dd($permissionNames);

        // This function is to remove duplicate attendance records for year 2026
        $coach_duplicates = CoachAttendance::select( 'batch_id', 'date', 'coach_id', DB::raw('count(*) as count'))
            ->groupBy( 'batch_id', 'date', 'coach_id')
            ->where('type', 'Batch')
            ->whereYear('date', 2026)
            ->having('count', '>', 1)
            ->get();

        foreach ($coach_duplicates as $dup) {
            $records = CoachAttendance::where('type', 'Batch')
                ->where('batch_id', $dup->batch_id)
                ->where('date', $dup->date)
                ->where('coach_id', $dup->coach_id)
                ->orderBy('id') 
                ->get();

            // Sort to prioritize keeping the one with data
            $toKeep = $records->first(function ($r) {
                return !empty($r->homework_link);
            });

            // If none has data, keep the second one
            if (!$toKeep) {
                $toKeep = $records->get(1) ?? $records->first();
            }

            // Delete others
            foreach ($records as $record) {
                if ($record->id !== $toKeep->id) {
                    $record->delete();
                }
            }
        }

        // Similar process for StudentAttendance duplicates
        $duplicates = StudentAttendance::select('student_id', 'batch_id', 'date', 'coach_id', DB::raw('count(*) as count'))
            ->groupBy('student_id', 'batch_id', 'date', 'coach_id')
            ->where('type', 'Batch')
            ->whereYear('date', 2026)
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $records = StudentAttendance::where('type', 'Batch')
                ->where('student_id', $dup->student_id)
                ->where('batch_id', $dup->batch_id)
                ->where('date', $dup->date)
                ->where('coach_id', $dup->coach_id)
                ->orderBy('id') 
                ->get();

            // Sort to prioritize keeping the one with data
            $toKeep = $records->first(function ($r) {
                return !empty($r->homework_link) || !empty($r->chapter_name);
            });

            // If none has data, keep the second one
            if (!$toKeep) {
                $toKeep = $records->get(1) ?? $records->first();
            }

            // Delete others
            foreach ($records as $record) {
                if ($record->id !== $toKeep->id) {
                    $record->delete();
                }
            }
        }

        $user = Auth::user();
        if ($user->hasRole('Coach')) {
            return $this->indexCoach($request);
        } else {
            return $this->indexSuperAdmin($request);
        }
    } 
    
    private function indexCoach(Request $request)
    {
        
        $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $todayDate       = Carbon::now()->toDateString();
        $todayDayOfWeek  = Carbon::now()->format('l');

        $user  = auth()->user();
        $coach = null;
        if ($user) {
            $coach = Coach::where('user_id', $user->id)->first();
        }

        if ($coach->status == 'INACTIVE') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact support.');
        }

        

        $coachId = $coach ? $coach->id : null;
        if ($coach->country) {
            $holidays = Holiday::where('status', 'ACTIVE')
                ->where(function ($query) use ($coach) {
                    foreach ($coach->country as $country) {
                        $query->orWhereJsonContains('country', $country);
                    }
                })
                ->where(function ($query) use ($todayDate) {
                    $query->where(function ($q) use ($todayDate) {
                        $q->whereNotNull('end_date')
                            ->whereDate('end_date', '>=', $todayDate);
                    })->orWhere(function ($q) use ($todayDate) {
                        $q->whereDate('start_date', '>=', $todayDate);
                    });
                })
                ->get();
        } else {
            $holidays = collect();
        }

        $date          = $request->input('date', Carbon::now()->format('Y-m-d'));
        $yesterdayDate = Carbon::now()->subDay()->format('Y-m-d');
        $dayName       = Carbon::parse($date)->format('l');
        $yesdayDayName = Carbon::parse($yesterdayDate)->format('l');

        $todayDate = Carbon::now()->format('Y-m-d');

        if ($date !== $todayDate) {
            return response()->json(['message' => 'Viewing schedules other than today is not allowed.'], 208);
        }

        $yesterdayBatches = Batch::with(['batchSchedules' => function ($query) use ($yesdayDayName, $yesterdayDate) {
            $query->where('weekday', $yesdayDayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($yesterdayDate) {
                $query
                // ->where('status', 'ACTIVE') // Count only 'ACTIVE' studentBatches
                    ->where('start_date', '<=', $yesterdayDate)
                    ->where('end_date', '>=', $yesterdayDate);
            }])
        // ->whereHas('studentBatches', function ($query) use ($yesterdayDate) {
        //     $query->where('status', 'ACTIVE') // Ensure studentBatches have 'ACTIVE' status
        //         ->where('start_date', '<=', $yesterdayDate)
        //         ->where('end_date', '>=', $yesterdayDate);
        // })
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();


        // Batches Data ::
        $batches = Batch::with(['batchSchedules' => function ($query) use ($dayName, $date) {
            $query->where('weekday', $dayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($date) {
                $query->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            }])
            ->whereHas('studentBatches', function ($query) use ($date) {
                $query->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();

        $yesdayCoachLeave = LeaveRequest::where('coach_id', $coachId)
            ->whereDate('from_date', '=', $yesterdayDate)
            ->where('status', 'APPROVED')
            ->first();

        $fromLeaveTime = null;

        if ($yesdayCoachLeave) {
            $fromLeaveTime = $yesdayCoachLeave->from_time;
        }

        $combinedData = [];

        if (in_array("UK", $coach->country)) {
            foreach ($yesterdayBatches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    if ($yesdayCoachLeave) {
                        if ($schedule->from_time >= $fromLeaveTime) {
                            continue;
                        }
                    }
                    $status                  = $schedule->status;
                    $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                        ->where('coach_id', $coachId)
                        ->whereDate('date', $yesterdayDate)
                        ->orderBy('id', 'desc')->first();

                    if ($latest_batch_attendance) {
                        $status = $latest_batch_attendance->status;
                    } else {
                        $today_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                            ->where('coach_id', $coachId)
                            ->whereDate('date', $date)
                            ->orderBy('id', 'desc')->first();
                        if ($today_batch_attendance) {
                            $status = $today_batch_attendance->status;
                        }
                    }
                    $combinedData[] = [
                        'id'              => $batch->id,
                        'name'            => $batch->name,
                        'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                        'status'          => $status,
                        'type'            => 'Yesterday Batch',
                        'active_students' => $batch->active_students_count,
                        'start_url' => $batch->start_url,
                        'homework_link' => $latest_batch_attendance ? $latest_batch_attendance->homework_link : null,
                        'attendance_exists' => $latest_batch_attendance ? true : false,
                        'attendance_time' => $latest_batch_attendance ? $latest_batch_attendance->created_at->format('Y-m-d H:i:s') : null,

                    ];
                }
            }
        }


        $todayCoachLeave = LeaveRequest::where('coach_id', $coachId)
            ->whereDate('from_date', '=', $date)
            ->where('status', 'APPROVED')
            ->first();

        if ($todayCoachLeave) {
            $fromLeaveTime = $todayCoachLeave->from_time;
            $toLeaveTime   = $todayCoachLeave->to_time;
        }

        // $combinedData = [];
        foreach ($batches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
                if ($todayCoachLeave) {
                    if ($schedule->from_time >= $fromLeaveTime && $schedule->to_time <= $toLeaveTime) {
                        continue;
                    }
                }
                $status                  = $schedule->status;
                $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                    ->where('coach_id', $coachId)
                    ->whereDate('date', $date)
                    ->orderBy('id', 'desc')->first();

                if ($latest_batch_attendance) {
                    $status = $latest_batch_attendance->status;
                }

                $students = StudentBatch::where('batch_id', $batch->id)
                ->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->pluck('student_id')
                    ->toArray();

                $studentCount = count($students);

                $combinedData[] = [
                    'id'              => $batch->id,
                    'name'            => $batch->name,
                    'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                    'status'          => $status,
                    'type'            => 'Batch',
                    'active_students' => $studentCount,
                    'start_url' => $batch->start_url,
                    'homework_link' => $latest_batch_attendance ? $latest_batch_attendance->homework_link : null,
                    'attendance_exists' => $latest_batch_attendance ? true : false,
                    'attendance_time' => $latest_batch_attendance ? $latest_batch_attendance->created_at->format('Y-m-d H:i:s') : null,
                ];

                if ($batch->name == 'Abhijeet-WTH4:30AM') {
                }
            }
        }


        // Demo Session Data ::
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->where('date', $date)
            ->get();


        foreach ($demoSessions as $session) {


             $demoAttendance = CoachAttendance::where('demolead_id', $session->id)
                            ->where('coach_id', $coachId)
                            ->whereDate('date', $date)
                            ->first();

            // dd($session);
            list($startTime, $endTime) = explode(' - ', $session->slot);
            $formattedStartTime        = Carbon::createFromFormat('H:i:s', $startTime)->format('h:i A');
            $formattedEndTime          = Carbon::createFromFormat('H:i:s', $endTime)->format('h:i A');
            $combinedData[]            = [
                'id'              => $session->id, // Include the session ID
                'name'            => $session->demolead->first_name . ' ' . $session->demolead->last_name,
                'slot'            => $formattedStartTime . ' - ' . $formattedEndTime,
                'status'          => $session->demolead->status,
                'type'            => 'Demo',
                'active_students' => 1, 
                'start_url' => $session->start_url,
                'homework_link' => $session ? $session->homework_link : null,
                'attendance_exists' =>  $demoAttendance ? true : false,
                'attendance_time' => $session ? $session->created_at->format('Y-m-d H:i:s') : null,
            ];
        }

        //Cover up Class
        $coverupClasses = Coverupclass::where('new_coach_id', $coachId)
            ->where('date', $date)
            ->get();
        $coverupClassBatchIds = $coverupClasses->pluck('batch_id')->toArray();
        $coverupClassBatches  = Batch::with(['batchSchedules' => function ($query) use ($dayName, $date) {
            $query->where('weekday', $dayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($date) {
                $query->where('status', 'ACTIVE') // Count only 'ACTIVE' studentBatches
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            }])
            ->whereHas('studentBatches', function ($query) use ($date) {
                $query->where('status', 'ACTIVE') // Ensure studentBatches have 'ACTIVE' status
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })
            ->whereIn('id', $coverupClassBatchIds)
            ->where('status', 'ACTIVE')
            ->get();

        foreach ($coverupClassBatches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
                $status = $schedule->status;
                $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                    // ->where('coach_id', $coachId)
                    ->whereDate('date', $date)
                    ->orderBy('id', 'desc')->first();
                if ($latest_batch_attendance) {
                    $status = $latest_batch_attendance->status;
                } 
                $combinedData[] = [
                    'id'              => $batch->id,
                    'name'            => $batch->name,
                    'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                    'status'          => $status,
                    'type'            => 'Coverup',
                    'active_students' => $batch->active_students_count,
                    'start_url' => $batch->start_url,
                    'homework_link' => $latest_batch_attendance ? $latest_batch_attendance->homework_link : null,
                    'attendance_exists' => $latest_batch_attendance ? true : false,
                    'attendance_time' => $latest_batch_attendance ? $latest_batch_attendance->created_at->format('Y-m-d H:i:s') : null,
                ];
            }
        }

        // Sort combined data by slot
        usort($combinedData, function ($a, $b) {
            $aStartTime = explode(' - ', $a['slot'])[0];
            $bStartTime = explode(' - ', $b['slot'])[0];
            return strtotime($aStartTime) - strtotime($bStartTime);
        });

        $schedules = $combinedData; 

        return view('Admin.Dashboard.Coach.coachindex', compact('coach', 'firstDayOfMonth', 'todayDate', 'todayDayOfWeek', 'holidays', 'schedules'));
    }

    public function getSchedule(Request $request, $coachId)
    {
        $coach = Coach::where('id', $coachId)->first();

        $date          = $request->input('date', Carbon::now()->format('Y-m-d'));
        $yesterdayDate = Carbon::now()->subDay()->format('Y-m-d');
        $dayName       = Carbon::parse($date)->format('l');
        $yesdayDayName = Carbon::parse($yesterdayDate)->format('l');

        $todayDate = Carbon::now()->format('Y-m-d');
        if ($date !== $todayDate) {
            return response()->json(['message' => 'Viewing schedules other than today is not allowed.'], 208);
        }

        $yesterdayBatches = Batch::with(['batchSchedules' => function ($query) use ($yesdayDayName, $yesterdayDate) {
            $query->where('weekday', $yesdayDayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($yesterdayDate) {
                $query
                // ->where('status', 'ACTIVE') // Count only 'ACTIVE' studentBatches
                    ->where('start_date', '<=', $yesterdayDate)
                    ->where('end_date', '>=', $yesterdayDate);
            }])
        // ->whereHas('studentBatches', function ($query) use ($yesterdayDate) {
        //     $query->where('status', 'ACTIVE') // Ensure studentBatches have 'ACTIVE' status
        //         ->where('start_date', '<=', $yesterdayDate)
        //         ->where('end_date', '>=', $yesterdayDate);
        // })
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();


        // Batches Data ::
        $batches = Batch::with(['batchSchedules' => function ($query) use ($dayName, $date) {
            $query->where('weekday', $dayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($date) {
                $query->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            }])
            ->whereHas('studentBatches', function ($query) use ($date) {
                $query->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();

        $yesdayCoachLeave = LeaveRequest::where('coach_id', $coachId)
            ->whereDate('from_date', '=', $yesterdayDate)
            ->where('status', 'APPROVED')
            ->first();

        $fromLeaveTime = null;

        if ($yesdayCoachLeave) {
            $fromLeaveTime = $yesdayCoachLeave->from_time;
        }

        $combinedData = [];

        if (in_array("UK", $coach->country)) {
            foreach ($yesterdayBatches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    if ($yesdayCoachLeave) {
                        if ($schedule->from_time >= $fromLeaveTime) {
                            continue;
                        }
                    }
                    $status = $schedule->status;
                    $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                        ->where('coach_id', $coachId)
                        ->whereDate('date', $yesterdayDate)
                        ->orderBy('id', 'desc')->first();

                    if ($latest_batch_attendance) {
                        $status = $latest_batch_attendance->status;
                    } else {
                        $today_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                            ->where('coach_id', $coachId)
                            ->whereDate('date', $date)
                            ->orderBy('id', 'desc')->first();

                        if ($today_batch_attendance) {
                            $status = $today_batch_attendance->status;
                        }
                    }

                        // $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                        //     ->where('coach_id', $coachId)
                        //     ->whereDate('date', $date)
                        //     ->orderBy('id', 'desc')
                        //     ->first();
                        // if ($latest_batch_attendance) {
                        //     $status = $latest_batch_attendance->status;
                        //     // dd($latest_batch_attendance);
                        // } else {
                        //     $status = $schedule->status;
                        // }

                    $combinedData[] = [
                        'id'              => $batch->id,
                        'name'            => $batch->name,
                        'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                        'status'          => $status,
                        'type'            => 'Yesterday Batch',
                        'coverup' => 'No',
                        'active_students' => $batch->active_students_count,
                        'start_url' => $batch->start_url,
                    ];
                }
            }
        }

        $todayCoachLeave = LeaveRequest::where('coach_id', $coachId)
            ->whereDate('from_date', '=', $date)
            ->where('status', 'APPROVED')
            ->first();
        if ($todayCoachLeave) {
            $fromLeaveTime = $todayCoachLeave->from_time;
            $toLeaveTime   = $todayCoachLeave->to_time;
        }

        $combinedData = [];
        foreach ($batches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
    
                // ✅ Check for coverup class first
                $coverup = Coverupclass::where('batchschedule_id', $schedule->id)
                    ->where('new_coach_id', $coachId)
                    ->where('date', $date)
                    ->first();
                $isCoverup = $coverup !== null;
                // ✅ Only skip session if coach is on leave AND no coverup
                if ($todayCoachLeave && !$isCoverup) {
                    if ($schedule->from_time >= $fromLeaveTime && $schedule->to_time <= $toLeaveTime) {
                        continue; // skip session
                    }
                }

                // ✅ Set attendance status
                $status = $schedule->status;
                $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                    ->where('coach_id', $coachId)
                    ->whereDate('date', $date)
                    ->orderBy('id', 'desc')->first();

                if ($latest_batch_attendance) {
                    $status = $latest_batch_attendance->status;
                }

                // ✅ Count active students
                $students = StudentBatch::where('batch_id', $batch->id)
                    ->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->pluck('student_id')
                    ->toArray();
                $studentCount = count($students);

                // ✅ If coverup exists, override status
                if ($isCoverup) {
                    $status = 'COVER UP';
                }

                $combinedData[] = [
                    'id' => $batch->id,
                    'name' => $batch->name,
                    'slot' => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                    'status' => $status,
                    'type' => $isCoverup ? 'COVERUP' : 'BATCH',
                    'coverup' => $isCoverup ? 'Yes' : 'No',
                    'active_students' => $studentCount,
                    'start_url' => $coverup->start_url ?? $batch->start_url, // Use coverup start_url if exists, else batch start_url
                ];
            }
        }


        // Demo Session Data ::
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->where('date', $date)
            ->get();

        foreach ($demoSessions as $session) {
            list($startTime, $endTime) = explode(' - ', $session->slot);
            $formattedStartTime        = Carbon::createFromFormat('H:i:s', $startTime)->format('h:i A');
            $formattedEndTime          = Carbon::createFromFormat('H:i:s', $endTime)->format('h:i A');
            $combinedData[]            = [
                'id'              => $session->id, // Include the session ID
                'name'            => $session->demolead->first_name . ' ' . $session->demolead->last_name,
                'slot'            => $formattedStartTime . ' - ' . $formattedEndTime,
                'status'          => $session->demolead->status,
                'type'            => 'Demo',
                'coverup' => 'No',
                'active_students' => 1, 
                'start_url' => $session->start_url,
            ];
        }

        //Cover up Class
        // $coverupClasses = Coverupclass::where('new_coach_id', $coachId)
        //     ->where('date', $date)
        //     ->get();
        $coverupClasses = CoverUpClass::where('new_coach_id', $coachId)
            ->whereDate('date', $date)
            ->with(['batch.batchSchedules' => function ($query) use ($dayName) {
                $query->where('weekday', $dayName)->where('status', 'ACTIVE');
            }])
            ->get();

        foreach ($coverupClasses as $coverup) {
            $batch = $coverup->batch;

            if (!$batch || $batch->status !== 'ACTIVE') continue;

            foreach ($batch->batchSchedules as $schedule) {
                $status = $schedule->status;

                $latestAttendance = CoachAttendance::where('batch_id', $batch->id)
                    ->whereDate('date', $date)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latestAttendance) {
                    $status = $latestAttendance->status;
                }

                $combinedData[] = [
                    'id'              => $batch->id,
                    'name'            => $batch->name,
                    'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                    'status'          => $status,
                    'type'            => 'COVERUP',
                    'coverup'         => 'Yes',
                    'active_students' => $batch->studentBatches()
                                                ->where('status', 'ACTIVE')
                                                ->where('start_date', '<=', $date)
                                                ->where('end_date', '>=', $date)
                                                ->count(),
                    'start_url'       => $coverup->start_url, // 🔥 important
                ];
            }
        }

        // dd($combinedData);
        // Sort combined data by slot
        usort($combinedData, function ($a, $b) {
            $aStartTime = explode(' - ', $a['slot'])[0];
            $bStartTime = explode(' - ', $b['slot'])[0];
            return strtotime($aStartTime) - strtotime($bStartTime);
        });


        return view('Admin.Dashboard.Coach.schedule', ['schedules' => $combinedData]);
    }

    public function getCoachMasteClass(Request $request)
    {
        $coach  = Coach::where('id', $request->coachId)->first();
        $upcomming_masterclasses = Masterclass::where('coach_id', $coach->id)
            ->whereDate('date', '>=', Carbon::now()->toDateString())
            ->get();
        return view('Admin.Dashboard.Coach.masterclass', compact('coach', 'upcomming_masterclasses'));
    }

    public function markPreMasterClassAttendance(Request $request)
    {
        // dd($request->all());
        $rules = [
            'coach_id' => 'required|exists:coachs,id',
            'id' => 'required|exists:masterclasses,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $current_date = Carbon::now()->toDateString();
        $current_time = Carbon::now()->toTimeString();
        $masterclass  = Masterclass::where('id', $request->id)->first();
        if (! $masterclass) {
            return response()->json(['error' => 'Masterclass not found'], 404);
        }

        $latest_masterclass_attendance = CoachAttendance::where('masterclass_id', $masterclass->id)
            ->where('coach_id', $masterclass->coach_id)
            ->whereDate('date', $current_date)
            ->orderBy('id', 'desc')->first();

        if ($latest_masterclass_attendance) {
            return response()->json(['error' => 'Attendance already marked for the masterclass', 'message' => 'Attendance already marked for the masterclass'], 422);
        }

        $coachAttendance                 = new CoachAttendance();
        $coachAttendance->coach_id       = $masterclass->coach_id;
        $coachAttendance->type           = 'Masterclass';
        $coachAttendance->masterclass_id = $masterclass->id;
        $coachAttendance->date           = $current_date;
        $coachAttendance->time           = $current_time;
        $coachAttendance->status         = 'COMPLETED';
        $coachAttendance->homework_link  = $request->homework_link;
        $coachAttendance->recording_link = $request->recording_link;
        $coachAttendance->chapter_name   = $request->chapter_name;
        $coachAttendance->save();

        return response()->json(['status' => 'success', 'message' => 'Attendance recorded successfully'], 200);
    }


    public function markMasterClassAttendance(Request $request)
    {
        // dd($request->all());
        $rules = [
            'homework_link'  => 'required',
            // 'recording_link' => 'required',
            'chapter_name'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $current_date = Carbon::now()->toDateString();
        $current_time = Carbon::now()->toTimeString();

        $masterclass = Masterclass::find($request->masterclassId);
        if (! $masterclass) {
            return response()->json(['error' => 'Masterclass not found'], 404);
        }

        $attendance = CoachAttendance::where('masterclass_id', $masterclass->id)
            ->where('coach_id', $masterclass->coach_id)
            ->whereDate('date', $current_date)
            ->first();

        if ($attendance) {
            // Update existing attendance
            $attendance->time           = $current_time; // update time if needed
            $attendance->status         = 'COMPLETED';
            $attendance->homework_link  = $request->homework_link;
            $attendance->recording_link = $request->recording_link;
            $attendance->chapter_name   = $request->chapter_name;
            $attendance->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Attendance updated successfully'
            ]);
        }

        // Create new attendance if none found
        CoachAttendance::create([
            'coach_id'       => $masterclass->coach_id,
            'type'           => 'Masterclass',
            'masterclass_id' => $masterclass->id,
            'date'           => $current_date,
            'time'           => $current_time,
            'status'         => 'COMPLETED',
            'homework_link'  => $request->homework_link,
            'recording_link' => $request->recording_link,
            'chapter_name'   => $request->chapter_name,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Attendance recorded successfully'
        ]);
    }

    public function getAttendanceData(Request $request, $coachId)
    {

        $id   = $request->input('id');
        $type = $request->input('type');

        // IMPORTANT — FIXED
        $attendanceDate = $request->input('attendance_date');
        $attendanceId   = $request->input('attendance_id');
        $attendanceTime = $request->input('attendance_time');

        if (! in_array($type, ['BATCH','Batch', 'Demo', 'COVERUP', 'Yesterday Batch'])) {
            return response()->json(['error' => 'Invalid type specified'], 400);
        }

        if ($type === 'BATCH' || $type === 'COVERUP' || $type === 'Yesterday Batch' || $type === 'Batch') {

            // USE PENDING ATTENDANCE DATE — NOT TODAY
            $date = $attendanceDate ?? now()->toDateString();
            $dayName = Carbon::parse($date)->format('l');
            $data = Batch::where('id', $id)
                ->with([
                    'batchSchedules',
                    'studentBatches' => function ($query) use ($date) {
                        $query->distinct('student_id')
                            ->whereDate('start_date', '<=', $date)
                            ->whereDate('end_date', '>=', $date);
                    },
                    'coach',
                    'parent'
                ])
                ->first();
            
            $todaysSchedule = BatchSchedule::where('batch_id', $id)
                ->where('weekday', $dayName)
                ->first();

            $isDelayed = false;
            $delayTime = 0;
            // if ($todaysSchedule) {
            //     $scheduledStart = Carbon::parse($date . ' ' . Carbon::parse($todaysSchedule->from_time)->format('H:i:s'));
            //     $now = Carbon::now();
            //     if ($now->gt($scheduledStart->copy()->addMinutes(3))) {
            //         $isDelayed = true;
            //         $delayTime = $scheduledStart->diffInMinutes($now);
            //     }
            // }

            if (! $data) {
                return response()->json(['error' => 'Data not found'], 404);
            }

            // Batch level + student batch
            $activeBatch     = $data->studentBatches()->first();
            $batchLevel      = $data->level;
            $batchStartDate  = optional($activeBatch)->start_date;
            $batchEndDate    = optional($activeBatch)->end_date;

            // NUMBER OF COMPLETED SESSIONS
            $coachAttendance = CoachAttendance::where('batch_id', $id)
                ->where('status', 'COMPLETED')
                ->count();

            $numberOfBatchSessions = $coachAttendance;

            // Student attendance list
            $studentAttendances = StudentAttendance::with(['student', 'batch'])
                ->whereHas('batch', function ($q) use ($id) {
                    $q->where('batch_id', $id);
                })
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');

            // Batch time
            $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
            $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
            $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

            // LOAD EXACT PENDING ATTENDANCE — FIXED
            if(empty($attendanceId)) {
                $todaysCoachAttendance = CoachAttendance::where('batch_id', $id)
                    ->whereDate('date', $date)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($type === 'Yesterday Batch') {
                    if ($todaysCoachAttendance == null) {
                        $yesterdayDate = Carbon::parse($date)->subDay()->toDateString();
                        $todaysCoachAttendance = CoachAttendance::where('batch_id', $id)
                        // ->where('coach_id', $coachId)
                            ->whereDate('date', $yesterdayDate)
                            ->orderBy('id', 'desc')
                            ->first();
                    }
                }
            } else {
                $todaysCoachAttendance = CoachAttendance::where('id', $attendanceId)->first();
            }   

            return view('Admin.Dashboard.Coach.batchattendance', [
                'type'                  => strtoupper($type),
                'coachId'               => $coachId,
                'data'                  => $data,
                'batchStartDate'        => $batchStartDate,
                'batchEndDate'          => $batchEndDate,
                'batchLevel'            => $batchLevel,
                'coachAttendance'       => $coachAttendance,
                'studentAttendances'    => $studentAttendances,
                'fromTime'              => $fromTime,
                'toTime'                => $toTime,
                'numberOfBatchSessions' => $numberOfBatchSessions,
                'todaysCoachAttendance' => $todaysCoachAttendance,
                'attendanceDate'        => $attendanceDate,
                'attendanceTime'        => $attendanceTime,
                'isDelayed'             => $isDelayed,
                'delayTime'             => $delayTime
            ]);
        }

        // DEMO -------------------
        if ($type === 'Demo') {

            $data = DemoSession::where('id', $id)
                ->where('coach_id', $coachId)
                ->with(['demolead', 'coach', 'level'])
                ->first();

            if (! $data) {
                return response()->json(['error' => 'Data not found'], 404);
            }

            $levels = Level::where('status', 'ACTIVE')->get();

            $studentAttendances = StudentAttendance::where('demolead_id', $data->demolead->id)->get();

            $coachAttendance = CoachAttendance::where('demolead_id', $data->demolead->id)
                ->where('coach_id', $coachId)
                ->first();

            return view('Admin.Dashboard.Coach.demoattendance', [
                'type'              => $type,
                'coachId'           => $coachId,
                'data'              => $data,
                'levels'            => $levels,
                'studentAttendances'=> $studentAttendances,
                'coachAttendance'   => $coachAttendance,
                'attendanceDate'    => $attendanceDate,
                'attendanceTime'    => $attendanceTime
            ]);
        }
    }

    // public function getAttendanceData(Request $request, $coachId)
    // {
    //     // dd($request->all());
    //     $id   = $request->input('id');
    //     $type = $request->input('type');
    //     // $date = $request->input('date');
    //     $todayDate = Carbon::today()->toDateString();
    //     if (! in_array($type, ['BATCH', 'Demo', 'COVERUP', 'Yesterday Batch'])) {
    //         return response()->json(['error' => 'Invalid type specified'], 400);
    //     }
    //     $data = null;

    //     if ($type === 'Batch' || $type === 'Coverup' || $type === 'Yesterday Batch') {
    //         $date = $request->input('date', now()->toDateString());
    //         $data = Batch::where('id', $id)
    //         // ->where('coach_id', $coachId)
    //             ->with(['batchSchedules', 'studentBatches' => function ($query) use ($date) {
    //                 $query->distinct('student_id')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
    //             }, 'coach', 'parent'])
    //             ->first();

    //         if (! $data) {
    //             return response()->json(['error' => 'Data not found'], 404);
    //         }
    //         if ($type === 'Yesterday Batch') {
    //             $activeBatch = $data->studentBatches()->first();
    //         } else {
    //             $activeBatch = $data->studentBatches()->where('status', 'ACTIVE')->first();
    //         }
    //         $batchLevel     = $data->level;
    //         $batchStartDate = optional($activeBatch)->start_date;
    //         $batchEndDate   = optional($activeBatch)->end_date;
    //         $date           = $request->input('date', now()->toDateString());

    //         ## ----------------- ## ----------------- Atendance logic ----------------- ## -----------------
    //         $coachAttendance = CoachAttendance::
    //             // where('coach_id', $coachId)->
    //             where('batch_id', $id)
    //             ->orderByDesc('created_at')
    //             ->where('status', 'COMPLETED')
    //             ->count();

    //         // Extract the number_of_batch_sessions from the latest entry
    //         $numberOfBatchSessions = $coachAttendance;

    //         $studentAttendances = StudentAttendance::with(['student', 'batch'])
    //             ->whereHas('batch', function ($query) use ($id) {
    //                 $query->where('batch_id', $id);
    //             })
    //             ->whereDate('date', $date)
    //             ->get()
    //             ->keyBy('student_id');

    //         $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
    //         $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
    //         $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

    //         $todaysCoachAttendance = CoachAttendance::where('batch_id', $id)
    //             ->whereDate('date', $date)
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         if ($type === 'Yesterday Batch') {
    //             if ($todaysCoachAttendance == null) {
    //                 $yesterdayDate = Carbon::parse($date)->subDay()->toDateString();
    //                 $todaysCoachAttendance = CoachAttendance::where('batch_id', $id)
    //                 // ->where('coach_id', $coachId)
    //                     ->whereDate('date', $yesterdayDate)
    //                     ->orderBy('id', 'desc')
    //                     ->first();
    //             }
    //         }
    //         // dd($batchLevel);

    //         return view('Admin.Dashboard.Coach.batchattendance', [
    //             'type'                  => strtoupper($type),
    //             'coachId'               => $coachId,
    //             'data'                  => $data,
    //             'batchStartDate'        => $batchStartDate,
    //             'batchEndDate'          => $batchEndDate,
    //             'batchLevel'            => $batchLevel,
    //             'coachAttendance'       => $coachAttendance,
    //             'studentAttendances'    => $studentAttendances,
    //             'fromTime'              => $fromTime,
    //             'toTime'                => $toTime,
    //             'numberOfBatchSessions' => $numberOfBatchSessions,
    //             'todaysCoachAttendance' => $todaysCoachAttendance,
    //         ]);
    //     } elseif ($type === 'Demo') {
    //         $data = DemoSession::where('id', $id)
    //             ->where('coach_id', $coachId)
    //             ->with(['demolead', 'coach', 'level'])
    //             ->first();
    //         $levels = Level::where('status', 'ACTIVE')->get();
    //         if (! $data) {
    //             return response()->json(['error' => 'Data not found'], 404);
    //         }
    //         $studentAttendances = StudentAttendance::where('demolead_id', $data->demolead->id)->get();
    //         $coachAttendance    = CoachAttendance::where('coach_id', $coachId)->where('demolead_id', $data->demolead->id)->get();

    //         return view('Admin.Dashboard.Coach.demoattendance', [
    //             'type'               => $type,
    //             'coachId'            => $coachId,
    //             'data'               => $data,
    //             'levels'             => $levels,
    //             'studentAttendances' => $studentAttendances,
    //             'coachAttendance'    => $coachAttendance,
    //         ]);
    //     }
    // }




    // public function getAttendanceData(Request $request, $coachId)
    // {
    //     $id             = $request->input('id');
    //     $type           = $request->input('type');
    //     $attendanceId   = $request->input('attendance_id');
    //     $attendanceDate = $request->input('attendance_date');

    //     if (!in_array($type, ['Batch', 'Demo', 'Coverup', 'Yesterday Batch'])) {
    //         return response()->json(['error' => 'Invalid type specified'], 400);
    //     }

    //     if ($type === 'Batch' || $type === 'Coverup' || $type === 'Yesterday Batch') {

    //         $date = $attendanceDate; // use pending attendance date

    //         $data = Batch::where('id', $id)
    //             ->with([
    //                 'batchSchedules',
    //                 'studentBatches' => function ($query) use ($date) {
    //                     $query->distinct('student_id')
    //                         ->whereDate('start_date', '<=', $date)
    //                         ->whereDate('end_date', '>=', $date);
    //                 },
    //                 'coach',
    //                 'parent'
    //             ])
    //             ->first();

    //         if (!$data) {
    //             return response()->json(['error' => 'Data not found'], 404);
    //         }

    //         $activeBatch     = $data->studentBatches()->first();
    //         $batchLevel      = $data->level;
    //         $batchStartDate  = optional($activeBatch)->start_date;
    //         $batchEndDate    = optional($activeBatch)->end_date;

    //         // Load EXACT CoachAttendance record (NOT latest)
    //         $todaysCoachAttendance = CoachAttendance::where('id', $attendanceId)->first();

    //         $studentAttendances = StudentAttendance::with(['student', 'batch'])
    //             ->whereHas('batch', function ($query) use ($id) {
    //                 $query->where('batch_id', $id);
    //             })
    //             ->whereDate('date', $date)
    //             ->get()
    //             ->keyBy('student_id');

    //         $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
    //         $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
    //         $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

    //         $coachAttendance = CoachAttendance::where('batch_id', $id)
    //             ->where('status', 'COMPLETED')
    //             ->count();

    //         return view('Admin.Dashboard.Coach.batchattendance', [
    //             'type'                  => strtoupper($type),
    //             'coachId'               => $coachId,
    //             'data'                  => $data,
    //             'batchStartDate'        => $batchStartDate,
    //             'batchEndDate'          => $batchEndDate,
    //             'batchLevel'            => $batchLevel,
    //             'coachAttendance'       => $coachAttendance,
    //             'studentAttendances'    => $studentAttendances,
    //             'fromTime'              => $fromTime,
    //             'toTime'                => $toTime,
    //             'numberOfBatchSessions' => $coachAttendance,
    //             'todaysCoachAttendance' => $todaysCoachAttendance,
    //             'attendanceDate'        => $attendanceDate,
    //             'attendanceTime'        => $todaysCoachAttendance ? $todaysCoachAttendance->time : null,
    //         ]);
    //     }

    //     // DEMO handling remains same...
    // }


    public function batchAttendance(Request $request, $coachId)
    {
        $rules = [
            'coach_id'        => 'required|integer',
            'type'            => 'required|string',
            'batch_id'        => 'required|integer',
            'date'            => 'required|date',
            'time'            => 'required',
            'student_ids'     => 'required|array',
            'student_ids.*'   => 'required|integer|exists:students,id', 
            'status'          => 'required|in:COMPLETED,CANCELLED',
            'homework_link'   => 'required_if:status,COMPLETED|nullable|url',
            'chapter_name'    => 'required_if:status,COMPLETED|nullable|string',
            'studentStatus'   => 'required|array',
            'studentStatus.*' => 'required|in:PRESENT,ABSENT',
            'studentRemark'   => 'sometimes|array',
            'studentRemark.*' => 'nullable|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $batchId = $request->batch_id;
        $batch = Batch::with('level')->find($batchId);
        if (! $batch) {
            return response()->json(['error' => 'Batch not found'], 404);
        }

        $attendanceDate = Carbon::parse($request->input('date'))->toDateString();
        $coachAttendance = CoachAttendance::where('batch_id', $request->batch_id)->whereDate('date', $attendanceDate)->first();

        $date = $request->input('date');
        $todaysDay = Carbon::parse($date)->format('l');
        $schedule = BatchSchedule::where('batch_id', $batch->id)
            ->where('weekday', $todaysDay)
            ->first();

        if (! $schedule) {
            return response()->json(['error' => 'No schedule found for this day'], 422);
        }

        $scheduledStart = $this->scheduledBatchDateTime($attendanceDate, $schedule->from_time);

        if (now()->gt($scheduledStart->copy()->addMinutes(7))) {
            return response()->json(['error' => 'Batch session can only be marked 7 minutes after the scheduled start time'], 422);
        }

        if ($coachAttendance) {
            $coachAttendance->status = $request->status;
            $coachAttendance->homework_link = $request->homework_link ?? '';
            $coachAttendance->chapter_name = $request->chapter_name ?? '';
            $coachAttendance->time = $request->input('time');
            $coachAttendance->save();
        }else{
            // dd('here');
            $latestCoachAttendance = CoachAttendance::where('batch_id', $batchId)
                ->orderBy('id', 'desc')
                ->first();

            $coachAttendance = new CoachAttendance();
            $coachAttendance->coach_id = $request->coach_id;
            $coachAttendance->type = strtoupper($request->type);
            $coachAttendance->batch_id = $request->batch_id;
            $coachAttendance->date = $attendanceDate;
            $coachAttendance->time = $request->input('time');
            $coachAttendance->status = $request->status;
            $coachAttendance->homework_link = $request->homework_link ?? '';
            $coachAttendance->chapter_name = $request->chapter_name ?? '';

            if ($request->status != 'CANCELLED') { 
                $coachAttendance->number_of_batch_sessions = $latestCoachAttendance
                    ? $latestCoachAttendance->number_of_batch_sessions + 1
                    : 1;
            }
            $coachAttendance->save();

            $actualAt = $this->actualAttendanceDateTime($attendanceDate, $request->input('time'));

            if ($actualAt->gt($scheduledStart->copy()->addMinutes(3))) {
                DelayedBatch::updateOrCreate(
                    [
                        'batch_id' => $batchId,
                        'date' => $attendanceDate,
                    ],
                    [
                        'coach_id' => $request->coach_id,
                        'coach_attendance_id' => $coachAttendance->id,
                        'time' => $actualAt->format('H:i:s'),
                        'batch_name' => $batch->name,
                        'country' => $batch->country,
                        'batch_status' => $batch->status,
                        'level_name' => optional($batch->level)->name,
                        'timeline' => sprintf(
                            'Scheduled start: %s | Marked at: %s',
                            $scheduledStart->format('d-M-Y h:i:s A'),
                            $actualAt->format('d-M-Y h:i:s A')
                        ),
                        'canceled_date' => $request->status === 'CANCELLED' ? $attendanceDate : null,
                        'canceled_time' => $request->status === 'CANCELLED' ? $actualAt->format('H:i:s') : null,
                    ]
                );
            }
        }

        if ($request->status == 'CANCELLED') {
            $batch = Batch::find($batchId);
            $batchSchedules = BatchSchedule::where('batch_id', $batchId)->get();
            $scheduledDays = $batchSchedules->pluck('weekday')->map(fn($day) => strtolower($day))->toArray();

            $batchEndDate = Carbon::parse($batch->end_date);
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
        }
        
        foreach ($request->student_ids as $studentId) {
            $studentAttendance = StudentAttendance::where('batch_id', $request->batch_id)
                ->where('student_id', $studentId)
                ->whereDate('date', $attendanceDate)
                ->first(); 
            
            if ($studentAttendance) {
                $studentAttendance->status = $request->studentStatus[$studentId] ?? 'ABSENT';
                $studentAttendance->remark = $request->studentRemark[$studentId] ?? '';
                $studentAttendance->time = $request->input('time');
                $studentAttendance->type = strtoupper($request->input('type'));
                $studentAttendance->coach_id = $coachId;
                $studentAttendance->homework_link = $request->homework_link ?? '';
                $studentAttendance->chapter_name = $request->chapter_name ?? '';
                $studentAttendance->number_of_batch_sessions = $coachAttendance->number_of_batch_sessions;
                if ($request->status == 'CANCELLED') {
                    $studentAttendance->status = 'CANCELLED';
                }
                $studentAttendance->save();
            } else {
                $studentAttendance = new StudentAttendance();
                $studentAttendance->student_id = $studentId;
                $studentAttendance->batch_id = $request->batch_id;
                $studentBatch = StudentBatch::where('batch_id', $request->batch_id)
                    ->where('student_id', $studentId)
                    ->where('status', 'ACTIVE')
                    ->first();
                $studentAttendance->level_id = $studentBatch ? $studentBatch->level_id : null; 
                
                $studentAttendance->date = $attendanceDate;
                $studentAttendance->time = $request->input('time');
                $studentAttendance->status = $request->studentStatus[$studentId] ?? 'ABSENT';
                $studentAttendance->remark = $request->studentRemark[$studentId] ?? '';
                $studentAttendance->type = strtoupper($request->input('type'));
                $studentAttendance->coach_id = $coachId;
                $studentAttendance->homework_link = $request->homework_link ?? '';
                $studentAttendance->chapter_name = $request->chapter_name ?? '';
                $studentAttendance->number_of_batch_sessions = $coachAttendance->number_of_batch_sessions;
                if ($request->status == 'CANCELLED') {
                    $studentAttendance->status = 'CANCELLED';
                }
                $studentAttendance->save();
            }

            
            if ($request->status == 'CANCELLED') {
                $studentBatch = StudentBatch::where('student_id', $studentId)
                    ->where('batch_id', $batchId)
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($studentBatch) {
                    $studentBatch->end_date = $batch->end_date;
                    $studentBatch->save();
                }

                // Update StudentFee end_date
                $studentLatestFee = StudentFee::where('student_id', $studentId)->orderBy('id', 'desc')->first();
                if ($studentLatestFee) {
                    $feeEndDate = Carbon::parse($studentLatestFee->end_date);
                    $nextFeeDate = null;
                    foreach ($scheduledDays as $day) {
                        $dayDiff = (Carbon::parse($day)->dayOfWeek - $feeEndDate->dayOfWeek + 7) % 7;
                        if ($dayDiff > 0) {
                            $nextFeeDate = $feeEndDate->copy()->addDays($dayDiff);
                            break;
                        }
                    }
                    if (! $nextFeeDate) {
                        $nextFeeDate = $feeEndDate->copy()->addDays(
                            (Carbon::parse($scheduledDays[0])->dayOfWeek - $feeEndDate->dayOfWeek + 7) % 7
                        );
                    }
                    $studentLatestFee->end_date = $nextFeeDate->toDateString();
                    $studentLatestFee->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Attendance recorded successfully', 'start_url' => $batch->start_url], 200);
    }

    public function preBatchAttendance(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|integer|exists:batchs,id',
        ]);

        $batch = Batch::find($request->batch_id);
        
        $date = $request->input('date', now()->toDateString());
        $batch = Batch::where('id', $request->batch_id)
            ->with(['batchSchedules', 'studentBatches' => function ($query) use ($date) {
                $query->distinct('student_id')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            }, 'coach', 'parent'])
            ->first();

        $todaysDay = Carbon::parse($date)->format('l');
        $schedule = BatchSchedule::where('batch_id', $batch->id)
            ->where('weekday', $todaysDay)
            ->first();


        $fromTime = Carbon::createFromFormat('H:i:s', $schedule->from_time)->setDate(
            now()->year, now()->month, now()->day
        );  

        if (now()->gt($fromTime->addMinutes(10))) {
            return response()->json(['error' => 'Coach change is not allowed after 10 minutes from batch start.'], 403);
        }
    
        $user = auth()->user();
        $coach = $user->coach;
        $attendanceDate = Carbon::now()->toDateString();
        $attendanceTime = Carbon::now()->format('H:i:s');

        // Check if attendance already exists for this batch and date
        $existingCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->whereDate('date', $attendanceDate)
            ->first();

        // If attendance exists, just return success without creating new entry
        if ($existingCoachAttendance) {
            return response()->json([
                'status' => 'success',
                'message' => 'Attendance already marked for this batch today, no duplicate entry created.'
            ]);
        }

        $latestCoachAttendance = CoachAttendance::where('batch_id', $batch->id)
            ->orderBy('id', 'desc')
            ->first();

        // Create coach attendance record with status COMPLETED
        $coachAttendance = new CoachAttendance();
        $coachAttendance->coach_id = $coach->id;
        $coachAttendance->type = strtoupper($request->type);
        $coachAttendance->batch_id = $batch->id;
        $coachAttendance->date = $attendanceDate;
        $coachAttendance->time = $attendanceTime;
        $coachAttendance->status = 'COMPLETED';
        $coachAttendance->number_of_batch_sessions = $latestCoachAttendance ? $latestCoachAttendance->number_of_batch_sessions + 1 : 1;
        $coachAttendance->save();

        // Mark all students as ABSENT with remark
        foreach ($batch->studentBatches as $studentBatch) {
            $studentAttendance = new StudentAttendance();
            $studentAttendance->type = strtoupper($request->type);  
            $studentAttendance->student_id = $studentBatch->student_id;
            $studentAttendance->batch_id = $batch->id;
            $studentAttendance->level_id = $studentBatch->level_id;
            $studentAttendance->date = $attendanceDate;
            $studentAttendance->time = $attendanceTime;
            $studentAttendance->status = 'ABSENT';
            $studentAttendance->remark = '';
            $studentAttendance->coach_id = $coach->id;
            $studentAttendance->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Class attendance marked successfully.',
        ]);
    }
    
    public function handleMeetingEnded(Request $request)
    {
        $payload = $request->all();

        $meetingId = $payload['payload']['object']['id'] ?? null;

        if (! $meetingId) {
            return response()->json(['error' => 'No meeting ID found'], 400);
        }

        $batch = Batch::where('zoom_meeting_id', $meetingId)->first();

        if (! $batch) {
            return response()->json(['error' => 'No batch found for this meeting'], 404);
        }

        $coach = $batch->coach;

        $studentIds = StudentBatch::where('batch_id', $batch->id)
            ->where('status', 'ACTIVE')
            ->pluck('student_id')
            ->toArray();

        $studentStatus = [];
        foreach ($studentIds as $id) {
            $studentStatus[$id] = 'PRESENT'; // optional: use Zoom's participant API to improve this
        }

        $fakeRequest = new Request([
            'coach_id'        => $coach->id,
            'type'            => 'Zoom',
            'batch_id'        => $batch->id,
            'level_id'        => $batch->level_id,
            'date'            => now()->toDateString(),
            'time'            => now()->format('h:i A'),
            'status'          => 'COMPLETED',
            'student_ids'     => $studentIds,
            'studentStatus'   => $studentStatus,
            'chapter_name'    => 'Automated from Zoom',
        ]);

        // You can put your batchAttendance logic right here OR
        // Call a trait/method you extract for cleanliness
        $this->storeBatchAttendance($fakeRequest);

        return response()->json(['status' => 'success'], 200);
    }

    public function demoAttendance(Request $request, $coachId)
    {
        $rules = [
            'coach_id'    => 'required|integer',
            'type'        => 'required|string',
            'demolead_id' => 'required|integer',
            'level_id'    => 'nullable|integer',
            'batch_id'    => 'nullable|integer',
            'date'        => 'required|date',
            'time'        => 'required',
            'status'      => 'required|in:COMPLETED,CANCELLED,Student Absent',
            'remark'      => 'required|string',
        ];
        $messages = [
            'level_id.required' => 'Please select level.',
            'remark.required' => 'Please select chapter number.',
        ];
        if ($request->input('status') === 'COMPLETED') {
            $rules['level_id'] = 'required|integer';
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Slot and time validation
        $date                      = $request->input('date');
        $slot                      = $request->input('slot');
        list($slotStart, $slotEnd) = explode(' - ', $slot);
        $slotStartTime             = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $slotStart, 'Asia/Kolkata');
        $endTime                   = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $slotEnd, 'Asia/Kolkata');
        $submissionStartTime       = $endTime->copy()->subMinutes(10);
        $submissionDeadline        = $endTime->copy()->addMinutes(15);
        $currentSubmissionTime     = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->input('time'), 'Asia/Kolkata');
        // if ($request->input('status') === 'COMPLETED') {
        //     if ($currentSubmissionTime->lt($submissionStartTime) || $currentSubmissionTime->gt($submissionDeadline)) {
        //         return response()->json(['error' => 'The submission time must be within 10 minutes before the slot ends and no later than 15 minutes after the slot has ended.'], 422);
        //     }
        // }

        // Save to CoachAttendances table
        $coachAttendanceAttributes = [
            'coach_id'    => $request->input('coach_id'),
            'type'        => $request->input('type'),
            'demolead_id' => $request->input('demolead_id'),
            'batch_id'    => $request->input('batch_id'),
            'date'        => $request->input('date'),
            'time'        => $request->input('time'),
            'status'      => $request->input('status'),
        ];
        CoachAttendance::updateOrCreate(
            ['demolead_id' => $request->input('demolead_id')],
            $coachAttendanceAttributes
        );
        // Update DemoSession level_id and coach_attendance_status if demolead_id, coach_id exist and status is ACTIVE
        $demoSession = DemoSession::where('demolead_id', $request->input('demolead_id'))
            ->where('coach_id', $request->input('coach_id'))
            ->where('status', 'ACTIVE')
            ->first();
        if ($demoSession) {
            $demoSession->level_id                = $request->input('level_id');
            $demoSession->coach_attendance_status = $request->input('status');
            $demoSession->save();
        }
        // If the status is COMPLETED, update the status in DemoLead table to "DEMO DONE"
        if ($request->input('status') === 'COMPLETED') {
            DemoLead::where('id', $request->input('demolead_id'))
                ->update(['status' => 'DEMO DONE']);
        } elseif ($request->input('status') === 'CANCELLED') {
            DemoLead::where('id', $request->input('demolead_id'))
                ->update(['status' => 'CANCELLED']);
        }
        // Save to StudentAttendances table
        $studentAttendanceAttributes = [
            'type'        => $request->input('type'),
            'coach_id'    => $request->input('coach_id'),
            'demolead_id' => $request->input('demolead_id'),
            'level_id'    => $request->input('level_id'),
            'status'      => $request->input('status'),
            'date'        => $request->input('date'),
            'time'        => $request->input('time'),
            'remark'      => $request->input('remark'),
        ];
        StudentAttendance::updateOrCreate(
            ['demolead_id' => $request->input('demolead_id')], // Conditions to find existing record
            $studentAttendanceAttributes                       // Attributes to update or create
        );

        $demolead   = DemoLead::where('id', $request->input('demolead_id'))->first();

        $studentAttendance = StudentAttendance::where('demolead_id', $demolead->id)->first();

        // Mail::to($demolead->user->email)->send(new DemoCompleteMail($demolead, $studentAttendance));

        return response()->json(['status' => 'success', 'message' => 'Attendance recorded successfully'], 200);
    }

    public function getCalendarData(Request $request, $coachId)
    {
        \Log::debug('Coach ID:', ['coachId' => $coachId]);
        $today        = Carbon::now();
        $todayDayName = $today->format('l');
        $todayDate    = $today->format('Y-m-d');
        $startDate    = Carbon::now()->startOfYear();
        $endDate      = Carbon::now()->endOfYear();

        $batches = Batch::with(['batchSchedules' => function ($query) {
            $query->where('status', 'ACTIVE');
        }, 'studentBatches' => function ($query) {
            $query->where('status', 'ACTIVE');
        }])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();
        $calendarData = [];
        foreach ($batches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
                $startOfWeek  = Carbon::now()->startOfWeek();
                $endDate      = Carbon::now()->addYear();
                $studentBatch = $batch->studentBatches->first();
                if ($studentBatch) {
                    $startOfWeek = Carbon::parse($studentBatch->start_date)->startOfWeek();
                    $endDate     = Carbon::parse($studentBatch->end_date);
                }
                $date = $startOfWeek->dayOfWeek === Carbon::parse($schedule->weekday)->dayOfWeek
                ? $startOfWeek
                : $startOfWeek->next($schedule->weekday);
                while ($date <= $endDate) {
                    $fromTimeCarbon    = Carbon::createFromFormat('H:i:s', $schedule->from_time);
                    $toTimeCarbon      = Carbon::createFromFormat('H:i:s', $schedule->to_time);
                    $formattedFromTime = $fromTimeCarbon->format('g') . ($fromTimeCarbon->format('i') === '00' ? ' ' . $fromTimeCarbon->format('A') : ':' . $fromTimeCarbon->format('i A'));
                    $formattedToTime   = $toTimeCarbon->format('g') . ($toTimeCarbon->format('i') === '00' ? ' ' . $toTimeCarbon->format('A') : ':' . $toTimeCarbon->format('i A'));
                    $calendarData[]    = [
                        'title'     => $formattedFromTime . ' - ' . $formattedToTime,
                        'start'     => $date->format('Y-m-d'),
                        'color'     => 'red',
                        'textColor' => 'white',
                    ];
                    $date->addWeek();
                }
            }
        }

        // Leave Requests Data ::
        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->whereBetween('from_date', [$startDate, $endDate])
            ->where('status', 'APPROVED') // Add this line to filter by status
            ->get();

        foreach ($leaveRequests as $leaveRequest) {
            $calendarData[] = [
                'title'     => '' . Carbon::parse($leaveRequest->from_time)->format('g:i A') . ' - ' . Carbon::parse($leaveRequest->to_time)->format('g:i A') . '',
                'start'     => $leaveRequest->from_date,
                'end'       => $leaveRequest->to_date,
                'color'     => 'yellow',
                'textColor' => 'black',
            ];
        }

        // Demo Session Data ::
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        foreach ($demoSessions as $session) {
            list($startTime, $endTime) = explode(' - ', $session->slot);
            $startTimeCarbon           = Carbon::createFromFormat('H:i:s', $startTime);
            $endTimeCarbon             = Carbon::createFromFormat('H:i:s', $endTime);

            $formattedStartTime = $startTimeCarbon->format('g') . ($startTimeCarbon->format('i') === '00' ? ' ' . $startTimeCarbon->format('A') : ':' . $startTimeCarbon->format('i A'));
            $formattedEndTime   = $endTimeCarbon->format('g') . ($endTimeCarbon->format('i') === '00' ? ' ' . $endTimeCarbon->format('A') : ':' . $endTimeCarbon->format('i A'));

            $calendarData[] = [
                'title'     => $formattedStartTime . ' - ' . $formattedEndTime,
                'start'     => $session->date,
                'color'     => 'blue',
                'textColor' => 'white',
            ];
        }

        // Sort calendar data by start date
        usort($calendarData, function ($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        return view('Admin.Dashboard.Coach.calendar', compact('calendarData'));
    }

    // SuperAdmin Dashboard Section ::
    // ----------------------------------------------------------------------------- ::

    private function indexSuperAdmin(Request $request): View
    {
                // $this->updateBatchZoomMeeting();

        $systemRoles = getSystemRoles();
        $users       = User::whereHas("roles", function ($q) use ($systemRoles) {
            $q->whereIn("name", $systemRoles)->where('name', '!=', 'SuperAdmin');
        })->count();

        // Fetch all data from Coach and Employee tables
        $coaches   = Coach::with('user')->get();
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'ACTIVE');
        })->with(['user.roles'])->get();
        $systemRoles = getSystemRoles();
        $roles       = Role::whereNotIn('name', $systemRoles)->get();

        // Active Counts ::
        $activeEmployees = Employee::whereHas('user', function ($q) {
            $q->where('status', 'ACTIVE');
        })->count();
        // $activeCoaches = Coach::whereHas('user', function ($q) {
        //     $q->where('status', 'ACTIVE');
        // })->count();

        $activeCoaches = Coach::where('status', 'ACTIVE')->count();
        $activeStudents = Student::where('status', 'ACTIVE')->count();

        $levels   = Level::where('status', 'ACTIVE')->get();
        $coaches  = Coach::where('status', 'ACTIVE')->get();
        $students = Student::all();

        return view('Admin.Dashboard.SuperAdmin.index', compact('users', 'coaches', 'employees', 'roles', 'activeEmployees', 'activeCoaches', 'activeStudents', 'levels', 'students'));
    }

    public function studentData(Request $request)
    {
        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        $isCoach = in_array("Coach", $role);

        // Join with StudentFee table and order by the end_date of the most recently created StudentFee
        $query = Student::leftJoin('student_fees', function ($join) {
            $join->on('students.id', '=', 'student_fees.student_id')
                ->whereRaw('student_fees.id = (select max(id) from student_fees as sf where sf.student_id = students.id)');
        })
            ->where('students.id', '!=', 0)
            ->orderByDesc('student_fees.end_date')
            ->select('students.*');

        if (! $user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->first();
            if ($countries) {
                $countriesArray = json_decode($countries, true);
                if (is_array($countriesArray) && ! empty($countriesArray)) {
                    $query->whereIn('students.country', $countriesArray);
                }
            }
        }

        // Apply filters based on request parameters
        if ($request->status) {
            $query->where('students.status', $request->status); // Specify the table name to avoid ambiguity
        }
        if ($request->country) {
            $query->where('students.country', $request->country);
        }
        if ($request->batch) {
            $studentIds = StudentBatch::where('batch_id', $request->batch)->where('status', 'ACTIVE')->pluck('student_id');
            $query->whereIn('students.id', $studentIds);
        }
        if ($request->coach) {
            $studentIds = StudentBatch::where('coach_id', $request->coach)->where('status', 'ACTIVE')->pluck('student_id');
            $query->whereIn('students.id', $studentIds);
        }

        return DataTables::eloquent($query)
            ->editColumn('age', function ($student) {
                return $student->age;
            })
            ->editColumn('mobile', function ($student) {
                return $student->mobile;
            })
            ->editColumn('email', function ($student) {
                return $student->email;
            })
            ->editColumn('address', function ($student) {
                return $student->address;
            })
            ->editColumn('student_id', function ($student) {
                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();
                if ($student->status === 'ACTIVE') {
                }
                // if (isset($studentFee)) {
                $message = $student->generateNewStudentMessage();

                $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                return '<div class="d-flex justify-content-between">' . $student->student_id
                    . '<div class="d-flex justify-content-end">' . $whatsappLink . '</div></div>';
                // }
                // return $student->student_id;
            })
            ->editColumn('batch', function ($student) use ($isCoach) {
                $attendedBatches = $student->studentBatches()->with('batch')->get();
                if ($isCoach) {
                    $attendedBatches = $attendedBatches->filter(function ($studentBatch) {
                        return $studentBatch->status === 'ACTIVE';
                    });
                }
                $sortedBatches = $attendedBatches->sortByDesc(function ($studentBatch) {
                    return $studentBatch->status === 'ACTIVE';
                });
                $attendedBatchNames = $sortedBatches->map(function ($studentBatch) {
                    $batchName   = $studentBatch->batch->name;
                    $statusBadge = $studentBatch->status === 'ACTIVE' ? ' (Present)' : ' (Previous Batch)';
                    return $batchName . $statusBadge;
                })->implode(', ');
                return $attendedBatchNames;
            })
            ->addColumn('student_fees', function ($student) {
                return '<a href="/admin/students/' . $student->id . '/student_fees" class="badge bg-success fs-1"><i class="ti ti-box-multiple"></i> &nbsp; Student Fees  </a>';
            })
            ->addColumn('first_name', function ($student) use ($isCoach) {
                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();
                $fullName   = $student->first_name . ' ' . $student->last_name;

                if ($studentFee && ! $isCoach) {
                    $message      = $studentFee->generateFeeDueMessage();
                    $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                    $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                    return '<div class="d-flex justify-content-between">' . $fullName . '</div>';
                }

                return $fullName;
            })
            ->editColumn('status', function ($student) use ($isCoach) {
                switch ($student->status) {
                    case 'FEESDUE':
                        $badgeColor = 'primary';
                        break;
                    case 'INACTIVE':
                        $badgeColor = 'warning';
                        break;
                    case 'STANDBY':
                        $badgeColor = 'danger';
                        break;
                    case 'ACTIVE':
                        $badgeColor = 'success';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }

                // Fetch the latest StudentFee record for the student
                $studentFee       = StudentFee::where('student_id', $student->id)->latest('end_date')->first();
                $endDateFormatted = $studentFee ? \Carbon\Carbon::parse($studentFee->end_date)->format('l, d-M-Y') : '';
                $totalDueAmount   = $student->monthly_fees;
                $currency         = $student->currency;

                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();

                // $message = "Dear " . $student->first_name . " " . $student->last_name . ", This is to inform you that the Chess Class fee has been due with Archer Chess Academy. Your previous module has ended on " . $endDateFormatted . ". You are requested to pay the fees before the next class to continue enjoying your chess class. The total due amount is " . $totalDueAmount . "  " . $currency . ". Kindly check out Name Archer Chess Academy on the payment gateway before making payment.";
                // $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                // $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                if ($studentFee) {
                    $message      = $studentFee->generateFeeDueMessage();
                    $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                    $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                } else {
                    $whatsappLink = '';
                }

                // Conditionally include the WhatsApp link based on the user's role
                $whatsappBadge = ! $isCoach ? ' &nbsp; ' . $whatsappLink : '';

                // Badge for the StudentFee end_date, only if status is FEESDUE
                $endDateBadge = '';
                if ($student->status === 'FEESDUE') {
                    $endDateBadge = '<span class="badge bg-primary fs-1">' . $endDateFormatted . '</span>';
                }

                return '<div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 student-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $student->id . '" data-id="' . $student->id . '">
                                    <i class="ti ti-analyze"></i> &nbsp; ' . $student->status . '
                                </button>
                                ' . $endDateBadge . '
                            </div>
                            <div class="d-flex justify-content-end">' . $whatsappBadge . '</div>
                        </div>';
            })
            ->addColumn('action', function ($student) {
                // $edit  = '<a href="'.route('admin.students.edit',['student' => $student->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"> Edit</i></a>';

                $show = '<a href="' . route('admin.students.show', ['student' => $student->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="students" data-title="Student Details" data-route-key="' . $student->route_key . '"><i class="fa fa-eye"></i> &nbsp; Show </a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-dark fs-1 delete-btn"  data-student-id="' . $student->id . '"><i class="fa fa-trash  fs-1"></i> &nbsp; Delete </a>';
                }

                return $show;
            })
            ->addIndexColumn()
            ->rawColumns(['first_name', 'age', 'mobile', 'email', 'address', 'student_id', 'status', 'action', 'student_fees', 'batch'])
            ->setRowId('id')
            ->make(true);
    }
 
    public function missedSessionsData(Request $request)
    {
        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        $isCoach = in_array("Coach", $role);


        

        $consecutive   = 2;
        // $startOfMonth  = now()->startOfMonth()->toDateString();
        // $endOfMonth    = now()->endOfMonth()->toDateString();

        // 1. Get all active coaches
        $activeCoachIds = Coach::where('status', 'ACTIVE')->pluck('id');
        // 2. Get all active batches under those coaches
        $activeBatchIds = Batch::whereIn('coach_id', $activeCoachIds)
            ->where('status', 'ACTIVE')
            ->pluck('id');

        // 3. Get all active students assigned to those active batches
        $activeStudentIds = StudentBatch::whereIn('batch_id', $activeBatchIds)
            ->where('status', 'ACTIVE')
            ->pluck('student_id')
            ->unique();

        $activeStudents = Student::whereIn('id', $activeStudentIds)
            ->where('status', 'ACTIVE')
            ->get();

        $missedStudentIds = [];

        // 4. Loop through students → batches → attendance
        foreach ($activeStudents as $student) {
            $studentBatchIds = StudentBatch::where('student_id', $student->id)
                ->whereIn('batch_id', $activeBatchIds) // only active batches with active coaches
                ->where('status', 'ACTIVE')
                ->pluck('batch_id');

            foreach ($studentBatchIds as $batchId) {
                $attendances = StudentAttendance::where('student_id', $student->id)
                    ->where('batch_id', $batchId)
                    // ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('date', 'desc')
                    ->where('status', '!=', 'CANCELLED')
                    ->pluck('status', 'date')->take(2)->toArray();

                // dd($attendances, $student->id);
                $absentCount = 0;
                foreach ($attendances as $status) {
                    if ($status === 'ABSENT') {
                        $absentCount++;
                    } 

                    if ($status === 'PRESENT') {
                        $absentCount --;
                    }

                    if ($absentCount >= 2) {
                        $missedStudentIds[] = $student->id;
                    }
                }
            }
        }

        // 5. Prepare datatable query
        $query = Student::whereIn('students.id', $missedStudentIds) // 👈 qualified
                ->with('latestBatch.batch')
                ->leftJoin('student_batches as sb', 'students.id', '=', 'sb.student_id')
                ->select('students.*')
                ->whereIn('sb.id', function ($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('student_batches')
                        ->where('status', 'ACTIVE')
                        ->groupBy('student_id');
                })
                ->orderByDesc('sb.created_at');


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
                $query->whereIn('country', $mergedCountries);
            }
        }



        return DataTables::eloquent($query)
            ->editColumn('age', fn($student) => $student->age)
            ->editColumn('mobile', fn($student) => $student->mobile)
            ->editColumn('country', fn($student) => $student->country)
            ->editColumn('email', fn($student) => $student->email)
            ->editColumn('address', fn($student) => $student->address)
            ->editColumn('student_id', function ($student) {
                $message = $student->generateNewStudentMessage();
                $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><i class="fab fa-whatsapp my-float"></i></a>';

                return '<div class="d-flex justify-content-between">' . $student->student_id
                    . '<div class="d-flex justify-content-end">' . $whatsappLink . '</div></div>';
            })
            ->editColumn('batch', function ($student) use ($isCoach) {
                $attendedBatches = $student->studentBatches()->with('batch')->get();
                if ($isCoach) {
                    $attendedBatches = $attendedBatches->filter(fn($b) => $b->status === 'ACTIVE');
                }
                return $attendedBatches->pluck('batch.name')->implode(', ');
            })
            ->editColumn('status', function ($student) use ($isCoach) {
                 switch ($student->status) {
                    case 'FEESDUE':
                        $badgeColor = 'primary';
                        break;
                    case 'INACTIVE':
                        $badgeColor = 'warning';
                        break;
                    case 'STANDBY':
                        $badgeColor = 'danger';
                        break;
                    case 'ACTIVE':
                        $badgeColor = 'success';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }

                // Fetch the latest StudentFee record for the student
                $studentFee       = StudentFee::where('student_id', $student->id)->latest('end_date')->first();
                $endDateFormatted = $studentFee ? \Carbon\Carbon::parse($studentFee->end_date)->format('l, d-M-Y') : '';
                $totalDueAmount   = $student->monthly_fees;
                $currency         = $student->currency;

                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();

                // $message = "Dear " . $student->first_name . " " . $student->last_name . ", This is to inform you that the Chess Class fee has been due with Archer Chess Academy. Your previous module has ended on " . $endDateFormatted . ". You are requested to pay the fees before the next class to continue enjoying your chess class. The total due amount is " . $totalDueAmount . "  " . $currency . ". Kindly check out Name Archer Chess Academy on the payment gateway before making payment.";
                // $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                // $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                if ($studentFee) {
                    $message      = $studentFee->generateFeeDueMessage();
                    $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                    $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                } else {
                    $whatsappLink = '';
                }

                // Conditionally include the WhatsApp link based on the user's role
                $whatsappBadge = ! $isCoach ? ' &nbsp; ' . $whatsappLink : '';

                // Badge for the StudentFee end_date, only if status is FEESDUE
                $endDateBadge = '';
                if ($student->status === 'FEESDUE') {
                    $endDateBadge = '<span class="badge bg-primary fs-1">' . $endDateFormatted . '</span>';
                }

                return '<div class="d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 student-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $student->id . '" data-id="' . $student->id . '">
                                    <i class="ti ti-analyze"></i> &nbsp; ' . $student->status . '
                                </button>
                                ' . $endDateBadge . '
                            </div>
                            <div class="d-flex justify-content-end">' . $whatsappBadge . '</div>
                        </div>';
            })
            ->addColumn('action', function ($student) {
                return '<a href="' . route('admin.students.show', ['student' => $student->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="students" data-title="Student Details" data-route-key="' . $student->route_key . '"><i class="fa fa-eye"></i> &nbsp; Show </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['student_id', 'status', 'action'])
            ->setRowId('id')
            ->make(true);
    }




    public function batchData(Request $request)
    {
        $latestVersions = Batch::select('parent_id', \DB::raw('MAX(version) as max_version'))
            ->groupBy('parent_id');
        $query = Batch::select('batchs.*')
            ->joinSub($latestVersions, 'latest_versions', function ($join) {
                $join->on('batchs.parent_id', '=', 'latest_versions.parent_id')
                    ->on('batchs.version', '=', 'latest_versions.max_version');
            })
            ->orderByDesc('batchs.id');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('level') && $request->level != '') {
            $query->whereHas('studentBatches', function ($query) use ($request) {
                $query->where('level_id', $request->level)
                    ->where('status', 'ACTIVE');
            });
        }
        if ($request->has('student') && $request->student != '') {
            $query->whereHas('studentBatches', function ($query) use ($request) {
                $query->where('student_id', $request->student);
            });
        }
        if ($request->has('coach') && $request->coach != '') {
            $query->where('coach_id', $request->coach);
        }
        return DataTables::eloquent($query)
            ->addColumn('name', function ($batch) {
                return $batch->name;
            })
            ->addColumn('total_active_students', function ($batch) {
                $totalActiveStudents = $batch->studentBatches()->where('status', 'ACTIVE')->count();
                return '<span class="badge bg-warning fs-1">' . $totalActiveStudents . ' &nbsp;  <i class="ti ti-user-shield"></i> </span>';
            })
            ->editColumn('version', function ($batch) {
                return $batch->version;
            })
            ->editColumn('status', function ($batch) {
                switch ($batch->status) {
                    case 'INACTIVE':
                        $badgeColor = 'warning';
                        break;
                    case 'STANDBY':
                        $badgeColor = 'danger';
                        break;
                    case 'ACTIVE':
                        $badgeColor = 'success';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }
                return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 batch-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $batch->id . '" data-id="' . $batch->id . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $batch->status . '</button>';
            })
            ->addColumn('total_sessions_completed', function ($batch) {
                $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)
                    ->where('status', 'COMPLETED')
                    ->orderByDesc('id')
                    ->first();
                $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;

                return '<span class="badge bg-success fs-3"> ' . $totalSessionsCompleted . ' &nbsp; <i class="ti ti-circle-check" style="font-size: 1em;"></i> </span>';
            })
            ->addColumn('timeline', function ($batch) {
                if ($batch->status === 'ACTIVE') {
                    $studentBatch = $batch->studentBatches()
                        ->where('status', 'ACTIVE')
                        ->orderByDesc('updated_at')
                        ->first();
                    if ($studentBatch) {
                        $startDate = Carbon::parse($studentBatch->start_date)->format('j, F Y');
                        $endDate   = Carbon::parse($studentBatch->end_date)->format('j, F Y');
                        return '( &nbsp; ' . $startDate . ' - ' . $endDate . ' &nbsp; )';
                    }
                }
                return '';
            })
            ->addColumn('assign', function ($batch) {
                $assignBadge = '<a href="' . route('admin.batchs.assign.student', ['batch' => $batch->id]) . '" class="badge bg-primary fs-1"><i class="ti ti-school"></i> &nbsp; Assign  &nbsp; </a>';
                return $assignBadge;
            })
            ->addColumn('action', function ($batch) {

                $show = '<a href="' . route('admin.batchs.show', ['batch' => $batch->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="batchs" data-title="Batch Details" data-route-key="' . $batch->route_key . '"><i class="fa fa-eye"></i> &nbsp; Show </a>';

                return $show;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'total_active_students', 'coach_id', 'action', 'status', 'schedule', 'assign', 'reassign', 'total_sessions_completed', 'timeline'])
            ->setRowId('id')
            ->make(true);
    }

    public function availabilityIndex(Request $request)
    {
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.Dashboard.availability-index', compact('coaches'));
    }

    public function availability(Request $request)
    {
        $coachId  = $request->coach_id;
        $selected = $request->date ? Carbon::parse($request->date) : Carbon::now();
        $weekStart = $selected->startOfWeek();
        $weekdays = [];

        // 1️⃣ Build weekdays with dates
        for ($i = 0; $i < 7; $i++) {
            $dayDate = $weekStart->copy()->addDays($i);
            $weekdays[] = [
                'day'  => $dayDate->format('l'),
                'date' => $dayDate->format('d M Y'),
                'full' => $dayDate->format('Y-m-d'),
            ];
        }

        // 2️⃣ Fetch availability periods for the coach
        $availabilityPeriods = CoachAvailabilityPeriod::whereHas('coachAvailability', function($q) use ($coachId) {
            $q->where('coach_id', $coachId)
            ->where('status', 'ACTIVE');
        })->orderBy('from_period')->get();
        // dd( $availabilityPeriods );

        $timeSlots    = [];
        $displaySlots = [];

        foreach ($availabilityPeriods as $period) {
            $fromTime = Carbon::parse($period->from_period);
            $toTime   = Carbon::parse($period->to_period);

            for ($time = $fromTime->copy(); $time->lt($toTime); $time->addMinutes(30)) {
                $timeStr    = $time->format('H:i:s'); 
                $displayStr = $time->format('g:i A');

                if (!in_array($timeStr, $timeSlots)) { // ✅ prevent duplicates
                    $timeSlots[]    = $timeStr;
                    $displaySlots[] = $displayStr;
                }
            }
        }


        // 4️⃣ Build availability grid
        $grid = [];

        foreach ($timeSlots as $index => $slot) {
            // dd( $slot );
            $endSlot = Carbon::parse($slot)->addMinutes(30)->format('H:i:s');
            foreach ($weekdays as $dayInfo) {
                // dd( $dayInfo );
                    $date = $dayInfo['full'];
                    $day  = $dayInfo['day'];            
                    $coach = Coach::find($coachId);
                    // dd($day, $date, $slot, $endSlot, $coach->id );
                // if ($date == '2025-01-02' && $slot == '11:30:00') {
                    // ✅ Coach Leave
                    if ($this->checkCoachLeave($coach, $date)) {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'Leave', 'color' => 'orange'];
                        continue;
                    }
                    
                    // ✅ Batch Class
                    if ($this->checkBatchSchedule($coach, $date, $day, $slot, $endSlot)) {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'Class', 'color' => 'red'];
                        continue;
                    }
                    // dd($date);
                    // dd($this->   checkCoverupClass($coach, $date, $day, $slot, $endSlot));
                    if ($this->checkCoverupClass($coach, $date, $day, $slot, $endSlot)) {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'Coverup', 'color' => 'purple'];
                        continue;
                    }

                    // ✅ Demo Assign
                    if ($this->checkDemoAssign($coach, $date, $day, $slot, $endSlot)) {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'Demo', 'color' => 'blue'];
                        continue;
                    }

                    // ✅ Availability Check
                    $availableCoaches = $this->getAvailableCoaches($slot, $endSlot, $day, $date, $coachId);
                    if (count($availableCoaches) > 0) {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'Available', 'color' => 'green'];
                    } else {
                        $grid[$displaySlots[$index]][$day] = ['status' => 'N/A', 'color' => '#e3e22e'];
                    }
                // }
            }
        }
        // dd($grid);


        return view('Admin.Dashboard.availability', compact('grid', 'timeSlots', 'weekdays', 'displaySlots'));
    }

    private function checkCoachLeave($coach, $date, $slot = null, $endSlot = null)
    {
        return $coach->leaves()
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->when($slot, function ($query) use ($slot, $endSlot) {
                $query->whereTime('from_time', '<', $endSlot)
                    ->whereTime('to_time', '>', $slot);
            })
            ->exists();
    }

    private function checkDemoAssign($coach, $date, $weekday, $from_time, $to_time)
    {
        return DemoSession::where('coach_id', $coach->id)
            ->whereDate('date', $date)
            // ->whereBetween('time', [$from_time, $to_time])
            ->where('time', $from_time)
            ->where('status', 'ACTIVE')
            ->exists();
    }

    private function checkBatchSchedule($coach, $date, $weekday, $from_time, $to_time)
    {
        // dd( $coach->id, $date, $weekday, $from_time, $to_time );
        $aa = BatchSchedule::whereHas('batch', function ($query) use ($coach, $date) {
                $query->where('coach_id', $coach->id)
                    ->where('status', '!=', 'INACTIVE')->where('start_date', '<=', $date)->where('end_date', '>=', $date);
            })
            ->where('weekday', $weekday)
            ->where(function ($query) use ($from_time, $to_time) {
                $query->where(function ($q) use ($from_time, $to_time) {
                    $q->where('from_time', '<', $to_time)
                    ->where('to_time', '>', $from_time);
                });
            })
            ->exists();
            // dd( $aa );
        return $aa;
    }
    private function checkCoverupClass($coach, $date, $weekday, $from_time, $to_time)
{
    // normalize inputs
    $dateOnly = Carbon::parse($date)->toDateString();         // 'YYYY-MM-DD'
    $weekdayName = Carbon::parse($date)->format('l');        // 'Monday' etc

    $isCoverupScheduleExist = Coverupclass::where('date', $dateOnly)
        ->where('new_coach_id', $coach->id)
        ->whereHas('batch', function ($batchQuery) use ($coach, $dateOnly, $weekdayName, $from_time, $to_time) {
            $batchQuery->whereHas('batchSchedules', function ($schedQ) use ($weekdayName, $from_time, $to_time) {
                    $schedQ->where('weekday', $weekdayName)
                        ->where(function ($subQ) use ($from_time, $to_time) {
                            $subQ->where('from_time', '<', $to_time)
                                ->where('to_time', '>', $from_time);
                        });
                });
        })
        ->exists();

    return $isCoverupScheduleExist;
}


    public function getAvailableCoaches($from_time, $to_time, $weekday, $date, $coach_id)
    {
        $dayOfWeek = Carbon::parse($date)->dayName;

        $availability = CoachAvailability::where('coach_id', $coach_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'ACTIVE')
            ->whereHas('periods', function ($query) use ($from_time, $to_time) {
                $query->where('from_period', '<=', $from_time)
                    ->where('to_period', '>=', $to_time);
            })
            ->exists();

        return $availability ? Coach::where('id', $coach_id)->with('user')->get() : collect([]);
    }


    public function getUnmarkedAttendance(Request $request)
    {
        $fromDate = '2025-12-16';
        $toDate   = Carbon::today()->format('Y-m-d');

        $user  = Auth::user();
        $coach = Coach::where('user_id', $user->id)->first();

        // Fetch the FIRST pending attendance of ANY TYPE
        $pending = CoachAttendance::where('coach_id', $coach->id)
            ->where('status', '!=', 'CANCELLED')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('type', '!=', 'Demo')
            ->whereNull('homework_link')
            ->whereNull('chapter_name')
            ->orderBy('date', 'asc')
            ->first();

        if (!$pending) {
            return response()->json([
                'attendance' => null
            ]);
        }

        // Determine TYPE based on the fields present
        if ($pending->batch_id) {

            // Batch or Coverup or Yesterday Batch
            $batch = Batch::find($pending->batch_id);

            return response()->json([
                'attendance'      => $pending,
                'batch'           => $batch,
                'type'            => $pending->type,      // BATCH, COVERUP, YESTERDAY BATCH
                'attendanceId'    => $pending->id,
                'attendanceDate'  => $pending->date,
                'attendanceTime'  => $pending->time
            ]);
        }

        // if ($pending->demolead_id) {

        //     // DEMO session
        //     $demo = DemoSession::find($pending->demolead_id);

        //     return response()->json([
        //         'attendance'      => $pending,
        //         'demo'            => $demo,
        //         'type'            => "Demo",
        //         'attendanceId'    => $pending->id,
        //         'attendanceDate'  => $pending->date,
        //         'attendanceTime'  => $pending->time
        //     ]);
        // }

        return response()->json([
            'attendance' => null
        ]);
    }

    /**
     * Scheduled slot start on the attendance calendar day (e.g. 12:03:01 on that date).
     */
    private function scheduledBatchDateTime(string $attendanceDate, $fromTimeRaw): Carbon
    {
        $time = Carbon::parse($fromTimeRaw)->format('H:i:s');

        return Carbon::parse(trim($attendanceDate) . ' ' . $time);
    }

    /**
     * Time submitted on the attendance form, on the same calendar day as the session.
     */
    private function actualAttendanceDateTime(string $attendanceDate, $timeRaw): Carbon
    {
        $time = Carbon::parse($timeRaw)->format('H:i:s');

        return Carbon::parse(trim($attendanceDate) . ' ' . $time);
    }

}
