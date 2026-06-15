<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\DemoLead;
use App\Models\Timezone;
use Illuminate\Http\Request;
use App\Models\DemoLeadEnquiry;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LeadEnquiryController extends Controller
{

     public function convertForm($id)
    {
        $enquiry = DemoLeadEnquiry::findOrFail($id);

        // Allowed countries (reuse your existing logic if needed)
        $allCountries = [
            'USA','CANADA','AUSTRALIA','NEWZEALAND','INDIA','UAE','UK','SINGAPORE','SOUTH AFRICA','QATAR','BAHRAIN','KUWAIT','EUROPEAN UNION','OMAN'
        ];

        // return a tiny blade partial rendered as string
        return response()->json([
            'success' => true,
            'html' => view('Admin.LeadEnquiries._convert_modal_form', [
                'enquiry'      => $enquiry,
                'allCountries' => $allCountries,
            ])->render()
        ]);
    }

    public function convertStore(Request $request, $id)
    {
        // dd($request->all());
        $enquiry = DemoLeadEnquiry::findOrFail($id);

        $data = $request->validate([
            'first_name'     => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($id, $enquiry) {
                    // Only run this if the email is changed
                    if ($value !== $enquiry->email) {
                        if (
                            User::where('email', $value)->exists() ||
                            Student::where('email', $value)->exists()
                        ) {
                            $fail('The email has already been taken.');
                        }
                    }
                },
            ],
            'age'            => 'nullable|integer|min:3|max:99',
            'mobile'         => 'required|string|max:20',
            'city'           => 'nullable|string|max:120',
            'country'        => 'required|string|max:50',
            'kids_time_zone' => 'required|string|max:100',
            'date'           => 'required|date',       // IST date (admin side)
            'time'           => 'required',            // IST time (HH:MM)
            'kids_date'      => 'required|date',       // child local date (from ajax conversion)
            'kids_time'      => 'required',            // child local time (HH:MM)
            'remark'         => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // upsert user
            $isNewUser = false;
            if ($enquiry->user_id) {
                $user = User::findOrFail($enquiry->user_id);
            } else {
                $user = new User();
                $isNewUser = true;
            }

            $user->first_name = $data['first_name'];
            $user->email      = $data['email'];
            $user->mobile     = $data['mobile'];
            $user->save();

            if ($isNewUser || empty($user->device_id)) {
                $devicePassword  = 'archer@' . $user->id;
                $user->device_id = $devicePassword;
                $user->password  = Hash::make($devicePassword);
                $user->save();
            }

            // ensure Student role + perms
            if ($role = Role::where('name', 'Student')->first()) {
                if (!$user->hasRole('Student')) {
                    $user->assignRole($role);
                }
                $user->syncPermissions($role->permissions->pluck('name')->all());
            }

            // create DemoLead
            $demoLead = new DemoLead();
            $demoLead->user_id        = $user->id;
            $demoLead->first_name     = $data['first_name'];
            $demoLead->last_name      = $data['last_name'] ?? null;
            $demoLead->age            = $data['age'] ?? null;
            $demoLead->mobile         = $data['mobile'];
            $demoLead->city           = $data['city'] ?? null;
            $demoLead->country        = strtoupper($data['country']);
            $demoLead->remark         = $data['remark'] ?? null;
            $demoLead->status         = 'ROWLEAD';
            $demoLead->kids_time_zone = $data['kids_time_zone'];
            $demoLead->kids_date      = $data['kids_date'];
            $demoLead->kids_time      = $data['kids_time'];
            $demoLead->date           = $data['date'];
            $demoLead->time           = $data['time'];
            $demoLead->save();

            // mark enquiry converted & link user
            $enquiry->status  = 'CONVERTED';
            $enquiry->user_id = $user->id;
            $enquiry->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Converted & saved successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to convert: '.$e->getMessage(),
            ], 422);
        }
    }



    
    public function index()
    {
        return view('Admin.LeadEnquiries.index');
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $query = DemoLeadEnquiry::where('id', '!=', 0)->where('is_hide', $request->is_hide)->orderByDesc('id');

        /*
        * What: Filter enquiries based on the authenticated user's role and associated countries.
        * Why: To ensure that users only see enquiries relevant to their assigned countries, unless they are SuperAdmin.
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
        }

        if ($request->country) {
            $query->where('country', $request->country);
        }

        /*
        * What: Filter enquiries based on date range for 'created_at'.
        * Why: To allow users to view enquiries within specific time frames, either by their creation
        *      date or the scheduled demo date in IST.
        */
        if ($request->date) {
            [$startDate, $endDate] = explode(' - ', $request->date);
            $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        /* 
        * What: Filter enquiries based on the demo date range in IST date.
        * Why: To enable users to filter enquiries by the scheduled demo dates, which are stored
        *      in Indian Standard Time (IST).
        */
        if ($request->demo_date) {
            [$demoStartDate, $demoEndDate] = explode(' - ', $request->demo_date);
            $demoStartDate = Carbon::createFromFormat('m/d/Y', $demoStartDate)->startOfDay();
            $demoEndDate = Carbon::createFromFormat('m/d/Y', $demoEndDate)->endOfDay();
            $query->whereBetween('ist_date', [$demoStartDate, $demoEndDate]);
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function ($enquiry) {
                // Basic buttons
                $show = '<a href="' . route('admin.leadenquiries.show', ['leadenquiry' => $enquiry->id]) . '" class="badge bg-info fs-1 modal-one-btn" data-entity="leadenquiries" data-title="DemoLeadEnquiry" data-route-key="' . $enquiry->id . '"><i class="fa fa-eye"></i></a>';


                // If lead already hidden
                if ($enquiry->is_hide == 1) {
                    return '<span class="badge bg-danger fs-1">Already Deleted</span> ' . $show;
                }

                // Basic buttons

                $convert = '<a href="' . route('admin.leadenquiries.convert', $enquiry->id) . '" 
                            class="badge bg-success fs-1 convert-btn">
                            <i class="ti ti-transform"></i> Convert
                        </a>';

                $reject = '<a href="' . route('admin.leadenquiries.reject', $enquiry->id) . '" 
                            class="badge bg-danger fs-1 reject-btn">
                            <i class="fa fa-times"></i> Reject
                        </a>';

                $delete = '';
                if (auth()->user()->hasRole('SuperAdmin')) {
                    $delete = '<a href="#" class="badge bg-danger fs-1 delete-btn" 
                                data-enquiry-id="' . $enquiry->id . '">
                                <i class="fa fa-trash fs-1"></i>
                            </a>';
                }

                // Status-wise handling
                if ($enquiry->status == 'CONVERTED') {
                    $convert = '<span class="badge bg-info fs-1">
                                    <i class="ti ti-transform"></i> Already Converted
                                </span>';
                    $reject = '';
                    $delete = '';
                } elseif ($enquiry->status == 'REJECTED') {
                    $reject = '';
                }

                // Return buttons
                return '<div style="display:inline-flex; gap:5px;">'
                    . $convert . $reject . $show . $delete .
                    '</div>';
            })

            ->editColumn('datetime', function ($enquiry) {
                $date = '' . Carbon::parse($enquiry->created_at)->format('d-M-Y');
                $time = '' . Carbon::parse($enquiry->created_at)->format('h:i A');
                return '<span style="white-space: nowrap;">' . $date . ' &nbsp; |  &nbsp; ' . $time . '</span>';
            })
            ->editColumn('language_preference', function ($enquiry) {
                if ($enquiry->language_preference != null) {
                    if ($enquiry->language_preference == 'agree') {
                        return 'Agree';
                    }
                    if ($enquiry->language_preference == 'not_comfortable') {
                        return 'Not Comfortable';
                    }
                }
            })
            ->editColumn('status', function ($enquiry) {
                $badgeColor = 'secondary'; 
                switch ($enquiry->status) {
                    case 'INACTIVE':
                        $badgeColor = 'danger';
                        break;
                    case 'CONVERTED':
                        $badgeColor = 'success';
                        break;
                    case 'REJECTED':
                        $badgeColor = 'warning';
                        break;
                    default:
                        $badgeColor = 'secondary';
                }

                return '<button type="button" class="btn badge bg-' . $badgeColor . ' fs-1" data-id="' . $enquiry->id . '" data-routekey="' . $enquiry->id . '" data-bs-toggle="modal" data-bs-target="">
                            <i class="ti ti-analyze"></i> &nbsp; ' . $enquiry->status . '
                        </button>';
            })
            ->editColumn('email_verified', function ($enquiry) {
                if ($enquiry->email_verified == 1) {
                    return '<span class="badge bg-success fs-1">VERIFIED</span>';
                } else {
                    return '<span class="badge bg-danger fs-1">NOT VERIFIED</span>';
                }
            })
            ->editColumn('mobile', function ($enquiry) {
                return $enquiry->mobile;
            })
            ->editColumn('full_name', function ($enquiry) {

                
                // Subject: Thank You for Your Enquiry – Archer Chess Academy

                // Dear [Parent’s Name],

                // Thank you for your enquiry and interest in Archer Chess Academy.
                // We have received your details, and our team will contact you shortly to assist you further.

                // We’re thrilled about your interest in our free demo class to kickstart your child’s chess adventure! 
                // 🌟To get started, could you please share your preferred date and time for 25 Mins Free Trial Class between 
                // India ,Singapore ,UAE: 3:00 PM to 9:00 PM
                // USA & Canada: 5:00 PM CST to 9:00 PM CST
                // UK.                   : 4:00 PM BST to 9:00 PM BST

                // We can’t wait to show your child the fun and strategy of chess!


                // Best regards,
                // Team Archer Chess Academy

                $createdAt = Carbon::parse($enquiry->created_at)->format('d-M-Y h:i A');

                $fullName = trim(($enquiry->kids_first_name ?? '') . ' ' . ($enquiry->kids_last_name ?? ''));

                $lines = [
                    "Dear {$fullName} ({$enquiry->country}),",
                    "",
                    "Thank you for your enquiry and interest in Archer Chess Academy.",
                    "We have received your details, and our team will contact you shortly to assist you further.",
                    "",
                    "We’re thrilled about your interest in our free demo class to kickstart your child’s chess adventure!",
                    "🌟 To get started, could you please share your preferred date and time for a 25 mins free trial class between:",
                    "India / Singapore / UAE: 3:00 PM to 9:00 PM",
                    "USA & Canada: 5:00 PM CST to 9:00 PM CST",
                    "UK: 4:00 PM BST to 9:00 PM BST",
                    "",
                    "We can’t wait to show your child the fun and strategy of chess!",
                    "",
                    "Best regards,",
                    "Team Archer Chess Academy",
                ];

                $message = implode("\n", $lines);


                $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $enquiry->mobile . "&text=" . urlencode($message);
                $whatsappLink = '<a target="_blank" class="badge bg-success fs-1" href="' . $whatsappUrl . '"><div class="tcul-contact_icon"><i class="fab fa-whatsapp my-float"></i></div></a>';

                return $fullName . ' ' . '<span class="d-flex justify-content-end">
                            ' . $whatsappLink . '
                        </span>';
            })
            ->editColumn('demo_datetime', function ($enquiry) {
                $date = '' . Carbon::parse($enquiry->date)->format('d-M-Y');
                $time = '' . Carbon::parse($enquiry->time)->format('h:i A');
                $timezone = '' . $enquiry->timezone;
                return '<span style="white-space: nowrap;">' . $date . ' &nbsp; |  &nbsp; ' . $time . ' &nbsp; |  &nbsp; ' . $timezone . '</span>';
            }) 
            ->editColumn('age', function ($enquiry) {
                return $enquiry->age;
            })
            ->editColumn('country', function ($enquiry) {
             return strtoupper($enquiry->country) . ' (' . $enquiry->timezone . ')';
            })
            ->editColumn('email', function ($enquiry) {
                return $enquiry->email;
            })
            ->addIndexColumn()
            ->rawColumns(['datetime', 'full_name', 'action', 'status', 'email_verified', 'demo_datetime', 'age', 'mobile', 'email'])
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $enquiries = DemoLeadEnquiry::all();
        return response()->json([
            'status' => 'success',
            'list' => $enquiries,
        ], 200);
    }

    public function show(DemoLeadEnquiry $leadenquiry)
    {
        return view('Admin.LeadEnquiries.show', compact('leadenquiry'));
    }

    
    /* * What: Convert a lead enquiry into a demo lead and associated user.
     * Why: To streamline the process of converting interested leads into active demo leads
     *      and ensuring they have the necessary user accounts with appropriate roles and permissions.
     */
    public function convertToDemoLead($id)
    {
        DB::transaction(function () use ($id) {
            // 1) Fetch enquiry
            $enquiry = DemoLeadEnquiry::findOrFail($id);

            // 2) Upsert user (update if exists, else create)
            $isNewUser = false;
            if ($enquiry->user_id) {
                $user = User::findOrFail($enquiry->user_id);
            } else {
                $user = new User();
                $isNewUser = true;
            }

            $user->first_name = $enquiry->kids_first_name;
            $user->mobile     = $enquiry->mobile;
            $user->save();

            // Only set device_id + password after we know the ID, and only if new
            if ($isNewUser || empty($user->device_id)) {
                $devicePassword   = 'archer@' . $user->id;
                $user->device_id  = $devicePassword;
                $user->password   = Hash::make($devicePassword);
                $user->save();
            }

            // 3) Ensure Student role + permissions
            $role = Role::where('name', 'Student')->first();
            if ($role) {
                if (! $user->hasRole('Student')) {
                    $user->assignRole($role);
                }
                // Sync permissions to match the role (by names)
                $user->syncPermissions($role->permissions->pluck('name')->all());
            }

            // 4) Create DemoLead
            $demoLead                  = new DemoLead();
            $demoLead->user_id         = $user->id;
            $demoLead->first_name      = $enquiry->kids_first_name;
            $demoLead->last_name       = $enquiry->kids_last_name;
            $demoLead->age             = $enquiry->age;
            $demoLead->mobile          = $enquiry->mobile;
            $demoLead->city            = $enquiry->city;
            $demoLead->country         = $enquiry->country;
            $demoLead->remark          = null;
            $demoLead->index           = null;
            $demoLead->status          = 'ROWLEAD';
            $demoLead->kids_time_zone  = $enquiry->timezone ?? null;
            $demoLead->kids_date       = $enquiry->date ?? null;
            $demoLead->kids_time       = $enquiry->time ?? null;
            $demoLead->date            = $enquiry->ist_date ?? null;
            $demoLead->time            = $enquiry->ist_time ?? null;
            $demoLead->save();

            // 5) Mark enquiry converted and link user
            $enquiry->status  = 'CONVERTED';
            $enquiry->user_id = $user->id;
            $enquiry->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Lead enquiry converted to demo lead successfully.',
        ]);
    }

    /* * What: Reject a demo lead enquiry with a remark.
     * Why: To provide a mechanism for administrators to reject enquiries that are not suitable,
     *      while also capturing the reason for rejection for future reference.
     */
    public function rejectTheDemoLead(Request $request, $id)
    {
        DB::transaction(function () use ($id, $request) {
            $enquiry = DemoLeadEnquiry::findOrFail($id);
            $enquiry->status = 'REJECTED';
            $enquiry->remark = $request->remark;
            $enquiry->save();
        });

        return response()->json(['success' => true, 'message' => 'Lead enquiry rejected successfully.']);
    }

    /* * What: Change the status of a demo lead enquiry.
     * Why: To allow administrators to update the status of an enquiry, reflecting its current state
     *      in the lead management process.
     */
    public function changeStatus(Request $request)
    {
        $validatedData = $request->validate([
            'leadenquiry_id' => 'required|exists:demoleadenquiries,id',
            'status' => 'required|string',
        ]);
        $enquiry = DemoLeadEnquiry::findOrFail($validatedData['leadenquiry_id']);
        $enquiry->status = $validatedData['status'];
        $enquiry->save();
 
        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully!',
            'data' => $enquiry,
        ]);
    }

    /* * What: Soft delete a demo lead enquiry by marking it as hidden.
     * Why: To allow administrators to remove enquiries from active view without permanently deleting
     *      them from the database, preserving data integrity and history.
     */
    public function destroy(Request $request, DemoLeadEnquiry $enquiry)
    {
        $enquiry = DemoLeadEnquiry::where('id', $request->enquiry_id)->first();
        if ($enquiry) {
            $enquiry->is_hide = 1;
            $enquiry->save();

            return response()->json([
                'success' => 'enquiry Deleted Successfully',
            ], 201);
        } else {
            return response()->json([
                'error' => 'enquiry not found',
            ], 404);
        }
    }
}
