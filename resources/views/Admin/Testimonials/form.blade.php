@extends('layouts.admin')
@section('title')
    Testimonials
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.testimonials.create') ? route('admin.testimonials.store') : route('admin.testimonials.update', ['testimonial' => $testimonial->route_key]) }}"
        enctype="multipart/form-data" autocomplete="off" id="testimonial-form">
        @csrf
        {{ Route::is('admin.testimonials.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>{{ Route::is('admin.testimonials.create') ? 'Create' : 'Edit' }} Testimonial</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Enter name" name="name" id="name"
                                    value="{{ isset($testimonial) ? $testimonial->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Designation</label>
                                <input type="text" class="form-control" placeholder="Student Father / Mumbai" name="designation" id="designation"
                                    value="{{ isset($testimonial) ? $testimonial->designation : '' }}" />
                                <div id="designation-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <label class="control-label col-form-label">Rating</label>
                                <input type="number" step="0.1" min="1" max="5" class="form-control" placeholder="5" name="rating" id="rating"
                                    value="{{ isset($testimonial) ? $testimonial->rating : '5' }}" />
                                <div id="rating-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <label class="control-label col-form-label">Display Order</label>
                                <input type="number" min="0" class="form-control" placeholder="0" name="display_order" id="display_order"
                                    value="{{ isset($testimonial) ? $testimonial->display_order : '0' }}" />
                                <div id="display_order-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4 mt-2">
                                <label class="control-label col-form-label">Card Color</label>
                                <select class="form-control" name="card_class" id="card_class">
                                    @php
                                        $selectedCardClass = isset($testimonial) ? $testimonial->card_class : 'bg-main-600';
                                    @endphp
                                    <option value="bg-main-600" {{ $selectedCardClass == 'bg-main-600' ? 'selected' : '' }}>Orange</option>
                                    <option value="bg-pink-600" {{ $selectedCardClass == 'bg-pink-600' ? 'selected' : '' }}>Pink</option>
                                    <option value="bg-main-two-600" {{ $selectedCardClass == 'bg-main-two-600' ? 'selected' : '' }}>Yellow</option>
                                </select>
                                <div id="card_class-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6 mt-2">
                                <label class="control-label col-form-label">Image <sup style="color:red">(Square image, example 304x304 px)*</sup></label>
                                <fieldset class="form-group">
                                    <input type="file" name="image" class="form-control" id="testimonial-image"
                                        accept="image/*"></input>
                                    <div id="image-error" style="color:red"></div>
                                </fieldset>
                                @if (isset($testimonial) && $testimonial->image)
                                    @php
                                        $testimonialImage = str_starts_with($testimonial->image, '/') || str_starts_with($testimonial->image, 'http')
                                            ? $testimonial->image
                                            : asset(Storage::url($testimonial->image));
                                    @endphp
                                    <img src="{{ $testimonialImage }}" border="10" width="100"
                                        height="100" class="img-rounded img-thumbnail mt-2" style="object-fit: cover;">
                                @endif
                            </div>
                            <div class="col-sm-12 mt-2">
                                <label class="control-label col-form-label">Review</label>
                                <textarea class="form-control" name="review" id="review" rows="5" placeholder="Enter testimonial review">{{ isset($testimonial) ? $testimonial->review : '' }}</textarea>
                                <div id="review-error" style="color:red"></div>
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
                        <a href="{{ route('admin.testimonials.index') }}" type="button" class="btn btn-secondary">
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
        $('#testimonial-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.testimonials.index') !!}";
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
