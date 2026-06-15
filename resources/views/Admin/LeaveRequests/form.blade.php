@extends('layouts.admin')
@section('title')
    LeaveRequest
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.leaverequests.create') ? route('admin.leaverequests.store') : route('admin.leaverequests.update', ['leaverequest' => $leaverequest->id]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="leaverequests-form">
        @csrf
        {{ Route::is('admin.leaverequests.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.leaverequests.create') ? 'Create' : 'Edit' }} LeaveRequest </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Coach <sup class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="coach_id">
                                    <option value="{{ $coach->id }}" selected>
                                        {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                    </option>
                                </select>
                                <div id="coach_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Status <sup class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="status">
                                    <option value="ACTIVE" {{ (isset($leaverequest) && $leaverequest->status == 'ACTIVE') ? 'selected' : '' }}>ACTIVE</option>
                                    <option value="INACTIVE" {{ (isset($leaverequest) && $leaverequest->status == 'INACTIVE') ? 'selected' : '' }}>INACTIVE</option>
                                </select>
                                <div id="status-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="control-label col-form-label">From Date <sup class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="from_date" id="from_date" value="{{ isset($leaverequest) ? $leaverequest->from_date : '' }}" />
                                <div id="from_date-error" style="color:red"></div>
                            </div>
                            {{-- <div class="col-sm-12 col-md-3" id="from_time_container" style=""></div>
                            <div class="col-sm-12 col-md-3" id="from_time_container" style=""></div>
                            <div class="col-sm-12 col-md-3" id="from_time_container" style=""></div> --}}
                            {{-- <div class="col-sm-12 col-md-3" id="from_time_container" style="">
                                <label class="control-label col-form-label">From Time <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="from_time" id="from_time" value="{{ isset($leaverequest) ? $leaverequest->from_time : '' }}" />
                                <div id="from_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3" id="to_time_container" style="">
                                <label class="control-label col-form-label">To Time <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="to_time" id="to_time" value="{{ isset($leaverequest) ? $leaverequest->to_time : '' }}" />
                                <div id="to_time-error" style="color:red"></div>
                            </div>
 --}}

                            <div class="col-sm-12 col-md-3" id="from_time_container"></div>
                            <div class="col-sm-12 col-md-3" id="from_time_container"></div>
                            <div class="col-sm-12 col-md-3" id="from_time_container"></div>
                            <div class="col-sm-12 col-md-3" id="from_time_container">
                                <label class="control-label col-form-label">From Time <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="from_time" id="from_time"
                                    value="{{ isset($leaverequest) ? date('H:i', strtotime($leaverequest->from_time)) : '' }}"
                                    step="60" min="00:00" max="23:59" />
                                <div id="from_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3" id="to_time_container">
                                <label class="control-label col-form-label">From Time <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="to_time" id="to_time"
                                    value="{{ isset($leaverequest) ? date('H:i', strtotime($leaverequest->to_time)) : '' }}"
                                    step="60" min="00:00" max="23:59" />
                                <div id="to_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Reason <sup class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Reason" name="reason"
                                    value="{{ isset($leaverequest) ? $leaverequest->reason : '' }}" />
                                <div id="reason-error" style="color:red"></div>
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
                        <a href="{{ route('admin.leaverequests.index') }}" type="button" class="btn btn-secondary">
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
    document.addEventListener('DOMContentLoaded', function() {
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');
        const fromTimeContainer = document.getElementById('from_time_container');
        const toTimeContainer = document.getElementById('to_time_container');

        function toggleTimeFields() {
            if (fromDateInput.value === toDateInput.value && fromDateInput.value !== '') {
                fromTimeContainer.style.display = 'block';
                toTimeContainer.style.display = 'block';
            } else {
                fromTimeContainer.style.display = 'none';
                toTimeContainer.style.display = 'none';
            }
        }

        fromDateInput.addEventListener('change', toggleTimeFields);
        toDateInput.addEventListener('change', toggleTimeFields);

        // Initial check in case the dates are already set
        toggleTimeFields();
    });

    $('#leaverequests-form').submit(function(e) {
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
                        window.location.href = "{!! route('admin.leaverequests.index') !!}";
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
