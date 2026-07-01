<?php
namespace App\Http\Controllers\Admin;

use DateTime;
use DataTables;
use DatePeriod;
use DateInterval;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Level;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\Coverupclass;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use Illuminate\Validation\Rule;
use App\Models\CoachAvailability;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CoachAvailabilityService;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Auth;


class BatchController extends Controller
{
    private function joiningStartDateForStudent(Student $student, $fallbackDate = null): string
    {
        $batchStartDate = Carbon::parse($fallbackDate ?: Carbon::today())->toDateString();
        $latestActiveFee = StudentFee::where('student_id', $student->id)
            ->where('status', 'ACTIVE')
            ->whereNotNull('start_date')
            ->orderBy('end_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($latestActiveFee) {
            $feeStartDate = Carbon::parse($latestActiveFee->start_date)->toDateString();

            return Carbon::parse($feeStartDate)->gt(Carbon::parse($batchStartDate))
                ? $feeStartDate
                : $batchStartDate;
        }

        return $batchStartDate;
    }

    private function joiningEndDateForStudent(Student $student, $fallbackDate = null): string
    {
        $batchEndDate = Carbon::parse($fallbackDate ?: Carbon::today())->toDateString();
        $latestActiveFee = StudentFee::where('student_id', $student->id)
            ->where('status', 'ACTIVE')
            ->whereNotNull('end_date')
            ->orderBy('end_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($latestActiveFee) {
            $feeEndDate = Carbon::parse($latestActiveFee->end_date)->toDateString();

            return Carbon::parse($feeEndDate)->lt(Carbon::parse($batchEndDate))
                ? $feeEndDate
                : $batchEndDate;
        }

        return $batchEndDate;
    }

    public function index()
    {
        $this->updateBatchStatusBasedOnEndDate();
        $this->deleteCancelledBatchesForCurrentUser();
        $levels = Level::where('status', 'ACTIVE')->get();
        // $batches = $this->fetchBatchData(); // already added console cmd for this .
        // $data = $batches->filter(function ($batch) {
        //     return !$batch->coach_attendance_submitted;
        // })->map(function ($batch) {
        //     return [
        //         'batch_id' => $batch->id,
        //         'batch_name' => $batch->name,
        //         'batch_level' => $batch->batchLevel,
        //         'batch_start_date' => $batch->batchStartDate,
        //         'batch_end_date' => $batch->batchEndDate,
        //         'total_sessions_completed' => $batch->totalSessionsCompleted,
        //         'from_time' => $batch->fromTime,
        //         'to_time' => $batch->toTime,
        //         'students' => $batch->studentBatches->map(function ($studentBatch) {
        //             return [
        //                 'student_id' => $studentBatch->student->id,
        //                 'student_name' => $studentBatch->student->first_name . ' ' . $studentBatch->student->last_name,
        //                 'attendance' => $studentBatch->attendance,
        //             ];
        //         }),
        //     ];
        // });
        //dd($data);

        return view('Admin.Batchs.index', compact('levels'));
    }

    private function fetchBatchData()
    {
        // Set the timezone to IST
        $now          = Carbon::now('Asia/Kolkata');
        $oneHourAgo   = $now->subHour()->format('H:i:s');
        $todayDate    = $now->toDateString();
        $todayWeekday = $now->format('l'); // Get the full name of the weekday

        $batches = Batch::with([
            'batchSchedules' => function ($query) use ($todayWeekday, $oneHourAgo) {
                $query->where('weekday', $todayWeekday)
                    ->where('to_time', '<=', $oneHourAgo);
            },
            'studentBatches' => function ($query) use ($todayDate) {
                $query->eligibleOn($todayDate)->with('student');
            },
            'coach',
            'parent',
        ])
            ->where('status', 'ACTIVE')
            ->whereHas('batchSchedules', function ($query) use ($todayWeekday, $oneHourAgo) {
                $query->where('weekday', $todayWeekday)
                    ->where('to_time', '<=', $oneHourAgo);
            })
            ->get();

        $batches->each(function ($batch) use ($now, $todayDate) {
            $batchId = $batch->id;
            $coachId = $batch->coach_id;

            if ($batch->studentBatches->isEmpty()) {
                return;
            }

            // Fetch additional data for each batch
            $batchLevel      = optional($batch->studentBatches->first())->level;
            $batchStartDate  = optional($batch->studentBatches->first())->start_date;
            $batchEndDate    = optional($batch->studentBatches->first())->end_date;
            $coachAttendance = CoachAttendance::
                where('batch_id', $batchId)
            // ->where('coach_id', $coachId)
                ->whereDate('date', $todayDate)
                ->first();
            $latestCompletedSession = CoachAttendance::where('batch_id', $batchId)
            // ->where('coach_id', $coachId)
                ->where('status', 'COMPLETED')
                ->orderByDesc('id')
                ->first();
            $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
            $studentAttendances     = StudentAttendance::with(['student', 'batch'])
                ->whereHas('batch', function ($query) use ($batchId) {
                    $query->where('batch_id', $batchId);
                })
                ->whereDate('date', $todayDate)
                ->get();
            $activeSlot = $batch->batchSchedules->firstWhere('status', 'ACTIVE');
            $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
            $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

            // Add the additional data to the batch object
            $batch->batchLevel             = $batchLevel;
            $batch->batchStartDate         = $batchStartDate;
            $batch->batchEndDate           = $batchEndDate;
            $batch->coachAttendance        = $coachAttendance;
            $batch->totalSessionsCompleted = $totalSessionsCompleted;
            $batch->studentAttendances     = $studentAttendances;
            $batch->fromTime               = $fromTime;
            $batch->toTime                 = $toTime;

            // Check if coach attendance is submitted for this batch
            if (is_null($coachAttendance)) {
                // Automatically submit coach attendance with status 'NOTMARKED'
                $coachAttendance                           = new CoachAttendance();
                $coachAttendance->coach_id                 = $coachId;
                $coachAttendance->type                     = 'BATCH';
                $coachAttendance->batch_id                 = $batchId;
                $coachAttendance->date                     = $todayDate;
                $coachAttendance->time                     = $now->format('H:i:s');
                $coachAttendance->status                   = 'NOTMARKED';
                $coachAttendance->number_of_batch_sessions = $totalSessionsCompleted + 1;
                $coachAttendance->save();

                // Automatically submit student attendance with status 'NOTMARKED'
                $batch->studentBatches->each(function ($studentBatch) use ($batchId, $todayDate, $now) {
                    StudentAttendance::create([
                        'batch_id'                 => $batchId,
                        'student_id'               => $studentBatch->student->id,
                        'date'                     => $todayDate,
                        'time'                     => $now->format('H:i:s'),
                        'status'                   => 'NOTMARKED',
                        'type'                     => 'BATCH',
                        'coach_id'                 => $studentBatch->batch->coach_id,
                        'number_of_batch_sessions' => $studentBatch->attendance ? $studentBatch->attendance->number_of_batch_sessions + 1 : 1,
                    ]);
                });
            }

            // Add student attendance data to each student batch
            $batch->studentBatches->each(function ($studentBatch) use ($studentAttendances) {
                $studentBatch->attendance = $studentAttendances->firstWhere('student_id', $studentBatch->student->id);
            });
        });

        return $batches;
    }

    public function getCoaches(Request $request)
    {
        $user  = Auth::user();
        $query = Coach::where('status', 'ACTIVE')->with('user');
        if ($user->roles()->where('name', 'Coach')->exists()) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->has('student_id') && $request->student_id != '') {
                $studentId = $request->input('student_id');
                $query->whereHas('studentBatches', function ($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                });
            }
        }
        $coaches = $query->get();
        return response()->json($coaches);
    }

    public function changeCoaches(Request $request, CoachAvailabilityService $availability)
    {
        $batchId = $request->batch_id;

        $batch = Batch::with(['batchSchedules' => function ($q) {
            $q->where('weekday', now()->format('l'));
        }])->findOrFail($batchId);

        $schedule = $batch->batchSchedules->first();

        if (!$schedule) {
            return response()->json(['error' => 'Batch is not scheduled for today.'], 403);
        }

        $fromTime = Carbon::createFromFormat('H:i:s', $schedule->from_time)->setDate(
            now()->year, now()->month, now()->day
        );

        if (now()->gt($fromTime->addMinutes(10))) {
            return response()->json(['error' => 'Coach change is not allowed after 10 minutes from batch start.'], 403);
        }
        $availableCoaches = Coach::where('status', 'ACTIVE')
            ->with('user')
            ->get()
            ->filter(function ($coach) use ($batch, $schedule, $availability) {
                if ($coach->id == $batch->coach_id) {
                    return false;
                }

                return $availability->validateCoachForSingleEvent(
                    $coach->id,
                    Carbon::today()->toDateString(),
                    $schedule->from_time,
                    $schedule->to_time
                )['ok'];
            })
            ->values();

        $currentCoachId = $batch->coach_id;

        return view('Admin.Batchs.changeCoach', [
            'batch_id' => $batchId,
            'coaches' => $availableCoaches,
            'current_coach_id' => $currentCoachId
        ]);
    }

    public function changeCoach(Request $request, CoachAvailabilityService $availability){
        $coach = Coach::find($request->coach_id);
        $batch = Batch::find($request->batch_id);

        if (!$coach || !$batch) {
            return response()->json(['error' => 'Coach or Batch not found.'], 404);
        } 

        $todayDay = Carbon::now()->format('l');
        $batchSchedule = $batch->batchSchedules()->where('weekday', $todayDay)->first();

        if (!$batchSchedule) {
            return response()->json(['error' => 'Batch is not scheduled for today.'], 403);
        }

        if ($batch->coach_id == $coach->id) {
            return response()->json(['error' => 'You cannot assign the same coach to the batch.'], 403);
        }

        $coachValidation = $availability->validateCoachForSingleEvent(
            (int) $coach->id,
            Carbon::today()->toDateString(),
            $batchSchedule->from_time,
            $batchSchedule->to_time
        );

        if (!$coachValidation['ok']) {
            return response()->json(['error' => $coachValidation['message']], 422);
        }

        $coverupclass                   = new Coverupclass();
        $coverupclass->batch_id         = $batch->id;
        $coverupclass->batchschedule_id = $batchSchedule->id;
        $coverupclass->old_coach_id     = $batch->coach_id;
        $coverupclass->new_coach_id     = $coach->id;
        $coverupclass->date             = Carbon::now()->format('Y-m-d');
        $coverupclass->save();


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

        return response()->json([
            'status'  => 'success',
            'message' => 'Coach changed successfully.',
        ], 200);
    }

    public function getStudents(Request $request)
    {
        $user  = Auth::user();
        $query = Student::query();

        if ($user->roles()->where('name', 'Coach')->exists()) {
            $coach = Coach::where('user_id', $user->id)->first();
            if ($coach) {
                $query->whereHas('studentBatches', function ($query) use ($coach) {
                    $query->where('coach_id', $coach->id);
                });
            }
        } else {
            if ($request->has('coach_id') && $request->coach_id != '') {
                $coachId = $request->input('coach_id');
                $query->whereHas('studentBatches', function ($query) use ($coachId) {
                    $query->where('coach_id', $coachId);
                });
            }
        }

        $students = $query->get();
        return response()->json($students);
    }

    private function updateBatchStatusBasedOnEndDate()
    {
        $activeBatches = Batch::where('status', 'ACTIVE')->orwhere('status', 'STANDBY')->get();
        foreach ($activeBatches as $batch) {
            // $activeStudentBatches = StudentBatch::where('batch_id', $batch->id)->where('status', 'ACTIVE')->get();
            // foreach ($activeStudentBatches as $studentBatch) {
            //     if (Carbon::parse($studentBatch->end_date)->lt(Carbon::today())) {
            //         $batch->update(['status' => 'STANDBY']);
            //         break;
            //     }
            // }
            // if ($batch->name == 'PRATYUSH-WF 4:30 AM') {
            // dd($batch);
            if (Carbon::parse($batch->end_date)->lt(Carbon::today())) {
                // dd(22);
                $batch->update(['status' => 'STANDBY']);
            }
            // }
        }
    }

    private function deleteCancelledBatchesForCurrentUser()
    {
        $currentUserId    = Auth::id();
        $cancelledBatches = Batch::where('confirm_reassign', 'CANCEL')
            ->where('created_by', $currentUserId)
            ->get();

        foreach ($cancelledBatches as $batch) {
            // Delete related records in StudentBatch and BatchSchedule
            StudentBatch::where('batch_id', $batch->id)->delete();
            BatchSchedule::where('batch_id', $batch->id)->delete();

            // Delete the batch
            $batch->delete();
        }
    }

    public function data(Request $request)
    {
        $user                = auth()->user();
        $role                = $user->getRoleNames()->toArray();

        $isAdminOrSuperAdmin = in_array("Admin", $role) || in_array("SuperAdmin", $role);
        // Get the countries the user can see
        $allowedCountries = [];
        if (! $isAdminOrSuperAdmin) {
            $userRole = $user->roles()->first();
            if ($userRole && $userRole->countries) {
                $allowedCountries = json_decode($userRole->countries);
            }
        }
        $latestVersions = Batch::select('parent_id', \DB::raw('MAX(version) as max_version'))
            ->groupBy('parent_id');
        $query = Batch::select('batchs.*')
            ->joinSub($latestVersions, 'latest_versions', function ($join) {
                $join->on('batchs.parent_id', '=', 'latest_versions.parent_id')
                    ->on('batchs.version', '=', 'latest_versions.max_version');
            });

        if ($request->status) {
            $query->where('status', $request->status);
        }else{
            $query->where('status', '!=', 'INACTIVE');
        }

        if ($request->country) {
            $country = $request->country;
            #we are using below code instead of wherejsoncontains
            // First, check if the `country` column contains valid JSON for this batch
            $query->whereNotNull('country')->where(function ($query) use ($country) {
                $query->whereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $country . '"]'])
                    ->orWhere(function ($query) use ($country) {
                        $query->whereRaw('NOT json_valid(country)')->where('country', $country);
                    });
            });
        }
        // Filter by allowed countries for non-admin/superadmin users
        if (! $isAdminOrSuperAdmin && ! empty($allowedCountries)) {
            $query->where(function ($query) use ($allowedCountries) {
                foreach ($allowedCountries as $allowedCountry) {
                    $query->orWhereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $allowedCountry . '"]'])
                        ->orWhere(function ($query) use ($allowedCountry) {
                            $query->whereRaw('NOT json_valid(country)')->where('country', $allowedCountry);
                        });
                }
            });
        }
        if ($request->has('level') && $request->level != '') {
            // $query->whereHas('studentBatches', function ($query) use ($request) {
            //     $query->where('level_id', $request->level)
            //         ->where('status', 'ACTIVE');
            // });
            if ($request->filled('level')) {
                $query->where('batchs.level_id', $request->level);
            }
        }
        if ($request->has('student') && $request->student != '') {
            $query->whereHas('studentBatches', function ($query) use ($request) {
                $query->where('student_id', $request->student);
            });
        }
        if ($request->has('coach') && $request->coach != '') {
            $query->where('coach_id', $request->coach);
        }
        if ($request->has('weekday') && $request->weekday != '') {
            $weekday = $request->weekday;
            $query->whereHas('batchSchedules', function ($query) use ($weekday) {
                $query->where('weekday', $weekday);
            });
        }

        if ($request->has('is_time') && $request->is_time == 'YES') {
            $todayDay = now()->format('l'); 
            $nowTime = now()->format('H:i:s');

            $query->where('status', 'ACTIVE')
                ->whereHas('batchSchedules', function ($query) use ($todayDay, $nowTime) {
                    $query->where('weekday', $todayDay)
                        ->where('to_time', '>=', $nowTime) 
                        ->whereNotNull('from_time')
                        ->whereNotNull('to_time');
                })
                ->with(['batchSchedules' => function ($q) use ($todayDay, $nowTime) {
                    $q->where('weekday', $todayDay)
                        ->where('to_time', '>=', $nowTime) 
                        ->orderBy('from_time', 'asc');
                }])
                ->orderByRaw("
                    (
                        SELECT MIN(from_time)
                        FROM batch_schedules
                        WHERE batch_schedules.batch_id = batchs.id
                        AND batch_schedules.weekday = ?
                        AND batch_schedules.to_time >= ?
                    ) ASC
                ", [$todayDay, $nowTime]);
        } else {
            $query->orderByDesc('batchs.id');
        }



        // Anil-TTH5:30AM
        return DataTables::eloquent($query)
            ->editColumn('name', function ($batch) {
                $today = Carbon::today()->toDateString();

                $regularActiveCount = $batch->studentBatches()
                    ->where('coach_id', $batch->coach_id)
                    ->eligibleOn($today)
                    ->get()
                    ->unique('student_id')
                    ->count();

                $totalActiveStudentsBadge = '<span class="badge bg-warning fs-1">' .
                $regularActiveCount . ' &nbsp; <i class="ti ti-user-shield"></i> </span>';

                $lateJoinerCount = $batch->studentBatches()
                    ->where('coach_id', $batch->coach_id)
                    ->where('status', 'ACTIVE')
                    ->whereDate('start_date', '>', $today)
                    ->get()
                    ->unique('student_id')
                    ->count();

                $lateJoinerBadge = '<span class="badge bg-info fs-1">' . $lateJoinerCount . ' late &nbsp; <i class="ti ti-user-plus"></i> </span>';

                $current_feesDueStudentIds = $batch->studentBatches()
                    ->where('coach_id', $batch->coach_id)
                    ->whereHas('student', function ($query) {
                        $query->where('status', 'FEESDUE');
                    })
                    ->distinct('student_id')
                    ->pluck('student_id')
                    ->toArray();



                $feesDueStudentCount = count($current_feesDueStudentIds);

                if ($batch->version > 1) {
                    $current_version = $batch->version;

                    $last_batch = Batch::where('parent_id', $batch->parent_id)
                        ->where('version', $current_version - 1)
                        ->first();

                    $lastfeesDueStudentIds = $last_batch->studentBatches()
                        ->where('coach_id', $batch->coach_id)
                        ->where('is_fees_due',1)
                        ->whereHas('student', function ($query) {
                            $query->where('status', 'FEESDUE');
                        })
                        ->distinct('student_id') // ✅ Use distinct instead of unique()
                        ->pluck('student_id')
                        ->toArray();


                    $feesDueStudentCount = count(array_unique(array_merge($current_feesDueStudentIds, $lastfeesDueStudentIds)));
                }

                // dd($feesDueStudentCount);

                // dd($lastfeesDueStudentCount, $current_feesDueStudentCount, $feesDueStudentCount);

                $feesDueStudentBadge = '<span class="badge bg-danger fs-1">' . $feesDueStudentCount . ' &nbsp; <i class="ti ti-user-shield"></i> </span>';

                $recentActiveStudentBatch = $batch->studentBatches()
                    ->where('status', 'ACTIVE')
                    ->where('coach_id', $batch->coach_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Get the level name of the most recently created ACTIVE student batch
                $levelName  = $batch->level_id ? $batch->level->name : '';
                $levelBadge = $levelName ? '<span class="badge bg-primary fs-1"> (' . $levelName . ') </span>' : '';

                $batchTypeBadge = $batch->is_one_to_one
                    ? '<span class="badge bg-dark fs-1 ms-1">1-1</span>'
                    : '<span class="badge bg-light-secondary text-secondary fs-1 ms-1">Normal</span>';
                $batchName = $batch->name . ' ' . $batchTypeBadge;

                return '<div class="d-flex justify-content-between">' . $batchName . ' &nbsp; ' .
                    '<div class="d-flex justify-content-end">' . $levelBadge . '&nbsp;&nbsp;' . $totalActiveStudentsBadge . '&nbsp;&nbsp;' . $lateJoinerBadge . '&nbsp;&nbsp;' . $feesDueStudentBadge . '</div>' . '</div>';
            })
            ->editColumn('version', function ($batch) {
                return $batch->version;
            })
            ->editColumn('country', function ($batch) {
                $countries = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;
                return '<img src="/backend/dist/images/svgs/icon-connect.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $countries;
            })
            ->editColumn('kids_zone_name', function ($batch) {
                if (strlen($batch->kids_zone_name) > 20) {
                    return substr($batch->kids_zone_name, 0, 15) . '   .....';
                } else {
                    return $batch->kids_zone_name;
                }
            })
            ->editColumn('created_by', function ($batch) {
                if ($batch->createdBy) {
                    $name = $batch->createdBy->first_name . ' ' . $batch->createdBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1">N/A</span>';
            })
            ->editColumn('updated_by', function ($batch) {
                if ($batch->updatedBy) {
                    $name = $batch->updatedBy->first_name . ' ' . $batch->updatedBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1">N/A</span>';
            })
            ->editColumn('created_at', function ($batch) {
                $createdAt = Carbon::parse($batch->created_at)->setTimezone('Asia/Kolkata')->format('j, M Y h:i A');
                return '<span class="mb-1 badge font-medium bg-light-info text-info fs-1"><i class="ti ti-calendar"></i> &nbsp; ' . $createdAt . '</span>';
            })
            ->editColumn('updated_at', function ($batch) {
                $updatedAt = Carbon::parse($batch->updated_at)->setTimezone('Asia/Kolkata')->format('j, M Y h:i A');
                return '<span class="mb-1 badge font-medium bg-light-warning text-warning fs-1"><i class="ti ti-calendar"></i> &nbsp; ' . $updatedAt . '</span>';
            })
            ->editColumn('start_url', function ($batch) {
                if(!empty($batch->start_url)) {
                    return '<i class="ti ti-link cursor-pointer text-primary fs-5 copy-link" data-url="' . e($batch->start_url) . '" title="Copy Start URL"></i>';
                } 

                return '<span class="badge bg-secondary fs-1">N/A</span>';
            })
            ->editColumn('join_url', function ($batch) {
                if(!empty($batch->join_url)){
                    return '<i class="ti ti-link cursor-pointer text-success fs-5 copy-link" data-url="' . e($batch->join_url) . '" title="Copy Join URL"></i>';
                }

                return '<span class="badge bg-success fs-1">N/A</span>';
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
                    case 'UPCOMING':
                        $badgeColor = 'info';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }
                return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 batch-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $batch->id . '" data-id="' . $batch->id . '" data-status="' . $batch->status . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $batch->status . '</button>';
            })
            ->addColumn('timeline', function ($batch) {
                $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)
                    ->where('status', 'COMPLETED')
                    ->count();

                $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession : 0;
                if ($batch->level_id == 2) {
                    $totalSessionsCompleted = $totalSessionsCompleted + 10;
                }

                $batchsessions = $batch->number_of_sessions ? $batch->number_of_sessions : 0;

                // Format the session completed badge
                $badge = '<span class="badge bg-danger fs-3"> ' . $batchsessions . ' &nbsp; 
                            <i class="ti ti-books" style="font-size: 1em;"></i> 
                        </span> &nbsp;  
                        <i class="ti ti-arrow-right" style="font-size: 1em;"></i> &nbsp; 
                        <span class="badge bg-success fs-3"> ' . $totalSessionsCompleted . ' &nbsp; 
                            <i class="ti ti-circle-check" style="font-size: 1em;"></i> 
                        </span>';

                if ($batch->status === 'ACTIVE') { 
                    if ($batch) {
                        $startDate = Carbon::parse($batch->start_date)->format('j, F Y');
                        $endDate   = Carbon::parse($batch->end_date)->format('j, F Y');
                        return '<p class="mb-0 text-start">' . $badge . ' &nbsp; ( &nbsp; ' . $startDate . ' - ' . $endDate . ' &nbsp; ) </p>';
                    }
                }

                return '<p class="mb-0 text-start">' . $badge . '</p>';
            })
            ->addColumn('assign', function ($batch) {
                $assignBadge = '<a href="' . route('admin.batchs.assign.student', ['batch' => $batch->id]) . '" class="badge bg-primary fs-1"><i class="ti ti-school"></i> &nbsp; Assign  &nbsp; </a>';
                return $assignBadge;
            })
            // ->addColumn('action', function ($batch) {
            //     $change_coach = '';

            //     $is_today_batch = false;

            //     $todayday = Carbon::now()->format('l');

            //     $batchdays = $batch->batchSchedules->pluck('weekday')->toArray();

            //     if (in_array($todayday, $batchdays)) {
            //         $is_today_batch = true;
            //     }

            //     if ($is_today_batch) {
            //         $change_coach = '<a href="#" class="badge bg-secondary fs-1 changebatch-coach-btn" data-batch-id="' . $batch->id . '" data-batch-name="' . $batch->name . '" data-coach-id="' . $batch->coach_id . '" data-route-key="' . $batch->route_key . '"><i class="fas fa-sync"></i></a>';
            //     }


            //     $edit = '<a href="' . route('admin.batchs.edit', ['batch' => $batch->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

            //     $show = '<a href="' . route('admin.batchs.show', ['batch' => $batch->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="batchs" data-title="Batch Details" data-route-key="' . $batch->route_key . '"><i class="fa fa-eye"></i></a>';

            //     $delete = '';
            //     if (auth()->user()->hasRole('SuperAdmin')) {
            //         // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-batch-id="' . $batch->id . '"><i class="fa fa-trash  fs-1"></i></a>';
            //     }
            //     return $edit . ' ' . $show . '  ' . $change_coach;
            // })
            ->addColumn('action', function ($batch) {
                $change_coach = '';
                $is_today_batch = false;

                $today = Carbon::now();
                $todayDay = $today->format('l');
                $todayDate = $today->toDateString();

                foreach ($batch->batchSchedules as $schedule) {
                    if ($schedule->weekday === $todayDay) {
                        $fromDateTime = Carbon::parse($todayDate . ' ' . $schedule->from_time);
                        if ($today->greaterThanOrEqualTo($fromDateTime) && $today->lessThanOrEqualTo($fromDateTime->copy()->addMinutes(800))) {
                            $is_today_batch = true;
                            break;
                        }
                    }
                }

                if ($is_today_batch) {
                    $change_coach = '<a href="#" class="badge bg-secondary fs-1 changebatch-coach-btn" data-batch-id="' . $batch->id . '" data-batch-name="' . $batch->name . '" data-coach-id="' . $batch->coach_id . '" data-route-key="' . $batch->route_key . '"><i class="fas fa-sync"></i></a>';
                }

                // $edit = '';

                // if (auth()->user()->batch_edit == 'YES' || auth()->user()->hasRole('SuperAdmin')) {
                    $edit = '<a href="' . route('admin.batchs.edit', ['batch' => $batch->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                // }

                $show = '<a href="' . route('admin.batchs.show', ['batch' => $batch->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="batchs" data-title="Batch Details" data-route-key="' . $batch->route_key . '"><i class="fa fa-eye"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-batch-id="' . $batch->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                // return $edit . ' ' . $show;
                return $edit . ' ' . $show . '  ' . $change_coach;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'coach_id', 'action', 'status', 'schedule', 'assign', 'reassign', 'timeline', 'created_by', 'updated_by', 'kids_zone_name', 'country', 'created_at', 'updated_at', 'start_url', 'join_url'])
            ->setRowId('id')
            ->make(true);
    }

    public function list(Request $request)
    {
        $countries = $request->input('countries', []);
        $batches   = Batch::where('status', 'ACTIVE')
            ->where(function ($query) use ($countries) {
                foreach ($countries as $country) {
                    $query->orWhereRaw('JSON_CONTAINS(country, ?)', [json_encode($country)]);
                }
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $batches, // Fix variable name from $batch to $batches
        ], 201);
    }

    public function masterclassTounamentlist(Request $request)
    {
        $countries = $request->input('countries', []);
        $batches   = Batch::where('status', '!=', 'INACTIVE')
            ->where(function ($query) use ($countries) {
                foreach ($countries as $country) {
                    $query->orWhereRaw('JSON_CONTAINS(country, ?)', [json_encode($country)]);
                }
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $batches, // Fix variable name from $batch to $batches
        ], 201);
    }

    public function create()
    {
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.Batchs.form', compact('coaches'));
    }

    public function addWeekday()
    {
        $batch_schedule = new BatchSchedule;
        $batch_schedule->save();
        return view('Admin.Batchs.scheduleform', compact('batch_schedule'));

    }

    public function store(Request $request, CoachAvailabilityService $availability)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('batchs')->where(function ($query) {
                    return $query->where('status', '!=', 'inactive');
                }),
            ],
        ]);

        $request->validate($this->rules, $this->customMessages);

        $daysOfWeek = is_array($request->input('weekday', [])) ? $request->input('weekday', []) : (array) $request->input('weekday', []);
        $fromTimes  = is_array($request->input('from_time', [])) ? $request->input('from_time', []) : (array) $request->input('from_time', []);
        $toTimes    = is_array($request->input('to_time', [])) ? $request->input('to_time', []) : (array) $request->input('to_time', []);
        $schedules  = $availability->schedulesFromRequest($daysOfWeek, $fromTimes, $toTimes);

        $coachValidation = $availability->validateRawBatchCoach(
            (int) $request->coach_id,
            $request->input('country', []),
            $schedules
        );

        if (!$coachValidation['ok']) {
            return response()->json([
                'message' => 'Selected coach is not available.',
                'errors' => [
                    'coach_id' => [$coachValidation['message']],
                ],
            ], 422);
        }

        $batch = new Batch;
        $batch->fill($request->all());
        $batch->is_one_to_one = $request->boolean('is_one_to_one');
        if ($request->has('country')) {
            $batch->country = $request->input('country');
        }

        $batch->version = 1;
        $batch->save();
        $batch->parent_id = $batch->id;
        $batch->status = 'UPCOMING';
        $batch->save();


        // ZOOM CODE

        $coach = Coach::find($request->coach_id);

        if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {

            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id 
            );
            
            $meetingData = [
                'title' => 'Batch - '.$coach->user->first_name.' - '.$batch->name,
                'duration_in_minute' => 40,
                'start_date_time' => now()->addMinutes(5)->toIso8601String(),
            ];

            $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

            // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);
            $batch->start_url = $zoomResponse['start_url'] ?? '';
            $batch->join_url  = $zoomResponse['join_url'] ?? '';
            $batch->zoom_meeting_id  = $zoomResponse['id'] ?? null;
            $batch->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
            $batch->save();
        }

        // Save Batch Schedule Data
        foreach ($daysOfWeek as $index => $day) {
            $batchSchedule            = new BatchSchedule();
            $batchSchedule->batch_id  = $batch->id;
            $batchSchedule->weekday   = $day;
            $batchSchedule->from_time = $fromTimes[$index] ?? null;
            $batchSchedule->to_time   = $toTimes[$index] ?? null;
            $batchSchedule->status    = 'ACTIVE';
            $batchSchedule->save();
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Batch Created Successfully',
            'batch'   => $batch,
        ], 201);
    }

    public function edit(Batch $batch)
    {
        $coaches = Coach::all();
        return view('Admin.Batchs.form', compact('batch', 'coaches'));
    }

    public function editWeekDay(Request $request)
    {
        $batch_schedule = BatchSchedule::find($request->schedule_id);
        if (! $batch_schedule) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Schedule not found.',
            ], 404);
        }
        return view('Admin.Batchs.scheduleform', compact('batch_schedule'));
    }

    public function update(Request $request, Batch $batch, CoachAvailabilityService $availability)
    {
        $this->rules['name']     = '';
        $this->rules['coach_id'] = '';
        $request->validate($this->rules, $this->customMessages);

        $daysOfWeek = is_array($request->input('weekday', [])) ? $request->input('weekday', []) : (array) $request->input('weekday', []);
        $fromTimes  = is_array($request->input('from_time', [])) ? $request->input('from_time', []) : (array) $request->input('from_time', []);
        $toTimes    = is_array($request->input('to_time', [])) ? $request->input('to_time', []) : (array) $request->input('to_time', []);
        $schedules  = $availability->schedulesFromRequest($daysOfWeek, $fromTimes, $toTimes);
        $countries  = $request->input('country', $batch->country ?? []);

        $canChangeCoach = $batch->status === 'UPCOMING';
        $coachId = $canChangeCoach
            ? (int) $request->input('coach_id', $batch->coach_id)
            : (int) $batch->coach_id;

        $coachValidation = $batch->status !== 'UPCOMING' && $batch->start_date && $batch->end_date
            ? $availability->validateCoachForBatchAssignment($coachId, $schedules, $batch->start_date, $batch->end_date, $batch->id, $countries)
            : $availability->validateRawBatchCoach($coachId, $countries, $schedules, $batch->id);

        if (!$coachValidation['ok']) {
            return response()->json([
                'message' => 'Selected coach is not available.',
                'errors' => [
                    'coach_id' => [$coachValidation['message']],
                ],
            ], 422);
        }

        if ($request->boolean('is_one_to_one') && $batch->studentBatches()->where('status', 'ACTIVE')->distinct('student_id')->count('student_id') > 1) {
            return response()->json([
                'message' => 'This batch already has more than one active student, so it cannot be marked as 1-1.',
                'errors' => [
                    'is_one_to_one' => ['This batch already has more than one active student, so it cannot be marked as 1-1.'],
                ],
            ], 422);
        }

        $batch->fill($canChangeCoach ? $request->all() : $request->except('coach_id'));
        $batch->is_one_to_one = $request->boolean('is_one_to_one');
        if ($request->has('country')) {
            $batch->country = $request->input('country');
        }
        $batch->save();

        $coach = Coach::find($batch->coach_id);

        // if (empty($batch->start_url) || empty($batch->join_url)) {

        if(!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {

            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id 
            );
            
            $meetingData = [
                'title' => 'Batch - '.$coach->user->first_name.' - '.$batch->name,
                'duration_in_minute' => 40,
                'start_date_time' => now()->addMinutes(5)->toIso8601String(),
            ];

            $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

            // $zoomResponse = $zoomMeetingService->createMeeting($meetingData);
            $batch->start_url = $zoomResponse['start_url'] ?? '';
            $batch->join_url  = $zoomResponse['join_url'] ?? '';
            $batch->zoom_meeting_id  = $zoomResponse['id'] ?? null;
            $batch->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
            $batch->save();
        }
        // }

        // Extract Request Data
        $deletedDaysInput = $request->input('deleted_days', []);
        $deletedDays = is_array($deletedDaysInput) ? $deletedDaysInput : explode(',', $deletedDaysInput);
        // Update or Add Batch Schedules
        foreach ($daysOfWeek as $id => $day) {
            $scheduleData = [
                'weekday'   => $day,
                'from_time' => $fromTimes[$id] ?? null,
                'to_time'   => $toTimes[$id] ?? null,
                'batch_id'  => $batch->id,
                'status'    => 'ACTIVE',
            ];
            // If ID is numeric, it's an update, otherwise, it's a new schedule
            if (is_numeric($id)) {
                BatchSchedule::where('id', $id)->update($scheduleData);
            } else {
                BatchSchedule::create($scheduleData);
            }
        }
        // Handle Deletions
        if (! empty($deletedDays)) {
            $validDeletedDays = array_filter($deletedDays, function ($value) {
                return is_numeric($value) && $value > 0;
            });
            if (! empty($validDeletedDays)) {
                BatchSchedule::whereIn('id', $validDeletedDays)->delete();
            }
        }
        return response()->json([
            'status'  => 'success',
            'message' => 'Batch Created Successfully',
            'slider'  => $batch,
        ], 201);
    }

    public function show(Batch $batch)
    {
        $batchSchedules = $batch->batchSchedules()->where('status', 'ACTIVE')->get();
        $studentBatches = $batch->studentBatches()->get();

        ## Count the total number of sessions completed
        $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)
            ->where('status', 'COMPLETED')
            ->orderByDesc('id')
            ->count();

        $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession : 0;
        $batchDetails           = $this->getBatchDetails($batch, $batchSchedules);
        return view('Admin.Batchs.show', compact('batch', 'batchDetails', 'batchSchedules', 'studentBatches', 'totalSessionsCompleted'));
    }

    private function getBatchDetails(Batch $batch, $batchSchedules)
    {
        $batchDetails = [
            'name'      => $batch->name,
            'schedules' => [],
            'totalDays' => 0,
            'startDate' => '',
            'endDate'   => '',
        ];

        $studentBatch = $batch->studentBatches->where('status', 'ACTIVE')->first();
        if ($studentBatch) {
            $batchDetails['startDate'] = $studentBatch->start_date;
            $batchDetails['endDate']   = $studentBatch->end_date;

            $startDate      = new DateTime($studentBatch->start_date);
            $endDate        = (new DateTime($studentBatch->end_date))->modify('+1 day');
            $interval       = new DateInterval('P1D');
            $dateRange      = new DatePeriod($startDate, $interval, $endDate);
            $scheduleCounts = [];

            foreach ($batchSchedules as $schedule) {
                if (! isset($scheduleCounts[$schedule->weekday])) {
                    $scheduleCounts[$schedule->weekday] = 0;
                    $batchDetails['schedules'][]        = [
                        'day'   => $schedule->weekday,
                        'from'  => $schedule->from_time,
                        'to'    => $schedule->to_time,
                        'count' => 0,
                    ];
                }
                foreach ($dateRange as $date) {
                    if ($date->format('l') === $schedule->weekday) {
                        $scheduleCounts[$schedule->weekday]++;
                    }
                }
            }

            foreach ($batchDetails['schedules'] as &$scheduleDetail) {
                $scheduleDetail['count'] = $scheduleCounts[$scheduleDetail['day']];
            }

            $batchDetails['totalDays'] = array_sum($scheduleCounts);
        }

        return $batchDetails;
    }

    public function assignBatchToStudent(Request $request, Batch $batch)
    {
        // dd($request->all());

        $user    = auth()->user();
        $is_hide = 0;
        $is_edit = 0; // default

        if ($user->batch_edit === 'YES') {
            $is_edit = 1;
        }

        if ($batch->confirm_reassign != 'CANCEL') {
            if (! in_array($user->id, [13, 14, 15, 16, 17, 1])) {
                if ($batch->start_date) {
                    $is_hide = 1;
                }
            }
        }

        $levels  = Level::where('status', 'ACTIVE')->get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        $preselectedStudentIds = array_filter((array) $request->input('student_id', []));
        $prefillStartDate = null;
        $prefillEndDate = null;

        if ($request->filled('new_enrollment_id')) {
            $newEnrollment = \App\Models\NewEnrollment::find($request->input('new_enrollment_id'));
            if ($newEnrollment) {
                $prefillStartDate = $newEnrollment->start_date;
                $prefillEndDate = $newEnrollment->end_date;
                $preselectedStudentIds[] = $newEnrollment->student_id;
            }
        }

        $preselectedStudentIds = array_values(array_unique(array_filter($preselectedStudentIds)));

        $currentBatchStudentIds = StudentBatch::where('batch_id', $batch->id)
            ->where('status', 'ACTIVE')
            ->pluck('student_id')
            ->toArray();

        $assignedStudentIds = StudentBatch::where('status', 'ACTIVE')
            ->where('batch_id', '!=', $batch->id)
            ->pluck('student_id')
            ->toArray();

        // Merge the current batch student IDs to ensure they are included
        $allExcludedStudentIds = array_diff($assignedStudentIds, $currentBatchStudentIds);

        // dd($currentBatchStudentIds ,$assignedStudentIds, $allExcludedStudentIds);

        $students = Student::where('status', 'ACTIVE')
            ->whereIn('country', $batch->country ?? [])
            ->whereNotIn('id', $allExcludedStudentIds)
            ->get();
        if (! empty($preselectedStudentIds)) {
            $preselectedStudents = Student::whereIn('id', $preselectedStudentIds)->get();
            $students = $students->merge($preselectedStudents)->unique('id')->values();
        }
        // dd($students);

        $assignedStudents = StudentBatch::where('batch_id', $batch->id)->where('status', 'ACTIVE')->get();
        $batchSchedules   = $batch->batchSchedules()->get();

        return view('Admin.Batchs.assignbatchform', compact('batch', 'levels', 'coaches', 'students', 'assignedStudents', 'batchSchedules', 'is_hide', 'is_edit', 'preselectedStudentIds', 'prefillStartDate', 'prefillEndDate'));
    }

    public function saveAssignedStudent(Request $request, CoachAvailabilityService $availability)
    {

        // Validate the request data
        $request->validate([
            'student_ids'        => 'required|array',
            'student_ids.*'      => 'exists:students,id',
            'coach_id'           => 'required|exists:coachs,id',
            'level_id'           => 'required|exists:levels,id',
            'batch_id'           => 'required|exists:batchs,id',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date',
            'number_of_sessions' => 'required|integer|min:1',
        ]);

        ## Retrieve the request data
        $batchId    = $request->batch_id;
        $studentIds = $request->student_ids;
        $coachId    = $request->coach_id;
        $levelId    = $request->level_id;
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;

        ## Get All Data
        $batch             = Batch::find($batchId);
        if ($batch->is_one_to_one && count(array_unique((array) $studentIds)) > 1) {
            return response()->json([
                'message' => 'Only one student can be assigned to a 1-1 batch.',
                'errors' => [
                    'student_ids' => ['Only one student can be assigned to a 1-1 batch.'],
                ],
            ], 422);
        }

        $schedules         = $batch->batchSchedules()
            ->where('status', 'ACTIVE')
            ->get()
            ->map(fn ($schedule) => [
                'weekday' => $schedule->weekday,
                'from_time' => $schedule->from_time,
                'to_time' => $schedule->to_time,
            ])
            ->all();

        $coachValidation = $availability->validateCoachForBatchAssignment((int) $coachId, $schedules, $startDate, $endDate, $batch->id, $batch->country ?? []);
        if (!$coachValidation['ok']) {
            return response()->json([
                'message' => 'Selected coach is not available.',
                'errors' => [
                    'coach_id' => [$coachValidation['message']],
                ],
            ], 422);
        }

        $batch->status     = 'ACTIVE';
        $batch->level_id   = $levelId;
        $batch->coach_id   = $coachId;
        $batch->start_date = $startDate;
        $batch->end_date   = $endDate;
        $batch->save();

        BatchSchedule::where('batch_id', $batchId)->update([
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        $requestStudents = Student::whereIn('id', $studentIds)->get();
        $level           = Level::find($levelId);
        $coach           = Coach::find($coachId);

        foreach ($requestStudents as $key => $student) {
            $studentStartDate = $this->joiningStartDateForStudent($student, $startDate);
            $studentEndDate = $this->joiningEndDateForStudent($student, $endDate);
            $oldStudentBatch = StudentBatch::where('student_id', $student->id)->where('batch_id', $batchId)->orderBy('id', 'desc')->first();
            if ($oldStudentBatch) {
                if ($oldStudentBatch->status == 'INACTIVE' && $oldStudentBatch->is_fees_due == '1') {
                    $oldStudentBatch                     = new StudentBatch();
                    $oldStudentBatch->student_id         = $student->id;
                    $oldStudentBatch->batch_id           = $batchId;
                    $oldStudentBatch->level_id           = $levelId;
                    $oldStudentBatch->coach_id           = $coachId;
                    $oldStudentBatch->start_date         = $studentStartDate;
                    $oldStudentBatch->end_date           = $studentEndDate;
                    $oldStudentBatch->status             = 'ACTIVE';
                    $oldStudentBatch->number_of_sessions = $request->number_of_sessions;
                    $oldStudentBatch->save();
                } else {
                    if ($oldStudentBatch->status == 'ACTIVE') {
                        $oldStudentBatch->status             = 'ACTIVE';
                        $oldStudentBatch->level_id           = $levelId;
                        $oldStudentBatch->coach_id           = $coachId;
                        $oldStudentBatch->start_date         = $studentStartDate;
                        $oldStudentBatch->end_date           = $studentEndDate;
                        $oldStudentBatch->number_of_sessions = $request->number_of_sessions;
                        $oldStudentBatch->save();
                    } else {
                        if ($oldStudentBatch->status == 'INACTIVE' && $oldStudentBatch->is_fees_due == '0') {
                            $studentBatch                     = new StudentBatch();
                            $studentBatch->student_id         = $student->id;
                            $studentBatch->batch_id           = $batchId;
                            $studentBatch->coach_id           = $coachId;
                            $studentBatch->level_id           = $levelId;
                            $studentBatch->status             = 'ACTIVE';
                            $studentBatch->start_date         = $studentStartDate;
                            $studentBatch->end_date           = $studentEndDate;
                            $studentBatch->number_of_sessions = $request->number_of_sessions;
                            $studentBatch->save();
                        } else {
                            $studentBatch                     = new StudentBatch();
                            $studentBatch->student_id         = $student->id;
                            $studentBatch->batch_id           = $batchId;
                            $studentBatch->coach_id           = $coachId;
                            $studentBatch->level_id           = $levelId;
                            $studentBatch->status             = 'ACTIVE';
                            $studentBatch->start_date         = $studentStartDate;
                            $studentBatch->end_date           = $studentEndDate;
                            $studentBatch->number_of_sessions = $request->number_of_sessions;
                            $studentBatch->save();
                        }
                    }
                }
            } else {
                if ($startDate < Carbon::today()) {
                    $studentBatch                     = new StudentBatch();
                    $studentBatch->student_id         = $student->id;
                    $studentBatch->batch_id           = $batchId;
                    $studentBatch->coach_id           = $coachId;
                    $studentBatch->level_id           = $levelId;
                    $studentBatch->status             = 'ACTIVE';
                    $studentBatch->start_date         = $studentStartDate;
                    $studentBatch->end_date           = $studentEndDate;
                    $studentBatch->number_of_sessions = $request->number_of_sessions;
                    $studentBatch->save();
                } else {
                    $studentBatch                     = new StudentBatch();
                    $studentBatch->student_id         = $student->id;
                    $studentBatch->batch_id           = $batchId;
                    $studentBatch->coach_id           = $coachId;
                    $studentBatch->level_id           = $levelId;
                    $studentBatch->status             = 'ACTIVE';
                    $studentBatch->start_date         = $studentStartDate;
                    $studentBatch->end_date           = $studentEndDate;
                    $studentBatch->number_of_sessions = $request->number_of_sessions;
                    $studentBatch->save();
                }
            }
        }
        // }

        $removeStudentIds = explode(',', $request->removed_student_ids);
        if (count($removeStudentIds) > 0) {
            $removeStudentBatches = StudentBatch::where('batch_id', $batchId)->whereIn('student_id', $removeStudentIds)->where('status', 'ACTIVE')->get();
            foreach ($removeStudentBatches as $removeStudentBatch) {
                $removeStudentBatch->status   = 'INACTIVE';
                $removeStudentBatch->end_date = Carbon::today();
                $removeStudentBatch->end_time = Carbon::now()->format('H:i:s');
                $removeStudentBatch->save();
            }
        }

        // $active_new_student_from_batch = StudentBatch::where('batch_id', $batchId)->where('status', 'ACTIVE')->first();

        if ($request->confirm_reassign === 'CANCEL') {
            $batch = Batch::find($request->batch_id);
            if ($batch && $batch->confirm_reassign_batch_id) {
                $oldBatchId = $batch->confirm_reassign_batch_id;

                // Update old batch status to INACTIVE
                Batch::where('id', $oldBatchId)->update([
                    'status' => 'INACTIVE',
                ]);

                // Update old batch schedules to INACTIVE
                BatchSchedule::where('batch_id', $oldBatchId)->update([
                    'status' => 'INACTIVE',
                ]);

                // Update old batch students to INACTIVE
                StudentBatch::where('batch_id', $oldBatchId)->update([
                    'status' => 'INACTIVE',
                ]);

                // Update old batch to make confirm_reassign and confirm_reassign_batch_id null
                Batch::where('id', $oldBatchId)->update([
                    'confirm_reassign'          => null,
                    'confirm_reassign_batch_id' => null,
                ]);
            }
        }

        $confirmReassign = $request->confirm_reassign === 'CANCEL' ? null : $request->confirm_reassign;
        Batch::where('id', $request->batch_id)->update([
            'start_date'                => $request->start_date,
            'end_date'                  => $request->end_date,
            'number_of_sessions'        => $request->number_of_sessions,
            'level_id'                  => $request->level_id,
            'status'                    => 'ACTIVE',
            'confirm_reassign'          => $confirmReassign,
            'confirm_reassign_batch_id' => $confirmReassign === null ? null : DB::raw('confirm_reassign_batch_id'),
            'created_by'                => Auth::id(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Students have been successfully assigned/updated to the batch.',
        ]);
    }

    public function reassignBatchToStudentModal(Batch $batch, Request $request)
    {
        //dd($request->all());
        $batchSchedules = $batch->batchSchedules;
        $studentBatches = $batch->studentBatches()->where('status', 'ACTIVE')->get();
        return view('Admin.Batchs.reassignbatch', compact('batch', 'batchSchedules', 'studentBatches'));

    }

    public function saveReassignedStudent(Request $request)
    {
        $oldBatchId        = $request->batch_id;
        $existingSchedules = BatchSchedule::where('batch_id', $oldBatchId)->get();
        $oldBatch          = Batch::findOrFail($oldBatchId);
        $coach             = $oldBatch->coach;

        $newBatchData                              = $oldBatch->toArray();
        $newBatchData['version']                   = $oldBatch->version + 1;
        $newBatchData['parent_id']                 = $oldBatch->parent_id ?? $oldBatchId;
        $newBatchData['confirm_reassign']          = 'CANCEL';
        $newBatchData['confirm_reassign_batch_id'] = $oldBatchId; // Store the old batch ID
        $newBatchData['created_by']                = Auth::id();
        $newBatch                                  = Batch::create($newBatchData);

        if (!empty($coach->zoom_id) && !empty($coach->zoom_api_key) && !empty($coach->zoom_client_secret) && !empty($coach->zoom_user_id)) {
            $zoomMeetingService = new ZoomMeetingService(
                $coach->zoom_api_key,
                $coach->zoom_client_secret,
                $coach->zoom_id
            );

            $meetingData = [
                'title' => 'Batch - ' . ($coach->user->first_name ?? 'Coach') . ' - ' . $newBatch->name,
                'duration_in_minute' => 40,
                'start_date_time' => now()->addMinutes(5)->toIso8601String(),
            ];

            $zoomResponse = $zoomMeetingService->createNewUserMeeting($meetingData, $coach->zoom_user_id);

            $newBatch->start_url         = $zoomResponse['start_url'] ?? '';
            $newBatch->join_url          = $zoomResponse['join_url'] ?? '';
            $newBatch->zoom_meeting_id   = $zoomResponse['id'] ?? null;
            $newBatch->zoom_meeting_uuid = $zoomResponse['uuid'] ?? null;
            $newBatch->save();
        }


        // Reassign students to the new batch
        $studentBatches = StudentBatch::where('batch_id', $oldBatchId)
            ->where('status', 'ACTIVE')
            ->get();

        foreach ($studentBatches as $studentBatch) {
            StudentBatch::create([
                'student_id'         => $studentBatch->student_id,
                'batch_id'           => $newBatch->id,
                'coach_id'           => $studentBatch->coach_id,
                'level_id'           => $studentBatch->level_id,           // Ensure correct level_id
                'number_of_sessions' => $studentBatch->number_of_sessions, // Use the same number of sessions
                'status'             => 'ACTIVE',
                'start_date'         => $studentBatch->start_date, // Ensure correct start_date
                'end_date'           => $studentBatch->end_date,   // Ensure correct end_date
                'confirm_reassign'   => $newBatchData['confirm_reassign'],
                'created_by'         => $newBatchData['created_by'],
            ]);
        }

        // Clone the batch schedules
        foreach ($existingSchedules as $schedule) {
            BatchSchedule::create([
                'batch_id'         => $newBatch->id,
                'weekday'          => $schedule->weekday,
                'from_time'        => $schedule->from_time,
                'to_time'          => $schedule->to_time,
                'status'           => 'ACTIVE',
                'start_date'       => $schedule->start_date,
                'end_date'         => $schedule->end_date,
                'confirm_reassign' => $newBatchData['confirm_reassign'],
                'created_by'       => $newBatchData['created_by'],
            ]);
        }

        // Redirect to the assign student route
        return redirect()->route('admin.batchs.assign.student', ['batch' => $newBatch->id]);
    }

    public function changeStatus(Request $request)
    {
        //dd($request->all());
        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        $coachId = null;
        if (in_array("Coach", $role) && $user->coach) {
            $coachId = $user->coach->id;
        }
        $isCoach = in_array("Coach", $role);
        // Check if the user has the role 'Coach'
        if ($isCoach) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You do not have permission to change the status.',
            ], 403);
        }

        $batch         = Batch::find($request->batch_id);
        if (!$batch) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Batch not found.',
            ], 404);
        }

        if ($batch->status === 'UPCOMING' && in_array($request->status, ['ACTIVE', 'STANDBY'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Upcoming batch can become active only after assigning students.',
            ], 422);
        }

        $batch->status = $request->status;
        $batch->save();

        // If the batch status is INACTIVE, update the status of all students in this batch
        if ($request->status === 'INACTIVE') {
            // StudentBatch::where('batch_id', $request->batch_id)
            //     ->update(['status' => 'INACTIVE']);

            $studentBatches = StudentBatch::where('batch_id', $request->batch_id)->where('status', 'ACTIVE')->get();
            foreach ($studentBatches as $studentBatch) {
                $studentBatch->status   = 'INACTIVE';
                $studentBatch->end_date = Carbon::today();
                $studentBatch->save();
            }

        }

        return response()->json([
            'status'  => 'success',
            'message' => $batch->name . ' has been marked ' . $batch->status . ' successfully',
            'batch'   => $batch,
        ], 201);
    }

    public function destroy(Request $request, Batch $batch)
    {
        //dd($request->all());
        $batch = Batch::where('id', $request->batch_id)->first();
        if ($batch) {
            $batch->delete();
            return response()->json([
                'success' => 'batch Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'batch not found',
            ], 404);
        }
    }

    public function checkName(Request $request)
    {
        $name = $request->name;
        $id   = $request->id;
        if ($id) {
            $batch = Batch::where('name', $name)->where('id', '!=', $id)->where('status','!=','INACTIVE')->first();
        } else {
            $batch = Batch::where('name', $name)->where('status','!=','INACTIVE')->first();
        }
        if ($batch) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The name has already been taken.',
            ], 422);
        }
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function batchAttendance(Request $request){
        $coach_attendances = CoachAttendance::where('batch_id', $request->id)->where('status','COMPLETED')->get();
        return view('Admin.Batchs.batchAttendance',compact('coach_attendances'));
    }
    public function checkSchedule(Request $request, CoachAvailabilityService $availability)
    {
        if ($request->has('country') && is_array($request->input('weekday'))) {
            $schedules = $availability->schedulesFromRequest(
                $request->input('weekday', []),
                $request->input('from_time', []),
                $request->input('to_time', [])
            );

            if (empty($request->input('country', [])) || empty($schedules)) {
                return response()->json([
                    'status' => 'success',
                    'coaches' => [],
                ]);
            }

            $availableCoaches = $availability->availableCoachesForRawBatch(
                $request->input('country', []),
                $schedules,
                $request->input('batch_id')
            );

            if ($request->filled('batch_id')) {
                $batch = Batch::find($request->input('batch_id'));
                if ($batch && $batch->coach_id && !$availableCoaches->contains('id', $batch->coach_id)) {
                    $currentCoach = Coach::with('user')->find($batch->coach_id);
                    if ($currentCoach) {
                        $availableCoaches->prepend($currentCoach);
                    }
                }
            }

            $coaches = $availableCoaches->map(function ($coach) {
                return [
                    'id' => $coach->id,
                    'name' => trim(optional($coach->user)->first_name . ' ' . optional($coach->user)->last_name),
                ];
            })->values();

            return response()->json([
                'status' => 'success',
                'coaches' => $coaches,
            ]);
        }

        $coach_id = $request->coach_id;
        $schedules = [[
            'weekday' => $request->weekday,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
        ]];

        $coachValidation = $availability->validateRawBatchCoach((int) $coach_id, $request->input('country', []), $schedules, $request->input('batch_id'));

        if (!$coachValidation['ok']) {
            return response()->json([
                'status'  => 'error',
                'message' => $coachValidation['message'],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    private $rules = [
        'coach_id' => 'required',
        'country'  => 'required',
    ];

    private $customMessages = [
        'name.required'     => 'The name field is required.',
        'name.unique'       => 'The name has already been taken.',
        'coach_id.required' => 'The coach ID field is required.',
        'country.required'  => 'The country field is required.',
    ];

}
