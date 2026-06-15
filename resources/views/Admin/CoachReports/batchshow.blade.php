
<style>
    ul.border-top-bold {
        border-top: 1px solid #000; /* Makes the border top bold */
    }
</style>
<div class="row align-items-center">
    <div class="col-lg-12 order-last">
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-color-swatch text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Batch : {{ $batch->name }}</h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-user-circle text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Coach : {{ $batch->coach->user->first_name }}{{ $batch->coach->user->last_name }} </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-2">
                <i class="ti ti-fingerprint text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0 {{ ($batch->status == 'ACTIVE' ? 'text-success' : 'text-danger' ) }}">
                    Status : {{ ucwords(strtolower($batch->status)) }}
                </h6>
            </li>
        </ul>
    </div>
</div>

@if($batchSchedules->isNotEmpty())
<div class="col-md-12 mt-4">
    <h5 class="fw-semibold mb-2">
        <i class="ti ti-calendar fs-5"></i> &nbsp;   &nbsp;Batch Schedule :
    </h5>
    <table class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
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
            @foreach($batchSchedules as $index => $schedule)
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
@endif


 
@if($studentBatches->isNotEmpty())
<div class="col-md-12 mt-4">
    <div class="row">
        <div class="col-6 mt-1">
            <h5 class="fw-semibold">
                <i class="ti ti-calendar fs-5"></i> &nbsp; &nbsp;Active Student Batches Record :
            </h5>
        </div>
        <div class="col-6">
            <div class="row" style="border: 1px solid #ccc; padding: 6px; border-radius: 30px;">
                <div class="col-lg-12 d-flex align-items-stretch">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2">
                            @if($totalSessionsCompleted > 0)
                            <i class="ti ti-check text-dark fs-6"></i>
                            <h6 class="fs-4 fw-semibold mb-0">Total Sessions Completed by Coach: {{
                                $totalSessionsCompleted }}</h6>
                            @else
                            <h6 class="fs-4 fw-semibold mb-0">No sessions completed yet.</h6>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg mt-2">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Student </th>
                <!-- <th scope="col">Coach </th> -->
                <th scope="col">Level </th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
            </tr>   
        </thead>
        <tbody>
            @foreach($studentBatches as $index => $studentBatch)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $studentBatch->student->first_name }} {{ $studentBatch->student->last_name }}  ({{ $studentBatch->student->student_id }})</td>
                    <!-- <td>{{ $studentBatch->coach->user->first_name }} {{ $studentBatch->coach->user->last_name }}</td> -->
                    <td>{{ $studentBatch->level->name }}</td>   
                    <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


@php
use App\Models\Batch;use App\Models\StudentBatch;

// Fetch student batches without checking the status
$activeStudentBatches = StudentBatch::whereHas('batch', function($query) use ($batch) {
$query->where('id', $batch->id);
})->with('batch', 'student', 'coach', 'level')->get();

$studentBatchIds = $activeStudentBatches->pluck('batch_id')->unique();
$batches = Batch::whereIn('id', $studentBatchIds)->with('studentBatches.student', 'studentBatches.coach',
'studentBatches.level')->get();
$parentIds = $batches->pluck('parent_id')->unique();
$relatedBatches = Batch::whereIn('parent_id', $parentIds)->with('studentBatches.student',
'studentBatches.coach',
'studentBatches.level')->get();
$parentBatches = Batch::whereIn('id', $parentIds)->with('studentBatches.student', 'studentBatches.coach',
'studentBatches.level')->get()->keyBy('id');
$groupedByParentId = $relatedBatches->groupBy('parent_id');
@endphp


@foreach($groupedByParentId as $parentId => $childBatches)
@php
// Sort child batches by version in descending order
$sortedChildBatches = $childBatches->sortByDesc('version');
@endphp
@if($parentBatches->has($parentId))
<!-- <div>Parent ID: {{ $parentId }} - Name: {{ $parentBatches[$parentId]->name }}</div> -->

<div class="card w-100 mb-2">
    <div class="card-body p-3">
        <ul>
            @foreach($sortedChildBatches as $batch)
            @if($batch->id && $batch->status != 'ACTIVE')
            <h5 class="fw-semibold mb-2 mt-2">
                <i class="ti ti-calendar fs-5"></i> &nbsp; &nbsp;Old Batches Records :
            </h5>
            <ul class="">
                <div class="col-md-12 mt-4">
                    <!-- <h5 class="fw-semibold mb-2">
                                <i class="ti ti-calendar fs-5"></i> &nbsp;   &nbsp;Old Batches Records [Version: {{ $batch->version }}]:
                            </h5> -->
                    <table
                        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student</th>
                                <!-- <th scope="col">Coach</th> -->
                                <th scope="col">Level</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->studentBatches as $studentBatch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $studentBatch->student->first_name }} {{ $studentBatch->student->last_name }} ({{
                                    $studentBatch->student->student_id }})</td>
                                <!-- <td>{{ $studentBatch->coach->user->first_name }} {{ $studentBatch->coach->user->last_name }}</td> -->
                                <td>{{ $studentBatch->level->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </ul>
            @endif
            @endforeach
        </ul>
        @endif
        @endforeach

    </div>
</div>