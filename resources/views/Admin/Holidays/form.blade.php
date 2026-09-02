@extends('layouts.admin')
@section('title')
    Holiday
@endsection
@section('content')
    @php
        $selectedCountries = normalizeCountryValues($holiday->country ?? []);
        $holidayCountries = [
            'USA' => 'USA',
            'CANADA' => 'CANADA',
            'AUSTRALIA' => 'AUSTRALIA',
            'NEWZEALAND' => 'NEW ZEALAND',
            'INDIA' => 'INDIA',
            'UAE' => 'UAE',
            'UK' => 'UK',
            'SINGAPORE' => 'SINGAPORE',
            'MALAYSIA' => 'MALAYSIA',
            'HONG KONG' => 'HONG KONG',
            'SOUTH AFRICA' => 'SOUTH AFRICA',
            'QATAR' => 'QATAR',
            'BAHRAIN' => 'BAHRAIN',
            'KUWAIT' => 'KUWAIT',
            'EUROPEAN UNION' => 'EUROPEAN UNION',
            'OMAN' => 'OMAN',
            'SAUDI ARABIA' => 'SAUDI ARABIA',
        ];
        $holidayFromTime = isset($holiday) && $holiday->from_time ? \Carbon\Carbon::parse($holiday->from_time)->format('H:i') : '00:00';
        $holidayToTime = isset($holiday) && $holiday->to_time ? \Carbon\Carbon::parse($holiday->to_time)->format('H:i') : '23:59';
    @endphp
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
                                    @foreach ($holidayCountries as $value => $label)
                                        <option value="{{ $value }}" {{ in_array($value, $selectedCountries, true) ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
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
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">From Time (IST) <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="from_time" min="00:00" max="23:59" value="{{ $holidayFromTime }}" />
                                <div id="from_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">To Time (IST) <sup class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="to_time" min="00:01" max="23:59" value="{{ $holidayToTime }}" />
                                <input type="hidden" name="timezone" value="Asia/Kolkata" />
                                <div id="to_time-error" style="color:red"></div>
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
