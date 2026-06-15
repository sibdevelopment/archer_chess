@extends('layouts.admin')
@section('title')
    Masterclasses
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
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <form method="POST"
                    action="{{ Route::is('admin.masterclasses.create') ? route('admin.masterclasses.store') : route('admin.masterclasses.update', ['masterclass' => $masterclass->route_key]) }}"
                    enctype="multipart/form-data" autocomplete="off" id="masterclass-form">
                    @csrf
                    {{ Route::is('admin.masterclasses.create') ? '' : method_field('PUT') }}
                    <div class="card-header">
                        <h5>
                            {{ Route::is('admin.masterclasses.create') ? 'Add' : 'Edit' }} Masterclasses
                        </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Country <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="country[]" id="country-select" multiple>
                                    {{-- <option value="">Select Country</option> --}}
                                    @php
                                        $selectedCountries = isset($masterclass) ? $masterclass->country : [];
                                    @endphp
                                    @if ($isAdminOrSuperAdmin)
                                        <option value="USA" {{ in_array('USA', $selectedCountries) ? 'selected' : '' }}>
                                            USA
                                        </option>
                                        <option value="CANADA"
                                            {{ in_array('CANADA', $selectedCountries) ? 'selected' : '' }}>CANADA
                                        </option>
                                        <option value="AUSTRALIA"
                                            {{ in_array('AUSTRALIA', $selectedCountries) ? 'selected' : '' }}>AUSTRALIA
                                        </option>
                                        <option value="NEWZEALAND"
                                            {{ in_array('NEWZEALAND', $selectedCountries) ? 'selected' : '' }}>NEW ZEALAND
                                        </option>
                                        <option value="INDIA"
                                            {{ in_array('INDIA', $selectedCountries) ? 'selected' : '' }}>INDIA
                                        </option>
                                        <option value="UAE" {{ in_array('UAE', $selectedCountries) ? 'selected' : '' }}>
                                            UAE
                                        </option>
                                        <option value="UK" {{ in_array('UK', $selectedCountries) ? 'selected' : '' }}>UK
                                        </option>
                                        <option value="SINGAPORE"
                                            {{ in_array('SINGAPORE', $selectedCountries) ? 'selected' : '' }}>SINGAPORE
                                        </option>
                                        <option value="SOUTH AFRICA"
                                            {{ in_array('SOUTH AFRICA', $selectedCountries) ? 'selected' : '' }}>SOUTH
                                            AFRICA
                                        </option>
                                        <option value="QATAR"{{ in_array('QATAR', $selectedCountries) ? 'selected' : '' }}>QATAR</option>
                                        <option value="BAHRAIN" {{ in_array('BAHRAIN', $selectedCountries) ? 'selected' : '' }}>BAHRAIN</option>
                                        <option value="KUWAIT" {{ in_array('KUWAIT', $selectedCountries) ? 'selected' : '' }}>KUWAIT</option>
                                        <option value="EUROPEAN UNION" {{ in_array('EUROPEAN UNION', $selectedCountries) ? 'selected' : '' }}>EUROPEAN UNION</option>
                                        <option value="OMAN" {{ in_array('OMAN', $selectedCountries) ? 'selected' : '' }}>OMAN</option>
                                    @else
                                        @foreach ($allowedCountries as $country)
                                            <option value="{{ $country }}"
                                                {{ in_array($country, $selectedCountries) ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="country-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Batches <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="batch_ids[]" multiple id="batch-select">
                                </select>
                                <div id="batch_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Students <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="student_ids[]" multiple id="student-select">
                                </select>
                                <div id="student_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Levels <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="level_ids[]" multiple id="level-select">
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}"
                                            {{ isset($selectedLevels) && in_array($level->id, $selectedLevels) ? 'selected' : '' }}>
                                            {{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <div id="level_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Coach <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="coach_id">
                                    <option value="">Select Coach</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}"
                                            @if (isset($masterclass) && $masterclass->coach_id == $coach->id) selected @endif>
                                            {{ $coach->user->full_name }} </option>
                                    @endforeach
                                </select>
                                <div id="coach_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Masterclass Name</label>
                                <input type="text" class="form-control" placeholder="Masterclass Name" name="name"
                                    value="{{ Route::is('admin.masterclasses.create') ? '' : $masterclass->name }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Masterclass Date</label>
                                <input type="date" class="form-control" placeholder="Masterclass Date" name="date"
                                    value="{{ Route::is('admin.masterclasses.create') ? '' : $masterclass->date }}" />
                                <div id="date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Time</label>
                                <input type="time" class="form-control" placeholder="Masterclass Time" name="time"
                                    value="{{ Route::is('admin.masterclasses.create') ? '' : $masterclass->time }}" />
                                <div id="time-error" style="color:red"></div>
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
                        <a href="{{ route('admin.masterclasses.index') }}" type="button" class="btn btn-secondary">
                            Cancel
                            &nbsp;
                            <i class="ti ti-arrow-back-up-double"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#batch-select').select2({
                allowClear: true
            });
            $('#student-select').select2({
                allowClear: true
            });
            $('#level-select').select2({
                allowClear: true
            });



            @if (isset($masterclass))
                getBatches();
                getStudents();
            @endif
        });



        $(document).ready(function() {
            // Event listener for #level-select
            $('#level-select').on('change', function() {
                const levelValue = $('#level-select').val(); // Get selected values
                if (levelValue && levelValue.length > 0) {
                    $('#batch-select').prop('disabled', true).val(null).trigger('change');
                    $('#student-select').prop('disabled', true).val(null).trigger('change');
                } else {
                    $('#batch-select').prop('disabled', false);
                    $('#student-select').prop('disabled', false);
                }
            });

            // Event listener for #batch-select
            $('#batch-select').on('change', function() {
                const batchValue = $('#batch-select').val(); // Get selected values
                if (batchValue && batchValue.length > 0) {
                    $('#level-select').prop('disabled', true).val(null).trigger('change');
                    $('#student-select').prop('disabled', true).val(null).trigger('change');
                } else {
                    $('#level-select').prop('disabled', false);
                    $('#student-select').prop('disabled', false);
                }
            });

            // Event listener for #student-select
            $('#student-select').on('change', function() {
                const studentValue = $('#student-select').val(); // Get selected values
                if (studentValue && studentValue.length > 0) {
                    $('#level-select').prop('disabled', true).val(null).trigger('change');
                    $('#batch-select').prop('disabled', true).val(null).trigger('change');
                } else {
                    $('#level-select').prop('disabled', false);
                    $('#batch-select').prop('disabled', false);
                }
            });
        });


        $('#masterclass-form').submit(function(e) {
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
                            window.location.href = "{{ route('admin.masterclasses.index') }}";
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
                    toastr.error(xhr.responseJSON.message, '', {
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

        $("#country-select").on('change', function() {
            getBatches();
            getStudents();
        })

        function getBatches() {
            var countries = $("#country-select").val();
            $.ajax({
                type: "POST",
                url: "/admin/batchs/masterclass_tounament/list",
                data: {
                    countries: countries,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(data) {
                    var html = '';
                    // html += '<option value="">Select Batches</option>';
                    $.each(data.data, function(key, value) {
                        html += '<option value="' + value.id + '">' + value.name + ' (' + value.status +
                            ')</option>';
                    });
                    $('#batch-select').html(html);

                    @if (isset($masterclass))
                        var selectedbatch_ids = {!! json_encode($masterclass->batch_ids) !!};
                    @else
                        var selectedbatch_ids = [];
                    @endif

                    @isset($masterclass)
                        $('#batch-select').val(selectedbatch_ids).trigger('change');
                    @endisset

                }
            });
        }

        function getStudents() {
            var countries = $("#country-select").val();
            $.ajax({
                type: "POST",
                url: "/admin/students/masterclass_tounament/list",
                data: {
                    countries: countries,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(data) {

                    var html = '';
                    // html += '<option value="" disable>Select Students</option>';
                    $.each(data.data, function(key, value) {
                        var full_name = '';
                        if (value.first_name) {
                            full_name += value.first_name;
                        }
                        if (value.last_name) {
                            full_name += ' ' + value.last_name;
                        }
                        html += '<option value="' + value.id + '">' + full_name + ' (' + value.mobile +
                            ') -  ' + '(' + value.country + ')</option>';
                    });
                    $('#student-select').html(html);

                    @if (isset($masterclass))
                        var selectedstudent_ids = {!! json_encode($masterclass->student_ids) !!};
                    @else
                        var selectedstudent_ids = [];
                    @endif

                    @isset($masterclass)
                        $('#student-select').val(selectedstudent_ids).trigger('change');
                    @endisset

                }
            });
        }
    </script>
@endsection
