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

                $studentBatches = app\Models\StudentBatch::where('batch_id', $data->id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->select('student_id')  // Select only student_id
                ->distinct()  // Ensure uniqueness
                ->get();

            // dd($studentBatches);

            @endphp
            @foreach ($studentBatches as $studentBatch)
                {{-- @if ($studentBatch->status != 'INACTIVE') --}}
                @php
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
                                {{-- @if($attendance && $attendance->status == 'CANCELLED')
                                    <option value="CANCELLED"
                                        {{ $attendance && $attendance->status == 'CANCELLED' ? 'selected' : '' }}>CANCELLED
                                    </option>
                                @endif --}}
                                {{-- <option value="LEAVE"
                                    {{ !$attendance || $attendance->status == 'NOTMARKED' ? 'selected' : '' }}>NOT
                                    MARKED</option> --}}
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
                {{-- @endif --}}
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>
    <!-- Hidden inputs and status selection placed outside but immediately after the table -->
    @foreach ($studentBatches as $studentBatch)
        {{-- @if ($studentBatch->status != 'INACTIVE') --}}
        <input type="hidden" name="student_ids[]" value="{{ $studentBatch->student->id }}">
        {{-- @endif --}}
    @endforeach
    <input type="hidden" name="coach_id" value="{{ $coachId }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="batch_id" value="{{ $data->id }}">
    <input type="hidden" name="batchEndTiming" value="{{ $toTime }}">

    <!-- Status selection for the entire batch -->
    <div class="row">
        @php
            // dd($coachAttendance);
            // Convert $toTime to 24-hour format
            $toTime24 = date('H:i', strtotime($toTime));
        @endphp
        <div class="col-2">
            <input type="date" class="form-control" name="date"
                value="{{ isset($coachAttendance) ? $coachAttendance->date : $date }}" readonly />
            <div id="date-error" style="color:red"></div>
        </div>
        <div class="col-2">
            @php 
                date_default_timezone_set('Asia/Kolkata');
                $user = Auth::user();
            @endphp
            <input type="time" class="form-control" name="time" value="{{ isset($coachAttendance) ? $coachAttendance->time : $toTime24 }}" />
            {{-- <input type="time" class="form-control" name="time" value="{{ isset($coachAttendance) ? $coachAttendance->time : $toTime24 }}" @if(!$user->can('reports-attedance') || in_array("SuperAdmin", $user->getRoleNames()->toArray())) readonly @endif /> --}}
            <div id="time-error" style="color:red"></div>
        </div>
        <div class="col-3">
            <select class="form-control" name="status">
                <option value="">Select a batch attendance ...</option>
                {{-- <option value="NOTMARKED" @if (isset($coachAttendance) && $coachAttendance->status == 'NOTMARKED') selected @endif>NOT MARKED</option> --}}
                <option value="COMPLETED" @if (isset($coachAttendance) && $coachAttendance->status == 'COMPLETED') selected @endif>COMPLETED</option>
                <option value="CANCELLED" @if (isset($coachAttendance) && $coachAttendance->status == 'CANCELLED') selected @endif>CANCELLED</option>
            </select>
            <div id="status-error" style="color:red"></div>
        </div>

        <div class="row mt-3 mb-3">
            <div class="col-6">
                <input type="text" class="form-control" name="homework_link" id="homework_link"
                    value="{{ !empty($coachAttendance->homework_link) ? $coachAttendance->homework_link : '' }}"
                    placeholder="Homework Link" />
                <div id="homework_link-error" style="color:red"></div>
            </div>

            <div class="col-6">
                <input type="text" class="form-control" name="recording_link" id="recording_link"
                    value="{{ !empty($coachAttendance->recording_link) ? $coachAttendance->recording_link : '' }}"
                    placeholder="Recording Link" />
                <div id="recording_link-error" style="color:red"></div>
            </div>
            <div class="col-6 mt-3">
                <input type="text" class="form-control" name="chapter_name" id="chapter_name"
                    value="{{ !empty($coachAttendance->chapter_name) ? $coachAttendance->chapter_name : '' }}"
                    placeholder="Chapter Name" />
                <div id="chapter_name-error" style="color:red"></div>
            </div>
            <div class="col-6 mt-3">

                {{-- @php 
                    $user = Auth::user();
                @endphp
                @if(!$user->can('reports-attedance') || !in_array("SuperAdmin", $user->getRoleNames()->toArray()))
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Submit Attendance
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                    </div>
                @else
                    @if (isset($coachAttendance) &&
                            $coachAttendance &&
                            $coachAttendance->status != 'NOTMARKED' &&
                            ($coachAttendance->status == 'CANCELLED' || $coachAttendance->status == 'COMPLETED'))
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" disabled>
                                Attendance already submitted
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    @else
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Submit Attendance
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    @endif
                @endif --}}
                @php 
                    $user = Auth::user();
                @endphp

                @if($user->can('reports-attedance') || in_array("SuperAdmin", $user->getRoleNames()->toArray()))
                    {{-- @if (isset($coachAttendance) &&
                            $coachAttendance &&
                            $coachAttendance->status != 'NOTMARKED' &&
                            ($coachAttendance->status == 'CANCELLED' || $coachAttendance->status == 'COMPLETED'))
                        <div class="col-12"> --}}
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Submit Attendance
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                        {{-- </div> --}}
                @else
                    @if (isset($coachAttendance) &&
                        $coachAttendance &&
                        $coachAttendance->status != 'NOTMARKED' &&
                        ($coachAttendance->status == 'CANCELLED' || $coachAttendance->status == 'COMPLETED'))
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" disabled>
                                Attendance already submitted
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    @else
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Submit Attendance
                                &nbsp;
                                <i class="ti ti-device-floppy"></i>
                            </button>
                        </div>
                    @endif
                @endif

            </div>

        </div>


    </div>
</form>
<!-- ------------------------------------------------------------------------------ :: -->
