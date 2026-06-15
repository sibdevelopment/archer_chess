<div class="row"></div>
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
<!-- ------------------------------------------------------------------------------ :: -->
<h6 class="text-danger">Number of Sessions done : {{ $numberOfBatchSessions }}</h6>
<h5 class="mt-2">Students :</h5>
<h6>Remember: You can only submit or update data within <span class="text-danger">10 minutes</span> after the batch
    ended.</h6>
<!-- ------------------------------------------------------------------------------ :: -->









<form method="POST" action="{{ route('admin.reports.batchAttendance', ['coachId' => $data->coach_id]) }}"
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
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($data->studentBatches as $studentBatch)
                @php
                    // Find the attendance record for the current student
                    $attendance = $studentAttendances->firstWhere('student_id', $studentBatch->student->id);
                @endphp
                <tr>
                    <th scope="row">{{ $i }}</th>
                    <td style="text-align: center;">{{ $studentBatch->student->first_name }}
                        {{ $studentBatch->student->last_name }}</td>
                    <td style="text-align: center;">
                        <div class="col-sm-12 col-md-12">
                            <select class="form-control" name="studentStatus[{{ $studentBatch->student->id }}]">
                                <option value="PRESENT"
                                    {{ $attendance && $attendance->status == 'PRESENT' ? 'selected' : '' }}>PRESENT
                                </option>
                                <option value="ABSENT"
                                    {{ $attendance && $attendance->status == 'ABSENT' ? 'selected' : '' }}>ABSENT
                                </option>
                            </select>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <input type="text" class="form-control"
                            name="studentRemark[{{ $studentBatch->student->id }}]" placeholder="Enter remarks here"
                            value="{{ $attendance ? $attendance->remark : '' }}" />
                        <div id="studentStatus-error" style="color:red"></div>
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
    <!-- Hidden inputs and status selection placed outside but immediately after the table -->
    @foreach ($data->studentBatches as $studentBatch)
        <input type="hidden" name="student_ids[]" value="{{ $studentBatch->student->id }}">
    @endforeach
    <input type="hidden" name="coach_id" value="{{ $data->coach_id }}">
    <input type="hidden" name="type" value="Batch">
    <input type="hidden" name="batch_id" value="{{ $data->id }}">
    <input type="hidden" name="batchEndTiming" value="{{ $toTime }}">

    <!-- Status selection for the entire batch -->
    <div class="row">
        @php
            // Convert $toTime to 24-hour format
            $toTime24 = date('H:i', strtotime($toTime));
        @endphp
        <div class="col-2">
            <input type="date" class="form-control" name="date"
                value="{{ isset($coachAttendance) ? $coachAttendance->date : $date }}" readonly />
            <div id="date-error" style="color:red"></div>
        </div>
        <div class="col-2">
            @php date_default_timezone_set('Asia/Kolkata'); @endphp
            <input type="time" class="form-control" name="time"
                value="{{ isset($coachAttendance) ? $coachAttendance->time : $toTime24 }}" />
            <div id="time-error" style="color:red"></div>
        </div>
        <div class="col-3">
            <select class="form-control" name="status">
                <option value="">Select a batch attendance ...</option>
                <option value="COMPLETED" @if (isset($coachAttendance) && $coachAttendance->status == 'COMPLETED') selected @endif>COMPLETED</option>
                <option value="CANCELLED" @if (isset($coachAttendance) && $coachAttendance->status == 'CANCELLED') selected @endif>CANCELLED</option>
            </select>
            <div id="status-error" style="color:red"></div>
        </div>
        <div class="col-3">
            <button type="submit" class="btn btn-primary">
                Submit Attendance
                &nbsp;
                <i class="ti ti-device-floppy"></i>
            </button>
        </div>
        <div class="col-2">
        </div>
    </div>
</form>
<!-- ------------------------------------------------------------------------------ :: -->
