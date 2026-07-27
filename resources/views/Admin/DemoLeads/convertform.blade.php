@extends('layouts.admin')
@section('title')
    DemoLeads - Convert to Student
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
                    <h5> Convert DemoLead to Student </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($demolead->first_name)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-user text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Full Name : {{ $demolead->first_name }} {{ $demolead->last_name }}
                                        </h6>
                                    </li>
                                @endif
                                @if ($demolead->age)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-cake text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Age: {{ $demolead->age }}
                                        </h6>
                                    </li>
                                @endif
                                @if ($demolead->address)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-map-pin text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Address : {{ $demolead->address }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($demolead->mobile)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-phone text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Mobile : {{ $demolead->mobile }}
                                        </h6>
                                    </li>
                                @endif
                                @if ($demolead->date)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-calendar text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Date : {{ date('F d, Y', strtotime($demolead->date)) }}
                                        </h6>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Time : {{ date('h:i A', strtotime($demolead->time)) }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        @php
                            // dd($demolead);
                        @endphp
                        <div class="col-lg-6 d-flex align-items-stretch">
                            <ul class="list-unstyled mb-0">
                                @if ($demolead->country)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-world text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Country : {{ $demolead->country }} ({{ $demolead->kids_time_zone }})
                                        </h6>
                                    </li>
                                @endif
                                @if ($demolead->remark)
                                    <li class="d-flex align-items-center gap-3 mb-4">
                                        <i class="ti ti-message-dots text-dark fs-6"></i>
                                        <h6 class="fs-4 fw-semibold mb-0">
                                            Remark : {{ $demolead->remark }}
                                        </h6>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 align-items-stretch">
            <form method="POST" action="{{ route('admin.demoleads.convert.save', ['demolead' => $demolead->id]) }}"
                enctype="multipart/form-data" autocomplete="off" id="demoleads-form">
                @csrf
                <!-- The rest of your form content -->
                <div class="row">
                    <div class="col-lg-12 align-items-stretch">
                        <div class="card w-100">
                            <div class="card-header">
                                <h5> Student Details </h5>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Chesslang Student ID (Portal ID) <sup
                                                class="tcul-star-restrict">*</sup><span
                                                style="color: red;font-size:60%;"><b> (Must start
                                                    with
                                                    "ARCHER")</b></span></label>

                                        <input type="text" class="form-control" placeholder="Student ID" id="student_id"
                                            name="student_id" />
                                        <div id="student_id-error" style="color:red"></div>
                                    </div>
                                    @php
                                        $portal_password = 'AKids';
                                        $student_country = isset($demolead) ? $demolead->country : '';
                                        if ($student_country) {
                                            if (
                                                $student_country == 'AUSTRALIA' ||
                                                $student_country == 'INDIA' ||
                                                $student_country == 'NEWZEALAND' ||
                                                $student_country == 'SINGAPORE' ||
                                                $student_country == 'UAE'
                                            ) {
                                                $portal_password = 'ARCHERCHESS';
                                            }
                                        }
                                    @endphp
                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Chesslang Portal Password <sup
                                                class="tcul-star-restrict"></sup></label>
                                        <input type="text" class="form-control" placeholder="Portal Password"
                                            name="portal_password"
                                            value="{{ isset($student) ? $student->portal_password : $portal_password }}" />
                                        <div id="portal_password-error" style="color:red"></div>
                                    </div>
                                    <input type="hidden" name="demolead_id" value="{{ $demolead->id }}" />
                                    {{-- Last Payment Level is no longer required during demo lead conversion.
                                    <div class="col-sm-12 col-md-4 ">
                                        <label for="lastpayment_level_id" class="control-label col-form-label">Last Payment
                                            Level
                                            ID</label>
                                        <select class="form-control select2" name="lastpayment_level_id"
                                            id="lastpayment_level_id">
                                            <option value="">Select an Option</option>
                                            @foreach ($lastpayment_levels as $lastpayment_level_id)
                                                <option value="{{ $lastpayment_level_id->id }}"
                                                    {{ old('lastpayment_level_id', isset($student) && $student->lastpayment_level_id == $lastpayment_level_id->id ? 'selected' : '') }}>
                                                    {{ $lastpayment_level_id->name }}
                                                    ({{ $lastpayment_level_id->level->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="lastpayment_level_id-error" style="color:red"></div>
                                    </div>
                                    --}}
                                    <input type="hidden" name="status" value="ACTIVE" />
                                </div>
                            </div>
                            <div class="card-header">
                                <h5>New Enrollment Details </h5>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Employee *</label>
                                        <select class="form-control select2" name="employee_ids[]" multiple>
                                            <option value="" disabled>Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->user->first_name }} {{ $employee->user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="employee_ids-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-6">
                                        <label class="control-label col-form-label">Batch </label>
                                        <select class="form-control select2" name="batch_id" id=""
                                            style="width: 100%;">
                                            <option value="">Select Batch</option>
                                            @foreach ($batches as $batch)
                                                <option value="{{ $batch->id }}">
                                                    {{ $batch->name }} ({{ $batch->status }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="batch_id-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Start Date </label>
                                        <input type="date" class="form-control" name="start_date" value="">
                                        <div id="start_date-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">End Date </label>
                                        <input type="date" class="form-control" name="end_date" value="">
                                        <div id="end_date-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Receive Date *</label>
                                        <input type="date" class="form-control" name="receive_date" value="">
                                        <div id="receive_date-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Fees *</label>
                                        <input type="text" class="form-control" name="fees" value="">
                                        <div id="fees-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Received Fees *</label>
                                        <input type="text" class="form-control" name="received_fees" value="">
                                        <div id="received_fees-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <label class="control-label col-form-label">Currency *</label>
                                        <input type="text" class="form-control" name="currency" value="">
                                        <div id="currency-error" class="text-danger"></div>
                                    </div>

                                    <div class="col-sm-12 col-md-12">
                                        <label class="control-label col-form-label">Remark *</label>
                                        <textarea class="form-control" name="remark"></textarea>
                                        <div id="remark-error" class="text-danger"></div>
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
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const allowedPrefix = "ARCHER";

            $('#student_id').on('beforeinput', function(e) {
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

            $('#student_id').on('input', function() {
                this.value = this.value.toUpperCase(); // Always keep it uppercase
            });

            $('#student_id').on('paste', function(e) {
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
