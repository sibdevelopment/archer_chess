<?php

namespace App\Http\Controllers\Frontend;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Blog;
use App\Models\Role;
use App\Models\User;
use App\Models\Batch;
use App\Models\Coach;
use App\Models\Event;
use App\Models\Enquiry;
use App\Models\Gallery;
use App\Models\Student;
use App\Models\Timezone;
use App\Mail\EnquiryMail;
use App\Models\MeetOurKid;
use App\Models\StudentFee;
use App\Models\DemoSession;
use App\Models\Coverupclass;
use App\Models\LeaveRequest;
use App\Models\MeetOurTutor;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use App\Mail\DemoBookingMail;
use App\Models\BatchSchedule;
use Illuminate\Support\Carbon;
use App\Models\CoachAttendance;
use App\Models\DemoLeadEnquiry;
use Illuminate\Validation\Rule;
use App\Models\CoachAvailability;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\ZoomMeetingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $country = session('country', $request->cookie('country', ''));
        $blogs = Blog::where('home_featured', 'ACTIVE')->take(6)->orderBy('date', 'desc')->get();
        $meetourkids = MeetOurKid::where('status', 'ACTIVE')->get();
        $meetourtutors = MeetOurTutor::where('status', 'ACTIVE')->orderBy('id', 'desc')->get();

        return view('Frontend.home', compact('country', 'blogs', 'meetourkids', 'meetourtutors'));
    }

    public function newHome()
    {
        return view('Frontend.Design.home');
    }
    public function newAbout()
    {
        return view('Frontend.Design.about');
    }
    public function newContact()
    {
        return view('Frontend.Design.contact');
    }
    public function newGallery()
    {
        return view('Frontend.Design.gallery');
    }
    public function newEvent()
    {
        return view('Frontend.Design.event');
    }
    // Blogs
    public function newBlogs()
    {
        return view('Frontend.Design.blogs');
    }
    public function newBlogDetails()
    {
        return view('Frontend.Design.blog_details');
    }
    // Policy pages
    public function newPrivacy()
    {
        return view('Frontend.Design.Policy.privacy');
    }
    public function newTerms()
    {
        return view('Frontend.Design.Policy.terms');
    }
    public function newRefundPolicy()
    {
        return view('Frontend.Design.Policy.refund');
    }
    public function newShippingPolicy()
    {
        return view('Frontend.Design.Policy.shipping');
    }
    // Course details pages
    public function newBeginnerCourse()
    {
        return view('Frontend.Design.Course.beginner');
    }
    public function newIntermediateCourse()
    {
        return view('Frontend.Design.Course.intermediate');
    }
    public function newAdvancedCourse()
    {
        return view('Frontend.Design.Course.advanced');
    }
    public function newExpertCourse()
    {
        return view('Frontend.Design.Course.expert');
    }

    public function contact()
    {
        return view('Frontend.Contact.contact');
    }

    public function thankyou()
    {
        return view('Frontend.thankyou');
    }

    public function about()
    {
        return view('Frontend.about');
    }

    public function privacy()
    {
        return view('Frontend.privacy');
    }

    public function terms()
    {
        return view('Frontend.terms');
    }

    public function refundPolicy()
    {
        return view('Frontend.refundpolicy');
    }

    public function shippingPolicy()
    {
        return view('Frontend.shippingpolicy');
    }
 
    public function blog()
    {
        $blogs = Blog::where('status', 'ACTIVE')->orderBy('date', 'desc')->get();
        $blogs->transform(function ($blog) {
            $blog->date = \Carbon\Carbon::parse($blog->date)->format('d-m-Y');
            return $blog;
        });
        return view('Frontend.blogs', compact('blogs'));
    }
 
    public function blogDetails($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $blog->date = \Carbon\Carbon::parse($blog->date)->format('d-m-Y');
        $similarBlogs = Blog::where('status', 'ACTIVE')->orderBy('date', 'desc')->take(10)->get();

        return view('Frontend.BlogDetails.details', compact('blog','similarBlogs'));
    }

    public function gallery(Request $request)
    {
        $galleries = Gallery::where('status', 'ACTIVE')
            ->with(['galleryImages' => function ($query) {
                $query->where('status', 'ACTIVE');
            }])
            ->get();
        return view('Frontend.gallery', compact('galleries'));
    }

    public function event(Request $request)
    {
        $events = Event::where('status', 'ACTIVE')->get();
        return view('Frontend.event', compact('events'));
    }

    /**
     * What: Handle contact form submission and store enquiry which is comming from contact page.
     * Why: To process and store user enquiries submitted via the contact form.
     */
    public function contactSubmit(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'country'     => 'required|string|max:255',
            'mobile'      => 'required|string|max:15',
            'subject'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $country_code = $request->input('country_code', '');
        $mobile       = $country_code . $data['mobile'];

        $enquiry = Enquiry::create([
            'first_name' => $data['name'],
            'last_name'  => ($request->input('last_name', '')),
            'country'  => $data['country'],
            'email'      => ($request->input('email', '')),
            'mobile'     => $mobile,
            'remark'     => ($request->input('subject', '')),
            'message'    => ($data['description'] ?? ''),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Your enquiry has been submitted successfully!',
        ]);
    }
 
    public function getPricingCard(Request $request)
    {
        $country = 'INDIA';
        $allPricingData = getCourcePricing();
        $pricingData    = $allPricingData[strtoupper($country)] ?? [];
        $beginners    = $pricingData['Beginners'] ?? [];
        $intermediate = $pricingData['Intermediate'] ?? [];
        $advanced     = $pricingData['Advanced'] ?? [];
        $expert       = $pricingData['Expert'] ?? [];

        return view('Frontend.BookTrialClass.courcepricingcards', compact('beginners', 'intermediate', 'advanced', 'expert', 'country'));
    }

    public function loadBookingForm(Request $request)
    {
        return view('Frontend.BookTrialClass.bookingform');
    }

    public function bookTrialClass(Request $request)
    {
        $country = session('country', $request->cookie('country', ''));
        $meetourkids = MeetOurKid::where('status', 'ACTIVE')->get();

        return view('Frontend.BookTrialClass.bookclass', compact('country', 'meetourkids'));
    }

    /*
    * What: Handle trial class booking form submission.
    * Why: To process and store user requests for booking a trial class.
    */
    public function storeBookATrailForm(Request $request)
    {

        // Timezone aliases for normalization
        $tzAliases = [
            'Mountain Time'          => 'Mountain Standard Time',
            'Eastern Time'           => 'Eastern Standard Time',
            'Central Time'           => 'Central Standard Time',
            'Pacific Time'           => 'Pacific Standard Time',
            'Alaska Time'            => 'Alaska Standard Time',
            'Hawaii-Aleutian Time'   => 'Hawaii-Aleutian Standard Time',
        ];

        $maxDate = Carbon::now()->addDays(3);

        // Build the full mobile (code + number) up front so we can validate on what we actually store
        $countryCode = $request->input('country_code', '+91');
        $rawMobile   = (string) $request->input('mobile', '');
        $finalMobile = preg_replace('/\D/', '', $rawMobile); // Strip non-digits
        $fullMobile  = $countryCode . $finalMobile;
        

        $rules = [
            'country'             => ['required','string','max:255'],
            'city'                => ['required','string','max:255'],
            'timezone'            => ['required','string'],
            'duration'            => ['nullable','string'],  
            'kids_first_name'     => ['required','string','max:255'],
            'kids_last_name'      => ['nullable','string','max:255'],
            'age'                 => ['required','integer','min:3','max:16'],
            'mobile'              => [
                'required','string','max:15',
                function ($attribute, $value, $fail) use ($fullMobile) {
                    $inUsers = User::where('mobile', $fullMobile)->exists();
                    $inLeads = DemoLeadEnquiry::where('mobile', $fullMobile)->exists();
                    if ($inUsers || $inLeads) {
                        $fail('Mobile number already exists.');
                    }
                },
            ],
            'country_code'        => ['required','string','max:6'],
            'available_device'    => ['nullable','string','max:255'],
            'language_preference' => ['required','string','max:255'],
            'email'               => ['required','email','max:255'], 
        ];

        $messages = [
            'date.before_or_equal' => 'The date must be a date before or equal to ' . $maxDate->format('d M, Y') . '.',
            'country.required' => 'Select your country.',
            'age.required' => 'Enter age.',
            'age.integer' => 'Age must be a number.',   
            'age.min' => 'Minimum age is 3 years.',
            'age.max' => '',
            'city.required' => 'Enter city.',
            'timezone.required' => 'Select your timezone.',
            'mobile.required' => 'Enter number.',   
        ];

       
        $data = $request->validate($rules, $messages);

        $lengthRules = [
            '+61'  => 9,
            '+65'  => 8,
            '+1'   => 10,
            '+91'  => 10,
            '+971' => 9,
            '+44'  => 10,
        ];

        $country_code = $request->input('country_code');

        if (isset($lengthRules[$country_code])) {
            $expectedLength = $lengthRules[$country_code];
            if (strlen($request->mobile) != $expectedLength) { 
                return Response::json([
                    'status'  => 'error',
                    'errors' => [
                        'mobile' => ["Mobile number must be exactly {$expectedLength} digits for {$country_code}."]
                    ],
                    'message' => "Mobile number must be exactly {$expectedLength} digits for {$country_code}.",
                ], 422);
            }
        }
 

        // Normalize/alias timezone (keep original if no alias)
        $normalizedTz = $tzAliases[$data['timezone']] ?? $data['timezone'];

        // Defaults & computed fields
        $data['duration']     = $data['duration'] ?: '25_minutes';
        $data['status']       = 'ACTIVE';
        $data['lead_status']  = 'ACTIVE';
        $data['email_otp']    = random_int(1000, 9999);
        $data['mobile']       = $fullMobile;          // store concatenated value
        $data['timezone']     = $normalizedTz;        // store normalized tz

        // Persist in a single transaction
        [$user, $demoLeadEnquiry] = DB::transaction(function () use ($data, $request, $countryCode) {

            // Create lead first
            $demoLeadEnquiry = DemoLeadEnquiry::create($data);
            $demoLeadEnquiry->utm_source = $request->input('utm_source');
            $demoLeadEnquiry->utm_medium = $request->input('utm_medium');
            
            $demoLeadEnquiry->save();
            // Create user
            $user = new User();
            $user->first_name   = $request->input('kids_first_name');
            $user->country_code = $countryCode;
            $user->mobile       = $data['mobile'];
            $user->email        = $request->input('email');
            // temp password (will be replaced below)
            $user->password     = Hash::make('12345678');
            $user->save();

            // Set device_id-based password
            $password        = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password  = Hash::make($password);
            $user->save();

            // Role & permissions
            $user->assignRole('Student');
            if ($role = Role::where('name', 'Student')->first()) {
                // Give user all permissions of the Student role
                $user->syncPermissions($role->permissions()->pluck('name')->all());
            }

            // Link lead to user
            $demoLeadEnquiry->user_id = $user->id;
            $demoLeadEnquiry->save();

            return [$user, $demoLeadEnquiry];
        });

        // Session cache for later steps
        session(['demo_lead_enquiry' => $demoLeadEnquiry->toArray()]);

        Mail::to($user->email)->send(new DemoBookingMail($demoLeadEnquiry, $user));
 
        return response()->json([
            'status'  => 'success',
            'message' => 'Trial class booked successfully!',
            'user_id' => $user->id,
        ]);
    }
    
    /**
     * What: Display the country-specific landing page.
     * Why: Client can see landing page based on their country selection.
    */
    public function countryView(Request $request)
    {
        // Allowed countries
        $countries = [
            'india'           => 'INDIA',
            'usa'             => 'USA',
            'canada'          => 'CANADA',
            'singapore'       => 'SINGAPORE',
            'united-kingdom'  => 'UK',
            'uae'             => 'UAE',
            'australia'       => 'AUSTRALIA',
            'qatar'           => 'QATAR',
            'european-union'  => 'EUROPEAN UNION',
            'middle-east'     => 'MIDDLE EAST',
        ];
 

        // Normalize the incoming country; default to India
        $countryParam = $request->country ?: 'india';
        // dd($countryParam);
        $countrySlug  = Str::slug($countryParam, '-');
        $slug = null;
        if($countrySlug === 'united-kingdom') {
            $slug = 'UK';
        } 
        if($countrySlug === 'european-union') {
            $slug = 'EUROPEAN UNION';
        }
        // Validate country
        if (!array_key_exists($countrySlug, $countries)) {
            abort(404, 'Country not found');
        }


        $countryDisplay = $countries[$countrySlug];

        if($countryDisplay === 'MIDDLE EAST') {
            return view('Frontend.country', [
                'country_slug' => $countrySlug, 
                'timezones'    => ['Arabian Standard Time'],
            ]);
        }

        $timezones = [
            'USA' => ['Mountain Time', 'Eastern Time', 'Central Time', 'Pacific Time', 'Alaska Time',
                'Hawaii-Aleutian Time'
            ],
            'CANADA' => ['Mountain Time', 'Eastern Time', 'Central Time', 'Pacific Time', 'Alaska Time',
                'Hawaii-Aleutian Time'
            ],
            'NEWZEALAND' => ['New Zealand Daylight Time', 'New Zealand Standard Time'],
            'AUSTRALIA' => ['Australia/Perth', 'Australia/Darwin', 'Australia/Brisbane', 'Australia/Adelaide',
                'Australia/Sydney'
            ],
            'UK' => ['British Summer Time', 'Greenwich Mean Time'],
            'INDIA' => ['Indian Standard Time'],
            'UAE' => ['Gulf Standard Time'],
            'SINGAPORE' => ['Singapore Standard Time'],
            'SOUTH AFRICA' => ['South Africa Standard Time'],
            'QATAR' => ['Arabian Standard Time'],
            'BAHRAIN' => ['Arabian Standard Time'],
            'KUWAIT' => ['Arabian Standard Time'],
            'EUROPEAN UNION' => ['Central European Time', 'Eastern European Time', 'Western European Time'],
            'OMAN' => ['Arabian Standard Time'],
        ];

        $timezones = $timezones[$countryDisplay];
    
        return view('Frontend.country', [
            'country'      => $countryDisplay,
            'country_slug' => $countrySlug, 
            'timezones'    => $timezones,
        ]);
    }

    /*
    * What: Handle trial class booking form submission for country-specific pages.
    * Why: To process and store user requests for booking a trial class from country-specific landing
    *      pages, with country-specific validation.
    */
    public function storeBookATrailFormForCountry(Request $request)
    {
        
        $tzAliases = [
            'Mountain Time'        => 'Mountain Standard Time',
            'Eastern Time'         => 'Eastern Standard Time',
            'Central Time'         => 'Central Standard Time',
            'Pacific Time'         => 'Pacific Standard Time',
            'Alaska Time'          => 'Alaska Standard Time',
            'Hawaii-Aleutian Time' => 'Hawaii-Aleutian Standard Time',
        ];

        $maxDate     = Carbon::now()->addDays(3);
        $countryCode = (string) $request->input('country_code', '+91');
        $rawMobile   = (string) $request->input('mobile', '');
        
        $mobile = preg_replace('/\D/', '', $rawMobile); // Strip non-digits for length checks
        $fullMobile  = $countryCode . $mobile;

        // dd($mobile);
        $rules = [
            // 'enrollment_plan'     => ['required','string','max:255'],
            'country'             => ['required','string','max:255'],
            'city'                => ['required','string','max:255'],
            'timezone'            => ['required','string'],
            'kids_first_name'     => ['required','string','max:255'],
            'kids_last_name'      => ['nullable','string','max:255'],
            'age'                 => ['required','integer','min:3','max:16'],
            'mobile'              => [
                'required','string','max:15',
                // Ensure uniqueness on the concatenated value across both tables:
                function ($attribute, $value, $fail) use ($fullMobile) {
                    $inUsers = User::where('mobile', $fullMobile)->exists();
                    $inLeads = DemoLeadEnquiry::where('mobile', $fullMobile)->exists();
                    if ($inUsers || $inLeads) {
                        $fail('Mobile number already exists.');
                    }
                },
                // If Country = India, enforce 10 digits
                Rule::when($request->country === 'India', ['digits:10']),
            ],
            'country_code'        => ['required','string','max:6'],
            'available_device'    => ['nullable','string','max:255'], 
            'email'               => ['required','email','max:255'], 
        ];
        

        $messages = [
            'kids_first_name.required' => 'Enter name',
            'country.required' => 'Select your country.',
            'age.required' => 'Enter age.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Minimum age is 3 years.',
            'age.max' => '',
            'city.required' => 'Enter city.',
            'timezone.required' => 'Select your timezone.',
            'mobile.required' => 'Enter number.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'mobile.digits' => 'For India, mobile number must be exactly 10 digits.',
            'date.before_or_equal' => 'The date must be a date before or equal to ' . $maxDate->format('d M, Y') . '.',
        ];
        $data = $request->validate($rules, $messages);
         // Country-specific number lengths
        
         $lengthRules = [
            '+61'  => 9,
            '+65'  => 8,
            '+1'   => 10,
            '+91'  => 10,
            '+971' => 9,
            '+44'  => 10,
        ];

        $country_code = $request->input('country_code');

        if (isset($lengthRules[$country_code])) {
            $expectedLength = $lengthRules[$country_code];
            if (strlen($mobile) != $expectedLength) { 
                return Response::json([
                    'status'  => 'error',
                    'errors' => [
                        'mobile' => ["Mobile number must be exactly {$expectedLength} digits for {$country_code}."]
                    ],
                    'message' => "Mobile number must be exactly {$expectedLength} digits for {$country_code}.",
                ], 422);
            }
        }


        // Normalize timezone (keep original if no alias)
        $data['timezone'] = $tzAliases[$data['timezone']] ?? $data['timezone'];

        // Defaults & computed fields
        $data['duration']     = $data['duration'] ?? '25_minutes';
        $data['status']       = 'ACTIVE';
        $data['lead_status']  = 'ACTIVE';
        $data['email_otp']    = random_int(1000, 9999);
        $data['mobile']       = $fullMobile; // store concatenated
        // If you need IST conversion later, you can still compute with your helper:
        // $kidTimeZone = convertTimeZoneString($data['timezone']);

        [$user, $demoLeadEnquiry] = DB::transaction(function () use ($data, $request, $countryCode) {

            // 1) Create lead
            $demoLeadEnquiry = DemoLeadEnquiry::create($data);
            $demoLeadEnquiry->utm_source = $request->input('utm_source');
            $demoLeadEnquiry->utm_medium = $request->input('utm_medium');
            $demoLeadEnquiry->save();

            // 2) Create user
            $user = new User();
            $user->first_name   = $request->input('kids_first_name');
            $user->country_code = $countryCode;
            $user->mobile       = $data['mobile'];
            $user->email        = $request->input('email');
            $user->password     = Hash::make('12345678'); // temp
            $user->save();

            // 3) Device ID password pattern (as in your original code)
            $password        = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password  = Hash::make($password);
            $user->save();

            // 4) Role + permissions
            $user->assignRole('Student');
            if ($role = Role::where('name', 'Student')->first()) {
                $user->syncPermissions($role->permissions()->pluck('name')->all());
            }

            // 5) Link lead → user
            $demoLeadEnquiry->user_id = $user->id;
            $demoLeadEnquiry->save();

            return [$user, $demoLeadEnquiry];
        });

        // Cache lead in session for later flow
        session(['demo_lead_enquiry' => $demoLeadEnquiry->toArray()]);

        // Mail::to($user->email)->send(new DemoBookingMail($demoLeadEnquiry, $user));


        return response()->json([
            'status'  => 'success',
            'message' => 'Trial class booked successfully!',
            'user_id' => $user->id,
        ]);
    }

    public function bookTrialClassThankYou(Request $request)
    {
        $user = User::find($request->user_id);
        return view('Frontend.BookTrialClass.thankyou', compact('user'));
    }

    /**
     * What: Handle general enquiry form submission.
     * Why: To process and store user enquiries submitted via a general enquiry form.
     */
    public function enquirySubmit(Request $request)
    {
        $rules = [
            'first_name'  => 'required',
            'country'     => 'required',
            'mobilePopup' => 'required|numeric',
            'country_code' => 'required',
            'timezone'   => 'required',
        ];

        $messages = [
            'first_name.required' => 'Enter your name.',
            'country.required' => 'Select your country.',
            'mobilePopup.required' => 'Enter your mobile number.',
            'mobilePopup.numeric' => 'Mobile number must be numeric.',
            'country_code.required' => 'Select your country code.',
            'timezone.required' => 'Select your timezone.',
        ];

        $request->validate($rules, $messages);

        // Country-specific number lengths
        $lengthRules = [
            '+61'  => 9,
            '+65'  => 8,
            '+1'   => 10,
            '+91'  => 10,
            '+971' => 9,
            '+44'  => 10,
        ];



        $countryCode = $request->input('country_code', '+91');
        $rawMobile   = (string) $request->input('mobilePopup', '');
        $finalMobile = preg_replace('/\D/', '', $rawMobile); // Strip non-digits
        $fullMobile  = $countryCode . $finalMobile;

        $checkisEnquiry = Enquiry::where('mobile', $fullMobile)->first();
        if ($checkisEnquiry) {
            return response()->json([
                'status'  => 'error',
                'errors' => [
                    'mobilePopup' => ['Mobile number already exists.']
                ],
            ], 422);
        }


        if (isset($lengthRules[$country_code])) {
            $expectedLength = $lengthRules[$country_code];
            if (strlen($request->mobilePopup) != $expectedLength) { 
                return Response::json([
                    'status'  => 'error',
                    'errors' => [
                        'mobilePopup' => ["Mobile number must be exactly {$expectedLength} digits for {$country_code}."]
                    ],
                    'message' => "Mobile number must be exactly {$expectedLength} digits for {$country_code}.",
                ], 422);
            }
        }

        // Save Enquiry
        $enquiry = new Enquiry();
        $enquiry->first_name = $request->first_name;
        $enquiry->last_name = '';
        $enquiry->country = $request->country . ' - ' . ($request->timezone ?? '');
        $enquiry->mobile = $fullMobile;
        $enquiry->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Enquiry submitted successfully!',
            'data' => $enquiry,
        ]);
    }

}
