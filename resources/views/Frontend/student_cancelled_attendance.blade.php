<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">Student Cancelled Attendance</h2>

    @if ($studentAttendances->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No students found with 2 or more cancelled attendances.
        </div>
    @else
        <table class="table table-striped table-bordered table-hover shadow-sm">
            <thead>
                <tr class="table-dark text-center">
                    <th>#</th>
                    <th>Student</th>
                    <th>Batch</th>
                    <th>Attendance Date</th>
                    <th>Fees Start Date</th>
                    <th>Fees End Date</th>
                    <th>Cancelled Count</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($studentAttendances as $index => $attendance)
                    @php
                        $student = $attendance->student;
                        $batch = $attendance->batch;
                        $coach = $attendance->coach;
                        $cancelledCount = App\Models\StudentAttendance::where('student_id', $attendance->student_id)
                            ->where('status', 'CANCELLED')
                            ->where('date', $attendance->date)
                            ->count();


                        $feeRecord = App\Models\StudentFee::where('student_id', $attendance->student_id)
                            ->orderBy('end_date', 'desc')
                            ->first();
                    @endphp

                    @if ($cancelledCount >= 2)
                        <tr class="text-center align-middle">
                            <td>{{ $loop->iteration }}</td>

                            {{-- Student --}}
                            <td>
                                <div class="fw-bold">
                                    {{ $student?->first_name }} {{ $student?->last_name }} ({{ $student?->id }})
                                </div>
                                <div class="text-muted small">
                                    ID: {{ $student?->student_id ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    Status: {{ $student?->status ?? '-' }}
                                </div>
                            </td>

                            {{-- Batch --}}
                            <td>
                                <div class="fw-bold">
                                    {{ $batch?->name ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                   Coach:@if ($coach && $coach->user)
                                    {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                @else
                                    <span class="text-muted">No Coach</span>
                                @endif
                                </div>
                            </td>


                            {{-- Attendance Date --}}
                            <td>{{ toIndianDate($attendance->date) }}</td>

                            {{-- Fee Start Date --}}
                            <td>
                                @if ($feeRecord)
                                    <span class="text-dark fw-bold">{{ toIndianDate($feeRecord->start_date) }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            {{-- Fee End Date --}}
                            <td class="editable-date text-center align-middle" data-student="{{ $student->id }}">
                                <span class="date-text">{{ $feeRecord ? toIndianDate($feeRecord->end_date) : 'N/A' }}</span><br>
                                <input type="date" class="form-control date-input d-none"
                                    value="{{ $feeRecord ? $feeRecord->end_date : '' }}">
                            </td>

                            {{-- Record Count --}}
                            <td>{{ $cancelledCount }}</td>
                            
                            {{-- View Button --}}
                            <td>
                                <a href="{{ url('admin/students/' . $student->id . '/student_fees') }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

</div>

<style>
    /* Custom Table Styling */
    table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
    }

    th, td {
        padding: 12px 20px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
        text-transform: uppercase;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr:hover {
        background-color: #e2e6ea;
    }

    .alert {
        padding: 15px;
        background-color: #fff3cd;
        color: #856404;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 20px;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    small.text-muted {
        font-size: 0.85rem;
    }

    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    /* Enhanced Button Styling */
    .badge.bg-info {
        background-color: #17a2b8;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 1rem;
    }

    .badge.bg-info:hover {
        background-color: #138496;
        text-decoration: none;
        color: white;
    }

    .badge i {
        margin-right: 5px;
    }

    /* Responsive table for smaller screens */
    @media (max-width: 767px) {
        table {
            font-size: 14px;
        }

        th, td {
            padding: 10px;
        }

        h2 {
            font-size: 1.5rem;
        }
    }

    .editable-date {
        cursor: pointer;
        position: relative;
    }

    .editable-date .date-input {
        width: 150px;
        font-size: 0.95rem;
        padding: 4px 6px;
    }

</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.edit-date-btn', function () {
        let td = $(this).closest('td');
        td.find('.date-text').addClass('d-none');
        td.find('.date-input').removeClass('d-none').focus();
    });

    $(document).on('change', '.date-input', function () {
        let td = $(this).closest('td');
        let newDate = $(this).val();
        let studentId = td.data('student');

        $.ajax({
            url: '{{ route("students.updateFeeEndDate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                student_id: studentId,
                end_date: newDate
            },
            success: function (response) {
                if (response.status === 'success') {
                    td.find('.date-text').text(response.formatted_date);
                    td.find('.date-text').removeClass('d-none');
                    td.find('.date-input').addClass('d-none');
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error('Something went wrong. Please try again.');
            }
        });
    });

</script>

