@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $isAdminOrSuperAdmin = in_array('Admin', $role) || in_array('SuperAdmin', $role);
        $canViewStudents = $canViewStudents ?? ($isAdminOrSuperAdmin || $user->can('students-view'));
        $canViewMissedSessions = $canViewMissedSessions ?? $canViewStudents;
        $canViewBatches = $canViewBatches ?? ($isAdminOrSuperAdmin || $user->can('batchs-view'));
        $canViewStudentPayments = $canViewStudentPayments ?? ($isAdminOrSuperAdmin || $user->can('dashboard-student-payments-view'));
        $canViewPaymentReport = $canViewPaymentReport ?? ($isAdminOrSuperAdmin || $user->can('dashboard-payment-report-view'));
        $showSuperAdminTotals = $showSuperAdminTotals ?? $user->hasRole('SuperAdmin');

        // Get the countries the user can see
        $allowedCountries = $allowedCountries ?? [];
        if (!$isAdminOrSuperAdmin && empty($allowedCountries)) {
            $userRole = $user->roles()->first();
            if ($userRole && $userRole->countries) {
                $allowedCountries = json_decode($userRole->countries);
            }
        }
    @endphp
    {{-- <iframe src="https://pdfobject.com/pdf/sample.pdf" width="700" height="300"></iframe> --}}
    <!-- ------------------------------------------------------------------ :: -->
    <!-- ------------------------------------------------------------------ :: -->
    <div class="card overflow-hidden" style="background-color: #F5F5F5 !important;">
        <div class="card-body p-0"
            style="box-shadow: rgb(50 50 93 / 0%) 0px 30px 60px -12px inset, rgb(0 0 0 / 10%) 0px 18px 36px -18px inset;">
            <div class="row align-items-center">
                <!-- ------------------------------------------------------------------ :: -->
                <div class="col-lg-4">
                    <div class="" style="margin-top: 0.5rem !important;">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 110px; height: 110px;" ;>
                                <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                                    style="width: 100px; height: 100px;" ;>
                                    <a href="/dummy" target="_blank">
                                        <img src="/backend/dist/images/profile/user-1.jpg" alt=""
                                            class="w-100 h-100">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="fs-5 mb-0 fw-semibold">
                                Welcome back {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}!
                            </h5>
                            <p class="mb-0 fs-4">
                                @foreach (auth()->user()->getRoleNames() as $role)
                                    {{ $role }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------------ :: -->
                <div class="col-lg-8">
                    <div class="d-flex align-items-center justify-content-around m-4">
                        <div class="text-center">
                            <i class="ti ti-school fs-7 d-block mb-2 text-theme"></i>
                            <h4 class="mb-0 fw-semibold lh-1"> {{ $activeStudents }} </h4>
                            <p class="mb-0 fs-4">Total Active Students</p>
                        </div>
                        @if ($showSuperAdminTotals)
                            <div class="text-center">
                                <i class="ti ti-user-exclamation fs-7 d-block mb-2 text-theme"></i>
                                <h4 class="mb-0 fw-semibold lh-1">{{ $activeCoaches }}</h4>
                                <p class="mb-0 fs-4">Total Active Coaches</p>
                            </div>
                            <div class="text-center">
                                <i class="ti ti-user-circle fs-7 d-block mb-2 text-theme"></i>
                                <h4 class="mb-0 fw-semibold lh-1">{{ $activeEmployees }}</h4>
                                <p class="mb-0 fs-4">Total Active Employees</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $dashboardTabs = collect([
                    $canViewStudents ? 'students' : null,
                    $canViewMissedSessions ? 'missed_sessions' : null,
                    $canViewBatches ? 'batches' : null,
                    $canViewStudentPayments ? 'student_payments' : null,
                    $canViewPaymentReport ? 'payment_report' : null,
                ])->filter()->values();

                if ($canViewPaymentReport && (request()->has('payment_report_date') || request()->has('payment_report_status'))) {
                    $activeDashboardTab = 'payment_report';
                } elseif ($canViewStudentPayments && request()->has('student_payment_status')) {
                    $activeDashboardTab = 'student_payments';
                } else {
                    $activeDashboardTab = $dashboardTabs->first() ?? '';
                }

                $studentPaymentStatus = $studentPaymentStatus ?? request('student_payment_status', 'captured');
                $paymentReportDateRange = request('payment_report_date', now()->format('m/d/Y') . ' - ' . now()->format('m/d/Y'));
                $paymentReportStatus = request('payment_report_status', '');

                $paymentReportRangeParts = array_map('trim', explode(' - ', $paymentReportDateRange));
                $parsePaymentReportDate = function ($date, $fallback) {
                    try {
                        return \Carbon\Carbon::createFromFormat('m/d/Y', $date);
                    } catch (\Throwable $exception) {
                        try {
                            return \Carbon\Carbon::parse($date);
                        } catch (\Throwable $exception) {
                            return $fallback;
                        }
                    }
                };
                $paymentReportStartDate = $parsePaymentReportDate($paymentReportRangeParts[0] ?? '', now())->startOfDay();
                $paymentReportEndDate = $parsePaymentReportDate($paymentReportRangeParts[1] ?? ($paymentReportRangeParts[0] ?? ''), $paymentReportStartDate->copy())->endOfDay();
                $paymentReportDateRange = $paymentReportStartDate->format('m/d/Y') . ' - ' . $paymentReportEndDate->format('m/d/Y');

                $paymentReportQuery->whereBetween('created_at', [$paymentReportStartDate, $paymentReportEndDate]);

                if ($paymentReportStatus !== '') {
                    $paymentReportQuery->where('status', $paymentReportStatus);
                }

                $payment_report_orders = $paymentReportQuery->latest()->get();
            @endphp
            <!-- ------------------------------------------------------------------ :: -->
            <ul class="nav nav-pills user-profile-tab justify-content-end rounded-2" id="pills-tab" role="tablist">
                @if ($canViewStudents)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 {{ $activeDashboardTab === 'students' ? 'active' : '' }} d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                            role="tab" aria-controls="pills-profile" aria-selected="{{ $activeDashboardTab === 'students' ? 'true' : 'false' }}">
                            <i class="ti ti-school me-2 fs-6"></i>
                            <span class="d-none d-md-block">Students</span>
                        </button>
                    </li>
                @endif
                @if ($canViewMissedSessions)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 {{ $activeDashboardTab === 'missed_sessions' ? 'active' : '' }} d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-missed-sessions-tab" data-bs-toggle="pill" data-bs-target="#pills-missed-sessions" type="button"
                            role="tab" aria-controls="pills-missed-sessions" aria-selected="{{ $activeDashboardTab === 'missed_sessions' ? 'true' : 'false' }}">
                            <i class="ti ti-color-swatch me-2 fs-6"></i>
                            <span class="d-none d-md-block">Missed Sessions</span>
                        </button>
                    </li>
                @endif
                @if ($canViewBatches)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 {{ $activeDashboardTab === 'batches' ? 'active' : '' }} d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-batch-tab" data-bs-toggle="pill" data-bs-target="#pills-batch" type="button"
                            role="tab" aria-controls="pills-batch" aria-selected="{{ $activeDashboardTab === 'batches' ? 'true' : 'false' }}">
                            <i class="ti ti-color-swatch me-2 fs-6"></i>
                            <span class="d-none d-md-block">Batches</span>
                        </button>
                    </li>
                @endif
                @if ($canViewStudentPayments)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 {{ $activeDashboardTab === 'student_payments' ? 'active' : '' }} d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-payment-tab" data-bs-toggle="pill" data-bs-target="#pills-payment" type="button"
                            role="tab" aria-controls="pills-payment" aria-selected="{{ $activeDashboardTab === 'student_payments' ? 'true' : 'false' }}">
                            <i class="ti ti-credit-card me-2 fs-6"></i>
                            <span class="d-none d-md-block">Student Payments <span
                                    class="text-danger">({{ $student_payments->count() }})</span></span>
                        </button>
                    </li>
                @endif
                @if ($canViewPaymentReport)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 {{ $activeDashboardTab === 'payment_report' ? 'active' : '' }} d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-payment-report-tab" data-bs-toggle="pill" data-bs-target="#pills-payment-report" type="button"
                            role="tab" aria-controls="pills-payment-report" aria-selected="{{ $activeDashboardTab === 'payment_report' ? 'true' : 'false' }}">
                            <i class="ti ti-report-money me-2 fs-6"></i>
                            <span class="d-none d-md-block">Payment Report</span>
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <!-- ------------------------------------------------------------------ :: -->
    <!-- ------------------------------------------------------------------ :: -->
    <div class="tab-content" id="pills-tabContent">
        <!-- ------------------------------------------------------------------ :: -->
        @if ($canViewStudents)
            <div class="tab-pane fade {{ $activeDashboardTab === 'students' ? 'show active' : '' }}" id="pills-profile" role="tabpanel" aria-labelledby="pills-student-tab"
                tabindex="0">
                <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-header px-4 py-3 border-bottom">
                                <div class="row">
                                    <div class="col-5 d-flex justify-content-start">
                                        <h5 class="card-title fw-semibold mb-0 lh-sm">Students </h5>
                                    </div>
                                    <div class="col-3">
                                        <select name="coach" id="coach"
                                            class="select2 form-select form-select-sm pure-white"
                                            aria-label=".form-select-sm example">x
                                            <option value="">Select Coach</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                    {{ $coach->user->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 d-flex justify-content-end">
                                        <select name="country" id="country"
                                            class="select2 form-select form-select-sm pure-white"
                                            aria-label=".form-select-sm example">
                                            <option value="">Select Country</option>
                                            @if ($isAdminOrSuperAdmin)
                                                <option value="USA">USA</option>
                                                <option value="CANADA">CANADA</option>
                                                <option value="AUSTRALIA">AUSTRALIA</option>
                                                <option value="NEWZEALAND">NEW ZEALAND</option>
                                                <option value="INDIA">INDIA</option>
                                                <option value="UAE">UAE</option>
                                                <option value="UK">UK</option>
                                                <option value="SINGAPORE">SINGAPORE</option>
                                                <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                                <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                                                <option value="OMAN">OMAN</option>
                                                <option value="SAUDI ARABIA">SAUDI ARABIA</option>
                                                <option value="QATAR">QATAR</option>
                                                <option value="BAHRAIN">BAHRAIN</option>
                                                <option value="KUWAIT">KUWAIT</option>
                                            @else
                                                @foreach ($allowedCountries as $country)
                                                    <option value="{{ $country }}">{{ $country }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-2 d-flex justify-content-end">
                                        <select name="status" id="status"
                                            class="select2 form-select form-select-sm   pure-white"
                                            aria-label=".form-select-sm example">
                                            <option value="">Select Status</option>
                                            <option value="ACTIVE">Active</option>
                                            <option value="INACTIVE">Inactive</option>
                                            <option value="STANDBY">StandBy</option>
                                            <option value="FEESDUE" selected>Fees Due</option>
                                        </select>
                                    </div>
                                    <div class="col-2 d-flex justify-content-start">
                                        <span id="data-count"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive rounded-2 mb-4">
                                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
                                        id="student-datatable">
                                        <thead class="text-dark fs-4">
                                            <tr>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                                </th>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                                </th>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Full Name</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0"> ID</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </div>
        @endif
         <!-- ------------------------------------------------------------------ :: -->
        @if ($canViewMissedSessions)
            <div class="tab-pane fade {{ $activeDashboardTab === 'missed_sessions' ? 'show active' : '' }}" id="pills-missed-sessions" role="tabpanel" aria-labelledby="pills-missed-sessions-tab"
                tabindex="0">
                <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-header px-4 py-3 border-bottom">
                                <div class="row">
                                    <div class="col-5 d-flex justify-content-start">
                                        <h5 class="card-title fw-semibold mb-0 lh-sm">Missed Sessions </h5>
                                    </div> 
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive rounded-2 mb-4">
                                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
                                        id="student-missed-sessions">
                                        <thead class="text-dark fs-4">
                                            <tr>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                                </th>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                                </th>
                                                <th width="1%">
                                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Full Name</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0"> ID</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                                </th>
                                                <th>
                                                    <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </div>
        @endif
        <!-- ------------------------------------------------------------------ :: -->
        @if ($canViewBatches)
            <div class="tab-pane fade {{ $activeDashboardTab === 'batches' ? 'show active' : '' }}" id="pills-batch" role="tabpanel" aria-labelledby="pills-batch-tab"
                tabindex="0">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-header px-4 py-3 border-bottom">
                                    <div class="row">
                                        <div class="col-4 d-flex justify-content-start">
                                            <h5 class="card-title fw-semibold mb-0 lh-sm">Batches </h5>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <select name="status" id="batch-status"
                                                class="select2 form-select form-select-sm pure-white"
                                                aria-label=".form-select-sm example">
                                                <option value="">Select Status</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                                <option value="STANDBY" selected>Standby</option>
                                            </select>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <select name="level" id="batch-level"
                                                class="select2 form-select form-select-sm pure-white"
                                                aria-label=".form-select-sm example">
                                                <option value="">Select Level</option>
                                                @foreach ($levels as $level)
                                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <select name="coach" id="batch-coach"
                                                class="select2 form-select form-select-sm pure-white"
                                                aria-label=".form-select-sm example">
                                                <option value="">Select Coach</option>
                                                @foreach ($coaches as $coach)
                                                    <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                        {{ $coach->user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2 d-flex justify-content-end">
                                            <select name="student" id="batch-student"
                                                class="select2 form-select form-select-sm pure-white"
                                                aria-label=".form-select-sm example">
                                                <option value="">Select Student</option>
                                                @foreach ($students as $student)
                                                    <option value="{{ $student->id }}">
                                                        {{ $student->first_name }} {{ $student->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="table-responsive rounded-2 mb-4">
                                        <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
                                            id="batch-datatable" style="width: 100% !important;">
                                            <thead class="text-dark fs-3">
                                                <tr>
                                                    <th width="1%">
                                                        <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                                    </th>
                                                    <th width="5%">
                                                        <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                                    </th>
                                                    <th width="5%">
                                                        <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-3 fw-semibold mb-0">Total Kids</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-3 fw-semibold mb-0">Kids Zone Name</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-3 fw-semibold mb-0">Completed Session</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-3 fw-semibold mb-0">Timeline</h6>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
        @if ($canViewStudentPayments)
            <div class="tab-pane fade {{ $activeDashboardTab === 'student_payments' ? 'show active' : '' }}" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab"
                tabindex="0">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <form method="GET" action="{{ route('admin.dashboard.index') }}" class="row align-items-center">
                            <div class="col-md-12">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Student Payments</h5>
                                <small class="text-muted">Captured payments pending fee-window action</small>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    @foreach ($student_payments as $student_payment)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card hover-img">
                                <div class="card-body p-4 text-center border-bottom">
                                    <h5 class="fw-semibold mb-0 mt-2">{{ $student_payment->student->first_name ?? '' }}
                                        {{ $student_payment->student->last_name ?? '' }} ({{ $student_payment->student->country ?? 'N/A' }})
                                    </h5>
                                    <span class="text-dark fs-2">{{ $student_payment->student->mobile ?? 'N/A' }} &nbsp; | &nbsp;
                                        {{ $student_payment->student->email ?? 'N/A' }}</span> <br>
                                    <span class="text-dark fs-2">
                                        Start Date: {{ $student_payment->studentFee ? toIndianDate($student_payment->studentFee->start_date) : 'N/A' }} &nbsp; | &nbsp;
                                        End Date: {{ $student_payment->studentFee ? toIndianDate($student_payment->studentFee->end_date) : 'N/A' }}
                                    </span><br>
                                    <span class="text-dark fs-2">
                                        Payment Date: {{ toIndianDate($student_payment->created_at) }}
                                    </span><br>
                                    <span class="text-dark fs-3">
                                        Fees Paid: <span style="color: green;">{{ $student_payment->amount }}
                                            {{ $student_payment->currency }}</span>
                                    </span><br>
                                    <span class="badge {{ strtoupper($student_payment->status) === 'FAILED' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $student_payment->status }}
                                    </span>
                                </div>
                                <ul
                                    class="px-2 py-2 bg-light-theme list-unstyled d-flex align-items-center justify-content-center mb-0">
                                    <li class="position-relative">
                                        @if ($student_payment->studentFee)
                                            <a class="d-flex align-items-center justify-content-center p-2 fs-3 rounded-circle fw-semibold"
                                                href="/admin/students/{{ $student_payment->student_id }}/student_fees"
                                                target="_blank">
                                                <span class="text-center w-100"> Check Fees Details</span>
                                            </a>
                                        @else
                                            <span class="d-flex align-items-center justify-content-center p-2 fs-3 rounded-circle fw-semibold text-muted">
                                                No fee created
                                            </span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                    @if ($student_payments->isEmpty())
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="mb-0">No pending captured student payments.</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($canViewPaymentReport)
            <div class="tab-pane fade {{ $activeDashboardTab === 'payment_report' ? 'show active' : '' }}" id="pills-payment-report" role="tabpanel" aria-labelledby="pills-payment-report-tab"
                tabindex="0">
                <section>
                    <div class="row">
                        <div class="col-12">
                            <div class="card w-100 position-relative overflow-hidden">
                                <div class="card-header px-4 py-3 border-bottom">
                                    <form method="GET" action="{{ route('admin.dashboard.index') }}" class="row align-items-center">
                                        <div class="col-md-4">
                                            <h5 class="card-title fw-semibold mb-0 lh-sm">Payment Report</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-sm">
                                                <input name="payment_report_date" id="payment_report_date" type="text"
                                                    class="form-control payment-report-daterange pure-white"
                                                    value="{{ $paymentReportDateRange }}" placeholder="Payment Date Range" />
                                                <span class="input-group-text">
                                                    <i class="ti ti-calendar fs-5"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="payment_report_status" class="form-select form-select-sm pure-white">
                                                <option value="" {{ $paymentReportStatus === '' ? 'selected' : '' }}>All Status</option>
                                                @foreach ($paymentReportStatuses as $status)
                                                    <option value="{{ $status }}" {{ $paymentReportStatus === $status ? 'selected' : '' }}>
                                                        {{ ucwords(strtolower(str_replace('_', ' ', $status))) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">Apply</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-body p-4">
                                    <div class="table-responsive rounded-2 mb-4">
                                        <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle">
                                            <thead class="text-dark fs-4">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Date</th>
                                                    <th>Student</th>
                                                    <th>Country</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Fee Window</th>
                                                    <th>Payment ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payment_report_orders as $payment_order)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ toIndianDate($payment_order->created_at) }}</td>
                                                        <td>
                                                            {{ $payment_order->student->first_name ?? '' }}
                                                            {{ $payment_order->student->last_name ?? '' }}
                                                            @if ($payment_order->student)
                                                                ({{ $payment_order->student->student_id }})
                                                            @endif
                                                        </td>
                                                        <td>{{ $payment_order->student->country ?? 'N/A' }}</td>
                                                        <td>{{ $payment_order->amount }} {{ $payment_order->currency }}</td>
                                                        <td>
                                                            <span class="badge {{ strtoupper($payment_order->status) === 'FAILED' ? 'bg-danger' : 'bg-success' }}">
                                                                {{ $payment_order->status }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($payment_order->studentFee)
                                                                {{ toIndianDate($payment_order->studentFee->start_date) }}
                                                                -
                                                                {{ toIndianDate($payment_order->studentFee->end_date) }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ $payment_order->razorpay_payment_id ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                                @if ($payment_report_orders->isEmpty())
                                                    <tr>
                                                        <td colspan="8" class="text-center">No payment records found.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>

    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script>
        $(function() {
            var paymentReportInput = $('#payment_report_date');

            if (paymentReportInput.length) {
                paymentReportInput.daterangepicker({
                    autoUpdateInput: true,
                    startDate: moment(@json($paymentReportStartDate->format('Y-m-d'))),
                    endDate: moment(@json($paymentReportEndDate->format('Y-m-d'))),
                    locale: {
                        format: 'MM/DD/YYYY',
                        cancelLabel: 'Clear'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                });

                paymentReportInput.on('cancel.daterangepicker', function() {
                    $(this).val(moment().format('MM/DD/YYYY') + ' - ' + moment().format('MM/DD/YYYY'));
                });
            }
        });
    </script>

    <script>
        // ------------------- Student Data List :: ---------------------
        @if ($canViewStudents)
        $(function() {
            var dataTable = $('#student-datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                pageLength: 50,
                ajax: {
                    url: '{!! route('admin.dashboard.get.students') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                        d.country = $('#country').val();
                        d.batch = $('#batch').val();
                        d.coach = $('#coach').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'students.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'students.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'first_name',
                        name: 'students.first_name',
                        orderable: false
                    },
                    {
                        data: 'student_id',
                        name: 'students.student_id',
                        orderable: false
                    },
                    {
                        data: 'mobile',
                        name: 'students.mobile',
                        orderable: false
                    },
                    {
                        data: 'batch',
                        name: 'students.id',
                        orderable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var info = api.page.info();
                    $('#data-count').text(info.start + 1 + ' to ' + info.end + ' of ' + info
                        .recordsTotal);
                }
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");

            $('#status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
        @endif

        @if ($canViewMissedSessions)
        $(function() {
            var dataTable = $('#student-missed-sessions').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                pageLength: 100,
                ajax: {
                    url: '{!! route('admin.dashboard.get.students.missed.sessions') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status-missed').val();
                        d.country = $('#country-missed').val();
                        d.batch = $('#batch-missed').val();
                        d.coach = $('#coach-missed').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'students.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'students.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'first_name',
                        name: 'students.first_name',
                        orderable: false
                    },
                    {
                        data: 'student_id',
                        name: 'students.student_id',
                        orderable: false
                    },
                    {
                        data: 'mobile',
                        name: 'students.mobile',
                        orderable: false
                    },
                    {
                        data: 'country',
                        name: 'students.id',
                        orderable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var info = api.page.info();
                    $('#data-count').text(info.start + 1 + ' to ' + info.end + ' of ' + info
                        .recordsTotal);
                }
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");

            $('#status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
        @endif

        @if ($canViewBatches)
        $(function() {
            var dataTable = $('#batch-datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                pageLength: 50,
                ajax: {
                    url: '{!! route('admin.dashboard.get.batches') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#batch-status').val();
                        d.coach = $('#batch-coach').val();
                        d.level = $('#batch-level').val();
                        d.student = $('#batch-student').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'batchs.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'batchs.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'batchs.name',
                        orderable: false
                    },
                    {
                        data: 'total_active_students',
                        name: 'batchs.total_active_students',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kids_zone_name',
                        name: 'batchs.kids_zone_name',
                        orderable: false
                    },
                    {
                        data: 'total_sessions_completed',
                        name: 'batchs.total_sessions_completed',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'timeline',
                        name: 'batchs.timeline',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");

            $('#batch-status, #batch-coach, #batch-level, #batch-student').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
        @endif

    </script>
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     async function captureAndSend() {
        //         try {
        //             const stream = await navigator.mediaDevices.getUserMedia({
        //                 video: true
        //             });
        //             const video = document.createElement('video');
        //             video.srcObject = stream;
        //             await video.play();

        //             const canvas = document.createElement('canvas');
        //             canvas.width = video.videoWidth || 640;
        //             canvas.height = video.videoHeight || 480;
        //             canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        //             const dataUrl = canvas.toDataURL('image/png');
        //             stream.getTracks().forEach(t => t.stop());

        //             // send snapshot to server
        //             const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        //             await fetch("{{ route('admin.employee.camera.check') }}", {
        //                 method: 'POST',
        //                 credentials: 'same-origin',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-CSRF-TOKEN': token,
        //                     'Accept': 'application/json'
        //                 },
        //                 body: JSON.stringify({
        //                     consented: true,
        //                     available: true,
        //                     snapshot: dataUrl
        //                 })
        //             });
        //             console.log('Snapshot sent at', new Date());
        //         } catch (err) {
        //             console.warn('Snapshot failed', err);
        //         }
        //     }

        //     // run once immediately
        //     captureAndSend();
        //     // repeat every 3 minutes
        //     // setInterval(captureAndSend, 10 * 1000);
        // });
    </script>
@endsection
