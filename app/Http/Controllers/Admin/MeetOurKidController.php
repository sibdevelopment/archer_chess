<?php

namespace App\Http\Controllers\Admin;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\MeetOurKid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetOurKidController extends Controller
{
    public function index()
    {
        return view('Admin.MeetOurKids.index');
    }
    public function data(Request $request){
        $query = MeetOurKid::orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('image', function ($meetourkid) {
                return $meetourkid->image;
            })
            ->editColumn('title', function ($meetourkid) {
                return $meetourkid->title;
            })
            ->addColumn('action', function ($meetourkid) {
                $edit  = '<a href="'.route('admin.meet-our-kids.edit',['meet_our_kid' => $meetourkid->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addColumn('status', fn($meetourkid) => 
                '<div class="form-check form-switch"><input class="form-check-input meetourkids-status-switch" type="checkbox" data-routekey="' . $meetourkid->route_key . '"' . ($meetourkid->status == 'ACTIVE' ? ' checked' : '') . '/></div>'
            )   
           ->addIndexColumn()
           ->rawColumns(['image','title','action','status'])->setRowId('route_key')->make(true);
    }

    public function create()
    {
        return view('Admin.MeetOurKids.form');
    }

    public function store(Request $request,MeetOurKid $meetourkid)
    {
        $request->validate($this->rules, $this->customMessages);
        $meetourkid->fill($request->all());

        if ($request->hasFile('image')) {
            $meetourkid->image = Storage::disk('public')->put('photos', $request->file('image'));
        }
        $meetourkid->save();
        return response()->json([   
            'status' => 'success',
            'message' => 'Details Created Successfully',
        ],201);
    }

    public function edit(MeetOurKid $meetourkid)
    {
        return view('Admin.MeetOurKids.form', compact('meetourkid'));
    }

    public function update(Request $request, MeetOurKid $meetourkid)
    {
        $this->rules['image'] = 'nullable|mimes:jpeg,png,jpg|dimensions:width=304,height=304|max:20480';
        $request->validate($this->rules, $this->customMessages);
        $meetourkid->fill($request->all());
        if ($request->hasFile('image')) {
            if ($meetourkid->image) {
                Storage::disk('public')->delete($meetourkid->image);
            }
            $meetourkid->image = Storage::disk('public')->put('images', $request->file('image'));
        }
        $meetourkid->save();

        return response()->json([   
            'status' => 'success',
            'message' => 'Details Updated Successfully',
        ],201);
    }

    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $meetourkid = MeetOurKid::findByKey($request->route_key);
        $meetourkid->update(['status' => $request->status]);
        return response()->json([
            'status' => 'success',
            'message' => 'Status has been marked ' . $meetourkid->status . ' successfully',
        ], 200);
    }

    private $rules = [
        'image' => 'required|mimes:jpeg,png,jpg|dimensions:width=304,height=304|max:20480',
    ];
    
    private $customMessages = [
        'image.required' => 'Photo is required.',
        'image.mimes' => 'Only JPEG, PNG, or JPG formats are allowed.',
        'image.max' => 'Photo size must not exceed 20MB.',
        'image.dimensions' => 'Photo dimensions must be 304x304 pixels.',
    ];
}
