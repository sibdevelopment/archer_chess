<?php
namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Level;
use App\Models\Student;
use App\Models\Employee;
use App\Models\StudentFee;
use App\Models\CameraCheck;
use App\Models\Changeclass;
use Illuminate\Support\Str;
use App\Models\Paymentlevel;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Models\BatchSchedule;
use App\Models\StudentStatus;
use Illuminate\Support\Carbon;
use App\Exports\StudentsExport;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{

    public function index()
    { 
        $batches = Batch::where('status', 'ACTIVE')->get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        $levels  = Level::where('status', 'ACTIVE')->get();

        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'ACTIVE');
        })->get();

        return view('Admin.Students.index', compact('levels', 'batches', 'coaches','employees'));
    } 

    public function getCoaches(Request $request)
    {
        $user  = Auth::user();
        $query = Coach::with('user')->where('status', 'ACTIVE');
        if ($user->roles()->where('name', 'Coach')->exists()) {
            $query->where('user_id', $user->id);
        } else {
            if ($request->has('batch_id')) {
                $batchId = $request->input('batch_id');
                $query->whereHas('batches', function ($q) use ($batchId) {
                    $q->where('id', $batchId);
                });
            }
        }
        $coaches = $query->get();
        return response()->json($coaches);
    }

    public function getBatches(Request $request)
    {
        $user  = Auth::user();
        $query = Batch::with('coach')->where('status', 'ACTIVE');
        if ($user->roles()->where('name', 'Coach')->exists()) {
            $coach = Coach::where('user_id', $user->id)->first();
            if ($coach) {
                $query->where('coach_id', $coach->id);
            }
        } else {
            if ($request->has('coach_id')) {
                $query->where('coach_id', $request->input('coach_id'));
            }
        }
        $activeBatches = $query->get();
        return response()->json($activeBatches);
    }

    public function data(Request $request)
    {
        $user = auth()->user();

        $role = $user->getRoleNames()->toArray();
        $isCoach = in_array("Coach", $role);
        $query = Student::orderByDesc('id');

        if (! $user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->toArray();
        
            $mergedCountries = collect($countries)
                ->map(fn($item) => json_decode($item, true))  
                ->flatten()  
                ->filter()  
                ->unique()  
                ->values() 
                ->toArray();
        
            if (! empty($mergedCountries)) {
                $query->whereIn('country', $mergedCountries);
            }
        }
        
        

        if ($request->user_id) {
            $query->where('id', $request->user_id);
        }
        
        if ($request->country) {
            $query->where('country', $request->country);
        }
        if ($request->batch) {
            $studentIds = StudentBatch::where('batch_id', $request->batch)->eligibleOn(Carbon::today())->pluck('student_id');
            $query->whereIn('id', $studentIds);
        }

        if ($request->has('weekday') && $request->weekday != '') {
            $weekday        = $request->weekday;

            $batchIds = BatchSchedule::where('weekday', $weekday)->where('status', 'ACTIVE')->get()->pluck('batch_id');
            $query->whereIn('id', function ($subQuery) use ($batchIds) {
                $subQuery->select('student_id')
                    ->from('student_batches') 
                    ->whereIn('batch_id', $batchIds);
                    // ->where('status', 'ACTIVE');
            });
        }

        if ($request->status) {
            if ($request->status == 'CURRENT_DAY') {
                $query->whereHas('studentFees', function ($query) {
                    $query->where('end_date', Carbon::today()->toDateString());
                });
            } else {
                $query->where('status', $request->status);
            }
        }else{
            $query->where('status','!=', 'INACTIVE');
        }

        if (in_array('Coach', $role) && $user->coach) {
            $coachId = $user->coach->id;
        }

        if (isset($coachId)) {
            $studentIds = StudentBatch::where('coach_id', $coachId)
                ->where('status', 'ACTIVE')
                ->pluck('student_id')
                ->toArray();
            $query->whereIn('id', $studentIds);
        }

        if ($request->coach_id) {
            $studentIds = [];

            foreach ($request->coach_id as $coach) {
                $students = StudentBatch::where('coach_id', $coach)
                    ->where('status', 'ACTIVE')
                    ->pluck('student_id')
                    ->toArray();

                $studentIds = array_merge($studentIds, $students);
            }

            // Ensure unique student IDs (optional, if duplicates are not needed)
            $studentIds = array_unique($studentIds);

            // Apply the filter to the query
            $query->whereIn('id', $studentIds);
        }
 
        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay()->format('Y-m-d H:i:s');
            $endDate   = Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay()->format('Y-m-d H:i:s');

            // Filter only by end_date range in studentFees
            $query = $query->whereHas('studentFees', function ($q) use ($startDate, $endDate) {
                $q->where('student_fees.id', function ($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('student_fees')
                        ->whereColumn('student_fees.student_id', 'students.id');
                });
                $q->whereBetween('end_date', [$startDate, $endDate]);
            });
        }

        // Apply FEESDUE filter separately if needed
        if ($request->status == 'FEESDUE') {
            $query->whereHas('studentFees', function ($q) {
                $q->where('student_fees.id', function ($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('student_fees')
                        ->whereColumn('student_fees.student_id', 'students.id');
                });
                $q->whereDate('end_date', '<', Carbon::today()->toDateString());
            });
        }


        if ($request->start_date) {
            // Extract start and end dates from the request
            [$startDate, $endDate] = explode(' - ', $request->start_date);

            // Convert the input date format (from 'm/d/Y' to 'Y-m-d H:i:s')
            $startDate = Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay()->format('Y-m-d H:i:s');
            $endDate   = Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay()->format('Y-m-d H:i:s');

            // Debugging log
            \Log::info("Filtering students where latest studentFees record has start_date between {$startDate} and {$endDate}");

            // Apply filter to get only the latest studentFee record for each student
            $query = $query->whereHas('studentFees', function ($q) use ($startDate, $endDate) {
                // Subquery to get the latest studentFee record for each student based on `start_date`
                $q->where('student_fees.id', '=', function ($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('student_fees')
                        ->whereColumn('student_fees.student_id', 'students.id');
                });

                // Filter by `start_date` within the given range
                $q->whereBetween('start_date', [$startDate, $endDate]);
            });
        }

        $coachStudentIds = [];
        if ($request->coach_id) {
            foreach ($request->coach_id as $coach) {
                $batches         = Batch::where('coach_id', $coach)->where('status', 'ACTIVE')->pluck('id');
                $studentIds      = StudentBatch::whereIn('batch_id', $batches)->pluck('student_id');
                $coachStudentIds = array_merge($coachStudentIds, $studentIds->toArray());
            }

            $studentIds = array_unique($coachStudentIds);
            $query->whereIn('id', $studentIds);
        }

        return DataTables::eloquent($query)
            ->editColumn('age', function ($student) {
                return $student->age;
            })
            ->editColumn('mobile', function ($student) {
                return '<img src="/backend/dist/images/svgs/icon-phone.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $student->mobile;
            })
            ->editColumn('email', function ($student) {
                return $student->email;
            })
            ->editColumn('address', function ($student) {
                return $student->address;
            })
            ->editColumn('last_payment_level_id', function ($student) {
                return $student->paymentlevel ? $student->paymentlevel->name : 'N/A';
            })

            ->editColumn('student_id', function ($student) {
                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();
                $message      = $student->generateNewStudentMessage();
                $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                return '<div class="d-flex justify-content-between">' . $student->student_id
                    . '<div class="d-flex justify-content-end">' . $whatsappLink . '</div></div>';
            })
            ->editColumn('created_by', function ($student) {
                if ($student->createdBy) {
                    $name = $student->createdBy->first_name . ' ' . $student->createdBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1">N/A</span>';
            })
            ->editColumn('updated_by', function ($student) {
                if ($student->updatedBy) {
                    $name           = $student->updatedBy->first_name . ' ' . $student->updatedBy->last_name;
                    $formatted_date = $student->updated_at->format('d-m-Y');
                    $formatted_time = $student->updated_at->format('H:i:s');

                    return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span> &nbsp;  <span class="badge bg-info fs-1">' . $formatted_date . ' &nbsp; ' . $formatted_time . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1">N/A</span>';
            })
            ->editColumn('batch', function ($student) use ($isCoach) {
                // $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                $student_latest_batch = $student->latestBatch;
                if ($student_latest_batch) {
                    $statusBadge = $student_latest_batch->status === 'ACTIVE' ? ' (Present)' : ' (Previous Batch)';
                    return $student_latest_batch->batch->name . $statusBadge;
                }
                return '';
            })
            ->editColumn('batch_schedule', function ($student) use ($isCoach) {
                $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                if ($student_latest_batch) {
                    $batch_schedule = BatchSchedule::where('batch_id', $student_latest_batch->batch_id)->get();
                    $scheduless     = '';
                    foreach ($batch_schedule as $schedule) {
                        $scheduless .= $schedule->weekday . '<br>';
                    }
                    return $scheduless;

                }
                return '';
            })
            ->addColumn('student_fees', function ($student) {
                $activeFee = StudentFee::where('student_id', $student->id)
                    ->orderBy('id', 'desc')
                    ->first();
                $dateBadge = '';
                if ($activeFee) {
                    $startDate = Carbon::parse($activeFee->start_date)->format('d-M-Y');
                    $endDate   = Carbon::parse($activeFee->end_date)->format('d-M-Y');
                    $dateBadge = '<span class="badge bg-info fs-1" style="margin-left: 5px;">' . $startDate . ' &nbsp; - &nbsp; ' . $endDate . '</span>';
                }

                return '<a href="/admin/students/' . $student->id . '/student_fees" class="badge bg-success fs-1"><i class="ti ti-box-multiple"></i> &nbsp; Student Fees  </a>' . $dateBadge;
            })
            ->addColumn('first_name', function ($student) use ($isCoach) {
                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();
                $fullName   = $student->first_name . ' ' . $student->last_name;

                $student_latest_batch = $student->latestBatch;

                $level_name = $student_latest_batch ? '<span class="badge bg-primary fs-1"> (' . $student_latest_batch->batch->level->name . ')</span>' : '';

                if ($studentFee && ! $isCoach) {
                    $message      = $studentFee->generateFeeDueMessage();
                    $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                    $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';
                    return '<div class="d-flex justify-content-between">' . $fullName . '' . $level_name . '</div>';
                }

                return $fullName . ' ' . $level_name;
            })
            ->editColumn('status', function ($student) use ($isCoach) {
                switch ($student->status) {
                    case 'FEESDUE':
                        $badgeColor = 'primary';
                        break;
                    case 'INACTIVE':
                        $badgeColor = 'warning';
                        break;
                    case 'STANDBY':
                        $badgeColor = 'danger';
                        break;
                    case 'ACTIVE':
                        $badgeColor = 'success';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }

                // Fetch the latest StudentFee record for the student
                // $studentFee       = StudentFee::where('student_id', $student->id)->latest('end_date')->first();
                // $endDateFormatted = $studentFee ? \Carbon\Carbon::parse($studentFee->end_date)->format('l, d-M-Y') : 'N/A';
                // $totalDueAmount   = $student->monthly_fees;
                // $currency         = $student->currency;
                // $latestBatch      = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                // // dd($latestBatch);
                // $level_name = $latestBatch ? $latestBatch->level->name : '';

                // $message = "**FEE REMINDER**\n\n";
                // $message .= "Hi Dear Parents,\n\n";
                // $message .= "We hope " . $student->first_name . " is enjoying the chess classes and progressing well!\n\n";
                // $message .= "Just a kind reminder — the next module fee is due by " . $endDateFormatted . ". Timely payment helps us keep the classes smooth and uninterrupted for your child.\n\n";
                // $message .= "You can pay through the student portal here:\n";
                // $message .= "https://archerchessacademy.com/student/login\n";
                // $message .= "Username: " . $student->mobile . "\n";
                // $message .= "Password: archer@" . $student->user->id . "\n\n";
                // $message .= "Once you’ve made the payment, kindly share the screenshot here so we can update our records.\n\n";
                // $message .= "Thanks for being a valued part of the Archer Chess family!\n\n";
                // $message .= "**Team Archer Chess Academy**";

                $studentFee = StudentFee::where('student_id', $student->id)->latest('end_date')->first();
                $endDate = $studentFee ? Carbon::parse($studentFee->end_date) : null;
                $latestBatch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
                $level_name = $latestBatch?->level?->name ?? '';

                $dueDateFormatted = $endDate ? $endDate->format('l, d-M-Y') : 'N/A';

                $message = "**FEE REMINDER**\n\n";
                $message .= "Hi Dear Parents,\n\n";
                $message .= "We hope " . $student->first_name . " is enjoying the chess classes and progressing well!\n\n";
                $message .= "Just a kind reminder — the next module fee is due by " . $dueDateFormatted . ". Timely payment helps us keep the classes smooth and uninterrupted for your child.\n\n";
                $message .= "You can pay through the student portal here:\n";
                $message .= "https://archerchessacademy.com/student/login\n";
                $message .= "Username: " . $student->mobile . "\n";
                $message .= "Password: archer@" . $student->user->id . "\n\n";
                $message .= "Once you’ve made the payment, kindly share the screenshot here so we can update our records.\n\n";
                $message .= "Thanks for being a valued part of the Archer Chess family!\n\n";
                $message .= "**Team Archer Chess Academy**";




                $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                // Conditionally include the WhatsApp link based on the user's role
                $whatsappBadge = ! $isCoach ? ' &nbsp; ' . $whatsappLink : '';

                $studentFee = $student->studentFees()->orderBy('end_date', 'desc')->first();

                if ($studentFee) {
                    $message      = $studentFee->generateFeeDueMessage();
                    $whatsappUrl  = "https://api.whatsapp.com/send?phone=" . $student->mobile . "&text=" . urlencode($message);
                    $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></a>';
                    return '<div class="d-flex justify-content-between">
                            <button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 student-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $student->id . '" data-id="' . $student->id . '"  data-status="' . $student->status . '">
                                <i class="ti ti-analyze"></i> &nbsp; ' . $student->status . '
                            </button>
                            <div class="d-flex justify-content-end">' . $whatsappBadge . '</div>
                        </div>';
                } else {
                    return '<div class="d-flex justify-content-between">
                            <button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 student-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $student->id . '" data-id="' . $student->id . '" data-status="' . $student->status . '">
                                <i class="ti ti-analyze"></i> &nbsp; ' . $student->status . '
                            </button>
                        </div>';
                }
            })
            ->addColumn('action', function ($student) {
                $edit = '<a href="' . route('admin.students.edit', ['student' => $student->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $show = '<a href="' . route('admin.students.show', ['student' => $student->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="students" data-title="Student Details" data-route-key="' . $student->route_key . '"><i class="fa fa-eye"></i></a>';

                $studentLogin = '<a href="' 
                    . route('student.login', ['user_id' => $student->user->id]) 
                    . '" title="Login as Student" class="badge bg-secondary fs-1" target="_blank">
                    <i class="fa fa-sign-in-alt"></i>
                </a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-student-id="' . $student->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                return $edit . '  ' . $show . ' ' . $delete . ' ' . $studentLogin;
            })
            ->addIndexColumn()
            ->rawColumns(['first_name', 'age', 'mobile', 'email', 'address', 'student_id', 'status', 'action', 'student_fees', 'batch', 'created_by', 'updated_by', 'batch_schedule'])
            ->setRowId('id')
            ->make(true);
    }

    public function list(Request $request)
    {
        $students = Student::
        // where('status', 'ACTIVE')
        //     ->
            when(! empty($request->countries), function ($query) use ($request) {
                return $query->whereIn('country', $request->countries);
            })
            ->get();
        // dd($students);
        return response()->json([
            'status' => 'success',
            'data'   => $students, // Fix variable name from $batch to $batches
        ], 201);
    }
    public function masterclassTounamentlist(Request $request)
    {
        $students = Student::
        // where('status', '!=', 'INACTIVE')
        //     ->
            when(! empty($request->countries), function ($query) use ($request) {
                return $query->whereIn('country', $request->countries);
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $students, // Fix variable name from $batch to $batches
        ], 201);
    }

    public function create()
    {
        //$nextStudentId = Student::max('student_id') + 1;
        $lastpayment_level_ids = Paymentlevel::where('status', 'ACTIVE')->get();
        return view('Admin.Students.form', compact('lastpayment_level_ids'));
    }

    public function store(Request $request)
    {
        // Custom validation for mobile with '+' sign
        $request->validate($this->rules, $this->customMessages);
        $request->validate([
            'mobile' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (strpos($value, '+') !== 0) {
                        $fail('Mobile number must start with a "+" sign.');
                    }
                },
            ],
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
        ]);

        $student = new Student;
        $student->fill($request->all());
        if ($request->hasFile('image')) {
            $student->image = Storage::disk('public')->put('photos', $request->file('image'));
        }
        $student->save();

        $user = new User;
        $user->fill($request->all());
        $user->first_name = $request->first_name;
        $user->email      = $request->email;
        $user->mobile     = $request->mobile;
        $user->password   = Hash::make('12314');
        $user->save();

        $password        = 'archer@' . $user->id;
        $user->device_id = $password;
        $user->password  = Hash::make($password);
        $user->save();

        $permissions = [];
        $user->assignRole('Student');
        $role = Role::where('name', 'Student')->first();
        array_push($permissions, $role->permissions()->get());
        $user->syncPermissions($permissions);
        $student->user_id = $user->id;
        $student->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Student Created Successfully',
            'student' => $student,
        ], 201);
    }

    public function edit(Student $student)
    {
        $lastpayment_level_ids = Paymentlevel::where('status', 'ACTIVE')->get();
        return view('Admin.Students.form', compact('lastpayment_level_ids', 'student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->merge([
            'mobile' => preg_replace('/[^0-9+]/', '', $request->mobile),
        ]);
        // Custom validation for mobile with '+' sign
        $request->validate([
            'mobile' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if (strpos($value, '+') !== 0) {
                        $fail('Mobile number must start with a "+" sign.');
                    }
                },
            ],
        ], [
            'mobile.required' => 'Mobile number is required.',
            'mobile.numeric' => 'Mobile number must be numeric.',
        ]);
        $this->rules['mobile'] = 'required';
        // $this->rules['image'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8048';
        $this->rules['student_id'] = 'required|unique:students,student_id,' . $student->id;

        $request->validate($this->rules, $this->customMessages);
        $student->fill($request->all());
        if ($request->hasFile('image')) {
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $student->image = Storage::disk('public')->put('photos', $request->file('image'));
        }

        $student->save();

        if (isset($student->id)) {
            $permissions = [];
            $user        = User::where('id', $student->user_id)->first();
            if (! isset($user) && empty($user)) {
                $user             = new User;
                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->mobile     = $request->mobile;
                $user->password   = Hash::make('1234');
                $user->save();
            } else {
                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->mobile     = $request->mobile;
                $user->save();
            }

            $password        = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password  = Hash::make($password);
            $user->save();

            $user->assignRole('Student');
            $role = Role::where('name', 'Student')->first();
            array_push($permissions, $role->permissions()->get());
            $user->syncPermissions($permissions);
            $student->user_id = $user->id;
            $student->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Student Created Successfully',
            'student' => $student,
        ], 201);
    }

    public function show(Student $student)
    {
        $studentFees = $student->studentFees()->orderBy('end_date', 'desc')->get();
        $studentStatuses = $student->studentStatuses()->get();

        $uniqueBatchIds = StudentBatch::where('student_id', $student->id)
            ->select('batch_id')
            ->groupBy('batch_id')   
            ->orderByRaw('MAX(id) DESC')
            ->pluck('batch_id'); 

        $studentBatches = Batch::whereIn('id', $uniqueBatchIds)
            ->orderBy('id', 'desc')
            ->get();

        $studentAttendances = $student->studentAttendances()
            ->with(['coach', 'batch', 'level'])
            ->where('status', '!=', 'NOTMARKED')
            ->orderBy('date', 'desc')
            ->get();

        // Format the student attendance data
        $formattedStudentAttendanceData = $studentAttendances->map(function ($attendance) {
            $student = $attendance->student;
            $coach   = $attendance->coach;
            $batch   = $attendance->batch;
            $level   = $attendance->level;

            return [
                'id'         => $attendance->id,
                'student_id' => $attendance->student->student_id,
                'student'    => [
                    'full_name' => $student ? $student->first_name . ' ' . $student->last_name : 'N/A',
                    'phone'     => $student ? $student->mobile : 'N/A',
                ],
                'coach'      => [
                    'id'   => $coach ? $coach->id : 'N/A',
                    'name' => $coach ? $coach->user->first_name . ' ' . $coach->user->last_name : 'N/A',
                ],
                'batch'      => [
                    'id'   => $batch ? $batch->id : 'N/A',
                    'name' => $batch ? $batch->name : 'N/A',
                ],
                'date'       => Carbon::parse($attendance->date)->format('j, M Y'),
                'time'       => Carbon::parse($attendance->time)->format('g:i A'),
                'status'     => $attendance->status,
            ];
        });

        // Prepare the data to be passed to the view
        $data = [
            'student'            => $student,
            'studentFees'        => $studentFees,
            'studentStatuses'    => $studentStatuses,
            'studentBatches'     => $studentBatches,
            'studentAttendances' => $formattedStudentAttendanceData,
        ];

        // Return the view with the data
        return view('Admin.Students.show', $data);
    }

    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $rules = [
            'student_id' => 'required|exists:students,id',
            'status'     => 'required',
            'employee_id' => 'required_if:status,CHANGECLASS',
            'remark'     => 'required_if:status,CHANGECLASS',
        ];

        $customMessages = [
            'student_id.required' => 'Student ID is required.',
            'student_id.exists'   => 'The selected student does not exist.',
            'status.required'     => 'Please select status.',
            'status.in'           => 'The selected status is invalid.',
            'employee_id.required_if' => 'Please select employee.',
            'employee_id.exists'  => 'The selected employee does not exist.',
            'remark.required_if'  => 'Please enter remark.',
        ];

        $request->validate($rules, $customMessages);
        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        $isCoach = in_array("Coach", $role);

        // Check if the user has the role 'Coach'
        if ($isCoach) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You do not have permission to change the status.',
            ], 403);
        }


        $student = Student::find($request->student_id);
        $privousStatus = $student->status;
        // dd($priorStatus, $request->status);
        // if ($student->status === 'STANDBY' && $request->status !== 'STANDBY') {
            // $studentStatus = StudentStatus::where('student_id', $student->id)->where('type', 'STANDBY')->whereNull('to_date')->latest('from_date')->first();
            // if ($studentStatus) {
            //     $studentStatus->to_date = now();
            //     $studentStatus->save();
            // }
        // }
        $student->status = $request->status;
        $student->save();

        if ($request->status === 'CHANGECLASS') {
            $studentlatestBatch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
            if ($studentlatestBatch) {
                $studentlatestBatch->status = 'INACTIVE';
                $studentlatestBatch->is_fees_due = 0;
                $studentlatestBatch->end_date    = \Carbon\Carbon::today()->format('Y-m-d');
                $studentlatestBatch->end_time   = Carbon::now()->format('H:i:s');
                $studentlatestBatch->save();
            }

            $changeclass = new Changeclass();
            $changeclass->student_id = $student->id;
            $changeclass->employee_id = $request->employee_id;
            $changeclass->employee_ids = array_map('intval', (array) $request->employee_id);
            $changeclass->current_batch_id = $studentlatestBatch->batch_id;
            $changeclass->remark = $request->remark;
            $changeclass->save();

            if ($privousStatus === 'ACTIVE') {
                $priorStudentFees = StudentFee::where('student_id', $student->id)
                    ->latest('end_date')
                    ->first();

                $changeclass->start_date = $priorStudentFees->start_date;
                $changeclass->end_date = $priorStudentFees->end_date;
                $changeclass->receive_date = $priorStudentFees->receive_date;
                $changeclass->currency = $priorStudentFees->currency;
                $changeclass->fees = $priorStudentFees->monthly_fees;
                $changeclass->received_fees = $priorStudentFees->total_amount_paid;
                $changeclass->save();
            }
        }

        // If the new status is 'INACTIVE', update the StudentBatch model
        if ($request->status === 'INACTIVE' || $request->status === 'STANDBY') {
            StudentBatch::where('student_id', $student->id)->update(['status' => 'INACTIVE']);
            $studentlatestBatch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
            if ($studentlatestBatch) {
                $studentlatestBatch->status      = 'INACTIVE';
                $studentlatestBatch->is_fees_due = 0;
                $studentlatestBatch->end_date    = \Carbon\Carbon::today()->format('Y-m-d');
                $studentlatestBatch->end_time   = Carbon::now()->format('H:i:s');
                $studentlatestBatch->save();
            }
        }

        // if ($request->status === 'STANDBY') {
        //     $studentStatus             = new StudentStatus();
        //     $studentStatus->student_id = $student->id;
        //     $studentStatus->type       = 'STANDBY';
        //     $studentStatus->from_date  = now();
        //     $studentStatus->save();
        // }

        $student_statuse = new StudentStatus();
        $student_statuse->student_id = $student->id;
        $student_statuse->type       = $request->status;
        $student_statuse->from_date  = now();
        $student_statuse->save();

        return response()->json([
            'status'  => 'success',
            'message' => $student->first_name . ' has been marked ' . $student->status . ' successfully',
            'student' => $student,
        ], 201);
    }

    public function destroy(Request $request, student $student)
    {
        //dd($request->all());
        $student = student::where('id', $request->student_id)->first();
        if ($student) {
            $student->delete();
            return response()->json([
                'success' => 'student Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'student not found',
            ], 404);
        }
    }

    public function deleteStudentAtendance(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id' => 'required|integer|exists:student_attendances,id',
        ]);
        $attendance = StudentAttendance::findOrFail($request->id);
        $attendance->delete();
        return response()->json(['success' => 'Attendance record deleted successfully.', 'status' => 'success'], 201);
    }

    public function deleteStudentBatchAtendance(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:student_attendances,id',
        ]);
        $attendance = StudentBatch::findOrFail($request->id);
        $attendance->delete();

        return response()->json(['success' => 'Attendance record deleted successfully.', 'status' => 'success'], 201);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $query = Student::orderByDesc('id');

        // Country restriction for non-SuperAdmins
        if (! $user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->toArray();
            $mergedCountries = collect($countries)
                ->map(fn($item) => json_decode($item, true))
                ->flatten()
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (! empty($mergedCountries)) {
                $query->whereIn('country', $mergedCountries);
            }
        }

        if ($request->user_id) {
            $query->where('id', $request->user_id);
        }

        if ($request->country) {
            $query->where('country', $request->country);
        }

        if ($request->batch) {
            $studentIds = StudentBatch::where('batch_id', $request->batch)
                ->where('status', 'ACTIVE')
                ->pluck('student_id');
            $query->whereIn('id', $studentIds);
        }

        if ($request->has('weekday') && $request->weekday != '') {
            $weekday        = $request->weekday;
            $latestBatchIds = $query->with('latestBatch')->get()->pluck('latestBatch.batch_id');
            if (! empty($latestBatchIds)) {
                $batchIds = BatchSchedule::where('weekday', $weekday)
                    ->whereIn('batch_id', $latestBatchIds)
                    ->pluck('batch_id')
                    ->unique();

                $query->whereIn('id', function ($subQuery) use ($batchIds) {
                    $subQuery->select('student_id')
                        ->from('student_batches')
                        ->whereIn('batch_id', $batchIds);
                });
            }
        }

        if ($request->status) {
            if ($request->status == 'CURRENT_DAY') {
                $query->whereHas('studentFees', function ($query) {
                    $query->where('end_date', Carbon::today()->toDateString());
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->coach_id) {
            $studentIds = [];
            foreach ($request->coach_id as $coach) {
                $students = StudentBatch::where('coach_id', $coach)
                    ->where('status', 'ACTIVE')
                    ->pluck('student_id')
                    ->toArray();
                $studentIds = array_merge($studentIds, $students);
            }
            $studentIds = array_unique($studentIds);
            $query->whereIn('id', $studentIds);
        }

        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', trim($startDate))->startOfDay()->format('Y-m-d H:i:s');
            $endDate   = Carbon::createFromFormat('m/d/Y', trim($endDate))->endOfDay()->format('Y-m-d H:i:s');

            $query = $query->whereHas('studentFees', function ($q) use ($startDate, $endDate) {
                $q->where('student_fees.id', '=', function ($subquery) {
                    $subquery->selectRaw('MAX(id)')
                        ->from('student_fees')
                        ->whereColumn('student_fees.student_id', 'students.id');
                });
                $q->whereBetween('end_date', [$startDate, $endDate])
                ->where('status', 'ACTIVE');
            });
        }

        // Fetch all filtered students
        $students = $query->with(['latestBatch.coach.user'])->get();

        // Export to Excel
        return Excel::download(new StudentsExport($students), 'students.xlsx');
    }

    private $rules = [
        'first_name' => 'required',
        // 'last_name' => 'required',
        // 'email' => 'required|email|unique:users,email',

        'mobile'     => 'required|numeric',
        //'address' => 'required',
        'student_id' => 'required|unique:students,student_id',
        'country'    => 'required', // Add rule for countries
        'fees_country' => 'required',
        'timezone'   => 'required',
    ];

    private $customMessages = [
        'first_name.required' => 'First name is required.',
        'mobile.required'     => 'Mobile number is required.',
        'mobile.regex'        => 'Mobile number must be a valid number.',
        //'address.required' => 'Address is required.',
        'student_id.required' => 'Student ID is required.',
        'student_id.unique'   => 'Student ID must be unique.',
        'country.required'    => 'Please select the country', // Add cust
        'fees_country' => 'Please select the Fees country',
        'timezone.required'   => 'Please select the timezone',
    ];






    // ---------------------------------------------------------------- Below Code is for Employee Camera Check History ----------------------------
    
    public function storeaaa(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $data = $request->validate([
            'consented' => 'required|boolean',
            'available' => 'required|boolean',
            'snapshot'  => 'nullable|string'
        ]);

        $employee->camera_consented = $data['consented'];
        $employee->camera_available = $data['available'];

        $snapshotPath = null;

        if ($data['consented'] && $data['available'] && $data['snapshot']) {
            $snapshot = $data['snapshot'];
            if (preg_match('/^data:image\/(\w+);base64,/', $snapshot, $type)) {
                $imageType = $type[1];
                $snapshot = substr($snapshot, strpos($snapshot, ',') + 1);
                $image = base64_decode($snapshot);
                if ($image === false) {
                    return response()->json(['error' => 'invalid_image'], 422);
                }
                $filename = 'camera_snapshots/' . now()->format('Ymd') . '/' . Str::random(12) . '.' . $imageType;
                Storage::disk('public')->put($filename, $image);
                $snapshotPath = $filename;
                $employee->camera_snapshot_path = $filename;
            } else {
                return response()->json(['error' => 'invalid_data_url'], 422);
            }
        }

        $employee->save();

        // create history record
        CameraCheck::create([
            'employee_id'   => $employee->id ?? null,
            'user_id'       => $user->id ?? null,
            'consented'     => (bool) $data['consented'],
            'available'     => (bool) $data['available'],
            'snapshot_path' => $snapshotPath,
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function indexa($employeeId)
    {
        return view('admin.employees.camera_history', compact('employeeId'));
    }

    public function dataa(Request $request, $employeeId)
    {
        $query = CameraCheck::where('employee_id', $employeeId)->orderBy('created_at','desc');
        return DataTables::of($query)
            ->addColumn('snapshot', function($row) {
                if ($row->snapshot_path) {
                    return '<a href="'.asset('storage/'.$row->snapshot_path).'" target="_blank">
                              <img src="'.asset('storage/'.$row->snapshot_path).'" style="width:48px;height:48px;object-fit:cover;border-radius:4px" />
                            </a>';
                }
                return '<span class="text-muted">—</span>';
            })
            ->editColumn('ip_address', fn($r) => $r->ip_address ?? '—')
            ->editColumn('user_agent', fn($r) => str_limit($r->user_agent, 80))
            ->editColumn('created_at', fn($r) => $r->created_at->toDateTimeString())
            ->rawColumns(['snapshot','user_agent'])
            ->make(true);
    }

    // ---------------------------------------------------------------- Above Code is for Employee Camera Check History ----------------------------


}
