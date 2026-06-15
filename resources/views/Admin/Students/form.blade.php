@extends('layouts.admin')
@section('title')
    Student
@endsection
@section('content')
    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $isAdminOrSuperAdmin = in_array('Admin', $role) || in_array('SuperAdmin', $role);

        // Get the countries the user can see
        $allowedCountries = [];
        if (!$isAdminOrSuperAdmin) {
            $userRole = $user->roles()->first();
            if ($userRole && $userRole->countries) {
                $allowedCountries = json_decode($userRole->countries);
            }
        }
    @endphp
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: var(--bs-border-width) solid #dfe5ef;
            border-radius: 7px;
            height: 100%;
            background-clip: padding-box;
        }
    </style>
    <form method="POST"
        action="{{ Route::is('admin.students.create') ? route('admin.students.store') : route('admin.students.update', ['student' => $student->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="students-form">
        @csrf
        {{ Route::is('admin.students.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.students.create') ? 'Create' : 'Edit' }} Student </h5>
                    </div>
                    <div class="card-body border-top">
                        <h6 class="text-warning fs-4">Personal Info :</h6>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Full Name <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Full Name" name="first_name"
                                    value="{{ isset($student) ? $student->first_name : '' }}" />
                                <div id="first_name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Mobile <sup
                                        class="tcul-star-restrict">*</sup> (Include country code)</label>
                                <input type="text" class="form-control" placeholder="Mobile" name="mobile"
                                    value="{{ isset($student) ? $student->mobile : '' }}" />
                                <div id="mobile-error" style="color:red"></div>
                            </div>
                            {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Last Name</label>
                                <input type="text" class="form-control" placeholder="Last Name" name="last_name"
                                    value="{{ isset($student) ? $student->last_name : '' }}" />
                                <div id="last_name-error" style="color:red"></div>
                            </div> --}}

                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Student Id (Portal ID)<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Student Id" name="student_id" id="student_id"
                                    value="{{ isset($student) ? $student->student_id : '' }}" />
                                <div id="student_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Portal Password <sup
                                        class="tcul-star-restrict"></sup></label>
                                <input type="text" class="form-control" placeholder="Portal Password"
                                    name="portal_password" value="{{ isset($student) ? $student->portal_password : '' }}" />
                                <div id="portal_password-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Age</label>
                                <input type="text" class="form-control" placeholder="Age" name="age"
                                    value="{{ isset($student) ? $student->age : '' }}" />
                                <div id="age-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Email</label>
                                <input type="text" class="form-control" placeholder="Email" name="email"
                                    value="{{ isset($student) ? $student->user->email : '' }}" />
                                <div id="email-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">City </label>
                                <input type="text" class="form-control" placeholder="City" name="city"
                                    value="{{ isset($student) ? $student->city : '' }}" />
                                <div id="city-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Country <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="country" id="country-select">
                                    <option value="">Select Country</option>
                                    @if ($isAdminOrSuperAdmin)
                                        <option value="USA"
                                            {{ isset($student) && $student->country == 'USA' ? 'selected' : '' }}>USA
                                        </option>
                                        <option value="CANADA"
                                            {{ isset($student) && $student->country == 'CANADA' ? 'selected' : '' }}>
                                            CANADA</option>
                                        <option value="AUSTRALIA"
                                            {{ isset($student) && $student->country == 'AUSTRALIA' ? 'selected' : '' }}>
                                            AUSTRALIA</option>
                                        <option value="NEWZEALAND"
                                            {{ isset($student) && $student->country == 'NEWZEALAND' ? 'selected' : '' }}>
                                            NEW ZEALAND</option>
                                        <option value="INDIA"
                                            {{ isset($student) && $student->country == 'INDIA' ? 'selected' : '' }}>INDIA
                                        </option>
                                        <option value="UAE"
                                            {{ isset($student) && $student->country == 'UAE' ? 'selected' : '' }}>UAE
                                        </option>
                                        <option value="UK"
                                            {{ isset($student) && $student->country == 'UK' ? 'selected' : '' }}>UK
                                        </option>
                                        <option value="SINGAPORE"
                                            {{ isset($student) && $student->country == 'SINGAPORE' ? 'selected' : '' }}>
                                            SINGAPORE</option>
                                        <option value="SOUTH AFRICA"
                                            {{ isset($student) && $student->country == 'SOUTH AFRICA' ? 'selected' : '' }}>
                                            SOUTH AFRICA</option>
                                        <option value="QATAR"
                                            {{ isset($student) && in_array('QATAR', $student->countries ?? []) ? 'selected' : '' }}>
                                            QATAR</option>
                                        <option value="BAHRAIN"
                                            {{ isset($student) && in_array('BAHRAIN', $student->countries ?? []) ? 'selected' : '' }}>
                                            BAHRAIN</option>
                                        <option value="KUWAIT"
                                            {{ isset($student) && in_array('KUWAIT', $student->countries ?? []) ? 'selected' : '' }}>
                                            KUWAIT</option>
                                        <option value="EUROPEAN UNION"
                                            {{ isset($student) && $student->country == 'EUROPEAN UNION' ? 'selected' : '' }}>
                                            EUROPEAN UNION</option>
                                            <option value="OMAN"
                                            {{ isset($student) && in_array('OMAN', $student->countries ?? []) ? 'selected' : '' }}>
                                            OMAN</option>
                                    @else
                                        @foreach ($allowedCountries as $country)
                                            <option value="{{ $country }}"
                                                {{ isset($student) && $student->country == $country ? 'selected' : '' }}>
                                                {{ $country }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="country-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">
                                    Fees Country <sup class="tcul-star-restrict">*</sup>
                                </label>
                                <select class="form-control" name="fees_country" id="fees_country-select">
                                    <option value="">Select Fees Country</option>

                                    {{-- @if ($isAdminOrSuperAdmin) --}}
                                        @php
                                            $countries = [
                                                'USA',
                                                'CANADA',
                                                'AUSTRALIA',
                                                'NEWZEALAND',
                                                'INDIA',
                                                'UAE',
                                                'UK',
                                                'SINGAPORE',
                                                'SOUTH AFRICA',
                                                'QATAR',
                                                'BAHRAIN',
                                                'KUWAIT',
                                            ];
                                        @endphp

                                        @foreach ($countries as $country)
                                            <option value="{{ $country }}"
                                                {{ isset($student) && $student->fees_country === $country ? 'selected' : '' }}>
                                                {{ str_replace('_', ' ', $country) }}
                                            </option>
                                        @endforeach
                                    {{-- @endif --}}
                                </select>
                                <div id="fees_country-error" style="color: red"></div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <label class="control-label col-form-label">Time Zone <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="timezone" id="timezone-select">
                                    <option value="">Select Timezone</option>
                                </select>
                                <div id="timezone-error" style="color:red"></div>
                            </div>


                            <!-- <div class="col-sm-12 col-md-6">
                                        <label for="lastpayment_level_id" class="control-label col-form-label">Last Payment Level
                                            ID</label>
                                        <select class="form-control select2" name="lastpayment_level_id"
                                            id="lastpayment_level_id">
                                            <option value="">Select an Option</option>
                                            @foreach ($lastpayment_level_ids as $lastpayment_level_id)
    <option value="{{ $lastpayment_level_id->id }}"
                                                    {{ old('lastpayment_level_id', isset($student) && $student->lastpayment_level_id == $lastpayment_level_id->id ? 'selected' : '') }}>
                                                    {{ $lastpayment_level_id->name }} ({{ $lastpayment_level_id->level->name }})
                                                </option>
    @endforeach
                                        </select>
                                        <div id="lastpayment_level_id-error" style="color:red"></div>
                                    </div> -->




                            {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Currency</label>
                                <input type="text" class="form-control" placeholder="Currency" name="currency"
                                    value="{{ isset($student) ? $student->currency : '' }}">
                                <div id="currency-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Monthly Fees </label>
                                <input type="number" class="form-control" placeholder="Monthly Fees"
                                    name="monthly_fees" value="{{ isset($student) ? $student->monthly_fees : '' }}" />
                                <div id="monthly_fees-error" style="color:red"></div>
                            </div> --}}
                        </div>
                    </div>
                    {{-- <div class="card-body border-top">
                        <div class="row"> --}}
                    <!-- Student Photo -->
                    {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Student Photo </label>
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        <input class="form-control" type="file" name="image"
                                            class="form-control-file" id="student-image"
                                            placeholder="Please Select Image"></input>
                                        <div id="image-error" style="color:red"></div>
                                    </fieldset>
                                    @if (isset($student) && $student->image)
                                        <div class="mt-4">
                                            <label>Current Image:</label>
                                            <img src="{{ asset(Storage::url($student->image)) }}" alt="Image"
                                                class="mt-2 img-fluid img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                            </div> --}}
                    <!-- Level -->
                    {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Level</label>
                                <select class="form-control select2" name="level_id">
                                    <option value="">Select a level</option>
                                    @foreach ($levels as $level)
                                        @if (isset($student) && $student->level && $student->level->id == $level->id)
                                            <option value="{{ $level->id }}" selected>{{ $level->name }}</option>
                                        @else
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="level_id-error" style="color:red"></div>
                            </div> --}}
                    {{-- </div>
                    </div> --}}
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.students.index') }}" type="button" class="btn btn-secondary">
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
            const allowedPrefix = "ARCHER";

            $('#student_id').on('beforeinput', function (e) {
                const input = $(this);
                const current = input.val().toUpperCase();
                const newChar = e.originalEvent.data?.toUpperCase() || '';
                const selectionStart = this.selectionStart;
                const proposed = 
                    current.slice(0, selectionStart) + newChar + current.slice(this.selectionEnd);

                // Allow only valid prefix typing
                if (proposed.length <= allowedPrefix.length) {
                    for (let i = 0; i < proposed.length; i++) {
                        if (proposed[i] !== allowedPrefix[i]) {
                            e.preventDefault(); // block just that invalid keystroke
                            return;
                        }
                    }
                } else if (!proposed.startsWith(allowedPrefix)) {
                    e.preventDefault(); // block anything not starting with ARCHER
                }
            });

            $('#student_id').on('input', function () {
                this.value = this.value.toUpperCase(); // Always keep it uppercase
            });

            $('#student_id').on('paste', function (e) {
                const pasted = (e.originalEvent || e).clipboardData.getData('text').toUpperCase();
                if (!pasted.startsWith(allowedPrefix)) {
                    e.preventDefault(); // Block invalid paste
                } else {
                    // Delay to apply pasted content and force uppercase
                    const el = this;
                    setTimeout(() => {
                        el.value = el.value.toUpperCase();
                    }, 0);
                }
            });
        });



        $('#students-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.students.index') !!}";
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


        $(document).ready(function() {
            @if (isset($student))
                timeZones('{{ isset($student) ? $student->country : '' }}');
            @endif

            $('#country-select').on('change', function() {
                var selectedCountry = $('#country-select').val();
                // alert(selectedCountry);
                timeZones(selectedCountry);
            });

            function timeZones(selectedCountry) {
                var timezoneDropdown = $('#timezone-select');

                timezoneDropdown.empty();
                timezoneDropdown.append('<option value="">Select Timezone</option>');

                if (selectedCountry) {
                    $.ajax({
                        url: '{{ route('get.timezones') }}',
                        type: 'GET',
                        data: {
                            country: selectedCountry
                        },
                        success: function(response) {
                            // Populate timezones
                            $.each(response.timezones, function(key, value) {
                                timezoneDropdown.append('<option value="' + value +
                                    '">' + value + '</option>');
                            });

                            @isset($student)
                                timezoneDropdown.val('{{ $student->timezone }}').trigger('change');
                            @endisset
                        },

                        error: function(xhr) {
                            console.error("Error fetching timezones: ", xhr.responseText);
                        }
                    });
                }
            }
        });
    </script>
@endsection
