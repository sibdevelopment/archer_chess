<?php
namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Employee;
use App\Models\StudentFee;
use App\Mail\EnrollmentMail;
use App\Models\Paymentlevel;
use Illuminate\Http\Request;
use App\Models\NewEnrollment;
use App\Exports\NewEnrollmentExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class NewEnrollmentController extends Controller
{
    public function index()
    {
        $user           = auth()->user();
        $batches        = Batch::all();
        $payment_levels = Paymentlevel::where('status', 'ACTIVE')->get();
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'ACTIVE');
        })->get();

        $created_bys = User::whereIn('id', NewEnrollment::select('created_by')->distinct()->pluck('created_by'))->get();
        return view('Admin.NewEnrollments.index', compact('batches', 'payment_levels', 'employees', 'user', 'created_bys'));
    }

    public function data(Request $request)
    {
        $query = NewEnrollment::query()
                ->leftJoin('batchs', 'batchs.id', '=', 'new_enrollments.batch_id')
                ->leftJoin('students', 'students.id', '=', 'new_enrollments.student_id')
                ->leftJoin('paymentlevels', 'paymentlevels.level_id', '=', 'students.level_id')
                ->leftJoin('employees', 'employees.id', '=', 'new_enrollments.employee_id')
                ->leftJoin('users as employee_users', 'employee_users.id', '=', 'employees.user_id')
                ->select([
                    'new_enrollments.*',
                    'batchs.name as batch_name',
                    'employee_users.first_name as employee_first_name',
                    'employee_users.last_name as employee_last_name',
                    'paymentlevels.name as payment_level_name'
                ])
                ->orderBy('new_enrollments.id', 'desc');


        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();

        $employee = Employee::where('user_id', $user->id)->first(); 

        if (! $user->roles()->where('name', 'SuperAdmin')->exists() && !$request->filled('employee_id') && !$request->filled('created_by')) {
            if ($employee) {
                $query->where(function ($q) use ($employee, $user) {
                    $q->whereRaw("JSON_CONTAINS(employee_ids, '\"$employee->id\"')")
                    ->orWhere('new_enrollments.created_by', $user->id);
                });
            }
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('country')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('country', $request->country);
            });
        }

        if ($request->filled('created_by')) {
            $query->where('new_enrollments.created_by', $request->created_by);
        }

        if ($request->filled('payment_level_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('lastpayment_level_id', $request->payment_level_id);
            });
        }

        if ($request->start_date) {
            [$startDate, $endDate] = explode(' - ', $request->start_date);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
            $query->whereBetween('new_enrollments.created_at', [$startDate, $endDate]);
        }

        return DataTables::eloquent($query)
            ->addColumn('student_id', function ($new_enrollment) {
                return $new_enrollment->student->student_id;
            })
            ->editColumn('name', function ($new_enrollment) {
                return $new_enrollment->student->first_name . ' ' . $new_enrollment->student->last_name;
            })
            ->editColumn('email', function ($new_enrollment) {
                return '<img src="/backend/dist/images/svgs/icon-mail.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $new_enrollment->student->email;
            })
            ->editColumn('mobile', function ($new_enrollment) {
                return '<img src="/backend/dist/images/svgs/icon-phone.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $new_enrollment->student->mobile;
            })
            ->editColumn('country', function ($new_enrollment) {
                return $new_enrollment->student->country;
            })
            ->editColumn('created_by', function ($new_enrollment) {
                if ($new_enrollment->created_by == null) {
                    return 'N/A';
                }
                $user = User::find($new_enrollment->created_by);
                if ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }
                return 'N/A';
            })
            ->filterColumn('batch', function ($query, $keyword) {
                $query->where('batchs.name', 'like', "%{$keyword}%");
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->whereHas('students.first_name', function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                      ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('mobile', function($query, $keyword) {
                $query->whereHas('students.mobile', function ($q) use ($keyword) {
                    $q->where('mobile', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('country', function($query, $keyword) {
                $query->whereHas('students.country', function ($q) use ($keyword) {
                    $q->where('country', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('student_id', function($query, $keyword) {
                $query->whereHas('students.student_id', function ($q) use ($keyword) {
                    $q->where('student_id', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('employee_users.first_name', 'like', "%{$keyword}%")
                      ->orWhere('employee_users.last_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('payment_level', function ($query, $keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->whereHas('paymentlevel', function ($q2) use ($keyword) {
                        $q2->where('paymentlevels.name', 'like', "%{$keyword}%");
                    });
                });
            })
            ->editColumn('payment_level', function ($new_enrollment) {
                if ($new_enrollment->student->lastpayment_level_id == null) {
                    return 'N/A';
                }
                $lastpayment_level = Paymentlevel::find($new_enrollment->student->lastpayment_level_id);
                return $lastpayment_level->name;
            })
            ->editColumn('batch', function ($new_enrollment) {
                if ($new_enrollment->batch_id == null) {
                    return 'N/A';
                }
                return $new_enrollment->batch->name;
            })
            ->editColumn('employee', function ($new_enrollment) {
                if (empty($new_enrollment->employee_ids)) {
                    return 'N/A';
                }
                // Convert JSON string to array if necessary
                $employeeIds = is_array($new_enrollment->employee_ids)
                    ? $new_enrollment->employee_ids
                    : json_decode($new_enrollment->employee_ids, true);

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

            ->editColumn('start_date', function ($new_enrollment) {
                if ($new_enrollment->start_date == null) {
                    return 'N/A';
                }
                return toIndianDate($new_enrollment->start_date);
            })
            ->editColumn('receive_date', function ($new_enrollment) {
                if ($new_enrollment->receive_date == null) {
                    return 'N/A';
                }
                return toIndianDate($new_enrollment->receive_date);
            })
            ->editColumn('end_date', function ($new_enrollment) {
                if ($new_enrollment->end_date == null) {
                    return 'N/A';
                }
                return toIndianDate($new_enrollment->end_date);
            })
            ->editColumn('fees', function ($new_enrollment) {
                if ($new_enrollment->fees == null) {
                    return 'N/A';
                }
                return $new_enrollment->fees;
            })
            ->editColumn('received_fees', function ($new_enrollment) {
                if ($new_enrollment->received_fees == null) {
                    return 'N/A';
                }
                return $new_enrollment->received_fees;
            })
            ->editColumn('currency', function ($new_enrollment) {
                if ($new_enrollment->currency == null) {
                    return 'N/A';
                }
                return $new_enrollment->currency;
            })
            ->editColumn('remark', function ($new_enrollment) {
                if ($new_enrollment->remark == null) {
                    return 'N/A';
                }
                return $new_enrollment->remark;
            })
            ->addColumn('action', function ($new_enrollment) {
                $show = '<a href="' . route('admin.newenrollments.show', ['newenrollment' => $new_enrollment->id]) . '"
                         class="badge bg-info fs-1"
                         data-entity="newenrollments"
                         data-title="New Enrollments Details"
                         data-route-key="' . $new_enrollment->id . '">
                         <i class="fa fa-eye"></i>
                         </a>';
                return $show;
            })

            ->addIndexColumn()
            ->rawColumns(['name', 'mobile', 'email', 'country', 'action', 'student_id', 'payment_level', 'batch', 'employee', 'start_date', 'end_date', 'fees', 'received_fees', 'currency', 'remark'])
            ->setRowId('id')
            ->make(true);
    }

    public function show($id)
    {
        $new_enrollment = NewEnrollment::with('student')->findOrFail($id);
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'ACTIVE');
        })->get();

        $user                = auth()->user();
        $role                = $user->getRoleNames()->toArray();
        $isAdminOrSuperAdmin = in_array("Admin", $role) || in_array("SuperAdmin", $role);

        $allowedCountries = [];
        if (! $isAdminOrSuperAdmin) {
            $userRole = $user->roles()->first();
            if ($userRole && $userRole->countries) {
                $allowedCountries = json_decode($userRole->countries);
            }
        }

        $batches = Batch::where('status','!=','INACTIVE')->orderBy('name', 'asc')->when($allowedCountries, function ($query, $allowedCountries) {
            $query->where(function ($q) use ($allowedCountries) {
                foreach ($allowedCountries as $country) {
                    $q->orWhere('country', 'LIKE', "%$country%");
                }
            });
        });

        $batches = $batches->get();

        return view('Admin.NewEnrollments.show', compact('new_enrollment', 'employees', 'batches'));
    }

    /*
    * What: Update New Enrollment
    * Why: To update existing new enrollment records
    * When click Save button in form then status is come new-enrollment so that time update new enrollment record
    * When click Confirm Enrollment button in form then status is come student-fee so that time update new enrollment record and create student fee record
    */
    public function update(Request $request, $id)
    {
        if ($request->type == 'new-enrollment') {
            $request->validate([
                'employee_ids'    => 'required', 
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

            $new_enrollment = NewEnrollment::with('student')->findOrFail($id);
            $new_enrollment->employee_ids    = $request->employee_ids;
            $new_enrollment->student_id    = $request->student_id;
            $new_enrollment->batch_id      = $request->batch_id;
            $new_enrollment->remark        = $request->remark;
            $new_enrollment->start_date    = $request->start_date;
            $new_enrollment->end_date      = $request->end_date;
            $new_enrollment->receive_date = $request->receive_date;
            $new_enrollment->fees          = $request->fees;
            $new_enrollment->received_fees = $request->received_fees;
            $new_enrollment->currency      = $request->currency;
            $new_enrollment->save();

            return response()->json([
                'status'         => 'success',
                'message'        => 'New Enrollment Created Successfully',
                'new_enrollment' => $new_enrollment,
            ], 201);
        } else
        if ($request->type == 'student-fee') {
            $request->validate([
                'employee_ids'    => 'required',
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
                'batch_id.required'       => 'Please select a batch.',
                'start_date.required'     => 'The start date is required.',
                'end_date.required'       => 'The end date is required.',
                'end_date.after_or_equal' => 'The end date must be on or after the start date.',
                'receive_date.required'   => 'The receive date is required.',
                'fees.required'           => 'Please enter the fees amount.',
                'fees.numeric'            => 'The fees must be a valid number.',
                'fees.min'                => 'Fees cannot be negative.',
                'received_fees.required'  => 'Please enter the received fees amount.',
                'received_fees.numeric'   => 'The received fees must be a valid number.',
                'received_fees.min'       => 'Received fees cannot be negative.',
                'currency.required'       => 'Please enter the currency.',
            ]);

            $student = Student::findOrFail($request->student_id);
            if ($student){
                $student->status = 'ACTIVE';
                $student->save();
            }

            $new_enrollment = NewEnrollment::with('student')->findOrFail($id);

            $new_enrollment->employee_ids    = $request->employee_ids;
            $new_enrollment->student_id    = $request->student_id;
            $new_enrollment->batch_id      = $request->batch_id;
            $new_enrollment->remark        = $request->remark;
            $new_enrollment->start_date    = $request->start_date;
            $new_enrollment->end_date      = $request->end_date;
            $new_enrollment->receive_date = $request->receive_date;
            $new_enrollment->fees          = $request->fees;
            $new_enrollment->received_fees = $request->received_fees;
            $new_enrollment->currency      = $request->currency;
            $new_enrollment->save();

            $student_fee                    = new StudentFee();
            $student_fee->student_id        = $request->student_id;
            $student_fee->start_date        = $request->start_date;
            $student_fee->end_date          = $request->end_date;
            $student_fee->receive_date      = $request->receive_date;
            $student_fee->monthly_fees      = $request->fees;
            $student_fee->total_amount_paid = $request->received_fees;
            $student_fee->currency          = $request->currency;
            $student_fee->status            = 'ACTIVE';
            $student_fee->save();
            
            return response()->json([
                'status'      => 'success',
                'message'     => 'New Enrollment Created Successfully',
                'student_fee' => $student_fee,
            ], 201);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new NewEnrollmentExport($request), 'new-enrollments-' . Carbon::now()->toDateTimeString() . '.xlsx');
    }
}
