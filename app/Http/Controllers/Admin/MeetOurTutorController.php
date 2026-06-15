<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\MeetOurTutor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MeetOurTutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.MeetOurTutors.index');
    }

    public function data(Request $request){
        $query = MeetOurTutor::orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('image', function ($meetourtutor) {
                return $meetourtutor->image;
            })
            ->editColumn('name', function ($meetourtutor) {
                return $meetourtutor->name;
            })
            ->editColumn('designation', function ($meetourtutor) {
                return $meetourtutor->designation;
            })
             ->editColumn('rating', function ($meetourtutor) {
                return $meetourtutor->rating;
            })
            ->addColumn('action', function ($meetourtutor) {
                $edit  = '<a href="'.route('admin.meet-our-tutors.edit',['meet_our_tutor' => $meetourtutor->route_key]).'" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addColumn('status', fn($meetourtutor) => 
                '<div class="form-check form-switch"><input class="form-check-input meetourtutors-status-switch" type="checkbox" data-routekey="' . $meetourtutor->route_key . '"' . ($meetourtutor->status == 'ACTIVE' ? ' checked' : '') . '/></div>'
            )   
           ->addIndexColumn()
           ->rawColumns(['image','title','action','status'])->setRowId('route_key')->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.MeetOurTutors.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $meetOurTutor = new MeetOurTutor();
        $meetOurTutor->fill($request->all());
        if ($request->hasFile('image')) {
            $meetOurTutor->image = Storage::disk('public')->put('images', $request->file('image'));
        }
        $meetOurTutor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tutor Created Successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MeetOurTutor $meetourtutor)
    {
        return view('Admin.MeetOurTutors.form', compact('meetourtutor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeetOurTutor $meetourtutor)
    {
        $this->rules['image'] = 'nullable|mimes:jpeg,png,jpg.svg|max:20480'; 

        $request->validate($this->rules, $this->customMessages);

        $meetourtutor->fill($request->all());
        if ($request->hasFile('image')) {
            if ($meetourtutor->image) {
                Storage::disk('public')->delete($meetourtutor->image);
            }
            $meetourtutor->image = Storage::disk('public')->put('images', $request->file('image'));
        }
        $meetourtutor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tutor Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        $meetourtutor = MeetOurTutor::findByKey($request->route_key);
        $meetourtutor->update(['status' => $request->status]);
        return response()->json([
            'status' => 'success',
            'message' => 'Status has been marked ' . $meetourtutor->status . ' successfully',
        ], 200);
    }



    private $rules = [
        'name' => 'required|string|max:255',
        'image' => 'required|mimes:jpeg,png,jpg.svg|max:20480',
        'designation' => 'required',
        'rating' => 'required|numeric',
    ];

    private $customMessages = [
        'name.required' => 'Name is required',
        'image.mimes' => 'Image must be a file of type: jpeg, png, jpg.',
        'image.dimensions' => 'Image dimensions must be 304x304 pixels.',
        'image.max' => 'Image size must not exceed 20MB.',
        'designation.required' => 'Designation is required',
        'rating.required' => 'Rating is required',
        'rating.numeric' => 'Rating must be a number',
    ];
}
