<style>
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        color: #4a4a4a;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 16px;
        text-align: center;
        font-size: 14px;
        border: 1px solid #eaeaea;
    }

    .table th {
        background-color: #f8f9fa;
        color: #343a40;
        font-weight: bold;
        text-transform: uppercase;
    }

    .table tr:nth-child(even) {
        background-color: #f8f8f8;
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .btn-primary {
        padding: 6px 12px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .empty-state {
        padding: 20px;
        text-align: center;
        color: #6c757d;
        font-size: 18px;
        background-color: #f8f9fa;
        border: 1px solid #eaeaea;
        border-radius: 8px;
        margin-top: 16px;
    }
</style>

@if (!$upcomming_masterclasses->isEmpty())
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Upcoming Masterclasses</h4>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($upcomming_masterclasses as $upcomming_masterclass)
                        @php
                            $text =
                                'Are you sure you want to mark attendance for ' .
                                $upcomming_masterclass->name .
                                ' masterclass?';
                            $upcomming_masterclass_coach_attendance = App\Models\CoachAttendance::where(
                                'masterclass_id',
                                $upcomming_masterclass->id,
                            )
                                ->where('coach_id', $upcomming_masterclass->coach_id)
                                ->first();
                        @endphp
                        <tr>
                            <td>{{ $upcomming_masterclass->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($upcomming_masterclass->date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($upcomming_masterclass->time)->format('h:i A') }}</td>
                            <td>
                                @if ($upcomming_masterclass_coach_attendance && !empty($upcomming_masterclass_coach_attendance->homework_link))
                                    <span class="text-success">Attendance Marked</span>
                                @elseif($upcomming_masterclass_coach_attendance)
                                    <a href="#" class="btn btn-primary masterclass_attendance_btn me-2"
                                        data-id="{{ $upcomming_masterclass->id }} " data-text="{{ $text }}">Mark
                                        Attendance</a>
                                @else
                                    <a href="#" class="btn btn-primary masterclass_attendance_btn me-2" data-id="{{ $upcomming_masterclass->id }} " data-text="{{ $text }}">Mark Attendance</a>

                                    <a href="{{ $upcomming_masterclass->start_url }}"
                                        data-attendance-url="{{ route('admin.dashboard.mark.pre.master.class.attendance') }}"
                                        class="btn btn-primary-theme-outline masterclass-attendance"
                                        data-zoom-url="{{ $upcomming_masterclass->start_url }}"
                                        data-coach-id="{{ $upcomming_masterclass->coach_id }}"
                                        data-masterclass-id ="{{ $upcomming_masterclass->id }}"
                                        target="_blank">Start</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="empty-state">
        No Upcoming Masterclasses Available.
    </div>
@endif

<br>