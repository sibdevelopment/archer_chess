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
                @if ($demolead->first_name)
                    <h5 class="fs-5 mb-0 fw-semibold"> {{ $demolead->first_name }} {{ $demolead->last_name }}
                    </h5>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            @if ($demolead->age)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-stretching text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Age: {{ $demolead->age }}
                    </h6>
                </li>
            @endif
          
            @if ($demolead->mobile)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-phone text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Mobile : {{ $demolead->mobile }}
                    </h6>
                </li>
            @endif
            @php
                // dd($demolead);
            @endphp
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-key text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Password :
                    {{ isset($demolead->user->device_id) ? $demolead->user->device_id : 'Not Set' }}</h6>
            </li>
            @if (!empty($demolead->user) && !empty($demolead->user->email))
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Email : {{ $demolead->user->email }}</h6>
                </li>
            @endif

            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-map text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">
                    City : {{ $demolead->city }}
                </h6>
            </li>
            @if ($demolead->country)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-map text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Country : {{ $demolead->country }}
                    </h6>
                </li>
            @endif
            @if ($demolead->date && $demolead->time)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-map text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">

                        Scheduled :
                        {{ \Carbon\Carbon::parse($demolead->date . ' ' . $demolead->time)->format('d-M-Y | h:i A') }}
                    </h6>
                </li>
            @endif
            @if ($demolead->kids_date && $demolead->kids_time)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-map text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Kids Zone :
                        {{ \Carbon\Carbon::parse($demolead->kids_date . ' ' . $demolead->kids_time)->format('d-M-Y | h:i A') }}
                        | ( {{ $demolead->kids_time_zone }} )
                    </h6>
                </li>
            @endif
            @if ($demolead->remark)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-lock-square-rounded text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">
                        Remark : {{ $demolead->remark }}
                    </h6>
                </li>
            @endif
        </ul>
    </div>
</div>
<div class="row align-items-center pt-2"></div>
@if ($demosessions->isNotEmpty())
    <div class="col-md-12 mt-4">
        <h5 class="fw-semibold mb-2">
            <i class="ti ti-clock-cancel fs-5"></i> &nbsp; &nbsp;Demo Sessions Record :
        </h5>
        <table
            class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Status</th>
                    <!-- <th scope="col">Student Name</th> -->
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Coach</th>
                    <th scope="col">Slot</th>
                    <th scope="col">Level <br> <span style="font-size: 12px;">(Given By Coach)</span> </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($demosessions as $index => $demosession)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $demosession->status }}</td>
                        <!-- <td>{{ $demolead->first_name . ' ' . $demolead->last_name }}</td> -->
                        <td>{{ \Carbon\Carbon::parse($demosession->date)->format('d-M-Y') }}</td>
                        <td>{{ $demosession->time ? \Carbon\Carbon::parse($demosession->time)->format('g:i A') : '' }}
                        </td>
                        <td>{{ $demosession->coach->user->first_name }} {{ $demosession->coach->user->last_name }}
                        </td>
                        @php
                            $times = explode(' - ', $demosession->slot);
                            // dd($demosession);
                            $startTime = \Carbon\Carbon::parse($times[0])->format('g:i A');
                            $endTime = \Carbon\Carbon::parse($times[1])->format('g:i A');
                        @endphp
                        <td>{{ $startTime . ' - ' . $endTime }}</td>
                        <td>{{ optional($demosession->level)->name ?? ' ' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
</div>
