<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use DataTables;
use DateTimeZone;
use App\Models\User;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Level;
use App\Models\Student;
use App\Models\DemoLead;
use App\Models\Employee;
use App\Models\DemoSession;
use App\Models\Paymentlevel;
use Illuminate\Http\Request;
use App\Models\NewEnrollment;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use App\Exports\DemoleadsExport;
use App\Models\StudentAttendance;
use App\Mail\ConvertedStudentMail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DemoLeadController extends Controller
{

    public function index()
    {
        $levels = Level::where('status', 'ACTIVE')->get();
        $coaches = Coach::where('status', 'ACTIVE')->get();
        $employees = Employee::get();
        return view('Admin.DemoLeads.index', compact('levels', 'coaches', 'employees'));
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $query = DemoLead::where('id', '!=', 0)
            ->where('date', '!=', null)
            ->where('is_hide', $request->is_hide)->orderBy('date', 'desc')->orderBy('time', 'desc');

        if ($request->employee_id) {
            $query->where('created_by', $request->employee_id);
        }

        /* * What: Filter demo leads based on user roles and associated countries.
        * Why: To ensure that users only see demo leads relevant to their assigned countries,
        *      enhancing data security and relevance.
        */
        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->first();
            if ($countries) {
                $countriesArray = json_decode($countries, true);
                if (is_array($countriesArray) && !empty($countriesArray)) {
                    $query->whereIn('country', $countriesArray);
                }
            }
        }

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'CONVERTED');
        }
        if ($request->country) {
            $query->where('country', $request->country);
        }
        if ($request->level) {
            $query->whereHas('demoSessions', function ($query) use ($request) {
                $query->where('status', 'ACTIVE')
                    ->where('level_id', $request->level);
            });
        }
        if ($request->coach) {
            $query->whereHas('demoSessions', function ($q) use ($request) {
                $q->where('coach_id', $request->coach)
                    ->whereRaw('demo_sessions.id = (SELECT MAX(id) FROM demo_sessions WHERE demolead_id = demoleads.id)');
            });
        }
        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        return DataTables::eloquent($query)

            ->editColumn('first_name', function ($demolead) {
                $dateTime = new DateTime($demolead->date . ' ' . $demolead->time);
                $formattedDateTime = $dateTime->format('d-M-Y | h:i A');
                $kidsDateTime = new DateTime($demolead->kids_date . ' ' . $demolead->kids_time);
                $kidsFormattedDateTime = $kidsDateTime->format('d-M-Y | h:i A');

                $kidsTimeZone = $demolead->kids_time_zone;

                $message = "Dear {$demolead->first_name} {$demolead->last_name} ({$demolead->country}), You Booked a 25 mins Chess Trial Class on {$formattedDateTime} IST, With Archer Chess Academy. Your timing will be ({$kidsFormattedDateTime} | {$kidsTimeZone}).\n\n"
                    . "Kindly confirm that you have read and agree to our program details.\n\n"
                    . "Once confirmed, we will reserve your slot and you will get all the meeting details on the student Dashboard. The trial class is absolutely free and it's 25-minute level assessment. If you decide to enroll after the trial, the provided pricing and details will apply.\n\n"
                    . "Thanks & Regards\n"
                    . "Team Archer!";

                $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $demolead->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                if ($demolead->status == 'DEMO DONE') {
                    $whatsappLink = '';
                }

                $latestDemoSession = $demolead->demosessions()->latest()->first();

                $latestStudentAttendance = StudentAttendance::where('demolead_id', $demolead->id)
                    ->latest()
                    ->first();

                $remark = $latestStudentAttendance ? $latestStudentAttendance->remark : '';

                $short_remark = strlen($remark) > 10 ? substr($remark, 0, 5) . '...' : $remark;

                $escapedFullRemark = htmlspecialchars($remark, ENT_QUOTES, 'UTF-8');
                $escapedShortRemark = htmlspecialchars($short_remark, ENT_QUOTES, 'UTF-8');

                return '
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div class="flex-grow-1">
                        <span class="fw-bold">' . htmlspecialchars($demolead->first_name . ' ' . $demolead->last_name) . '</span>
                            (' . $demolead->country . ')
                    </div>
                    <div class="mx-3 text-muted">
                        <span>' . (!empty($latestDemoSession->level->name) ? htmlspecialchars($latestDemoSession->level->name) : '') . '</span>
                        ' . (!empty($short_remark)
                    ? ' - <span>
                                    <span class="remark-short">' . $escapedShortRemark . '</span>
                                    <span class="remark-full d-none">' . $escapedFullRemark . '</span>
                                    <a href="#" class="demoLeadEnquiry-remark" data-remark="' . $escapedFullRemark . '"><b class"text-primary">Read more</b></a>
                            </span>'
                    : '') . '

                    </div>
                    <div class="d-flex justify-content-end">
                        ' . $whatsappLink . '
                    </div>
                </div>';
            })
            ->editColumn('age', function ($demolead) {
                return $demolead->age;
            })
            ->editColumn('mobile', function ($demolead) {
                return '<img src="/backend/dist/images/svgs/icon-phone.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $demolead->mobile;
            })
            ->editColumn('address', function ($demolead) {
                return $demolead->address;
            })
            ->editColumn('remark', function ($demolead) {
                if (strlen($demolead->remark) > 30) {
                    return substr($demolead->remark, 0, 25) . '..<a href="#" data-id="' . $demolead->id . '" class="demoLeadEnquiry-remark" style="color:red">read more</a>';
                } else {
                    return $demolead->remark;
                }
            })
            ->editColumn('reason', function ($demolead) {
                if (strlen($demolead->reason) > 30) {
                    return substr($demolead->reason, 0, 25) . '..<a href="#" data-id="' . $demolead->id . '" class="demoLeadEnquiry-reason" style="color:red">read more</a>';
                } else {
                    return $demolead->reason;
                }
            })
            ->editColumn('date_time', function ($demolead) {
                $dateTime = new DateTime($demolead->date . ' ' . $demolead->time);
                return $dateTime->format('d-M-Y | h:i A');
            })
            ->editColumn('created_by', function ($demolead) {
                if ($demolead->createdBy) {
                    $name = $demolead->createdBy->first_name . ' ' . $demolead->createdBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-primary text-primary fs-1">N/A</span>';
            })
            ->editColumn('updated_by', function ($demolead) {
                if ($demolead->updatedBy) {
                    $name = $demolead->updatedBy->first_name . ' ' . $demolead->updatedBy->last_name;
                    return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1"><i class="ti ti-user-circle"></i> &nbsp; ' . $name . '</span>';
                }
                return '<span class="mb-1 badge font-medium bg-light-secondary text-secondary fs-1">N/A</span>';
            })
            ->editColumn('status', function ($demolead) {
                switch ($demolead->status) {
                    case 'SCHEDULED':
                        $badgeColor = 'primary';
                        break;
                    case 'RESCHEDULED':
                        $badgeColor = 'warning';
                        break;
                    case 'DEMO DONE':
                        $badgeColor = 'success';
                        break;
                    case 'CANCELLED':
                        $badgeColor = 'danger';
                        break;
                    case 'CONVERTED':
                        $badgeColor = 'info';
                        break;
                    case 'ROWLEAD':
                        $badgeColor = 'secondary';
                        break;
                    case 'INTERESTED':
                        $badgeColor = 'success';
                        break;
                    case 'NOT INTERESTED':
                        $badgeColor = 'danger';
                        break;
                    default:
                        $badgeColor = 'dark';
                }
                $statusButton = '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 demolead-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $demolead->id . '" data-id="' . $demolead->id . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $demolead->status . '</button>';

                $latestDemoSession = $demolead->demosessions()->latest()->first();
                $levelId = $latestDemoSession->level->name ?? '  ';

                $message = "Dear $demolead->first_name  $demolead->last_name ($demolead->country), Thanks for taking chess trial class with Archer Chess Academy. How was the trial class. Your feedback is very valuable for us. We will waiting for your response.

Dear Parents, Your kid is in {$levelId}. We will start with {$levelId}. For more details our executive will contact you shortly.
Thanks & regards
Archer Chess Academy";

                $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $demolead->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                if ($demolead->status != 'DEMO DONE') {
                    $whatsappLink = '';
                }

                return '<div class="d-flex justify-content-between">
                            ' . $statusButton . ' &nbsp;
                            <div class="d-flex justify-content-end"> ' . $whatsappLink . '</div>
                        </div>';
            })
            ->addColumn('demosession', function ($demolead) {
                $total_demo_session_counts = DemoSession::where('demolead_id', $demolead->id)->count();
                $demosession = DemoSession::where('demolead_id', $demolead->id)->where('status', 'ACTIVE')->orderByDesc('id')->first();
                if ($demosession) {
                    $demosession_txt = '<a href="/admin/demoleads/' . $demolead->id . '/demo_sessions" class="badge bg-success fs-1">Demo Session (' . $total_demo_session_counts . ')  </a>';
                    if ($demosession->coach_id == null) {
                        // dd($demosession);
                        return $demosession_txt;
                    }
                    $coach = $demosession->coach->user->first_name . ' ' . $demosession->coach->user->last_name;
                    return $demosession_txt . '<br>' . $coach;
                } else {
                    return '<a href="/admin/demoleads/' . $demolead->id . '/demo_sessions" class="badge bg-success fs-1"><i class="ti ti-box-multiple"></i> &nbsp; Demo Session </a>';
                }
            })
            ->addColumn('convert', function ($demolead) {
                if ($demolead->status == 'CONVERTED') {
                    return '<a hred="#" class="badge bg-warning fs-1">Aleady Converted</a>';
                } else {
                    return '<a href="' . route('admin.demoleads.convert', ['demolead' => $demolead->id]) . '" class="badge bg-primary fs-1"><i class="ti ti-school"></i> &nbsp; Convert To Student </a>';
                }
            })
            ->addColumn('action', function ($demolead) {
                $total_demo_session_counts = DemoSession::where('demolead_id', $demolead->id)->count();
                if ($demolead->is_hide == 1) {
                    return '<a href="#" title="Delete" class="badge bg-danger fs-1" data-demolead-id="' . $demolead->id . '">Already Deleted</a>';
                }



                $delete = '';
                $edit = '<a href="' . route('admin.demoleads.edit', ['demolead' => $demolead->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $show = '<a href="' . route('admin.demoleads.show', ['demolead' => $demolead->id]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="demoleads" data-title="Demo Lead Details" data-route-key="' . $demolead->id . '"><i class="fa fa-eye"></i></a>';

                if (auth()->user()->hasRole('SuperAdmin')) {
                    $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-demolead-id="' . $demolead->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }
                if ($total_demo_session_counts > 0) {
                    $edit = '';
                }
                return $edit . ' ' . $show . '  ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['first_name', 'age', 'mobile', 'address', 'status', 'remark', 'reason', 'action', 'convert', 'demosession', 'created_by', 'updated_by'])
            ->setRowId('id')
            ->toJson(JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function show($id)
    {
        $demolead = DemoLead::find($id);
        $demosessions = DemoSession::where('demolead_id', $demolead->id)->get();
        return view('Admin.DemoLeads.show', compact('demolead', 'demosessions'));
    }

    public function create()
    {
        return view('Admin.DemoLeads.form');
    }

    /*
    * What: Store a new demo lead and create an associated user account.
    * Why: To manage demo leads effectively and ensure they have access to the platform.
    */
    public function store(Request $request)
    {
        // ✅ Sanitize and validate
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

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            // 'email' => [
            //     'required',
            //     'email',
            //     function ($attribute, $value, $fail) {
            //         if (
            //             User::where('email', $value)->exists() ||
            //             Student::where('email', $value)->exists()
            //         ) {
            //             $fail('The email has already been taken.');
            //         }
            //     },
            // ],
            'email'      => 'required|email',
            'mobile'     => 'required|numeric',
            'country'    => 'required|string|max:255',
            'date' => 'required',
            'time' => 'required',
        ], [
            'first_name.required' => 'First name is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Email must be valid.',
            // 'email.email'         => 'Email must be a valid email address.',
            // 'email.unique'       => 'This email is already taken.',
            'mobile.required'     => 'Mobile number is required.',
            'mobile.numeric'      => 'Mobile number must be numeric.',
            'country.required'   => 'Country is required.',
            'date.required' => 'Date is required.',
            'time.required' => 'Time is required.',
        ]);

        // Create Demo Lead
        $demoLead = new DemoLead();
        $demoLead->fill($request->all());
        $demoLead->status = 'ROWLEAD';
        $demoLead->save();

        // Create user (if not exists)
        $user = new User();
        $user->first_name = $demoLead->first_name;
        $user->mobile     = $demoLead->mobile;
        $user->email     = $request->email;

        // Set password and device_id (after user ID exists)
        $password         = 'archer@' . $user->id;
        $user->device_id  = $password;
        $user->password   = Hash::make($password);
        $user->save();


        $password        = 'archer@' . $user->id;
        $user->device_id = $password;
        $user->password  = Hash::make($password);
        $user->save();

        // Assign Student role & permissions
        $role = Role::where('name', 'Student')->first();
        if ($role) {
            $user->assignRole($role);
            $user->syncPermissions($role->permissions->pluck('name')->toArray());
        }

        // ✅ Link demo lead to user
        $demoLead->update(['user_id' => $user->id]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Demo Lead created successfully.',
            'data'    => $demoLead,
        ], 201);
    }


    public function edit(DemoLead $demolead)
    {
        return view('Admin.DemoLeads.form', compact('demolead'));
    }

    /*
    * What: Check if a mobile number is unique across DemoLead and Student tables.
    * Why: To prevent duplicate entries and ensure data integrity.
    */
    public function checkMobileUniqueness(Request $request)
    {
        $mobile = preg_replace('/\s+/', '', $request->input('mobile', ''));
        $demoLeadId = $request->input('demolead_id');
        if (!$mobile) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mobile number is required.',
            ], 400);
        }
        // ✅ Check in DemoLead (excluding current ID if provided)
        $demoLead = DemoLead::where('mobile', $mobile)
            ->when($demoLeadId, fn($q) => $q->where('id', '!=', $demoLeadId))
            ->first();
        if ($demoLead) {
            return response()->json([
                'status'  => 'error',
                'message' => "This mobile number is already used by Demo Lead: {$demoLead->first_name} {$demoLead->last_name}",
            ], 400);
        }
        // ✅ Check in Student table
        $student = Student::where('mobile', $mobile)->first();
        if ($student) {
            return response()->json([
                'status'  => 'error',
                'message' => "This mobile number is already used by Student: {$student->first_name} {$student->last_name}",
            ], 400);
        }
        // ✅ No duplicates found
        return response()->json([
            'status'  => 'success',
            'message' => 'This mobile number is available.',
        ]);
    }



    public function processDateTimeZone(Request $request)
    {
        $date = $request->input('date');
        $time = $request->input('time');
        $targetTimeZone = $request->input('timeZone');
        $sourceTimeZone = new DateTimeZone('Asia/Kolkata');

        // --------------------------------- Start Time ---------------------------------
        $indiaStartTime = $date . ' ' . $time;

        // Use Carbon::parse() for flexibility
        try {
            $starttime = Carbon::parse($indiaStartTime, 'Asia/Kolkata');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid start time format.'], 400);
        }

        // Convert start time to the target timezone
        $convertStartTimeInTimeZone = $starttime->setTimezone(convertTimeZoneString($targetTimeZone));
        $convertedStartTime = $convertStartTimeInTimeZone->format('Y-m-d H:i:s');

        return response()->json([
            'convertedDateTime' => $convertedStartTime,
            'targetTimeZone' => $targetTimeZone,
        ]);
    }

    /*
    * What: Update a demo lead and synchronize with the associated user account.
    * Why: To ensure data consistency between demo leads and user accounts.
    */
    public function update(Request $request, DemoLead $demolead)
    {
        // ✅ Sanitize and validate
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

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            // 'email' => [
            //     'required',
            //     'email',
            //     function ($attribute, $value, $fail) use ($demolead) {
            //         // Check in users, ignoring current user_id
            //         $userExists = User::where('email', $value)
            //             ->where('id', '!=', $demolead->user_id)
            //             ->exists();

            //         // Check in students table
            //         $studentExists = Student::where('email', $value)->exists();

            //         if ($userExists || $studentExists) {
            //             $fail('This email is already taken.');
            //         }
            //     },
            // ],
            'email'      => 'required|email',
            'mobile'     => 'required|numeric',
            'country'    => 'required|string|max:255',
            'date'       => 'required',
            'time'       => 'required',
        ], [
            'first_name.required' => 'First name is required.',
            'mobile.required'     => 'Mobile number is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Email must be a valid email address.',
            // 'email.unique'        => 'This email is already taken.',
            'mobile.numeric'      => 'Mobile number must be a valid number.',
            'country.required'   => 'Country is required.',
            'date.required'      => 'Date is required.',
            'time.required'      => 'Time is required.',
        ]);

        DB::transaction(function () use ($request, $demolead) {
            // ✅ Update Demo Lead
            $demolead->fill($request->all());
            $demolead->save();

            // ✅ Find or create user
            $user = User::find($demolead->user_id);
            if (!$user) {
                $user = new User();
            }

            $user->first_name = $demolead->first_name;
            $user->email      = $request->email;
            $user->mobile     = $demolead->mobile;
            $user->device_id = 'archer@';
            $user->save();

            if ($user->device_id == 'archer@') {
                $password        = 'archer@' . $user->id;
                $user->device_id = $password;
                $user->password  = Hash::make($password);
                $user->save();
            }

            // ✅ Update password & device ID only if new user created
            if (!$demolead->user_id) {
                $password        = 'archer@' . $user->id;
                $user->device_id = $password;
                $user->password  = Hash::make($password);
                $user->save();
            }

            // ✅ Assign "Student" role and permissions
            $role = Role::where('name', 'Student')->first();
            if ($role) {
                $user->assignRole($role);
                $user->syncPermissions($role->permissions->pluck('name')->toArray());
            }

            // ✅ Link DemoLead to User
            $demolead->update(['user_id' => $user->id]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Demo Lead updated successfully.',
            'data'    => $demolead,
        ], 200);
    }


    /*
    * What: Change the status of a demo lead with optional reason.
    * Why: To track the progress of demo leads and manage their lifecycle effectively.
    */
    public function changeStatus(Request $request)
    {
        $demolead = DemoLead::find($request->demolead_id);
        $demolead->status = $request->status;
        $demolead->reason = $request->reason;
        $demolead->save();

        return response()->json([
            'status' => 'success',
            'message' => $demolead->first_name . ' has been marked ' . $demolead->status . ' successfully',
            'demolead' => $demolead,
        ], 201);
    }

    public function convertToStudent(Request $request, $demoleadId)
    {
        $demolead = DemoLead::findOrFail($demoleadId);
        $levels = Level::where('status', 'ACTIVE')->get();
        $lastpayment_levels = Paymentlevel::where('status', 'ACTIVE')->get();
        $employees = Employee::get();
        $batches = Batch::where('status', 'ACTIVE')->get();
        return view('Admin.DemoLeads.convertform', compact('demolead', 'levels', 'lastpayment_levels', 'employees', 'batches'));
    }

    /*
    * What: Convert a demo lead into a student, creating necessary records.
    * Why: To streamline the conversion process and ensure all related data is properly created and linked.
    */
    public function saveConvertedStudent(Request $request)
    {
        // dd($request->all());
        $this->rules = [
            'student_id' => 'required|unique:students',
            'employee_ids'    => 'required',
            // 'batch_id'      => 'required',
            // 'start_date'    => 'required|date',
            // 'end_date'      => 'required|date|after_or_equal:start_date',
            'receive_date' => 'required|date',
            'fees'          => 'required|numeric|min:0',
            'received_fees' => 'required|numeric|min:0',
            'currency'      => 'required',
            'remark'        => 'required',
        ];
        $this->customMessages = [
            'student_id.required' => 'The student ID is required.',
        ];
        $request->validate($this->rules, $this->customMessages);


        $demolead = DemoLead::find($request->demolead_id);
        if (!$demolead) {
            return response()->json([
                'status' => 'error',
                'message' => 'Demo lead not found.',
            ], 404);
        }

        $user = User::find($demolead->user_id);
        if (empty($user)) {
            $user = new User;
            $user->first_name = $demolead->first_name;
            $user->mobile = $demolead->mobile;
            $user->password = Hash::make('12345678');
            $user->save();

            $password = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password = Hash::make($password);
            $user->save();

            $permissions = [];
            $user->assignRole('Student');
            $role = Role::where('name', 'Student')->first();
            array_push($permissions, $role->permissions()->get());
            $user->syncPermissions($permissions);
        } else {
            $password = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password = Hash::make($password);
            $user->save();
        }

        $permissions = [];
        $user->assignRole('Student');
        $role = Role::where('name', 'Student')->first();
        array_push($permissions, $role->permissions()->get());
        $user->syncPermissions($permissions);

        $demolead->user_id = $user->id;
        $demolead->save();

        $first_name = $demolead->first_name;
        $student = new Student;
        $student->first_name = !empty($first_name) ? $first_name : '';
        $student->age = !empty($demolead->age) ? $demolead->age : '';
        $student->mobile = !empty($demolead->mobile) ? $demolead->mobile : '';
        $student->city = !empty($demolead->city) ? $demolead->city : '';
        $student->country = !empty($demolead->country) ? $demolead->country : '';
        $student->lastpayment_level_id = !empty($request->lastpayment_level_id) ? $request->lastpayment_level_id : null;

        $student->status = 'INACTIVE';
        if ($request->has('student_id') && !empty($request->student_id)) {
            $student->student_id = $request->student_id;
        }
        if ($request->has('level_id') && !empty($request->level_id)) {
            $student->level_id = $request->level_id;
        }

        $student->portal_password = !empty($request->portal_password) ? $request->portal_password : '';
        $student->currency = !empty($request->currency) ? $request->currency : '';
        $student->monthly_fees = !empty($request->monthly_fees) ? $request->monthly_fees : '';
        $student->timezone = !empty($demolead->kids_time_zone) ? $demolead->kids_time_zone : '';
        $student->user_id = $user->id;
        $student->save();

        $demolead->status = 'CONVERTED';
        $demolead->save();

        $new_enrollment = new NewEnrollment;
        $new_enrollment->student_id = $student->id;
        $new_enrollment->created_by = Auth::user()->id;
        $new_enrollment->employee_ids    = $request->employee_ids;
        $new_enrollment->batch_id      = $request->batch_id;
        $new_enrollment->remark        = $request->remark;
        $new_enrollment->start_date    = $request->start_date;
        $new_enrollment->end_date      = $request->end_date;
        $new_enrollment->receive_date = $request->receive_date;
        $new_enrollment->fees          = $request->fees;
        $new_enrollment->received_fees = $request->received_fees;
        $new_enrollment->currency      = $request->currency;
        $new_enrollment->save();

        Mail::to($user->email)->send(new ConvertedStudentMail($student));

        return response()->json([
            'status' => 'success',
            'message' => 'Demo lead converted to student.',
        ]);
    }

    public function destroy(Request $request, DemoLead $demolead)
    {
        $demolead = DemoLead::where('id', $request->demolead_id)->first();
        if ($demolead) {
            $demolead->is_hide = 1;
            $demolead->save();

            return response()->json([
                'success' => 'demolead Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'demolead not found',
            ], 404);
        }
    }

    public function getTimezones(Request $request)
    {
        $country = $request->query('country');
        $timezones = getTimezones();
        if ($country && isset($timezones[$country])) {
            return response()->json([$country => $timezones[$country]]);
        }
        return response()->json($timezones);
    }

    public function getRemark(Request $request)
    {
        $id = $request->input('id');
        $demolead = DemoLead::find($id);

        if ($demolead) {
            return response()->json([
                'status' => 'success',
                'remark' => $demolead->remark,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Remark not found',
            ]);
        }
    }

    public function getReason(Request $request)
    {
        $id = $request->input('id');
        $demolead = DemoLead::find($id);

        if ($demolead) {
            return response()->json([
                'status' => 'success',
                'reason' => $demolead->reason,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Reason not found',
            ]);
        }
    }

    /*
    * What: Export filtered demo leads to an Excel file.
    * Why: To allow users to download and analyze demo lead data offline.
    */
    public function export(Request $request)
    {
        $user = Auth::user();

        $query = DemoLead::where('id', '!=', 0)
            ->where('is_hide', $request->is_hide)
            ->orderByDesc('id');

        if ($request->sequence) {
            $query->orderBy('date', $request->sequence)
                ->orderBy('time', $request->sequence);
        }

        if ($request->employee_id) {
            $query->where('created_by', $request->employee_id);
        }

        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->first();
            if ($countries) {
                $countriesArray = json_decode($countries, true);
                if (is_array($countriesArray) && !empty($countriesArray)) {
                    $query->whereIn('country', $countriesArray);
                }
            }
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->country) {
            $query->where('country', $request->country);
        }

        if ($request->level) {
            $query->whereHas('demoSessions', function ($query) use ($request) {
                $query->where('status', 'ACTIVE')
                    ->where('level_id', $request->level);
            });
        }

        if ($request->coach) {
            $query->whereHas('demoSessions', function ($q) use ($request) {
                $q->where('coach_id', $request->coach)
                    ->whereRaw('demo_sessions.id = (SELECT MAX(id) FROM demo_sessions WHERE demolead_id = demoleads.id)');
            });
        }

        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $demoLeads = $query->get();

        // Return Excel file with proper headers for AJAX download
        return Excel::download(new DemoleadsExport($demoLeads), 'demoleads.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Disposition' => ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        ]);
    }

    private $rules = [
        'first_name' => 'required',
        'date' => 'required|date',
        'time' => 'required',
        'country' => 'required', // Add rule for countries
    ];

    private $customMessages = [
        'first_name.required' => 'First name is required.',
        'date.required' => 'Free date is required.',
        'date.date' => 'Free date must be a valid date.',
        'time.required' => 'Free time is required.',
        'country.required' => 'Please select the country', // Add cust
    ];
}
