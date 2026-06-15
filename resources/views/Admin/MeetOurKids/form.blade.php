@extends('layouts.admin')
@section('title')
    Meet Our Kids
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.meet-our-kids.create') ? route('admin.meet-our-kids.store') : route('admin.meet-our-kids.update', ['meet_our_kid' => $meetourkid->route_key]) }}"
        enctype="multipart/form-data" autocomplete="off" id="meetourkid-form">
        @csrf
        {{ Route::is('admin.meet-our-kids.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.meet-our-kids.create') ? 'Create' : 'Edit' }} Meet Our Kid </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Title</label>
                                <input type="text" class="form-control" placeholder="Title" name="title" id="title"
                                    value="{{ isset($meetourkid) ? $meetourkid->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Image <sup style="color:red">(Upload image size: 304×304 px)*</sup></label>
                                <fieldset class="form-group">
                                    <input type="file" name="image" class="form-control" id="meetourkid-image"
                                        accept="image/*"></input>
                                    <div id="image-error" style="color:red"></div>
                                </fieldset>
                                @if (isset($meetourkid))
                                    <img src="{{ asset(Storage::url($meetourkid->image)) }}" border="10" width="100"
                                        height="100" class="img-rounded img-thumbnail">
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
                        <a href="{{ route('admin.meet-our-kids.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#full_description'))
            .catch(error => {
                console.error(error);
            });
        $('#meetourkid-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.meet-our-kids.index') !!}";
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
