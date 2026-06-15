@extends('layouts.admin')
@section('title')
    Gallery
@endsection
@section('content')
<style>
    input[type="checkbox"]:checked + img {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>

    <form
        action="{{ Route::is('admin.galleries.create') ? route('admin.galleries.store') : route('admin.galleries.update', ['gallery' => $gallery->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="package-form">
        @csrf
        {{ Route::is('admin.galleries.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.galleries.create') ? 'Create' : 'Edit' }} Gallery </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Enter title here" name="title"
                                    value="{{ isset($gallery) ? $gallery->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-6 ">
                                <label class="control-label col-form-label">Images 1 <sup style="color:red">(Upload image size: 304×304 px)</sup></label>
                                <input type="file" class="form-control" name="images_1[]" multiple />
                                <div id="images_1-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Images 2 <sup style="color:red">(Upload image size: 304×304 px)</sup></label>
                                <input type="file" class="form-control" name="images_2[]" multiple />
                                <div id="images_2-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Images 3 <sup style="color:red">(Upload image size: 304×304 px)</sup></label>
                                <input type="file" class="form-control" name="images_3[]" multiple />
                                <div id="images_3-error" style="color:red"></div>
                            </div>
                        
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Images 4 <sup style="color:red">(Upload image size: 304×304 px)</sup></label>
                                <input type="file" class="form-control" name="images_4[]" multiple />
                                <div id="images_4-error" style="color:red"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Images 5 <sup style="color:red">(Upload image size: 304×304 px)</sup></label>
                                <input type="file" class="form-control" name="images_5[]" multiple />
                                <div id="images_5-error" style="color:red"></div>
                            </div>
                        </div>
                    

                        {{-- @if(isset($gallery))
                            <div class="mt-4">
                                <label class="control-label col-form-label">Uploaded Images (Select any)</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach (['images_1', 'images_2', 'images_3', 'images_4', 'images_5'] as $imageField)
                                        @if(!empty($gallery->$imageField))
                                            @foreach($gallery->$imageField as $index => $image)
                                                <label style="position: relative; display: inline-block; cursor: pointer;">
                                                    <input type="checkbox" name="selected_images[]" value="{{ $image }}"
                                                        style="position: absolute; top: 5px; left: 5px; z-index: 1; width: 20px; height: 20px;" />

                                                    <img src="{{ Storage::url($image) }}" alt="Image" width="100" height="100"
                                                        style="object-fit: cover; border: 2px solid #ccc; border-radius: 4px;">
                                                </label>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif --}}

                        @if(isset($gallery))
                            <div class="mt-4">
                                <label class="control-label col-form-label">Uploaded Images</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach (['images_1', 'images_2', 'images_3', 'images_4', 'images_5'] as $imageField)
                                        @if(!empty($gallery->$imageField))
                                            @foreach($gallery->$imageField as $image)
                                                <div style="position: relative;">
                                                    <input type="checkbox" name="delete_images[]" value="{{ $image }}"
                                                        style="position: absolute; top: 5px; left: 5px; z-index: 2;">
                                                <a href="{{ Storage::url($image) }}" target="_blank">
                                                    <img src="{{ Storage::url($image) }}" alt="Image" width="100" height="100" style="object-fit:cover; border:1px solid #ccc; border-radius: 4px;">
                                                </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif



                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.galleries.index') }}" type="button" class="btn btn-secondary">
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
                            window.location.href = "{!! route('admin.galleries.index') !!}";
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
