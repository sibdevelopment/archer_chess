<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CoachAvailability;
use App\Mail\StudentFeeInvoiceMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class StudentFeeController extends Controller
{
    public function index(Student $student)
    {
        // dd(extension_loaded('gd'));

        $students = Student::all();
        return view('Admin.StudentFees.index', compact('students', 'student'));
    }

    public function data(Student $student, Request $request)
    {
        $query = StudentFee::where('student_id', $student->id)->orderBy('created_at');

        return DataTables::eloquent($query)
            ->editColumn('student_id', function ($student_fee) {
                return $student_fee->student_id;
            })
            ->editColumn('date', function ($student_fee) {
                if ($student_fee->start_date == null || $student_fee->end_date == null) {
                    return '<span class="badge bg-light-danger text-danger">N/A</span>';
                }
                return date('d-M-Y', strtotime($student_fee->start_date)) . ' - ' . date('d-M-Y', strtotime($student_fee->end_date));
            })
            ->editColumn('receive_date', function ($student_fee) {
                if ($student_fee->receive_date == null) {
                    return '<span class="badge bg-light-danger text-danger">N/A</span>';
                }
                return date('d-M-Y', strtotime($student_fee->receive_date));
            })
            ->editColumn('currency', function ($student_fee) {
                return $student_fee->currency;
            })
            ->editColumn('monthly_fees', function ($student_fee) {
                return $student_fee->monthly_fees;
            })
            ->editColumn('total_amount_paid', function ($student_fee) {
                return $student_fee->total_amount_paid;
            })
            ->editColumn('created_by', function ($student_fee) {
                if ($student_fee->createdBy) {
                    $name = $student_fee->createdBy->first_name . ' ' . $student_fee->createdBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1">N/A</span>';
            })
            ->editColumn('updated_by', function ($student_fee) {
                if ($student_fee->updatedBy) {
                    $name = $student_fee->updatedBy->first_name . ' ' . $student_fee->updatedBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1">N/A</span>';
            })
            ->editColumn('status', function ($student_fee) {
                // if ($student_fee->status == 'ACTIVE') {
                //     return '<div class="form-check form-switch"><input class="form-check-input student_fee-status-switch" type="checkbox" checked data-routekey="' . $student_fee->route_key . '"/></div>';
                // } else {
                //     return '<div class="form-check form-switch"><input class="form-check-input student_fee-status-switch" type="checkbox" data-routekey="' . $student_fee->route_key . '"/></div>';
                // }
                return $student_fee->status;
            })
            ->addColumn('action', function ($student_fee) use ($student) {
                $user    = auth()->user();
                $edit = '';
                if (auth()->check() && auth()->user()->student_fees_edit === 'YES' || in_array($user->id, [13, 14, 15, 16, 17, 1])) {
                    $edit = '<a href="' . route('admin.students.student_fees.edit', ['student' => $student->id, 'student_fee' => $student_fee->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i> &nbsp; Edit</a>';
                }
                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete" class="badge bg-dark fs-1 delete-btn" data-student-id="' . $student->id . '" data-student_fee-id="' . $student_fee->id . '"><i class="fa fa-trash fs-1"></i> &nbsp; Delete </a>';
                }

                return $edit . '  ' . $delete;
            })
            ->addColumn('pdf', function ($student_fee) use ($student) {
                return '<a href="' . route('admin.students.student_fees.invoice', ['student' => $student->id, 'student_fee' => $student_fee->id]) . '" class="badge bg-danger fs-1" title="Download Invoice"><i class="ti ti-file-text"></i></a>';
            })
            ->addIndexColumn()
            ->rawColumns(['student_id', 'action', 'status', 'to_period', 'from_period', 'day_of_week', 'coach_id', 'monthly_fees', 'total_amount_paid', 'created_by', 'date', 'updated_by', 'receive_date', 'pdf'])
            ->setRowId('id')
            ->make(true);
    }

    public function downloadInvoice(Student $student, StudentFee $student_fee)
    {
        $logoPath = public_path('backend/images/ArcherKids-logo.png');

        $pdf = Pdf::loadView('Admin.StudentFees.invoicePdf', [
            'student'     => $student,
            'student_fee' => $student_fee,
            'logoPath'    => $logoPath,
        ]);

        return $pdf->download(
            'Invoice_' . $student->student_id . '_' . $student_fee->id . '.pdf'
        );
    }

    public function list(Request $request)
    {
        if ($request->student_id) {
            $students = StudentFee::where('student_id', $request->student_id)->where('status', 'ACTIVE')->with('student')->get();
        } elseif ($request->student_ids) {
            if (is_string($request->student_ids)) {
                $request->student_ids = explode(',', $request->student_ids);
            }
            $students = StudentFee::whereIn('student_id', $request->student_ids)->where('status', 'ACTIVE')->with('student')->get();
        } else {
            $students = StudentFee::with('student')->get();
        }
        return response()->json([
            'status' => 'success',
            'list' => $students,
        ], 200);
    }

    public function create(Student $student)
    {
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();
        $slots = CoachAvailability::where('status', 'ACTIVE')->get();
        return view('Admin.StudentFees.form', compact('student', 'coaches', 'slots'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $activeFeeExists = StudentFee::where('student_id', $request->student_id)
            ->orderBy('end_date', 'desc')
            ->first();


        if ($activeFeeExists && $activeFeeExists->end_date >= Carbon::today()) {
            return response()->json([
                'status' => 'error',
                'message' => 'An active fee record already exists for this student until '
                    . $activeFeeExists->end_date
                    . '. Please update the existing record or wait until it expires before creating a new one.',
            ], 400);
        }


        $student = Student::find($request->student_id);
        $student_fee = new StudentFee;
        $student_fee->student_id = $student->id;
        $student_fee->fill($request->all());
        $today = Carbon::today();
        $endDate = Carbon::parse($request->input('end_date'));

        $old_status = $student->status;

        if ($endDate->lt($today)) {
            $student_fee->status = 'INACTIVE';
            Student::where('id', $student_fee->student_id)->update(['status' => 'FEESDUE']);
        } else {
            $student_fee->status = 'ACTIVE';
            Student::where('id', $student_fee->student_id)->update(['status' => 'ACTIVE']);
        }

        if ($old_status == 'FEESDUE') {
            $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
            // dd($student_latest_batch);
            if ($student_latest_batch) {
                $batch = Batch::where('id', $student_latest_batch->batch_id)->first();
                if ($batch) {
                    $latest_reassign_batch = Batch::where('parent_id', $batch->parent_id)->where('status', '!=', 'INACTIVE')->latest('created_at')->first();
                    if ($latest_reassign_batch) {
                        $last_student = StudentBatch::where('batch_id', $latest_reassign_batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                        $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $latest_reassign_batch->id)->first();
                        if (isset($student_batch)) {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $student_batch->batch_id;
                            $sudentBatch->coach_id = $student_batch->coach_id;
                            $sudentBatch->level_id = $student_batch->level_id;
                            $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                            $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                            $sudentBatch->status = $student_batch->status;
                            $sudentBatch->is_fees_due = 0;
                            $sudentBatch->start_date = Carbon::today();
                            $sudentBatch->end_date = $student_batch->batch->end_date;
                            $sudentBatch->status = 'ACTIVE';
                            $sudentBatch->save();
                        } else {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $last_student->batch_id;
                            $sudentBatch->coach_id = $last_student->coach_id;
                            $sudentBatch->level_id = $last_student->level_id;
                            $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                            $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                            $sudentBatch->status = $last_student->status;
                            $sudentBatch->is_fees_due = $last_student->is_fees_due;
                            $sudentBatch->start_date = $last_student->start_date;
                            $sudentBatch->end_date = $last_student->end_date;
                            $sudentBatch->created_by = $last_student->created_by;
                            $sudentBatch->updated_by = $last_student->updated_by;
                            $sudentBatch->save();
                        }
                    } else {
                        $last_student = StudentBatch::where('batch_id', $batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                        $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $batch->id)->first();
                        if (isset($student_batch)) {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $student_batch->batch_id;
                            $sudentBatch->coach_id = $student_batch->coach_id;
                            $sudentBatch->level_id = $student_batch->level_id;
                            $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                            $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                            $sudentBatch->status = $student_batch->status;
                            $sudentBatch->is_fees_due = 0;
                            $sudentBatch->start_date = Carbon::today();
                            $sudentBatch->end_date = $student_batch->batch->end_date;
                            $sudentBatch->status = 'ACTIVE';
                            $sudentBatch->save();
                        } else {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $last_student->batch_id;
                            $sudentBatch->coach_id = $last_student->coach_id;
                            $sudentBatch->level_id = $last_student->level_id;
                            $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                            $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                            $sudentBatch->status = $last_student->status;
                            $sudentBatch->is_fees_due = $last_student->is_fees_due;
                            $sudentBatch->start_date = $last_student->start_date;
                            $sudentBatch->end_date = $last_student->end_date;
                            $sudentBatch->created_by = $last_student->created_by;
                            $sudentBatch->updated_by = $last_student->updated_by;
                            $sudentBatch->save();
                        }
                    }
                }
            }
        }



        $student_fee->save();

        if ($student->user->email) {
            Mail::to($student->user->email)
                ->send(new StudentFeeInvoiceMail($student, $student_fee));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'StudentFee Created Successfully',
            'student_fee' => $student_fee,
        ], 201);
    }

    public function edit(Student $student, StudentFee $student_fee)
    {
        $students = Student::all();
        $coaches = Coach::where('status', 'ACTIVE')->with('user')->get();

        return view('Admin.StudentFees.form', compact('student_fee', 'students', 'student', 'coaches'));
    }

    public function update(Student $student, Request $request, StudentFee $student_fee)
    {
        //dd($request->all());
        $request->validate($this->rules, $this->customMessages);
        $student_fee->fill($request->all());
        $today = Carbon::today();
        $endDate = Carbon::parse($request->input('end_date'));

        $old_status = $student->status;

        if ($endDate->lt($today)) {
            $student_fee->status = 'INACTIVE';
            Student::where('id', $student_fee->student_id)->update(['status' => 'FEESDUE']);
        } else {
            $student_fee->status = 'ACTIVE';
            Student::where('id', $student_fee->student_id)->update(['status' => 'ACTIVE']);

            // $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();

            // if ($student_latest_batch) {
            //     $batch = Batch::find($student_latest_batch->batch_id);
            //     if ($batch) {
            //         $lastChildBatch = Batch::where('parent_id', $batch->parent_id)->latest('created_at')->first();
            //         if ($lastChildBatch) {
            //             $last_student = StudentBatch::where('batch_id', $lastChildBatch->id)->latest('created_at')->first();
            //             if (!$last_student) {
            //                 $sudentBatch = new StudentBatch();
            //                 $sudentBatch->student_id = $student->id;
            //                 $sudentBatch->batch_id = $last_student->batch_id;
            //                 $sudentBatch->coach_id = $last_student->coach_id;
            //                 $sudentBatch->level_id = $last_student->level_id;
            //                 $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
            //                 $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
            //                 $sudentBatch->status = $last_student->status;
            //                 $sudentBatch->is_fees_due = $last_student->is_fees_due;
            //                 $sudentBatch->start_date = $last_student->start_date;
            //                 $sudentBatch->end_date = $last_student->end_date;
            //                 $sudentBatch->created_by = $last_student->created_by;
            //                 $sudentBatch->updated_by = $last_student->updated_by;
            //                 $sudentBatch->save();
            //             }
            //         }
            //     }
            // }

            // if ($student_latest_batch && $old_status != 'INACTIVE' && $student_latest_batch->status != 'INACTIVE') {
            //     $student_latest_batch->status = 'ACTIVE';
            //     $student_latest_batch->save();
            // }
        }
        $student_fee->save();

        return response()->json([
            'status' => 'success',
            'message' => 'StudentFee Updated Successfully',
            'student_fee' => $student_fee,
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $student_fee = StudentFee::findByKey($request->route_key);
        $student_fee->status = $request->status;
        $student_fee->save();

        if ($request->status === 'INACTIVE') {
            $student = $student_fee->student;
            $student->status = 'STANDBY';
            $student->save();
        } elseif ($request->status === 'ACTIVE') {
            $student = $student_fee->student;
            $student->status = 'ACTIVE';
            $student->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => $student_fee->title . ' has been marked ' . $student_fee->status . ' successfully',
            'student_fee' => $student_fee,
        ], 201);
    }

    public function destroy($studentId, $studentFee)
    {
        //dd($studentFeeId);
        if ($studentFee) {
            $studentFee->delete();
            return response()->json([
                'message' => 'Student fee deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Student fee not found',
            ], 404);
        }
    }

    private $rules = [
        'student_id' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'receive_date' => 'required|date',
        'currency' => 'required|string',
        'monthly_fees' => 'required|numeric|min:0',
        'total_amount_paid' => 'required|numeric|min:0',
    ];

    private $customMessages = [
        'student_id.required' => 'StudentFee ID is required',
        'start_date.required' => 'Start date is required',
        'end_date.required' => 'End date is required',
        'end_date.after_or_equal' => 'End date must be after or equal to start date',
        'receive_date.required' => 'Receive date is required',
        'currency.required' => 'Currency is required',
        'currency.string' => 'Currency must be a string',
        'monthly_fees.required' => 'Monthly fees are required',
        'monthly_fees.numeric' => 'Monthly fees must be a number',
        'monthly_fees.min' => 'Monthly fees must be at least 0',
        'total_amount_paid.required' => 'Total amount paid is required',
        'total_amount_paid.numeric' => 'Total amount paid must be a number',
        'total_amount_paid.min' => 'Total amount paid must be at least 0',
    ];
}
