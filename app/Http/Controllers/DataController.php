<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DemoBookingMail;
use App\Mail\EnquiryMail;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Coach;
use App\Models\CoachAttendance;
use App\Models\CoachAvailability;
use App\Models\Coverupclass;
use App\Models\DemoLeadEnquiry;
use App\Models\DemoSession;
use App\Models\Enquiry;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\MeetOurKid;
use App\Models\MeetOurTutor;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBatch;
use App\Models\StudentFee;
use App\Models\Timezone;
use App\Models\User;
use App\Services\ZoomMeetingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DataController extends Controller
{
    /**
     * What: Export fees collection for a specific date (optionally filtered by country) as CSV.
     * Why: Finance team needs a daily snapshot of active fee periods for auditing/reporting.
     */
    public function feesCollection(string $year, string $month, string $day, ?string $country = null)
    {
        $inputDate = "{$year}-{$month}-{$day}";

        $query = DB::table('student_fees')
            ->join('students', 'students.id', '=', 'student_fees.student_id')
            ->select(
                'student_fees.id',
                'students.first_name as student_first',
                'students.last_name as student_last',
                'student_fees.start_date',
                'student_fees.end_date',
                'student_fees.currency',
                'student_fees.monthly_fees',
                'student_fees.total_amount_paid',
                'student_fees.status'
            )
            ->whereDate('student_fees.start_date', '<=', $inputDate)
            ->whereDate('student_fees.end_date', '>=', $inputDate)
            ->orderBy('student_fees.end_date');

        if (!empty($country)) {
            $query->where('students.country', $country);
        }

        $fees = $query->get();

        if ($fees->isEmpty()) {
            return response()->json(['message' => 'No data found for the selected criteria.'], 404);
        }

        $rows = [];
        $rows[] = ['Student Name', 'Start Date', 'End Date', 'Currency', 'Monthly Fees', 'Total Paid', 'Status'];

        foreach ($fees as $fee) {
            $rows[] = [
                trim($fee->student_first . ' ' . $fee->student_last),
                $fee->start_date,
                $fee->end_date,
                $fee->currency,
                $fee->monthly_fees,
                $fee->total_amount_paid,
                $fee->status,
            ];
        }

        $filename = "fees_collection_{$year}-{$month}-{$day}" . ($country ? "_{$country}" : "") . ".csv";
        return $this->csvResponse($filename, $rows);
    }

    /**
     * What: Display last-month coach attendance with duplicates flagged (by coach, batch, date).
     * Why: Ops needs to quickly spot accidental double-marked sessions.
     */
    public function coachAttendance(Request $request)
    {
        $coachAttendances = CoachAttendance::with(['coach.user'])
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->where('status', 'COMPLETED')
            ->whereNotNull('batch_id')
            ->orderByDesc('created_at')
            ->get();

        // Group by unique key and mark duplicates
        $duplicateGroups = $coachAttendances->groupBy(function ($item) {
            return implode('|', [
                optional($item->coach?->user)->first_name,
                optional($item->coach?->user)->last_name,
                $item->batch_id,
                $item->date,
            ]);
        })->filter(fn($group) => $group->count() > 1);

        foreach ($coachAttendances as $attendance) {
            $key = implode('|', [
                optional($attendance->coach?->user)->first_name,
                optional($attendance->coach?->user)->last_name,
                $attendance->batch_id,
                $attendance->date,
            ]);
            $attendance->is_duplicate = $duplicateGroups->has($key);
        }

        return view('Frontend.coach_attendance', compact('coachAttendances'));
    }

    /**
     * What: Fetch Zoom users via API.
     * Why: Admins need to see available Zoom accounts for meeting scheduling/assignment.
     */
    public function getZoomUsers(Request $request)
    {
        $zoomService = new ZoomMeetingService(
            'q5kwKGfNSWOrwrg4IMxloQ',
            'SiyrPhtEJ3y7B4A0KPSx4kwEAYuLVMnn',
            'uApfR5EWRI6vzocPQ_lwpg'
        );


        $zoomUsers = $zoomService->getUsers();
        // dd($zoomUsers);

        return view('zoom_users', compact('zoomUsers'));
    }

    /**
     * What: Show duplicate student attendances (same student/date/time/coach/batch).
     * Why: Data cleaning—helps find and remove accidental duplicate rows.
     */
    public function dummy()
    {
        $studentAttendance = DB::table('student_attendances')
            ->select('student_id', 'date', 'time', DB::raw('COUNT(*) as duplicate_count'), 'coach_id', 'batch_id')
            ->where('type', 'like', '%batch%')
            ->groupBy('student_id', 'date', 'time', 'coach_id', 'batch_id')
            ->having('duplicate_count', '>', 1)
            ->get();

        return view('dummy', compact('studentAttendance'));
    }

    /**
     * What: List batches that are duplicate by name/zone/coach/level/date range.
     * Why: Prevent accidental creation of clonish batches that cause scheduling conflicts.
     */
    public function multiple_batch()
    {
        $batches = Batch::select(
            'name',
            'kids_zone_name',
            'coach_id',
            'level_id',
            'start_date',
            'end_date',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('name', 'kids_zone_name', 'coach_id', 'level_id', 'start_date', 'end_date')
            ->having('count', '>', 1)
            ->get();

        return view('multiple_batch', compact('batches'));
    }

    /**
     * What: Show fees lists for a month: due, entered, due+inactive, and by creation date; filter by country.
     * Why: Finance/reporting needs multiple angles for recon and forecasting.
     */
    public function feesList(string $year ,string $month, string $country)
    {
        $month = $month ?: date('m');
        $year  = $year ?: date('Y');

        $countryFilter = function ($query) use ($country) {
            // If country is NOT ALL and NOT empty, apply filter
            if (strtoupper($country) !== 'ALL' && $country !== '') {
                $query->where('country', strtoupper($country));
            }
        };

        

        $current_month_fees_due = StudentFee::whereMonth('end_date', $month)
            ->whereYear('end_date', $year)
            ->whereHas('student', $countryFilter)
            ->orderBy('end_date')
            ->get();

        $current_month_fees_enter = StudentFee::whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->whereHas('student', $countryFilter)
            ->orderBy('start_date')
            ->get();

        $fees_due_and_inactive = StudentFee::whereMonth('end_date', $month)
            ->whereYear('end_date', $year)
            ->whereHas('student', function ($q) use ($country, $countryFilter) {
                $countryFilter($q);
                $q->where('status', 'INACTIVE');
            })
            ->orderBy('end_date')
            ->get();

        $fees_enter_by_creation_date = StudentFee::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereHas('student', $countryFilter)
            ->orderBy('created_at')
            ->get();

        return view('fees_list', compact(
            'current_month_fees_due',
            'month',
            'current_month_fees_enter',
            'fees_due_and_inactive',
            'fees_enter_by_creation_date'
        ));
    }

    /**
     * What: Export CSV of cancelled batches in a date range, excluding coach-approved leave days.
     * Why: Ops needs to review real cancellations (not those covered by approved leave).
     */
    public function cancelBatchList(string $fromdate, string $todate)
    {
        try {
            $from = Carbon::parse($fromdate)->startOfDay();
            $to   = Carbon::parse($todate)->endOfDay();

            $attendances = CoachAttendance::with(['coach.user', 'batch'])
                ->where('status', 'CANCELLED')
                ->whereNotNull('batch_id')
                ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
                ->orderBy('date')
                ->get();

            if ($attendances->isEmpty()) {
                return response()->json(['message' => 'No cancelled sessions found in the selected range.'], 404);
            }

            $rows = [];
            $rows[] = ['Date', 'Batch Name', 'Coach Name'];

            foreach ($attendances as $attendance) {
                $attendanceDate = Carbon::parse($attendance->date)->toDateString();

                $hasLeave = \App\Models\LeaveRequest::where('coach_id', $attendance->coach_id)
                    ->where('status', 'APPROVED')
                    ->whereDate('from_date', '<=', $attendanceDate)
                    ->whereDate('to_date', '>=', $attendanceDate)
                    ->exists();

                if ($hasLeave) {
                    continue;
                }

                $rows[] = [
                    Carbon::parse($attendance->date)->format('d-m-Y'),
                    optional($attendance->batch)->name ?? 'N/A',
                    trim((optional($attendance->coach?->user)->first_name ?? '') . ' ' . (optional($attendance->coach?->user)->last_name ?? '')),
                ];
            }

            if (count($rows) === 1) {
                return response()->json(['message' => 'No cancelled sessions found (excluding those with leave).'], 404);
            }

            $filename = "Cancelled_Batches_{$from->format('Ymd')}_to_{$to->format('Ymd')}.csv";
            return $this->csvResponse($filename, $rows);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    /**
     * What: Show (student, batch, coach) tuples where the student appears multiple times in the same batch.
     * Why: Detect duplicate enrollments that can break scheduling/attendance.
     */
    public function multiple_student_in_batch()
    {
        $student_batches = StudentBatch::select('student_id', 'batch_id', 'coach_id', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'batch_id', 'coach_id')
            ->having('count', '>', 1)
            ->get();

        return view('multiple_student_in_batch', compact('student_batches'));
    }

    /**
     * What: Download CSV of students inactivated in a given month (current year).
     * Why: Audit trail for offboarding and coach allocations.
     */
    public function student_inactive(string $month)
    {
        $currentYear = now()->year;

        $students = Student::where('status', 'INACTIVE')
            ->whereYear('updated_at', $currentYear)
            ->whereMonth('updated_at', $month)
            ->get();

        $monthName = date('F', mktime(0, 0, 0, (int)$month, 1));

        $rows = [];
        $rows[] = ['Student Name (ID)', 'Coach Name & Status', 'Updated By', 'Updated At', 'Status'];

        foreach ($students as $student) {
            $updatedByUser = $student->updated_by ? User::find($student->updated_by) : null;

            $coachName = '-';
            $statusBadgeText = '';
            $student_latest_batch = $student->latestBatch;
            if ($student_latest_batch) {
                $statusBadgeText = $student_latest_batch->status === 'ACTIVE' ? ' (Present)' : ' (Previous Coach)';
                $coachName = $student_latest_batch->coach
                    ? trim($student_latest_batch->coach->user->first_name . ' ' . $student_latest_batch->coach->user->last_name)
                    : 'N/A';
            }

            $rows[] = [
                trim($student->first_name . ' ' . $student->last_name) . ' (' . $student->student_id . ')',
                $coachName !== '-' ? $coachName . $statusBadgeText : '-',
                $updatedByUser ? trim($updatedByUser->first_name . ' ' . $updatedByUser->last_name) : '-',
                $student->updated_at?->format('d M Y'),
                ucfirst(strtolower($student->status)),
            ];
        }

        $filename = "Inactive_Students_{$monthName}_{$currentYear}.csv";
        return $this->csvResponse($filename, $rows);
    }

    /**
     * What: Show duplicate attendances for (student, batch, date, coach) on batch type.
     * Why: Identify data integrity issues in attendance logs.
     */
    public function multiple_student_attendance()
    {
        $student_attendances = StudentAttendance::select('student_id', 'batch_id', 'date', 'coach_id', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'batch_id', 'date', 'coach_id')
            ->where('type', 'Batch')
            ->having('count', '>', 1)
            ->get();

        return view('multiple_student_attendance', compact('student_attendances'));
    }

    /**
     * What: Same as above but constrained to the same calendar day key (option to filter year).
     * Why: Narrow view for specific audits (e.g., for 2025).
     */
    public function same_day_multiple_student_attendance()
    {
        $student_attendances = StudentAttendance::select('student_id', 'batch_id', 'date', 'coach_id', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'batch_id', 'date', 'coach_id')
            ->where('type', 'Batch')
            // ->whereYear('date', 2025) // uncomment when you want 2025-only
            ->having('count', '>', 1)
            ->get();

        return view('multiple_student_attendance', compact('student_attendances'));
    }

    /**
     * What: List users without a device_id (data hygiene).
     * Why: Helps fill missing device IDs or spot onboarding gaps.
     */
    public function sameUsers()
    {
        $users = User::whereNull('device_id')->get();

        return view('same_users', compact('users'));
    }

    /**
     * What: Display the country-specific landing page.
     * Why: Client can see landing page based on their country selection.
     */
    public function countryView(Request $request)
    {
        // Single map (slug => display) for easy maintenance
        $countries = [
            'india'           => 'India',
            'usa'             => 'USA',
            'canada'          => 'Canada',
            'singapore'       => 'Singapore',
            'united-kingdom'  => 'United Kingdom',
            'uae'             => 'UAE',
            'australia'       => 'Australia',
            'qatar'           => 'Qatar',
            'kuwait'          => 'Kuwait',
            'european-union'  => 'European Union',
        ];

        // Normalize input like "United Kingdom" → "united-kingdom"
        $countryParam = $request->country ?: 'india';
        $countrySlug  = Str::slug($countryParam, '-');

        if (!array_key_exists($countrySlug, $countries)) {
            abort(404, 'Country not found');
        }

        $countryDisplay = $countries[$countrySlug];

        $timezones = Timezone::query()
            ->where('country', $countrySlug)
            ->where('status', 'ACTIVE')
            ->select('timezone')
            ->distinct()
            ->orderBy('timezone')
            ->get();

        return view('Frontend.country', [
            'country'      => $countryDisplay,
            'country_slug' => $countrySlug,
            'timezones'    => $timezones,
        ]);
    }

    /**
     * What: Handle trial class booking form submission for country-specific pages.
     * Why: Process and store requests for trial booking with country/timezone normalization.
     */
    public function storeBookATrailFormForCountry(Request $request)
    {
        // Timezone alias map to canonical labels
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
        $fullMobile  = $countryCode . $rawMobile;

        $rules = [
            'enrollment_plan'     => ['required','string','max:255'],
            'country'             => ['required','string','max:255'],
            'city'                => ['required','string','max:255'],
            'timezone'            => ['required','string'],
            'kids_first_name'     => ['required','string','max:255'],
            'kids_last_name'      => ['nullable','string','max:255'],
            'age'                 => ['required','integer'],
            'mobile'              => [
                'required','string','max:15',
                function ($attribute, $value, $fail) use ($fullMobile) {
                    $inUsers = User::where('mobile', $fullMobile)->exists();
                    $inLeads = DemoLeadEnquiry::where('mobile', $fullMobile)->exists();
                    if ($inUsers || $inLeads) {
                        $fail('Mobile number already exists.');
                    }
                },
                Rule::when($request->country === 'India', ['digits:10']),
            ],
            'country_code'        => ['required','string','max:6'],
            'available_device'    => ['nullable','string','max:255'],
            'language_preference' => ['required','string','max:255'],
            'level'               => ['required','string','max:255'],
            'email'               => ['required','email','max:255'],
            // 'date' => ['required','date','after_or_equal:today','before_or_equal:'.$maxDate->toDateString()],
            // 'time' => ['required','date_format:H:i:s'],
        ];

        $messages = [
            'date.before_or_equal' => 'The date must be a date before or equal to ' . $maxDate->format('d M, Y') . '.',
        ];

        $data = $request->validate($rules, $messages);

        // Normalize timezone & set computed fields
        $data['timezone']     = $tzAliases[$data['timezone']] ?? $data['timezone'];
        $data['duration']     = $data['duration'] ?? '25_minutes';
        $data['status']       = 'ACTIVE';
        $data['lead_status']  = 'ACTIVE';
        $data['email_otp']    = random_int(1000, 9999);
        $data['mobile']       = $fullMobile;

        [$user, $demoLeadEnquiry] = DB::transaction(function () use ($data, $request, $countryCode) {
            // Lead
            $demoLeadEnquiry = DemoLeadEnquiry::create($data);
            $demoLeadEnquiry->utm_source = $request->input('utm_source');
            $demoLeadEnquiry->utm_medium = $request->input('utm_medium');
            $demoLeadEnquiry->save();

            // User
            $user = new User();
            $user->first_name   = $request->input('kids_first_name');
            $user->country_code = $countryCode;
            $user->mobile       = $data['mobile'];
            $user->email        = $request->input('email');
            $user->password     = Hash::make('12345678'); // temp
            $user->save();

            $password        = 'archer@' . $user->id;
            $user->device_id = $password;
            $user->password  = Hash::make($password);
            $user->save();

            $user->assignRole('Student');
            if ($role = Role::where('name', 'Student')->first()) {
                $user->syncPermissions($role->permissions()->pluck('name')->all());
            }

            $demoLeadEnquiry->user_id = $user->id;
            $demoLeadEnquiry->save();

            return [$user, $demoLeadEnquiry];
        });

        session(['demo_lead_enquiry' => $demoLeadEnquiry->toArray()]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Trial class booked successfully!',
            'user_id' => $user->id,
        ]);
    }

    /* ===========================
       Private helpers
       =========================== */

    /**
     * Stream a CSV download from an array of rows.
     */
    private function csvResponse(string $filename, array $rows)
    {
        $handle = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csvContent, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }



    public function student_cancelled_attendance(Request $request)
    {
        // Step 1: Get all student attendances that are CANCELLED (for the given dates)
        $studentAttendances = StudentAttendance::with(['student', 'batch', 'coach.user'])
            ->where('status', 'CANCELLED')
            ->whereIn('date', [
                '2025-08-09',
                '2025-08-15',
                '2025-08-27',
                '2025-10-02',
                '2025-10-20',
                '2025-10-21',
                '2025-10-22',
            ])
            ->where('type', '!=', 'Demo')
            ->orderBy('date')
            ->get();

        // Step 2: Filter only those students having 2 or more CANCELLED attendances
        $filteredAttendances = $studentAttendances
            ->groupBy('student_id')
            ->filter(function ($group) {
                return $group->count() >= 2;
            })
            ->flatten()
            ->sortByDesc('date'); // ✅ Ensures final ordering by date


        return view('Frontend.student_cancelled_attendance', [
            'studentAttendances' => $filteredAttendances
        ]);
    }


    public function updateFeeEndDate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'end_date' => 'required|date',
        ]);

        // Find latest fee record for that student
        $feeRecord = StudentFee::where('student_id', $request->student_id)
            ->orderBy('end_date', 'desc')
            ->first();

        if (!$feeRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'No fee record found for this student.'
            ]);
        }

        // Update only the end_date
        $feeRecord->end_date = $request->end_date;
        $feeRecord->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Fee end date updated successfully.',
            'formatted_date' => toIndianDate($feeRecord->end_date),
        ]);
    }



}
