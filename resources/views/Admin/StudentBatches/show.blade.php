<div class="row align-items-center">
    <div class="col-lg-4 order-lg-2 order-1">
        <div class="">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 110px; height: 110px;" ;>
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                        style="width: 100px; height: 100px;" ;>
                        <img src="/backend/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <h5 class="fs-5 mb-0 fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            @if($student->age)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-stretching text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Age : {{ $student->age }}</h6>
            </li>
            @endif
            @if($student->mobile)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-phone text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Mobile : {{ $student->mobile }}</h6>
            </li>
            @endif
            @if($student->email)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-mail text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Email : {{ $student->email }}</h6>
            </li>
            @endif
            @if($student->city)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-home text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">City : {{ $student->city }}</h6>
            </li>
            @endif
            @if($student->country)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-home text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Country : {{ $student->country }}</h6>
            </li>
            @endif
            @if($student->student_id)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-id-badge text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Id : {{ $student->student_id }}</h6>
            </li>
            @endif
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-fingerprint text-dark fs-6"></i>
                <h6
                    class="fs-4 fw-semibold mb-0 {{ ($student->status == 'ACTIVE' ? 'text-success' : 'text-danger' ) }}">
                    {{ ucwords(strtolower($student->status)) }}
                </h6>
            </li>
            @if($student->currency)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-currency text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Currency : {{ $student->currency }}</h6>
            </li>
            @endif
            @if($student->monthly_fees)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-coins text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Monthly Fees : {{ $student->monthly_fees }}</h6>
            </li>
            @endif
            @if($student->level_id)
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-chart-arrows-vertical text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Level : {{ $student->level->name }}</h6>
            </li>
            @endif
        </ul>
    </div>
</div>

@if($studentStatuses->isNotEmpty())
<div class="row" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
    <div class="col-12">
        <h5 class="text-start mb-4">Standby History </h5>
    </div>
    @foreach($studentStatuses as $status)
    <div class="col-lg-12 d-flex align-items-stretch">
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-calendar text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">
                    {{ $status->type }}: {{ \Carbon\Carbon::parse($status->from_date)->format('M d, Y') }} -
                    {{ $status->to_date ? \Carbon\Carbon::parse($status->to_date)->format('M d, Y') : 'Today (' .
                    \Carbon\Carbon::now()->format('M d, Y') . ')' }}
                </h6>
            </li>
        </ul>
    </div>
    @endforeach
</div>
@endif

@if($studentFees->isNotEmpty())
<div class="col-md-12 mt-4">
    <h5 class="fw-semibold mb-2">
        <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Fees Record :
        {{-- <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Active Fees Record : --}}
    </h5>
    <table
        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Currency</th>
                <th scope="col">Monthly Fee</th>
                <th scope="col">Total Amount Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentFees as $index => $fee)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($fee->start_date)->format('d-M-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($fee->end_date)->format('d-M-Y') }}</td>
                <td>{{ $fee->currency }}</td>
                <td>{{ $fee->monthly_fees }}</td>
                <td>{{ $fee->total_amount_paid }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


@if($studentBatches->isNotEmpty())
<div class="col-md-12 mt-4">
    <h5 class="fw-semibold mb-2">
        <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Batches History :
    </h5>
    <table
        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Batch</th>
                <th scope="col">Coach</th>
                <th scope="col">Level</th>
                <th scope="col">Status</th>
                <th scope="col">Number of Sessions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentBatches as $index => $studentBatch)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}</td>
                <td>{{ $studentBatch->batch->name }}</td>
                <td>{{ $studentBatch->coach->user->first_name }} {{ $studentBatch->coach->user->last_name }}</td>
                <td>{{ $studentBatch->level->name }}</td>
                <td>{{ $studentBatch->status }}</td>
                <td>
                    @php
                    $lastSessionRecord = App\Models\StudentAttendance::where('student_id', $student->id)
                    ->where('batch_id', $studentBatch->batch_id)
                    ->latest('date')
                    ->first();
                    $sessionsAttended = $lastSessionRecord ? $lastSessionRecord->number_of_batch_sessions : 0;
                    @endphp
                    {{ $sessionsAttended }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<h5 class="fw-semibold mb-2 mt-5">
    <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Student Attendance History :
</h5>
<div class="table-responsive rounded mt-2">
    <table class="table table-bordered table-hover table-striped" id="studentsAttendanceTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Coach</th>
                <th>Batch</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th> <!-- New column for action -->
            </tr>
        </thead>
        <tbody>
            @foreach($studentAttendances as $attendance)
            <tr id="attendance-row-{{ $attendance['id'] }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $attendance['coach']['name'] }}</td>
                <td>{{ $attendance['batch']['name'] }}</td>
                <td>{{ $attendance['date'] }}</td>
                <td>{{ $attendance['time'] }}</td>
                <td>{{ $attendance['status'] }}</td>
                <td>
                    <div class="action-btn">
                        <a href="#" data-id="{{ $attendance['id'] }}" class="text-dark delete ms-2">
                            <i class="ti ti-trash fs-5"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).on('click', '.delete', function (e) {
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
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ route('admin.students.delete.attendance') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: deleteId
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            swal({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success'
                            }).then(function () {
                                $('#attendance-row-' + deleteId).fadeOut();
                            });
                        } else {
                            swal("Error", data.message, "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            swal("Error", xhr.responseJSON.message, "error");
                        } else {
                            swal("Error", "An error occurred while deleting the attendance record.", "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "Your attendance record is safe :)", "error");
            }
        });
    });
</script>
