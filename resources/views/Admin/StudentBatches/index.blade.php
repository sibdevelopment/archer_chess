@extends('layouts.admin')
@section('title')
    Batches
@endsection
@section('content')

    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Batches</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Batches</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../backend/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @if (!empty($studentFees) && $studentFees->count() > 0)
        <div class="container-fluid">
            <div class="product-list">
                <div class="card boder-0">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">
                            <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Fees Details :
                        </h5>

                        @if (!empty($studentFees) && $studentFees->count() > 0)
                            <div class="table-responsive border rounded">
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
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentFees as $index => $fee)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($fee->start_date)->format('d-M-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($fee->end_date)->format('d-M-Y') }}</td>
                                                <td>{{ $fee->currency }}</td>
                                                <td>{{ $fee->monthly_fees }}</td>
                                                <td>{{ $fee->total_amount_paid }}</td>
                                                <td>{{ $fee->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    @if (!empty($studentBatches) && count($studentBatches) > 0)
        <div class="container-fluid">
            <div class="product-list">
                <div class="card boder-0">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">
                            <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Batches History :
                        </h5>

                        @if (!empty($studentBatches) && count($studentBatches) > 0)
                            <div class="table-responsive border rounded">
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
                                        @foreach ($studentBatches as $index => $studentBatch)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}
                                                </td>
                                                <td>{{ $studentBatch->name }}</td>
                                                <td>{{ $studentBatch->coach->user->first_name }}
                                                    {{ $studentBatch->coach->user->last_name }}</td>
                                                <td>{{ $studentBatch->level->name }}</td>
                                                <td>{{ $studentBatch->status }}</td>
                                                <td>
                                                    @php
                                                        $total_sessions = App\Models\CoachAttendance::where(
                                                            'batch_id',
                                                            $studentBatch->id,
                                                        )
                                                            ->where('coach_id', $studentBatch->coach_id)
                                                            ->where('status', 'COMPLETED')
                                                            ->orderBy('id', 'desc')
                                                            ->count();
                                                    @endphp
                                                    {{ $total_sessions ?? 0 }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if (!empty($studentAttendances))
        <div class="container-fluid">
            <div class="product-list">
                <div class="card boder-0">
                    <div class="card-body p-3">
                        <h5 class="fw-semibold mb-3">
                            <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Classes History :
                        </h5>
                        @if (!empty($studentAttendances))
                            <div class="table-responsive border rounded">
                                <table class="table table-bordered table-hover table-striped" id="studentsAttendanceTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Coach</th>
                                            <th>Batch</th>
                                            <th>IND Date</th>
                                            <th>IND Time</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentAttendances as $attendance)
                                            <tr id="attendance-row-{{ $attendance['id'] }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $attendance['coach']['name'] }}</td>
                                                <td>{{ $attendance['batch']['name'] }}</td>
                                                <td>{{ $attendance['date'] }}</td>
                                                <td>{{ $attendance['time'] }}</td>
                                                <td>{{ convertTimeZomeWiseDate($attendance['date'], $attendance['time'], $attendance['student_id']) }}
                                                </td>
                                                <td>{{ convertTimeZomeWiseTime($attendance['date'], $attendance['time'], $attendance['student_id']) }}
                                                </td>
                                                <td>{{ $attendance['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
