<div class="row align-items-center">
    <div class="col-lg-4 order-lg-2 order-1">
        <div class="">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 110px; height: 110px;" ;>
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                        style="width: 100px; height: 100px;" ;>
                        <img src="/backend/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
            <div class="text-center">
                @if ($coach->user->first_name)
                    <h5 class="fs-5 mb-0 fw-semibold"> {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                    </h5>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            @if ($coach->user->mobile)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-phone text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Mobile : {{ $coach->user->mobile }}
                    </h6>
                </li>
            @endif
            @if ($coach->user->email)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Email : {{ $coach->user->email }}
                    </h6>
                </li>
            @endif
            @if ($coach->zoom_id)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Zoom Account ID : {{ $coach->zoom_id }}
                    </h6>
                </li>
            @endif
            @if ($coach->zoom_user_id)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Zoom User ID : {{ $coach->zoom_user_id }}
                    </h6>
                </li>
            @endif
            @if ($coach->zoom_api_key)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Zoom API Key : {{ $coach->zoom_api_key }}
                    </h6>
                </li>
            @endif
            @if ($coach->zoom_client_secret)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Zoom Client Secret : {{ $coach->zoom_client_secret }}
                    </h6>
                </li>
            @endif
            @if ($coach->zoom_password)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-lock-square-rounded text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Zoom Password : {{ $coach->zoom_password }}
                    </h6>
                </li>
            @endif
            @if ($coach->portal_id)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Portal ID : {{ $coach->portal_id }}
                    </h6>
                </li>
            @endif
            @if ($coach->portal_password)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-lock-square-rounded text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Portal Password : {{ $coach->portal_password }}
                    </h6>
                </li>
            @endif
        </ul>
    </div>
</div>


<div class="row align-items-center pt-2">
    @if ($coach_availabilities->isNotEmpty())
        <div class="col-md-12 mt-4">
            <h5 class="fw-semibold mb-2">
                <i class="ti ti-clock-cancel fs-5"></i> &nbsp; &nbsp;Coach Availabilities:
            </h5>
            <table
                class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
                <thead>
                    <tr>
                        <th scope="col">
                            <i class="ti ti-calendar-event fs-5"></i> &nbsp; Day of Week
                        </th>
                        <th scope="col">
                            <i class="ti ti-clock-record fs-5"></i> &nbsp; Periods
                        </th>
                        <th scope="col">
                            <i class="ti ti-calendar-event fs-5"></i> &nbsp; Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coach_availabilities as $availability)
                        <tr>
                            <td>{{ ucfirst($availability->day_of_week) }}</td>
                            <td>
                                @foreach ($availability->periods as $period)
                                    @php
                                        $fromTime = \Carbon\Carbon::createFromFormat(
                                            'H:i:s',
                                            $period->from_period,
                                        )->format('g:i A');
                                        $toTime = \Carbon\Carbon::createFromFormat('H:i:s', $period->to_period)->format(
                                            'g:i A',
                                        );
                                    @endphp
                                    <div>{{ $fromTime }} - {{ $toTime }}</div>
                                @endforeach
                            </td>
                            <td>{{ $availability->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if ($batches->isNotEmpty())
    <div class="col-md-12 mt-4">
        <h5 class="fw-semibold mb-2">
            <i class="ti ti-color-swatch fs-5"></i> &nbsp; &nbsp;Connected Batches:
        </h5>
        <table
            class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
            <thead>
                <tr>
                    <th scope="col">
                        <i class="ti ti-tag fs-5"></i> &nbsp; Batch Name
                    </th>
                    <th scope="col">
                        <i class="ti ti-home fs-5"></i> &nbsp; Kids Zone Name
                    </th>
                    <th scope="col">
                        <i class="ti ti-calendar-event fs-5"></i> &nbsp; Status
                    </th>
                    <!-- <th scope="col">
                    <i class="ti ti-loop fs-5"></i> &nbsp; Version</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($batches as $batch)
                    <tr>
                        <td>{{ $batch->name }}</td>
                        <td>{{ $batch->kids_zone_name }}</td>
                        <td>{{ $batch->status }}</td>
                        <!-- <td>{{ $batch->version }}</td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="col-md-12 mt-4">
        <div class="alert alert-warning" role="alert">
            No connected batches found for this coach.
        </div>
    </div>
@endif


<h5 class="fw-semibold mb-2 mt-5">
    <i class="ti ti-money fs-5"></i> &nbsp; &nbsp; Coach Batch Attendance History :
</h5>
<div class="table-responsive rounded">
    <table class="table table-bordered table-hover table-striped" id="coachBatchesTable">
        <thead class="table-dark">
            <tr>
                <th style="text-align: center;" width="1%">#</th>
                <th style="text-align: center;">Batch (Country) </th>
                <th style="text-align: center;" width="1%">Status</th>
                <th style="text-align: center;">Timeline</th>
                <th style="text-align: center;" width="1%">Completed Count</th>
                <th style="text-align: center;">Completed Dates & Times</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batchData as $index => $batch)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        {{ $batch['name'] }}<br>
                        <small>( {{ $batch['country'] }} )</small><br>
                        <small>( {{ $batch['level_names'] }} )</small>
                    </td>
                    <td style="text-align: center;">{!! $batch['status'] !!}</td>
                    <td style="text-align: center;">{{ $batch['timeline'] }}</td>
                    <td style="text-align: center;">{{ $batch['completed_count'] }}</td>
                    <td style="text-align: center;">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batch['completed_dates'] as $key => $date)
                                    <tr>
                                        <td style="text-align: center;">{{ $date }}</td>
                                        <td style="text-align: center;">{{ $batch['completed_times'][$key] ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
