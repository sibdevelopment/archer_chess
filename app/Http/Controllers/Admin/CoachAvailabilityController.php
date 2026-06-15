<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Coach;
use App\Models\CoachAvailability;
use App\Models\CoachAvailabilityPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachAvailabilityController extends Controller
{
    public function index(Coach $coach)
    {
        $coaches = Coach::all();
        return view('Admin.CoachAvailabilities.index', compact('coaches','coach'));
    }

    public function data(Coach $coach, Request $request)
    {
        $query = CoachAvailability::with('periods')
            ->where('coach_id', $coach->id)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')");

        return DataTables::eloquent($query)
            ->editColumn('coach_id', function ($coachavailability) {
                return $coachavailability->coach->user->first_name;
            })
            ->editColumn('day_of_week', function ($coachavailability) {
                return ucfirst($coachavailability->day_of_week);
            })
            ->editColumn('periods', function ($coachavailability) {
                $periodsHtml = '';
                foreach ($coachavailability->periods as $period) {
                    $fromTime = \Carbon\Carbon::createFromFormat('H:i:s', $period->from_period)->format('g:i A');
                    $toTime = \Carbon\Carbon::createFromFormat('H:i:s', $period->to_period)->format('g:i A');
                    $periodsHtml .= "<div>{$fromTime} - {$toTime}</div>";
                }
                return $periodsHtml;
            })
            ->editColumn('status', function ($coachavailability) {
                if ($coachavailability->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input coachavailability-status-switch" type="checkbox" checked data-routekey="' . $coachavailability->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input coachavailability-status-switch" type="checkbox" data-routekey="' . $coachavailability->route_key . '"/></div>';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['coach_id', 'status', 'periods'])
            ->setRowId('id')
            ->make(true);
    }


    public function list(Request $request)
    {
        if ($request->coach_id) {
            $coach_availabilities = CoachAvailability::where('coach_id', $request->coach_id)->where('status', 'ACTIVE')->with('coach')->get();
        } elseif ($request->client_ids) {
            if (is_string($request->client_ids)) {
                $request->client_ids = explode(',', $request->client_ids);
            }
            $coach_availabilities = CoachAvailability::whereIn('coach_id', $request->client_ids)->where('status', 'ACTIVE')->with('coach')->get();

        } else {
            $coach_availabilities = CoachAvailability::with('coach')->get();
        }
        return response()->json([
            'status' => 'success',
            'list' => $coach_availabilities
        ], 200);
    }

    public function create(Coach $coach)
    {
        return view('Admin.CoachAvailabilities.form', compact('coach'));
    }
    
    public function addDay(Request $request)
    {
        $coachavailability = new CoachAvailability;
        $coachavailability->save();
        return view('Admin.CoachAvailabilities.dayofweek', compact('coachavailability'));
    }
 
    public function addPeriod(Request $request)
    {
        //  dd($request->all());
        $uniqueRowId = $request->unique_row_id;
        $coachavailability = CoachAvailability::find($uniqueRowId);
        if (!$coachavailability) {
            return response()->json(['status' => 'error', 'message' => 'Category property not found'], 404);
        }
        $coachavailabilityperiod = new CoachAvailabilityPeriod;
        $coachavailabilityperiod->availability_id = $coachavailability->id;
        //$coachavailabilityperiod->save();
        return view('Admin.CoachAvailabilities.period', compact('coachavailability', 'coachavailabilityperiod'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Store Days ::
        foreach ($request->day_of_week as $key => $day) {
            $coachAvailability = CoachAvailability::firstOrCreate([
                'coach_id' => $request->coach_id,
                'day_of_week' => $day,
            ], [
                'status' => 'ACTIVE' // Assuming 'status' is the column name in your table
            ]);
            // Store Periods ::
            if (isset($request->from_period[$key]) && isset($request->to_period[$key])) {
                foreach ($request->from_period[$key] as $periodKey => $fromTime) {
                    $toTime = $request->to_period[$key][$periodKey];
                    $coachAvailabilityPeriod = new CoachAvailabilityPeriod;
                    $coachAvailabilityPeriod->availability_id = $coachAvailability->id;
                    $coachAvailabilityPeriod->from_period = $fromTime;
                    $coachAvailabilityPeriod->to_period = $toTime;
                    $coachAvailabilityPeriod->save();
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'CoachAvailability Created Successfully',
        ], 201);
    }

    public function editAll(Request $request, Coach $coach)
    {
        return view('Admin.CoachAvailabilities.form', compact('coach'));
    }

    public function editDay(Request $request, Coach $coach)
    {
        $coachavailability = CoachAvailability::find($request->coachday_id);
        return view('Admin.CoachAvailabilities.dayofweek', compact('coachavailability'));
    }

    public function editPeriod(Request $request)
    {
        $coachavailability = CoachAvailability::find($request->availability_id);
        $coachavailabilityperiod = CoachAvailabilityPeriod::find($request->period_id);
        if (!$coachavailability || !$coachavailabilityperiod) {
            return back()->withErrors(['msg' => 'Coach Availability or Period not found.']);
        }
        return view('Admin.CoachAvailabilities.period', compact('coachavailability', 'coachavailabilityperiod'));
    }

    public function updateAll(Request $request)
    {
        // Extract Request Data
        $coachId = $request->input('coach_id');
        $daysOfWeek = $request->input('day_of_week', []);
        $fromPeriods = $request->input('from_period', []);
        $toPeriods = $request->input('to_period', []);
        $deletedDays = $request->input('deleted_days', []);
        $deletedPeriods = $request->input('deleted_periods', []);
        // Ensure arrays are properly formatted
        $daysOfWeek = is_array($daysOfWeek) ? $daysOfWeek : (array)$daysOfWeek;
        $fromPeriods = is_array($fromPeriods) ? $fromPeriods : (array)$fromPeriods;
        $toPeriods = is_array($toPeriods) ? $toPeriods : (array)$toPeriods;
        $deletedDays = is_array($deletedDays) ? $deletedDays : explode(',', $deletedDays); 
        $deletedPeriods = is_array($deletedPeriods) ? $deletedPeriods : explode(',', $deletedPeriods); 
        // Update or Create Availability
        foreach ($daysOfWeek as $index => $day) {
            $coachAvailability = CoachAvailability::updateOrCreate([
                'coach_id' => $coachId,
                'day_of_week' => $day,
            ], [
                'status' => 'ACTIVE' 
            ]);
            // Update or Create Periods
            if (isset($fromPeriods[$index]) && is_array($fromPeriods[$index])) {
                foreach ($fromPeriods[$index] as $periodIndex => $fromTime) {
                    if (isset($toPeriods[$index][$periodIndex])) {
                        $toTime = $toPeriods[$index][$periodIndex];
                        CoachAvailabilityPeriod::updateOrCreate([
                            'id' => $periodIndex, // Use the period index as the id
                            'availability_id' => $coachAvailability->id,
                        ], [
                            'from_period' => $fromTime,
                            'to_period' => $toTime,
                        ]);
                    }
                }
            }
        }
        // Handle Deletions
        if (!empty($deletedDays)) {
            $validDeletedDays = array_filter($deletedDays, function($value) {
                return is_numeric($value) && $value > 0;
            });
            if (!empty($validDeletedDays)) {
                CoachAvailability::whereIn('id', $validDeletedDays)->delete();
            }
        }
        if (!empty($deletedPeriods)) {
            $validDeletedPeriods = array_filter($deletedPeriods, function($value) {
                return is_numeric($value) && $value > 0;
            });
            if (!empty($validDeletedPeriods)) {
                CoachAvailabilityPeriod::whereIn('id', $validDeletedPeriods)->delete();
            }
        }
        // Return Response
        return response()->json([
            'status' => 'success',
            'message' => 'Coach availability updated successfully.',
        ]);
    }

    public function destroy(CoachAvailability $coachavailability)
    {
        // Implement the logic for deleting the CoachAvailability
    }

    public function changeStatus(Request $request)
    {
        $coachavailability = CoachAvailability::findByKey($request->route_key);
        $coachavailability->status = $request->status;
        $coachavailability->save();

        return response()->json([
            'status' => 'success',
            'message' => $coachavailability->day_of_week . ' has been marked ' . $coachavailability->status . ' successfully',
            'coachavailability' => $coachavailability
        ], 201);
    }

    private $rules = [
        'coach_id' => 'required',
    ];

    private $customMessages = [
        'coach_id.required' => 'CoachAvailability ID is required',
    ];
}

