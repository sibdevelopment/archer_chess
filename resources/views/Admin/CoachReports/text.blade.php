
@extends('layouts.admin')
@section('title') Report @endsection
@section('content')

@php
$user = auth()->user();
$role = $user->getRoleNames()->toArray();
$coachId = null;
if (in_array("Coach", $role) && $user->coach) {
$coachId = $user->coach->id;
}
$isCoach = in_array("Coach", $role);
$isAdminOrSuperAdmin = in_array("Admin", $role) || in_array("SuperAdmin", $role);
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
                        @if($isCoach)
                        <div class="col-3 d-flex justify-content-start">
                            <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example" disabled>
                                <option value="">Select Coach</option>
                                @foreach($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ isset($coachId) && $coachId==$coach->id ? 'selected'
                                    : '' }}>
                                    {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-3 d-flex justify-content-start">
                            <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example">
                                <option value="">Select Coach</option>
                                @foreach($coaches as $index => $coach)
                                <option value="{{ $coach->id }}" {{ (isset($coachId) && $coachId==$coach->id) ||
                                    (!isset($coachId) && $index == 0) ? 'selected' : '' }}>
                                    {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-3 d-flex justify-content-start">
                        </div>
                        <div class="col-6">
                            <form action="" method="POST" enctype="multipart/form-data" id="downloadReport">
                                @csrf
                                <div class="row">
                                    {{--
                                    <div class="col-8 d-flex justify-content-end">
                                        <div class="input-group">
                                            <input name="dateRange" id="dateRange" type="text"
                                                class="form-control daterange" />
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar fs-5"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4 d-flex justify-content-end">
                                        <input type="date" name="fromDate" class="form-control" id="fromDate"
                                            placeholder="From Date" value="{{ $firstDayOfMonth }}">
                                    </div>
                                    <div class="col-4 d-flex justify-content-end">
                                        <input type="date" name="toDate" class="form-control" id="toDate"
                                            placeholder="To Date" value="{{ $todayDate }}">
                                    </div>
                                    <div class="col-4 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Download Report</button>
                                    </div>
                                    --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------- :: -->
    <div class="row">
        <div class="col-12">
            <div class="row mb-2 mt-2">
                <div class="col-3 d-flex justify-content-end">
                    <input type="month" name="month" id="month" class="form-control form-control-sm me-2 ms-4"
                        aria-label="Select Month" style="border-color: #00000057 !important;">
                </div>
                <div class="col-9">
                    <h5 class="card-title fw-semibold"></h5>
                </div>
            </div>
            <div class="card w-100 position-relative overflow-hidden mb-3">
                <div class="card-header px-4 py-3 border-bottom" id="getcounts">

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ------------------------------------------------------------------------- :: -->
<section>
    <div class="row">
        <div class="col-md-6">
            <div id="schedule" class="rounded"
                style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important;">
            </div>

        </div>
        <!-- ------------------------------------------------------------------------- :: -->
        <div class="col-md-6">
            <div class="card" style="--bs-card-spacer-y: 0px !important; --bs-card-spacer-x: 0px !important;">
                <div class="card-body" id="report-calendar">

                </div>
            </div>
        </div>
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
                <h5>Total Students in Batches: <span id="totalStudentsBatchesCount"></span></h5>
                <table
                    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg"
                    style="border-radius: 8px;" id="studentsPerCountryTable">
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Total Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be appended here by AJAX -->
                    </tbody>
                </table>
                <h5>Students Without Country</h5>
                <table
                    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg"
                    style="border-radius: 8px;" id="studentsWithoutCountryTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be appended here by AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-1">
                <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="/backend/dist/libs/fullcalendar/index.global.min.js"></script>
<script src="/backend/dist/js/apps/calendar-init.js"></script>
<script>
    $(document).ready(function () {
        let now = new Date("{{ $todayDate }}");
        let utcOffset = now.getTimezoneOffset() * 60000;
        let istOffset = 5.5 * 60 * 60000;
        let istTime = new Date(now.getTime() + utcOffset + istOffset);
        let year = istTime.getFullYear();
        let month = ('0' + (istTime.getMonth() + 1)).slice(-2);
        $('#month').val(year + '-' + month);

        // Function to fetch and update counts based on the selected coach and date
        function fetchAndUpdateCounts(coachId, month, year) {
            console.log(`Fetching counts for coachId: ${coachId}, month: ${month}, year: ${year}`);
            let getCountsUrl = `{{ route('admin.reports.get.counts', ['coachId' => ':coachId']) }}`.replace(':coachId', coachId);
            $.ajax({
                url: getCountsUrl,
                type: 'GET',
                data: {
                    month: month,
                    year: year
                },
                success: function (response) {
                    console.log('Counts fetched successfully:', response);
                    $('#getcounts').html(response);
                },
                error: function (error) {
                    console.log('Error fetching counts:', error);
                }
            });
        }

        // Function to fetch schedule data of selected coach
        function fetchScheduleData(date, coachId) {
            let scheduleDataURLTemplate = `{{ route('admin.reports.getSchedule', ['coachId' => ':coachId']) }}`;
            let scheduleDataURL = scheduleDataURLTemplate.replace(':coachId', coachId);
            $.ajax({
                url: scheduleDataURL,
                type: 'GET',
                data: { date: date },
                success: function (response) {
                    if (response.message && response.message === "Not enough data available to satisfy format") {
                        $('#schedule').html('<p>No schedule found</p>');
                    } else {
                        $('#schedule').html(response);
                    }
                },
                error: function (error) {
                    console.error("Error fetching schedule:", error);
                    //$('#schedule').html('<p>No schedule found. Please try again later.</p>');
                }
            });
        }

        // Function to fetch and display calendar data for the selected coach
        function fetchCalendarData(coachId) {
            const calendarDataURL = `{{ route("admin.reports.calendar", ["coachId" => ":coachId"]) }}`.replace(':coachId', coachId);
            $.ajax({
                url: calendarDataURL,
                type: 'GET',
                success: function (response) {
                    $('#report-calendar').html(response);
                },
                error: function (error) {
                    console.error("Error fetching calendar data:", error);
                }
            });
        }

        // Function to fetch schedule data of selected coach
        function fetchAvailabilityData(date, coachId) {
            let scheduleDataURLTemplate = `{{ route('admin.reports.getAvailability', ['coachId' => ':coachId']) }}`;
            let scheduleDataURL = scheduleDataURLTemplate.replace(':coachId', coachId);
            $.ajax({
                url: scheduleDataURL,
                type: 'GET',
                data: { date: date },
                success: function (response) {
                    $('#coachavailability').html(response);
                },
                error: function (error) {
                    console.error("Error fetching coachavailability:", error);
                    $('#coachavailability').html('<p>Error fetching schedule. Please try again later.</p>');
                }
            });
        }

        // Listen for custom event 'dateSelected'
        document.addEventListener('dateSelected', function (e) {
            var selectedDate = e.detail.date;
            var selectedCoachId = $('#coach').val();
            fetchScheduleData(selectedDate, selectedCoachId);
            fetchAvailabilityData(selectedDate, selectedCoachId);
        });

        // Listen for custom event 'dateSelected'
        document.addEventListener('dateSelected', function (e) {
            var selectedDate = e.detail.date;
            var selectedCoachId = $('#coach').val();
            fetchScheduleData(selectedDate, selectedCoachId);
            fetchAvailabilityData(selectedDate, selectedCoachId);
        });

        // Get today's date in YYYY-MM-DD format and fetch schedule for the initially selected coach
        const today = istTime.toISOString().split('T')[0];
        const initialCoachId = $('#coach').val();
        fetchAndUpdateCounts(initialCoachId, month, year);
        fetchScheduleData(today, initialCoachId);
        fetchCalendarData(initialCoachId);
        fetchAvailabilityData(today, initialCoachId);

        // Event listener for coach selection change
        $('#coach').change(function () {
            const selectedCoachId = $(this).val();
            const selectedDate = $('#month').val().split('-');
            const selectedYear = selectedDate[0];
            const selectedMonth = selectedDate[1];
            fetchAndUpdateCounts(selectedCoachId, selectedMonth, selectedYear);
        });

        // Event listener for month input field change
        $('#month').on('change', function () {
            console.log('Month input field changed');
            const selectedDate = $(this).val().split('-');
            const selectedYear = selectedDate[0];
            const selectedMonth = selectedDate[1];
            const selectedCoachId = $('#coach').val();
            fetchAndUpdateCounts(selectedCoachId, selectedMonth, selectedYear);
        });

        // Event listener for batchStudent click event
        $(document).on('click', '#batchStudent', function (e) {
            e.preventDefault();
            const selectedCoachId = $(this).data('coach-id');
            const selectedDate = $('#month').val().split('-');
            const selectedYear = selectedDate[0];
            const selectedMonth = selectedDate[1];
            handleBatchStudentClick(selectedCoachId, selectedMonth, selectedYear);
        });


        // -------------------------------------------------------------------- ::
        $('#downloadReport').submit(function (e) {
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
                success: function (response) {
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
                error: function (xhr) {
                    toastr.error(xhr.responseJSON.message);
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('#' + key + '-error').html(value);
                    });
                }
            });
        });

        // -------------------------------------------------------------------- ::
        $(document).on('click', '.schedule-data-modal-btn', function (e) {
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
                success: function (response) {
                    $('#scheduleModal .modal-body').html(response);
                    $('#scheduleModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + status + " " + error);
                }
            });
        });

        // -------------------------------------------------------------------- ::
        $(document).on('click', '.status-btn', function () {
            var scheduleId = $(this).data('id');
            var scheduleType = $(this).data('type');
            var scheduleDate = $(this).data('date');
            var selectedCoachId = $('#coach').val();
            const attendanceDataURL = '{{ route("admin.reports.getAttendanceData", ["coachId" => ":coachId"]) }}'.replace(':coachId', selectedCoachId);
            $.ajax({
                url: attendanceDataURL,
                type: 'GET',
                data: {
                    id: scheduleId,
                    type: scheduleType,
                    coach_id: selectedCoachId,
                    date: scheduleDate
                },
                success: function (response) {
                    $('#AttendanceModal .modal-body').html(response);
                    $('#AttendanceModal').modal('show');
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        // -------------------------------------------------------------------- ::
        $(document).on('submit', '#coachDemoAttendanceForm', function (e) {
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
                success: function (data) {
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
                error: function (xhr) {
                    let errorMessage = 'There are some errors in Form. Please check your inputs'; // Default error message
                    if (xhr.responseJSON.error) { // If the response has an 'error' key
                        toastr.error(xhr.responseJSON.error, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    } else if (xhr.responseJSON.errors) { // If the response has an 'errors' key
                        toastr.error(errorMessage, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error').offset().top - 200
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
        $(document).on('submit', '#coachBatchAttendanceForm', function (e) {
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
                success: function (data) {
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
                error: function (xhr) {
                    if (xhr.status === 422) {
                        toastr.error(xhr.responseJSON.error || 'There are some errors in the form. Please check your inputs.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                $('#' + key + '-error').html(value);
                            });
                            $('html, body').animate({
                                scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error').offset().top - 200
                            }, 500);
                        }
                    } else {
                        toastr.error('An unexpected error occurred. Please try again later.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                }
            });
        });


        // Updated AJAX code for batchStudent click event
        // -------------------------------------------------------------------- ::
        function handleBatchStudentClick(coachId, month, year) {
            $.ajax({
                type: "GET",
                url: '{{ route("admin.batchstudent.countrydata") }}',
                data: {
                    coachId: coachId,
                    month: month,
                    year: year
                },
                success: function (data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });

                        // Update total students count
                        $('#totalStudentsBatchesCount').text(data.totalStudentsBatchesCount);

                        // Clear existing table rows
                        $('#studentsPerCountryTable tbody').empty();

                        // Append new rows to the table
                        data.studentsPerCountry.forEach(function (item) {
                            var row = '<tr><td>' + item.country + '</td><td>' + item.total + '</td></tr>';
                            $('#studentsPerCountryTable tbody').append(row);
                        });

                        // Clear existing rows for students without country
                        $('#studentsWithoutCountryTable tbody').empty();

                        // Append new rows for students without country
                        if (data.studentsWithoutCountry && data.studentsWithoutCountry.length > 0) {
                            data.studentsWithoutCountry.forEach(function (student) {
                                var row = '<tr><td>' + student.student_id + '</td><td>' + student.first_name + '</td></tr>';
                                $('#studentsWithoutCountryTable tbody').append(row);
                            });
                        } else {
                            var row = '<tr><td colspan="2">No data found</td></tr>';
                            $('#studentsWithoutCountryTable tbody').append(row);
                        }

                        // Show the modal
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
                error: function (xhr) {
                    toastr.error('An unexpected error occurred. Please try again later.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            });
        }



    });
</script>


@endsection