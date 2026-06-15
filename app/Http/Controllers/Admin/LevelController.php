<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;

class LevelController extends Controller
{
    public function index(){
        return view('Admin.Levels.index');
    }

    public function data(Request $request)
    {
      $query = Level::whereNotIn('id', [0, 22, 23])->orderByDesc('id');


        if ($request->status) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->editColumn('name', function ($level) {
                return  '<img src="/backend/dist/images/svgs/icon-dd-lifebuoy.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $level->name;
            })
            ->editColumn('index', function ($level) {
                return $level->index;
            })
            ->editColumn('status', function ($level) {
                if ($level->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input level-status-switch" type="checkbox" checked data-routekey="' . $level->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input level-status-switch" type="checkbox" data-routekey="' . $level->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($level) {
                $edit  = '<a href="'.route('admin.levels.edit',['level' => $level->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-level-id="' . $level->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                return $edit. '  ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['status','name','index','action'])
            ->setRowId('id')
            ->make(true);
    }

    public function create()
    {
        $nextIndex = Level::all()->max(function ($project) {
            return (int) $project->index;
        }) + 1;
        return view('Admin.Levels.form', compact( 'nextIndex'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $level = new Level;
        $level->fill($request->all());
        $level->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Level Created Successfully',
            'level' =>  $level
        ], 201);
    }

    public function edit(Level $level){
        return view('Admin.Levels.form', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate($this->rules, $this->customMessages);
        $level->fill($request->all());
        $level->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Level Created Successfully',
            'level' =>  $level
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $level = Level::findByKey($request->route_key);
        $level->status = $request->status;
        $level->save();

        return response()->json([
            'status' => 'success',
            'message' => $level->name . ' has been marked ' . $level->status . ' successfully',
            'level' => $level
        ], 201);
    }

    public function destroy(Request $request, Level $level)
    {
        //dd($request->all());
        $level = Level::where('id', $request->level_id)->first();
        if ($level) {
            $level->delete();
            return response()->json([
                'success' => 'level Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'level not found',
            ], 404);
        }
    }

    private $rules = [
        'name' => 'required|unique:levels',
    ];

    private $customMessages = [
        'name.required' => 'The name field is required.',
        'name.unique' => 'The name has already been taken.',
    ];

}
