<div class="row align-items-center">
    <div class="col-lg-4 order-lg-2 order-1">
        <div class="">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;";>
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;";>
                        <img src="/backend/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <h5 class="fs-5 mb-0 fw-semibold">{{ $feedback->full_name }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-user text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0 text-success">
                     {{ $feedback->coach->user->first_name }} {{ $feedback->coach->user->last_name }}
                </h6>
            </li>
            <li class="d-flex align-items-center gap-3 mb-4">
                <i class="ti ti-briefcase text-dark fs-6"></i>
                <h6 class="fs-4 fw-semibold mb-0">
                    {{ $feedback->feedback  }}
                </h6>
            </li>
        </ul>
    </div>
</div>
