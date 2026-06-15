<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">
        Inactive Students - {{ $monthName }} {{ now()->year }}
    </h2>

    @if ($students->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No students found.
        </div>
    @else
        <table class="table table-striped table-bordered table-hover shadow-sm">
            <thead>
                <tr class="table-dark">
                    <th>Student Name</th>
                    <th>Coach</th>
                    <th>Updated By</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    @php
                        $updatedByUser = $student->updated_by ? \App\Models\User::find($student->updated_by) : null;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $student->first_name }} {{ $student->last_name }} ( {{ $student->student_id }})</td>
                        <td>
                            @php
                                $student_latest_batch = $student->latestBatch;
                                if ($student_latest_batch) {
                                    $statusBadge = $student_latest_batch->status === 'ACTIVE' ? ' (Present)' : ' (Previous Coach)';
                                    $coachName = $student_latest_batch->coach
                                        ? $student_latest_batch->coach->user->first_name . ' ' . $student_latest_batch->coach->user->last_name
                                        : 'N/A';
                                }
                            @endphp

                            @if ($student_latest_batch)
                                {{ $coachName }} {!! $statusBadge !!}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $updatedByUser ? $updatedByUser->first_name . ' ' . $updatedByUser->last_name : '-' }}
                        </td>
                        <td>
                            {{ $student->updated_at->format('d M Y') }}
                        </td>
                        <td>
                            <span
                                class="badge 
                                {{ $student->status === 'ACTIVE' ? 'bg-success' : ($student->status === 'INACTIVE' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ ucfirst(strtolower($student->status)) }}
                            </span>
                        </td>
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

    th,
    td {
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

    .table td,
    .table th {
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

        th,
        td {
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
    // $(document).on('click', '.delete-attendance', function(e) {
    //     e.preventDefault();
    //     let id = $(this).data('id');
    //     let student = $(this).data('student');
    //     let batch = $(this).data('batch');
    //     let date = $(this).data('date');

    //     if (confirm('Are you sure you want to delete this record?')) {
    //         $.ajax({
    //             url: '/admin/students/delete/attendance',
    //             type: 'POST',
    //             data: {
    //                 _token: '{{ csrf_token() }}',
    //                 id: id,
    //                 student: student,
    //                 batch: batch,
    //                 date: date
    //             },
    //             success: function(response) {
    //                 if (response.status === 'success') {
    //                     alert('Record deleted successfully!');
    //                     location.reload();
    //                 } else {
    //                     alert('An error occurred. Please try again.');
    //                 }
    //             }
    //         });
    //     }
    // });
</script>
