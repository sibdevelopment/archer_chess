@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .scrollable-container {
            max-height: 180px;
            /* Adjust the height as needed */
            overflow-y: auto;
            padding: 10px;
            /* Optional: adds spacing */
            border-radius: 5px;
            /* Optional: adds rounded corners */
        }
    </style>
    @php
        // dd($student);
    @endphp
    <!-- ------------------------------------------------------------------ :: -->
    <!-- ------------------------------------------------------------------ :: -->
    <div class="card overflow-hidden" style="background-color: #F5F5F5 !important;">
        <div class="card-body p-0"
            style="box-shadow: rgb(50 50 93 / 0%) 0px 30px 60px -12px inset, rgb(0 0 0 / 10%) 0px 18px 36px -18px inset;">
            <div class="row align-items-center">
                <!-- ------------------------------------------------------------------ :: -->
                <div class="col-lg-4">
                    <div class="" style="margin-top: 0.5rem !important;">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 110px; height: 110px;" ;>
                                <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                                    style="width: 100px; height: 100px;" ;>
                                    @php
                                        if (!empty($student) && !empty($student->image)) {
                                            $image = \Storage::url($student->image);
                                        } else {
                                            $image = '/backend/dist/images/profile/user-1.jpg';
                                        }
                                    @endphp
                                    <img src="{{ $image }}" alt="" class="w-100 h-100">
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <h5 class="fs-5 mb-0 fw-semibold">
                                @if (!empty($student))
                                    Welcome back &nbsp;{{ isset($student->first_name) ? $student->first_name : '' }}!
                                @else
                                    @if (!empty($data['demoLeadEnquiry']))
                                        Welcome back
                                        &nbsp;{{ isset($data['demoLeadEnquiry']->kids_first_name) ? $data['demoLeadEnquiry']->kids_first_name : '' }}!
                                    @endif
                                @endif
                            </h5>
                            <p class="mb-0 fs-4">
                                Student
                            </p>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------------ :: -->
                <div class="col-lg-8">
                    @if (!empty($data['demoLeadEnquiry']) && empty($student))
                        <div class="message-container text-center"
                            style="text-align: center; margin-left:0px; margin-top: 0px;">
                            @if ($data['demoLeadEnquiry']->status == 'ACTIVE')
                                <div class="alert"
                                    style="background-color: #28a745; color: white; padding: 6px 09px; border-radius: 5px;">
                                    <strong>Success!</strong> Your enquiry has been submitted.
                                </div>
                            @elseif($data['demoLeadEnquiry']->status == 'CONVERTED')
                                <div class="alert"
                                    style="background-color: #007bff; color: white; padding: 6px 09px; border-radius: 5px;">
                                    <strong>Congratulations!</strong> You have been converted to the demo.
                                </div>
                            @elseif($data['demoLeadEnquiry']->status == 'REJECTED')
                                <div class="alert"
                                    style="background-color: #dc3545; color: white; padding: 6px 09px; border-radius: 5px;">
                                    <strong>Sorry!</strong> You are not applicable, so your enquiry has been
                                    <b>REJECTED</b>.
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="d-flex align-items-center justify-content-between m-3">
                        <div class="text-center">
                            <h5 class="fw-semibold lh-1">Mobile</h5>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti ti-phone fs-6 d-block text-theme"></i>
                                @if (!empty($student))
                                    <p class="mb-0 text-dark fs-4">{{ isset($student->mobile) ? $student->mobile : '' }}
                                    </p>
                                @else
                                    @if (!empty($data['demoLeadEnquiry']))
                                        <p class="mb-0 text-dark fs-4">
                                            {{ isset($data['demoLeadEnquiry']->mobile) ? $data['demoLeadEnquiry']->mobile : '' }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="fw-semibold lh-1">Country</h5>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti ti-home fs-6 d-block text-theme"></i>
                                @if (!empty($student))
                                    <p class="mb-0 text-dark fs-4">{{ isset($student->country) ? $student->country : '' }}
                                    </p>
                                @else
                                    @if (!empty($data['demoLeadEnquiry']))
                                        <p class="mb-0 text-dark fs-4">
                                            {{ isset($data['demoLeadEnquiry']->country) ? $data['demoLeadEnquiry']->country : '' }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @php
                            // dd($latestActiveBatch->level->name);
                        @endphp
                        <div class="text-center">
                            <h5 class="fw-semibold lh-1">Level</h5>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti ti-chart-arrows-vertical fs-6 d-block text-theme"></i>

                                @if (!empty($student))
                                    @php
                                        $latestActiveBatch = $student->studentBatches
                                            ->where('status', 'ACTIVE')
                                            ->sortByDesc('created_at')
                                            ->first();
                                    @endphp
                                    <p class="mb-0 text-dark fs-4">

                                        {{ isset($latestActiveBatch->level) ? $latestActiveBatch->level->name : '' }}
                                    @else
                                        @if (!empty($data['demoLeadEnquiry']))
                                            <p class="mb-0 text-dark fs-4">
                                                {{ isset($data['demoLeadEnquiry']->level) ? $data['demoLeadEnquiry']->level : '' }}
                                            </p>
                                        @endif
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-semibold lh-1">Age</h5>
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti ti-stretching fs-6 d-block text-theme"></i>
                                @if (!empty($student))
                                    <p class="mb-0 text-dark fs-4">{{ isset($student->age) ? $student->age : '' }}</p>
                                @else
                                    @if (!empty($data['demoLeadEnquiry']))
                                        <p class="mb-0 text-dark fs-4">
                                            {{ isset($data['demoLeadEnquiry']->age) ? $data['demoLeadEnquiry']->age : '' }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </div>
                    @if ($student)
                        <div class="d-flex align-items-center justify-content-between m-3">
                            <div class="text-center">
                                <h5 class="fw-semibold lh-1">Chesslang Id</h5>
                                <div class="row align-items-center gap-1">
                                    @if (!empty($student))
                                        <p class="mb-0 text-dark fs-4">
                                            {{ isset($student->student_id) ? $student->student_id : '' }}</p>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="fw-semibold lh-1">Chesslang Password</h5>
                                <div class="row align-items-center gap-1">
                                    @if (!empty($student))
                                        <p class="mb-0 text-dark fs-4">
                                            {{ isset($student->portal_password) ? $student->portal_password : '' }}</p>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="fw-semibold lh-1">Email</h5>
                                <div class="row align-items-center gap-1">
                                    @if (!empty($student))
                                        <p class="mb-0 text-dark fs-4">{{ isset($student->email) ? $student->email : '' }}
                                        </p>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <h2></h2>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @php
                //  dd($student);
            @endphp
            <!-- ------------------------------------------------------------------ :: -->
            <div class="d-flex justify-content-between ps-5">
                <div class="pb-2">
                    @if ($student)
                        <a href="https://app.chesslang.com/login" class="btn btn-primary-theme mt-3 ms-5" target="_blank">
                            Join Chesslang
                            &nbsp;
                            <i class="ti ti-plus"></i>
                        </a>
                    @endif

                </div>
                <ul class="nav nav-pills user-profile-tab justify-content-end rounded-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-batch-tab" data-bs-toggle="pill" data-bs-target="#pills-batch" type="button"
                            role="tab" aria-controls="pills-batch" aria-selected="false">
                            <i class="ti ti-school me-2 fs-6"></i>
                            <span class="d-none d-md-block">Home</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                            id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-feedback" type="button"
                            role="tab" aria-controls="pills-feedback" aria-selected="true">
                            <i class="ti ti-stars me-2 fs-6"></i>
                            <span class="d-none d-md-block">Feedback</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-end">
            <p class="recording-alert text-end">The recording will be available for only <strong>24 hours</strong>. Please
                download it.</p>
        </div>
    </div>

    <style>
        .recording-alert {
            font-size: 18px;
            font-weight: 600;
            color: #d9534f;
            /* Red color for urgency */
            background: #fff3cd;
            /* Light yellow background */
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 5px solid #d9534f;
            /* Red left border */
            display: inline-block;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- ------------------------------------------------------------------ :: -->
    <div class="tab-content" id="pills-tabContent">
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade show active" id="pills-batch" role="tabpanel" aria-labelledby="pills-student-tab"
            tabindex="0">
            <div class="row">
                @if (!empty($firstMatchingClass) && isset($firstMatchingClass))
                    @php

                        // Current day and time
                        $currentDay = \Carbon\Carbon::now()->format('l'); // E.g., "Thursday"
                        $currentTime = \Carbon\Carbon::now(); // Current date and time
                        $currentDate = \Carbon\Carbon::now()->toDateString();

                        $latestCoachAttendance = \App\Models\CoachAttendance::where('batch_id', $firstMatchingClass->batch_id)
                            ->whereDate('date', $currentDate)
                            ->latest()
                            ->first();

                        $next_comming_day = $firstMatchingClass['weekday'];
                        $next_comming_from_time = $firstMatchingClass['from_time'];
                        $next_comming_to_time = $firstMatchingClass['to_time'];

                        // dd($firstMatchingClass, $currentDay, $currentTime, $next_comming_day, $next_comming_from_time, $next_comming_to_time);

                        //Find next class day
                        $nextClassDay = $next_comming_day;
                        $nextClassTime = $next_comming_from_time;
                        $nextClassEndTime = $next_comming_to_time;

                        $allDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                        $currentDayIndex = array_search($currentDay, $allDays);
                        $nextClassDayIndex = array_search($nextClassDay, $allDays);

                        $daysUntilNextClass = $nextClassDayIndex - $currentDayIndex;

                        if ($daysUntilNextClass < 0) {
                            $daysUntilNextClass += 7;
                        }

                        $nextClassDate = $currentTime->copy()->addDays($daysUntilNextClass);

                        $batch = $firstMatchingClass->batch;
                        $coach = $batch->coach;

                    @endphp

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body pb-0 d-flex align-items-center justify-content-between">
                                @if (isset($coverup_class) && !empty($coverup_class))
                                    <h4 class="card-title fw-semibold">Upcoming Coverup Class</h4>
                                @else
                                    <h4 class="card-title fw-semibold">Upcoming Class</h4>
                                @endif
                                @php
                                    $classStart = \Carbon\Carbon::parse(
                                        $nextClassDate->format('Y-m-d') . ' ' . $next_comming_from_time
                                    );

                                    // Show weekday in student's timezone/country.
                                    $studentLocalClassDate = convertTimeZomeWiseDate(
                                        \Carbon\Carbon::parse($nextClassDate)->format('d, M Y'),
                                        \Carbon\Carbon::parse($nextClassTime)->format('h:i A'),
                                        $student->id,
                                    );
                                    try {
                                        $studentLocalWeekday = \Carbon\Carbon::createFromFormat(
                                            'd, M Y',
                                            $studentLocalClassDate
                                        )->format('l');
                                    } catch (\Exception $e) {
                                        $studentLocalWeekday = $firstMatchingClass['weekday'];
                                    }
                                
                                    $showFrom = $classStart->copy()->subMinutes(10);
                                    $showTill = $classStart->copy()->addMinutes(80);
                                
                                    $now = \Carbon\Carbon::now();
                                @endphp
                                @if ($latestCoachAttendance && $latestCoachAttendance->status == 'CANCELLED')
                                    <span>Class cancelled for today</span>
                                @else
                                    @if ($firstMatchingClass['weekday'] == $currentDay && $now->between($showFrom, $showTill))                                    
                                        <span>({{ $studentLocalWeekday }})</span>
                                        @if (isset($coverup_class) && !empty($coverup_class))
                                            @php $joinFallbackUrl = route('admin.student-dashboard.join-class') . '?id=' . $coverup_class->batch_id . '&student_id=' . $student->id . '&join_url=' . rawurlencode($coverup_class->join_url ?? ''); @endphp
                                            <a href="{{ $joinFallbackUrl }}" class="btn btn-primary-theme-outline joinBtn" data-type="COVERUP" data-id="{{ $coverup_class->batch_id }}" data-studentid="{{ $student->id }}" data-join_url="{{ $coverup_class->join_url }}" target="_blank">
                                                Join &nbsp;
                                            </a>
                                        @else
                                            @php $joinFallbackUrl = route('admin.student-dashboard.join-class') . '?id=' . $batch->id . '&student_id=' . $student->id . '&join_url=' . rawurlencode($batch->join_url ?? ''); @endphp
                                            <a href="{{ $joinFallbackUrl }}" class="btn btn-primary-theme-outline joinBtn" data-type="BATCH" data-id="{{ $batch->id }}" data-studentid="{{ $student->id }}" data-join_url="{{ $batch->join_url }}" target="_blank">
                                                Join &nbsp;
                                            </a>
                                        @endif
                                        @else
                                            <span>({{ $studentLocalWeekday }})</span>
                                        
                                            @if ($now->lt($showFrom))
                                                <span style="color: orange;">
                                                    Class not started yet, please try again later
                                                </span>
                                            @elseif ($now->gt($showTill))
                                                {{-- After class window → nothing or optional --}}
                                            @else
                                                <span style="color: blue;">
                                                    Days until class: {{ $daysUntilNextClass }}
                                                </span>
                                            @endif
                                        @endif
                                @endif
                            </div>
                            <ul class="feeds ps-0">
                                <div class="feed-item my-2 py-2 pe-3 ps-4">
                                    @if (!empty($firstMatchingClass))
                                        <div class=" border-start border-2 border-info d-md-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0)"
                                                    class="ms-3 btn btn-light-info text-info btn-circle fs-5 d-flex align-items-center justify-content-center flex-shrink-0">
                                                    <i class="ti ti-chess fs-6"></i></a>
                                                <div class="ms-3 text-truncate">
                                                    <p class="text-dark font-weight-medium fs-4 mb-0">
                                                        @if (isset($firstMatchingClass->batch) && !empty($firstMatchingClass->batch))
                                                            @if (isset($coverup_class) && !empty($coverup_class))
                                                                {{ ucwords($coverup_class->newCoach->user->full_name) }}
                                                            @else
                                                                {{ isset($firstMatchingClass->batch->coach->user) && !empty($firstMatchingClass->batch->coach->user) ? ucwords($firstMatchingClass->batch->coach->user->full_name) : '' }}
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            @php

                                                $convertedDate = convertTimeZomeWiseDate(
                                                    \Carbon\Carbon::parse($nextClassDate)->format('j, M Y'),
                                                    \Carbon\Carbon::parse($nextClassTime)->format('g:i A'),
                                                    $student->id,
                                                );

                                                $convertedFromTime = convertTimeZomeWiseTime(
                                                    \Carbon\Carbon::parse($nextClassDate)->format('j, M Y'),
                                                    \Carbon\Carbon::parse($nextClassTime)->format('g:i A'),
                                                    $student->id,
                                                );

                                                $convertedToTime = convertTimeZomeWiseTime(
                                                    \Carbon\Carbon::parse($nextClassDate)->format('j, M Y'),
                                                    \Carbon\Carbon::parse($nextClassEndTime)->format('g:i A'),
                                                    $student->id,
                                                );
                                            @endphp

                                            <div class="justify-content-end text-truncate ms-5 ms-md-auto ps-4 ps-md-0">
                                                <span><i class="ti ti-calendar text-info mr-2"></i></span>
                                                <span class="fs-2 fw-medium text-info">
                                                    {{ $convertedFromTime }} - {{ $convertedToTime }}
                                                    | {{ $convertedDate }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($student)
                    <div class="col-lg-6 holiday-container">
                        <div class="card-body pb-0 d-flex align-items-center justify-content-between">
                            <h4 class="card-title fw-semibold text-black">Upcoming Holidays</h4>
                        </div>
                        <div class="holiday-list scrollable-container mt-2">
                            @if (!empty($holidays) && $holidays->isNotEmpty())
                                @foreach ($holidays as $key => $holiday)
                                    @php
                                        $from_date = convertTimeZomeWiseDate(
                                            \Carbon\Carbon::parse($holiday->start_date)->format('j, M Y'),
                                            '00:00 AM',
                                            $student->id,
                                        );

                                        $to_date = convertTimeZomeWiseDate(
                                            \Carbon\Carbon::parse($holiday->end_date)->format('j, M Y'),
                                            '00:00 AM',
                                            $student->id,
                                        );

                                    @endphp
                                    <div class="holiday-item">
                                        <div class="holiday-number">{{ $key + 1 }})</div>
                                        <div class="holiday-details row">
                                            <div class="holiday-name">{{ $holiday->name }}
                                                <div class="holiday-date">
                                                    ({{ $from_date }}
                                                    @if ($holiday->end_date)
                                                        /
                                                        {{ $to_date }}
                                                    @endif)
                                                </div>
                                            </div>
                                            <div class="holiday-description">
                                                <p>{{ Str::limit($holiday->description, 30) }}
                                                    @if (strlen($holiday->description) > 30)
                                                        <a href="javascript:void(0);" class="read-more"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#holidayModal{{ $holiday->id }}">Read
                                                            More</a>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal for Holiday Description -->
                                    <div class="modal fade" id="holidayModal{{ $holiday->id }}" tabindex="-1"
                                        aria-labelledby="holidayModalLabel{{ $holiday->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="holidayModalLabel{{ $holiday->id }}">
                                                        {{ $holiday->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ $holiday->description }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="holiday-name">No upcoming holidays</div>
                            @endif
                        </div>
                    </div>
                @endif


                <div class="row col-md-12 mt-3 row">
                    <!-- Upcoming Master Class -->
                    @if (isset($masterclassedata) && !empty($masterclassedata))
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body pb-0 d-flex align-items-center justify-content-between">
                                    <h4 class="card-title fw-semibold">Upcoming Master Class</h4>
                                    <a href="/admin/student-masterclasses" class=" btn-sm text-primary text-end"
                                        style="text-decoration: underline" target="_blank">
                                        View More
                                    </a>
                                </div>
                                <ul class="feeds ps-0">
                                    @foreach ($masterclassedata as $masterclass)
                                        <div class="feed-item my-2 py-2 pe-3 ps-4">
                                            <div class="border-start border-2 border-warning d-md-flex align-items-center">
                                                <div class="d-flex align-items-center w-100">
                                                    <a href="javascript:void(0)"
                                                        class="ms-3 btn btn-light-warning text-warning btn-circle fs-5 d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="fas fa-chalkboard-teacher fs-6"></i>
                                                    </a>
                                                    <div class="ms-3 text-truncate flex-grow-1">
                                                        <p class="text-dark font-weight-medium fs-5 mb-1 text-truncate">
                                                            {{ $masterclass->name }}
                                                            ({{ $masterclass->coach->user->full_name }})
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fs-4 fw-medium text-warning">
                                                                {{ isset($masterclass->time)
                                                                    ? convertTimeZomeWiseTime(
                                                                        \Carbon\Carbon::parse($masterclass->date)->format('j, M Y'),
                                                                        \Carbon\Carbon::parse($masterclass->time)->format('g:i A'),
                                                                        $student->id,
                                                                    )
                                                                    : '' }}
                                                                |{{ isset($masterclass->date)
                                                                    ? convertTimeZomeWiseDate(
                                                                        \Carbon\Carbon::parse($masterclass->date)->format('j, M Y'),
                                                                        \Carbon\Carbon::parse($masterclass->time)->format('g:i A'),
                                                                        $student->id,
                                                                    )
                                                                    : '' }}
                                                            </span>
                                                            @php
                                                                $time = \Carbon\Carbon::parse(
                                                                    $masterclass->time,
                                                                )->format('H:i'); // Class time (24-hour format)
                                                                $date = \Carbon\Carbon::parse(
                                                                    $masterclass->date,
                                                                )->format('Y-m-d'); // Class date

                                                                $today = \Carbon\Carbon::now()->format('Y-m-d'); // Current date
                                                                $currentTime = \Carbon\Carbon::now()->format('H:i'); // Current time

                                                                // Convert class time to Carbon instance
                                                                $classStartTime = \Carbon\Carbon::createFromFormat(
                                                                    'Y-m-d H:i',
                                                                    "$date $time",
                                                                );

                                                                // Define time range (1 hour before, 1 hour after)
                                                                $startShowTime = $classStartTime->copy()->subHour(); // 1 hour before class
                                                                $endShowTime = $classStartTime->copy()->addHour(); // 1 hour after class

                                                                // Determine if the button should be shown
                                                                $is_btn_show =
                                                                    $today == $date &&
                                                                    $currentTime >= $startShowTime->format('H:i') &&
                                                                    $currentTime <= $endShowTime->format('H:i')
                                                                        ? 1
                                                                        : 0;
                                                            @endphp


                                                            @if ($is_btn_show == 1)
                                                                <a href="{{ $masterclass->join_url }}"
                                                                    class="btn btn-sm btn-primary">Join</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Upcoming Tournament --}}
                    @if (isset($tournamentData) && !empty($tournamentData))
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body pb-0 d-flex align-items-center justify-content-between">
                                    <h4 class="card-title fw-semibold">Upcoming Tournament</h4>

                                    <!-- View More Button -->
                                    <a href="/admin/student-tournaments" class=" btn-sm text-primary text-end"
                                        style="text-decoration: underline" target="_blank">
                                        View More
                                    </a>
                                </div>

                                <ul class="feeds ps-0">
                                    @foreach ($tournamentData as $tournament)
                                        <div class="feed-item my-2 py-2 pe-3 ps-4">
                                            <div class="border-start border-2 border-warning d-md-flex align-items-center">
                                                <div class="d-flex align-items-center w-100">
                                                    <a href="javascript:void(0)"
                                                        class="ms-3 btn btn-light-warning text-warning btn-circle fs-5 d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="fas fa-trophy fs-6"></i>
                                                    </a>
                                                    <div class="ms-3 text-truncate flex-grow-1">
                                                        <p class="text-dark font-weight-medium fs-5 mb-1 text-truncate">
                                                            {{ isset($tournament) ? $tournament->name : '' }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fs-4 fw-medium text-warning">
                                                                {{ isset($tournament->time)
                                                                    ? convertTimeZomeWiseTime(
                                                                        \Carbon\Carbon::parse($tournament->date)->format('j, M Y'),
                                                                        \Carbon\Carbon::parse($tournament->time)->format('g:i A'),
                                                                        $student->id,
                                                                    )
                                                                    : '' }}
                                                                |{{ isset($tournament->date)
                                                                    ? convertTimeZomeWiseDate(
                                                                        \Carbon\Carbon::parse($tournament->date)->format('j, M Y'),
                                                                        \Carbon\Carbon::parse($tournament->time)->format('g:i A'),
                                                                        $student->id,
                                                                    )
                                                                    : '' }}
                                                            </span>
                                                            <a href="{{ $tournament->link }}"
                                                                class="btn btn-sm btn-primary">Join</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>


                {{-- Batch Details --}}
                <div class="col-12">
                    <!-- Card -->
                    <div class="card">
                        @if (!empty($student->studentBatches) && $student->studentBatches->isNotEmpty())
                            @php
                                $latestActiveBatch = $student->studentBatches
                                    ->where('status', 'ACTIVE')
                                    ->sortByDesc('created_at')
                                    ->first();
                                $studentbatch = isset($latestActiveBatch->batch) ? $latestActiveBatch->batch : ' ';
                            @endphp
                            @if (!empty($studentbatch) && is_object($studentbatch))
                                <div class="card-body">
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        <div class="mt-2">
                                            <h3 class="font-weight-medium fs-5 mb-1">Batch Details</h3>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col border-end text-center">
                                            <h2 class="fs-3">
                                                {{ \Carbon\Carbon::parse($studentbatch->start_date)->format('d-M-Y') }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Start Date</h6>
                                        </div>
                                        <div class="col border-end text-center">
                                            <h2 class="fs-3">
                                                {{ \Carbon\Carbon::parse($studentbatch->end_date)->format('d-M-Y') }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">End Date</h6>
                                        </div>
                                        <div class="col border-end text-center">
                                            <h2 class="fs-3">
                                                {{ isset($studentbatch->name) ? $studentbatch->name : '' }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Batch</h6>
                                        </div>
                                        <div class="col border-end text-center">
                                            <h2 class="fs-3">
                                                {{ isset($latestActiveBatch->coach->user) ? $latestActiveBatch->coach->user->full_name : '' }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Coach</h6>
                                        </div>
                                        <div class="col border-end text-center">
                                            <h2 class="fs-3">
                                                {{ isset($latestActiveBatch->level) ? $latestActiveBatch->level->name : '' }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Level</h6>
                                        </div>
                                        @php
                                            // dd($latestActiveBatch->status);
                                        @endphp
                                        <div class="col border-end text-center">
                                            @if (!empty($latestActiveBatch->status))
                                                @if ($latestActiveBatch->status == 'ACTIVE')
                                                    <h2 class="fs-3 text-success badge text-end mb-2">Active</h2>
                                                @elseif ($latestActiveBatch->status == 'STANDBY')
                                                    <h2 class="fs-3 text-warning badge text-end mb-2">Standby</h2>
                                                @elseif ($latestActiveBatch->status == 'INACTIVE')
                                                    <h2 class="fs-3 text-danger badge text-end mb-2">Inactive</h2>
                                                @endif
                                            @endif
                                            <h6 class="mb-0 text-muted">Status</h6>
                                        </div>

                                        <div class="col border-end text-center">
                                            @php
                                                $lastSessionRecord = App\Models\StudentAttendance::where(
                                                    'student_id',
                                                    $student->id,
                                                )
                                                    ->where('batch_id', $latestActiveBatch->batch_id)
                                                    ->latest('date')
                                                    ->first();
                                                $sessionsAttended = $lastSessionRecord
                                                    ? $lastSessionRecord->number_of_batch_sessions
                                                    : 0;
                                            @endphp
                                            <h2 class="fs-3">
                                                {{ $sessionsAttended }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Number of Sessions</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endif
                    </div>
                </div>

                {{-- Student Fees --}}

                @if (isset($student->studentFees) && !empty($student->studentFees))
                    @if (!empty($student->studentFees->where('status', 'ACTIVE') && $student->studentFees->isNotEmpty()))
                        <div class="col-12">
                            <!-- Card -->
                            <div class="container-fluid">
                                <div class="product-list">
                                    <div class="card boder-0">
                                        <div class="card-body p-3">
                                            <h5 class="fw-semibold mb-3">
                                                <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Fees Details :
                                            </h5>
                                            <div class="table-responsive border rounded">
                                                <table class="table align-middle text-nowrap mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Start Date</th>
                                                            <th scope="col">End Date</th>
                                                            <th scope="col">Currency</th>
                                                            <th scope="col">Monthly Fee</th>
                                                            <th scope="col">Total Amount Paid</th>
                                                            <th scope="col">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($student->studentFees) && $student->studentFees->isNotEmpty())
                                                            @foreach ($student->studentFees->where('status', 'ACTIVE') as $key => $fees)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>
                                                                        <div class="">
                                                                            <p class="mb-0">
                                                                                {{ isset($fees->start_date) ? Carbon\Carbon::parse($fees->start_date)->format('d-M-Y') : '' }}
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="mb-0">
                                                                            {{ isset($fees->end_date) ? Carbon\Carbon::parse($fees->end_date)->format('d-M-Y') : '' }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="mb-0">
                                                                            {{ isset($fees->currency) ? $fees->currency : '' }}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <h6 class="mb-0 fs-4">
                                                                            {{ isset($fees->monthly_fees) ? $fees->monthly_fees : '' }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        <h6 class="mb-0 fs-4">
                                                                            {{ isset($fees->total_amount_paid) ? $fees->total_amount_paid : '' }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            @if (isset($fees->status) && $fees->status == 'ACTIVE')
                                                                                <span
                                                                                    class="bg-success p-1 rounded-circle"></span>
                                                                                <p class="mb-0 ms-2">{{ $fees->status }}
                                                                                </p>
                                                                            @else
                                                                                <span
                                                                                    class="bg-danger p-1 rounded-circle"></span>
                                                                                <p class="mb-0 ms-2">
                                                                                    {{ !empty($fees->status) ? $fees->status : '' }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if (!empty($data['demoLead']))
                    @php
                        $attendance = \App\Models\CoachAttendance::where(
                            'demolead_id',
                            $data['demoLead']['id'],
                        )->first();
                        $recording_link = $attendance ? $attendance->recording_link : null;
                    @endphp
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if (!empty($data['demoLead']))
                                    <div class="row">
                                        <h3 class="font-weight-medium fs-5 mb-3">Demo Details</h3>
                                        <div class="col">
                                            <h2 class="fs-3">
                                                {{ !empty($data['demoLead']) ? $data['demoLead']->country : '' }}</h2>
                                            <h6 class="mb-0 text-muted">Country</h6>
                                        </div>
                                        <div class="col">
                                            <h2 class="fs-3">
                                                {{ isset($data['demoLead']) ? Carbon\Carbon::parse($data['demoLead']->kids_date)->format('d-M-Y') : '' }}
                                            </h2>
                                            <h6 class="mb-0 text-muted">Scheduled</h6>
                                        </div>
                                        <div class="col">
                                            <h2 class="fs-3">
                                                {{ isset($data['demoLead']) ? $data['demoLead']->kids_time : '' }}</h2>
                                            <h6 class="mb-0 text-muted">Time</h6>
                                        </div>
                                        @if (!empty($data['demoLead']))
                                            <div class="col">
                                                @if (!empty($data['demoLead']) && $data['demoLead']->status == 'ACTIVE')
                                                    <span
                                                        class="bg-light-success text-success badge text-end">Active</span>
                                                @else
                                                    <span
                                                        class="bg-light-danger text-danger badge text-end ">{{ $data['demoLead']->status }}</span>
                                                @endif
                                                <h6 class="mb-0 text-muted"></h6>
                                            </div>
                                        @endif
                                        @if (!empty($recording_link))
                                            <div class="col">
                                                <a href="{{ !empty($recording_link) ? $recording_link : '#' }}"
                                                    target="_blank">
                                                    <button
                                                        class="btn btn-outline-danger py-1 px-2 ms-auto">Recording</button>
                                                </a>
                                                {{-- <span
                                                        class="bg-light-success text-success badge text-end">{{ $recording_link}}</span> --}}
                                                <h6 class="mb-0 text-muted"></h6>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                {{-- </div> --}}
                                <hr>
                                @if (!empty($data['demoLead']->demosessions))
                                    @php
                                        $demosessions = $data['demoLead']->demosessions;
                                    @endphp
                                    @if ($demosessions->isNotEmpty())
                                        <h3 class="font-weight-medium fs-5 mb-3">Demo Session</h3>
                                        @foreach ($demosessions as $key => $demosession)
                                            @php
                                                $convertedTime = convertDemoTimeZomeWiseTime(
                                                    \Carbon\Carbon::parse($demosession->date)->format('j, M Y'),
                                                    \Carbon\Carbon::parse($demosession->time)->format('g:i A'),
                                                    $demosession,
                                                );

                                                $convertedDate = convertDemoTimeZomeWiseDate(
                                                    \Carbon\Carbon::parse($demosession->date)->format('j, M Y'),
                                                    \Carbon\Carbon::parse($demosession->time)->format('g:i A'),
                                                    $demosession,
                                                );
                                            @endphp
                                            <div class="row mt-4">
                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">
                                                        {{ !empty($demosession->coach->user) ? $demosession->coach->user->full_name : '' }}
                                                    </h2>
                                                    <h6 class="mb-0 text-muted">Coach</h6>
                                                </div>
                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">
                                                        {{ !empty($demosession->date) ? $convertedDate : '' }}
                                                    </h2>
                                                    <h6 class="mb-0 text-muted">Date</h6>
                                                </div>
                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">
                                                        {{ !empty($demosession->time)
                                                            ? $convertedTime
                                                            : // $demosession->time
                                                            '' }}
                                                    </h2>
                                                    <h6 class="mb-0 text-muted">Time</h6>
                                                </div>
                                                @php
                                                    // Check if $demosession->slot is not empty
                                                    $slot = !empty($demosession->slot) ? $demosession->slot : '';

                                                    // If slot exists, we will split it into from-time and to-time and convert them
                                                    if ($slot) {
                                                        [$fromTime, $toTime] = explode(' - ', $slot);

                                                        // Convert the time using your helper function for timezones
                                                        $convertedFromTime = convertDemoTimeZomeWiseTime(
                                                            \Carbon\Carbon::parse($demosession->date)->format('j, M Y'),
                                                            \Carbon\Carbon::parse($fromTime)->format('g:i A'),
                                                            $demosession,
                                                        );

                                                        $convertedToTime = convertDemoTimeZomeWiseTime(
                                                            \Carbon\Carbon::parse($demosession->date)->format('j, M Y'),
                                                            \Carbon\Carbon::parse($toTime)->format('g:i A'),
                                                            $demosession,
                                                        );
                                                    }
                                                @endphp

                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">
                                                        {{ $convertedFromTime ?? '' }} - {{ $convertedToTime ?? '' }}
                                                    </h2>
                                                    <h6 class="mb-0 text-muted">Slot</h6>
                                                </div>

                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">
                                                        {{ !empty($demosession->level) ? $demosession->level->name : '' }}
                                                    </h2>
                                                    <h6 class="mb-0 text-muted">Level</h6>
                                                </div>
                                                <div class="col border-end text-center">
                                                    <h2 class="fs-3">{{ $demosession->status }}</h2>
                                                    <h6 class="mb-0 text-muted">Status</h6>
                                                </div>
                                                @if (!empty($demosession->date) && !empty($demosession->time) && $demosession->status == 'ACTIVE')
                                                    @php
                                                        $start = Carbon\Carbon::createFromFormat(
                                                            'Y-m-d H:i:s',
                                                            $demosession->date . ' ' . $demosession->time
                                                        );
                                                    
                                                        $showFrom = $start->copy()->subMinutes(10); // 7:50 PM
                                                        $showTill = $start->copy()->addMinutes(30); // 8:20 PM
                                                    
                                                        $now = Carbon\Carbon::now();
                                                    @endphp
                                                    @if ($now->between($showFrom, $showTill))
                                                        <div class="col border-end text-center">
                                                            <a href="{{ isset($demosession) ? $demosession->join_url : '#' }}"
                                                                class="btn btn-info mt-3" target="_blank">
                                                                <span class="f-3"> Join&nbsp;now&nbsp;<i
                                                                        class="ti ti-plus"></i></span>
                                                            </a>
                                                            <h6 class="mb-0 text-muted"></h6>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning mt-5 text-center" role="alert">
                                            No demo session assigned. Please Wait .
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($data['demoLeadEnquiry']) && isset($data['demoLeadEnquiry']))
                    @if (!empty($data['demoLeadEnquiry']) && isset($data['demoLeadEnquiry']))
                        <div class="col-12">
                            <div class="card">

                                <div class="card-body">
                                    <h4 class="card-title mb-3">Lead Enquiry</h4>
                                    @if (!empty($data['demoLeadEnquiry']))
                                        @php
                                            $demoleadenquiry = $data['demoLeadEnquiry'];

                                        @endphp
                                        <div class="row">
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->date) ? \Carbon\Carbon::parse($demoleadenquiry->date)->format('d-M-Y') : '' }}
                                                    {{ !empty($demoleadenquiry->ist_date) ? \Carbon\Carbon::parse($demoleadenquiry->ist_date)->format('d-M-Y') : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Date</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->time) ? $demoleadenquiry->time : '' }}
                                                    {{ !empty($demoleadenquiry->ist_time) ? $demoleadenquiry->ist_time : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Time</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->country) ? $demoleadenquiry->country : '' }}

                                                </h2>
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->city) ? $demoleadenquiry->city : '' }}
                                                </h2>
                                                <h2 class="fs-1">
                                                    {{ !empty($demoleadenquiry->timezone) ? $demoleadenquiry->timezone : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Time Zone</h6>
                                            </div>

                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->enrollment_plan) ? ucwords(str_replace('_', ' ', $demoleadenquiry->enrollment_plan)) : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Enrollment Plan</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->language_preference) ? $demoleadenquiry->language_preference : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Language Preference</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->level) ? $demoleadenquiry->level : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Level</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->duration) ? ucwords(str_replace('_', ' ', $demoleadenquiry->duration)) : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Duration</h6>
                                            </div>
                                            <div class="col border-end text-center">
                                                <h2 class="fs-2">
                                                    {{ !empty($demoleadenquiry->status) ? $demoleadenquiry->status : '' }}
                                                </h2>
                                                <h6 class="mb-0 text-muted">Status</h6>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                @endif
            </div>
        </div>
        <!-- ------------------------------------------------------------------ :: -->
        <div class="tab-pane fade show" id="pills-feedback" role="tabpanel" aria-labelledby="pills-student-tab"
            tabindex="0" style="display:none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Feedback Form</h5>
                    <form method="POST" action="{{ route('admin.feedbacks.store') }}" enctype="multipart/form-data"
                        autocomplete="off" id="studentfeddback-form">
                        @csrf
                        <div class="row">
                            {{-- //When submite save user id in this table --}}
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        placeholder="Enter Name here" />
                                    <label for="full_name">Name <sup style="color:red;">*</sup></label>
                                </div>
                                <div id="full_name-error" style="margin: 10px; color:red;"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <select class="form-control" id="coach_id" name="coach_id">
                                        <option value="" disabled selected>Select Coach</option>
                                        @foreach ($data['coaches'] as $key => $coach)
                                            @if (isset($coach->user))
                                                <option value="{{ $coach->id }}">{{ $coach->user->first_name }} &nbsp;
                                                    {{ $coach->user->last_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="tb-fname">Coach <sup style="color:red;">*</sup></label>
                                </div>
                                <div id="coach_id-error" style="margin: 10px; color:red;"></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="Message" name="feedback" id="feedback" style="height: 100px"></textarea>
                                    <label for="tb-fname">Feedback Message <sup style="color:red;">*</sup></label>
                                </div>
                                <div id="feedback-error" style="margin: 10px; color:red;"></div>
                            </div>
                            <div class="col-12">
                                <input type="submit" class="btn btn-primary-theme" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ------------------------------------------------------------------ :: -->
    </div>
    <!-- ------------------------------------------------------------------ :: -->

    <!-- Modal -->
    <div class="modal fade" id="fees-due" tabindex="-1" aria-labelledby="fees-dueLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fees-dueLabel">Fees Due</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Fees is due. Please pay the fees to continue your classes. --}}
                    Fees is due. Please Contact Admin to pay the fees.
                </div>
                <div class="modal-footer justify-content-center">
                    <a href="/admin/student-fees" class="btn btn-primary px-4 py-2 rounded-pill">
                        <i class="bi bi-wallet2 me-1"></i> Pay Fees
                    </a>
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------------------------ :: -->


    <script>
        // Event delegation: works on mobile/tablet and when DOM is ready at any time
        $(document).on('click', '.joinBtn', function(e) {
            e.preventDefault();

            var $btn = $(this);
            var type = $btn.data('type');
            var id = $btn.data('id');
            var studentId = $btn.data('studentid');
            var joinUrl = $btn.data('join_url');
            var fallbackHref = $btn.attr('href');

            if (!joinUrl) {
                if (fallbackHref) window.location.href = fallbackHref;
                return;
            }

            var newWindow = null;
            try {
                newWindow = window.open('', '_blank');
                if (newWindow) {
                    newWindow.document.write('<html><head><title>Loading...</title></head><body><h2>Please wait, joining class...</h2></body></html>');
                }
            } catch (err) {}

            $.ajax({
                url: '{{ route("admin.student-dashboard.mark-attendance") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    id: id,
                    student_id: studentId,
                },
                success: function(response) {
                    if (response.status === 'error') {
                        if (newWindow) try { newWindow.close(); } catch (e) {}
                        var errorMsg = response.message || "Something went wrong!";
                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMsg, '', { showMethod: "slideDown", hideMethod: "slideUp", timeOut: 3000, closeButton: true });
                        }
                        return;
                    }
                    if (newWindow) {
                        try {
                            newWindow.location.href = joinUrl;
                        } catch (e) {
                            window.location.href = joinUrl;
                        }
                    } else {
                        // Mobile/tablet: popup often blocked – use same tab
                        window.location.href = joinUrl;
                    }
                },
                error: function(xhr) {
                    if (newWindow) try { newWindow.close(); } catch (e) {}
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : (xhr.status === 403 ? "Access denied. Please try again." : "Something went wrong!");
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg, '', { showMethod: "slideDown", hideMethod: "slideUp", timeOut: 3000, closeButton: true });
                    }
                }
            });
        });
    </script>

    <script>

        

        
        $(document).ready(function() {
            @if (isset($is_fees_due) && $is_fees_due == 1)
                $('#fees-due').modal('show');
            @endif
        });

        $(document).on('click', "#pills-student-tab", function() {
            $('#pills-feedback').show();
        })
        $('#studentfeddback-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 100);
                    } else {
                        toastr.error('There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });
    </script>
@endsection
