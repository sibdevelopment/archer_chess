@extends('layouts.admin')
@section('title')
    Tournaments
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
                <form method="POST" action="{{ Route::is('admin.tournaments.create') ? route('admin.tournaments.store') : route('admin.tournaments.update', ['tournament' => $tournament->route_key]) }}" enctype="multipart/form-data" autocomplete="off" id="tournament-form">
                    @csrf
                    {{ Route::is('admin.tournaments.create') ? '' : method_field('PUT') }}
                    <div class="card-header">
                        <h5>
                            {{ Route::is('admin.tournaments.create') ? 'Add' : 'Edit' }} Tournament
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
                                        $selectedCountries = isset($tournament) ? ($tournament->country) : [];
                                    @endphp
                                    @if ($isAdminOrSuperAdmin)
                                        <option value="USA"
                                            {{ in_array('USA', $selectedCountries) ? 'selected' : '' }}>USA
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
                                        <option value="UAE"
                                            {{ in_array('UAE', $selectedCountries) ? 'selected' : '' }}>UAE
                                        </option>
                                        <option value="UK"
                                            {{ in_array('UK', $selectedCountries) ? 'selected' : '' }}>UK
                                        </option>
                                        <option value="SINGAPORE"
                                            {{ in_array('SINGAPORE', $selectedCountries) ? 'selected' : '' }}>SINGAPORE
                                        </option>
                                        <option value="SOUTH AFRICA"
                                            {{ in_array('SOUTH AFRICA', $selectedCountries) ? 'selected' : '' }}>SOUTH AFRICA
                                        </option>
                                        <option value="QATAR"
                                            {{ in_array('QATAR', $selectedCountries) ? 'selected' : '' }}>QATAR
                                        </option>
                                        <option value="BAHRAIN"
                                            {{ in_array('BAHRAIN', $selectedCountries) ? 'selected' : '' }}>BAHRAIN
                                        </option>
                                        <option value="KUWAIT"
                                            {{ in_array('KUWAIT', $selectedCountries) ? 'selected' : '' }}>KUWAIT
                                        </option>
                                        <option value="EUROPEAN UNION"
                                            {{ in_array('EUROPEAN UNION', $selectedCountries) ? 'selected' : '' }}>EUROPEAN UNION
                                        </option>
                                        <option value="OMAN"
                                            {{ in_array('OMAN', $selectedCountries) ? 'selected' : '' }}>OMAN
                                        </option>
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
                                    {{-- @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}"
                                            {{ isset($selectedBatches) && in_array($batch->id, $selectedBatches) ? 'selected' : '' }}>
                                            {{ $batch->name }}
                                        </option>
                                    @endforeach --}}
                                </select>
                                <div id="batch_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Students <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="student_ids[]" multiple id="student-select">
                                    {{-- @foreach ($students as $student)
                                        <option value="{{ $student->id }}"
                                            {{ isset ($selectedStudents) && in_array($student->id, $selectedStudents) ? 'selected' : '' }}>
                                            {{ $student->first_name }}
                                            {{ $student->last_name }} ({{ $student->mobile }})</option>
                                    @endforeach --}}
                                </select>
                                <div id="student_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Levels <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="level_ids[]" multiple id="level-select">
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}"
                                            {{ isset ($selectedLevels) && in_array($level->id, $selectedLevels) ? 'selected' : '' }}>
                                            {{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <div id="level_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Tournament Name</label>
                                <input type="text" class="form-control" placeholder="Tournament Name" name="name" value="{{ isset($tournament) ? $tournament->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Tournamet Date</label>
                                <input type="date" class="form-control" placeholder="Tournament Date" name="date"  value="{{ isset($tournament) ? $tournament->date : '' }}" />
                                <div id="date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Time</label>
                                <input type="time" class="form-control" placeholder="Tournament Time" name="time"  value="{{ isset($tournament) ? $tournament->time : '' }}" />
                                <div id="time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Link</label>
                                <input type="text" class="form-control" placeholder="Tournament Link" name="link"  value="{{ isset($tournament) ? $tournament->link  : ''}}" />
                                <div id="link-error" style="color:red"></div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Certificate (1755 × 1241)</label>
                                <input type="file" class="form-control" placeholder="Tournament Certificate" name="certificate" accept=".jpeg, .jpg" />
                                <div id="certificate-error" style="color:red"></div>
                            </div>
                            @if(isset($tournament) && !empty($tournament->certificate))
                                <div class="col-sm-12 col-md-6">
                                    <label class="control-label col-form-label">Certificate</label>
                                    <div>
                                        <img src="{{ asset(\Storage::url($tournament->certificate['path'])) }}" alt="Certificate" style="max-width: 50%; height: auto; display: block;">
                                    </div>
                                    <div>
                                        <a href="{{ url('admin/tournaments/view/certificate/' . $tournament->route_key) }}" class="btn btn-primary mt-2" style="display: block; width: 50%;" target="_blank">Download Certificate</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.tournaments.index') }}" type="button" class="btn btn-secondary">
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

            @if(isset($tournament))
                getBatches();
                getStudents();
            @endif
        });

        $('#tournament-form').submit(function(e) {
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
                            window.location.href = "{{ route('admin.tournaments.index') }}";
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

        $("#country-select").on('change',function(){
            getBatches();
            getStudents();
        })

        function getBatches() {
            var countries = $("#country-select").val();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.batchs.list') }}",
                data: {
                    countries: countries,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(data) {
                    var html = '';
                    // html += '<option value="">Select Batches</option>';
                    $.each(data.data, function(key,value) {
                        html += '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#batch-select').html(html);

                    @if(isset($tournament))
                    var selectedbatch_ids = {!! json_encode($tournament->batch_ids) !!};

                    @else
                        var selectedbatch_ids = [];
                    @endif

                    @isset($tournament)
                    $('#batch-select').val(selectedbatch_ids).trigger('change');
                    @endisset
                 
                }
            });
        }
        
        function getStudents() {
            var countries = $("#country-select").val();
            $.ajax({
                type: "POST",
                url: "{{ route('admin.students.list') }}",
                data: {
                    countries: countries,
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(data) {

                    var html = '';
                    // html += '<option value="" disable>Select Students</option>';
                    $.each(data.data, function(key,value) {
                        var full_name = '';
                        if (value.first_name) {
                            full_name += value.first_name;
                        }
                        if (value.last_name) {
                            full_name += ' ' + value.last_name;
                        }
                        html += '<option value="' + value.id + '">' +full_name + ' (' + value.mobile + ') -  '+'('+ value.country +')</option>';
                    });
                    $('#student-select').html(html);

                    @if(isset($tournament))
                    var selectedstudent_ids = {!! json_encode($tournament->student_ids) !!};

                    @else
                        var selectedstudent_ids = [];
                    @endif

                    @isset($tournament)
                    $('#student-select').val(selectedstudent_ids).trigger('change');
                    @endisset
                 
                }
            });
        }
    </script>
@endsection
