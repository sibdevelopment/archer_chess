<style>
    .table>:not(caption)>*>* {
        padding: 16px 5px !important;
        color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
        background-color: var(--bs-table-bg);
        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
    }

    .rounded-table {
        border-radius: 15px;
        overflow: hidden;
    }

    .rounded-table th,
    .rounded-table td {
        border: 1px solid #dee2e6;
        /* Keep internal borders */
    }
</style>

<table
    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg rounded-table">
    <thead>
        <tr>
            @php
                use Carbon\Carbon;
                $formattedDate = Carbon::parse($date)->setTimezone('Asia/Kolkata')->format('d M, Y');
            @endphp
            <th colspan="6" style="text-align: center; font-size: 1em; padding: 10px;">{{ $formattedDate }} - Today's
                Schedule </th>
        </tr>
        <tr>
            <th style="width: 3%; text-align: center;">Actions</th>
            <th style="text-align: center;">Name</th>
            <th style="text-align: center;"> Slot</th>
            <th style="width: 3%; text-align: center;">Session Status</th>
            <th style="width: 3%; text-align: center;">Type</th>
            <th style="width: 5%; text-align: center;">Students</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $schedule)
            
            <tr>
                <td style="text-align: center;">
                    @if ($schedule['type'] == 'Batch')
                        @php
                            $batch_id = $schedule['id'];
                            $batch = App\Models\Batch::find($batch_id);
                        @endphp
                        <a href="{{ route('admin.batchs.show', ['batch' => $batch->route_key]) }}"
                            class="badge bg-info fs-1 modal-one-btn" data-entity="batchs" data-title="Batch Details"
                            data-route-key="{{ $batch->route_key }}">
                            <i class="fa fa-eye"></i>
                        </a>
                    @else
                        @if ($schedule['id'] !== null)
                            <a href="" class="badge bg-info fs-2 schedule-data-modal-btn"
                                data-entity="{{ $schedule['type'] }}" data-title="{{ $schedule['type'] }} Details"
                                data-route-key="{{ $schedule['id'] }}"
                                data-url="{{ route('admin.reports.scheduleData') }}">
                                <i class="fa fa-eye fs-1"></i>
                            </a>
                        @endif
                    @endif
                </td>
                <td style="text-align: center;">{{ $schedule['name'] }}</td>
                <td style="text-align: center;">{{ $schedule['slot'] }}</td>
                <td style="text-align: center;">
                    @if (isset($schedule['id']) && isset($schedule['type']))
                        @php
                            switch ($schedule['status']) {
                                case 'SCHEDULED':
                                    $badgeColor = 'primary';
                                    break;
                                case 'RESCHEDULED':
                                    $badgeColor = 'warning';
                                    break;
                                case 'DEMO DONE':
                                    $badgeColor = 'success';
                                    break;
                                case 'CANCELLED':
                                    $badgeColor = 'danger';
                                    break;
                                case 'CONVERTED':
                                    $badgeColor = 'info';
                                    break;
                                case 'ROWLEAD':
                                    $badgeColor = 'secondary';
                                    break;
                                case 'INTERESTED':
                                    $badgeColor = 'success';
                                    break;
                                default:
                                    $badgeColor = 'primary';
                            }
                        @endphp
                        <button class="btn btn-{{ $badgeColor }} status-btn" data-id="{{ $schedule['id'] }}"
                            data-type="{{ $schedule['type'] }}" data-date="{{ $schedule['date'] ?? '' }}"
                            style="--bs-btn-padding-x: 5px !important; --bs-btn-padding-y: 1px !important; --bs-btn-border-radius: 4px; font-size: 0.875rem;">
                            {{ $schedule['status'] }}
                        </button>
                    @else
                        {{ $schedule['status'] }}
                    @endif
                </td>
                <td style="text-align: center;">
                    @if ($schedule['type'] == 'Batch' || $schedule['type'] == 'Coverup')
                        @if (($schedule['is_one_to_one'] ?? false) && $schedule['type'] == 'Batch')
                            <span class="badge" style="background-color: #0f766e;">1-1 Batch</span>
                        @elseif ($schedule['batchStatus'] == 'ACTIVE')
                            <span class="badge bg-danger">{{ $schedule['type'] }}</span>
                        @elseif($schedule['batchStatus'] == 'INACTIVE')
                            <span class="badge" style="background-color: purple;">{{ $schedule['type'] }}</span>
                        @elseif($schedule['batchStatus'] == 'STANDBY')
                            <span class="badge" style="background-color: black;">{{ $schedule['type'] }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $schedule['type'] }}</span>
                        @endif
                    @elseif($schedule['type'] == 'Demo')
                        <span class="badge" style="background-color: blue;">{{ $schedule['type'] }}</span>
                    @elseif($schedule['type'] == 'Available')
                        <span class="badge bg-success">{{ $schedule['type'] }}</span>
                    @else
                        {{ $schedule['type'] }}
                    @endif

                </td>
                <td style="text-align: center;">
                    @if ($schedule['type'] == 'Batch' || $schedule['type'] == 'Coverup')
                        <span class="badge bg-secondary">{{ $schedule['active_students'] }}</span>
                    @elseif($schedule['type'] == 'Demo')
                        <span class="badge" style="background-color: blue;">{{ $schedule['active_students'] }}</span>
                    @elseif($schedule['type'] == 'Available')
                        <span class="badge bg-success"></span>
                    @else
                        {{ $schedule['type'] }}
                    @endif
                </td> <!-- Dynamic student count -->
            </tr>
        @endforeach
    </tbody>
</table>

<table
    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg rounded-table">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 1em; padding: 10px;">{{ $formattedDate }} -
                Today's Masterclass
                Schedule </th>
        </tr>
        <tr>
            <th width="5%;" style="text-align: center;">#</th>
            <th style="text-align: center;">Name</th>
            <th width="15%;" style="text-align: center;"> Date</th>
            <th width="15%;" style="text-align: center;">Time</th>
            <th width="15%;" style="text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($masterclassData as $masterclass)
            @php
                $masterclass = App\Models\Masterclass::find($masterclass['id']);
                $text = 'Are you sure you want to mark attendance for ' . $masterclass->name . ' masterclass?';

                $upcomming_masterclass_coach_attendance = App\Models\CoachAttendance::where(
                    'masterclass_id',
                    $masterclass['id'],
                )
                    ->where('coach_id', $masterclass->coach_id)
                    ->first();
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ $masterclass->name }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($masterclass->date)->format('d M, Y') }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($masterclass->time)->format('h:i A') }}</td>
                <td>
                    @if ($upcomming_masterclass_coach_attendance)
                        <span class="text-success">Attendance Marked</span>
                    @else
                        <a href="#" class="btn btn-primary masterclass_attendance_btn"
                            data-id="{{ $masterclass['id'] }} " data-text="{{ $text }}">Mark
                            Attendance</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
