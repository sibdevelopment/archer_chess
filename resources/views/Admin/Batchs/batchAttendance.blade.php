<div class="table-responsive rounded">
    <table class="table table-bordered table-hover table-striped" id="studentsAttendanceTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Coach</th>
                <th>Date</th>
                <th>Time</th>
                {{-- <th>Status</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($coach_attendances as $attendance)
                <tr id="attendance-row-{{ $attendance['id'] }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attendance->coach->user->first_name }} {{ $attendance->coach->user->last_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('d-m-Y') }}</td>
                    <td>{{ $attendance['time'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
