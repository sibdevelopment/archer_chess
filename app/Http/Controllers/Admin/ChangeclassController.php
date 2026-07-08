<?php

namespace App\Http\Controllers\Admin;

use auth;
use DataTables;
use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Employee;
use App\Models\StudentFee;
use App\Models\Changeclass;
use App\Models\StudentBatch;
use App\Models\Paymentlevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ChangeclassController extends Controller
{
    private function batchContainsStudentCountry(Batch $batch, Student $student): bool
    {
        $countries = is_array($batch->country) ? $batch->country : json_decode($batch->country, true);
        if (! is_array($countries)) {
            $countries = array_filter(array_map('trim', explode(',', (string) $batch->country)));
        }

        return in_array($student->country, $countries);
    }

    private function applyBatchCountryFilter($query, ?string $country)
    {
        if (! $country) {
            return $query;
        }

        return $query->whereNotNull('country')->where(function ($query) use ($country) {
            $query->whereRaw('json_valid(country) AND json_contains(country, ?)', [json_encode($country)])
                ->orWhere(function ($query) use ($country) {
                    $query->whereRaw('NOT json_valid(country)')->where(function ($query) use ($country) {
                        $query->where('country', $country)
                            ->orWhereRaw('FIND_IN_SET(?, country)', [$country]);
                    });
                });
        });
    }

    private function directAssignChangeClassStudent(Student $student, Batch $batch, StudentFee $studentFee, Changeclass $changeClass): array
    {
        if ($batch->status === 'UPCOMING') {
            return [
                'ok' => false,
                'redirect' => route('admin.batchs.assign.student', [
                    'batch' => $batch->id,
                    'student_id' => $student->id,
                    'change_class_id' => $changeClass->id,
                ]),
                'message' => 'Raw batch needs assignment details before activation.',
            ];
        }

        if (! in_array($batch->status, ['ACTIVE', 'STANDBY'])) {
            return ['ok' => false, 'message' => 'Selected batch is not active/standby for direct assignment.'];
        }

        if (! $batch->coach_id || ! $batch->level_id || ! $batch->start_date || ! $batch->end_date) {
            return ['ok' => false, 'message' => 'Selected batch is missing coach, level, start date, or end date.'];
        }

        if (! $this->batchContainsStudentCountry($batch, $student)) {
            return ['ok' => false, 'message' => 'Student country does not match selected batch country.'];
        }

        $activeStudentsInBatch = StudentBatch::where('batch_id', $batch->id)
            ->where('status', 'ACTIVE')
            ->where('student_id', '!=', $student->id)
            ->distinct('student_id')
            ->count('student_id');

        if ($batch->is_one_to_one && $activeStudentsInBatch >= 1) {
            return ['ok' => false, 'message' => 'Only one student can be assigned to a 1-1 batch.'];
        }

        $feeStartDate = Carbon::parse($studentFee->start_date)->toDateString();
        $feeEndDate = Carbon::parse($studentFee->end_date)->toDateString();
        $batchStartDate = Carbon::parse($batch->start_date)->toDateString();
        $batchEndDate = Carbon::parse($batch->end_date)->toDateString();

        $studentBatchStartDate = Carbon::parse($feeStartDate)->gt(Carbon::parse($batchStartDate))
            ? $feeStartDate
            : $batchStartDate;
        $studentBatchEndDate = Carbon::parse($feeEndDate)->lt(Carbon::parse($batchEndDate))
            ? $feeEndDate
            : $batchEndDate;

        StudentBatch::where('student_id', $student->id)
            ->where('batch_id', '!=', $batch->id)
            ->where('status', 'ACTIVE')
            ->update([
                'status' => 'INACTIVE',
                'end_date' => Carbon::parse($studentBatchStartDate)->subDay()->toDateString(),
                'end_time' => Carbon::now()->format('H:i:s'),
            ]);

        StudentBatch::updateOrCreate(
            [
                'student_id' => $student->id,
                'batch_id' => $batch->id,
            ],
            [
                'coach_id' => $batch->coach_id,
                'level_id' => $batch->level_id,
                'status' => 'ACTIVE',
                'start_date' => $studentBatchStartDate,
                'end_date' => $studentBatchEndDate,
                'number_of_sessions' => $batch->number_of_sessions,
                'is_fees_due' => 0,
            ]
        );

        return ['ok' => true, 'message' => 'Student assigned to selected batch successfully.'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user           = auth()->user();
        $batches        = Batch::get();
        $payment_levels = Paymentlevel::where('status', 'ACTIVE')->get();
        $employees      = Employee::get();
        return view('Admin.ChangeClass.index', compact('user', 'batches', 'payment_levels', 'employees'));
    }

    public function data(Request $request)
    {
        $query = Changeclass::orderBy('id', 'desc')->where('is_submitted', 0);

        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();

        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $query->where(function ($q) use ($employee, $user) {
                    $q->whereJsonContains('employee_ids', $employee->id)
                      ->orWhere('created_by', $user->id);
                });
            } else {
                $query
                ->orWhere('created_by', $user->id);
            }
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('payment_level_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('lastpayment_level_id', $request->payment_level_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '=', $request->start_date);
        }

        return DataTables::eloquent($query)
            ->addColumn('student_id', function ($change_class) {
                return $change_class->student->student_id;
            })
            ->editColumn('name', function ($change_class) {
                return $change_class->student->first_name . ' ' . $change_class->student->last_name;
            })
            ->editColumn('email', function ($change_class) {
                return '<img src="/backend/dist/images/svgs/icon-mail.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $change_class->student->email;
            })
            ->editColumn('mobile', function ($change_class) {
                return '<img src="/backend/dist/images/svgs/icon-phone.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $change_class->student->mobile;
            })
            ->editColumn('country', function ($change_class) {
                return $change_class->student->country;
            })
            ->editColumn('payment_level', function ($change_class) {
                if ($change_class->student->lastpayment_level_id == null) {
                    return 'N/A';
                }
                $lastpayment_level = Paymentlevel::find($change_class->student->lastpayment_level_id);
                return $lastpayment_level->name;
            })
            ->editColumn('batch', function ($change_class) {
                if ($change_class->batch_id == null) {
                    return 'N/A';
                }
                return $change_class->batch->name;
            })
            ->editColumn('employee', function ($change_class) {
                if (empty($change_class->employee_ids)) {
                    return 'N/A';
                }
                // Convert JSON string to array if necessary
                $employeeIds = is_array($change_class->employee_ids)
                    ? $change_class->employee_ids
                    : json_decode($change_class->employee_ids, true);

                if (empty($employeeIds)) {
                    return 'N/A';
                }

                // Fetch employees by their IDs
                $employees = \App\Models\Employee::whereIn('id', $employeeIds)->with('user')->get();

                if ($employees->isEmpty()) {
                    return 'N/A';
                }

                // Define $employeeList inside the function scope
                $employeeList = $employees->map(function ($employee, $index) use ($employees) {
                    return ($employees->count() > 1 ? ($index + 1) . ') ' : '') . $employee->user->first_name . ' ' . $employee->user->last_name;
                })->implode('<br>'); // Use <br> for line breaks

                return $employeeList;
            })

            ->editColumn('start_date', function ($change_class) {
                if ($change_class->start_date == null) {
                    return 'N/A';
                }
                return toIndianDate($change_class->start_date);
            })
            ->editColumn('receive_date', function ($change_class) {
                if ($change_class->receive_date == null) {
                    return 'N/A';
                }
                return toIndianDate($change_class->receive_date);
            })
            ->editColumn('end_date', function ($change_class) {
                if ($change_class->end_date == null) {
                    return 'N/A';
                }
                return toIndianDate($change_class->end_date);
            })
            ->editColumn('fees', function ($change_class) {
                if ($change_class->fees == null) {
                    return 'N/A';
                }
                return $change_class->fees;
            })
            ->editColumn('received_fees', function ($change_class) {
                if ($change_class->received_fees == null) {
                    return 'N/A';
                }
                return $change_class->received_fees;
            })
            ->editColumn('currency', function ($change_class) {
                if ($change_class->currency == null) {
                    return 'N/A';
                }
                return $change_class->currency;
            })
            ->editColumn('remark', function ($change_class) {
                if ($change_class->remark == null) {
                    return 'N/A';
                }
                return $change_class->remark;
            })
            ->addColumn('action', function ($change_class) {
                $show = '<a href="' . route('admin.changeclasses.show', ['changeclass' => $change_class->id]) . '"
                         class="badge bg-info fs-1"
                         data-entity="newenrollments"
                         data-title="New Enrollments Details"
                         data-route-key="' . $change_class->id . '">
                         <i class="fa fa-eye"></i>
                         </a>';
                return $show;
            })

            ->addIndexColumn()
            ->rawColumns(['name', 'mobile', 'email', 'country', 'action', 'student_id', 'payment_level', 'batch', 'employee', 'start_date', 'end_date', 'fees', 'received_fees', 'currency', 'remark'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Changeclass $changeclass)
    {
        $employees = Employee::all();
        $batches   = $this->applyBatchCountryFilter(
            Batch::where('status','!=','INACTIVE'),
            $changeclass->student->country ?? null
        )->get();

        
        return view('Admin.ChangeClass.show', compact('changeclass', 'employees', 'batches'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Changeclass $change_class)
    {
        // dd($request->all());
        if ($request->type == 'changeclass') {

            $request->validate([
                'employee_ids'    => 'required',
                // 'employee_id'   => 'required',
                // 'batch_id'      => 'required',
                // 'start_date'    => 'required|date',
                // 'end_date'      => 'required|date|after_or_equal:start_date',
                'fees'          => 'required|numeric|min:0',
                'received_fees' => 'required|numeric|min:0',
                'currency'      => 'required',
                'remark'        => 'nullable',
            ], [
                'employee_ids.required'     => 'Please select a Employee.',
                'employee_id.required'    => 'Please select an employee.',
                'batch_id.required'       => 'Please select a batch.',
                'start_date.required'     => 'The start date is required.',
                'end_date.required'       => 'The end date is required.',
                'end_date.after_or_equal' => 'The end date must be on or after the start date.',
                'fees.required'           => 'Please enter the fees amount.',
                'fees.numeric'            => 'The fees must be a valid number.',
                'fees.min'                => 'Fees cannot be negative.',
                'received_fees.required'  => 'Please enter the received fees amount.',
                'received_fees.numeric'   => 'The received fees must be a valid number.',
                'received_fees.min'       => 'Received fees cannot be negative.',
                'currency.required'       => 'Please enter the currency.',
            ]);

            // $change_class = Changeclass::with('student')->findOrFail($change_class->id);
            // dd($change_class);

            // $change_class->employee_ids    = $request->employee_ids;
            $change_class->employee_ids = array_map('intval', $request->employee_ids);

            $change_class->student_id    = $request->student_id;
            // $change_class->employee_id = (int) $request->employee_id;
            $change_class->batch_id      = $request->batch_id;
            $change_class->remark        = $request->remark;
            $change_class->start_date    = $request->start_date;
            $change_class->end_date      = $request->end_date;
            $change_class->receive_date = $request->receive_date; 
            $change_class->fees          = $request->fees;
            $change_class->received_fees = $request->received_fees;
            $change_class->currency      = $request->currency;
            $change_class->save();

            return response()->json([
                'status'         => 'success',
                'message'        => 'Change Class Created Successfully',
                'change_class' => $change_class,
            ], 201);
        } else
        if ($request->type == 'student-fee') {
            $request->validate([
                'employee_ids'    => 'required',
                // 'employee_id'   => 'required',
                'batch_id'      => 'required',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|after_or_equal:start_date',
                'receive_date' => 'required|date',
                'fees'          => 'required|numeric|min:0',
                'received_fees' => 'required|numeric|min:0',
                'currency'      => 'required',
                'remark'        => 'required',
            ], [
                'employee_ids.required'     => 'Please select a Employee.',
                // 'employee_id.required'    => 'Please select an employee.',
                'batch_id.required'       => 'Please select a batch.',
                'start_date.required'     => 'The start date is required.',
                'end_date.required'       => 'The end date is required.',
                'end_date.after_or_equal' => 'The end date must be on or after the start date.',
                'receive_date.required' => 'The receive date is required.',
                'fees.required'           => 'Please enter the fees amount.',
                'fees.numeric'            => 'The fees must be a valid number.',
                'fees.min'                => 'Fees cannot be negative.',
                'received_fees.required'  => 'Please enter the received fees amount.',
                'received_fees.numeric'   => 'The received fees must be a valid number.',
                'received_fees.min'       => 'Received fees cannot be negative.',
                'currency.required'       => 'Please enter the currency.',
            ]);

            DB::beginTransaction();
            try {
                $student = Student::findOrFail($request->student_id);
                $batch = Batch::findOrFail($request->batch_id);

                $change_class->employee_ids  = array_map('intval', $request->employee_ids);
                $change_class->student_id    = $request->student_id;
                // $change_class->employee_id   =  (int) $request->employee_id;
                $change_class->batch_id      = $request->batch_id;
                $change_class->remark        = $request->remark;
                $change_class->start_date    = $request->start_date;
                $change_class->end_date      = $request->end_date;
                $change_class->receive_date  = $request->receive_date;
                $change_class->fees          = $request->fees;
                $change_class->received_fees = $request->received_fees;
                $change_class->currency      = $request->currency;
                $change_class->is_submitted  = 1;
                $change_class->save();

                StudentFee::where('student_id', $student->id)
                    ->where('status', 'ACTIVE')
                    ->update(['status' => 'INACTIVE']);

                $student_fee                    = new StudentFee;
                $student_fee->student_id        = $request->student_id;
                $student_fee->start_date        = $request->start_date;
                $student_fee->end_date          = $request->end_date;
                $student_fee->receive_date      = $request->receive_date;
                $student_fee->monthly_fees      = $request->fees;
                $student_fee->total_amount_paid = $request->received_fees;
                $student_fee->currency          = $request->currency;
                $student_fee->status            = 'ACTIVE';
                $student_fee->save();

                $student->status = 'ACTIVE';
                $student->save();

                $assignmentResult = $this->directAssignChangeClassStudent($student, $batch, $student_fee, $change_class);

                if (! $assignmentResult['ok'] && empty($assignmentResult['redirect'])) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $assignmentResult['message'],
                        'errors' => [
                            'batch_id' => [$assignmentResult['message']],
                        ],
                    ], 422);
                }

                DB::commit();

                return response()->json([
                    'status'       => 'success',
                    'message'      => $assignmentResult['message'] ?? 'Change Batch Confirmed Successfully',
                    'student_fee'  => $student_fee,
                    'redirect_url' => $assignmentResult['redirect'] ?? null,
                ], 201);
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
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
}
