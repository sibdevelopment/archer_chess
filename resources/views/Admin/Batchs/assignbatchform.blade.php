@extends('layouts.admin')
@section('title')
    Batch Assign To Students
@endsection
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: var(--bs-border-width) solid #dfe5ef;
            border-radius: 7px;
            height: 100%;
            background-clip: padding-box;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5> Batch </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($batch->name)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-id-badge-2 text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Name : {{ $batch->name }}
                                            @if ($batch->is_one_to_one)
                                                <span class="badge bg-dark ms-2">1-1 Batch</span>
                                            @endif
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($batch->kids_zone_name)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-id-badge-2 text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Kids Zone : {{ $batch->kids_zone_name }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($batch->coach_id)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-user-circle text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Coach: {{ $batch->coach->user->first_name }}
                                            {{ $batch->coach->user->last_name }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($batch->country)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-id-badge-2 text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Country : {{ implode(', ', $batch->country) }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="row" style="border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
                        <div class="col-12">
                            <h5 class="text-start mb-4">Schedules</h5>
                        </div>
                        @foreach ($batchSchedules as $schedule)
                            <div class="col-lg-6 d-flex align-items-stretch">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-calendar text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            {{ $schedule->weekday }}:
                                            {{ \Carbon\Carbon::parse($schedule->from_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($schedule->to_time)->format('h:i A') }}
                                        </h6>
                                    </li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-flex align-items-stretch">
            <form method="POST" action="{{ route('admin.batchs.assigned.student.save', ['batch' => $batch->id]) }}"
                enctype="multipart/form-data" autocomplete="off" id="batch-form">
                @csrf
                <!-- The rest of your form content -->
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100"
                            style="{{ $batch->confirm_reassign === 'CANCEL' ? 'border: 1px solid #FF4D4D !important;' : '' }}">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5>Batch Assign To Students</h5>
                                    </div>
                                    @php
                                        $isReassign = false;
                                        $today = \Carbon\Carbon::now()->startOfDay(); // Reset time to 00:00:00
                                        $batch_end_date = \Carbon\Carbon::parse($batch->end_date)->startOfDay(); // Reset time to 00:00:00

                                        if ($batch_end_date->lt($today)) {
                                            $isReassign = true;
                                        }
                                    @endphp


                                    @if ($isReassign)
                                        <div class="col-6 d-flex justify-content-end">
                                            <button type="button"
                                                class="btn {{ $batch->confirm_reassign === 'CANCEL' ? 'btn-danger' : 'btn-primary' }} reassign-modal-trigger"
                                                data-entity="reassign" data-title="Reassign Student"
                                                data-batch-id="{{ $batch->id }}"
                                                {{ $batch->confirm_reassign === 'CANCEL' ? 'readonly' : '' }}>
                                                Reassign&nbsp;<i class="ti ti-school"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-6 d-flex justify-content-end">
                                            <p class="text-danger">Reassign is readonly for this batch. because the batch is
                                                not ended yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    {{-- <h6 class="text-warning fs-4">Personal Info :</h6> --}}
                                    <!-- Roll No. (Portal ID) -->
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Student ID (Portal ID) <sup
                                                class="tcul-star-restrict">*</sup></label>
                                                <select class="form-control select2"
        name="student_ids[]" multiple="multiple"
        id="student_ids"
        data-one-to-one="{{ $batch->is_one_to_one ? 'YES' : 'NO' }}"
        @if($batch->status == 'INACTIVE') data-readonly="true" @endif>

                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}"
                                                    @foreach ($assignedStudents as $assignedStudent) {{ $assignedStudent->student_id == $student->id ? 'selected' : '' }} @endforeach
                                                    {{ in_array($student->id, $preselectedStudentIds ?? []) ? 'selected' : '' }}>
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                    @if (!is_null($student->student_id))
                                                        ({{ $student->student_id }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="student_ids-error" style="color:red"></div>
                                    </div>
                                    <input type="hidden" id="removed_student_ids" name="removed_student_ids"
                                        value="">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Coach</label>
                                        <select class="form-control select2" name="coach_id" readonly>
                                            <option value="">Select a coach</option>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}"
                                                    @if ($batch->coach_id == $coach->id) selected @endif>
                                                    {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="coach_id" value="{{ $batch->coach_id }}">
                                        <div id="coach_id-error" style="color:red"></div>
                                    </div>
                                    <!-- Level -->
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Level <sup
                                                class="tcul-star-restrict">*</sup></label>

                                        <select class="form-control select2" name="levelid" id="level_id"
                                            @if ($is_hide == 1) disabled @endif>
                                            <option value="">Select Level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}"
                                                    @if ($batch->level_id == $level->id) selected @endif>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- Hidden input to store the selected value -->
                                        <input type="hidden" id="hidden_level_id" name="level_id"
                                            value="{{ $batch->level_id }}">

                                        <div id="level_id-error" style="color:red"></div>
                                    </div>

                                    {{-- <div class="col-sm-12 col-md-3">
                                        <label class="control-label col-form-label">Status <sup
                                                class="tcul-star-restrict">*</sup></label>
                                        <select class="form-control" name="status">
                                            <option value="INACTIVE"
                                                {{ !empty($batch->status) && $batch->status == 'INACTIVE' ? 'selected' : '' }}>
                                                INACTIVE
                                            </option>
                                            <option value="ACTIVE"
                                                {{ !empty($batch->status) && $batch->status == 'ACTIVE' ? 'selected' : '' }}>
                                                ACTIVE
                                            </option>
                                        </select>
                                    </div> --}}
                                    <div class="col-sm-12 col-md-3">
                                        <label class="control-label col-form-label">Number of Sessions <sup
                                                class="tcul-star-restrict">*</sup></label>
                                        <input type="number" class="form-control" name="number_of_sessions"
                                            value="{{ $batch->number_of_sessions ?? 8 }}" required
                                            @if ($is_hide == 1) readonly @endif>
                                        <div id="number_of_sessions-error" style="color:red"></div>
                                    </div>
                                    @php
                                        // dd($batch);15 17 14 16
                                    @endphp
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Start Date <sup
                                                class="tcul-star-restrict">*</sup></label>
                                        @php
                                            $readonly = (!$is_edit && $is_hide);
                                        @endphp
                                        <input type="date" class="form-control" name="start_date"
                                            value="{{ $prefillStartDate ? \Carbon\Carbon::parse($prefillStartDate)->format('Y-m-d') : (isset($batch) && $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('Y-m-d') : '') }}"
                                            @if ($readonly) readonly @endif
                                            >
                                        <div id="start_date-error" style="color:red"></div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">
                                            End Date <sup class="tcul-star-restrict">*</sup>
                                        </label>
                                        @php
                                            $readonly = (!$is_edit && $is_hide);
                                        @endphp

                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            name="end_date"
                                            value="{{ $prefillEndDate ? \Carbon\Carbon::parse($prefillEndDate)->format('Y-m-d') : (isset($batch) && $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('Y-m-d') : '') }}"
                                            @if ($readonly) readonly @endif
                                        >


                                        <div id="end_date-error" style="color:red"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-6" style="display: none;">
                                        <label class="control-label col-form-label">Confirm Reassign</label>
                                        <input type="text" class="form-control" name="confirm_reassign"
                                            value="{{ $batch->confirm_reassign ?? '' }}">
                                        <div id="confirm_reassign-error" style="color:red"></div>
                                    </div>
                                    <input type="hidden" name="batch_id" value="{{ $batch->id }}" />
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
        </div>
    </div>

    <!-- Reassign Modal -->
    <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            if ($('#student_ids').data('readonly')) {
                $('#student_ids').on('select2:opening select2:unselecting', function (e) {
                    e.preventDefault(); // prevent dropdown or removal
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            let levelSelect = document.getElementById("level_id"); // Dropdown
            let hiddenLevelId = document.getElementById("hidden_level_id"); // Hidden input

            if (levelSelect && hiddenLevelId) {
                // When dropdown changes, update hidden input
                levelSelect.addEventListener("change", function() {
                    hiddenLevelId.value = this.value;
                });
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#level_id').on('change', function() {
                $('#hidden_level_id').val($(this).val());
            });
        });
    </script>



    <script>
        // --------------------------------------------- ::
        $(document).on('click', '.reassign-modal-trigger', function(e) {
            e.preventDefault();
            var batchId = $(this).data('batch-id');

            $.ajax({
                url: '/admin/batchs/' + batchId + '/reassign/student',
                type: 'GET',
                data: {
                    batchId: batchId
                },
                success: function(response) {
                    $('#reassignModal').modal('show');
                    $('.modal-content').html(response);
                },
                error: function(xhr) {
                    // Handle error
                    console.log(xhr.responseText);
                }
            });
        });

        $('#student_ids').on('change', function() {
            var removedStudentIds = [];
            var studentIds = $(this).val() || [];
            if ($(this).data('one-to-one') === 'YES' && studentIds.length > 1) {
                var latestStudentId = studentIds[studentIds.length - 1];
                $(this).val([latestStudentId]).trigger('change.select2');
                studentIds = [latestStudentId];
                toastr.error('Only one student can be assigned to a 1-1 batch.');
            }
            var assignedStudentIds = {!! json_encode($assignedStudents->pluck('student_id')) !!};

            assignedStudentIds.forEach(function(assignedStudentId) {
                if (!studentIds.includes(assignedStudentId.toString())) {
                    removedStudentIds.push(assignedStudentId);
                }
            });

            $('#removed_student_ids').val(removedStudentIds);
        });

        // --------------------------------------------- ::
        $(document).ready(function() {
            $('#reassignForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#ajax-loader').show();
                    },
                    complete: function() {
                        $('#ajax-loader').hide();
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.message);
                            $('#reassignModal').modal('hide');
                            $('#datatable').DataTable().draw();
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('An error occurred during the reassignment process.');
                        }
                    }
                });
            });
        });

        // --------------------------------------------- ::
        $('#batch-form').submit(function(e) {
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
@endsection
