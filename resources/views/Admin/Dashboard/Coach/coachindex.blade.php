@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    <style>
        .fc-toolbar-chunk .fc-today-button {
            display: none !important;
        }
    </style>

    <!-- ------------------------------------------------------------------------------------------ :: -->

    <div class="container-fluid" style="--bs-gutter-x: 0px !important; padding: 0px !important;">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3 bg-light-info shadow-none position-relative overflow-hidden"
                    style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important;">
                    <div class="card-body px-4 py-3">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="fw-semibold mb-8">Welcome back {{ $coach->user->first_name }}
                                    {{ $coach->user->last_name }}! </h4>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a class="text-muted " href="">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Batch & Demo Overview</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-3">
                                <div class="text-center mb-n5">
                                    <img src="/backend/dist/images/backgrounds/welcome-bg.svg" alt=""
                                        class="img-fluid mb-n4" />
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-start mt-2 mb-4">
                                <div class="text-center mb-n5">
                                    <span>( IST ) &nbsp; {{ \Carbon\Carbon::parse($todayDate)->format('l, d F Y') }} -
                                        Schedules</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="schedule"
                    style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important;">

                </div>
                <div id="masterClass"
                    style=" box-shadow:  rgba(145, 158, 171, 0.2) 0px 0px 2px 0px, rgba(145, 158, 171, 0.12) 0px 12px 24px -4px !important;">

                </div>

            </div>
            <br>
            <div class="col-md-12 row">
                <div class="col-md-8">
                    <div class="card" style="--bs-card-spacer-y: 0px !important; --bs-card-spacer-x: 0px !important;">
                        <div class="card-body" id="report-calendar">

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="holiday-container">
                        <h4 class="holiday-title">Upcoming Holidays</h4>
                        <div class="holiday-list">
                            @foreach ($holidays as $key => $holiday)
                                <div class="holiday-item">
                                    <div class="holiday-number">{{ $key + 1 }})</div>
                                    <div class="holiday-details row">
                                        <div class="holiday-name">{{ $holiday->name }}
                                            <div class="holiday-date">
                                                ({{ toIndianDate($holiday->start_date) }}
                                                @if ($holiday->end_date)
                                                    /
                                                    {{ toIndianDate($holiday->end_date) }}
                                                @endif)
                                            </div>
                                        </div>
                                        <div class="holiday-description">
                                            <p>{{ Str::limit($holiday->description, 30) }}
                                                @if (strlen($holiday->description) > 30)
                                                    <a href="javascript:void(0);" class="read-more" data-bs-toggle="modal"
                                                        data-bs-target="#holidayModal{{ $holiday->id }}">Read More</a>
                                                @endif
                                            </p>

                                        </div>

                                    </div>
                                </div>

                                <!-- Modal for Holiday Description -->
                                <div class="modal fade" id="holidayModal{{ $holiday->id }}" tabindex="-1"
                                    aria-labelledby="holidayModalLabel{{ $holiday->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="holidayModalLabel{{ $holiday->id }}">
                                                    {{ $holiday->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ $holiday->description }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Attendance Modal -->
    <div class="modal fade" id="HomeworkAttendanceModal" tabindex="-1" role="dialog"
        aria-labelledby="HomeworkAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="HomeworkAttendanceModalLabel">Add Homework</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" style="margin-bottom: 10px !important;">
                                <div class="border-top">
                                    <div class="row gx-0">
                                        <div class="col-md-3 border-end">
                                            <div class="p-4 py-3 py-md-4">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <h6 class="mb-0">Batch:</h6>
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 class="mb-0">ABC Batch Name</h6>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 border-end">
                                            <div class="p-4 py-3 py-md-4">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <h6 class="mb-0">Level:</h6>
                                                    </div>
                                                    <div class="col-7">
                                                        <h6 class="mb-0"> Beginner</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 border-end">
                                            <div class="p-4 py-3 py-md-4">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <h6 class="mb-0">Schedule :</h6>
                                                    </div>
                                                    <div class="col-10">
                                                        <h6 class="mb-0">( 10:00 AM - 11:00 AM ) &nbsp;
                                                            01-Jan-2023 &nbsp; - &nbsp; 31-Dec-2023</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 p-2">
                                <div class="col-6">
                                    <input type="text" class="form-control" name="chapter_name" id="chapter_name"
                                        value="{{ !empty($todaysCoachAttendance->chapter_name) ? $todaysCoachAttendance->chapter_name : '' }}"
                                        placeholder="Chapter Name" />
                                    <div id="chapters_name-error" style="color:red"></div>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" name="homework_link" id="homework_link"
                                        value="{{ !empty($todaysCoachAttendance->homework_link) ? $todaysCoachAttendance->homework_link : '' }}"
                                        placeholder="Homework Link" />
                                    <div id="homeworsk_link-error" style="color:red"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer border-1 text-center" style="justify-content: center;">
                    <div class="row mt-3">
                        <div class="col-12 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary">
                                Submit Homework
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ------------------------------------------------------------------------- :: -->

    <!--  Attendance Modal -->
    <div class="modal fade" id="AttendanceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="AttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="AttendanceModalLabel">Mark Attendance (Coach & Student)</h5>
                    <button type="button" class="btn-close attendanceClose" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="AttendanceData">
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn bg-light-secondary attendanceClose"
                        data-bs-dismiss="modal">Close</button>
                    <!-- <button type="submit" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------------------------------------------------------------------------------ :: -->


    <script src="/backend/dist/libs/fullcalendar/index.global.min.js"></script>
    <script src="/backend/dist/js/apps/calendar-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data-10-year-range.min.js">
    </script>

    <script>
        $(document).ready(function() {
            // $('#HomeworkAttendanceModal').modal('show');

            getMasterClass();
            @if (isset($coach))
                const coachId = '{{ $coach->id }}';
                const calendarDataURL = '{{ route('admin.dashboard.calendar', ['coachId' => $coach->id]) }}';
                // Function to fetch calendar data
                $.ajax({
                    url: calendarDataURL,
                    type: 'GET',
                    success: function(response) {
                        $('#report-calendar').html(response);
                    },
                    error: function(error) {
                        console.error("Error fetching calendar data:", error);
                    }
                });
                // Get today's date in YYYY-MM-DD format
                const today = moment.tz("Asia/Kolkata").format('YYYY-MM-DD');
                fetchScheduleData(today);
            @endif

            function initDelayedBatchPopup() {
                var $modal = $('#delayedBatchNoticeModal');
                if (!$modal.length) {
                    return;
                }
                $modal.appendTo('body');

                $modal.find('#delayedBatchConfirmBtn').off('click.delayedBatch').on('click.delayedBatch', function() {
                    $modal.modal('hide');
                });

                $modal.find('#delayedBatchDontStartBtn').off('click.delayedBatch').on('click.delayedBatch', function() {
                    $modal.modal('hide');
                    $('#AttendanceModal').modal('hide');
                });

                $modal.on('hidden.bs.modal', function() {
                    $(this).remove();
                });

                setTimeout(function() {
                    $modal.modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                }, 350);
            }

            // -------------------------------------------------------------------- ::
            $(document).on('click', '.status-btn', function() {
                var scheduleId = $(this).data('id');
                var scheduleType = $(this).data('type');
                var coachId = '{{ $coach->id }}';
                var dataBtn = $(this).data('btn');

                const attendanceDataURL =
                    '{{ route('admin.dashboard.getAttendanceData', ['coachId' => $coach->id]) }}';
                $.ajax({
                    url: attendanceDataURL,
                    type: 'GET',
                    data: {
                        id: scheduleId,
                        type: scheduleType,
                    },
                    success: function(response) {
                        $('#AttendanceModal .modal-body').html(response);
                        $('#AttendanceModal').modal('show');
                        initDelayedBatchPopup();
                        if (dataBtn === 'statusBtn') {
                            $('.bottomDiv').hide();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            function showAttendanceModal(batch) {
                // $('#AttendanceModalLabel').text('Mark Attendance: ' + batch.name);
                $('#AttendanceData').html('<p>Loading attendance data...</p>');

                const attendanceDataURL =
                    '{{ route('admin.dashboard.getAttendanceData', ['coachId' => $coach->id]) }}';

                $.ajax({
                    url: attendanceDataURL,
                    type: 'GET',
                    data: {
                        id: batch.id,
                        type: batch.type || 'Batch',
                        attendance_id: batch.attendance_id,
                        attendance_date: batch.attendance_date,
                        attendance_time: batch.attendance_time
                    },
                    success: function(response) {
                        $('#AttendanceData').html(response);
                        $('#AttendanceModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).modal('show');
                        initDelayedBatchPopup();
                        $('.attendanceClose').hide();
                    },
                    error: function(error) {
                        console.error('Error loading attendance data:', error);
                        $('#AttendanceData').html('<p class="text-danger">Failed to load data.</p>');
                        $('#AttendanceModal').modal('show');
                    }
                });
            }

            $(document).on('click', '.start-url', function(e) {
                e.preventDefault();

                let schedule = $(this).data('schedule');
                let type = $(this).data('type');
                let startUrl = schedule.start_url;

                $.ajax({
                    url: "/admin/coach/get-unmarked-attendance",
                    method: "GET",
                    success: function(response) {

                        if (response.attendance) {

                            // DEMO CASE
                            // if (response.type === "Demo") {

                            //     let demoData = {
                            //         id: response.demo.id,
                            //         type: "Demo",
                            //         attendance_id: response.attendanceId,
                            //         attendance_date: response.attendanceDate,
                            //         attendance_time: response.attendanceTime
                            //     };

                            //     showAttendanceModal(demoData);
                            //     return;
                            // }

                            // BATCH / COVERUP / YESTERDAY BATCH
                            let batch = {
                                id: response.batch.id,
                                type: response.type,
                                attendance_id: response.attendanceId,
                                attendance_date: response.attendanceDate,
                                attendance_time: response.attendanceTime
                            };

                            showAttendanceModal(batch);
                            return;
                        }

                        $.ajax({
                            url: `/admin/dashboard/${schedule.id}/pre-batch-attendance`,
                            method: 'POST',
                            data: {
                                batch_id: schedule.id,
                                demosession_id: schedule.id,
                                type: type,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {

                                toastr.success(response.message || 'Success', '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 2000,
                                    closeButton: true,
                                });

                                if (response.status === 'success') {
                                    setTimeout(function() {
                                        window.open(startUrl, '_blank');
                                    }, 1000);
                                }
                            },
                            error: function(xhr) {
                                let message = xhr.responseJSON?.error ||
                                    'There is some error!!';

                                toastr.error(message, '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 3000,
                                    closeButton: true,
                                });
                            }
                        });
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
                            $('.attendanceClose').hide();

                            setTimeout(function() {
                                window.open(data.start_url, '_blank');
                            }, 100);


                            $('#AttendanceModal').modal('hide');

                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);


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
        });

        // -------------------------------------------------------------------- ::
        function fetchScheduleData(date) {
            const scheduleDataURL = '{{ route('admin.dashboard.getSchedule', ['coachId' => $coach->id ?? '']) }}';
            $.ajax({
                url: scheduleDataURL,
                type: 'GET',
                data: {
                    date: date
                },
                success: function(response) {
                    if (response.message === "Viewing schedules other than today is not allowed.") {
                        toastr.warning(response.message);
                    } else {
                        $('#schedule').html(response);

                    }
                },
                error: function(xhr, status, error) {
                    const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : 'Error fetching schedule. Please try again later.';
                    toastr.error(errorMessage);
                    console.error("Error fetching schedule:", error);
                }
            });
        }

        document.addEventListener('dateSelected', function(e) {
            var selectedDate = e.detail.date;
            fetchScheduleData(selectedDate);
        });

        function getMasterClass() {
            $.ajax({
                url: '{!! route('admin.dashboard.get.coach.master.class') !!}', // Replace with your endpoint URL
                type: 'POST',
                data: {
                    coachId: '{{ $coach->id }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#masterClass').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
