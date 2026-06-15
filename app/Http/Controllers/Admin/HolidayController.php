<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;

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
            ->rawColumns(['name', 'date', 'description', 'country', 'action', 'status'])
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
        $holiday->fill($request->all());
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
        $holiday->fill($request->all());
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
        'name' => 'required',
        'start_date' => 'required',
    ];
    private $customMessages = [
        'name .required' => 'The Name is required to fill .',
        'start_date.required' => 'The Date is required to select .',
    ];
}
