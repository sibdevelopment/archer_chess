@extends('layouts.admin')
@section('title')
Coach Availability
@endsection
@section('content')
<section>
    <div class="row">
        <div class="col-3">
            <a href="/admin/coaches/{{ $coach->route_key }}/coach_availabilities"
                class="card w-100 position-relative overflow-hidden mb-1 text-white bg-info">
                <div class="p-3 fw-semibold">
                    @if(!empty($coach->user->first_name))
                    Name : {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                    @endif
                </div>
            </a>
            <div class="card w-100 position-relative overflow-hidden" style="margin-bottom:2%;">
                <div class="card-header px-4 py-3 border-bottom">
                    @if(!empty($coach->user->email))
                    <p class="text-black fw-semibold">Email : {{ $coach->user->email }}</p>
                    @endif
                    @if(!empty($coach->user->mobile))
                    <p class="text-black fw-semibold">Mobile : {{ $coach->user->mobile }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-9">
            <form method="POST"
                action="{{ Route::is('admin.coaches.coach_availabilities.create') ? route('admin.coaches.coach_availabilities.store', ['coach' => $coach->routekey]) : route('admin.coaches.coach_availabilities.updateAll', ['coach' => $coach->routekey]) }}"
                method="POST" enctype="multipart/form-data" autocomplete="off" id="coachavailability-form">
                @csrf
                {{ Route::is('admin.coaches.coach_availabilities.create') ? '' : method_field('PUT') }}
                <input type="hidden" name="coach_id" value="{{ $coach->id }}">
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-header">
                                <div class="row" style="">
                                    <div class="col-md-6  d-flex justify-content-start">
                                        <h4>{{ Route::is('admin.coaches.coach_availabilities.create') ? 'Create' :
                                            'Edit' }} Coach Availability :</h4>
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
                            <!-- Hidden input field to hold the ids of days to be deleted & ids of periods to be deleted ::  -->
                            <input type="hidden" id="deleted-days" name="deleted_days">
                            <input type="hidden" id="deleted-periods" name="deleted_periods">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                    &nbsp;
                                    <i class="ti ti-device-floppy"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('admin.coaches.coach_availabilities.index', ['coach' => $coach->routekey]) }}"
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
        </div>
    </div>
</section>
<script>
    // Add Day ::
    $(document).ready(function () {
        var deleteDay = [];
        $('#day-add-btn').on('click', function () {
            addDay();
        });
        // Add Day Ajax::
        function addDay() {
            $.ajax({
                type: "POST",
                url: '/admin/coach_availabilities/add-day',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    $('#day-div').prepend(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(xhr.responseJSON.message, '');
                }
            });
        };
        // Add period ::
        $(document).on('click', '.add-period-btn', function (event) {
            event.preventDefault(); // Prevents the default action (form submission, if any)
            event.stopPropagation(); // Stops the event from bubbling up to parent elements
            var unique_row_id = $(this).data('unique-row-id');
            addPeriod(unique_row_id);
        });
        // Add period function ::
        function addPeriod(unique_row_id) {
            $.ajax({
                type: "POST",
                url: '/admin/coach_availabilities/add-day-period',
                data: {
                    _token: '{{ csrf_token() }}',
                    unique_row_id: unique_row_id,
                },
                success: function (data) {
                    $('#period-div-' + unique_row_id).prepend(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(xhr.responseJSON.message, '');
                }
            });
        }

        // Edit Day (then load periods for that day so period-div exists before appending)
        @if (isset($coach))
            @if ($coach->coach_availabilities)
                @foreach($coach->coach_availabilities as $availability)
                    (function (availabilityId, periodIds) {
                        $.ajax({
                            type: "POST",
                            url: '/admin/coach_availabilities/edit-day',
                            data: {
                                _token: '{{ csrf_token() }}',
                                coachday_id: availabilityId,
                                coach_id: '{{ isset($coach) ? $coach->id : '' }}'
                            },
                            success: function (data) {
                                $('#day-div').prepend(data);
                                // Load periods only after this day's HTML is in the DOM
                                periodIds.forEach(function (periodId) {
                                    $.ajax({
                                        type: "POST",
                                        url: '/admin/coach_availabilities/edit-period',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            period_id: periodId,
                                            availability_id: availabilityId
                                        },
                                        success: function (periodData) {
                                            $('#period-div-' + availabilityId).append(periodData);
                                        },
                                        error: function (xhr, ajaxOptions, thrownError) {
                                            toastr.error(xhr.responseJSON.message, '');
                                        }
                                    });
                                });
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(xhr.responseJSON.message, '');
                            }
                        });
                    })({{ $availability->id }}, {!! json_encode($availability->periods->pluck('id')->values()) !!});
                @endforeach
            @endif
        @endif

        // Remove day
        $(document).on('click', '.day-remove-btn', function () {
            var unique_row_id = $(this).attr('data-unique-row-id');
            $('#whole-div-' + unique_row_id).remove();
            // Add Day ID to hidden input for later deletion
            var deletedDays = $('#deleted-days').val();
            $('#deleted-days').val(deletedDays ? deletedDays + ',' + unique_row_id : unique_row_id);
        });

        // Remove period
        $(document).on('click', '.period-remove-btn', function () {
            var unique_row_id = $(this).attr('data-unique-row-id');
            $('#whole-div-' + unique_row_id).remove();
            // Add Period ID to hidden input for later deletion
            var deletedPeriods = $('#deleted-periods').val();
            $('#deleted-periods').val(deletedPeriods ? deletedPeriods + ',' + unique_row_id : unique_row_id);
        });
    });

    $('#coachavailability-form').submit(function (e) {
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
            success: function (data) {
                if (data.status == 'success') {
                    toastr.success(data.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function () {
                        window.location.href = '{!! route('admin.coaches.coach_availabilities.index', ['coach' => $coach->routekey]) !!}';
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
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error('There are some errors in Form. Please check your inputs', '', {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    timeOut: 1500,
                    closeButton: true,
                });
                $.each(xhr.responseJSON.errors, function (key, value) {
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