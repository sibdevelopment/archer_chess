<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">Multiple Students in Batch</h2>

    @if ($student_batches->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No records found where the combination of Student, Batch, and Coach is repeated more than once.
        </div>
    @else
        <table class="table table-striped table-bordered table-hover shadow-sm">
            <thead>
                <tr class="table-dark">
                    <th>Student Name</th>
                    <th>Batch Name</th>
                    <th>Coach Name</th>
                    <th>Record Count</th>
                    <th>Batch Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student_batches as $student_batch)
                    @php
                        $batch = App\Models\Batch::where('id', $student_batch['batch_id'])->first();
                        $student = App\Models\Student::where('id', $student_batch['student_id'])->first();
                        $coach = App\Models\Coach::where('id', $student_batch['coach_id'])->first();
                        $multiple_batches = App\Models\StudentBatch::where('student_id', $student->id)
                            ->where('batch_id', $batch->id)->get();
                    @endphp
                    <tr class="text-center">
                        <td>{{ $student ? $student->first_name : '' }} {{ $student ? $student->last_name : '' }}</td>
                        <td>
                            {{ $batch ? $batch->name : '' }}
                            <small class="text-muted">({{ toIndianDate($batch->start_date) }} /
                                {{ toIndianDate($batch->end_date) }})</small>
                            <br>
                            @foreach ($multiple_batches as $key => $multiple_batch)
                                <small class="text-black" style="font-weight: bold;">{{ $key+1 }}) {{ toIndianDate($multiple_batch->start_date) }} /
                                    {{ toIndianDate($multiple_batch->end_date) }}</small> <br>
                            @endforeach
                        </td>
                        <td>{{ $coach ? $coach->user->first_name : '' }} {{ $coach ? $coach->user->last_name : '' }}</td>
                        <td>{{ $student_batch->count }}</td>
                        <td>
                            @if ($batch && $batch->route_key)
                                <a href="{{ route('admin.batchs.show', ['batch' => $batch->route_key]) }}"
                                    class="badge bg-info fs-1 modal-one-btn" data-entity="batchs"
                                    data-title="Batch Details" target="_blank">
                                    <i class="fa fa-eye"></i> View Batch
                                </a>
                            @else
                                <span class="text-danger">No Batch</span>
                            @endif
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
