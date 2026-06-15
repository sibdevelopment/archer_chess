<?php
namespace App\Http\Controllers\Admin;

use PDF;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Level;
use App\Models\Student;
use App\Models\DemoLead;
use App\Models\StudentFee;
use App\Models\DemoSession;
use App\Models\Masterclass;
use App\Models\Coverupclass;
use App\Models\LeaveRequest;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use App\Mail\DemoCompleteMail;
use App\Models\CoachAttendance;
use App\Models\DelayedBatch;
use App\Models\CoachAvailability;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $coachId = null;
        if (in_array('Coach', $role) && $user->coach) {
            $coachId = $user->coach->id;
        }
        $isCoach = in_array('Coach', $role);

        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->toArray();
            $mergedCountries = collect($countries)
                ->map(fn($item) => json_decode($item, true))  
                ->flatten()  
                ->filter()  
                ->unique()  
                ->values() 
                ->toArray();
        }

        $nulltodaterequests = LeaveRequest::where('to_date', null)->get();
        foreach ($nulltodaterequests as $nulltodaterequest) {
            $nulltodaterequest->to_date = $nulltodaterequest->from_date;
            $nulltodaterequest->save();
        }

        $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $todayDate       = Carbon::now()->toDateString();
        $todayDayOfWeek  = Carbon::now()->format('l');
        $coaches         = Coach::where('status', 'ACTIVE')->get();
        if (! $user->roles()->where('name', 'SuperAdmin')->exists()) {
            if($isCoach){
                $coaches = $coaches->where('id', $coachId);
            }
            else{
                $coaches = $coaches->filter(function ($coach) use ($mergedCountries) {
                    $coachCountries = is_array($coach->country) ? $coach->country : explode(',', $coach->country);
                    return ! empty(array_intersect($coachCountries, $mergedCountries));
                });
            }
        }
        $user            = auth()->user();
        $coachId         = null;
        if ($user) {
            $coach = Coach::where('user_id', $user->id)->first();
            if ($coach) {$coachId = $coach->id;} else { $coach = Coach::where('status', 'ACTIVE')->first();
                if ($coach) {$coachId = $coach->id;}}
        }
        return view('Admin.CoachReports.index', compact('coaches', 'coachId', 'firstDayOfMonth', 'todayDate', 'todayDayOfWeek'));
    }

    public function getCounts(Request $request, $coachId)
    {
        // Extract startDate and endDate from the request
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');
        // If startDate or endDate is null, use the current month and year
        if (! $startDate || ! $endDate) {
            $currentDate = Carbon::now();
            $startDate   = $currentDate->startOfMonth()->toDateString();
            $endDate     = $currentDate->toDateString();
        }
        // Find the coach
        $coach = Coach::find($coachId);
        if (! $coach) {
            return response()->json(['error' => 'Coach not found'], 404);
        }
        \Log::debug('Coach ID:', ['coachId' => $coach->id]);

        // Fetch the counts based on the specified date range
        $completedDemosCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Demo')
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $approvedLeavesCount = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->whereBetween('from_date', [$startDate, $endDate])
            ->count();
        $completedBatchesCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
            // dd($coachId,$startDate,$endDate);

        $totalStudentsBatchesCount = StudentAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'NOTMARKED')
            ->where('status', '!=', 'CANCELLED')
            ->count();
            // dd( $totalStudentsBatchesCount);

        $masterclassCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Masterclass')
            ->where('masterclass_id', '!=', null)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $coverupclassCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'COVERUP')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $delayedBatchesCount = DelayedBatch::where('coach_id', $coachId)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        // Return the view with the calculated data
        return view('Admin.CoachReports.getcount', compact('completedDemosCount', 'completedBatchesCount', 'approvedLeavesCount', 'totalStudentsBatchesCount', 'coachId', 'startDate', 'endDate', 'masterclassCount', 'coverupclassCount', 'delayedBatchesCount'));
    }

    public function batchStudentCountryData(Request $request)
    {
        // dd(11);
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        // Validate the input dates
        if (! $startDate || ! $endDate) {
            return response()->json(['status' => 'error', 'message' => 'Invalid date range'], 400);
        }

        // Get the total count of students in batches for the given coachId and date range
        $totalStudentsBatchesCount = StudentAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'NOTMARKED')
            ->where('status', '!=', 'CANCELLED')
            ->count();

        // Get the student IDs from StudentAttendance for the given coachId and date range
        $studentIds = StudentAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'NOTMARKED')
            ->where('status', '!=', 'CANCELLED')
            ->pluck('student_id');

        // Count occurrences of each student ID
        $studentIdCounts = array_count_values($studentIds->toArray());



        if (!empty($studentIdCounts)) {
        // Get the count of students per country, considering multiple occurrences
        $studentsPerCountry = Student::whereIn('id', array_keys($studentIdCounts))
            ->select(\DB::raw('IFNULL(country, "Unknown") as country'), \DB::raw('SUM(CASE ' . implode(' ', array_map(function ($id, $count) {
                return "WHEN id = $id THEN $count";
            }, array_keys($studentIdCounts), $studentIdCounts)) . ' ELSE 0 END) as total'))
            ->groupBy('country')
            ->get();
        } else {
            $studentsPerCountry = collect();
        }

        // Get students who don't have a country specified
        $studentsWithoutCountry = Student::whereIn('id', $studentIds)
            ->whereNull('country')
            ->orWhere('country', '')
            ->get(['student_id', 'first_name']);

        // Fetch all student attendance data for the given coachId and date range
        $studentAttendanceData = StudentAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', '!=', 'NOTMARKED')
            ->where('status', '!=', 'CANCELLED')
            ->with(['student'])
            ->orderByDesc('id')
            ->get();

        // Format the student attendance data
        $formattedStudentAttendanceData = $studentAttendanceData->map(function ($attendance) {
            $student  = $attendance->student;
            $coach    = $attendance->coach;
            $demoLead = $attendance->demoLead;
            $batch    = $attendance->batch;
            $level    = $attendance->level;

            return [
                'id'         => $attendance->id,
                'student_id' => $attendance->student->student_id,
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

        // Prepare the data to be returned
        $data = [
            'status'                    => 'success',
            'message'                   => 'Data retrieved successfully',
            'totalStudentsBatchesCount' => $totalStudentsBatchesCount,
            'studentsPerCountry'        => $studentsPerCountry,
            'studentsWithoutCountry'    => $studentsWithoutCountry,
            'studentAttendanceData'     => $formattedStudentAttendanceData,
        ];

        // Return the data as JSON
        return response()->json($data);
    }

    public function demoCompletedData(Request $request)
    {
        // Extract parameters from the request
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        // Fetch the count of completed demos from CoachAttendance
        $completedDemosCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Demo')
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
        // dd($completedDemosCount);
        // Fetch the demolead IDs of completed demos
        $completedDemoLeadIds = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Demo')
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->pluck('demolead_id');

        $demoLeads = DemoLead::whereIn('id', $completedDemoLeadIds)->get();
    
        // Format the demo session data
        
        $demoData = $demoLeads->map(function ($demo) {
            $demolead  = $demo;
            $levelName = $demo->level ? $demo->level->name : 'N/A';

            // Split the slot time range and format each part
            $slotTimes     = explode(' - ', $demo->slot);
            $formattedSlot = count($slotTimes) === 2
            ? Carbon::parse($slotTimes[0])->format('g:i A') . ' - ' . Carbon::parse($slotTimes[1])->format('g:i A')
            : 'N/A';

            return [
                'id'                              => $demo->id,
                'first_name'                      => $demolead->first_name,
                'last_name'                       => $demolead->last_name,
                'age'                             => $demolead->age,
                'mobile'                          => $demolead->mobile,
                'city'                            => $demolead->city,
                'country'                         => $demolead->country,
                'kids_time_zone'                  => $demolead->kids_time_zone,
                'remark'                          => $demolead->remark,
                'status'                          => $demolead->status,
                'index'                           => $demolead->index,
                'date'                            => Carbon::parse($demolead->date)->format('j, M Y'),
                'time'                            => Carbon::parse($demolead->time)->format('g:i A'),
                'kids_date'                       => Carbon::parse($demolead->kids_date)->format('j, M Y'),
                'kids_time'                       => Carbon::parse($demolead->kids_time)->format('g:i A'),
                'created_by'                      => $demolead->created_by,
                'session_status'                  => $demo->status,
                'session_index'                   => $demo->index,
                'session_date'                    => Carbon::parse($demo->date)->format('j, M Y'),
                'session_time'                    => Carbon::parse($demo->time)->format('g:i A'),
                'session_slot'                    => $formattedSlot,
                'session_level_name'              => $levelName,
                'session_coach_attendance_status' => $demo->coach_attendance_status,
            ];
        });

        // Fetch the demo session data where coach_attendance_status is COMPLETED
        // $demoSessions = DemoSession::whereIn('demolead_id', $completedDemoLeadIds)
        //     ->where('coach_attendance_status', 'COMPLETED')
        //     ->with(['demolead', 'level'])
        //     ->orderByDesc('id')
        //     ->get();

        // // Format the demo session data
        // $demoData = $demoSessions->map(function ($demo) {
        //     $demolead  = $demo->demolead;
        //     $levelName = $demo->level ? $demo->level->name : 'N/A';

        //     // Split the slot time range and format each part
        //     $slotTimes     = explode(' - ', $demo->slot);
        //     $formattedSlot = count($slotTimes) === 2
        //     ? Carbon::parse($slotTimes[0])->format('g:i A') . ' - ' . Carbon::parse($slotTimes[1])->format('g:i A')
        //     : 'N/A';

        //     return [
        //         'id'                              => $demo->id,
        //         'first_name'                      => $demolead->first_name,
        //         'last_name'                       => $demolead->last_name,
        //         'age'                             => $demolead->age,
        //         'mobile'                          => $demolead->mobile,
        //         'city'                            => $demolead->city,
        //         'country'                         => $demolead->country,
        //         'kids_time_zone'                  => $demolead->kids_time_zone,
        //         'remark'                          => $demolead->remark,
        //         'status'                          => $demolead->status,
        //         'index'                           => $demolead->index,
        //         'date'                            => Carbon::parse($demolead->date)->format('j, M Y'),
        //         'time'                            => Carbon::parse($demolead->time)->format('g:i A'),
        //         'kids_date'                       => Carbon::parse($demolead->kids_date)->format('j, M Y'),
        //         'kids_time'                       => Carbon::parse($demolead->kids_time)->format('g:i A'),
        //         'created_by'                      => $demolead->created_by,
        //         'session_status'                  => $demo->status,
        //         'session_index'                   => $demo->index,
        //         'session_date'                    => Carbon::parse($demo->date)->format('j, M Y'),
        //         'session_time'                    => Carbon::parse($demo->time)->format('g:i A'),
        //         'session_slot'                    => $formattedSlot,
        //         'session_level_name'              => $levelName,
        //         'session_coach_attendance_status' => $demo->coach_attendance_status,
        //     ];
        // });

        // Format the start and end dates
        $formattedStartDate = Carbon::parse($startDate)->format('j, M Y');
        $formattedEndDate   = Carbon::parse($endDate)->format('j, M Y');

        // Return the result as a JSON response, including the formatted start and end dates and the count of completed demos
        return response()->json([
            'completedDemosCount' => $completedDemosCount,
            'demoData'            => $demoData,
            'startDate'           => $formattedStartDate,
            'endDate'             => $formattedEndDate,
        ]);
    }

    public function delayedBatchesReportData(Request $request)
    {
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        if (! $coachId || ! $startDate || ! $endDate) {
            return response()->json(['message' => 'Invalid parameters'], 400);
        }

        $delayedBatchData = DelayedBatch::where('coach_id', $coachId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get()
            ->map(function (DelayedBatch $row) {
                $country = $row->country;
                $countryStr = is_array($country) ? implode(', ', array_filter($country)) : (string) $country;

                $canceledDate = $row->date
                    ? Carbon::parse($row->date)->format('j M Y')
                    : '—';
                $canceledTime = $row->time
                    ? Carbon::parse($row->time)->format('g:i A')
                    : '—';

                return [
                    'batch_name'    => $row->batch_name ?? '',
                    'country'       => $countryStr,
                    'batch_status'  => $row->batch_status ?? '',
                    'level_name'    => $row->level_name ?? '',
                    'timeline'      => $row->timeline ?? '',
                    'canceled_date' => $canceledDate,
                    'canceled_time' => $canceledTime,
                ];
            })
            ->values();

        return response()->json([
            'delayedBatchData' => $delayedBatchData,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
        ]);
    }

    public function batchCompletedData(Request $request)
    {
        // Extract parameters from the request
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        // Get the counts and dates of completed batches within the specified date range
        $completedBatchData = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Batch')
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('batch_id', \DB::raw('count(*) as count'), \DB::raw('GROUP_CONCAT(date) as dates'), \DB::raw('GROUP_CONCAT(time) as times'))
            ->groupBy('batch_id')
            ->get()
            ->keyBy('batch_id');

        // Calculate the total count of completed batches
        //$completedBatchesCount = $completedBatchData->sum('count');
        //dd( $completedBatchesCount);

        // Get the IDs of completed batches
        $completedBatchIds = $completedBatchData->keys();
        //dd($completedBatchIds );

        // Fetch all batch data for the completed batch IDs
        $batches   = Batch::whereIn('id', $completedBatchIds)->get();
        $batchData = $batches->map(function ($batch) use ($completedBatchData) {
            $totalActiveStudents  = $batch->studentBatches()->where('status', 'ACTIVE')->count();
            $activeStudentBatches = $batch->studentBatches()->where('status', 'ACTIVE')->get();
            $levelNames           = $activeStudentBatches->map(function ($studentBatch) {
                return $studentBatch->level->name;
            })->unique()->implode(', ');

            // Fetch all student batches to ensure level data is included for INACTIVE batches
            $allStudentBatches = $batch->studentBatches()->get();
            $allLevelNames     = $allStudentBatches->map(function ($studentBatch) {
                return $studentBatch->level->name;
            })->unique()->implode(', ');

            $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)
                ->where('status', 'COMPLETED')
                ->orderByDesc('id')
                ->first();
            $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;

            // Priority to ACTIVE status for timeline
            $studentBatch = $batch->studentBatches()
                ->where('status', 'ACTIVE')
                ->orderByDesc('updated_at')
                ->first();

            // If no ACTIVE status, take the latest available timeline data
            if (! $studentBatch) {
                $studentBatch = $batch->studentBatches()
                    ->orderByDesc('updated_at')
                    ->first();
            }

            $timeline = '';
            if ($batch) {
                $startDate = Carbon::parse($batch->start_date)->format('j, M Y');
                $endDate   = Carbon::parse($batch->end_date)->format('j, M Y');
                $timeline  = $startDate . ' - ' . $endDate;
            }

            // Format completed dates and times
            $completedDates = explode(',', $completedBatchData[$batch->id]->dates ?? '');
            $completedTimes = explode(',', $completedBatchData[$batch->id]->times ?? '');
            $formattedDates = array_map(function ($date) {
                return Carbon::parse($date)->format('j, M Y');
            }, $completedDates);
            $formattedTimes = array_map(function ($time) {
                return Carbon::parse($time)->format('g:i A');
            }, $completedTimes);

                                       // Determine badge color based on status
            $badgeColor = 'secondary'; // Default color
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
            }

            // Generate status button HTML
            $statusButton = '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 batch-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $batch->id . '" data-id="' . $batch->id . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $batch->status . '</button>';

            // Join country array with commas
            $countryString = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;

            // Fetch Completed Batches Count
            $coachAttendance = CoachAttendance::
                // where('coach_id', $batch->coach_id)
                //     ->
                where('batch_id', $batch->id)
                ->where('type', 'Batch')
                ->where('status', 'COMPLETED')
                ->orderByDesc('id')
                ->count();

            return [
                'id'                    => $batch->id,
                'name'                  => $batch->name,
                'version'               => $batch->version,
                'country'               => $countryString,
                'kids_zone_name'        => $batch->kids_zone_name,
                'created_by'            => $batch->createdBy ? $batch->createdBy->first_name . ' ' . $batch->createdBy->last_name : 'N/A',
                'updated_by'            => $batch->updatedBy ? $batch->updatedBy->first_name . ' ' . $batch->updatedBy->last_name : 'N/A',
                'status'                => $statusButton, // Include the status button HTML
                'total_active_students' => $totalActiveStudents,
                'level_names'           => $batch->status === 'INACTIVE' ? $allLevelNames : $levelNames,
                'timeline'              => $timeline,
                'completed_count'       => $coachAttendance,
                'completed_dates'       => $formattedDates, // Include the formatted dates of completed batches
                'completed_times'       => $formattedTimes, // Include the formatted times of completed batches
            ];
        });

        // Format the start and end dates
        $formattedStartDate = Carbon::parse($startDate)->format('j, M Y');
        $formattedEndDate   = Carbon::parse($endDate)->format('j, M Y');
        // Return the result as a JSON response, including the formatted start and end dates
        return response()->json([
            'batchData' => $batchData,
            'startDate' => $formattedStartDate,
            'endDate'   => $formattedEndDate,
        ]);
    }

    public function coverupclassCompletedData(Request $request)
    {
        // dd($request->all());
        // Extract parameters from the request
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        $coverupclassesAttendance = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'COVERUP')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $coverupclassesAttendance = $coverupclassesAttendance->map(function ($coverupclass) {
            $batch         = Batch::find($coverupclass->batch_id);
            $countryString = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;

            $startDate = Carbon::parse($batch->start_date)->format('j, M Y');
            $endDate   = Carbon::parse($batch->end_date)->format('j, M Y');
            $timeline  = $startDate . ' - ' . $endDate;

            $badgeColor = 'secondary'; // Default color
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
            }

            // Generate status button HTML
            $statusButton        = '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 batch-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $batch->id . '" data-id="' . $batch->id . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $batch->status . '</button>';
            $totalActiveStudents = $batch->studentBatches()->where('status', 'ACTIVE')->count();

            return [
                'id'                    => $batch->id,
                'name'                  => $batch->name,
                'version'               => $batch->version,
                'country'               => $countryString,
                'kids_zone_name'        => $batch->kids_zone_name,
                'created_by'            => $batch->createdBy ? $batch->createdBy->first_name . ' ' . $batch->createdBy->last_name : 'N/A',
                'updated_by'            => $batch->updatedBy ? $batch->updatedBy->first_name . ' ' . $batch->updatedBy->last_name : 'N/A',
                'status'                => $statusButton, // Include the status button HTML
                'total_active_students' => $totalActiveStudents,
                'level_names'           => $batch->level->name,
                'timeline'              => $timeline,
                'attendance_date'       => Carbon::parse($coverupclass->date)->format('j, M Y'),
                // 'completed_count'       => $coachAttendance,
                // 'completed_count'       => $coachAttendance,
                // 'completed_dates'       => $formattedDates, // Include the formatted dates of completed batches
                // 'completed_times'       => $formattedTimes, // Include the formatted times of completed batches
            ];
        });

        // Format the start and end dates
        $formattedStartDate = Carbon::parse($startDate)->format('j, M Y');
        $formattedEndDate   = Carbon::parse($endDate)->format('j, M Y');
        // Return the result as a JSON response, including the formatted start and end dates
        return response()->json([
            'batchData' => $coverupclassesAttendance,
            'startDate' => $formattedStartDate,
            'endDate'   => $formattedEndDate,
        ]);
    }

    public function coachLeaveData(Request $request)
    {
        //dd($request->all());
        // Extract parameters from the request
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');

        // Fetch the count of approved leaves from LeaveRequest
        $approvedLeavesCount = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->whereBetween('from_date', [$startDate, $endDate])
            ->count();

        // Fetch the leave request data
        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->whereBetween('from_date', [$startDate, $endDate])
            ->with('coach')
            ->orderByDesc('id')
            ->get();

        // Format the leave request data
        $leaveData = $leaveRequests->map(function ($leave) {
            $coach = $leave->coach;

            return [
                'id'         => $leave->id,
                'coach_name' => $coach->name,
                'from_date'  => Carbon::parse($leave->from_date)->format('j, M Y'),
                'to_date'    => Carbon::parse($leave->to_date)->format('j, M Y'),
                'from_time'  => Carbon::parse($leave->from_time)->format('g:i A'),
                'to_time'    => Carbon::parse($leave->to_time)->format('g:i A'),
                'reason'     => $leave->reason,
                'status'     => $leave->status,
            ];
        });

        // Format the start and end dates
        $formattedStartDate = Carbon::parse($startDate)->format('j, M Y');
        $formattedEndDate   = Carbon::parse($endDate)->format('j, M Y');

        //dd($leaveData);
        // Return the result as a JSON response, including the formatted start and end dates and the count of approved leaves
        return response()->json([
            'approvedLeavesCount' => $approvedLeavesCount,
            'leaveData'           => $leaveData,
            'startDate'           => $formattedStartDate,
            'endDate'             => $formattedEndDate,
        ]);
    }

    public function coachMasterClassAttendanceData(Request $request)
    {
        // dd($request->all());
        // Extract parameters from the request
        $coachId   = $request->input('coachId');
        $startDate = $request->input('startDate');
        $endDate   = $request->input('endDate');
        // Fetch the count of approved leaves from LeaveRequest

        $masterclassCount = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Masterclass')
            ->where('masterclass_id', '!=', null)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        // Fetch the leave request data
        $masterclassAttendance = CoachAttendance::where('coach_id', $coachId)
            ->where('type', 'Masterclass')
            ->where('masterclass_id', '!=', null)
            ->where('status', 'COMPLETED')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('id')
            ->get();

        // dd($masterclassAttendance);

        // Format the leave request data
        $masterclassData = $masterclassAttendance->map(function ($attendance) {
            // dd( $attendance);
            $masterclass = Masterclass::find($attendance->masterclass_id);
            // if ($masterclass) {
            $coach = Coach::find($masterclass->coach_id);
            // } else {
            //     dd($masterclass);
            // }
            
            // dd($coach);
            return [
                'id'               => $attendance->id,
                'coach_name'       => $coach->user->first_name . ' ' . $coach->user->last_name,
                'masterclass_name' => $masterclass->name,
                'date'             => Carbon::parse($attendance->date)->format('j, M Y'),
                'time'             => Carbon::parse($attendance->time)->format('g:i A'),
                'status'           => $attendance->status,
            ];
        });

        // Format the start and end dates
        $formattedStartDate = Carbon::parse($startDate)->format('j, M Y');
        $formattedEndDate   = Carbon::parse($endDate)->format('j, M Y');

        //dd($masterclassData);
        // Return the result as a JSON response, including the formatted start and end dates and the count of approved leaves
        return response()->json([
            'masterclassCount' => $masterclassCount,
            'masterclassData'  => $masterclassData,
            'startDate'        => $formattedStartDate,
            'endDate'          => $formattedEndDate,
        ]);
    }

    // Fetching Coach Availability Data in Table ::
    public function getAvailability(Request $request, $coachId)
    {
        \Log::debug('Coach ID:', ['coachId' => $coachId]);
        $date    = $request->input('date', Carbon::now()->format('Y-m-d'));
        $dayName = Carbon::parse($date)->format('l');

        // Fetch Coach Availability for the specified date
        $availability = CoachAvailability::with('periods')
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->where('day_of_week', $dayName)
            ->get();
        // Fetch Batches Data
        $batches = Batch::with(['batchSchedules' => function ($query) use ($dayName) {
            $query->where('weekday', $dayName)
                ->where('status', 'ACTIVE'); // Ensure batchSchedules have 'ACTIVE' status
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
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();
        // Fetch Demo Session Data
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->where('date', $date)
            ->get();
        $occupiedSlots = [];

        // dd($batches);
        foreach ($batches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
                $occupiedSlots[] = [
                    'from' => Carbon::parse($schedule->from_time)->format('H:i:s'),
                    'to'   => Carbon::parse($schedule->to_time)->format('H:i:s'),
                ];
            }
        }
        foreach ($demoSessions as $session) {
            list($startTime, $endTime) = explode(' - ', $session->slot);
            $occupiedSlots[]           = [
                'from' => Carbon::parse($startTime)->format('H:i:s'),
                'to'   => Carbon::parse($endTime)->format('H:i:s'),
            ];
        }

        $hourlySlots  = [];
        $combinedData = [];

        foreach ($availability as $dayAvailability) {
            $dayOfWeek = $dayAvailability->day_of_week;
            foreach ($dayAvailability->periods as $period) {
                $fromPeriod = Carbon::createFromFormat('H:i:s', $period->from_period);
                $toPeriod   = Carbon::createFromFormat('H:i:s', $period->to_period);

                while ($fromPeriod->lessThan($toPeriod)) {
                    $endPeriod = $fromPeriod->copy()->addHour();
                    if ($endPeriod->greaterThan($toPeriod)) {
                        $endPeriod = $toPeriod;
                    }

                    $slot = [
                        'from' => $fromPeriod->format('H:i:s'),
                        'to'   => $endPeriod->format('H:i:s'),
                    ];

                    // Check if the slot overlaps with any batch or demo slot
                    $overlaps = false;
                    foreach ($occupiedSlots as $occupiedSlot) {
                        if ($slot['from'] < $occupiedSlot['to'] && $slot['to'] > $occupiedSlot['from']) {
                            $overlaps = true;
                            if ($slot['from'] < $occupiedSlot['from']) {
                                $hourlySlots[$dayOfWeek][] = [
                                    'from' => $slot['from'],
                                    'to'   => $occupiedSlot['from'],
                                ];
                            }
                            if ($slot['to'] > $occupiedSlot['to']) {
                                $hourlySlots[$dayOfWeek][] = [
                                    'from' => $occupiedSlot['to'],
                                    'to'   => $slot['to'],
                                ];
                            }
                            break;
                        }
                    }

                    if (! $overlaps) {
                        $hourlySlots[$dayOfWeek][] = $slot;
                    }

                    $fromPeriod->addHour();
                }
            }
        }

        // Format hourly slots for Calendar
        foreach ($hourlySlots as $dayOfWeek => $slots) {
            foreach ($slots as $slot) {
                $formattedSlot  = Carbon::createFromFormat('H:i:s', $slot['from'])->format('g:i A') . ' - ' . Carbon::createFromFormat('H:i:s', $slot['to'])->format('g:i A');
                $combinedData[] = [
                    'title'     => $formattedSlot,
                    'start'     => $date, // Use the date from the request
                    'color'     => 'black',
                    'textColor' => 'white',
                    'slot'      => $formattedSlot, // Add slot key for consistency
                ];
            }
        }

        // Sort combined data by slot
        usort($combinedData, function ($a, $b) {
            $aStartTime = explode(' - ', $a['slot'])[0];
            $bStartTime = explode(' - ', $b['slot'])[0];
            return strtotime($aStartTime) - strtotime($bStartTime);
        });

        return view('Admin.CoachReports.coachavailability', ['schedules' => $combinedData]);
    }

    // Fetching Schedule Data in Table ::
    public function getSchedule(Request $request, $coachId)
    {
        \Log::debug('Coach ID:', ['coachId' => $coachId]);
        $date    = $request->input('date', Carbon::now('Asia/Kolkata')->format('Y-m-d'));
        $dayName = Carbon::parse($date, 'Asia/Kolkata')->format('l');

        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        $isCoach = in_array("Coach", $role);


        // Fetch leave requests and calculate leave dates
        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->get();

        $leaveDates = [];
        foreach ($leaveRequests as $leaveRequest) {
            $startDate = Carbon::parse($leaveRequest->from_date);
            $endDate   = Carbon::parse($leaveRequest->to_date);
            while ($startDate <= $endDate) {
                $leaveDates[] = [
                    'date'      => $startDate->format('Y-m-d'),
                    'from_time' => $leaveRequest->from_time,
                    'to_time'   => $leaveRequest->to_time,
                ];
                $startDate->addDay();
            }
        }

        // ---------------------------------------------------- ::

        $activeBatches = Batch::with(['batchSchedules' => function ($query) use ($dayName, $date) {
            $query->where('weekday', $dayName)
                ->where('status', 'ACTIVE');
        }])
            ->withCount(['studentBatches as active_students_count' => function ($query) use ($date) {
                $query->where('status', 'ACTIVE') // Count only 'ACTIVE' studentBatches
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            }])
            // ->whereHas('studentBatches', function ($query) use ($date) {
            //     $query->where('status', 'ACTIVE') // Ensure studentBatches have 'ACTIVE' status
            //         ->where('start_date', '<=', $date)
            //         ->where('end_date', '>=', $date);
            // })
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();

        // dd($activeBatches);

        $statuses = ['INACTIVE', 'STANDBY'];

        $inactiveStandbyBatches = Batch::with(['batchSchedules' => function ($query) use ($dayName) {
            $query->where('weekday', $dayName);
        }])
            // ->whereHas('studentBatches', function ($query) use ($date, $statuses) {
            //     $query // Use $statuses array for filtering
            //         ->where('start_date', '<=', $date)
            //         ->where('end_date', '>=', $date);
            // })
            ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
            ->where('coach_id', $coachId)
            ->whereIn('status', $statuses)
            ->get();

        // dd($inactiveStandbyBatches);
        // Merge the results
        $batches      = $activeBatches->merge($inactiveStandbyBatches);
        $combinedData = [];
        foreach ($batches as $batch) {
            foreach ($batch->batchSchedules as $schedule) {
                // dd($schedule, $schedule->batch_id, $batch->id, $date);
                // Fetch the CoachAttendance record for the batch on the specific date
                $coachAttendance = CoachAttendance::where('batch_id', $batch->id)
                    // ->where('type', 'Batch')
                    // ->where('coach_id', $coachId)
                    ->where('date', $date) // Filter by the specific date
                    // ->orderByDesc('id')
                    ->first();
                    // dd($coachAttendance);
                \Log::debug('CoachAttendance Query Result', [
                'batch_id' => $batch->id,
                'coach_id' => $coachId,
                'date'     => $date,
                'found'    => $coachAttendance ? true : false,
                'attendance' => $coachAttendance ? $coachAttendance->toArray() : null,
            ]);

                $coachAttendanceStatus = $coachAttendance ? $coachAttendance->status : 'N/A';

                // Check if the date is within the leave dates
                $isLeaveDate = false;
                foreach ($leaveDates as $leaveDate) {
                    if ($leaveDate['date'] == $date) {
                        if ($leaveDate['from_time'] && $leaveDate['to_time']) {
                            $leaveFromTime = Carbon::createFromFormat('H:i:s', $leaveDate['from_time']);
                            $leaveToTime   = Carbon::createFromFormat('H:i:s', $leaveDate['to_time']);
                            $batchFromTime = Carbon::createFromFormat('H:i:s', $schedule->from_time);
                            $batchToTime   = Carbon::createFromFormat('H:i:s', $schedule->to_time);

                            // Check if the batch is within the leave period
                            if ($batchFromTime->between($leaveFromTime, $leaveToTime, false) || $batchToTime->between($leaveFromTime, $leaveToTime, false)) {
                                $isLeaveDate = true;
                                break;
                            }
                        } else {
                            $isLeaveDate = true;
                            break;
                        }
                    }
                }

                $unique_student_count = StudentBatch::where('batch_id', $batch->id)
                // ->where('status', 'ACTIVE')
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date)
                    ->distinct('student_id')
                    ->count('student_id');

                if ($coachAttendanceStatus != 'N/A') {
                    $status = $coachAttendanceStatus;
                }else{
                    $next_day = Carbon::parse($date)->addDay()->format('Y-m-d');
                    $coachAttendance = CoachAttendance::where('batch_id', $batch->id)

                    ->where('date', $next_day) // Filter by the specific date
                    ->orderByDesc('id')
                    ->first();

                    $coachAttendanceStatus = $coachAttendance ? $coachAttendance->status : 'N/A';

                }

                if (! $isLeaveDate) {
                    $combinedData[] = [
                        'id'              => $batch->id,
                        'name'            => $batch->name,
                        'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                        'status'          => $coachAttendanceStatus,
                        'type'            => 'Batch',
                        'active_students' => $unique_student_count,
                        'date'            => $date,
                        'batchStatus'     => $batch->status,
                    ];
                }
            }
        }

        // dd($combinedData);
        // Demo Session Data ::
        // ---------------------------------------------------- ::
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->where('date', $date)
            ->get();

        foreach ($demoSessions as $session) {
            list($startTime, $endTime) = explode(' - ', $session->slot);
            $formattedStartTime        = Carbon::createFromFormat('H:i:s', $startTime)->format('h:i A');
            $formattedEndTime          = Carbon::createFromFormat('H:i:s', $endTime)->format('h:i A');

            // Fetch the latest CoachAttendance record where demolead_id and coach_id match
            $coachAttendance = CoachAttendance::where('demolead_id', $session->demolead->id)
                ->where('coach_id', $coachId)
                ->latest()
                ->first();
            $coachAttendanceStatus = $coachAttendance ? $coachAttendance->status : 'N/A';

            // Check if the date is within the leave dates
            $isLeaveDate = false;
            foreach ($leaveDates as $leaveDate) {
                if ($leaveDate['date'] == $date) {
                    if ($leaveDate['from_time'] && $leaveDate['to_time']) {
                        $leaveFromTime   = Carbon::createFromFormat('H:i:s', $leaveDate['from_time']);
                        $leaveToTime     = Carbon::createFromFormat('H:i:s', $leaveDate['to_time']);
                        $sessionFromTime = Carbon::createFromFormat('H:i:s', $startTime);
                        $sessionToTime   = Carbon::createFromFormat('H:i:s', $endTime);

                        // Check if the session is within the leave period
                        if ($sessionFromTime->between($leaveFromTime, $leaveToTime, false) || $sessionToTime->between($leaveFromTime, $leaveToTime, false)) {
                            $isLeaveDate = true;
                            break;
                        }
                    } else {
                        $isLeaveDate = true;
                        break;
                    }
                }
            }

            if (! $isLeaveDate) {
                $combinedData[] = [
                    'id'              => $session->demolead->id,
                    'name'            => $session->demolead->first_name . ' ' . $session->demolead->last_name,
                    'slot'            => $formattedStartTime . ' - ' . $formattedEndTime,
                    'status'          => $coachAttendanceStatus,
                    'type'            => 'Demo',
                    'active_students' => 1,
                    'date'            => $date,
                ];
            }
        }

        // ---------------------------------------------------- ::
        if ((Carbon::parse($date)->isToday() || Carbon::parse($date)->isFuture()) && ! $isCoach) {
            $availability = CoachAvailability::with('periods')
                ->where('coach_id', $coachId)
                ->where('status', 'ACTIVE')
                ->where('day_of_week', $dayName)
                ->get();
            $occupiedSlots = [];
            foreach ($batches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    $occupiedSlots[] = [
                        'from' => Carbon::parse($schedule->from_time)->format('H:i:s'),
                        'to'   => Carbon::parse($schedule->to_time)->format('H:i:s'),
                    ];
                }
            }
            foreach ($demoSessions as $session) {
                list($startTime, $endTime) = explode(' - ', $session->slot);
                $occupiedSlots[]           = [
                    'from' => Carbon::parse($startTime)->format('H:i:s'),
                    'to'   => Carbon::parse($endTime)->format('H:i:s'),
                ];
            }

            $hourlySlots = [];
            foreach ($availability as $dayAvailability) {
                $dayOfWeek = $dayAvailability->day_of_week;
                foreach ($dayAvailability->periods as $period) {
                    $fromPeriod = Carbon::createFromFormat('H:i:s', $period->from_period);
                    $toPeriod   = Carbon::createFromFormat('H:i:s', $period->to_period);

                    while ($fromPeriod->lessThan($toPeriod)) {
                        $endPeriod = $fromPeriod->copy()->addHour();
                        if ($endPeriod->greaterThan($toPeriod)) {
                            $endPeriod = $toPeriod;
                        }
                        $slot = [
                            'from' => $fromPeriod->format('H:i:s'),
                            'to'   => $endPeriod->format('H:i:s'),
                        ];
                        // Check if the slot overlaps with any batch or demo slot
                        $overlaps = false;
                        foreach ($occupiedSlots as $occupiedSlot) {
                            if ($slot['from'] < $occupiedSlot['to'] && $slot['to'] > $occupiedSlot['from']) {
                                $overlaps = true;
                                if ($slot['from'] < $occupiedSlot['from']) {
                                    $hourlySlots[$dayOfWeek][] = [
                                        'from' => $slot['from'],
                                        'to'   => $occupiedSlot['from'],
                                    ];
                                }
                                if ($slot['to'] > $occupiedSlot['to']) {
                                    $hourlySlots[$dayOfWeek][] = [
                                        'from' => $occupiedSlot['to'],
                                        'to'   => $slot['to'],
                                    ];
                                }
                                break;
                            }
                        }
                        foreach ($leaveDates as $leaveDate) {
                            if ($leaveDate['date'] == $date) {
                                $leaveFromTime = Carbon::createFromFormat('H:i:s', $leaveDate['from_time']);
                                $leaveToTime   = Carbon::createFromFormat('H:i:s', $leaveDate['to_time']);
                                if ($slot['from'] < $leaveToTime->format('H:i:s') && $slot['to'] > $leaveFromTime->format('H:i:s')) {
                                    $overlaps = true;
                                    break;
                                }
                            }
                        }
                        if (! $overlaps) {
                            $hourlySlots[$dayOfWeek][] = $slot;
                        }
                        $fromPeriod->addHour();
                    }
                }
            }

            // Format hourly slots for Calendar
            foreach ($hourlySlots as $dayOfWeek => $slots) {
                foreach ($slots as $slot) {
                    $formattedSlot = Carbon::createFromFormat('H:i:s', $slot['from'])->format('g:i A') . ' - ' . Carbon::createFromFormat('H:i:s', $slot['to'])->format('g:i A');

                    // $check_coverupclass = false;

                    // $coverupclass = Coverupclass::where('new_coach_id', $coachId)
                    //     ->whereHas('schedule', function ($query) use ($dayOfWeek) {
                    //         $query->where('weekday', $dayOfWeek)->where('from_time', '>=', $slot['from'])->where('to_time', '<=', $slot['to']);
                    //     })
                    //     ->where('date', $date)
                    //     ->get();

                    // if ($coverupclass->count() > 0) {
                    //     $check_coverupclass = true;
                    // }

                    // if (! $check_coverupclass) {
                    $combinedData[] = [
                        'id'              => null,
                        'name'            => 'Available',
                        'slot'            => $formattedSlot,
                        'status'          => null,
                        'type'            => 'Available',
                        'active_students' => null,
                        'date'            => $date,
                    ];
                    // }

                }
            }
        }

        $masterclassData = [];
        // Fetch Masterclass Data
        $masterclasses = Masterclass::where('coach_id', $coachId)
            ->where('date', $date)
            ->get();

        foreach ($masterclasses as $masterclass) {
            $masterclassData[] = [
                'id'     => $masterclass->id,
                'name'   => $masterclass->name,
                'slot'   => Carbon::parse($masterclass->time)->format('h:i A') . ' - ' . Carbon::parse($masterclass->time)->format('h:i A'),
                'status' => $masterclass->status,
                'type'   => 'Masterclass',
            ];
        }

        //Cover up Class
        $coverupClasses = Coverupclass::where('new_coach_id', $coachId)
            ->where('date', $date)
            ->get();

        // dd($schedule);
        $coverupClassBatchIds = $coverupClasses->pluck('batch_id')->toArray();
        // dd($coachId);
        $coverupClassBatches  = Batch::
        with(['batchSchedules' => function ($query) use ($dayName, $date) {
            $query->where('weekday', $dayName);
        }])
            ->
            withCount(['studentBatches as active_students_count' => function ($query) use ($date) {
                $query
                    ->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            }])
            ->whereHas('studentBatches', function ($query) use ($date) {
                $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
            })
            ->whereIn('id', $coverupClassBatchIds)
            // ->where('status', 'ACTIVE')
            ->get();

        // dd($coverupClassBatches);
        foreach ($coverupClassBatches as $batch) {
            // if ($batch->id == '4238') {
                foreach ($batch->batchSchedules as $schedule) {
                    $status = $schedule->status;
                    // dd($coachId, $date, $schedule->batch_id);
                    $latest_batch_attendance = CoachAttendance::where('batch_id', $schedule->batch_id)
                        ->whereDate('date', $date)
                        ->orderBy('id', 'desc')->first();

                    // dd($latest_batch_attendance);

                    // dd($latest_batch_attendance);
                    if ($latest_batch_attendance) {
                        $status = $latest_batch_attendance->status;
                    }

                    // dd($status);
                    $combinedData[] = [
                        'date'            => $date,
                        'id'              => $batch->id,
                        'name'            => $batch->name,
                        'slot'            => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                        'status'          => $status,
                        'type'            => 'Coverup',
                        'batchStatus'     => $batch->status,
                        'active_students' => $batch->active_students_count,
                    ];
                }
            // }
        }

        // dd($combinedData);

        // Sort combined data by slot
        // ---------------------------------------------------- ::
        usort($combinedData, function ($a, $b) {
            $aStartTime = explode(' - ', $a['slot'])[0];
            $bStartTime = explode(' - ', $b['slot'])[0];
            return strtotime($aStartTime) - strtotime($bStartTime);
        });


        // dd($combinedData);

        return view('Admin.CoachReports.schedule', ['schedules' => $combinedData, 'date' => $date, 'masterclassData' => $masterclassData]);
    }

    // Fetching Calendar Data ::
    public function getCalendarData(Request $request, $coachId)
    {
        $user      = auth()->user();
        $role      = $user->getRoleNames()->toArray();
        $today     = Carbon::now();
        $todayDate = $today->format('Y-m-d');
        $startDate = Carbon::now()->startOfYear();
        $endDate   = Carbon::now()->endOfYear();

        // Get calendar data from separate functions
        $batchCalendarData         = $this->getBatchCalendarData($coachId, $role, $todayDate);
        $leaveRequestsCalendarData = $this->getLeaveRequestsCalendarData($coachId, $startDate, $endDate);
        $demoSessionsCalendarData  = $this->getDemoSessionsCalendarData($coachId, $role, $todayDate, $startDate, $endDate);
        // dd($batchCalendarData);

        $calendarData = array_merge(
            $batchCalendarData,
            $leaveRequestsCalendarData,
            $demoSessionsCalendarData
        );

        // Sort calendar data by start date
        usort($calendarData, function ($a, $b) {
            return strtotime($a['start']) - strtotime($b['start']);
        });

        return view('Admin.CoachReports.calendar', compact('calendarData'));
    }

    private function getBatchCalendarData($coachId, $role, $todayDate)
    {
        // Fetch batches with ACTIVE status
        $activeBatches = Batch::with(['batchSchedules' => function ($query) {
            $query->where('status', 'ACTIVE');
        }, 'studentBatches' => function ($query) {
            $query->where('status', 'ACTIVE');
        }])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->get();

        // Fetch batches with INACTIVE and STANDBY statuses
        $statuses               = ['INACTIVE', 'STANDBY'];
        $inactiveStandbyBatches = Batch::with(['batchSchedules', 'studentBatches'])
            ->where('coach_id', $coachId)
            ->whereIn('status', $statuses)
            ->get();

        // Merge the results
        $batches = $activeBatches->merge($inactiveStandbyBatches);

        // Fetch approved leave requests
        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->get();

        // Process leave dates
        $leaveDates = [];
        foreach ($leaveRequests as $leaveRequest) {
            $startDate = Carbon::parse($leaveRequest->from_date);
            $endDate   = Carbon::parse($leaveRequest->to_date);
            while ($startDate <= $endDate) {
                $leaveDates[] = [
                    'date'      => $startDate->format('Y-m-d'),
                    'from_time' => $leaveRequest->from_time,
                    'to_time'   => $leaveRequest->to_time,
                ];
                $startDate->addDay();
            }
        }

        // Generate calendar data
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

                    $isLeaveDate = false;
                    foreach ($leaveDates as $leaveDate) {
                        if ($leaveDate['date'] == $date->format('Y-m-d')) {
                            if ($leaveDate['from_time'] && $leaveDate['to_time']) {
                                $leaveFromTime = Carbon::createFromFormat('H:i:s', $leaveDate['from_time']);
                                $leaveToTime   = Carbon::createFromFormat('H:i:s', $leaveDate['to_time']);
                                $batchFromTime = Carbon::createFromFormat('H:i:s', $schedule->from_time);
                                $batchToTime   = Carbon::createFromFormat('H:i:s', $schedule->to_time);

                                // Check if the batch is within the leave period
                                if ($batchFromTime->between($leaveFromTime, $leaveToTime, false) || $batchToTime->between($leaveFromTime, $leaveToTime, false)) {
                                    $isLeaveDate = true;
                                    break;
                                }
                            } else {
                                $isLeaveDate = true;
                                break;
                            }
                        }
                    }

                    if (! $isLeaveDate) {
                        $color     = 'red';
                        $textColor = 'white';
                        if ($batch->status === 'INACTIVE') {
                            $color = 'PURPLE';
                        } elseif ($batch->status === 'STANDBY') {
                            $color = 'BLACK';
                        }

                        if (in_array("Coach", $role)) {
                            if ($date->format('Y-m-d') <= $todayDate) {
                                $calendarData[] = [
                                    'title'     => $formattedFromTime . ' - ' . $formattedToTime,
                                    'start'     => $date->format('Y-m-d'),
                                    'color'     => $color,
                                    'textColor' => $textColor,
                                ];
                            }
                        } else {
                            $calendarData[] = [
                                'title'     => $formattedFromTime . ' - ' . $formattedToTime,
                                'start'     => $date->format('Y-m-d'),
                                'color'     => $color,
                                'textColor' => $textColor,
                            ];
                        }
                    }

                    $date->addWeek();
                }
            }
        }

        return $calendarData;
    }

    private function getLeaveRequestsCalendarData($coachId, $startDate, $endDate)
    {
        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('from_date', [$startDate, $endDate])
                    ->orWhereBetween('to_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('from_date', '<=', $startDate)
                            ->where('to_date', '>=', $endDate);
                    });
            })
            ->where('status', 'APPROVED')
            ->get();

        $calendarData = [];
        foreach ($leaveRequests as $leaveRequest) {
            $calendarData[] = [
                'title'     => '' . Carbon::parse($leaveRequest->from_time)->format('g:i A') . ' - ' . Carbon::parse($leaveRequest->to_time)->format('g:i A') . '',
                'start'     => $leaveRequest->from_date,
                'end'       => Carbon::parse($leaveRequest->to_date)->addDay()->format('Y-m-d'),
                'color'     => 'yellow',
                'textColor' => 'black',
            ];
        }

        return $calendarData;
    }

    private function getDemoSessionsCalendarData($coachId, $role, $todayDate, $startDate, $endDate)
    {
        $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
            ->where('coach_id', $coachId)
            ->where('status', 'ACTIVE')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $leaveRequests = LeaveRequest::where('coach_id', $coachId)
            ->where('status', 'APPROVED')
            ->get();

        $leaveDates = [];
        foreach ($leaveRequests as $leaveRequest) {
            $startDate = Carbon::parse($leaveRequest->from_date);
            $endDate   = Carbon::parse($leaveRequest->to_date);
            while ($startDate <= $endDate) {
                $leaveDates[] = [
                    'date'      => $startDate->format('Y-m-d'),
                    'from_time' => $leaveRequest->from_time,
                    'to_time'   => $leaveRequest->to_time,
                ];
                $startDate->addDay();
            }
        }

        $calendarData = [];
        foreach ($demoSessions as $session) {
            $isLeaveDate = false;
            foreach ($leaveDates as $leaveDate) {
                if ($leaveDate['date'] == $session->date) {
                    if ($leaveDate['from_time'] && $leaveDate['to_time']) {
                        $leaveFromTime             = Carbon::createFromFormat('H:i:s', $leaveDate['from_time']);
                        $leaveToTime               = Carbon::createFromFormat('H:i:s', $leaveDate['to_time']);
                        list($startTime, $endTime) = explode(' - ', $session->slot);
                        $sessionFromTime           = Carbon::createFromFormat('H:i:s', $startTime);
                        $sessionToTime             = Carbon::createFromFormat('H:i:s', $endTime);

                        // Check if the session is within the leave period
                        if ($sessionFromTime->between($leaveFromTime, $leaveToTime, false) || $sessionToTime->between($leaveFromTime, $leaveToTime, false)) {
                            $isLeaveDate = true;
                            break;
                        }
                    } else {
                        $isLeaveDate = true;
                        break;
                    }
                }
            }

            if (! $isLeaveDate) {
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
        }

        return $calendarData;
    }

    public function reportsScheduleData(Request $request)
    {
        //dd($request->all());
        $entityType = $request->input('type');
        $entityId   = $request->input('id');

        if ($entityType === 'Batch' || $entityType === 'Coverup') {
            $batch                  = Batch::findOrFail($entityId);
            $batchSchedules         = $batch->batchSchedules;
            $studentBatches         = $batch->studentBatches()->where('status', 'ACTIVE')->get();
            $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)->where('status', 'COMPLETED')->orderByDesc('date')->first();
            $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
            return view('Admin.CoachReports.batchshow', compact('batch', 'batchSchedules', 'studentBatches', 'totalSessionsCompleted'));
        } elseif ($entityType === 'Demo') {
            $demolead     = DemoLead::findOrFail($entityId);
            $demosessions = DemoSession::where('demolead_id', $demolead->id)->get();
            return view('Admin.CoachReports.demoshow', compact('demolead', 'demosessions'));
        }

    }

    public function getAttendanceData(Request $request, $coachId)
    {
        // dd($request->all());
        // Get the authenticated user
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        // dd($user->can('reports-attedance'));
        // Check if the user has the SuperAdmin role
       if (!in_array("SuperAdmin", $role) && !$user->can('reports-attedance')) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }


        // Get the request parameters
        $id   = $request->input('id');
        $type = $request->input('type');
        $date = $request->input('date');

        // Validate the type parameter
        if (! in_array($type, ['Batch', 'Demo', 'Coverup'])) {
            return response()->json(['error' => 'Invalid type specified'], 400);
        }
        // dd($type);
        // Check the status of the batch if the type is 'Batch'
        if ($type === 'Batch' || $type === 'Coverup') {
            $batch = Batch::find($id);
            if (! $batch) {
                return response()->json(['error' => 'Batch not found'], 404);
            }
            // dd($batch->status);
            // Call the appropriate handler based on the batch status
            switch ($batch->status) {
                case 'ACTIVE' || 'STANDBY':
                    return $this->ACTIVEhandleBatchType($id, $coachId, $date, $type);
                case 'INACTIVE':
                    return $this->INACTIVEhandleBatchType($id, $coachId, $date, $type);
                // case 'STANDBY':
                //     return $this->STANDBYhandleBatchType($id, $coachId, $date);
                default:
                    return response()->json(['error' => 'Invalid batch status'], 403);
            }
        } elseif ($type === 'Demo') {
            return $this->handleDemoType($id, $coachId, $date);
        }
    }

    private function ACTIVEhandleBatchType($id, $coachId, $date, $type)
    {
        // dd($id, $coachId, $date);
        $data = Batch::where('id', $id)
        // ->where('coach_id', $coachId)
            ->with(['batchSchedules', 'studentBatches' => function ($query) use ($date) {
                $query->distinct('student_id')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            }, 'coach', 'parent'])
            ->first();

        // dd($data);
        if (! $data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $batchLevel = Level::where('id', $data['level_id'])->first();

        // $batchLevel = optional($data->studentBatches->first())->level;
        // dd($batchLevel);
        $batchStartDate  = optional($data->studentBatches->first())->start_date;
        $batchEndDate    = optional($data->studentBatches->first())->end_date;
        $coachAttendance = CoachAttendance::where('batch_id', $id)
        // ->where('coach_id', $coachId)
            ->whereDate('date', $date)
            ->orderByDesc('id')
            ->first();


        // Extract the number_of_batch_sessions from the latest entry
        $latestCompletedSession = CoachAttendance::where('batch_id', $id)
            ->where('coach_id', $coachId)
            ->where('status', 'COMPLETED')
            ->orderByDesc('id')
            ->first();

        $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
        $studentAttendances     = StudentAttendance::with(['student', 'batch'])
            ->whereHas('batch', function ($query) use ($id) {
                $query->where('batch_id', $id);
            })
            ->whereDate('date', $date)
            ->get();

        // Extract from and to times separately for the active slot
        $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
        $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
        $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

        return view('Admin.CoachReports.BatchAttendances.activebatchattendance', [
            // batch timeline ::
            'type'                  => strtoupper($type),
            'coachId'               => $coachId,
            'batchStartDate'        => $batchStartDate,
            'batchEndDate'          => $batchEndDate,
            'fromTime'              => $fromTime,
            'toTime'                => $toTime,
            'date'                  => $date,
            'data'                  => $data,
            'batchLevel'            => $batchLevel,
            'coachAttendance'       => $coachAttendance,
            'studentAttendances'    => $studentAttendances,
            'numberOfBatchSessions' => $totalSessionsCompleted,
        ]);
    }

    private function INACTIVEhandleBatchType($id, $coachId, $date, $type)
    {
        // Check if there is attendance data in the StudentAttendance table with status NOTMARKED
        $studentAttendances = StudentAttendance::where('batch_id', $id)
            ->where('coach_id', $coachId)
            ->whereDate('date', $date)
            ->where('status', 'NOTMARKED')
            ->get();

        if ($studentAttendances->isEmpty()) {
            // Handle the case where no attendance records exist
            return response()->json(['error' => 'Batch Is INACTIVE'], 404);
        }

        // Get the student IDs from the attendance records
        $notMarkedStudentIds = $studentAttendances->pluck('student_id')->toArray();

        // Fetch the batch data and filter the students using the student IDs
        $data = Batch::where('id', $id)
            ->where('coach_id', $coachId)
            ->with(['batchSchedules', 'studentBatches' => function ($query) use ($date, $notMarkedStudentIds) {
                $query->whereIn('student_id', $notMarkedStudentIds)->with('student')->distinct('student_id')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            }, 'coach', 'parent'])
            ->first();

        if (! $data) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $batchLevel      = optional($data->studentBatches->first())->level;
        $batchStartDate  = optional($data->studentBatches->first())->start_date;
        $batchEndDate    = optional($data->studentBatches->first())->end_date;
        $coachAttendance = CoachAttendance::where('batch_id', $id)
        // ->where('coach_id', $coachId)
            ->whereDate('date', $date)
            ->first();
        //dd( $coachAttendance);

        // Extract the number_of_batch_sessions from the latest entry
        $latestCompletedSession = CoachAttendance::where('batch_id', $id)
            ->where('coach_id', $coachId)
            ->where('status', 'COMPLETED')
            ->orderByDesc('id')
            ->first();
        $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
        $studentAttendances     = StudentAttendance::with(['student', 'batch'])
            ->whereHas('batch', function ($query) use ($id) {
                $query->where('batch_id', $id);
            })
            ->whereDate('date', $date)
            ->get();
        //dd( $studentAttendances);

        // Extract from and to times separately for the active slot
        $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
        $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
        $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

        //dd($data);

        //dd($coachAttendance ,  $studentAttendances);
        return view('Admin.CoachReports.BatchAttendances.inactivebatchattendance', [
            // batch timeline ::
            'type'                  => strtoupper($type),
            'coachId'               => $coachId,
            'batchStartDate'        => $batchStartDate,
            'batchEndDate'          => $batchEndDate,
            'fromTime'              => $fromTime,
            'toTime'                => $toTime,
            'date'                  => $date,
            // batch data ::
            'data'                  => $data,
            'batchLevel'            => $batchLevel,
            'coachAttendance'       => $coachAttendance,
            'studentAttendances'    => $studentAttendances,
            'numberOfBatchSessions' => $totalSessionsCompleted,
        ]);
    }

    private function STANDBYhandleBatchType($id, $coachId, $date, $type)
    {

        // Check if there is attendance data in the StudentAttendance table with status NOTMARKED
        $studentAttendances = StudentAttendance::where('batch_id', $id)
            ->where('coach_id', $coachId)
            ->whereDate('date', $date)
        // ->where('status', 'NOTMARKED')
            ->get();
        // dd($studentAttendances);
        if ($studentAttendances->isEmpty()) {
            // Handle the case where no attendance records exist
            return response()->json(['error' => 'Batch Is STANDBY'], 404);
        }

        // Get the student IDs from the attendance records
        $notMarkedStudentIds = $studentAttendances->pluck('student_id')->toArray();

        // Fetch the batch data and filter the students using the student IDs
        $data = Batch::where('id', $id)
            ->where('coach_id', $coachId)
            ->with(['batchSchedules', 'studentBatches' => function ($query) use ($notMarkedStudentIds, $date) {
                $query->whereIn('student_id', $notMarkedStudentIds)->with('student')->distinct('student_id')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
            }, 'coach', 'parent'])
            ->first();

        if (! $data) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $batchLevel      = optional($data->studentBatches->first())->level;
        $batchStartDate  = optional($data->studentBatches->first())->start_date;
        $batchEndDate    = optional($data->studentBatches->first())->end_date;
        $coachAttendance = CoachAttendance::where('batch_id', $id)
        // ->where('coach_id', $coachId)
            ->whereDate('date', $date)
            ->first();
        //dd( $coachAttendance);

        // Extract the number_of_batch_sessions from the latest entry
        $latestCompletedSession = CoachAttendance::where('batch_id', $id)
            ->where('coach_id', $coachId)
            ->where('status', 'COMPLETED')
            ->orderByDesc('id')
            ->first();
        $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;
        $studentAttendances     = StudentAttendance::with(['student', 'batch'])
            ->whereHas('batch', function ($query) use ($id) {
                $query->where('batch_id', $id);
            })
            ->whereDate('date', $date)
            ->get();
        //dd( $studentAttendances);

        // Extract from and to times separately for the active slot
        $activeSlot = $data->batchSchedules->firstWhere('status', 'ACTIVE');
        $fromTime   = $activeSlot ? Carbon::parse($activeSlot->from_time)->format('h:i A') : null;
        $toTime     = $activeSlot ? Carbon::parse($activeSlot->to_time)->format('h:i A') : null;

        //dd($data);

        //dd($coachAttendance ,  $studentAttendances);
        return view('Admin.CoachReports.BatchAttendances.standbybatchattendance', [
            // batch timeline ::
            'type'                  => strtoupper($type),
            'coachId'               => $coachId,
            'batchStartDate'        => $batchStartDate,
            'batchEndDate'          => $batchEndDate,
            'fromTime'              => $fromTime,
            'toTime'                => $toTime,
            'date'                  => $date,
            // batch data ::
            'data'                  => $data,
            'batchLevel'            => $batchLevel,
            'coachAttendance'       => $coachAttendance,
            'studentAttendances'    => $studentAttendances,
            'numberOfBatchSessions' => $totalSessionsCompleted,
        ]);
    }

    private function handleDemoType($demoleadId, $coachId, $date)
    {
        // Retrieve the demo session data based on demolead_id
        $data = DemoSession::where('demolead_id', $demoleadId)
            ->where('coach_id', $coachId)
            ->with(['demolead', 'coach', 'level'])
            ->first();

        // Debug statement
        if (! $data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $levels = Level::where('status', 'ACTIVE')->get();

        $studentAttendances = StudentAttendance::where('demolead_id', $demoleadId)
            ->where('coach_id', $coachId)
            ->whereDate('date', $date)
            ->get();
        $coachAttendance = CoachAttendance::where('coach_id', $coachId)
            ->where('demolead_id', $demoleadId)
            ->whereDate('date', $date)
            ->first();

        return view('Admin.CoachReports.demoattendance', [
            'date'               => $date,
            'data'               => $data,
            'levels'             => $levels,
            'studentAttendances' => $studentAttendances,
            'coachAttendance'    => $coachAttendance,
        ]);
    }

    public function batchAttendance(Request $request, $coachId)
    {
        $coachId = $request->coach_id;
        // dd($request->all());
        // Authorization: only users with permission OR SuperAdmin role
        $user = Auth::user();
        if (!$user->can('reports-attedance') && !in_array("SuperAdmin", $user->getRoleNames()->toArray())) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Validation rules
        $rules = [
            'coach_id'        => 'required|integer',
            'type'            => 'required|string',
            'batch_id'        => 'required|integer|exists:batchs,id',
            'date'            => 'required|date',
            'time'            => 'required',
            'student_ids'     => 'required|array|min:1',
            'student_ids.*'   => 'required|integer|exists:students,id',
            'status'          => 'required|in:COMPLETED,CANCELLED',
            'studentStatus'   => 'required|array',
            'studentStatus.*' => 'required|in:PRESENT,ABSENT',
            'studentRemark'   => 'sometimes|array',
            'studentRemark.*' => 'nullable|string',
            'level_id'        => 'sometimes|integer',
            'homework_link'   => 'sometimes|nullable|string',
            'recording_link'  => 'sometimes|nullable|string',
            'chapter_name'    => 'sometimes|nullable|string|max:255',
            'batchEndTiming'  => 'sometimes|nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $requestedType = $request->type;

        // Route param coachId must match request
        if ($requestedType !== 'COVERUP' && (int) $coachId !== (int) $request->input('coach_id')) {
            return response()->json(['error' => 'Coach ID mismatch'], 422);
        }

        // Normalize studentStatus keys
        $studentIds = array_values(array_map('intval', $request->input('student_ids', [])));
        // dd($studentIds);
        $studentStatus = $request->input('studentStatus', []);
        $studentStatusKeys = array_map('intval', array_keys($studentStatus));
        $missingStatusFor = array_values(array_diff($studentIds, $studentStatusKeys));
        if (!empty($missingStatusFor)) {
            return response()->json([
                'error' => 'Missing studentStatus entries for some students',
                'missing' => $missingStatusFor
            ], 422);
        }

        // Ensure students belong to batch and are ACTIVE
        $batchId = $request->input('batch_id');
        $batch = Batch::find($batchId);
        if (!$batch) {
            return response()->json(['error' => 'Batch not found'], 422);
        }

        $validStudentIds = StudentBatch::where('batch_id', $batchId)
            ->whereIn('student_id', $studentIds)
            ->where('status', 'ACTIVE')
            ->pluck('student_id')
            ->toArray();

        $invalidStudents = array_values(array_diff($studentIds, $validStudentIds));
        // if (!empty($invalidStudents)) {
        //     return response()->json([
        //         'error' => 'Some students are not active in this batch',
        //         'invalid_students' => $invalidStudents
        //     ], 422);
        // }

        $attendanceDate = Carbon::parse($request->input('date'))->toDateString();

        DB::beginTransaction();
        try {
            // Existing coach attendance
            $todaysCoachAttendance = CoachAttendance::where('batch_id', $batchId)
                ->whereDate('date', $attendanceDate)
                ->first();

            $latestCoachAttendance = CoachAttendance::where('batch_id', $batchId)
                ->orderBy('id', 'desc')
                ->first();

            $requestedStatus = $request->input('status');
            // dd($requestedStatus, $todaysCoachAttendance, $latestCoachAttendance);
            // === Update existing coach attendance ===
            if ($todaysCoachAttendance) {
                $previousStatus = $todaysCoachAttendance->status;
                // dd($previousStatus, $requestedStatus);
                // CANCELLED -> COMPLETED (revert / pull back dates)
                if ($previousStatus === 'CANCELLED' && $requestedStatus === 'COMPLETED') {
                    $this->adjustEndDatesBySchedule($batch, 'decrease');
                }

                // COMPLETED -> CANCELLED (push forward dates)
                if ($previousStatus === 'COMPLETED' && $requestedStatus === 'CANCELLED') {
                    $this->adjustEndDatesBySchedule($batch, 'increase');
                }

                // dd($coachId);
                // Update coach attendance
                $todaysCoachAttendance->coach_id = $coachId;
                $todaysCoachAttendance->type = $request->input('type');
                $todaysCoachAttendance->time = $request->input('time');
                $todaysCoachAttendance->status = $requestedStatus;
                $todaysCoachAttendance->homework_link = $request->input('homework_link', '') ?? '';
                $todaysCoachAttendance->recording_link = $request->input('recording_link', '') ?? '';
                $todaysCoachAttendance->chapter_name = $request->input('chapter_name', '') ?? '';

                if ($requestedStatus !== 'CANCELLED') {
                    $todaysCoachAttendance->number_of_batch_sessions = ($latestCoachAttendance && $latestCoachAttendance->id !== $todaysCoachAttendance->id)
                        ? $latestCoachAttendance->number_of_batch_sessions + 1
                        : ($todaysCoachAttendance->number_of_batch_sessions ?: 1);
                }

                $todaysCoachAttendance->save();
                $coachAttendanceId = $todaysCoachAttendance->id;
            } else {
                // === No coach attendance => create ===
                $coachAttendance = new CoachAttendance();
                $coachAttendance->coach_id = $coachId;
                $coachAttendance->type = $request->input('type');
                $coachAttendance->batch_id = $batchId;
                $coachAttendance->date = $attendanceDate;
                $coachAttendance->time = $request->input('time');
                $coachAttendance->status = $requestedStatus;
                $coachAttendance->homework_link = $request->input('homework_link', '') ?? '';
                $coachAttendance->recording_link = $request->input('recording_link', '') ?? '';
                $coachAttendance->chapter_name = $request->input('chapter_name', '') ?? '';

                if ($requestedStatus !== 'CANCELLED') {
                    $coachAttendance->number_of_batch_sessions = $latestCoachAttendance
                        ? $latestCoachAttendance->number_of_batch_sessions + 1
                        : 1;
                }

                $coachAttendance->save();
                $coachAttendanceId = $coachAttendance->id;

                if ($requestedStatus === 'CANCELLED') {
                    $this->adjustEndDatesBySchedule($batch, 'increase');
                }
            }

            // === Student attendances ===
            // dd($studentIds);
            foreach ($studentIds as $studentId) {
                if ($requestedStatus === 'CANCELLED') {
                    // 🚨 Force override when batch is cancelled
                    $status = 'CANCELLED';
                    $remark = 'Batch Cancelled';
                } elseif ($requestedStatus === 'COMPLETED') {
                    // ✅ Use values from request when batch is completed
                    $status = $studentStatus[$studentId] ?? 'ABSENT';
                    $remark = $request->input("studentRemark.{$studentId}", '');
                } else {
                    // Optional fallback
                    $status = 'ABSENT';
                    $remark = '';
                }

                $studentAttendance = StudentAttendance::where('student_id', $studentId)
                    ->where('batch_id', $batchId)
                    ->whereDate('date', $attendanceDate)
                    ->first();
                // dd($request->all());
                if ($studentAttendance) {
                    $studentAttendance->coach_id = $coachId;
                    $studentAttendance->type = $request->input('type');
                    $studentAttendance->status = $status;
                    $studentAttendance->homework_link = $request->input('homework_link', '') ?? '';
                    $studentAttendance->recording_link = $request->input('recording_link', '') ?? '';
                    $studentAttendance->chapter_name = $request->input('chapter_name', '') ?? '';
                    $studentAttendance->remark = $remark;
                    $studentAttendance->updated_by = $user->id;
                    $studentAttendance->save();
                } else {
                    $newSa = new StudentAttendance();
                    $newSa->student_id = $studentId;
                    $newSa->batch_id = $batchId;
                    $newSa->level_id = $request->input('level_id', $batch->level_id ?? null);
                    $newSa->date = $attendanceDate;
                    $newSa->time = $request->input('time');
                    $newSa->status = $status;
                    $newSa->remark = $remark;
                    $newSa->type = $request->input('type');
                    $newSa->coach_id = $coachId;
                    $newSa->homework_link = $request->input('homework_link', '') ?? '';
                    $newSa->recording_link = $request->input('recording_link', '') ?? '';
                    $newSa->chapter_name = $request->input('chapter_name', '') ?? '';
                    $newSa->number_of_batch_sessions = $coachAttendance->number_of_batch_sessions ?? null;
                    $newSa->created_by = $user->id;
                    $newSa->save();
                }
            }



            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Attendance recorded successfully', 'date' => $attendanceDate], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Batch Attendance Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }


    }

    protected function adjustEndDatesBySchedule(Batch $batch, string $mode = 'increase'): void
    {
        // dd('Adjusting batch end dates: ' . $mode);
        // gather scheduled weekdays
        $batchSchedules = BatchSchedule::where('batch_id', $batch->id)->get();
        $scheduledDays = $batchSchedules->pluck('weekday')->map(fn($d) => strtolower($d))->filter()->values()->toArray();
        // dd($scheduledDays);
        if (empty($scheduledDays)) {
            // No scheduled days -> throw to be caught by caller
            throw new \Exception('No scheduled days found for this batch');
        }

        $currentEnd = Carbon::parse($batch->end_date);

        if ($mode === 'increase') {
            $newBatchEnd = $this->nextScheduledDate($currentEnd, $scheduledDays);
        } else {
            $newBatchEnd = $this->previousScheduledDate($currentEnd, $scheduledDays);
        }

        // Update batch end_date
        $batch->end_date = $newBatchEnd->toDateString();
        $batch->save();

        // Update StudentBatch end_date for ACTIVE students
        $studentBatches = StudentBatch::where('batch_id', $batch->id)->where('status', 'ACTIVE')->get();
        foreach ($studentBatches as $sb) {
            $sb->end_date = $batch->end_date;
            $sb->save();
        }

        // Update each student's latest fee end_date (if exists) using same shift (next/previous scheduled day)
        $studentIds = $studentBatches->pluck('student_id')->unique()->toArray();
        foreach ($studentIds as $studentId) {
            $studentLatestFee = StudentFee::where('student_id', $studentId)->orderBy('id', 'desc')->first();
            if (! $studentLatestFee) continue;

            $feeEndDate = Carbon::parse($studentLatestFee->end_date);
            $newFeeDate = $mode === 'increase'
                ? $this->nextScheduledDate($feeEndDate, $scheduledDays)
                : $this->previousScheduledDate($feeEndDate, $scheduledDays);

            $studentLatestFee->end_date = $newFeeDate->toDateString();
            $studentLatestFee->save();
        }
    }

    /** Return next scheduled date after $fromDate based on $scheduledDays (array of 'monday', 'tuesday', ...). */
    protected function nextScheduledDate(Carbon $fromDate, array $scheduledDays): Carbon
    {
        // dd($scheduledDays);
        $dayIndexes = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6
        ];

        $fromDow = $fromDate->dayOfWeek;
        $minDiff = null;

        foreach ($scheduledDays as $day) {
            $d = strtolower($day);
            if (!isset($dayIndexes[$d])) continue;
            $target = $dayIndexes[$d];
            $diff = ($target - $fromDow + 7) % 7;
            if ($diff === 0) $diff = 7; // next week same day
            if ($minDiff === null || $diff < $minDiff) $minDiff = $diff;
        }

        $diff = $minDiff ?? 7;
        return $fromDate->copy()->addDays($diff);
    }

    protected function previousScheduledDate(Carbon $fromDate, array $scheduledDays): Carbon
    {
        // dd($scheduledDays);
        $dayIndexes = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3,
            'thursday' => 4, 'friday' => 5, 'saturday' => 6
        ];

        $fromDow = $fromDate->dayOfWeek;
        $minDiff = null;

        foreach ($scheduledDays as $day) {
            $d = strtolower($day);
            if (!isset($dayIndexes[$d])) continue;
            $target = $dayIndexes[$d];
            $diff = ($fromDow - $target + 7) % 7;
            if ($diff === 0) $diff = 7; // previous week same day
            if ($minDiff === null || $diff < $minDiff) $minDiff = $diff;
        }

        $diff = $minDiff ?? 7;
        return $fromDate->copy()->subDays($diff);
    }

    public function demoAttendance(Request $request, $coachId)
    {
        //dd($request->all());
        $rules = [
            'coach_id'    => 'required|integer',
            'type'        => 'required|string',
            'demolead_id' => 'required|integer',
            'level_id'    => 'nullable|integer',
            'batch_id'    => 'nullable|integer',
            'date'        => 'required|date',
            'time'        => 'required',
            'status'      => 'required|in:COMPLETED,CANCELLED,Student Absent',
            'remark'      => 'nullable|string',
        ];
        if ($request->input('status') === 'COMPLETED') {
            $rules['level_id'] = 'required|integer';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Slot and time validation
        $date                      = $request->input('date');
        $slot                      = $request->input('slot');
        list($slotStart, $slotEnd) = explode(' - ', $slot);
        $slotStartTime             = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $slotStart, 'Asia/Kolkata');
        $endTime                   = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $slotEnd, 'Asia/Kolkata');
        $submissionDeadline        = $endTime->addMinutes(15);
        $currentSubmissionTime     = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $request->input('time'), 'Asia/Kolkata');
        // if ($currentSubmissionTime->lt($slotStartTime) || $currentSubmissionTime->gt($submissionDeadline)) {
        //     return response()->json(['error' => 'The submission time must be within the slot time and no later than 15 minutes after the slot has ended.'], 422);
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

        $demolead = DemoLead::where('id', $request->input('demolead_id'))->first();

        $studentAttendance = StudentAttendance::where('demolead_id', $demolead->id)->first();
        
        Mail::to($demolead->user->email)->send(new DemoCompleteMail($demolead, $studentAttendance));


        return response()->json(['status' => 'success', 'message' => 'Attendance recorded successfully'], 200);
    }

    public function downloadReport(Request $request)
    {
        //dd($request->all());
        // Retrieve data from the request
        $fromDate = $request->input('fromDate');
        $toDate   = $request->input('toDate');
        $coachId  = $request->input('coachId');
        //dd($fromDate, $toDate, $coachId);

        $combinedData = [];
        $period       = new \DatePeriod(
            new \DateTime($fromDate),
            new \DateInterval('P1D'),
            (new \DateTime($toDate))->modify('+1 day')
        );
        foreach ($period as $date) {
            $dayName = $date->format('l');
            // Fetch and process Batches Data
            $batches = Batch::with(['batchSchedules' => function ($query) use ($dayName) {
                $query->where('weekday', $dayName);
            }])->where('coach_id', $coachId)->get();
            foreach ($batches as $batch) {
                foreach ($batch->batchSchedules as $schedule) {
                    $combinedData[] = [
                        'name'    => $batch->name,
                        'slot'    => Carbon::parse($schedule->from_time)->format('h:i A') . ' - ' . Carbon::parse($schedule->to_time)->format('h:i A'),
                        'status'  => $schedule->status,
                        'weekday' => $schedule->weekday,
                        'type'    => 'Batch',
                    ];
                }
            }
            // Fetch and process Demo Session Data
            $demoSessions = DemoSession::with(['demolead', 'coach', 'level'])
                ->where('coach_id', $coachId)
                ->where('date', $date->format('Y-m-d'))
                ->get();

            foreach ($demoSessions as $session) {
                list($startTime, $endTime) = explode(' - ', $session->slot);
                $formattedStartTime        = Carbon::createFromFormat('H:i:s', $startTime)->format('h:i A');
                $formattedEndTime          = Carbon::createFromFormat('H:i:s', $endTime)->format('h:i A');
                $combinedData[]            = [
                    'name'   => $session->demolead->first_name . ' ' . $session->demolead->last_name,
                    'slot'   => $formattedStartTime . ' - ' . $formattedEndTime,
                    'status' => $session->status,
                    'date'   => $session->date,
                    'type'   => 'Demo',
                ];
            }
        }
        // Sort combined data by slot
        usort($combinedData, function ($a, $b) {
            $aStartTime = explode(' - ', $a['slot'])[0];
            $bStartTime = explode(' - ', $b['slot'])[0];
            return strtotime($aStartTime) - strtotime($bStartTime);
        });

        $pdf        = App::make('dompdf');
        $folderPath = public_path('/backend/reports_pdfs/');
        if (! File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        $pdf      = PDF::loadView('Admin.CoachReports.reportpdf', ['reportData' => $combinedData]);
        $filename = 'report_' . time() . '.pdf';
        $path     = $folderPath . $filename;
        $pdf->save($path);
        return response()->json([
            'status'  => 'success',
            'pdf_url' => url('/backend/reports_pdfs/' . $filename),
        ]);
    }

    // --------- --------------------------- -------------  -------------------------- ::
    // --------- --------------------------- -------------  -------------------------- ::
}
