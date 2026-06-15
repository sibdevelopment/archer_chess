@extends('layouts.admin')
@section('title')
    Timezone
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.timezones.create') ? route('admin.timezones.store') : route('admin.timezones.update', ['timezone' => $timezone->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="timezones-form">
        @csrf
        {{ Route::is('admin.timezones.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.timezones.create') ? 'Create' : 'Edit' }} Timezone </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Weekday <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="weekday">
                                    <option value="">SELECT WEEKDAY</option>
                                    <option value="MONDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'MONDAY' ? 'selected' : '' }}>
                                        MONDAY</option>
                                    <option value="TUESDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'TUESDAY' ? 'selected' : '' }}>
                                        TUESDAY</option>
                                    <option value="WEDNESDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'WEDNESDAY' ? 'selected' : '' }}>
                                        WEDNESDAY</option>
                                    <option value="THURSDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'THURSDAY' ? 'selected' : '' }}>
                                        THURSDAY</option>
                                    <option value="FRIDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'FRIDAY' ? 'selected' : '' }}>
                                        FRIDAY</option>
                                    <option value="SATURDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'SATURDAY' ? 'selected' : '' }}>
                                        SATURDAY</option>
                                    <option value="SUNDAY"
                                        {{ isset($timezone) && $timezone->weekday == 'SUNDAY' ? 'selected' : '' }}>
                                        SUNDAY</option>
                                </select>
                                <div id="weekday-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Country <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="country">
                                    <option value="">Select Country</option>
                                    <option value="USA"
                                        {{ isset($timezone) && $timezone->country == 'USA' ? 'selected' : '' }}>
                                        USA</option>
                                    <option value="CANADA"
                                        {{ isset($timezone) && $timezone->country == 'CANADA' ? 'selected' : '' }}>
                                        CANADA</option>
                                    <option value="AUSTRALIA"
                                        {{ isset($timezone) && $timezone->country == 'AUSTRALIA' ? 'selected' : '' }}>
                                        AUSTRALIA</option>
                                    <option value="NEWZEALAND"
                                        {{ isset($timezone) && $timezone->country == 'NEWZEALAND' ? 'selected' : '' }}>
                                        NEW ZEALAND</option>
                                    <option value="INDIA"
                                        {{ isset($timezone) && $timezone->country == 'INDIA' ? 'selected' : '' }}>
                                        INDIA</option>
                                    <option value="UAE"
                                        {{ isset($timezone) && $timezone->country == 'UAE' ? 'selected' : '' }}>
                                        UAE</option>
                                    <option value="UK"
                                        {{ isset($timezone) && $timezone->country == 'UK' ? 'selected' : '' }}>UK
                                    </option>
                                    <option value="SINGAPORE"
                                        {{ isset($timezone) && $timezone->country == 'SINGAPORE' ? 'selected' : '' }}>
                                        SINGAPORE</option>
                                        <option value="QATAR" {{ (isset($timezone) && in_array('QATAR', $timezone->countries ?? [])) ? 'selected' : '' }}>QATAR</option>
                                        <option value="BAHRAIN" {{ (isset($timezone) && in_array('BAHRAIN', $timezone->countries ?? [])) ? 'selected' : '' }}>BAHRAIN</option>
                                        <option value="KUWAIT" {{ (isset($timezone) && in_array('KUWAIT', $timezone->countries ?? [])) ? 'selected' : '' }}>KUWAIT</option>    
                                    <option value="EUROPEAN UNION"
                                        {{ isset($timezone) && $timezone->country == 'EUROPEAN UNION' ? 'selected' : '' }}>
                                        EUROPEAN UNION</option>    
                                        <option value="OMAN" {{ (isset($timezone) && in_array('OMAN', $timezone->countries ?? [])) ? 'selected' : '' }}>OMAN</option>   
                                </select>
                                <div id="country-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Timezone <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" id="timezoneSelect" name="timezone">
                                    <option value="" disabled selected>Select Time Zone</option>
                                    @if (isset($timezone))
                                        <option value="{{ $timezone->timezone }}" selected>{{ $timezone->timezone }}
                                        </option>
                                    @endif
                                </select>
                                <div id="timezone-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">India Start Time <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="india_start_time"
                                    value="{{ isset($timezone) ? $timezone->india_start_time : '' }}" />
                                <div id="india_start_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">India End Time <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="india_end_time"
                                    value="{{ isset($timezone) ? $timezone->india_end_time : '' }}" />
                                <div id="india_end_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Country Start Time <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="country_start_time"
                                    value="{{ isset($timezone) ? $timezone->country_start_time : '' }}" readonly />
                                <div id="country_start_time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Country End Time <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="country_end_time"
                                    value="{{ isset($timezone) ? $timezone->country_end_time : '' }}" readonly />
                                <div id="country_end_time-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                    <div id="custom-error" style="color:red"></div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.timezones.index') }}" type="button" class="btn btn-secondary">
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
        function sendData() {
            const timeZone = document.getElementById('timezoneSelect').value;
            const country = document.querySelector('select[name="country"]').value;
            const indiaStartTime = document.querySelector('input[name="india_start_time"]').value;
            const indiaEndTime = document.querySelector('input[name="india_end_time"]').value;

            $.ajax({
                url: '{{ route('admin.timezones.processDateTimeZone') }}',
                type: 'POST',
                data: {
                    timeZone: timeZone,
                    country: country,
                    india_start_time: indiaStartTime,
                    india_end_time: indiaEndTime,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    // Assuming the response.convertedStartTime and response.convertedEndTime format is "YYYY-MM-DD HH:MM:SS"
                    const startTimeParts = response.convertedStartTime.split(' ');
                    const endTimeParts = response.convertedEndTime.split(' ');
                    const startTime = startTimeParts[1]; // This will be "HH:MM:SS"
                    const endTime = endTimeParts[1]; // This will be "HH:MM:SS"

                    // Now, set these values in the respective input fields
                    document.querySelector('input[name="country_start_time"]').value = startTime.substring(0,
                    5); // Trimming to "HH:MM" format
                    document.querySelector('input[name="country_end_time"]').value = endTime.substring(0,
                    5); // Trimming to "HH:MM" format
                    // Optionally, update the response display if needed
                    document.getElementById('responseDisplay').innerHTML = JSON.stringify(response);
                },
                error: function(xhr, status, error) {
                    // Assuming you have Toastr included in your project
                    toastr.error("An error occurred: " + xhr.responseText);
                }
            });
        }


        $(document).ready(function() {
            $('#timezoneSelect').select2({
                placeholder: "Select Time Zone",
                allowClear: true
            });

            // Function to fetch and populate timezones
            function fetchTimezones(selectedTimezone = null) {
                var selectedCountry = $('select[name="country"]').val();
                if (!selectedCountry) {
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.timezones.get.timezones') }}',
                    method: 'GET',
                    data: {
                        country: selectedCountry
                    },
                    success: function(data) {
                        $('#timezoneSelect').empty().append(
                            '<option value="" disabled>Select Time Zone</option>');
                        $.each(data, function(groupLabel, timezones) {
                            var optgroup = $('<optgroup>').attr('label', groupLabel);
                            $.each(timezones, function(value, label) {
                                var option = $('<option>').attr('value', value).text(
                                    label);
                                if (value === selectedTimezone) {
                                    option.attr('selected', 'selected');
                                }
                                optgroup.append(option);
                            });
                            $('#timezoneSelect').append(optgroup);
                        });
                        $('#timezoneSelect').trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching timezones:', error);
                    }
                });
            }

            // Fetch timezones when the country changes
            $('select[name="country"]').change(function() {
                fetchTimezones();
            });

            // Fetch timezones if the country is pre-selected
            if ($('select[name="country"]').val()) {
                var selectedTimezone = '{{ isset($timezone) ? $timezone->timezone : null }}';
                fetchTimezones(selectedTimezone);
            }

            $('#timezoneSelect').on('select2:select', sendData);
            $('input[name="india_start_time"]').change(sendData);
            $('input[name="india_end_time"]').change(sendData);
        });

        $('#timezones-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            $('#custom-error').empty(); // Clear the custom error div
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
                            window.location.href = "{!! route('admin.timezones.index') !!}";
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
                    if (xhr.status === 422) {
                        if (xhr.responseJSON.status === 'error' && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        } else {
                            toastr.error('There are some errors in Form. Please check your inputs',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] +
                                '-error').offset().top - 200
                        }, 500);
                    } else {
                        toastr.error('There are some errors in Form. Please check your inputs', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                }
            });
        });
    </script>
@endsection
