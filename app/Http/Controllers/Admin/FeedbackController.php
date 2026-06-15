<?php

namespace App\Http\Controllers\Admin;
use DataTables;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{

    public function index()
    {
        return view('Admin.Feedback.index');
    }
    public function data(Request $request){
        $query = Feedback::where('id','!=',0)->orderBy('id','desc');

        return DataTables::eloquent($query)

            ->editColumn('full_name', function ($feedback) {
                return isset($feedback->full_name) ? $feedback->full_name : 'N/A';
            })
            ->editColumn('coach', function ($feedback) {
                return isset($feedback->coach->user) ? $feedback->coach->user->full_name: '';
            })
            ->editColumn('feedback', function ($feedback) {
                return !empty($feedback->feedback) ?  mb_strimwidth($feedback->feedback, 0, 97, '...') : '';
            })
            ->editColumn('mobile', function ($feedback) {
                return isset($feedback->mobile) ? $feedback->mobile : 'N/A';
            })
             ->addColumn('action', function ($feedback) {
                $show = '<a href="'.route('admin.feedbacks.show',['feedback' => $feedback->route_key]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="feedbacks" data-title="Feedback" data-route-key="'.$feedback->route_key.'"><i class="fa fa-eye"></i></a>';
                return $show;
            })
           ->addIndexColumn()
           ->rawColumns(['full_name','coach','feedback','action'])->setRowId('id')->make(true);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);
        $user = Auth::user();
        $feedback = new Feedback;
        $feedback->fill($request->all());
        $feedback->user_id == $user->id;
        $feedback->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback Created Successfully',
            'feedback' =>  $feedback
        ], 201);
    }


    public function show(Feedback $feedback)
    {
        return view('Admin.Feedback.show', compact('feedback'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, Feedback $feedback)
    {
       $request->validate($this->rules, $this->customMessages);
        $user = Auth::user();
        $feedback->fill($request->all());
        $feedback->user_id == $user->id;
        $feedback->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Feedback Update Successfully',
            'feedback' =>  $feedback
        ], 201);
    }


    public function destroy($id)
    {
        //
    }

    private $rules = [
        'full_name' => 'required',
        'coach_id' => 'required',
        'feedback' => 'required',
    ];

    private $customMessages = [
        'full_name.required' => 'The  name is required.',
        'coach_id.required' => 'The coach is required.',
        'feedback.required' => 'The feedback field is required.',
    ];

}
