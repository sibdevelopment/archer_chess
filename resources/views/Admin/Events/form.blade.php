@extends('layouts.admin')
@section('title')
    Event
@endsection
@section('content')
    <form
        action="{{ Route::is('admin.events.create') ? route('admin.events.store') : route('admin.events.update', ['event' => $event->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="package-form">
        @csrf
        {{ Route::is('admin.events.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.events.create') ? 'Create' : 'Edit' }} Event </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter title here"
                                    value="{{ old('title', isset($event) ? $event->title : '') }}">
                                <div id="title-error" class="text-danger mt-1"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="link" class="form-label">Link<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="link" name="link"
                                    placeholder="Enter link here" value="{{ old('link', isset($event) ? $event->link : '') }}">
                                <div id="link-error" class="text-danger mt-1"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date<sup class="text-danger">*</sup></label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ old('date', isset($event) ? $event->date : '') }}">
                                <div id="date-error" class="text-danger mt-1"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="link" class="form-label">Mode<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="mode" name="mode"
                                    placeholder="Enter mode here" value="{{ old('mode', isset($event) ? $event->mode : '') }}">
                                <div id="mode-error" class="text-danger mt-1"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="location" class="form-label">Location<sup class="text-danger">*</sup></label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Enter location here"
                                    value="{{ old('location', isset($event) ? $event->location : '') }}">
                                <div id="location-error" class="text-danger mt-1"></div>
                            </div>
                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="short_description" class="form-label">Short Description<sup class="text-danger">*</sup></label>
                                <textarea class="form-control" id="short_description" name="short_description"
                                    placeholder="Enter short description here"
                                    rows="3">{{ old('short_description', isset($event) ? $event->short_description : '') }}</textarea>
                                <div id="short_description-error" class="text-danger mt-1"></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <!-- Left Column: File Input -->
                            <div class="col-md-6">
                                <label for="image" class="form-label">
                                    Image <sup class="text-danger">*</sup>
                                    <small class="text-muted">(Recommended size: 1080 × 790 pixels)</small>
                                </label>
                                <input type="file" name="image" class="form-control" id="image"
                                    placeholder="Please Select Image">
                                <div id="image-error" class="text-danger mt-1"></div>
                            </div>

                            <!-- Right Column: Image Preview -->
                            <div class="col-md-6 d-flex align-items-center">
                                @if (isset($event) && $event->image)
                                    <img id="image-preview" src="{{ asset(Storage::url($event->image)) }}"
                                        class="img-thumbnail"
                                        style="object-fit: cover; max-width: 200px; max-height: 200px;">
                                @else
                                    <img id="image-preview" src="" class="img-thumbnail d-none"
                                        style="object-fit: cover; max-width: 200px; max-height: 200px;">
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="image" class="form-label">
                                    Brochure <sup class="text-danger">*</sup> 
                                </label>
                                <input type="file" name="brochure" class="form-control" id="brochure"
                                    placeholder="Please Select brochure">
                                <div id="brochure-error" class="text-danger mt-1"></div>
                            </div>

                            <!-- Right Column: Image Preview -->
                            <div class="col-md-6 d-flex align-items-center">
                                @if (isset($event) && $event->brochure)
                                    <img id="brochure-preview" src="{{ asset(Storage::url($event->brochure)) }}"
                                        class="img-thumbnail"
                                        style="object-fit: cover; max-width: 200px; max-height: 200px;">
                                @else
                                    <img id="brochure-preview" src="" class="img-thumbnail d-none"
                                        style="object-fit: cover; max-width: 200px; max-height: 200px;">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.events.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $('#package-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.events.index') !!}";
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
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('image-preview');
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        });

        document.getElementById('brochure').addEventListener('change', function(event) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('brochure-preview');
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        });
    </script>
@endsection
