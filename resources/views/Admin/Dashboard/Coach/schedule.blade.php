<style>
    .table>:not(caption)>*>* {
        padding: 16px 3px !important;
        color: var(--bs-table-color-state, var(--bs-table-color-type, var(--bs-table-color)));
        background-color: var(--bs-table-bg);
        border-bottom-width: var(--bs-border-width);
        box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)))
    }

    .btn-danger {
        background-color: #dc3545 !important;
    }
</style>
<table
    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
    <thead>
        <tr>
            <th style="text-align: center;">Name</th>
            <th style="width: 20%; text-align: center;">Status</th>
            <th style="text-align: center;">Slot</th>
            <th style="width: 3%; text-align: center;">Type</th>
            <th style="width: 3%; text-align: center;">Students</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $schedule)
            @php
                // dd($schedules);
                $todaysDate = date('Y-m-d');
                $isAttendaceMarked = App\Models\CoachAttendance::where('batch_id', $schedule['id'])
                    ->whereDate('date', $todaysDate)
                    ->exists();

                if($schedule['id'] == '4584'){
                    // dd($isAttendaceMarked);
                }
                // dd($latestAttendance);

            @endphp
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
                            data-btn="statusBtn" data-type="{{ $schedule['type'] }}"
                            style="--bs-btn-padding-x: 10px !important; --bs-btn-padding-y: 1px !important; --bs-btn-border-radius: 4px; font-size: 0.875rem;">
                            {{ $schedule['status'] }}
                        </button>
                    @else
                        {{ $schedule['status'] }}
                    @endif
                </td>
                <td style="text-align: center;">{{ $schedule['slot'] }}</td>
                <td style="text-align: center;">
                    @if ($schedule['type'] == 'Batch' || $schedule['type'] == 'Coverup')
                        <span class="badge" style="background-color: red;">{{ $schedule['type'] }}</span>
                    @elseif($schedule['type'] == 'Demo')
                        <span class="badge" style="background-color: blue;">{{ $schedule['type'] }}</span>
                    @else
                        {{ $schedule['type'] }}
                    @endif
                </td>
                <td style="text-align: center;">{{ $schedule['active_students'] }}</td>

                <td style="text-align: center;">
                    @php
                        $slotParts = explode(' - ', $schedule['slot']);
                        $now = \Carbon\Carbon::now();

                        // Determine correct slot dates
                        $slotDate = $schedule['type'] === 'Yesterday Batch' ? now()->subDay() : now();

                        // Parse start & end times
                        $slotStartTime = \Carbon\Carbon::parse($slotParts[0])->setDate(
                            $slotDate->year,
                            $slotDate->month,
                            $slotDate->day,
                        );
                        $slotEndTime = \Carbon\Carbon::parse($slotParts[1])->setDate(
                            $slotDate->year,
                            $slotDate->month,
                            $slotDate->day,
                        );

                        // If session crosses midnight
                        if ($slotEndTime->lt($slotStartTime)) {
                            $slotEndTime->addDay();
                        }

                        $demoLatestAttendance = null;
                        if ($schedule['type'] === 'Demo') {
                            $demoLatestAttendance = App\Models\CoachAttendance::where('batch_id', $schedule['id'])
                                ->whereDate('date', $todaysDate)
                                ->latest()
                                ->first();

                            // dd($demoLatestAttendance);
                        }

                        $latestAttendance = App\Models\CoachAttendance::where('batch_id', $schedule['id'])
                            ->whereDate('date', $todaysDate)
                            ->latest()
                            ->first();

                        $timeDiff = $now->diffInMinutes($slotStartTime, false); // Negative if now is before start
                        // dd($now->lte($slotEndTime));
                    @endphp

                    {{-- @if ($latestAttendance && $latestAttendance->status === 'COMPLETED') --}}
                    {{-- 1. Yesterday Batch --}}
                    @if (
                        $schedule['type'] === 'Yesterday Batch' &&
                            $isAttendaceMarked &&
                            $schedule['status'] !== 'CANCELLED' &&
                            $now->lte($slotEndTime))
                        <a href="{{ $schedule['start_url'] }}" target="_blank"
                            class="btn btn-primary-theme-outline">Start</a>

                        {{-- 2. Demo session: allow anytime if SCHEDULED --}}
                    @elseif ($schedule['type'] === 'Demo' && $schedule['status'] === 'SCHEDULED')

                            @php
                                $startTime = $slotStartTime; // already calculated
                                $showFrom = $startTime->copy()->subMinutes(10);
                                $showTill = $startTime->copy()->addMinutes(30);
                            @endphp
                            @if ($now->between($showFrom, $showTill))
                                <a href="{{ $schedule['start_url'] }}" target="_blank"
                                    class="btn btn-primary-theme-outline">Start</a>
                            @endif
                        {{-- 3. Attendance already marked (not cancelled, not demo) --}}
                    @elseif ($isAttendaceMarked && $schedule['status'] !== 'CANCELLED')
                        @php
                            $showFrom = $slotStartTime->copy()->subMinutes(10);
                            $showTill = $slotStartTime->copy()->addMinutes(80);
                        @endphp
                    
                        @if ($now->between($showFrom, $showTill))
                            <a href="{{ $schedule['start_url'] }}" target="_blank"
                                class="btn btn-primary-theme-outline">Start</a>
                        @endif

                        {{-- 4. Normal session, not marked yet, allow within 30 minutes before to end --}}
                    @elseif (!$isAttendaceMarked && $schedule['status'] !== 'CANCELLED')

                        @php
                        $showFrom = $slotStartTime->copy()->subMinutes(10);
                        $showTill = $slotStartTime->copy()->addMinutes(80);
                        @endphp
                        
                        @if ($now->between($showFrom, $showTill))
                        <a href="#" data-schedule='@json($schedule)'
                            class="btn btn-primary-theme-outline status-btn"
                            data-type="{{ $schedule['type'] }}"
                            data-id="{{ $schedule['id'] }}"
                            data-btn="startBtn">
                            Start
                        </a>
                        @endif
                    @endif
                    {{-- @endif --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
