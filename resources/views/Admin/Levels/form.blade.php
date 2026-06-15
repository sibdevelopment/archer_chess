@extends('layouts.admin')
@section('title')
    Level
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.levels.create') ? route('admin.levels.store') : route('admin.levels.update', ['level' => $level->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="levels-form">
        @csrf
        {{ Route::is('admin.levels.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.levels.create') ? 'Create' : 'Edit' }} Level </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <!-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Status <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Status" name="status"
                                    value="{{ isset($level) ? $level->status : '' }}" />
                                <div id="status-error" style="color:red"></div>
                            </div> -->
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Name" name="name"
                                    value="{{ isset($level) ? $level->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6" style="display: none !important;">
                                <label class="control-label col-form-label">Index</label>
                                <input type="text" class="form-control" placeholder="Index" name="index"
                                    value="{{ isset($nextIndex) ? $nextIndex : (isset($level) ? $level->index : '') }}" />
                                <div id="index-error" style="color:red"></div>
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
                        <a href="{{ route('admin.levels.index') }}" type="button" class="btn btn-secondary">
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
    $('#levels-form').submit(function(e) {
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
                        window.location.href = "{!! route('admin.levels.index') !!}";
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
