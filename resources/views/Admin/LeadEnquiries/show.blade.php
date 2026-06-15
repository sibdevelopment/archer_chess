<div class="row align-items-center">
    <div class="col-lg-4 order-lg-2 order-1">
        <div class="">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;">
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;">
                        <img src="/backend/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <h5 class="fs-5 mb-0 fw-semibold">{{ $leadenquiry->kids_first_name }} {{ $leadenquiry->kids_last_name }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            @if($leadenquiry->age)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-stretching text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Age : {{ $leadenquiry->age }}</h6>
                </li>
            @endif
            @if($leadenquiry->mobile)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-phone text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Mobile : {{ $leadenquiry->mobile }}</h6>
                </li>
            @endif
            @php
                // dd($leadenquiry->user);
            @endphp
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-key text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">Password : {{
                    (isset($leadenquiry->user->device_id) ? $leadenquiry->user->device_id : 'Not Set')
                }}</h6>
            </li>
            @if($leadenquiry->email)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Email : {{ $leadenquiry->email }}</h6>
                </li>
            @endif
            @if($leadenquiry->city)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-home text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">City : {{ $leadenquiry->city }}</h6>
                </li>
            @endif
            @if($leadenquiry->country)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-home text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Country : {{ $leadenquiry->country }}</h6>
                </li>
            @endif
            @if($leadenquiry->available_device)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-device-desktop text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Available Device : {{ $leadenquiry->available_device }}</h6>
                </li>
            @endif
            @if($leadenquiry->enrollment_plan)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-book text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Enrollment Plan : {{ $leadenquiry->enrollment_plan }}</h6>
                </li>
            @endif
            @if($leadenquiry->language_preference)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-language text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Language Preference : {{ $leadenquiry->language_preference }}</h6>
                </li>
            @endif
            @if($leadenquiry->level)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-chart-arrows-vertical text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Level : {{ $leadenquiry->level }}</h6>
                </li>
            @endif
            @if($leadenquiry->duration)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-clock text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Duration : {{ $leadenquiry->duration }}</h6>
                </li>
            @endif
            @if($leadenquiry->mobile_verified)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-check text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Mobile Verified : {{ $leadenquiry->mobile_verified }}</h6>
                </li>
            @endif
            @if($leadenquiry->lead_status)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-info-circle text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Lead Status : {{ $leadenquiry->lead_status }}</h6>
                </li>
            @endif
            @if($leadenquiry->parent_name)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-user text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Parent Name : {{ $leadenquiry->parent_name }}</h6>
                </li>
            @endif
            @if($leadenquiry->dob)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-calendar text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Date of Birth : {{ $leadenquiry->dob }}</h6>
                </li>
            @endif
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-fingerprint text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0 {{ ($leadenquiry->status == 'ACTIVE' ? 'text-success' : 'text-danger' ) }}">
                    {{ ucwords(strtolower($leadenquiry->status)) }}
                </h6>
            </li>
            @if($leadenquiry->email_verified)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Email Verified : {{ $leadenquiry->email_verified }}</h6>
                </li>
            @endif
            @if($leadenquiry->created_at)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-calendar text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Created At : {{ $leadenquiry->created_at }}</h6>
                </li>
            @endif
            @if($leadenquiry->remark)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Remark : {{ $leadenquiry->remark }}</h6>
                </li>
            @endif
        </ul>
    </div>
</div>
