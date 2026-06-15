<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Paymentlevel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentLevelController extends Controller
{
    /**
     * Display a listing of the payment levels.
     */
    public function index()
    {
        $level_ids = Level::where('status', 'ACTIVE')->get(); // Fetch active levels
        return view('Admin.PaymentLevels.index', compact('level_ids'));
    }

    /**
     * Fetch the payment levels data for DataTables.
     */
    public function data(Request $request)
    {
        $query = Paymentlevel::orderBy('sequence', 'ASC');

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        return DataTables::eloquent($query)
            ->editColumn('name', fn($paymentlevel) => $paymentlevel->name)
            ->editColumn('fees', fn($paymentlevel) => $paymentlevel->fees)
            ->editColumn('level_id', fn($paymentlevel) =>
             $paymentlevel->level->name ?? 'N/A')
            ->editColumn('sequence', fn($paymentlevel) => $paymentlevel->sequence)
            ->editColumn('status', function ($paymentlevel) {
                $checked = $paymentlevel->status === 'ACTIVE' ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input paymentlevel-status-switch" type="checkbox" ' . $checked . ' data-routekey="' . $paymentlevel->id . '"/>
                </div>';
            })
            ->addColumn('action', function ($paymentlevel) {
                return '<a href="' . route('admin.paymentlevels.edit', $paymentlevel->id) . '" class="badge bg-warning fs-1">
                    <i class="fa fa-edit"></i>
                </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action', 'level_id','sequence'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Return all payment levels as a JSON response.
     */
    public function list()
    {
        $paymentlevels = Paymentlevel::all();
        return response()->json([
            'status' => 'success',
            'list'   => $paymentlevels,
        ], 200);
    }

    /**
     * Show the form for creating a new payment level.
     */
    public function create()
    {
        $level_ids = Level::where('status', 'ACTIVE')->get();
        return view('Admin.PaymentLevels.form', compact('level_ids'));
    }

    /**
     * Store a newly created payment level in the database.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'            => 'required',
            'canada_fees'     => 'required|numeric',
            'sequence'        => 'required|numeric|unique:paymentlevels,sequence',
            'level_id'        => 'required|exists:levels,id',
            'usa_fees'        => 'required|numeric',
            'australia_fees'  => 'required|numeric',
            'newzealand_fees' => 'required|numeric',
            'india_fees'      => 'required|numeric',
            'uae_fees'        => 'required|numeric',
            'uk_fees'         => 'required|numeric',
            'qatar_fees'      => 'required|numeric',
            'singapore_fees'  => 'required|numeric',
            'european_union_fees'  => 'required|numeric',
        ];

        $customMessages = [
            'name.required'     => 'Please enter Name',
            'fees.required'     => 'Please enter Fees',
            'fees.numeric'      => 'The Fees must be a number',
            'sequence.required' => 'Please enter Sequence',
            'sequence.numeric'  => 'The Sequence must be a number',
            'sequence.unique'   => 'The Sequence must be unique',
            'level_id.required' => 'Please select a valid Level',
            'canada_fees.required' => 'Please enter Canada Fees',
            'canada_fees.numeric'  => 'The Canada Fees must be a number',
            'usa_fees.required' => 'Please enter USA Fees',
            'usa_fees.numeric'  => 'The USA Fees must be a number',
            'australia_fees.required' => 'Please enter Australia Fees',
            'australia_fees.numeric'  => 'The Australia Fees must be a number',
            'newzealand_fees.required' => 'Please enter New Zealand Fees',
            'newzealand_fees.numeric'  => 'The New Zealand Fees must be a number',
            'india_fees.required' => 'Please enter India Fees',
            'india_fees.numeric'  => 'The India Fees must be a number', 
            'uae_fees.required' => 'Please enter UAE Fees',
            'uae_fees.numeric'  => 'The UAE Fees must be a number',
            'uk_fees.required' => 'Please enter UK Fees',
            'uk_fees.numeric'  => 'The UK Fees must be a number',
            'qatar_fees.required' => 'Please enter Qatar Fees',
            'qatar_fees.numeric'  => 'The Qatar Fees must be a number',
            'singapore_fees.required' => 'Please enter Singapore Fees',
            'singapore_fees.numeric'  => 'The Singapore Fees must be a number',
        ];

        $request->validate($rules, $customMessages);

        $paymentlevel = new Paymentlevel;
        $paymentlevel->fill($request->all());
        $paymentlevel->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Payment Level Created Successfully',
        ], 201);
    }

    /**
     * Update the status of a payment level.
     */
    public function changeStatus(Request $request)
    {
        $paymentlevel         = Paymentlevel::find($request->route_key);
        $paymentlevel->status = $request->status;
        $paymentlevel->save();

        return response()->json([
            'status'       => 'success',
            'message'      => $paymentlevel->name . ' has been marked ' . $paymentlevel->status . ' successfully',
            'paymentlevel' => $paymentlevel,
        ], 201);
    }

    /**
     * Show the form for editing the specified payment level.
     */
    public function edit(Paymentlevel $paymentlevel)
    {
        $level_ids    = Level::where('status', 'ACTIVE')->get();
        return view('Admin.PaymentLevels.form', compact('paymentlevel', 'level_ids'));
    }

    /**
     * Update the specified payment level in the database.
     */
    public function update(Request $request, Paymentlevel $paymentlevel)
    {
        $rules = [
            'name'            => 'required',
            'canada_fees'     => 'required|numeric',
            'sequence'        => 'required|numeric|unique:paymentlevels,sequence',
            'level_id'        => 'required|exists:levels,id',
            'usa_fees'        => 'required|numeric',
            'australia_fees'  => 'required|numeric',
            'newzealand_fees' => 'required|numeric',
            'india_fees'      => 'required|numeric',
            'uae_fees'        => 'required|numeric',
            'uk_fees'         => 'required|numeric',
            'qatar_fees'      => 'required|numeric',
            'singapore_fees'  => 'required|numeric',
            'european_union_fees'  => 'required|numeric',
        ];

        $customMessages = [
            'name.required'     => 'Please enter Name',
            'fees.required'     => 'Please enter Fees',
            'fees.numeric'      => 'The Fees must be a number',
            'sequence.required' => 'Please enter Sequence',
            'sequence.numeric'  => 'The Sequence must be a number',
            'sequence.unique'   => 'The Sequence must be unique',
            'level_id.required' => 'Please select a valid Level',
            'canada_fees.required' => 'Please enter Canada Fees',
            'canada_fees.numeric'  => 'The Canada Fees must be a number',
            'usa_fees.required' => 'Please enter USA Fees',
            'usa_fees.numeric'  => 'The USA Fees must be a number',
            'australia_fees.required' => 'Please enter Australia Fees',     
            'australia_fees.numeric'  => 'The Australia Fees must be a number',
            'newzealand_fees.required' => 'Please enter New Zealand Fees',
            'newzealand_fees.numeric'  => 'The New Zealand Fees must be a number',
            'india_fees.required' => 'Please enter India Fees',
            'india_fees.numeric'  => 'The India Fees must be a number',
            'uae_fees.required' => 'Please enter UAE Fees',
            'uae_fees.numeric'  => 'The UAE Fees must be a number',
            'uk_fees.required' => 'Please enter UK Fees',
            'uk_fees.numeric'  => 'The UK Fees must be a number',
            'qatar_fees.required' => 'Please enter Qatar Fees',
            'qatar_fees.numeric'  => 'The Qatar Fees must be a number',
            'singapore_fees.required' => 'Please enter Singapore Fees',
            'singapore_fees.numeric'  => 'The Singapore Fees must be a number', 
        ];

        $rules['sequence'] = 'required|numeric|unique:paymentlevels,sequence,' . $paymentlevel->id;

        $request->validate($rules, $customMessages);



        $paymentlevel->fill($request->all());
        $paymentlevel->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Payment level updated successfully',
        ], 201);
    }


}
