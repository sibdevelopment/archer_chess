<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Carbon;

class HolidayController extends Controller
{
    public function index()
    {
        return view('Admin.Holidays.index');
    }

    public function data(Request $request)
    {
        //dd($request->all());
        $query = Holiday::where('id', '!=', 0)->orderBy('start_date', 'asc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->country) {
            $country = $request->country;
            $query->whereNotNull('country')->where(function ($query) use ($country) {
                $query->whereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $country . '"]'])
                    ->orWhere(function ($query) use ($country) {
                        $query->whereRaw('NOT json_valid(country)')->where('country', $country);
                    });
            });
        }

        return DataTables::eloquent($query)
            ->editColumn('name', function ($holiday) {
                return $holiday->name;
            })
            ->editColumn('date', function ($holiday) {
                return date("d-M-Y", strtotime($holiday->date));
            })
            ->editColumn('description', function ($holiday) {
                return $holiday->description;
            })
            ->addColumn('period', function ($holiday) {
                return $this->formatTime($holiday->from_time ?: '00:00:00') . ' - ' . $this->formatTime($holiday->to_time ?: '23:59:00') . ' IST';
            })
            ->editColumn('country', function ($batch) {
                $countries = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;
                return '<img src="/backend/dist/images/svgs/icon-connect.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $countries;
            })
            ->editColumn('status', function ($holiday) {
                if ($holiday->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input holiday-status-switch" type="checkbox" checked data-routekey="' . $holiday->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input holiday-status-switch" type="checkbox" data-routekey="' . $holiday->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($holiday) {
                $edit  = '<a href="' . route('admin.holidays.edit', ['holiday' => $holiday->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-holiday-id="' . $holiday->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                return $edit . '  ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'date', 'description', 'period', 'country', 'action', 'status'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        return view('Admin.Holidays.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $holiday = new Holiday;
        $holiday->fill($this->holidayData($request));
        $holiday->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Holiday Created Successfully',
            'slider' =>  $holiday
        ], 201);
    }

    public function edit(Holiday $holiday)
    {
        return view('Admin.Holidays.form', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate($this->rules, $this->customMessages);
        $holiday->fill($this->holidayData($request));
        $holiday->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Holiday Updated Successfully',
            'slider' =>  $holiday
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $holiday = Holiday::findByKey($request->route_key);
        $holiday->status = $request->status;
        $holiday->save();

        return response()->json([
            'status' => 'success',
            'message' => $holiday->name . ' has been marked ' . $holiday->status . ' successfully',
            'holiday' => $holiday
        ], 201);
    }

    public function destroy(Request $request, Holiday $holiday)
    {
        //dd($request->all());
        $holiday = Holiday::where('id', $request->holiday_id)->first();
        if ($holiday) {
            $holiday->delete();
            return response()->json([
                'success' => 'holiday Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'holiday not found',
            ], 404);
        }
    }

    private $rules = [
        'country' => 'required|array|min:1',
        'name' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'from_time' => 'nullable|date_format:H:i',
        'to_time' => 'nullable|date_format:H:i|after:from_time',
    ];
    private $customMessages = [
        'country.required' => 'Please select country.',
        'name.required' => 'The Name is required to fill.',
        'start_date.required' => 'The Date is required to select.',
        'end_date.after_or_equal' => 'End date should be same or after start date.',
        'to_time.after' => 'To time must be after from time.',
    ];

    private function holidayData(Request $request): array
    {
        $data = $request->all();
        $data['end_date'] = $request->input('end_date') ?: $request->input('start_date');
        $data['from_time'] = $request->input('from_time') ?: '00:00';
        $data['to_time'] = $request->input('to_time') ?: '23:59';
        $data['timezone'] = $request->input('timezone') ?: 'Asia/Kolkata';

        return $data;
    }

    private function formatTime(?string $time): string
    {
        return Carbon::parse($time ?: '00:00:00')->format('h:i A');
    }
}
