@extends('layouts.admin')
@section('title')
    Report
@endsection
@section('content')

    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $coachId = null;
        if (in_array('Coach', $role) && $user->coach) {
            $coachId = $user->coach->id;
        }
        $isCoach = in_array('Coach', $role);
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

    <style>
        #datatable tbody tr td {
            color: black !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th,
        table td {
            padding: 1px;
            /* Adjust for smaller height */
            border: 1px solid #ddd;
        }

        table thead th {
            font-weight: bold;
            color: #333;
            font-size: 13px;
            /* Adjust for smaller headings */
        }

        .todaydayofweek {
            display: block;
            width: 100%;
            padding: 8px 16px;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #5a6a85;
            background-color: transparent;
            background-clip: padding-box;
            border: var(--bs-border-width) solid #dfe5ef;
            appearance: none;
            border-radius: 7px;
            box-shadow: unset;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .fc-toolbar-chunk .fc-today-button {
            display: none !important;
        }
    </style>
    <section>
        <!-- ------------------------------------------------------------------------- :: -->
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden mb-3">
                    <!-- ------------------------------------------------------------------------- :: -->
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            @if ($isCoach)
                                <div class="col-3 d-flex justify-content-start">
                                    <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example" disabled>
                                        <option value="">Select Coach</option>
                                        @foreach ($coaches as $coach)
                                            <option value="{{ $coach->id }}"
                                                {{ isset($coachId) && $coachId == $coach->id ? 'selected' : '' }}>
                                                {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-3 d-flex justify-content-start">
                                    <select name="coach" id="coach"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Coach</option>
                                        @foreach ($coaches as $index => $coach)
                                            <option value="{{ $coach->id }}"
                                                {{ (isset($coachId) && $coachId == $coach->id) || (!isset($coachId) && $index == 0) ? 'selected' : '' }}>
                                                {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-5 d-flex justify-content-end">
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <div class="input-group">
                                    <input name="date_Range" id="date_Range" type="text"
                                        class="form-control daterange" />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        {{--
                    <form action="" method="POST" enctype="multipart/form-data" id="downloadReport">
                        @csrf
                        <div class="row">
                            <div class="col-4 d-flex justify-content-end">
                                <input type="date" name="fromDate" class="form-control" id="fromDate"
                                    placeholder="From Date" value="{{ $firstDayOfMonth }}">
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <input type="date" name="toDate" class="form-control" id="toDate" placeholder="To Date"
                                    value="{{ $todayDate }}">
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Download Report</button>
                            </div>
                        </div>
                    </form>
                    --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- ------------------------------------------------------------------------- :: -->
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden mb-3">
                    <div class="card-header border-bottom p-0" id="getcounts">

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------------------- :: -->
    <section>
        <!-- Status Dropdown -->
        <div class="row">
            <!-- ------------------------------------------------------------------------- :: -->
            <div class="col-md-12">
                <div class="card" style="--bs-card-spacer-y: 0px !important; --bs-card-spacer-x: 0px !important;">
                    <div class="card-body" id="report-calendar">

                    </div>
                </div>
            </div>
            <!-- ------------------------------------------------------------------------- :: -->
            <div class="col-md-12">
                <div id="schedule"
                    style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important; border-radius: 7px !important;">
                </div>
            </div>
            <!-- ------------------------------------------------------------------------- :: -->
            <div class="col-md-6" style="display: none;">
                <div id="coachavailability" class="rounded"
                    style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important;">
                </div>

            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------------------- :: -->
    <!--  schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="scheduleData">
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ :: -->
    <!--  Attendance Modal -->
    <div class="modal fade" id="AttendanceModal" tabindex="-1" role="dialog" aria-labelledby="AttendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="AttendanceModalLabel">Mark Attendance (Coach & Student)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="AttendanceData">
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ :: -->
    <!-- Modal for Batch Student -->
    <div class="modal fade" id="BatchStudentModal" tabindex="-1" role="dialog" aria-labelledby="BatchStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="BatchStudentModalLabel">Student Batch Country Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5>Total Students in Batches &nbsp; : &nbsp; <span id="totalStudentsBatchesCount"
                                class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-bordered table-hover table-striped" id="studentsPerCountryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="2">
                                        <h5 class="mb-0 text-white">Students Per Country</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-flag"></i> &nbsp; Country</th>
                                    <th><i class="fas fa-users"></i> &nbsp; Total Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be appended here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive rounded">
                        <table class="table table-bordered table-hover table-striped " id="studentsWithoutCountryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="2">
                                        <h5 class="mb-0 text-white">Students Without Country</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-id-badge"></i> &nbsp; Student ID</th>
                                    <th><i class="fas fa-user"></i> &nbsp; Student Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be appended here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive rounded">
                        <table class="table table-bordered table-hover table-striped" id="studentsAttendanceTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Phone</th>
                                    <th>Coach</th>
                                    <th>Batch</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be appended here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ :: -->
    <!-- Modal for Total Batches Completed -->
    <div class="modal fade" id="BatchCompleteModal" tabindex="-1" role="dialog"
        aria-labelledby="BatchCompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="BatchCompleteModalLabel">Total Batches Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5> <span id="totalStudentsBatchesCompletedCount" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="batchDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Name</th>
                                    <th style="text-align: center;" width="3%">Reassign Times</th>
                                    <th style="text-align: center;">Country</th>
                                    <th style="text-align: center;" width="1%">Status</th>
                                    <th style="text-align: center;" width="5%">Level</th>
                                    <th style="text-align: center;">Timeline</th>
                                    <th style="text-align: center;" width="3%">Completed Count</th>
                                    <th style="text-align: center;" width="10%">Completed Dates</th>
                                    <th style="text-align: center;" width="3%">Completed Times</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DelayedBatchesModal" tabindex="-1" role="dialog"
        aria-labelledby="DelayedBatchesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="DelayedBatchesModalLabel">Delayed batches (report)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5><span id="delayedBatchesDateRange" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="delayedBatchesDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Batch Name</th>
                                    <th style="text-align: center;">Country</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Level</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Attendance Time</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CoverClassCompleteModal" tabindex="-1" role="dialog"
        aria-labelledby="CoverClassCompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="CoverclassCompleteModalLabel">Total Coverclasses Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5> <span id="totalStudentsCoverclassesCompletedCount" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="CoverclassDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Name</th>
                                    <th style="text-align: center;">Country</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Level</th>
                                    <th style="text-align: center;">Batch Date</th>
                                    <th style="text-align: center;">Attendance Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ :: -->
    <!-- Modal for Total Demos Completed -->
    <div class="modal fade" id="DemoCompleteModal" tabindex="-1" role="dialog"
        aria-labelledby="DemoCompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="DemoCompleteModalLabel">Total Demo Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5> <span id="totalStudentsDemoCompletedCount" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="DemoDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">First Name</th>
                                    <th style="text-align: center;">Age</th>
                                    <th style="text-align: center;">Mobile</th>
                                    <th style="text-align: center;">Country</th>
                                    <th style="text-align: center;">Kids Time Zone</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Time</th>
                                    <th style="text-align: center;">Kids Date</th>
                                    <th style="text-align: center;">Kids Time</th>
                                    <th style="text-align: center;">Session Status</th>
                                    <th style="text-align: center;">Session Date</th>
                                    <th style="text-align: center;">Session Time</th>
                                    {{-- <th style="text-align: center;">Session Slot</th>
                                <th style="text-align: center;">Session Coach Attendance Status</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------ :: -->
    <!-- Modal for Total Leave coach -->
    <div class="modal fade" id="CoachLeaveModal" tabindex="-1" role="dialog" aria-labelledby="CoachLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="CoachLeaveModalLabel">Total Coach Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5> <span id="totalCoachLeaveCount" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="CoachLeaveDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">From Date</th>
                                    <th style="text-align: center;">To Date</th>
                                    <th style="text-align: center;">From Time</th>
                                    <th style="text-align: center;">To Time</th>
                                    <th style="text-align: center;">Reason</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Masterclass Attendance -->
    <div class="modal fade" id="masterClassAttendanceModal" tabindex="-1" role="dialog"
        aria-labelledby="masterClassAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="masterClassAttendanceModalLabel">Total Masterclass Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h5> <span id="totalmasterClassAttendanceCount" class="badge bg-primary"></span></h5>
                    </div>
                    <div class="table-responsive mb-4 rounded">
                        <table class="table table-striped custom-table" id="masterClassAttendanceDataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Coach Name</th>
                                    <th style="text-align: center;">Masterclass Name</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Time</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/dist/libs/fullcalendar/index.global.min.js"></script>
    <script src="/backend/dist/js/apps/calendar-init.js"></script>
    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script>
        $(document).ready(function() {
            let now = new Date("{{ $todayDate }}");
            let utcOffset = now.getTimezoneOffset() * 60000;
            let istOffset = 5.5 * 60 * 60000;
            let istTime = new Date(now.getTime() + utcOffset + istOffset);
            let year = istTime.getFullYear();
            let month = ('0' + (istTime.getMonth() + 1)).slice(-2);
            $('#month').val(year + '-' + month);

            // Function to fetch and update counts based on the selected coach and date
            function fetchAndUpdateCounts(coachId, startDate, endDate) {
                let getCountsUrl = `{{ route('admin.reports.get.counts', ['coachId' => ':coachId']) }}`.replace(
                    ':coachId', coachId);
                $.ajax({
                    url: getCountsUrl,
                    type: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        $('#getcounts').html(response);
                    },
                    error: function(error) {
                        console.log('Error fetching counts:', error);
                    }
                });
            }

            // Updated AJAX code for batchStudent click event
            // -------------------------------------------------------------------- ::
            $(document).on('click', '#batchStudent', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.batchstudent.countrydata') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            $('#totalStudentsBatchesCount').text(data
                            .totalStudentsBatchesCount);
                            $('#studentsPerCountryTable tbody').empty();
                            data.studentsPerCountry.forEach(function(item) {
                                var row = '<tr><td>' + item.country + '</td><td>' + item
                                    .total + '</td></tr>';
                                $('#studentsPerCountryTable tbody').append(row);
                            });
                            $('#studentsWithoutCountryTable tbody').empty();
                            if (data.studentsWithoutCountry && data.studentsWithoutCountry
                                .length > 0) {
                                data.studentsWithoutCountry.forEach(function(student) {
                                    var row = '<tr><td>' + student.student_id +
                                        '</td><td>' + student.first_name + '</td></tr>';
                                    $('#studentsWithoutCountryTable tbody').append(row);
                                });
                            } else {
                                var row = '<tr><td colspan="2">No data found</td></tr>';
                                $('#studentsWithoutCountryTable tbody').append(row);
                            }
                            // Populate the students attendance table
                            $('#studentsAttendanceTable tbody').empty();
                            data.studentAttendanceData.forEach(function(attendance, index) {
                                var row = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    // Sequential number
                                    '<td>' + attendance.student_id + '</td>' +
                                    '<td>' + attendance.student.full_name + '</td>' +
                                    '<td>' + attendance.student.phone + '</td>' +
                                    '<td>' + attendance.coach.name + '</td>' +
                                    '<td>' + attendance.batch.name + '</td>' +
                                    '<td>' + attendance.date + '</td>' +
                                    '<td>' + attendance.time + '</td>' +
                                    '<td>' + attendance.status + '</td>' +
                                    '</tr>';
                                $('#studentsAttendanceTable tbody').append(row);
                            });

                            $('#BatchStudentModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            // Updated AJAX code for batchCompletedData click event
            // -------------------------------------------------------------------- ::
            $(document).on('click', '#batchCompletedData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.batch.completed.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.batchData) {
                            // Clear previous data
                            $('#batchDataTable tbody').empty();

                            // Set the date range in the h5 element
                            $('#totalStudentsBatchesCompletedCount').text(
                                `From ${data.startDate} to ${data.endDate}`);


                            // Populate the table with new data
                            data.batchData.forEach(function(batch, index) {
                                var row = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) +
                                    '</td>' + // Use index + 1 for simple counts
                                    '<td style="text-align: center;">' + batch.name +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch.version +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch.country +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch.status +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .level_names + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .timeline + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .completed_count + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .completed_dates.join('<br>') + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .completed_times.join('<br>') + '</td>' +
                                    '</tr>';
                                $('#batchDataTable tbody').append(row);
                            });

                            // Show the modal
                            $('#BatchCompleteModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            $(document).on('click', '#delayedBatchesData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.reports.delayed.batches.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.delayedBatchData) {
                            $('#delayedBatchesDataTable tbody').empty();
                            $('#delayedBatchesDateRange').text(
                                'From ' + data.startDate + ' to ' + data.endDate);
                            data.delayedBatchData.forEach(function(row, index) {
                                var tr = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) + '</td>' +
                                    '<td style="text-align: center;">' + row.batch_name + '</td>' +
                                    '<td style="text-align: center;">' + row.country + '</td>' +
                                    '<td style="text-align: center;">' + row.batch_status + '</td>' +
                                    '<td style="text-align: center;">' + row.level_name + '</td>' +
                                    '<td style="text-align: center;">' + row.canceled_date + '</td>' +
                                    '<td style="text-align: center;">' + row.canceled_time + '</td>' +
                                    '</tr>';
                                $('#delayedBatchesDataTable tbody').append(tr);
                            });
                            $('#DelayedBatchesModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            // Updated AJAX code for DemoCompletedData click event
            // -------------------------------------------------------------------- ::
            $(document).on('click', '#demoCompletedData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.demo.completed.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.demoData) {
                            // Clear previous data
                            $('#DemoDataTable tbody').empty();

                            // Set the date range in the h5 element
                            $('#totalStudentsDemoCompletedCount').text(
                                `From ${data.startDate} to ${data.endDate}`);

                            // Populate the table with new data
                            data.demoData.forEach(function(demo, index) {
                                var row = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) +
                                    '</td>' + // Use index + 1 for simple counts
                                    '<td style="text-align: center;">' + demo
                                    .first_name + '</td>' +
                                    '<td style="text-align: center;">' + demo.age +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo.mobile +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo.country +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .kids_time_zone + '</td>' +
                                    '<td style="text-align: center;">' + demo.status +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo.date +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo.time +
                                    '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .kids_date + '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .kids_time + '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .session_status + '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .session_date + '</td>' +
                                    '<td style="text-align: center;">' + demo
                                    .session_time + '</td>' +
                                    // '<td style="text-align: center;">' + demo.session_slot + '</td>' +
                                    // '<td style="text-align: center;">' + demo.session_coach_attendance_status + '</td>' +
                                    '</tr>';
                                $('#DemoDataTable tbody').append(row);
                            });

                            // Show the modal
                            $('#DemoCompleteModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            // Updated AJAX code for leaveTakenData click event
            // -------------------------------------------------------------------- ::
            $(document).on('click', '#leaveTakenData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.coach.leave.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.leaveData) {
                            // Clear previous data
                            $('#CoachLeaveDataTable tbody').empty();

                            // Set the date range in the h5 element
                            $('#totalCoachLeaveCount').text(
                                `From ${data.startDate} to ${data.endDate}`);

                            // Populate the table with new data
                            data.leaveData.forEach(function(leave, index) {
                                var row = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) +
                                    '</td>' + // Use index + 1 for simple counts
                                    '<td style="text-align: center;">' + leave
                                    .from_date + '</td>' +
                                    '<td style="text-align: center;">' + leave.to_date +
                                    '</td>' +
                                    '<td style="text-align: center;">' + leave
                                    .from_time + '</td>' +
                                    '<td style="text-align: center;">' + leave.to_time +
                                    '</td>' +
                                    '<td style="text-align: center;">' + leave.reason +
                                    '</td>' +
                                    '<td style="text-align: center;">' + leave.status +
                                    '</td>' +
                                    '</tr>';
                                $('#CoachLeaveDataTable tbody').append(row);
                            });

                            // Show the modal
                            $('#CoachLeaveModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            $(document).on('click', '#masterclassTakenData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.coach.masterclass.attendance.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.masterclassData) {
                            // Clear previous data
                            $('#masterClassAttendanceDataTable tbody').empty();

                            // Set the date range in the h5 element
                            $('#totalmasterClassAttendanceCount').text(
                                `From ${data.startDate} to ${data.endDate}`);

                            // Populate the table with new data
                            data.masterclassData.forEach(function(leave, index) {
                                var row = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) +
                                    '</td>' + // Use index + 1 for simple counts
                                    '<td style="text-align: center;">' + leave
                                    .coach_name + '</td>' +
                                    '<td style="text-align: center;">' + leave
                                    .masterclass_name + '</td>' +
                                    '<td style="text-align: center;">' + leave.date +
                                    '</td>' +
                                    '<td style="text-align: center;">' + leave.time +
                                    '</td>' +
                                    '<td style="text-align: center;">' + leave.status +
                                    '</td>' +
                                    '</tr>';
                                $('#masterClassAttendanceDataTable tbody').append(row);
                            });

                            // Show the modal
                            $('#masterClassAttendanceModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });

            $(document).on('click', '#coverupclassTakenData', function(e) {
                e.preventDefault();
                var coachId = $(this).data('coach-id');
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                $.ajax({
                    type: "GET",
                    url: '{{ route('admin.coverupclass.completed.data') }}',
                    data: {
                        coachId: coachId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(data) {
                        if (data.batchData) {
                            // Clear previous data
                            $('#CoverclassDataTable tbody').empty();

                            // Set the date range in the h5 element
                            $('#totalStudentsCoverclassesCompletedCount').text(
                                `From ${data.startDate} to ${data.endDate}`);


                            // Populate the table with new data
                            data.batchData.forEach(function(batch, index) {
                                var row = '<tr>' +
                                    '<td style="text-align: center;">' + (index + 1) +
                                    '</td>' + // Use index + 1 for simple counts
                                    '<td style="text-align: center;">' + batch.name +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch.country +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch.status +
                                    '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .level_names + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .timeline + '</td>' +
                                    '<td style="text-align: center;">' + batch
                                    .attendance_date + '</td>' +
                                    '</tr>';
                                $('#CoverclassDataTable tbody').append(row);
                            });

                            // Show the modal
                            $('#CoverClassCompleteModal').modal('show');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error('An unexpected error occurred. Please try again later.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                    }
                });
            });


            // Function to fetch schedule data of selected coach
            function fetchScheduleData(date, coachId) {
                let scheduleDataURLTemplate =
                `{{ route('admin.reports.getSchedule', ['coachId' => ':coachId']) }}`;
                let scheduleDataURL = scheduleDataURLTemplate.replace(':coachId', coachId);
                $.ajax({
                    url: scheduleDataURL,
                    type: 'GET',
                    data: {
                        date: date,
                    },
                    success: function(response) {
                        if (response.message && response.message ===
                            "Not enough data available to satisfy format") {
                            $('#schedule').html('<p>No schedule found</p>');
                        } else {
                            $('#schedule').html(response);
                        }
                    },
                    error: function(error) {
                        console.error("Error fetching schedule:", error);
                        //$('#schedule').html('<p>No schedule found. Please try again later.</p>');
                    }
                });
            }

            // Function to fetch and display calendar data for the selected coach
            function fetchCalendarData(coachId) {
                const calendarDataURL = `{{ route('admin.reports.calendar', ['coachId' => ':coachId']) }}`.replace(
                    ':coachId', coachId);

                $.ajax({
                    url: calendarDataURL,
                    type: 'GET',
                    data: {},
                    success: function(response) {
                        $('#report-calendar').html(response);
                    },
                    error: function(error) {
                        console.error("Error fetching calendar data:", error);
                    }
                });
            }

            // Function to fetch schedule data of selected coach
            function fetchAvailabilityData(date, coachId) {
                let scheduleDataURLTemplate =
                    `{{ route('admin.reports.getAvailability', ['coachId' => ':coachId']) }}`;
                let scheduleDataURL = scheduleDataURLTemplate.replace(':coachId', coachId);
                $.ajax({
                    url: scheduleDataURL,
                    type: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#coachavailability').html(response);
                    },
                    error: function(error) {
                        console.error("Error fetching coachavailability:", error);
                        $('#coachavailability').html(
                            '<p>Error fetching schedule. Please try again later.</p>');
                    }
                });
            }

            // Initialize the date range picker with default values
            var firstDayOfMonth = @json($firstDayOfMonth);
            var todayDate = @json($todayDate);
            $('#date_Range').daterangepicker({
                startDate: moment(firstDayOfMonth, 'YYYY-MM-DD'),
                endDate: moment(todayDate, 'YYYY-MM-DD'),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Listen for changes to the date range picker
            $('#date_Range').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');
                var selectedCoachId = $('#coach').val();
                fetchAndUpdateCounts(selectedCoachId, startDate, endDate);
            });

            // Get today's date in YYYY-MM-DD format and fetch schedule for the initially selected coach
            const today = istTime.toISOString().split('T')[0];
            const initialCoachId = $('#coach').val();
            fetchAndUpdateCounts(initialCoachId, firstDayOfMonth, todayDate);
            fetchScheduleData(today, initialCoachId);
            fetchCalendarData(initialCoachId);
            fetchAvailabilityData(today, initialCoachId);

            // Event listener for coach selection change
            $('#coach').change(function() {
                const selectedCoachId = $(this).val();
                const selectedDate = $('#date_Range').data('daterangepicker');
                const startDate = selectedDate.startDate.format('YYYY-MM-DD');
                const endDate = selectedDate.endDate.format('YYYY-MM-DD');
                fetchAndUpdateCounts(selectedCoachId, startDate, endDate);
                fetchScheduleData(today, selectedCoachId);
                fetchCalendarData(selectedCoachId);
                fetchAvailabilityData(today, selectedCoachId);
            });

            // Event listener for month input field change
            $('#month').on('change', function() {
                console.log('Month input field changed');
                const selectedDate = $(this).val().split('-');
                const selectedYear = selectedDate[0];
                const selectedMonth = selectedDate[1];
                const selectedCoachId = $('#coach').val();
                fetchAndUpdateCounts(selectedCoachId, selectedMonth, selectedYear);
            });

            // Listen for custom event 'dateSelected'
            document.addEventListener('dateSelected', function(e) {
                var selectedDate = e.detail.date;
                var selectedCoachId = $('#coach').val();
                fetchScheduleData(selectedDate, selectedCoachId);
                fetchAvailabilityData(selectedDate, selectedCoachId);
            });

            // -------------------------------------------------------------------- ::
            $(document).on('click', '.schedule-data-modal-btn', function(e) {
                e.preventDefault();
                var entityType = $(this).data('entity');
                var entityId = $(this).data('route-key');
                var url = $(this).data('url');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id: entityId,
                        type: entityType
                    },
                    success: function(response) {
                        $('#scheduleModal .modal-body').html(response);
                        $('#scheduleModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + status + " " + error);
                    }
                });
            });

            // -------------------------------------------------------------------- ::
            $(document).on('click', '.status-btn', function() {
                var scheduleId = $(this).data('id');
                var scheduleType = $(this).data('type');
                var scheduleDate = $(this).data('date');
                var selectedCoachId = $('#coach').val();
                const attendanceDataURL =
                    '{{ route('admin.reports.getAttendanceData', ['coachId' => ':coachId']) }}'.replace(
                        ':coachId', selectedCoachId);
                $.ajax({
                    url: attendanceDataURL,
                    type: 'GET',
                    data: {
                        id: scheduleId,
                        type: scheduleType,
                        coach_id: selectedCoachId,
                        date: scheduleDate
                    },
                    success: function(response) {
                        $('#AttendanceModal .modal-body').html(response);
                        $('#AttendanceModal').modal('show');
                    },
                    error: function(xhr) {
                        let errorMessage =
                            'There are some errors in Form. Please check your inputs'; // Default error message
                        if (xhr.responseJSON.error) { // If the response has an 'error' key
                            toastr.error(xhr.responseJSON.error, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        } else if (xhr.responseJSON
                            .errors) { // If the response has an 'errors' key
                            toastr.error(errorMessage, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '-error').html(value);
                            });
                            $('html, body').animate({
                                scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[
                                    0] + '-error').offset().top - 200
                            }, 500);
                        } else { // Fallback error message
                            toastr.error(errorMessage, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    }
                });
            });

            // -------------------------------------------------------------------- ::
            $(document).on('submit', '#coachDemoAttendanceForm', function(e) {
                e.preventDefault();
                $('div[id$="-error"]').empty();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage =
                            'There are some errors in Form. Please check your inputs'; // Default error message
                        if (xhr.responseJSON.error) { // If the response has an 'error' key
                            toastr.error(xhr.responseJSON.error, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        } else if (xhr.responseJSON
                            .errors) { // If the response has an 'errors' key
                            toastr.error(errorMessage, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                $('#' + key + '-error').html(value);
                            });
                            $('html, body').animate({
                                scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[
                                    0] + '-error').offset().top - 200
                            }, 500);
                        } else { // Fallback error message
                            toastr.error(errorMessage, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    }
                });
            });

            // -------------------------------------------------------------------- ::
            $(document).on('submit', '#coachBatchAttendanceForm', function(e) {
                e.preventDefault();
                $('div[id$="-error"]').empty();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                            var selectedDate = data.date;
                            var selectedCoachId = $('#coach').val();
                            fetchScheduleData(selectedDate, selectedCoachId);
                            fetchAvailabilityData(selectedDate, selectedCoachId);
                            $('#AttendanceModal').modal('hide');
                        } else {
                            toastr.error(data.message || 'There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            toastr.error(xhr.responseJSON.error ||
                                'There are some errors in the form. Please check your inputs.',
                                '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true,
                                });
                            $('#loaderImage').hide(); // Hide loader image
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#' + key + '-error').html(value);
                                });
                                $('html, body').animate({
                                    scrollTop: $('#' + Object.keys(xhr.responseJSON
                                        .errors)[0] + '-error').offset().top - 200
                                }, 500);
                            }
                        } else {
                            toastr.error(
                                'An unexpected error occurred. Please try again later.',
                                '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true,
                                });
                        }
                    }
                });
            });

            // -------------------------------------------------------------------- ::
            $('#downloadReport').submit(function(e) {
                e.preventDefault();
                var coachId = $('#coach').val();
                var url = '/admin/reports/' + coachId + '/download';
                var formData = new FormData(this);
                formData.append('coachId', coachId);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            window.open(response.pdf_url, '_blank');
                        } else {
                            toastr.error(response.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                    }
                });
            });

        });
    </script>
@endsection
