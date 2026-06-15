<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class EnquiryController extends Controller
{
    public function index(){
        return view('Admin.Enquiries.index');
    }

    public function data(Request $request){
        $query = Enquiry::where('id','!=',0)->orderBy('created_at', 'desc');
        
        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        return DataTables::eloquent($query)
            ->editColumn('datetime', function ($enquiry) {
                return toIndianDateTime($enquiry->created_at);
            }) 
            ->editColumn('first_name', function ($enquiry) {
                return $enquiry->first_name;
            }) 
            ->editColumn('last_name', function ($enquiry) {
                return $enquiry->last_name;
            }) 
            ->editColumn('email', function ($enquiry) {
                return $enquiry->email;
            }) 
            ->editColumn('mobile', function ($enquiry) {
                return $enquiry->mobile;
            }) 
            ->editColumn('message', function ($enquiry) {
                return mb_strimwidth($enquiry->message, 0, 97, '...');
            }) 
            ->addColumn('action', function ($enquiry) {
                $show = '<a href="'.route('admin.enquiries.show',['enquiry' => $enquiry->route_key]).'" class="badge bg-info fs-1 modal-one-btn" data-entity="enquiries" data-title="Enquiry" data-route-key="'.$enquiry->route_key.'"><i class="fa fa-eye"></i></a>';
                return $show;
            })  
            ->addColumn('delete', function ($enquiry) {

                $delete = '<a href="javascript:void(0);" class="badge bg-danger fs-1 delete-enquiry" data-id="'.$enquiry->id.'" data-url="'.route('admin.enquiries.destroy', $enquiry->id).'"> <i class="fa fa-trash"></i></a>';

                return $delete;
            })    
           ->addIndexColumn()
           ->rawColumns(['datetime','first_name','last_name','email','mobile','status','action', 'delete'])->setRowId('id')->make(true);
    }

    public function list(){
		$enquiries = Enquiry::all();
        return response()->json([   
            'status' => 'success',
            'list' => $enquiries
        ],200);   
	}

    public function show(Enquiry $enquiry){
        return view('Admin.Enquiries.show',compact('enquiry'));
    }
    
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Enquiry deleted successfully.'
        ]);
    }

}
