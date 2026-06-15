@extends('layouts.admin')
@section('title')
Batch Schedule
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.batchs.batch_schedules.create') ? route('admin.batchs.batch_schedules.store', ['batch' => $batch->id]) : route('admin.batchs.batch_schedules.update', ['batch' => $batch->id, 'batch_schedule' => $batch_schedule->id]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="batch_schedule-form">
        @csrf
        {{ Route::is('admin.batchs.batch_schedules.create') ? '' : method_field('PUT') }}
        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
        <div class="row">

            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5 class="text-black"> {{ Route::is('admin.batchs.batch_schedules.create') ? 'Create' : 'Edit' }} Batch Schedule </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <label class="control-label col-form-label">Weekday </label>
                                <select class="form-control" name="weekday">
                                    <option value="">Select a weekday</option>
                                    <option value="Monday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Monday' ? 'selected' : '' }}>Monday</option>
                                    <option value="Tuesday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                    <option value="Wednesday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                    <option value="Thursday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                    <option value="Friday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Friday' ? 'selected' : '' }}>Friday</option>
                                    <option value="Saturday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                    <option value="Sunday" {{ isset($batch_schedule) && $batch_schedule->weekday == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                </select>
                                <div id="weekday-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="control-label col-form-label">From Time </label>
                                <input type="time" class="form-control" name="from_time" value="{{ isset($batch_schedule) ? $batch_schedule->from_time : '' }}" />
                                <div id="from_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="control-label col-form-label">To Time </label>
                                <input type="time" class="form-control" name="to_time" value="{{ isset($batch_schedule) ? $batch_schedule->to_time : '' }}" />
                                <div id="to_time-error" style="color:red"></div>
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
                        <a href="{{ route('admin.batchs.batch_schedules.index', ['batch' => $batch->id]) }}"
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
    <script>
        $('#batch_schedule-form').submit(function(e) {
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
                            window.location.href = '{!! route('admin.batchs.batch_schedules.index', ['batch' => $batch->id]) !!}';
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
