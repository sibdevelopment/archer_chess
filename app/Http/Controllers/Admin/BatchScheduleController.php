<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Coach;
use App\Models\Batch;
use App\Models\BatchSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CoachAvailability;
use App\Http\Controllers\Controller;

class BatchScheduleController extends Controller
{
    public function index(Batch $batch)
    {
        $batchs = Batch::all();
        return view('Admin.BatchSchedules.index', compact('batchs','batch'));
    }

    public function data(Batch $batch, Request $request)
    {
        $query = BatchSchedule::where('batch_id', $batch->id)->orderBy('created_at');
        //dd($batch, $query);

        return DataTables::eloquent($query)
            ->editColumn('date', function ($batch_schedule) {
                return date('d-M-Y', strtotime($batch_schedule->date));
            })
            ->editColumn('weekday', function ($batch_schedule) {
                return $batch_schedule->weekday;
            })
            ->editColumn('from_time', function ($batch_schedule) {
                return date("g:i a", strtotime($batch_schedule->from_time));
            })
            ->editColumn('to_time', function ($batch_schedule) {
                return date("g:i a", strtotime($batch_schedule->to_time));
            })
            ->editColumn('status', function ($batch_schedule) {
                if ($batch_schedule->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input batch_schedule-status-switch" type="checkbox" checked data-routekey="' . $batch_schedule->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input batch_schedule-status-switch" type="checkbox" data-routekey="' . $batch_schedule->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($batch_schedule) use ($batch) {
                $edit = '<a href="' . route('admin.batchs.batch_schedules.edit', ['batch' => $batch->id, 'batch_schedule' => $batch_schedule->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i> &nbsp; Edit</a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['batch_id', 'action', 'status', 'to_period','from_period','day_of_week','coach_id'])
            ->setRowId('id')
            ->make(true);
    }

    public function list(Request $request)
    {
        if ($request->batch_id) {
            $batchs = BatchSchedule::where('batch_id', $request->batch_id)->where('status', 'ACTIVE')->with('batch')->get();
        } elseif ($request->batch_ids) {
            if (is_string($request->batch_ids)) {
                $request->batch_ids = explode(',', $request->batch_ids);
            }
            $batchs = BatchSchedule::whereIn('batch_id', $request->batch_ids)->where('status', 'ACTIVE')->with('batch')->get();

        } else {
            $batchs = BatchSchedule::with('batch')->get();
        }
        return response()->json([
            'status' => 'success',
            'list' => $batchs
        ], 200);
    }

    public function create(Batch $batch)
    {
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();
        $slots = CoachAvailability::where('status', 'ACTIVE')->get();
        return view('Admin.BatchSchedules.form', compact('batch', 'coaches', 'slots'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);
        $batch = Batch::find($request->batch_id);
        $batch_schedule = new BatchSchedule;
        $batch_schedule->batch_id = $batch->id;
        $batch_schedule->fill($request->all());
        $batch_schedule->save();

        return response()->json([
            'status' => 'success',
            'message' => 'BatchSchedule Created Successfully',
            'batch_schedule' => $batch_schedule
        ], 201);
    }

    public function edit(Batch $batch, BatchSchedule $batch_schedule)
    {
        $batchs = Batch::all();
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();

        return view('Admin.BatchSchedules.form', compact('batch_schedule', 'batchs', 'batch', 'coaches'));
    }

    public function update(Batch $batch, Request $request, BatchSchedule $batch_schedule)
    {
        $request->validate($this->rules, $this->customMessages);
        $batch_schedule->fill($request->all());
        $batch_schedule->save();

        // Return a success response in JSON format
        return response()->json([
            'status' => 'success',
            'message' => 'BatchSchedule Updated Successfully',
            'batch_schedule' => $batch_schedule
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $batch_schedule = BatchSchedule::findByKey($request->route_key);
        $batch_schedule->status = $request->status;
        $batch_schedule->save();

        return response()->json([
            'status' => 'success',
            'message' => $batch_schedule->title . ' has been marked ' . $batch_schedule->status . ' successfully',
            'batch_schedule' => $batch_schedule
        ], 201);
    }


    private $rules = [
        'batch_id' => 'required',
    ];

    private $customMessages = [
        'batch_id.required' => 'BatchSchedule ID is required',
    ];
}
