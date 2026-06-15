<div class="row align-items-start">
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-4">

            <!-- Blog Title -->
            <li class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="ti ti-heading text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Title:</h6>
                </div>
                <p class="fs-4 mb-0">{{ $blog->title }}</p>
            </li>

            <!-- Blog Date -->
            <li class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="ti ti-calendar text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Date:</h6>
                </div>
                <p class="fs-4 mb-0">{{ $blog->date }}</p>
            </li>

            <!-- Blog Slug -->
            <li class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="ti ti-link text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Slug:</h6>
                </div>
                <p class="fs-4 mb-0">{{ $blog->slug }}</p>
            </li>

            <!-- Short Description -->
            <li class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="ti ti-align-justify text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Short Description:</h6>
                </div>
                <p class="fs-4 mb-0">{{ $blog->short_description }}</p>
            </li>

            <!-- Full Description -->
            <li class="mb-4">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="ti ti-file-text text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Description:</h6>
                </div>
                <div class="fs-4">{!! $blog->description !!}</div>
            </li>

        </ul>
    </div>
</div>
