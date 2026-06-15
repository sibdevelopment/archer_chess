<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timezone;
use DataTables;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TimezoneController extends Controller
{
    public function index()
    {
        return view('Admin.Timezones.index');
    }

    public function data(Request $request)
    {
        $query = Timezone::where('id', '!=', 0)->orderByDesc('id');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->country) {
            $query->where('country', $request->country);
        }
        if ($request->weekday) {
            $query->where('weekday', $request->weekday);
        }

        return DataTables::eloquent($query)

            ->editColumn('country', function ($timezone) {
                return '<img src="/backend/dist/images/svgs/icon-connect.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $timezone->country;;
            })
            ->editColumn('weekday', function ($timezone) {
                return '<img src="/backend/dist/images/svgs/icon-master-card-2.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $timezone->weekday;
            })
            ->editColumn('timezone', function ($timezone) {
                return $timezone->timezone;
            })
            ->editColumn('india_time', function ($timezone) {
                if (is_null($timezone->india_start_time) || is_null($timezone->india_end_time)) {
                    return null;
                }
                return date('h:i A', strtotime($timezone->india_start_time)) . ' - ' . date('h:i A', strtotime($timezone->india_end_time));
            })
            ->editColumn('country_time', function ($timezone) {
                if (is_null($timezone->country_start_time) || is_null($timezone->country_end_time)) {
                    return null;
                }
                return date('h:i A', strtotime($timezone->country_start_time)) . ' - ' . date('h:i A', strtotime($timezone->country_end_time));
            })
            ->editColumn('status', function ($timezone) {
                if ($timezone->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input timezone-status-switch" type="checkbox" checked data-routekey="' . $timezone->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input timezone-status-switch" type="checkbox" data-routekey="' . $timezone->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($timezone) {
                $edit = '<a href="' . route('admin.timezones.edit', ['timezone' => $timezone->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete" class="badge bg-danger fs-1 delete-btn" data-timezone-id="' . $timezone->id . '"><i class="fa fa-trash fs-1"></i></a>';
                }

                return $edit . '  ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'country', 'weekday'])
            ->setRowId('id')
            ->make(true);
    }

    public function processDateTimeZone(Request $request)
    {


        $targetTimeZone = $request->input('timeZone');
        $indiaStartTime = $request->input('india_start_time');
        $indiaEndTime = $request->input('india_end_time');
        $sourceTimeZone = new DateTimeZone('Asia/Kolkata');

        // --------------------------------- Start Time ---------------------------------
        if ($indiaStartTime == '') {
            $indiaStartTime = '00:00';
        }

        // Use Carbon::parse() for flexibility
        try {
            $starttime = Carbon::parse($indiaStartTime, 'Asia/Kolkata');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid start time format.'], 400);
        }

        // Convert start time to the target timezone
        $convertStartTimeInTimeZone = $starttime->setTimezone(convertTimeZoneString($targetTimeZone));
        $convertedStartTime = $convertStartTimeInTimeZone->format('Y-m-d H:i:s');

        // --------------------------------- End Time ---------------------------------
        if ($indiaEndTime == '') {
            $indiaEndTime = '00:00';
        }

        // Use Carbon::parse() for flexibility
        try {
            $endtime = Carbon::parse($indiaEndTime, 'Asia/Kolkata');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid end time format.'], 400);
        }

        // Convert end time to the target timezone
        $convertEndTimeInTimeZone = $endtime->setTimezone(convertTimeZoneString($targetTimeZone));
        $convertedEndTime = $convertEndTimeInTimeZone->format('Y-m-d H:i:s');

        return response()->json([
            'convertedStartTime' => $convertedStartTime,
            'convertedEndTime' => $convertedEndTime,
            'targetTimeZone' => $targetTimeZone,
        ]);
    }



    public function create()
    {
        return view('Admin.Timezones.form');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate($this->rules, $this->customMessages);

        // Check if the timezone for the given country, timezone, and weekday already exists
        // $existingTimezone = Timezone::where('country', $request->input('country'))
        //     ->where('timezone', $request->input('timezone'))
        //     ->where('weekday', $request->input('weekday'))
        //     ->first();
        // if ($existingTimezone) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'The timezone for this country, timezone, and weekday already exists.',
        //     ], 422);
        // }

        // Create a new timezone record
        $timezone = new Timezone;
        $timezone->fill($request->all());
        $timezone->status = 'ACTIVE';
        $timezone->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Timezone Created Successfully',
            'timezone' => $timezone,
        ], 201);
    }

    public function edit(Timezone $timezone)
    {
        return view('Admin.Timezones.form', compact('timezone'));
    }

    public function update(Request $request, Timezone $timezone)
    {
        // Validate the request
        $request->validate($this->rules, $this->customMessages);

        // Check if the timezone for the given country, timezone, and weekday already exists, excluding the current record
        // $existingTimezone = Timezone::where('country', $request->input('country'))
        //     ->where('timezone', $request->input('timezone'))
        //     ->where('weekday', $request->input('weekday'))
        //     ->where('id', '!=', $timezone->id)
        //     ->first();
        // if ($existingTimezone) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'The timezone for this country, timezone, and weekday already exists.',
        //     ], 422);
        // }

        // Update the timezone record
        $timezone->fill($request->all());
        $timezone->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Timezone Updated Successfully',
            'timezone' => $timezone,
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $timezone = Timezone::findByKey($request->route_key);
        $timezone->status = $request->status;
        $timezone->save();

        return response()->json([
            'status' => 'success',
            'message' => $timezone->name . ' has been marked ' . $timezone->status . ' successfully',
            'timezone' => $timezone,
        ], 201);
    }

    public function destroy(Request $request, Timezone $timezone)
    {
        //dd($request->all());
        $timezone = Timezone::where('id', $request->timezone_id)->first();
        if ($timezone) {
            $timezone->delete();
            return response()->json([
                'success' => 'Timezone Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'Timezone not found',
            ], 404);
        }
    }

    public function getTimezones(Request $request)
    {
        $country = $request->query('country');
        $timezones = getTimezones();

        // dd($timezones);

        if ($country && isset($timezones[$country])) {
            return response()->json([$country => $timezones[$country]]);
        }
        return response()->json($timezones);
    }

    private $rules = [
        'country' => 'required',
        'weekday' => 'required',
        'timezone' => 'required',
        'india_start_time' => 'required',
        'india_end_time' => 'required|after:india_start_time',
        'country_start_time' => 'required',
        'country_end_time' => 'required', // Removed 'after:country_start_time'
    ];

    private $customMessages = [
        'country.required' => 'The country field is required.',
        'weekday.required' => 'The weekday field is required.',
        'timezone.required' => 'The timezone field is required.',
        'india_start_time.required' => 'The India start time field is required.',
        'india_start_time.date_format' => 'The India start time must be in the format HH:MM.',
        'india_end_time.required' => 'The India end time field is required.',
        'india_end_time.date_format' => 'The India end time must be in the format HH:MM.',
        'india_end_time.after' => 'The India end time must be after the India start time.',
        'country_start_time.required' => 'The country start time field is required.',
        'country_start_time.date_format' => 'The country start time must be in the format HH:MM.',
        'country_end_time.required' => 'The country end time field is required.',
        'country_end_time.date_format' => 'The country end time must be in the format HH:MM.',
        // Removed 'country_end_time.after' custom message
    ];
}
