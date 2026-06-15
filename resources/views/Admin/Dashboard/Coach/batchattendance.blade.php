<script>
    $(document).ready(function() {
        $('#delayedBatchNoticeModal').modal('show');
    });
</script>

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
                                    <h6 class="mb-0">{{ $data->name }}</h6>

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
                                    <h6 class="mb-0">{{ $batchLevel->name }}</h6>
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
                                    <h6 class="mb-0">( {{ $fromTime }} - {{ $toTime }}) &nbsp;
                                        {{ \Carbon\Carbon::parse($batchStartDate)->format('d-M-Y') }} &nbsp; - &nbsp;
                                        {{ \Carbon\Carbon::parse($batchEndDate)->format('d-M-Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h6 class="text-danger">Number of Sessions done : 
    @if ($batchLevel->id == 2)
        {{ $numberOfBatchSessions + 10 }}
    @else
        {{ $numberOfBatchSessions }}
    @endif  
</h6>

<h5 class="mt-2">Students :</h5>
<form method="POST" action="{{ route('admin.dashboard.batchAttendance', ['coachId' => $coachId]) }}"
    enctype="multipart/form-data" autocomplete="off" id="coachBatchAttendanceForm">
    @csrf
    <!-- Table for displaying student names and inputs for remarks and status -->
    <table
        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
        <thead>
            <tr>
                <th scope="col" style="text-align: center;width: 1%;">#</th>
                <th scope="col" style="text-align: center;width: 20%;">Name</th>
                <th scope="col" style="text-align: center;width: 10%;">Attendance</th>
                <th scope="col" style="text-align: center;width: 30%;">Remark</th>
            </tr>
        </thead>
        @php
            $date = date('Y-m-d');
            $studentBatches = app\Models\StudentBatch::where('batch_id', $data->id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->where('status', 'ACTIVE')
                ->select('student_id')
                ->distinct()
            ->get();
        @endphp
        <tbody>
            @foreach ($studentBatches as $studentBatch)
                {{-- @if ($studentBatch->status == 'ACTIVE') --}}
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td style="text-align: center;">{{ $studentBatch->student->first_name }}
                        {{ $studentBatch->student->last_name }} </td>
                    <td style="text-align: center;">
                        <div class="col-sm-12 col-md-12">
                            <select class="form-control" name="studentStatus[{{ $studentBatch->student->id }}]">
                                {{-- <option value="PRESENT" @if (isset($studentAttendances[$studentBatch->student->id]) &&
                                        $studentAttendances[$studentBatch->student->id]->status == 'PRESENT') selected @endif>PRESENT
                                </option> --}}
                                <option value="ABSENT" @if (isset($studentAttendances[$studentBatch->student->id]) &&
                                        $studentAttendances[$studentBatch->student->id]->status == 'ABSENT') selected @endif>ABSENT</option>
                            </select>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <input type="text" class="form-control"
                            name="studentRemark[{{ $studentBatch->student->id }}]" placeholder="Enter remarks here"
                            value="{{ $studentAttendances[$studentBatch->student->id]->remark ?? '' }}" />
                        <div id="studentStatus-error" style="color:red"></div>
                    </td>
                </tr>
                {{-- @endif --}}
            @endforeach
        </tbody>
    </table>
    <!-- ------------------------------------------------------------------------------ :: -->
    <!-- Hidden inputs and status selection placed outside but immediately after the table -->
    @foreach ($studentBatches as $studentBatch)
        {{-- @if ($studentBatch->status == 'ACTIVE') --}}
        <input type="hidden" name="student_ids[]" value="{{ $studentBatch->student->id }}">
        {{-- @endif --}}
    @endforeach
    <input type="hidden" name="coach_id" value="{{ $coachId }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="batch_id" value="{{ $data->id }}">
    <input type="hidden" name="batchEndTiming" value="{{ $toTime }}">
    <input type="hidden" value="{{ $batchLevel->id }}" name="level_id">
    <!-- ------------------------------------------------------------------------------ :: -->
    <!-- Status selection for the entire batch -->
    <div class="row bottomDiv">
        <div class="col-4">
            <input type="date" class="form-control" name="date" value="{{ isset($attendanceDate) ? $attendanceDate : date('Y-m-d') }}" readonly />
            <div id="date-error" style="color:red"></div>
        </div>
        <div class="col-4">
            @php 
                date_default_timezone_set('Asia/Kolkata'); 
            @endphp
            <input type="time" class="form-control" name="time" value="{{ isset($attendanceTime) ? $attendanceTime : date('H:i') }}" readonly />
            <div id="time-error" style="color:red"></div>
        </div>
        <div class="col-4 ">
            <select class="form-control" name="status">
                {{-- <option value="">Select a batch attendance ...</option> --}}
                {{-- <option value="NOTMARKED" @if (isset($todaysCoachAttendance) && $todaysCoachAttendance->status == 'NOTMARKED') selected @endif>NOTMARKED</option> --}}
                <option value="COMPLETED" @if (isset($todaysCoachAttendance) && $todaysCoachAttendance->status == 'COMPLETED') selected @endif>COMPLETED</option>
                {{-- <option value="CANCELLED" @if (isset($todaysCoachAttendance) && $todaysCoachAttendance->status == 'CANCELLED') selected @endif>CANCELLED</option> --}}
            </select>
            <div id="status-error" style="color:red"></div>
        </div>
    </div>
    <div class="row mt-3 bottomDiv">
        <div class="col-6">
            <input type="text" class="form-control" name="homework_link" id="homework_link"
                value="{{ !empty($todaysCoachAttendance->homework_link) ? $todaysCoachAttendance->homework_link : '' }}"
                placeholder="Homework Link" />
            <div id="homework_link-error" style="color:red"></div>
        </div>
        <div class="col-6">
            <input type="text" class="form-control" name="chapter_name" id="chapter_name"
                value="{{ !empty($todaysCoachAttendance->chapter_name) ? $todaysCoachAttendance->chapter_name : '' }}"
                placeholder="Chapter Name" />
            <div id="chapter_name-error" style="color:red"></div>
        </div>
    </div>

    {{-- @if (isset($todaysCoachAttendance))
        @if($todaysCoachAttendance->status == 'CANCELLED')
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-primary" disabled>
                        Class Cancelled
                        &nbsp;
                        <i class="ti ti-device-floppy"></i>
                    </button>
                </div>
            </div>
        @else --}}
            <div class="row mt-3 bottomDiv">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary">
                        Submit Attendance
                        &nbsp;
                        <i class="ti ti-device-floppy"></i>
                    </button>
                </div>
            </div>
        {{-- @endif
    @else  
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <button type="button" class="btn btn-primary" disabled>
                    Class Not Started Yet
                    &nbsp;
                    <i class="ti ti-device-floppy"></i>
                </button>
            </div>  
        </div>
    @endif  --}}


    <!-- ------------------------------------------------------------------------------ :: -->
</form>

@if ($isDelayed)
    <div class="modal fade" id="delayedBatchNoticeModal" tabindex="-1"
        aria-labelledby="delayedBatchNoticeModalLabel" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="delayedBatchNoticeModalLabel">Delayed batch</h5>
                </div>
                <div class="modal-body">
                    <p class="mb-2">The batch has been delayed by {{ $delayTime }} minutes.</p>
                    <p class="mb-0 text-muted small">This counts as a delayed batch. Confirm to continue marking
                        attendance, or choose not to start.</p>
                </div>
                <div class="modal-footer flex-nowrap gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-grow-1"
                        id="delayedBatchDontStartBtn">Don&rsquo;t start batch</button>
                    <button type="button" class="btn btn-primary flex-grow-1"
                        id="delayedBatchConfirmBtn">Yes, confirm</button>
                </div>
            </div>
        </div>
    </div>
@endif
