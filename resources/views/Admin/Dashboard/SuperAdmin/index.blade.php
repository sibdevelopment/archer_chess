@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $isAdminOrSuperAdmin = in_array('Admin', $role) || in_array('SuperAdmin', $role);

        // Get the countries the user can see
        $allowedCountries = [];
        if (!$isAdminOrSuperAdmin) {
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
                    </div>
                </div>
            </div>
            @php
                $student_payments = App\Models\StudentFee::where('student_id', '!=', null)
                    ->where('currency', null)
                    ->where('status', 'ACTIVE')
                    ->orderBy('id', 'DESC')
                    ->get();
                // dd($student_payments);
            @endphp
            <!-- ------------------------------------------------------------------ :: -->
            <ul class="nav nav-pills user-profile-tab justify-content-end rounded-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                        role="tab" aria-controls="pills-profile" aria-selected="true">
                        <i class="ti ti-school me-2 fs-6"></i>
                        <span class="d-none d-md-block">Students</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-missed-sessions-tab" data-bs-toggle="pill" data-bs-target="#pills-missed-sessions" type="button"
                        role="tab" aria-controls="pills-missed-sessions" aria-selected="false">
                        <i class="ti ti-color-swatch me-2 fs-6"></i>
                        <span class="d-none d-md-block">Missed Sessions</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-batch-tab" data-bs-toggle="pill" data-bs-target="#pills-batch" type="button"
                        role="tab" aria-controls="pills-batch" aria-selected="false">
                        <i class="ti ti-color-swatch me-2 fs-6"></i>
                        <span class="d-none d-md-block">Batches</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-coach-tab" data-bs-toggle="pill" data-bs-target="#pills-followers" type="button"
                        role="tab" aria-controls="pills-followers" aria-selected="false">
                        <i class="ti ti-user-exclamation me-2 fs-6"></i>
                        <span class="d-none d-md-block">Coaches</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-employee-tab" data-bs-toggle="pill" data-bs-target="#pills-friends" type="button"
                        role="tab" aria-controls="pills-friends" aria-selected="false">
                        <i class="ti ti-user-circle me-2 fs-6"></i>
                        <span class="d-none d-md-block">Employees</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                        id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-payment" type="button"
                        role="tab" aria-controls="pills-payment" aria-selected="true">
                        <i class="ti ti-credit-card me-2 fs-6"></i>
                        <span class="d-none d-md-block">Student Payments <span
                                class="text-danger">({{ $student_payments->count() }})</span></span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <!-- ------------------------------------------------------------------ :: -->
    <!-- ------------------------------------------------------------------ :: -->
    <div class="tab-content" id="pills-tabContent">
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-student-tab"
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
         <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade " id="pills-missed-sessions" role="tabpanel" aria-labelledby="pills-missed-sessions-tab"
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
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade" id="pills-batch" role="tabpanel" aria-labelledby="pills-batch-tab" tabindex="0">
            <section>
                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-header px-4 py-3 border-bottom">
                                <div class="row">
                                    <div class="col-4 d-flex justify-content-start">
                                        <h5 class="card-title fw-semibold mb-0 lh-sm">Batches </h5>
                                    </div>
                                    <div class="col-2 d-flex justify-content-end ">
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
                                                    {{ $student->first_name }}{{ $student->last_name }}</option>
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
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade" id="pills-followers" role="tabpanel" aria-labelledby="pills-coach-tab"
            tabindex="0">
            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h4 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Active Coaches </h4>
            </div>
            <div class="row">
                @foreach ($coaches as $coach)
                    @if ($coach->status === 'ACTIVE')
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body p-4 d-flex align-items-center gap-3">
                                    <i class="ti ti-user-exclamation me-2 fs-7 text-theme"></i>
                                    <div>
                                        <h5 class="fw-semibold mb-0">{{ $coach->user->first_name }}
                                            {{ $coach->user->last_name }}
                                        </h5>
                                        <span class="fs-2 d-flex align-items-center mt-2">
                                            <i class="ti ti-mail text-dark fs-3 me-1"></i>{{ $coach->user->email }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade" id="pills-friends" role="tabpanel" aria-labelledby="pills-employee-tab"
            tabindex="0">
            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h4 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Active Employees </h4>
            </div>
            <div class="row">
                @foreach ($employees as $employee)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card hover-img">
                            <div class="card-body p-4 text-center border-bottom">
                                <i class="ti ti-user-circle me-2 fs-7 text-theme"></i>
                                <h5 class="fw-semibold mb-0 mt-2">{{ $employee->user->first_name }}
                                    {{ $employee->user->last_name }}
                                </h5>
                                <span class="text-dark fs-2">{{ $employee->user->mobile }} &nbsp; | &nbsp;
                                    {{ $employee->user->email }}</span> <br>
                                <span class="text-dark fs-2">
                                    {{ implode(
                                        ', ',
                                        $employee->user->roles->flatMap(function ($role) {
                                                return json_decode($role->countries, true);
                                            })->toArray(),
                                    ) }}
                                </span>
                            </div>
                            <ul
                                class="px-2 py-2 bg-light-theme list-unstyled d-flex align-items-center justify-content-center mb-0">
                                <li class="position-relative">
                                    <a class="d-flex align-items-center justify-content-center p-2 fs-3 rounded-circle fw-semibold"
                                        href="javascript:void(0)">
                                        <i class="ti ti-user-check p-2 fs-5 text-theme"></i> &nbsp;
                                        <span
                                            class="text-center w-100">{{ implode(', ', $employee->user->roles->pluck('name')->toArray()) }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="tab-pane fade" id="pills-payment" role="tabpanel" aria-labelledby="pills-employee-tab"
            tabindex="0">
            {{-- <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                <h4 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Student Payments </h4>
            </div> --}}
            @php
                // dd($student_payments);
            @endphp
            <div class="row">
                @foreach ($student_payments as $student_payment)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card hover-img">
                            <div class="card-body p-4 text-center border-bottom">
                                <h5 class="fw-semibold mb-0 mt-2">{{ $student_payment->student->first_name }}
                                    {{ $student_payment->student->last_name }} ({{ $student_payment->student->country }})
                                </h5>
                                <span class="text-dark fs-2">{{ $student_payment->student->mobile }} &nbsp; | &nbsp;
                                    {{ $student_payment->student->email }}</span> <br>
                                <span class="text-dark fs-2">
                                    Start Date: {{ toIndianDate($student_payment->start_date) }} &nbsp; | &nbsp;
                                    End Date: {{ toIndianDate($student_payment->end_date) }}
                                </span><br>
                                <span class="text-dark fs-3">
                                    Fees Paid: <span style="color: green;">{{ $student_payment->monthly_fees }}
                                        {{ $student_payment->currency }}</span>
                                </span>
                            </div>
                            <ul
                                class="px-2 py-2 bg-light-theme list-unstyled d-flex align-items-center justify-content-center mb-0">
                                <li class="position-relative">
                                    <a class="d-flex align-items-center justify-content-center p-2 fs-3 rounded-circle fw-semibold"
                                        href="/admin/students/{{ $student_payment->student->id }}/student_fees"
                                        target="_blank">
                                        <span class="text-center w-100"> Check Fees Details</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // ------------------- Student Data List :: ---------------------
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


        // ------------------- Batch Data List :: ---------------------
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
            $('#batch-status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch-coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch-level').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch-student').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
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
