@extends('layouts.admin')
@section('title')
    Holiday
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.holidays.create') ? route('admin.holidays.store') : route('admin.holidays.update', ['holiday' => $holiday->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="holidays-form">
        @csrf
        {{ Route::is('admin.holidays.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.holidays.create') ? 'Create' : 'Edit' }} Holiday </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            {{-- <h6 class="text-warning fs-4">Holiday Details :</h6> --}}
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Country <sup class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="country[]" multiple="multiple">
                                    <option value="USA" {{ (isset($holiday) && in_array('USA', $holiday->country ?? [])) ? 'selected' : '' }}>USA</option>
                                    <option value="CANADA" {{ (isset($holiday) && in_array('CANADA', $holiday->country ?? [])) ? 'selected' : '' }}>CANADA</option>
                                    <option value="AUSTRALIA" {{ (isset($holiday) && in_array('AUSTRALIA', $holiday->country ?? [])) ? 'selected' : '' }}>AUSTRALIA</option>
                                    <option value="NEWZEALAND" {{ (isset($holiday) && in_array('NEWZEALAND', $holiday->country ?? [])) ? 'selected' : '' }}>NEW ZEALAND</option>
                                    <option value="INDIA" {{ (isset($holiday) && in_array('INDIA', $holiday->country ?? [])) ? 'selected' : '' }}>INDIA</option>    
                                    <option value="UAE" {{ (isset($holiday) && in_array('UAE', $holiday->country ?? [])) ? 'selected' : '' }}>UAE</option>
                                    <option value="UK" {{ (isset($holiday) && in_array('UK', $holiday->country ?? [])) ? 'selected' : '' }}>UK</option>
                                    <option value="SINGAPORE" {{ (isset($holiday) && in_array('SINGAPORE', $holiday->country ?? [])) ? 'selected' : '' }}>SINGAPORE</option>
                                </select>
                                <div id="country-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6"></div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Name" name="name"
                                    value="{{ isset($holiday) ? $holiday->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Start Date <sup class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="start_date" value="{{ isset($holiday) ? $holiday->start_date : '' }}" />
                                <div id="start_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">End Date <sup class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="end_date" value="{{ isset($holiday) ? $holiday->end_date : '' }}" />
                                <div id="end_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Description</label>
                                <input type="text" class="form-control" placeholder="Description" name="description"
                                    value="{{ isset($holiday) ? $holiday->description : '' }}" />
                                <div id="description-error" style="color:red"></div>
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
                        <a href="{{ route('admin.holidays.index') }}" type="button" class="btn btn-secondary">
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
    $('#holidays-form').submit(function(e) {
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
                        window.location.href = "{!! route('admin.holidays.index') !!}";
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
