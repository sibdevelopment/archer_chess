<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\DemoSession;
use App\Models\Coverupclass;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use App\Models\CoachAvailability;
use App\Http\Controllers\Controller;

class CoverupclassController extends Controller
{
    public function index()
    {
        $batches = Batch::get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        return view('Admin.Coverupclass.index', compact('coaches', 'batches'));
    }

    public function data(Request $request)
    {
        $query = Coverupclass::where('id', '!=', 0)->orderBy('date', 'desc');

        if ($request->batch_id) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->from_date) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('date', '<=', $request->to_date);
        }


        if ($request->old_coach) {
            $query->where('old_coach_id', $request->old_coach);
        }

        if ($request->new_coach) {
            $query->where('new_coach_id', $request->new_coach);
        }

        return DataTables::eloquent($query)
            ->editColumn('batch', function ($coverupclass) {
            //     return $coverupclass->batch->name;
            // })
            $totalActiveStudentsBadge = '<span class="badge bg-warning fs-1">' .
                $coverupclass->batch->studentBatches()
                    ->where('coach_id', $coverupclass->batch->coach_id)
                    ->where('status', 'ACTIVE')
                    ->get()
                    ->unique('student_id')
                    ->count() . ' &nbsp; <i class="ti ti-user-shield"></i> </span>';

                $current_feesDueStudentIds = $coverupclass->batch->studentBatches()
                    ->where('coach_id', $coverupclass->batch->coach_id)
                    ->whereHas('student', function ($query) {
                        $query->where('status', 'FEESDUE');
                    })
                    ->distinct('student_id')
                    ->pluck('student_id')
                    ->toArray();



                $feesDueStudentCount = count($current_feesDueStudentIds);

                if ($coverupclass->batch->version > 1) {
                    $current_version = $coverupclass->batch->version;

                    $last_batch = Batch::where('parent_id', $coverupclass->batch->parent_id)
                        ->where('version', $current_version - 1)
                        ->first();

                    $lastfeesDueStudentIds = $last_batch->studentBatches()
                        ->where('coach_id', $coverupclass->batch->coach_id)
                    // ->unique('student_id')
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

                $recentActiveStudentBatch = $coverupclass->batch->studentBatches()
                    ->where('status', 'ACTIVE')
                    ->where('coach_id', $coverupclass->batch->coach_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Get the level name of the most recently created ACTIVE student batch
                $levelName  = $coverupclass->batch->level_id ? $coverupclass->batch->level->name : '';
                $levelBadge = $levelName ? '<span class="badge bg-primary fs-1"> (' . $levelName . ') </span>' : '';

                $coverupclassName = $coverupclass->batch->name;

                return '<div class="d-flex justify-content-between">' . $coverupclassName . ' &nbsp; ' .
                    '<div class="d-flex justify-content-end">' . $totalActiveStudentsBadge . '&nbsp;&nbsp;' . $feesDueStudentBadge . '</div>' . '</div>';
            })
            ->editColumn('change_coach', function ($coverupclass) {
                $date = $coverupclass->date;
                $batch_schedule = BatchSchedule::where('id', $coverupclass->batchschedule_id)->first();

                if (!$batch_schedule) {
                    return ''; // Avoid errors if schedule is not found
                }

                $from_time = Carbon::parse($batch_schedule->from_time); // Convert time to Carbon
                $now = Carbon::now();
                $today = Carbon::today(); // Get today's date without time

                // Hide if date is in the past OR if it's today and class time has already passed
                if ($date < $today || ($date == $today->toDateString() && $from_time < $now)) {
                    return '';
                }

                return '<a href="#" class="btn btn-primary btn-sm change_coach" data-id="' . $coverupclass->id . '"
                data-toggle="modal" data-target="#changeCoachModal"><i class="fas fa-exchange-alt"></i></a>';
            })
            ->editColumn('batchSchedule', function ($coverupclass) {
                $batchschedule = BatchSchedule::where('id', $coverupclass->batchschedule_id)->first();
                $fromTime  = $batchschedule->from_time ? Carbon::parse($batchschedule->from_time)->format('h:i A') : '';
                $toTime    = $batchschedule->to_time ? \Carbon\Carbon::parse($batchschedule->to_time)->format('h:i A') : '';
                return $batchschedule->weekday . '  (' . $fromTime . ' - ' . $toTime . ')';
            })
            ->editColumn('old_coach', function ($coverupclass) {
                return $coverupclass->oldCoach->user->first_name . ' ' . $coverupclass->oldCoach->user->last_name;
            })
            ->editColumn('new_coach', function ($coverupclass) {
                return $coverupclass->newCoach->user->first_name . ' ' . $coverupclass->newCoach->user->last_name;
            })
            ->editColumn('date', function ($coverupclass) {
                return date("d-M-Y", strtotime($coverupclass->date));
            })
            ->addIndexColumn()
            ->rawColumns(['batch', 'batchSchedule', 'old_coach', 'new_coach', 'date', 'change_coach'])
            ->setRowId('id')
            ->make(true);
    }

    public function getCoach(Request $request)
    {
        $coverup_class_id = $request->coverup_class_id;
        $coverup_class = Coverupclass::where('id',$coverup_class_id)->first();
        $schedule = BatchSchedule::where('id', $coverup_class->batchschedule_id)->first();

        // $coaches = Coach::where('status', 'ACTIVE')->get();
        // dd($coverup_class->schedule);
        $coaches = $this->getAvailableCoaches($schedule->from_time, $schedule->to_time, $schedule->weekday, $coverup_class->date, $coverup_class->new_coach_id);


        return View('Admin.Coverupclass.change_coach', compact('coaches', 'coverup_class_id'));
    }

    public function changeCoach(Request $request)
    {
        $coverupclass = Coverupclass::find($request->coverupclass_id);
        $coverupclass->new_coach_id = $request->new_coach_id;
        $coverupclass->save();

        return response()->json(['success' => 'Coach Changed Successfully', 'status' => 'success']);
    }

    public function getAvailableCoaches($from_time, $to_time, $weekday, $date, $coach_id)
    {
        // dd($from_time, $to_time, $weekday, $date, $coach_id);
        $coach          = Coach::where('id', $coach_id)->with('user')->first();
        $coachCountries = is_array($coach->country) ? $coach->country : explode(',', $coach->country);

        $dayOfWeek                   = Carbon::parse($date)->dayName;
        $coachAvailabilitiesCoachIds = CoachAvailability::where('day_of_week', $dayOfWeek)
            ->whereHas('periods', function ($query) use ($from_time, $to_time) {
                $query->where('from_period', '<=', $from_time)
                    ->where('to_period', '>=', $to_time);
            })
            ->where('status', 'ACTIVE')
            ->whereHas('coach', function ($query) {
                $query->where('status', 'ACTIVE');
            })
            ->with('coach.user')
            ->pluck('coach_id')
            ->toArray();

        // dd($coachAvailabilitiesCoachIds);

        $coachCountries = is_array($coach->country) ? $coach->country : explode(',', $coach->country);
        // dd($coachCountries);
        // dd is
        // array:2 [ // app/Http/Controllers/Admin/LeaveRequestController.php:486
        //     0 => "USA"
        //     1 => "CANADA"
        //   ]

        $coaches = Coach::whereIn('id', $coachAvailabilitiesCoachIds)->get();
        // dd($coaches);
        // dd($coaches);

        $availableCoachIds = [];
        foreach ($coaches as $coach) {
            // dd($coach);
            $isLeave = $this->checkCoachLeave($coach, $date);
            // dd($isLeave);
            if ($isLeave == 0) {
                $isBatchSchedule = $this->checkBatchSchedule($coach, $date, $weekday, $from_time, $to_time);
                // dd($isBatchSchedule);
                if ($isBatchSchedule == 0) {
                    $isDemoAssign = $this->checkDemoAssign($coach, $date, $weekday, $from_time, $to_time);
                    if ($isDemoAssign == 0) {
                        $availableCoachIds[] = $coach->id;
                    }
                }
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
    private function checkCoachLeave($coach, $date)
    {
        $isCoachLeave = LeaveRequest::where('coach_id', $coach->id)
            ->whereDate('from_date', '=', $date)
            ->where('status', 'APPROVED')
            ->first();

        if ($isCoachLeave) {
            return 1;
        }
        return 0;
    }


}
