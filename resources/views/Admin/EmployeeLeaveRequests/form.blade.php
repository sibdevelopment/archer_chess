@extends('layouts.admin')
@section('title')
    Employee Leave Request
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.employeeleaverequests.create') ? route('admin.employeeleaverequests.store') : route('admin.employeeleaverequests.update', ['employeeleaverequest' => $employeeleaverequest->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="employee-leave-form">
        @csrf
        {{ Route::is('admin.employeeleaverequests.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>{{ Route::is('admin.employeeleaverequests.create') ? 'Create' : 'Edit' }} Employee Leave Request</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $employee->user->full_name }}" readonly>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Leave Type <sup class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="leave_type">
                                    @foreach (['FULL DAY' => 'Full Day', 'HALF DAY' => 'Half Day', 'SHORT LEAVE' => 'Short Leave', 'SICK LEAVE' => 'Sick Leave', 'EMERGENCY' => 'Emergency', 'OTHER' => 'Other'] as $value => $label)
                                        <option value="{{ $value }}" {{ (isset($employeeleaverequest) && $employeeleaverequest->leave_type === $value) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div id="leave_type-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="control-label col-form-label">From Date <sup class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="from_date" id="from_date" value="{{ isset($employeeleaverequest) ? $employeeleaverequest->from_date : '' }}" />
                                <div id="from_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="control-label col-form-label">To Date <sup class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="to_date" id="to_date" value="{{ isset($employeeleaverequest) ? $employeeleaverequest->to_date : '' }}" />
                                <div id="to_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="control-label col-form-label">From Time</label>
                                <input type="time" class="form-control" name="from_time" id="from_time"
                                    value="{{ isset($employeeleaverequest) && $employeeleaverequest->from_time ? date('H:i', strtotime($employeeleaverequest->from_time)) : '' }}"
                                    step="60" min="00:00" max="23:59" />
                                <div id="from_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label class="control-label col-form-label">To Time</label>
                                <input type="time" class="form-control" name="to_time" id="to_time"
                                    value="{{ isset($employeeleaverequest) && $employeeleaverequest->to_time ? date('H:i', strtotime($employeeleaverequest->to_time)) : '' }}"
                                    step="60" min="00:00" max="23:59" />
                                <div id="to_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Reason <sup class="tcul-star-restrict">*</sup></label>
                                <textarea class="form-control" placeholder="Reason" name="reason" rows="3">{{ isset($employeeleaverequest) ? $employeeleaverequest->reason : '' }}</textarea>
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
                        <a href="{{ route('admin.employeeleaverequests.index') }}" type="button" class="btn btn-secondary">
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
        function resetEmployeeLeaveSubmitLoader(form) {
            $('#loaderImage').hide();
            $(form).find('button[type=submit]').show();
        }

        $('#employee-leave-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();

            var fromTime = $('#from_time').val();
            var toTime = $('#to_time').val();
            if (toTime === '00:00') {
                $('#to_time-error').html('Please use 11:59 PM as the day-ending leave time. Do not use 12:00 AM.');
                resetEmployeeLeaveSubmitLoader(this);
                return;
            }
            if (fromTime && toTime && toTime <= fromTime) {
                $('#to_time-error').html('Leave end time must be later than start time.');
                resetEmployeeLeaveSubmitLoader(this);
                return;
            }

            var form = $(this);
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function() {
                        window.location.href = "{!! route('admin.employeeleaverequests.index') !!}";
                    }, 100);
                },
                error: function(xhr) {
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value[0]);
                        });
                    }
                }
            });
        });
    </script>
@endsection
