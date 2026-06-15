@extends('layouts.admin')
@section('title')
    Gallery Images
@endsection
@section('content')
    <div class="row">
        <div class="col-3">
            <div class="col-md-12 col-lg-12">
                <div class="card rounded-3 card-hover">
                    <a href="{{ route('admin.galleries.index', ['gallery' => $gallery]) }}" class="stretched-link"></a>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="flex-shrink-0"><i class="ti ti-photo text-warning display-6"></i></span>
                            <div class="ms-4">
                                <h4 class="card-title text-dark"> {{ $gallery->title }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-9">
            <form
                action="{{ Route::is('admin.galleries.gallery_images.create', ['gallery' => $gallery->route_key])
                    ? route('admin.galleries.gallery_images.store', ['gallery' => $gallery->route_key])
                    : route('admin.galleries.gallery_images.update', [
                        'gallery' => $gallery->route_key,
                        'gallery_image' => $galleryImages->route_key,
                    ]) }}"
                method="POST" enctype="multipart/form-data" autocomplete="off" id="package-form">
                @csrf
                {{ Route::is('admin.galleries.gallery_images.create', ['gallery' => $gallery]) ? '' : method_field('PUT') }}
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    {{ Route::is('admin.galleries.gallery_images.create', ['gallery' => $gallery]) ? 'Create' : 'Edit' }}
                                    Gallery Images
                                </h5>

                                <a href="{{ route('admin.galleries.gallery_images.index', ['gallery' => $gallery->route_key]) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left me-1"></i> Back
                                </a>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="row mt-4">
                                        <div class="col-sm-12 col-md-10 d-flex align-items-start gap-4">
                                            <div class="w-100">
                                                <label class="control-label col-form-label">Image <sup
                                                        class="tcul-star-restrict">*</sup></span> <small class="text-muted">(Recommended size: 1650×1275 pixels)</small></label>
                                                <fieldset class="form-group">
                                                    <input type="file" name="image" class="form-control" id="image"
                                                        placeholder="Please Select Image">
                                                    <div id="image-error" style="color:red"></div>
                                                </fieldset>
                                            </div>

                                            @if (isset($galleryImages))
                                                <div>
                                                    <img id="image-preview"
                                                        src="{{ asset(Storage::url($galleryImages->image)) }}"
                                                        width="200" height="200" class="img-thumbnail"
                                                        style="object-fit: cover;">
                                                </div>
                                            @else
                                                <div>
                                                    <img id="image-preview" src="" width="200" height="200"
                                                        class="img-thumbnail d-none" style="object-fit: cover;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                        &nbsp;
                                        <i class="ti ti-device-floppy"></i>
                                    </button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{{ route('admin.galleries.gallery_images.index', ['gallery' => $gallery]) }}"
                                        type="button" class="btn btn-secondary">
                                        Cancel
                                        &nbsp;
                                        <i class="ti ti-arrow-back-up-double"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>

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
                            window.location.href = "{!! route('admin.galleries.gallery_images.index', ['gallery' => $gallery]) !!}";
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
    </script>
@endsection
