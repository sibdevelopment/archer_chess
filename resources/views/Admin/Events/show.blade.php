<div class="row g-4">
    <div class="col-lg-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-book text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Title</h6>
                    <div class="text-muted">{{ $event->title }}</div>
                </div>
            </li>
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-world text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Link</h6>
                    <div><a href="{{ $event->link }}" target="_blank" class="text-decoration-underline text-primary">{{ $event->link }}</a></div>
                </div>
            </li>
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-calendar text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Date</h6>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($event->date)->format('d M, Y') }}</div>
                </div>
            </li>
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-map-pin text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Location</h6>
                    <div class="text-muted">{{ $event->location }}</div>
                </div>
            </li>
        </ul>
    </div>

    <div class="col-lg-6">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-toggle-right text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Mode</h6>
                    <div class="text-muted">{{ ucfirst($event->mode) }}</div>
                </div>
            </li>
            @if($event->short_description)
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-align-left text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Short Description</h6>
                    <div class="text-muted">{{ $event->short_description }}</div>
                </div>
            </li>
            @endif
            @if($event->image)
            <li class="list-group-item d-flex gap-3 align-items-start">
                <i class="ti ti-photo text-primary fs-5 pt-1"></i>
                <div>
                    <h6 class="fw-semibold mb-1">Image</h6>
                    <img src="{{ asset('storage/'.$event->image) }}" alt="Event Image" class="img-fluid rounded shadow-sm mt-2" style="max-height: 180px;">
                </div>
            </li>
            @endif
        </ul>
    </div>
</div>
