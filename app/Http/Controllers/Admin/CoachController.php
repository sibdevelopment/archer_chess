<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use App\Models\CoachAvailability;
use App\Http\Controllers\Controller;

class CoachController extends Controller
{
    public function index()
    {
        return view('Admin.Coachs.index');
    }

    public function data(Request $request)
    {
        $query = Coach::select('coachs.*')
        ->join('users', 'coachs.user_id', '=', 'users.id')
        ->orderBy('coachs.id', 'desc')
        ->with('user');

        $user    = auth()->user();
        $role    = $user->getRoleNames()->toArray();
        if (!$user->roles()->where('name', 'SuperAdmin')->exists()) {
            $countries = $user->roles()->pluck('countries')->flatten()->filter()->first();
            if ($countries) {
                $countriesArray = json_decode($countries, true);
                if (is_array($countriesArray) && !empty($countriesArray)) {
                    $query->where(function ($q) use ($countriesArray) {
                        foreach ($countriesArray as $country) {
                            $q->orWhere('country', 'LIKE', '%' . $country . '%');
                        }
                    });
                }
            }            
        }

        if ($request->status) {
            $query->where('coachs.status', $request->status); // Specify the table name here
        }
        if ($request->has('day_of_week') && $request->day_of_week) {
            $query->whereHas('coach_availabilities', function ($subQuery) use ($request) {
                $subQuery->where('day_of_week', $request->day_of_week);
            });
        }

        if ($request->country) {
            $country = $request->country;
            $query->whereNotNull('country')->where(function ($query) use ($country) {
                $query->whereRaw('json_valid(country) AND json_contains(country, ?)', ['["' . $country . '"]'])
                    ->orWhere(function ($query) use ($country) {
                        $query->whereRaw('NOT json_valid(country)')->where('country', $country);
                    });
            });
        }

        return DataTables::eloquent($query)
            ->editColumn('first_name', function ($coach) {
                return $coach->user->first_name . ' ' . $coach->user->last_name;
            })
            ->editColumn('email', function ($coach) {
                return '<img src="/backend/dist/images/svgs/icon-mail.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $coach->user->email;
            })
            ->editColumn('mobile', function ($coach) {
                return '<img src="/backend/dist/images/svgs/icon-phone.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $coach->user->mobile;
            })
            ->editColumn('zoom_id', function ($coach) {
                return $coach->zoom_id;
            })
            ->editColumn('zoom_password', function ($coach) {
                return $coach->zoom_password;
            })
            ->editColumn('portal_id', function ($coach) {
                return $coach->portal_id;
            })
            ->editColumn('portal_password', function ($coach) {
                return $coach->portal_password;
            })
            ->editColumn('country', function ($batch) {
                $countries = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;
                return '<img src="/backend/dist/images/svgs/icon-connect.svg" width="20" height="20" class="" alt="" /> &nbsp; ' . $countries;
            })
            ->editColumn('status', function ($coach) {
                if ($coach->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input coach-status-switch" type="checkbox" checked data-routekey="' . $coach->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input coach-status-switch" type="checkbox" data-routekey="' . $coach->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($coach) {
                $edit = '<a href="' . route('admin.coaches.edit', ['coach' => $coach->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $show = '<a href="' . route('admin.coaches.show', ['coach' => $coach->route_key]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="coaches" data-title="Coach Details" data-route-key="' . $coach->route_key . '"><i class="fa fa-eye"></i></a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    // $delete = '<a href="#" title="Delete"   class="badge bg-danger fs-1 delete-btn"  data-coach-id="' . $coach->id . '"><i class="fa fa-trash  fs-1"></i></a>';
                }

                $coachLogin = '<a href="' 
                    . route('coach.login', ['user_id' => $coach->user->id]) 
                    . '" title="Login as Coach" class="badge bg-secondary fs-1" target="_blank">
                    <i class="fa fa-sign-in-alt"></i>
                </a>';
                
                return $edit . '  ' . $show . ' ' . $delete . ' ' . $coachLogin;
            })
            ->addColumn('coachavailability', function ($coach) {
                return '<a href="/admin/coaches/' . $coach->route_key . '/coach_availabilities" class="badge bg-success fs-1"><i class="ti ti-box-multiple"></i> &nbsp; Availability </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'first_name', 'last_name', 'mobile', 'email', 'zoom_id','country','zoom_password', 'portal_id', 'portal_password', 'action', 'coachavailability', 'full_name'])
            ->setRowId('id')
            ->make(true);
    }

    public function show(Coach $coach)
    {
        // Fetch coach availabilities
        $coach_availabilities = CoachAvailability::with('periods')
            ->where('coach_id', $coach->id)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        // Fetch batches
        $batches = Batch::where('coach_id', $coach->id)->where('status', '!=', 'INACTIVE')->get();

        // Fetch attendance data without date range filtering
        $completedBatchData = CoachAttendance::where('coach_id', $coach->id)
            ->where('type', 'Batch')
            ->where('status', 'COMPLETED')
            ->select('batch_id', \DB::raw('count(*) as count'), \DB::raw('GROUP_CONCAT(date) as dates'), \DB::raw('GROUP_CONCAT(time) as times'))
            ->groupBy('batch_id')
            ->orderByDesc('batch_id')   
            ->get()
            ->keyBy('batch_id');


        $completedBatchIds = $completedBatchData->keys();

        $batches = Batch::whereIn('id', $completedBatchIds)
        ->orderBy('id', 'desc')
        ->get();

        $batchData = $batches->map(function ($batch) use ($completedBatchData) {
            $totalActiveStudents = $batch->studentBatches()->eligibleOn(Carbon::today())->count();
            $activeStudentBatches = $batch->studentBatches()->eligibleOn(Carbon::today())->get();
            $levelNames = $activeStudentBatches->map(function ($studentBatch) {
                return $studentBatch->level->name;
            })->unique()->implode(', ');

            $allStudentBatches = $batch->studentBatches()->get();
            $allLevelNames = $allStudentBatches->map(function ($studentBatch) {
                return $studentBatch->level->name;
            })->unique()->implode(', ');

            $latestCompletedSession = CoachAttendance::where('batch_id', $batch->id)
                ->where('status', 'COMPLETED')
                ->orderByDesc('id')
                ->first();
            $totalSessionsCompleted = $latestCompletedSession ? $latestCompletedSession->number_of_batch_sessions : 0;

            $studentBatch = $batch->studentBatches()
                ->where('status', 'ACTIVE')
                ->orderByDesc('updated_at')
                ->first();

            if (!$studentBatch) {
                $studentBatch = $batch->studentBatches()
                    ->orderByDesc('updated_at')
                    ->first();
            }

            $timeline = '';
            if ($batch) {
                $startDate = Carbon::parse($batch->start_date)->format('j, M Y');
                $endDate = Carbon::parse($batch->end_date)->format('j, M Y');
                $timeline = $startDate . ' - ' . $endDate;
            }

            $completedDates = explode(',', $completedBatchData[$batch->id]->dates ?? '');
            $completedTimes = explode(',', $completedBatchData[$batch->id]->times ?? '');

            $formattedDates = array_map(function ($date) {
                return Carbon::parse($date)->format('j, M Y');
            }, $completedDates);

            // dd($completedBatchData);

            $formattedTimes = array_map(function ($time) {
                return Carbon::parse($time)->format('g:i A');
            }, $completedTimes);

            $badgeColor = 'secondary';
            switch ($batch->status) {
                case 'INACTIVE':
                    $badgeColor = 'warning';
                    break;
                case 'STANDBY':
                    $badgeColor = 'danger';
                    break;
                case 'ACTIVE':
                    $badgeColor = 'success';
                    break;
            }

            $statusButton = '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1 batch-status-switch" data-bs-toggle="modal" data-bs-target="#statusChangeModal" data-routekey="' . $batch->id . '" data-id="' . $batch->id . '"><i class="ti ti-analyze"></i> &nbsp;  ' . $batch->status . '</button>';

            $countryString = is_array($batch->country) ? implode(', ', $batch->country) : $batch->country;

            return [
                'id' => $batch->id,
                'name' => $batch->name,
                'version' => $batch->version,
                'country' => $countryString,
                'kids_zone_name' => $batch->kids_zone_name,
                'created_by' => $batch->createdBy ? $batch->createdBy->first_name . ' ' . $batch->createdBy->last_name : 'N/A',
                'updated_by' => $batch->updatedBy ? $batch->updatedBy->first_name . ' ' . $batch->updatedBy->last_name : 'N/A',
                'status' => $statusButton,
                'total_active_students' => $totalActiveStudents,
                'level_names' => $batch->status === 'INACTIVE' ? $allLevelNames : $levelNames,
                'timeline' => $timeline,
                'completed_count' => $completedBatchData[$batch->id]->count ?? 0,
                'completed_dates' => $formattedDates,
                'completed_times' => $formattedTimes,
            ];
        });

        // dd($batchData);

        return view('Admin.Coachs.show', compact('coach', 'coach_availabilities', 'batches', 'batchData'));
    }

    public function create()
    {
        return view('Admin.Coachs.form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate($this->rules, $this->customMessages);

        // New User ::
        $user = new User;
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        // Assign the 'Coach' role to the user ::
        $user->assignRole('Coach');
        $role = Role::where('name', 'Coach')->first();
        $permissions = $role->permissions()->get();
        $user->syncPermissions($permissions);

        // New Coach with the user_id set to the ID of the user ::
        $coach = new Coach;
        $coach->fill($request->all());
        $coach->user_id = $user->id;
        $coach->decrypt_password = $request->password;
        $coach->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Coach Created Successfully',
            'slider' => $coach,
        ], 201);
    }

    public function edit(Coach $coach)
    {
        $systemRoles = getSystemRoles();
        $roles = Role::whereNotIn('name', $systemRoles)->get();
        $decrypt_password = $coach->decrypt_password;
        return view('Admin.Coachs.form', compact('coach', 'roles', 'decrypt_password'));
    }

    public function update(Request $request, Coach $coach)
    {
        $this->rules['email'] = 'required|email|unique:users,email,' . $coach->user->id;
        $this->rules['mobile'] = 'required|digits:10|unique:users,mobile,' . $coach->user->id;
        $this->rules['password'] = 'sometimes|nullable|min:6';
        $this->rules['password_confirmation'] = 'sometimes|nullable|same:password';
        // $this->rules['zoom_user_id'] = 'sometimes|nullable|unique:coachs,zoom_user_id,' . $coach->id;
        $this->rules['portal_id'] = 'sometimes|nullable|unique:coachs,portal_id,' . $coach->id;

        $request->validate($this->rules, $this->customMessages);
        $user = User::find($coach->user_id);
        $user->fill($request->all());
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        $coach->fill($request->all());
        if ($request->password) {
            $coach->decrypt_password = $request->password;
        }

        // dd($coach);
        
        $coach->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Coach Updated Successfully',
            'slider' => $coach,
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $coach = Coach::findByKey($request->route_key);
        $coach->status = $request->status;
        $coach->save();

        return response()->json([
            'status' => 'success',
            'message' => $coach->first_name . ' has been marked ' . $coach->status . ' successfully',
            'coach' => $coach,
        ], 201);
    }

    public function destroy(Request $request, Coach $coach)
    {
        //dd($request->all());
        $coach = Coach::where('id', $request->coach_id)->first();
        if ($coach) {
            $coach->delete();
            return response()->json([
                'success' => 'Coach Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'Coach not found',
            ], 404);
        }
    }

    private $rules = [
        'first_name' => 'required|regex:/^[a-zA-Z\s]+$/',
        'country' => 'required',
        'first_name.required' => 'First Name is required',
        'last_name' => 'sometimes|nullable|regex:/^[a-zA-Z\s]+$/',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|digits:10|unique:users,mobile',
        // 'zoom_id' => 'sometimes|nullable|unique:coachs,zoom_id',
        'zoom_user_id' => 'required',
        'zoom_password' => 'sometimes|nullable',
        'portal_id' => 'sometimes|nullable|unique:coachs,portal_id',   
        'status' => 'required|in:ACTIVE,INACTIVE',
    ];

    private $customMessages = [
        'first_name.required' => 'The first name field is required.',
        'email.required' => 'Email is required',
        'email.unique' => 'Email already exists',
        'country.required' => 'Country is required',
        'email.email' => 'Email should be a valid email',
        'email.unique' => 'Email already exists',
        'mobile.required' => 'Mobile is required',
        'mobile.digits' => 'Mobile should be 10 digits',
        'mobile.unique' => 'Mobile already exists',
        'zoom_id.unique' => 'The Zoom ID has already been taken.',
        'portal_id.unique' => 'The portal ID has already been taken.',
        'password.required' => 'Password is required',
        'password.min' => 'Password should be minimum 6 characters',
        'confirm_password.required' => 'Confirm Password is required',
        'confirm_password.same' => 'Confirm Password should be same as Password',
        'zoom_user_id.unique' => 'The Zoom User ID has already been taken.',
    // 'zoom_password.required' => 'Zoom Password is required',
    ];

}
