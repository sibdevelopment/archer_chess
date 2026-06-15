@extends('layouts.admin')
@section('title')
    Demo Lead
@endsection
@section('content')
@php
    $user = auth()->user();
    $role = $user->getRoleNames()->toArray();
    $isAdminOrSuperAdmin = in_array("Admin", $role) || in_array("SuperAdmin", $role);

    // Get the countries the user can see
    $allowedCountries = [];
    if (!$isAdminOrSuperAdmin) {
        $userRole = $user->roles()->first();
        if ($userRole && $userRole->countries) {
            $allowedCountries = json_decode($userRole->countries);
        }
    }
@endphp
<form method="POST"
    action="{{ Route::is('admin.demoleads.create') ? route('admin.demoleads.store') : route('admin.demoleads.update', ['demolead' => $demolead->id]) }}"
    method="POST" enctype="multipart/form-data" autocomplete="off" id="demoleads-form">
    @csrf
    {{ Route::is('admin.demoleads.create') ? '' : method_field('PUT') }}
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5> {{ Route::is('admin.demoleads.create') ? 'Create' : 'Edit' }} DemoLeads </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <h6 class="text-warning fs-4">Personal Info :</h6>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Name <sup class="tcul-star-restrict">*</sup></label>
                            <input type="text" class="form-control" placeholder="First Name" name="first_name"
                                value="{{ isset($demolead) ? $demolead->first_name : '' }}" />
                            <div id="first_name-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Age </label>
                            <input type="text" class="form-control" placeholder="Age" name="age"
                                value="{{ isset($demolead) ? $demolead->age : '' }}" />
                            <div id="age-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Mobile <sup class="tcul-star-restrict">*</sup> (Include country code)</label>
                            <input type="text" class="form-control" placeholder="Mobile" name="mobile"
                                value="{{ isset($demolead) ? $demolead->mobile : '' }}" id="mobile" />
                            <div id="mobile-error" style="color:red"></div>
                            {{-- <div id="unique_mobile-error" style="color:red"></div> --}}
                            {{-- <div id="unique_mobile-success" style="color:green"></div> --}}
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Email <sup class="tcul-star-restrict">*</sup></label>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                value="{{ isset($demolead) ? $demolead->user->email : '' }}" />
                            <div id="email-error" style="color:red"></div>
                        </div>
                        <!-- ------------------------------------------------------------ :: -->
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">City</label>
                            <input type="text" class="form-control" placeholder="City" name="city"
                                value="{{ isset($demolead) ? $demolead->city : '' }}" />
                            <div id="city-error" style="color:red"></div>
                        </div>
                        @php
                            // dd($demolead);
                        @endphp
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Country <sup class="tcul-star-restrict">*</sup></label>
                            <select class="form-control select2" name="country">
                                <option value="">Select Country</option>
                                @if ($isAdminOrSuperAdmin)
                                    <option value="USA" {{ (isset($demolead) && strtoupper($demolead->country) == 'USA') ? 'selected' : '' }}>USA</option>
                                    <option value="CANADA" {{ (isset($demolead) && strtoupper($demolead->country) == 'CANADA') ? 'selected' : '' }}>CANADA</option>
                                    <option value="AUSTRALIA" {{ (isset($demolead) && strtoupper($demolead->country) == 'AUSTRALIA') ? 'selected' : '' }}>AUSTRALIA</option>
                                    <option value="NEWZEALAND" {{ (isset($demolead) && strtoupper($demolead->country) == 'NEWZEALAND') ? 'selected' : '' }}>NEW ZEALAND</option>
                                    <option value="INDIA" {{ (isset($demolead) && strtoupper($demolead->country) == 'INDIA') ? 'selected' : '' }}>INDIA</option>
                                    <option value="UAE" {{ (isset($demolead) && strtoupper($demolead->country) == 'UAE') ? 'selected' : '' }}>UAE</option>
                                    <option value="UK" {{ (isset($demolead) && strtoupper($demolead->country) == 'UK') ? 'selected' : '' }}>UK</option>
                                    <option value="SINGAPORE" {{ (isset($demolead) && strtoupper($demolead->country) == 'SINGAPORE') ? 'selected' : '' }}>SINGAPORE</option>
                                    <option value="SOUTHAFRICA" {{ (isset($demolead) && strtoupper($demolead->country) == 'SOUTHAFRICA') ? 'selected' : '' }}>SOUTH AFRICA</option>
                                    <option value="QATAR" {{ (isset($demolead) && strtoupper($demolead->country) == 'QATAR') ? 'selected' : '' }}>QATAR</option>
                                    <option value="BAHRAIN" {{ (isset($demolead) && strtoupper($demolead->country) == 'BAHRAIN') ? 'selected' : '' }}>BAHRAIN</option>
                                    <option value="KUWAIT" {{ (isset($demolead) && strtoupper($demolead->country) == 'KUWAIT') ? 'selected' : '' }}>KUWAIT</option>
                                    <option value="EUROPEAN UNION" {{ (isset($demolead) && strtoupper($demolead->country) == 'EUROPEAN UNION') ? 'selected' : '' }}>EUROPEAN UNION</option>
                                    <option value="OMAN" {{ (isset($demolead) && strtoupper($demolead->country) == 'OMAN') ? 'selected' : '' }}>OMAN</option>
                                @else
                                    @foreach ($allowedCountries as $country)
                                        <option value="{{ $country }}" {{ (isset($demolead) && strtoupper($demolead->country) == $country) ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div id="country-error" style="color:red"></div>
                        </div>
                        {{-- @if(Route::is('admin.demoleads.edit')) --}}
                        {{-- <div class="col-sm-12 col-md-6"></div>
                        @endif
                        <div class="col-sm-12 col-md-6" @if (Route::is('admin.demoleads.edit')) style="display: none;" @endif> --}}
                        <div class="col-sm-12 col-md-6" >
                            <label class="control-label col-form-label">Timezone <sup class="tcul-star-restrict">*</sup></label>
                            <select class="form-control select2" id="kidstimeZoneSelect" name="kids_time_zone">
                                <option value="" disabled>Select Time Zone</option>
                                @if(isset($demolead))
                                    <option value="{{ $demolead->kids_time_zone }}" selected>{{ $demolead->kids_time_zone }}</option>
                                @endif
                            </select>
                            <div id="demolead-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label class="control-label col-form-label"> Date <sup class="tcul-star-restrict">*</sup></label>
                            <input type="date" id="dateInput" class="form-control" name="date" value="{{ isset($demolead) ? $demolead->date : '' }}" />
                            <div id="date-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label class="control-label col-form-label"> Time <sup class="tcul-star-restrict">*</sup></label>
                            <input type="time" id="timeInput" class="form-control" name="time" value="{{ isset($demolead) ? $demolead->time : '' }}" />
                            <div id="time-error" style="color:red"></div>
                        </div>
                        <!-- ------------------------------------------------------------ :: -->
                        <div class="col-sm-12 col-md-3">
                            <label class="control-label col-form-label">Kids Date</label>
                            <input type="date" class="form-control" name="kids_date" value="{{ isset($demolead) ? $demolead->kids_date : '' }}" readonly style="background-color: #f5f5f5"/>
                            <div id="kids-date-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <label class="control-label col-form-label">Kids Time</label>
                            <input type="time" class="form-control" name="kids_time" value="{{ isset($demolead) ? $demolead->kids_time : '' }}" readonly style="background-color: #f5f5f5"/>
                            <div id="kids-time-error" style="color:red"></div>
                        </div>
                        <!-- ------------------------------------------------------------ :: -->
                        <div class="col-sm-12 col-md-12">
                            <label class="control-label col-form-label">Remark</label>
                            <input type="text" class="form-control" placeholder="Remark" name="remark"
                                value="{{ isset($demolead) ? $demolead->remark : '' }}" />
                            <div id="remark-error" style="color:red"></div>
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
                    <a href="{{ route('admin.demoleads.index') }}" type="button" class="btn btn-secondary">
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
    $(document).ready(function () {
        // Function to fetch and populate timezones
        function fetchTimezones(selectedTimezone = null) {
            var selectedCountry = $('select[name="country"]').val();
            if (!selectedCountry) {
                return;
            }

            $.ajax({
                url: '{{ route("admin.timezones.get.timezones") }}',
                method: 'GET',
                data: { country: selectedCountry },
                success: function (data) {
                    $('#kidstimeZoneSelect').empty().append('<option value="" disabled>Select Time Zone</option>');
                    $.each(data, function (groupLabel, timezones) {
                        var optgroup = $('<optgroup>').attr('label', groupLabel);
                        $.each(timezones, function (value, label) {
                            var option = $('<option>').attr('value', value).text(label);
                            if (value === selectedTimezone) {
                                option.attr('selected', 'selected');
                            }
                            optgroup.append(option);
                        });
                        $('#kidstimeZoneSelect').append(optgroup);
                    });
                    $('#kidstimeZoneSelect').trigger('change');
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching timezones:', error);
                }
            });
        }

        // Fetch timezones when the country changes
        $('select[name="country"]').change(function () {
            fetchTimezones();
        });

        // Fetch timezones if the country is pre-selected
        if ($('select[name="country"]').val()) {
            var selectedTimezone = '{{ isset($demolead) ? $demolead->kids_time_zone : null }}';
            fetchTimezones(selectedTimezone);
        }

        $('#kidstimeZoneSelect').on('select2:select', sendData);


        $('#dateInput').change(sendData);
        $('#timeInput').change(sendData);
        $('#mobile').on('input', checkMobileNumber);
    });

    function checkMobileNumber() {
        var mobile = $('#mobile').val();
        if (mobile.length > 0) {
            // Clear both messages at the start
            $('#unique_mobile-error').text('');
            $('#unique_mobile-success').text('');

            $.ajax({
                url: '{{ route("admin.demoleads.check.mobile") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    mobile: mobile
                },
                success: function(response) {
                    // Ensure only one message is shown at a time
                    if (response.status === 'error') {
                        $('#unique_mobile-error').text(response.message);
                        $('#unique_mobile-success').text('');
                    } else if (response.status === 'success') {
                        $('#unique_mobile-success').text(response.message);
                        $('#unique_mobile-error').text('');
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    // Ensure only the error message is shown
                    if (response && response.message) {
                        $('#unique_mobile-error').text(response.message);
                    } else {
                        $('#unique_mobile-error').text('An error occurred while checking the mobile number.');
                    }
                    $('#unique_mobile-success').text('');
                }
            });
        } else {
            // Clear both messages if the mobile input is empty
            $('#unique_mobile-error').text('');
            $('#unique_mobile-success').text('');
        }
    }
    $('#mobile').on('blur', checkMobileNumber);

    function sendData() {
        const date = document.getElementById('dateInput').value;
        const time = document.getElementById('timeInput').value;
        const timeZone = document.getElementById('kidstimeZoneSelect').value;
        $.ajax({
            url: '{{ route("admin.demoleads.processDateTimeZone") }}',
            type: 'POST',
            data: {
                date: date,
                time: time,
                timeZone: timeZone,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Assuming the response.convertedDateTime format is "YYYY-MM-DD HH:MM:SS"
                const dateTimeParts = response.convertedDateTime.split(' ');
                const datePart = dateTimeParts[0]; // This will be "YYYY-MM-DD"
                const timePart = dateTimeParts[1]; // This will be "HH:MM:SS"

                // Now, set these values in the respective input fields
                document.querySelector('input[name="kids_date"]').value = datePart;
                document.querySelector('input[name="kids_time"]').value = timePart.substring(0, 5); // Trimming to "HH:MM" format

                // Optionally, update the response display if needed
                document.getElementById('responseDisplay').innerHTML = JSON.stringify(response);
            },
            error: function(xhr, status, error) {
                // Assuming you have Toastr included in your project
                toastr.error("An error occurred: " + xhr.responseText);
            }
        });
    }

    $('#demoleads-form').submit(function(e) {
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
                        window.location.href = "{!! route('admin.demoleads.index') !!}";
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
