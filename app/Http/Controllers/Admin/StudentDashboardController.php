<?php
namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Holiday;
use App\Models\Student;
use App\Models\DemoLead;
use App\Models\Leveltest;
use App\Models\Tournament;
use App\Models\Masterclass;
use App\Models\Coverupclass;
use App\Models\LeaveRequest;
use App\Models\Paymentlevel;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use App\Models\CoachAttendance;
use App\Models\DemoLeadEnquiry;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function markAttendance(Request $request)
    {
        $batchId = $request->input('id');
        $studentId = $request->input('student_id');

        $coachAttendance = CoachAttendance::where('batch_id', $batchId)
            ->where('date', Carbon::now()->toDateString())
            ->where('status','COMPLETED')
            ->first();
        // dd($coachAttendance);

        if ($coachAttendance) {
            $studentAttendance = StudentAttendance::where('student_id', $studentId)
                ->where('batch_id', $batchId)
                ->where('date', Carbon::now()->toDateString())
                ->first();
                // dd($studentAttendance);

            if ($studentAttendance) {
                // dd(33);
                $studentAttendance->status = 'PRESENT';
                $studentAttendance->time = Carbon::now()->toTimeString();
                $studentAttendance->save();
                return response()->json([
                    'message' => 'Attendance marked successfully',
                    'status' => 'success'
                ], 200);
            } else {
                // dd(22);
                $otherStudentAttendance = StudentAttendance::where('batch_id', $batchId)
                    ->where('date', Carbon::now()->toDateString())
                    ->first();
                // dd($otherStudentAttendance);

                if ($otherStudentAttendance) {
                    $studentAttendance = new StudentAttendance();
                    $studentAttendance->student_id = $studentId;
                    $studentAttendance->type = 'BATCH';
                    $studentAttendance->coach_id = $otherStudentAttendance->coach_id;
                    $studentAttendance->batch_id = $batchId;
                    $studentAttendance->level_id = $otherStudentAttendance->level_id;
                    $studentAttendance->date = Carbon::now()->toDateString();
                    $studentAttendance->time = Carbon::now()->toTimeString();
                    $studentAttendance->remark = '';
                    $studentAttendance->number_of_batch_sessions = $otherStudentAttendance->number_of_batch_sessions;
                    $studentAttendance->homework_link = $otherStudentAttendance->homework_link;
                    $studentAttendance->recording_link = $otherStudentAttendance->recording_link;
                    $studentAttendance->chapter_name = $otherStudentAttendance->chapter_name;
                    $studentAttendance->status = 'PRESENT';
                    $studentAttendance->save();
                    return response()->json([
                        'message' => 'Attendance marked successfully',
                        'status' => 'success'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Class not started yet please try again later',
                        'status' => 'error'
                    ], 404);
                }
            }
        } else {
            return response()->json([
                'message' => 'Class not started yet please try again later',
                'status' => 'error'
            ], 404);    
        }
      
    }

    /**
     * Mark attendance and redirect to join URL (works without JavaScript - mobile/tablet fallback).
     */
    public function joinClass(Request $request)
    {
        $batchId = $request->input('id');
        $studentId = $request->input('student_id');
        $joinUrl = $request->input('join_url');

        if (!$batchId || !$studentId || !$joinUrl) {
            return redirect()->back()->with('error', 'Invalid join link.');
        }

        $coachAttendance = CoachAttendance::where('batch_id', $batchId)
            ->where('date', Carbon::now()->toDateString())
            ->first();

        if ($coachAttendance) {
            $studentAttendance = StudentAttendance::where('student_id', $studentId)
                ->where('batch_id', $batchId)
                ->where('date', Carbon::now()->toDateString())
                ->first();

            if ($studentAttendance) {
                $studentAttendance->status = 'PRESENT';
                $studentAttendance->time = Carbon::now()->toTimeString();
                $studentAttendance->save();
            } else {
                $otherStudentAttendance = StudentAttendance::where('batch_id', $batchId)
                    ->where('date', Carbon::now()->toDateString())
                    ->first();

                if ($otherStudentAttendance) {
                    $studentAttendance = new StudentAttendance();
                    $studentAttendance->student_id = $studentId;
                    $studentAttendance->coach_id = $coachAttendance->coach_id;
                    $studentAttendance->batch_id = $batchId;
                    $studentAttendance->level_id = $coachAttendance->level_id;
                    $studentAttendance->date = Carbon::now()->toDateString();
                    $studentAttendance->time = Carbon::now()->toTimeString();
                    $studentAttendance->status = 'PRESENT';
                    $studentAttendance->save();
                }
            }
        }

        return redirect()->away($joinUrl);
    }

    public function studentDashboard()
    {

        $user = Auth::user();

        // dd($user);

        $roles = $user->roles->pluck('name')->first();

        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        // dd($permissions);
        $coverup_class = '';

        $data = [
            'allBatches'           => 0,
            'activeBatches'        => 0,
            'inactiveBatches'      => 0,
            'studentFees'          => [],
            'demoLead'             => [],
            'masterclassedata'     => [],
            'tournamentData'       => [],
            'upcomingSchedules'    => [],
            'nextUpcomingSchedule' => null,
            'demoLeadEnquiry'      => '',
            'studentbatch'         => '',
            'student'              => '',
            'coaches'              => '',
            'studentattendance'    => '',
            'batchschedule'        => '',
            'currentDate'          => '',
            'currentWeekday'       => '',
            'allStudentBatches'    => '',
        ];
        $country = '';

        $holidays = collect();

        $user               = Auth::user();
        $roleName           = $user->roles->pluck('name')->first();
        $student            = Student::where('user_id', $user->id)->first();
        $masterclassedata   = '';
        $tournamentData     = '';
        $firstMatchingClass = null;
        $upcomingClasses    = [];
        $is_fees_due        = 0;

        if ($student) {
            if ($student->status == 'FEESDUE') {
                $is_fees_due = 1;
            }

            $student      = $student;
            $studentbatch = StudentBatch::where('student_id', $student->id)
                ->whereHas('batch', function ($query) {
                    $query->where('status', 'ACTIVE');
                })->whereHas('student', function ($query) {
                $query->where('status', 'ACTIVE');
            })->where('status', 'ACTIVE')
                ->first();

            if (! empty($studentbatch)) {
                $batchSchedules = BatchSchedule::where('batch_id', $studentbatch->batch_id)
                    ->orderBy('weekday', 'ASC')
                    ->where('status', 'ACTIVE')
                    ->get();

                $currentDate    = Carbon::now()->startOfDay();
                $currentWeekday = $currentDate->format('l'); // e.g., "Monday"

                // Map weekdays to numbers
                $all_day = [
                    'Monday'    => 1,
                    'Tuesday'   => 2,
                    'Wednesday' => 3,
                    'Thursday'  => 4,
                    'Friday'    => 5,
                    'Saturday'  => 6,
                    'Sunday'    => 7,
                ];

                $currentWeekdayNumber = $all_day[$currentWeekday]; // Convert current weekday to a number

                // Find the next upcoming day
                $firstMatchingClass = null;
                $minDayDifference   = PHP_INT_MAX; // Start with a large number
                foreach ($batchSchedules as $schedule) {
                    if (isset($schedule->weekday)) {
                        $scheduleDayNumber = $all_day[$schedule->weekday]; // Convert schedule weekday to a number
                        $dayDifference     = $scheduleDayNumber - $currentWeekdayNumber;

                        // If the day difference is negative, add 7 to roll over to the next week
                        if ($dayDifference < 0) {
                            $dayDifference += 7;
                        }

                        // Check if this is the closest upcoming day
                        if ($dayDifference < $minDayDifference) {
                            $minDayDifference   = $dayDifference;
                            $firstMatchingClass = $schedule;
                        }
                    }
                }

            }

            $allStudentBatches       = $student->studentBatches;
            $data['allBatches']      = $allStudentBatches->count();
            $data['activeBatches']   = $allStudentBatches->where('status', 'ACTIVE')->count();
            $data['inactiveBatches'] = $allStudentBatches->where('status', 'INACTIVE')->count();

            $country = $student->country;

            $latestStduentBatch = $student->studentBatches()->orderBy('id', 'desc')->first();

            if ($latestStduentBatch) {
                $latestBatch        = Batch::where('id', $latestStduentBatch->batch_id)->first();
                $latestBatchLevel   = $latestBatch->level->name;
                $latestBatchLevelId = $latestBatch->level->id;

            }
            // dd($latestBatchLevel, $latestBatch, $latestStduentBatch);

            $country     = $student->country;
            $recentBatch = $student->studentBatches()
                ->whereHas('student', function ($query) {
                    $query->where('status', '!=', 'INACTIVE');
                })
                ->orderBy('id', 'desc')
                ->first();

            $batchId = $recentBatch ? [$recentBatch->batch_id] : [];
            $levelId = $recentBatch ? [$recentBatch->batch->level_id] : [];

            $batchId   = array_map('strval', $batchId);
            $levelId   = array_map('strval', $levelId);
            $studentId = (string) $student->id;

            $currentDate = Carbon::now()->toDateString();
            $currentTime = Carbon::now();

            $batchId = $recentBatch ? [$recentBatch->batch_id] : [];
            $levelId = $recentBatch ? [$recentBatch->batch->level_id] : [];

            $batchId   = array_map('strval', $batchId); // Convert to strings
            $levelId   = array_map('strval', $levelId);
            $studentId = (string) $student->id;

            $currentDate = Carbon::now()->toDateString();
            $currentTime = Carbon::now();

            $tournamentData = Tournament::where('status', 'ACTIVE')
                ->whereDate('date', '>=', $currentDate)
                ->whereJsonContains('country', $country)
                ->whereJsonContains('level_ids', $levelId) // 🔥 Filter by level ID!
                ->orderBy('date', 'asc')
                ->take(3)
                ->get();

            $masterclassedata = Masterclass::where('status', 'ACTIVE')
                ->whereDate('date', '>=', $currentDate)
                ->whereJsonContains('country', $country)
                ->whereJsonContains('level_ids', $levelId) // 🔥 Filter by level ID!
                ->orderBy('date', 'asc')
                ->take(3)
                ->get();

            // dd($levelId, $masterclassedata->map->getAttributes());

        }

        $data['coaches']         = Coach::all();
        $data['demoLeadEnquiry'] = DemoLeadEnquiry::where('user_id', $user->id)->first();

        // dd($data['demoLeadEnquiry']);
        if (! empty($data['demoLeadEnquiry'])) {
            // dd($data['demoLeadEnquiry']);

            // $data['demoLeadEnquiry'] = DemoLeadEnquiry::where('mobile', $user->mobile)->first();

            $country = $data['demoLeadEnquiry']->country;
            // dd($country);
        }
        // dd($data['demoLeadEnquiry']->country);
        $data['demoLead'] = DemoLead::where('user_id', $user->id)->first();

        // $levelTest = null;

        if (!empty($data['demoLead'])) {
            $demoLeadId = $data['demoLead']->id;

            // $levelTest = Leveltest::where('status', 'ACTIVE')
            //     // ->whereJsonContains('demolead_ids', $demoLeadId)
            //     ->orderBy('id', 'desc')
            //     ->first();
        }

        // dd($data);
        if (! empty($data['demoLead']) && ! empty($data['demoLeadEnquiry'])) {
            // dd($data['demoLead']);
            // $data['demoLead'] = DemoLead::where('mobile', $user->mobile)->first();
            // dd($data['demoLead']);
            $country = $data['demoLead']->country;
        }

        // dd(22);

        $todayDate = Carbon::now()->toDateString();

        $holidays = Holiday::where('status', 'ACTIVE')
            ->whereJsonContains('country', $country)
            ->where(function ($query) use ($todayDate) {
                $query->where(function ($q) use ($todayDate) {
                    $q->whereNotNull('end_date')
                        ->whereDate('end_date', '>=', $todayDate);
                })->orWhere(function ($q) use ($todayDate) {
                    $q->whereDate('start_date', '>=', $todayDate);
                });
            })
            ->get();

        // if ($firstMatchingClass) {
        //     $today                      = Carbon::now();
        //     $upcomingClassBatch         = Batch::find($firstMatchingClass->batch_id);
        //     $upcomingClassCoach         = Coach::find($upcomingClassBatch->coach_id);
        //     $firstMatchingClassFromTime = Carbon::parse($firstMatchingClass->from_time);
        //     $firstMatchingClassFromTime = $firstMatchingClassFromTime->format('H:i:s');

        //     $upcomingClassDate = '';

        //     if ($today->format('l') == $firstMatchingClass->weekday) {
        //         $weekdayDate = $today;
        //     } else {
        //         $weekdayDate = $today->copy()->next($firstMatchingClass->weekday);
        //         if ($weekdayDate->isBefore($today)) {
        //             $weekdayDate = $today->copy()->previous($firstMatchingClass->weekday);
        //         }
        //     }

        //     $upcomingClassDate = $weekdayDate->format('Y-m-d');

        //     $coachLeave = LeaveRequest::where('coach_id', $upcomingClassCoach->id)
        //         ->whereDate('from_date', '=', $upcomingClassDate)
        //         ->where('status', 'APPROVED')
        //         ->first();
        //     if ($coachLeave) {
        //         $fromLeaveTime = $coachLeave->from_time;
        //         $toLeaveTime   = $coachLeave->to_time;

        //         $is_coverup_class = 0;

        //         $coverup_class = Coverupclass::where('old_coach_id', $upcomingClassBatch->coach_id)
        //             ->where('date', $upcomingClassDate)
        //             ->where('batch_id', $upcomingClassBatch->id)
        //             ->where('batchschedule_id', $firstMatchingClass->id)
        //             ->first();

        //         if ($firstMatchingClassFromTime >= $fromLeaveTime && $firstMatchingClassFromTime <= $toLeaveTime && ! $coverup_class) {
        //             $firstMatchingClass = null;
        //         }
        //     }
        // }
        if ($firstMatchingClass) {
            $today                      = Carbon::now();
            $upcomingClassBatch         = Batch::find($firstMatchingClass->batch_id);
            $upcomingClassCoach         = Coach::find($upcomingClassBatch->coach_id);
            $firstMatchingClassFromTime = Carbon::parse($firstMatchingClass->from_time)->format('H:i:s');

            // Determine upcoming class date based on weekday
            $upcomingClassDate = '';
            if ($today->format('l') == $firstMatchingClass->weekday) {
                $weekdayDate = $today;
            } else {
                $weekdayDate = $today->copy()->next($firstMatchingClass->weekday);
                if ($weekdayDate->isBefore($today)) {
                    $weekdayDate = $today->copy()->previous($firstMatchingClass->weekday);
                }
            }

            $upcomingClassDate = $weekdayDate->format('Y-m-d');

            // ✅ Always check if a coverup exists for today
            $existingCoverup = Coverupclass::where('batch_id', $upcomingClassBatch->id)
                ->where('batchschedule_id', $firstMatchingClass->id)
                ->where('date', $upcomingClassDate)
                ->first();
            if ($existingCoverup) {
                $coverup_class = $existingCoverup;
            }

            // ✅ If leave exists, and no coverup, then invalidate class
            $coachLeave = LeaveRequest::where('coach_id', $upcomingClassCoach->id)
                ->whereDate('from_date', '=', $upcomingClassDate)
                ->where('status', 'APPROVED')
                ->first();

            if ($coachLeave && !$existingCoverup) {
                $fromLeaveTime = $coachLeave->from_time;
                $toLeaveTime   = $coachLeave->to_time;

                if ($firstMatchingClassFromTime >= $fromLeaveTime && $firstMatchingClassFromTime <= $toLeaveTime) {
                    $firstMatchingClass = null;
                }
            }
        }

        // dd($student);

        return view('Admin.StudentDashboard.dashboard', compact('data', 'student', 'firstMatchingClass', 'tournamentData', 'masterclassedata', 'holidays', 'is_fees_due', 'coverup_class'));
    }

    public function studentRecordings()
    {
        $studentattendance = '';
        $user              = Auth::user();
        $student           = Student::where('user_id', $user->id)->first();
        // CoachAttendance
        if (isset($student) && ! empty($student)) {
            $studentattendance = StudentAttendance::where('student_id', $student->id)->orderBy('date', 'desc')->get();
        }
        return view('Admin.StudentDashboard.recordings', compact('studentattendance', 'student'));
    }

    public function studentHomework()
    {
        $studentattendance = '';
        $user              = Auth::user();
        $student           = Student::where('user_id', $user->id)->first();
        // dd($student);
        if (isset($student) && !empty($student)) {
            $studentattendance = StudentAttendance::where('student_id', $student->id)->orderBy('date', 'desc')->get();
        }
        return view('Admin.StudentDashboard.homework', compact('studentattendance', 'student'));
    }

    public function studentBatches()
    {
        $user     = Auth::user();
        $roleName = $user->roles->pluck('name')->first();
        $student  = Student::where('user_id', $user->id)->first();
        $data     = [
            'student'            => '',
            'studentFees'        => [],
            'studentStatuses'    => [],
            'studentBatches'     => [],
            'studentAttendances' => [],
        ];

        if (isset($student) && ! empty($student)) {
            $studentFees     = $student->studentFees;
            $studentStatuses = $student->studentStatuses;

            $uniqueBatchIds = StudentBatch::where('student_id', $student->id)
                ->select('batch_id')         // Select batch_id for grouping
                ->groupBy('batch_id')        // Group by batch_id for uniqueness
                ->orderByRaw('MAX(id) DESC') // Order by the latest id for each batch
                ->pluck('batch_id');         // Get the unique batch IDs
                                         // dd($student->id);

            $studentBatches = Batch::whereIn('id', $uniqueBatchIds)
                ->orderBy('start_date', 'asc') // Order the batches by start_date
                ->get();

            $studentAttendances = $student->studentAttendances()
                ->with(['coach', 'batch', 'level'])
                ->where('status', '!=', 'NOTMARKED')
                ->get();

            // Format the student attendance data
            $formattedStudentAttendanceData = $studentAttendances->map(function ($attendance) {
                $student = $attendance->student;
                $coach   = $attendance->coach;
                $batch   = $attendance->batch;
                $level   = $attendance->level;

                return [
                    'id'         => $attendance->id,
                    'student_id' => $attendance->student->id,
                    'student'    => [
                        'full_name' => $student ? $student->first_name . ' ' . $student->last_name : 'N/A',
                        'phone'     => $student ? $student->mobile : 'N/A',
                    ],
                    'coach'      => [
                        'id'   => $coach ? $coach->id : 'N/A',
                        'name' => $coach ? $coach->user->first_name . ' ' . $coach->user->last_name : 'N/A',
                    ],
                    'batch'      => [
                        'id'   => $batch ? $batch->id : 'N/A',
                        'name' => $batch ? $batch->name : 'N/A',
                    ],
                    'date'       => Carbon::parse($attendance->date)->format('j, M Y'),
                    'time'       => Carbon::parse($attendance->time)->format('g:i A'),
                    'status'     => $attendance->status,
                ];
            });

            // Prepare the data to be passed to the view
            $data = [
                'student'            => $student,
                'studentFees'        => $studentFees,
                'studentStatuses'    => $studentStatuses,
                'studentBatches'     => $studentBatches,
                'studentAttendances' => $formattedStudentAttendanceData,
            ];
        }

        return view('Admin.StudentBatches.index', $data);
    }

 
    public function studentFees()
    {
        $user     = Auth::user();
        $roleName = $user->roles->pluck('name')->first();

        $student = Student::where('user_id', $user->id)->first(); // removed INDIA filter

        $data = [
            'student'                => $student,
            'studentFees'            => [],
            'nextPaymentLevel'       => null,
            'nextThreePaymentLevels' => [],
        ];
        

        if (isset($student) && !empty($student)) {
            $studentFees = $student->studentFees;

            $data['studentFees'] = $studentFees;

            // Apply next level logic only for Indian students
            // if ($student->country != 'QATAR' && $student->country != 'SINGAPORE') {
                $student_last_batch = StudentBatch::where('student_id', $student->id)->orderBy('id', 'desc')->first();
                
                if ($student_last_batch) {
                    $lastpayment_level = Paymentlevel::where('level_id', $student_last_batch->batch->level_id)->where('status', 'ACTIVE')->first();

                    // If current batch is not active, move to next sequence
                    if ($student_last_batch->batch->status != 'ACTIVE') {
                        $lastpayment_level = Paymentlevel::where('sequence', $lastpayment_level->sequence + 1)->where('status', 'ACTIVE')->first();
                    }

                    if ($student->status == 'FEESDUE') {
                        if ($lastpayment_level) {
                            $nextPaymentLevel = Paymentlevel::where('sequence', $lastpayment_level->sequence)->where('status', 'ACTIVE')->first();

                            $nextThreePaymentLevels = Paymentlevel::where('sequence', '>=', $lastpayment_level->sequence)
                                ->orderBy('sequence', 'asc')
                                ->where('status', 'ACTIVE')
                                ->limit(3)
                                ->get();
                        } else {
                            $nextPaymentLevel = Paymentlevel::first();

                            $nextThreePaymentLevels = Paymentlevel::where('sequence', '>=', $nextPaymentLevel->sequence)
                                ->orderBy('sequence', 'asc')
                                ->where('status', 'ACTIVE')
                                ->limit(3)
                                ->get();
                        }

                        $data['nextPaymentLevel'] = $nextPaymentLevel;
                        $data['nextThreePaymentLevels'] = $nextThreePaymentLevels;
                    }
                } 
            // }
        }
        
        return view('Admin.StudentDashboard.fees', $data);
    }

    public function studentCertificates()
    {
        $user     = Auth::user();
        $roleName = $user->roles->pluck('name')->first();
        $student  = Student::where('user_id', $user->id)->first();

        $level_1  = false;
        $level_2  = false;
        $level_3  = false;
        $level_4  = false;
        $level_5  = false;
        $level_6  = false;
        

        $first_level_arr  = ['Beginner', '1'];

        $second_level_arr = ['Intermediate', '5'];

        $third_level_arr = ['AL-1', '10'];

        $fourth_level_arr  = ['AL-2', '19'];

        $fifth_level_arr  = ['Expert-1 (Module-1)', '16'];

        $sixth_level_arr  = ['Expert-1 (Module-2)', '17'];

        $seventh_level_arr  = ['Expert-1 (Module-3)', '18'];



        $levelIds = $student->studentBatches()
            ->orderBy('id', 'desc')
            ->pluck('level_id')
            ->unique()
            ->map(fn($v) => (int) $v)  // ensure integers
            ->all();

        $level_1 = in_array(1,  $levelIds, true);  // Beginner
        $level_1 = in_array(2,  $levelIds, true);  // Beginner
        $level_2 = in_array(5,  $levelIds, true);  // Intermediate
        $level_3 = in_array(10, $levelIds, true);  // AL-1
        $level_4 = in_array(19, $levelIds, true);  // AL-2
        $level_5 = in_array(16, $levelIds, true);  // Expert-1 (Module-1)
        $level_6 = in_array(17, $levelIds, true);  // Expert-1 (Module-2)
        $level_7 = in_array(18, $levelIds, true);  // Expert-1 (Module-3)

        

        $certificatesLevel = [
            'level_1' => $level_1,
            'level_2' => $level_2,
            'level_3' => $level_3,
            'level_4' => $level_4,
            'level_5' => $level_5,
            'level_6' => $level_6,
        ];

        // dd($certificatesLevel);
        return view('Admin.StudentDashboard.certificates', compact('student', 'certificatesLevel'));
    }
    public function studentCertificatesPdf(Request $request, Student $student)
    {

        $data              = [];
        $data['full_name'] = isset($student->full_name) ? $student->full_name : 'Not Found';
        $data['level']     = $request->level;
        $data['studentId'] = $student->student_id;

        $pdf = PDF::loadView('Admin.StudentDashboard.Form-Sections.student-certificates-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        // Stream the PDF without the first page (ensure the view is correctly formatted)
        $dompdf = $pdf->getDomPDF();
        $dompdf->render();
        return $pdf->stream($data['studentId'] . '.pdf');
    }

    public function studentTournaments()
    {

        $user = Auth::user();

        $userroles = $user->roles->pluck('name')->first();
        $user      = Auth::user();
        $student   = Student::where('user_id', $user->id)->first();
        // dd($student);
        if (empty($student)) {
            $tournamentDatas = [];
            return view('Admin.StudentDashboard.tournaments', compact('tournamentDatas', 'student'));
        }

        $country     = $student->country;
        $recentBatch = $student->studentBatches()
            // ->whereHas('student', function ($query) {
            //     $query->where('status', '!=', 'INACTIVE');
            // })
            ->orderBy('id', 'desc')
            ->first();

        $batchId = $recentBatch ? [$recentBatch->batch_id] : [];
        $levelId = $recentBatch ? [$recentBatch->batch->level_id] : [];

        $batchId   = array_map('strval', $batchId);
        $levelId   = array_map('strval', $levelId);
        $studentId = (string) $student->id;

        // dd($recentBatch);
        if (! $recentBatch) {
            $tournamentDatas = Tournament::where('status', 'ACTIVE')
                ->whereJsonContains('country', $country)
                ->whereJsonContains('student_ids', $studentId)
                                      // ->whereDate('date', '<=', now()) // Include today’s tournaments
                ->orderBy('date', 'desc') // Sort from latest to past
                                      // ->take(1) // Get only the latest (next slot)
                ->get();
            // dd($tournamentDatas);

            return view('Admin.StudentDashboard.tournaments', compact('tournamentDatas', 'student'));
        }

        $tournamentDatas = Tournament::where('status', 'ACTIVE')
            ->whereJsonContains('country', $country)
            ->where(function ($query) use ($batchId, $levelId, $studentId) {
                $query->whereJsonContains('batch_ids', $batchId)
                    ->orWhereJsonContains('level_ids', $levelId)
                    ->orWhereJsonContains('student_ids', $studentId);
            })
        // ->whereDate('date', '<=', now()) // Include today’s tournaments
            ->orderBy('date', 'desc')
        // ->take(1) // Get only the latest (next slot)
            ->get();

        return view('Admin.StudentDashboard.tournaments', compact('tournamentDatas', 'student'));
    }
    public function studentMasterclasses()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        // dd($student);
        if (empty($student)) {
            $masterclassDatas = [];
            return view('Admin.StudentDashboard.masterclass', compact('masterclassDatas', 'student'));
        }

        $country     = $student->country;
        // dd($country);
        $recentBatch = $student->studentBatches()
            ->whereHas('student', function ($query) {
                $query->where('status', '!=', 'INACTIVE');
            })
            ->orderBy('id', 'desc')
            ->first();
        // dd($recentBatch->level_id);

        $batchId = $recentBatch ? [$recentBatch->batch_id] : [];
        $levelId = $recentBatch ? [$recentBatch->batch->level_id] : [];
    // dd($recentBatch);
        $batchId   = array_map('strval', $batchId);
        $levelId   = array_map('strval', $levelId);
        $studentId = (string) $student->id;
        // dd($recentBatch);
        if (! $recentBatch) {
            $masterclassDatas = Masterclass::where('status', 'ACTIVE')
                ->whereJsonContains('country', $country)
                ->whereJsonContains('student_ids', $studentId)
                ->orderBy('date', 'desc')
                ->get();
            return view('Admin.StudentDashboard.masterclass', compact('masterclassDatas', 'student'));
        }
        // dd($batchId, $levelId, $studentId);
        $masterclassDatas = Masterclass::where('status', 'ACTIVE')
            ->whereJsonContains('country', $country)
            ->where(function ($query) use ($batchId, $levelId, $studentId) {
                $query->whereJsonContains('batch_ids', $batchId)
                    ->orWhereJsonContains('level_ids', $levelId)
                    ->orWhereJsonContains('student_ids', $studentId);
            })
            ->orderBy('date', 'desc')
            ->get();
        // dd($masterclassDatas);

        return view('Admin.StudentDashboard.masterclass', compact('masterclassDatas', 'student'));
    }

    public function tournamentCertificate(Tournament $tournament)
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (! empty($tournament->certificate)) {
            $certificatePath = storage_path('app/public/' . $tournament->certificate['path']);
            if (! file_exists($certificatePath)) {
                return response()->json(['message' => 'Certificate file not found'], 404);
            }
            $studentName = '';
            if (! empty($student->first_name)) {
                $studentName .= $student->first_name;
            }
            if (! empty($student->last_name)) {
                $studentName .= ' ' . $student->last_name;
            }
            $data = [
                'certificate_name' => $tournament->name ?? 'Certificate',
                'certificateUrl'   => $certificatePath,
                'studentName'      => $studentName,
            ];

            // Generate the PDF
            $pdf = PDF::loadView('Admin.StudentDashboard.Form-Sections.tournaments-certificate', $data);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream($data['certificate_name'] . '.pdf');
        } else {
            return response()->json(['message' => 'Certificate not found'], 404);
        }

    }

}
