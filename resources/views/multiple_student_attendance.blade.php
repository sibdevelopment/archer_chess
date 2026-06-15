<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">Multiple Students in Batch</h2>

    @if ($student_attendances->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No records found where the combination of Student, Batch, and Coach is repeated more than once.
        </div>
    @else
        <table class="table table-striped table-bordered table-hover shadow-sm">
            <thead>
                <tr class="table-dark">
                    <th>Student Name</th>
                    <th>Batch Name</th>
                    <th>Date</th>
                    <th>Coach Name</th>
                    <th>Record Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student_attendances as $student_attendance)
                    @php
                        $batch = App\Models\Batch::where('id', $student_attendance['batch_id'])->first();
                        $student = App\Models\Student::where('id', $student_attendance['student_id'])->first();
                        $coach = App\Models\Coach::where('id', $student_attendance['coach_id'])->first();

                        $multiple_attendances = App\Models\StudentAttendance::where('student_id',$student->id)->where('batch_id', $batch->id)->where('date',$student_attendance['date'])->get();
                    @endphp
                    <tr class="text-center">
                        <td>{{ $student ? $student->first_name : '' }} {{ $student ? $student->last_name : '' }} {{ $student ? $student->id : '' }} </td>
                        <td>
                            {{ $batch ? $batch->name : '' }}
                        </td>
                        <td class="align-middle">
                            <span class="fw-bold">{{ toIndianDate($student_attendance['date']) }}</span>
                            <div class="mt-2">
                                @foreach ($multiple_attendances as $multiple_attendance)
                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded bg-light mb-2">
                                        <small class="text-dark fw-bold">{{ $multiple_attendance->time }}</small>
                                        <a href="#"
                                           class="badge bg-danger text-white delete-attendance"
                                           data-id="{{ $multiple_attendance->id }}"
                                           data-student="{{ $student->id }}"
                                           data-batch="{{ $batch->id }}"
                                           data-date="{{ $student_attendance['date'] }}"
                                           style="cursor: pointer;">
                                            ❌ Delete
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td>{{ $coach ? $coach->user->first_name : '' }} {{ $coach ? $coach->user->last_name : '' }}</td>
                        <td>{{ $student_attendance->count }}</td>
                    </tr>
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
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // Delete Student Attendance
    $(document).on('click', '.delete-attendance', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let student = $(this).data('student');
        let batch = $(this).data('batch');
        let date = $(this).data('date');

        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: '/admin/students/delete/attendance',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    student: student,
                    batch: batch,
                    date: date
                },
                success: function (response) {
                    if (response.status === 'success') {
                        alert('Record deleted successfully!');
                        location.reload();
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        }
    });
</script>
