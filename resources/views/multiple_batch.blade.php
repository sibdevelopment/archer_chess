

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">Multiple Batches</h2>
    <br><br>

    @if ($batches->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No records found where the combination of Name, Kids Zone, Coach, Level, and Dates is duplicated.
        </div>
    @else
        <table class="table table-striped table-bordered table-hover shadow-sm">
            <thead>
                <tr class="table-dark">
                    <th>Batch Name</th>
                    <th>Kids Zone</th>
                    <th>Coach Name</th>
                    <th>Level</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($batches as $batch)
                    <tr class="text-center">
                        <td>{{ $batch->name }}</td>
                        <td>{{ $batch->kids_zone_name }}</td>
                        <td>
                            {{ $batch->coach ? $batch->coach->user->first_name : '' }}
                            {{ $batch->coach ? $batch->coach->user->last_name : '' }}
                        </td>
                        <td>{{ $batch->level ? $batch->level->name : '' }}</td>
                        <td>{{ toIndianDate($batch->start_date) }}</td>
                        <td>{{ toIndianDate($batch->end_date) }}</td>
                        <td>{{ $batch->count }}</td>
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

    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

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
