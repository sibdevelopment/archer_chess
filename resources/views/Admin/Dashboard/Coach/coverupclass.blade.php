<style>
    .table>:not(caption)>*>* {
        padding: 16px 3px !important;
        color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
        background-color: var(--bs-table-bg);
        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)))
    }
</style>
<table
    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
    <thead>
        <tr>
            <th style="text-align: center;">Name</th>
            <th style="width: 25%; text-align: center;">Status</th>
            <th style="text-align: center;">Slot</th>
            <th style="width: 3%; text-align: center;">Type</th>
            <th style="width: 3%; text-align: center;">Students</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $schedule)

            <tr>
                <td style="text-align: center;">{{ $schedule['name'] }}</td>
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
                            data-type="{{ $schedule['type'] }}"
                            style="--bs-btn-padding-x: 10px !important; --bs-btn-padding-y: 1px !important; --bs-btn-border-radius: 4px; font-size: 0.875rem;">
                            {{ $schedule['status'] }}
                        </button>
                    @else
                        {{ $schedule['status'] }}
                    @endif
                </td>
                <td style="text-align: center;">{{ $schedule['slot'] }}</td>
                <td style="text-align: center;">
                    @if ($schedule['type'] == 'Batch')
                        <span class="badge" style="background-color: red;">{{ $schedule['type'] }}</span>
                    @elseif($schedule['type'] == 'Demo')
                        <span class="badge" style="background-color: blue;">{{ $schedule['type'] }}</span>
                    @else
                        {{ $schedule['type'] }}
                    @endif
                </td>
                <td style="text-align: center;">{{ $schedule['active_students'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
