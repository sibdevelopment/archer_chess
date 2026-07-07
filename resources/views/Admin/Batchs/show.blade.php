<style>
    ul.border-top-bold {
        border-top: 1px solid #000;
        /* Makes the border top bold */
    }
</style>


<!-- -------------------------------------------------------------------------------------------- :: -->
<!-- -------------------------------------------------------------------------------------------- :: -->

<div class="row">
    <div class="col-12">
        <div class="card" style="margin-bottom: 10px !important;">
            <div class="border-top">
                <div class="row gx-0">
                    <div class="col-md-4 border-end">
                        <div class="p-2 py-3 py-md-4">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="mb-0">Batch:</h6>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0">{{ $batch->name }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 border-end">
                        <div class="p-2 py-3 py-md-4">
                            <div class="row">
                                <div class="col-5">
                                    <h6 class="mb-0">Coach: </h6>
                                </div>
                                <div class="col-7">
                                    <h6 class="mb-0">
                                        {{ $batch->coach->user->first_name }}{{ $batch->coach->user->last_name }} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="p-2 py-3 py-md-4">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="mb-0">Status: </h6>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0">{{ ucwords(strtolower($batch->status)) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- -------------------------------------------------------------------------------------------- :: -->
<!-- -------------------------------------------------------------------------------------------- :: -->

@if ($batchSchedules->isNotEmpty())
    <div class="card w-100 mb-2">
        <div class="card-body p-3">
            <div class="col-md-12 mt-2">
                <h5 class="fw-semibold mb-2">
                    <i class="ti ti-calendar fs-5"></i> &nbsp; &nbsp;Batch Schedule :
                </h5>
                <table
                    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <!-- <th scope="col">Batch</th> -->
                            <th scope="col">Weekday</th>
                            <th scope="col">From Time</th>
                            <th scope="col">To Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batchSchedules as $index => $schedule)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <!-- <td>{{ $schedule->batch->name }}</td> -->
                                <td>{{ $schedule->weekday }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->from_time)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->to_time)->format('h:i A') }}</td>
                                <td>{{ ucwords(strtolower($schedule->status)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif


<!-- -------------------------------------------------------------------------------------------- :: -->
<!-- -------------------------------------------------------------------------------------------- :: -->

<div class="row">
    @if (!empty($batchDetails['schedules']))
        <div class="col-md-12 col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-3">
                    <div class="mb-4">
                        <h5 class="card-title fw-semibold"><i class="ti ti-calendar fs-5"></i> &nbsp;
                            &nbsp;Approximate Session Occurrences</h5>
                        <p class="card-subtitle">
                            Total days the batch will happen: <strong>{{ $batchDetails['totalDays'] }}</strong> </p>
                    </div>
                    <ul class="timeline-widget mb-0 position-relative">

                        <li class="timeline-item d-flex position-relative overflow-hidden">
                            <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                <span class="timeline-badge-border d-block flex-shrink-0"></span>
                            </div>
                            <div class="timeline-desc fs-3 text-dark mt-n1"> Start:
                                <strong>{{ date('d F Y', strtotime($batchDetails['startDate'])) }}</strong>
                                End:
                                <strong>{{ date('d F Y', strtotime($batchDetails['endDate'])) }}</strong>
                            </div>
                        </li>
                        @foreach ($batchDetails['schedules'] as $index => $schedule)
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span
                                        class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                                    @if ($index < count($batchDetails['schedules']) - 1)
                                        <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                    @endif
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n1"> The batch on
                                    <strong>{{ $schedule['day'] }}</strong> will happen
                                    <strong>{{ $schedule['count'] }}</strong>
                                    times under this start and end date.
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>


<div class="card w-100 mb-2">
    <div class="card-body p-3">
        @if ($studentBatches->isNotEmpty())
            @php
                $uniqueStudentCounts = $studentBatches->unique('student_id')->count();
                $activeStudentCounts = $studentBatches->where('status', 'ACTIVE')->unique('student_id')->count();
            @endphp
            <div class="col-md-12 mt-2">
                <div class="row">
                    <!-- Left Section: Basic Information -->
                    <div class="col-12 col-md-7 mb-4 mb-md-0">
                        <div class="card shadow-sm rounded-lg p-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="ti ti-calendar fs-5 text-primary me-2"></i>
                                <h5 class="fw-semibold mb-0">Active Student Batches Record</h5>
                            </div>
                            <div class="d-flex flex-wrap gap-4">
                                <!-- Start Date -->
                                <div class="d-flex align-items-center">
                                    <b class="text-muted me-2">Start Date:</b>
                                    <span class="text-dark">{{ toIndianDate($batch->start_date) }}</span>
                                </div>
                                <!-- End Date -->
                                <div class="d-flex align-items-center">
                                    <b class="text-muted me-2">End Date:</b>
                                    <span class="text-dark">{{ toIndianDate($batch->end_date) }}</span>
                                </div>
                                <!-- Level -->
                                <div class="d-flex align-items-center">
                                    <b class="text-muted me-2">Level:</b>
                                    <span class="text-dark">{{ $batch->level->name }}</span>
                                </div>
                                <!-- Unique Students -->
                                {{-- <div class="d-flex align-items-center">
                                    <b class="text-muted me-2">Unique Students:</b>
                                    <span class="text-dark">{{ $uniqueStudentCounts }}</span>
                                </div>
                                <!-- Unique Students -->
                                <div class="d-flex align-items-center">
                                    <b class="text-muted me-2">Active Students:</b>
                                    <span class="text-dark">{{ $uniqueStudentCounts }}</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Total Sessions Completed -->
                    <div class="col-12 col-md-5">
                        <div class="card shadow-sm rounded-lg p-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="ti ti-check fs-5 text-success me-2"></i>
                                <h6 class="fw-semibold mb-0">Coach Session Overview</h6>
                            </div>
                            <div class="d-flex flex-column">
                                <!-- Total Sessions Completed -->
                                <div class="d-flex align-items-center gap-2">
                                    @if ($totalSessionsCompleted > 0)
                                        <i class="ti ti-check-circle text-success fs-6"></i>
                                        <span class="fs-5 fw-semibold text-dark">Total Sessions Completed:
                                            <strong>
                                                @if ($batch->level_id == 2)
                                                    {{ $totalSessionsCompleted + 10 }}
                                                @else
                                                    {{ $totalSessionsCompleted }}
                                                @endif
                                            </strong>
                                            <a href="#" data-id="{{ $batch->id }}"
                                                class="text-dark ms-2 view-session">
                                                <i class="ti ti-eye fs-5"></i>
                                                <span class="text-dark">View</span>
                                            </a>
                                        </span>
                                    @else
                                        <span class="fs-5 fw-semibold text-muted">No sessions completed yet.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table
                    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg mt-2">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Student </th>
                            <th scope="col">Status </th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Action</th>
                            {{-- <th scope="col">Created At</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentBatches as $index => $studentBatch)
                            <tr id="attendance-row-{{ $studentBatch->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $studentBatch->student->first_name }} {{ $studentBatch->student->last_name }}
                                    ({{ $studentBatch->student->student_id }})
                                    @if ($studentBatch->student->status == 'STANDBY')
                                        <span class="badge badge-warning" style="color:orange;">(Standby)</span>
                                    @elseif ($studentBatch->student->status == 'INACTIVE')
                                        <span class="badge badge-danger" style="color:red;">(Inactive)</span>
                                    @elseif ($studentBatch->student->status == 'ACTIVE')
                                        <span class="badge badge-success" style="color:green;">(Active)</span>
                                    @elseif ($studentBatch->student->status == 'FEESDUE')
                                        <span class="badge badge-info" style="color:blue;">(Fees Due)</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($studentBatch->status == 'ACTIVE')
                                        <span class="badge badge-success" style="color:green;">Active</span>
                                    @endif
                                    @if ($studentBatch->status == 'INACTIVE')
                                        <span class="badge badge-danger" style="color:red;">Inactive</span>
                                    @endif

                                    @if ($studentBatch->is_fees_due == 1)
                                        <span class="badge badge-danger" style="color:red;">(Fees Due)</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}</td>
                                <td>
                                    @if ($studentBatch->end_time)
                                        {{ \Carbon\Carbon::parse($studentBatch->end_time)->format('h:i A') }}
                                    @else
                                        <span class="badge badge-danger" style="color:red;">NA</span>
                                    @endif
                                {{-- <td>{{ \Carbon\Carbon::parse($studentBatch->created_at)->format('d-M-Y') }}</td> --}}
                                <td>
                                    <div class="action-btn">
                                        <a href="#" data-id="{{ $studentBatch['id'] }}" class="text-dark delete ms-2">
                                            <i class="ti ti-trash fs-5"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@php
    $oldBatches = \App\Models\Batch::where('parent_id', $batch->parent_id)->where('id', '!=', $batch->id)->orderBy('id', 'desc')->get();
@endphp

@if ($oldBatches->isNotEmpty())
    @foreach ($oldBatches as $batch)
    <div class="card w-100 mb-2">
        <div class="card-body p-3">
            @if ($batch->studentBatches->isNotEmpty())
                <div class="col-md-12 mt-2">
                    <div class="row">
                        <!-- Left Section: Basic Information -->
                        <div class="col-12 col-md-7 mb-4 mb-md-0">
                            <div class="card shadow-sm rounded-lg p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-calendar fs-5 text-primary me-2"></i>
                                    <h5 class="fw-semibold mb-0" style="color: blue;">Old Student Batches Record</h5>
                                </div>
                                <div class="d-flex flex-wrap gap-4">
                                    <!-- Start Date -->
                                    <div class="d-flex align-items-center">
                                        <b class="text-muted me-2">Start Date:</b>
                                        <span class="text-dark">{{ toIndianDate($batch->start_date) }}</span>
                                    </div>
                                    <!-- End Date -->
                                    <div class="d-flex align-items-center">
                                        <b class="text-muted me-2">End Date:</b>
                                        <span class="text-dark">{{ toIndianDate($batch->end_date) }}</span>
                                    </div>
                                    <!-- Level -->
                                    <div class="d-flex align-items-center">
                                        <b class="text-muted me-2">Level:</b>
                                        <span class="text-dark">{{ $batch->level->name }}</span>
                                    </div>
                                    <!-- Unique Students -->
                                    {{-- <div class="d-flex align-items-center">
                                        <b class="text-muted me-2">Unique Students:</b>
                                        <span class="text-dark">{{ $uniqueStudentCounts }}</span>
                                    </div>
                                    <!-- Unique Students -->
                                    <div class="d-flex align-items-center">
                                        <b class="text-muted me-2">Active Students:</b>
                                        <span class="text-dark">{{ $uniqueStudentCounts }}</span>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        @php
                            $CompletedSession = app\Models\CoachAttendance::where('batch_id', $batch->id)
                                ->where('status', 'COMPLETED')
                                ->orderByDesc('id')
                                ->count();

                            $FinalSessionsCompleted = $CompletedSession ? $CompletedSession : 0;
                        @endphp

                        <!-- Right Section: Total Sessions Completed -->
                        <div class="col-12 col-md-5">
                            <div class="card shadow-sm rounded-lg p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-check fs-5 text-success me-2"></i>
                                    <h6 class="fw-semibold mb-0">Coach Session Overview</h6>
                                </div>
                                <div class="d-flex flex-column">
                                    <!-- Total Sessions Completed -->
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($FinalSessionsCompleted > 0)
                                            <i class="ti ti-check-circle text-success fs-6"></i>
                                            <span class="fs-5 fw-semibold text-dark">Total Sessions Completed:
                                                <strong>{{ $FinalSessionsCompleted }}</strong>
                                            </span>
                                            <a href="#" data-id="{{ $batch->id }}"
                                                class="text-dark ms-2 view-session">
                                                <i class="ti ti-eye fs-5"></i>
                                                <span class="text-dark">View</span>
                                            </a>
                                        @else
                                            <span class="fs-5 fw-semibold text-muted">No sessions completed yet.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table
                        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg mt-2">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student </th>
                                <th scope="col">Status </th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batch->studentBatches as $index => $studentBatch)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $studentBatch->student->first_name }} {{ $studentBatch->student->last_name }}
                                        ({{ $studentBatch->student->student_id }})
                                        @if ($studentBatch->student->status == 'STANDBY')
                                            <span class="badge badge-warning" style="color:orange;">(Standby)</span>
                                        @elseif ($studentBatch->student->status == 'INACTIVE')
                                            <span class="badge badge-danger" style="color:red;">(Inactive)</span>
                                        @elseif ($studentBatch->student->status == 'ACTIVE')
                                            <span class="badge badge-success" style="color:green;">(Active)</span>
                                        @elseif ($studentBatch->student->status == 'FEESDUE')
                                            <span class="badge badge-info" style="color:blue;">(Fees Due)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($studentBatch->status == 'ACTIVE')
                                            <span class="badge badge-success" style="color:green;">Active</span>
                                        @endif
                                        @if ($studentBatch->status == 'INACTIVE')
                                            <span class="badge badge-danger" style="color:red;">Inactive</span>
                                        @endif

                                        @if ($studentBatch->is_fees_due == 1)
                                            <span class="badge badge-danger" style="color:red;">(Fees Due)</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endforeach
@endif

<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Batch Attendance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>


<script>
    //on click of view-session call ajxax
    $(document).on('click', '.view-session', function(e) {
        e.preventDefault();
        var batchId = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.batchs.attendance') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: batchId
            },
            success: function(data) {
                $('#attendanceModal .modal-body').html(data);
                $('#attendanceModal').modal('show');
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    swal("Error", xhr.responseJSON.message, "error");
                } else {
                    swal("Error",
                        "An error occurred while fetching the attendance record.",
                        "error");
                }
            }
        });
    });




    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var deleteId = $(this).data('id');

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this attendance record!",
            icon: "warning",
            buttons: [
                'No, cancel it!',
                'Yes, I am sure!'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ route('admin.students.delete.batch.attendance') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: deleteId
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            swal({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success'
                            }).then(function() {
                                $('#attendance-row-' + deleteId).fadeOut();
                            });
                        } else {
                            swal("Error", data.message, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            swal("Error", xhr.responseJSON.message, "error");
                        } else {
                            swal("Error",
                                "An error occurred while deleting the attendance record.",
                                "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "Your attendance record is safe :)", "error");
            }
        });
    });
</script>
