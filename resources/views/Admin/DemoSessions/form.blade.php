@extends('layouts.admin')
@section('title')
    Demo Session
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.demoleads.demo_sessions.create') ? route('admin.demoleads.demo_sessions.store', ['demolead' => $demolead->id]) : route('admin.demoleads.demo_sessions.update', ['demolead' => $demolead->id, 'demo_session' => $demosessions->id]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="demosessions-form">
        @csrf
        {{ Route::is('admin.demoleads.demo_sessions.create') ? '' : method_field('PUT') }}
        <input type="hidden" name="demolead_id" value="{{ $demolead->id }}">
        <div class="row">

            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5 class="text-black"> {{ Route::is('admin.demoleads.demo_sessions.create') ? 'Create' : 'Edit' }}
                            Demo Session </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row mb-4">
                            <div class="col-sm-6 col-md-3">
                                <label class="control-label col-form-label">Date <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" name="date"
                                    value="{{ isset($demolead) ? $demolead->date : '' }}" />
                                <div id="date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="control-label col-form-label">Time <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="time" class="form-control" name="time"
                                    value="{{ isset($demolead) ? $demolead->time : '' }}" />
                                <div id="time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Level</label>
                                <select class="form-control select2" name="level_id">
                                    <option value="">Select a level</option>
                                    @foreach ($levels as $level)
                                        @if (isset($demosessions) && $demosessions->level && $demosessions->level->id == $level->id)
                                            <option value="{{ $level->id }}" selected>{{ $level->name }}</option>
                                        @else
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="level_id-error" style="color:red"></div>
                            </div>
                        </div>
                        @if ($saved_coach_name && $saved_slot)
                            <div class="row mb-4">
                                <h6 class="text-warning fs-4">Saved Coach and Slot :</h6>
                                <div class="col-sm-6 col-md-3">
                                    <label class="control-label col-form-label">Coach Name <sup
                                            class="tcul-star-restrict">*</sup></label>
                                    <input type="text" class="form-control" name="saved_coach_name"
                                        value="{{ $saved_coach_name }}" readonly />
                                    <input type="text" class="form-control" name="saved_coach_id"
                                        value="{{ $saved_coach_id }}" readonly hidden />
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label class="control-label col-form-label">Slot <sup
                                            class="tcul-star-restrict">*</sup></label>
                                    <input type="text" class="form-control" name="saved_slot"
                                        value="{{ $saved_slot }}" readonly />
                                    <input type="text" class="form-control" name="saved_slot_normal"
                                        value="{{ $saved_slot_normal }}" readonly hidden />
                                </div>
                            </div>
                        @endif
                        {{-- @php
                            dd($coaches);
                        @endphp --}}
                        <div class="row">
                            @if ($saved_coach_name && $saved_slot)
                                <h6 class="text-warning fs-4">Update the Coach and Slot:</h6>
                            @else
                                <h6 class="text-warning fs-4">Select The Coach :</h6>
                            @endif
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Coach<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="coach_id">
                                    <option value="">Select a coach</option>
                                    
                                    @foreach ($coaches as $coach)
                                        @if (isset($demosessions) && $demosessions->coach_id == $coach->id)
                                            <option value="{{ $coach->id }}" selected>{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @else
                                            <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="coach_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <label class="control-label col-form-label">Slot <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control select2" name="slot">
                                    <option value="">Select a slot</option>
                                    @foreach ($slots as $slot)
                                        <option value="{{ $slot }}"
                                            {{ $slot == $demosessions->slot ? 'selected' : '' }}>{{ $slot }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="slot-error" style="color:red"></div>
                            </div>
                            <div id="zoom-error" style="color:red"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                            &nbsp;
                            <i class="ti ti-device-floppy"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="{{ route('admin.demoleads.demo_sessions.index', ['demolead' => $demolead->id]) }}"
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
        $(document).ready(function() {
            $('input[name="date"], input[name="time"]').change(function() {
                var date = $('input[name="date"]').val();
                var time = $('input[name="time"]').val();
                $.ajax({
                    url: '{{ route('admin.demo_sessions.coach_availability') }}',
                    method: 'GET',
                    data: {
                        date: date,
                        time: time,
                        demolead_id: '{{ $demolead->id }}',
                    },
                    success: function(data) {
                        var coachSelect = $('select[name="coach_id"]');
                        coachSelect.empty();
                        coachSelect.append('<option value="">Select a coach</option>');
                        $.each(data, function(key, value) {
                            var selected = value.coach.id ==
                                '{{ $demosessions->coach_id ?? '' }}' ? ' selected' :
                                '';
                            coachSelect.append('<option value="' + value.coach.id +
                                '"' + selected + '>' + value.coach.user.first_name +
                                ' ' + value.coach.user.last_name + '</option>');
                        });
                    }
                });
            });

            $('select[name="coach_id"]').change(function() {
                var coachId = $(this).val();
                var date = $('input[name="date"]').val();
                var time = $('input[name="time"]').val();

                $.ajax({
                    url: '{{ route('admin.demo_sessions.available_slots') }}',
                    method: 'GET',
                    data: {
                        coach_id: coachId,
                        date: date,
                        time: time
                    },
                    success: function(data) {
                        var slotSelect = $('select[name="slot"]');
                        slotSelect.empty();

                        $.each(data, function(key, value) {
                            var selected = value == '{{ $demosessions->slot ?? '' }}' ?
                                ' selected' : '';
                            slotSelect.append('<option value="' + value + '"' +
                                selected + '>' + value + '</option>');
                        });
                    }
                });
            });

            // Trigger the change event manually to load initial data
            $('input[name="date"], input[name="time"], select[name="coach_id"]').change();
        });

        $('#demosessions-form').submit(function(e) {
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
                            window.location.href = '{!! route('admin.demoleads.demo_sessions.index', ['demolead' => $demolead->id]) !!}';
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
