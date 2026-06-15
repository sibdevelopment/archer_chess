<h5 class="title">Demo Sessions</h5>

<!-- -------------------------------------------------------------------------------- :: -->
@if($studentAttendances->isNotEmpty())
<h6 class="text-danger"> Attendance records have been created for this DemoLead. 
    @foreach($studentAttendances as $attendance)
        <tr> <td>{{ $attendance->updated_at->format('d-M-Y |  H:i:s') }}</td></tr>
    @endforeach
</h6>
@else
<h6 class="text-primary"> No attendance records have been created for this Demo Lead yet. </h6>
@endif
<h6>Remember: You can only submit or update data within <span class="text-danger">15 minutes</span> after the demo ended.</h6>

<!-- Table for displaying demolead names and additional information along with form elements for status and remark -->
<form method="POST" action="{{ route('admin.dashboard.demoAttendance', ['coachId' => $data->coach_id]) }}"
    enctype="multipart/form-data" autocomplete="off" id="coachDemoAttendanceForm">
    @csrf
    <table
        class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
        <thead>
            <tr>
                <th scope="col" style="text-align: center;width: 3%;">#</th>
                <th scope="col" style="text-align: center;">Name</th>
                <th scope="col" style="text-align: center;width: 3%;">Age</th>
                <th scope="col" style="text-align: center;width: 20%;">Slot</th>
                <th scope="col" style="text-align: center;width: 15%;">Status</th>
                <th scope="col" style="text-align: center;width: 15%;">Level</th>
                <th scope="col" style="text-align: center;">Chapter Number</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td style="text-align: center;">{{ $data->demolead->first_name }} {{ $data->demolead->last_name }}</td>
                <td style="text-align: center;">{{ $data->demolead->age }}</td>
                <td style="text-align: center;">
                    {{ date('h:i A', strtotime(explode(' - ', $data->slot)[0])) }} - {{ date('h:i A', strtotime(explode(' - ', $data->slot)[1])) }}
                </td>
                <td style="text-align: center;">
                    <div class="col-sm-12 col-md-12">
                        <select class="form-control" name="status">
                            <option value="COMPLETED" {{ ($studentAttendances->first() &&
                                $studentAttendances->first()->status == 'COMPLETED') ? 'selected' : '' }}>COMPLETED
                            </option>
                            <option value="CANCELLED" {{ ($studentAttendances->first() &&
                                $studentAttendances->first()->status == 'CANCELLED') ? 'selected' : '' }}>CANCELLED
                            </option>
                        </select>
                        <div id="status-error" style="color:red"></div>
                    </div>
                </td>
                <td style="text-align: center;">
                    <div class="col-sm-12 col-md-12">
                        <select class="form-control" name="level_id">
                            <option value="">Select Level</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ ($studentAttendances->first() &&
                                $studentAttendances->first()->level_id == $level->id) ? 'selected' : '' }}>{{
                                $level->name }}</option>
                            @endforeach
                        </select>
                        <div id="level_id-error" style="color:red"></div>
                    </div>
                </td>
                <td style="text-align: center;">
                    {{-- <input type="number" class="form-control" name="remark" placeholder="Enter chapter number here"
                        value="{{ $studentAttendances->first()->remark ?? '' }}"> --}}
                    <select name="remark" class="form-control">
                        <option value="">Select chapter number</option>
                        @for ($i = 1; $i <= 100; $i++)
                            <option value="{{ $i }}" 
                                {{ ($studentAttendances->first()->remark ?? '') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>

                    <div id="remark-error" style="color:red"></div>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Status selection for the entire batch -->
    <div class="row">
        <!-- Hidden input fields placed outside but immediately after the table -->
        <input type="hidden" name="coach_id" value="{{ $data->coach_id }}">
        <input type="hidden" name="type" value="Demo">
        <input type="hidden" name="demolead_id" value="{{ $data->demolead->id }}">
        <input type="hidden" name="slot" value="{{ $data->slot }}">
        <div class="col-2">
            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" readonly/>
            <div id="date-error" style="color:red"></div>
        </div>
        <div class="col-2">
            @php date_default_timezone_set('Asia/Kolkata'); @endphp
            <input type="time" class="form-control" name="time" value="{{ date('H:i') }}" readonly/>
            <div id="time-error" style="color:red"></div>
        </div>
        <div class="col-3">
            <!-- Submit button placed outside the table -->
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