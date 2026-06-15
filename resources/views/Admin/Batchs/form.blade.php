@extends('layouts.admin')
@section('title')
    Batch
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
        action="{{ Route::is('admin.batchs.create') ? route('admin.batchs.store') : route('admin.batchs.update', ['batch' => $batch->route_key]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="batchs-form">
        @csrf
        {{ Route::is('admin.batchs.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.batchs.create') ? 'Create' : 'Edit' }} Batch </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Name" id="name" name="name"
                                    value="{{ isset($batch) ? $batch->name : '' }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Kids Zone Name</label>
                                <input type="text" class="form-control" placeholder="Kids Zone Name"
                                    name="kids_zone_name" value="{{ isset($batch) ? $batch->kids_zone_name : '' }}" />
                                <div id="kids-zone-name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Coach <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" id="coach_id" name="coach_id"
                                    {{ isset($batch) ? 'disabled' : '' }}>
                                    <option value="">Select a coach</option>
                                    @foreach ($coaches as $coach)
                                        @if (isset($batch) && $batch->coach_id == $coach->id)
                                            <option value="{{ $coach->id }}" selected>{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @else
                                            <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="coach_id-error" style="color:red"></div>
                                @if (isset($batch))
                                    <div class="text-secondary mt-2">You can't change the coach once it has been set.</div>
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Country <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="country[]" multiple="multiple">
                                    <option value="">Select Country</option>
                                    @if ($isAdminOrSuperAdmin)
                                        <option value="USA"
                                            {{ isset($batch) && in_array('USA', $batch->country ?? []) ? 'selected' : '' }}>
                                            USA
                                        </option>
                                        <option value="CANADA"
                                            {{ isset($batch) && in_array('CANADA', $batch->country ?? []) ? 'selected' : '' }}>
                                            CANADA
                                        </option>
                                        <option value="AUSTRALIA"
                                            {{ isset($batch) && in_array('AUSTRALIA', $batch->country ?? []) ? 'selected' : '' }}>
                                            AUSTRALIA
                                        </option>
                                        <option value="NEWZEALAND"
                                            {{ isset($batch) && in_array('NEWZEALAND', $batch->country ?? []) ? 'selected' : '' }}>
                                            NEW ZEALAND
                                        </option>
                                        <option value="INDIA"
                                            {{ isset($batch) && in_array('INDIA', $batch->country ?? []) ? 'selected' : '' }}>
                                            INDIA
                                        </option>
                                        <option value="UAE"
                                            {{ isset($batch) && in_array('UAE', $batch->country ?? []) ? 'selected' : '' }}>
                                            UAE
                                        </option>
                                        <option value="UK"
                                            {{ isset($batch) && in_array('UK', $batch->country ?? []) ? 'selected' : '' }}>
                                            UK
                                        </option>
                                        <option value="SINGAPORE"
                                            {{ isset($batch) && in_array('SINGAPORE', $batch->country ?? []) ? 'selected' : '' }}>
                                            SINGAPORE
                                        </option>
                                        <option value="QATAR"
                                            {{ isset($batch) && in_array('QATAR', $batch->countries ?? []) ? 'selected' : '' }}>
                                            QATAR
                                        </option>
                                        <option value="BAHRAIN"
                                            {{ isset($batch) && in_array('BAHRAIN', $batch->countries ?? []) ? 'selected' : '' }}>
                                            BAHRAIN
                                        </option>
                                        <option value="KUWAIT"
                                            {{ isset($batch) && in_array('KUWAIT', $batch->countries ?? []) ? 'selected' : '' }}>
                                            KUWAIT
                                        </option>
                                        <option value="EUROPEAN UNION"
                                            {{ isset($batch) && in_array('EUROPEAN UNION', $batch->countries ?? []) ? 'selected' : '' }}>
                                            EUROPEAN UNION
                                        </option>
                                        <option value="OMAN"
                                            {{ isset($batch) && in_array('OMAN', $batch->countries ?? []) ? 'selected' : '' }}>
                                            OMAN
                                        </option>
                                    @else
                                        @foreach ($allowedCountries as $country)
                                            <option value="{{ $country }}"
                                                {{ isset($batch) && in_array($country, $batch->country ?? []) ? 'selected' : '' }}>
                                                {{ $country }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="country-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 d-flex align-items-stretch">
                            <div class="card w-100">
                                <div class="card-header">
                                    <div class="row" style="">
                                        <div class="col-md-6  d-flex justify-content-start">
                                            <h5>Batch Schedules : </h5>
                                        </div>
                                        <div class="col-md-6  d-flex justify-content-end">
                                            <a id="day-add-btn" style="margin-top:-1.5%;"
                                                class="btn btn-md btn-primary pull-right"> Add Day &nbsp; <i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="" id="add-day-card">
                                    <div class="card-body">
                                        <div id="day_name-error" style="color:red"></div>
                                        <div id="day-div">

                                        </div>
                                    </div>
                                </div>
                                <!-- Hidden input field to hold the ids of days to be deleted ::  -->
                                <input type="hidden" id="deleted-days" name="deleted_days">
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
                        <a href="{{ route('admin.batchs.index') }}" type="button" class="btn btn-secondary">
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
        // Add Day ::
        $(document).ready(function() {
            var deleteDay = [];
            $('#day-add-btn').on('click', function() {
                addDay();
            });

            $('#name').on('input', function() {
                $.ajax({
                    type: "POST",
                    url: '/admin/batches/check-name',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: $('#name').val(),
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            $('#name-error').html('');
                        } else {
                            $('#name-error').html(data.message);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        toastr.error(xhr.responseJSON.message, '');
                    }
                });
            });



            // Add Day Ajax::
            function addDay() {
                $.ajax({
                    type: "POST",
                    url: '/admin/batch_schedules/add-weekday',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        $('#day-div').prepend(data);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        toastr.error(xhr.responseJSON.message, '');
                    }
                });
            };

            // Edit Day ::
            @if (isset($batch))
                @if ($batch->batchSchedules)
                    @foreach ($batch->batchSchedules as $schedule)
                        $.ajax({
                            type: "POST",
                            url: '/admin/batch_schedules/edit-weekday',
                            data: {
                                _token: '{{ csrf_token() }}',
                                schedule_id: '{!! $schedule->id !!}',
                                batch_id: '{{ $batch->id }}'
                            },
                            success: function(data) {
                                $('#day-div').prepend(data);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                toastr.error(xhr.responseJSON.message, '');
                            }
                        });
                    @endforeach
                @endif
            @endif

            // Remove day
            $(document).on('click', '.day-remove-btn', function() {
                var unique_row_id = $(this).attr('data-unique-row-id');
                $('#whole-div-' + unique_row_id).remove();
                // Add Day ID to hidden input for later deletion
                var deletedDays = $('#deleted-days').val();
                $('#deleted-days').val(deletedDays ? deletedDays + ',' + unique_row_id : unique_row_id);
            });
        });
    </script>
    <script>
        $('#batchs-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.batchs.index') !!}";
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

    <script>
        $(document).on('change', '.weekday, .from_time, .to_time, #coach_id', function() {
            checkBatchSchedule();
        });

        function checkBatchSchedule() {
            let weekday = $('.weekday').val();
            let fromTime = $('.from_time').val();
            let toTime = $('.to_time').val();
            let coach_id = $('#coach_id').val();

            $.ajax({
                url: "{{ route('admin.batchs.check.schedule') }}",
                type: "POST",
                data: {
                    weekday: weekday,
                    from_time: fromTime,
                    to_time: toTime,
                    coach_id: coach_id,
                    _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                },
                success: function(response) {
                    console.log(response);
                    console.log(response.status);
                    if (response.status === "error") {
                        alert("This schedule already exists!");
                    } else {
                        console.log("Schedule is available.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                    console.error(status);
                    console.error(error);
                }
            });
        }
    </script>

@endsection
