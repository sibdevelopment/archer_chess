@extends('layouts.admin')
@section('title')
    Tournaments
@endsection
@section('content')
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
                                <label class="control-label col-form-label">Batches <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="batch_ids[]" multiple id="batch-select">
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                                <div id="batch_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Students <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="student_ids[]" multiple id="student-select">
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->first_name }}
                                            {{ $student->last_name }} ({{ $student->mobile }})</option>
                                    @endforeach
                                </select>
                                <div id="student_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Levels <sup
                                        class="tcul-star-restrict">*</sup></label>
                                <select class="form-control" name="level_ids[]" multiple id="level-select">
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <div id="level_ids-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Tournament Name</label>
                                <input type="text" class="form-control" placeholder="Tournament Name" name="name" value="{{ Route::is('admin.tournaments.create') ? '' : $tournament->name }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Tournamet Date</label>
                                <input type="date" class="form-control" placeholder="Tournament Date" name="date"  value="{{ Route::is('admin.tournaments.create') ? '' : $tournament->date }}" />
                                <div id="date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Time</label>
                                <input type="time" class="form-control" placeholder="Tournament Time" name="time"  value="{{ Route::is('admin.tournaments.create') ? '' : $tournament->time }}" />
                                <div id="time-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Link</label>
                                <input type="text" class="form-control" placeholder="Tournament Link" name="link"  value="{{ Route::is('admin.tournaments.create') ? '' : $tournament->link }}" />
                                <div id="link-error" style="color:red"></div>
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
    </script>
@endsection
