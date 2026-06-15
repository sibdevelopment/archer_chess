@extends('layouts.admin')
@section('title')
    New Enrollment Details
@endsection
@section('content')
<div class="row align-items-center">
    <div class="col-lg-4 order-lg-2 order-1">
        <div class="">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 110px; height: 110px;" ;>
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                        style="width: 100px; height: 100px;" ;>
                        <img src="/backend/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <h5 class="fs-5 mb-0 fw-semibold">{{ $new_enrollment->student->first_name }}
                    {{ $new_enrollment->student->last_name }}
                </h5>
            </div>
        </div>
    </div>
    <div class="col-lg-8 order-last">
        <ul class="list-unstyled mb-0">
            @if ($new_enrollment->student->age)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-stretching text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Age : {{ $new_enrollment->student->age }}</h6>
                </li>
            @endif
            @if ($new_enrollment->student->mobile)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-phone text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Mobile : {{ $new_enrollment->student->mobile }}</h6>
                </li>
            @endif
            @if ($new_enrollment->student->user)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-key text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Password : {{ $new_enrollment->student->user->device_id }}</h6>
                </li>
            @endif

                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-mail text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Email : {{ $new_enrollment->student->user->email ?? $new_enrollment->student->email }}</h6>
                </li>

                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-home text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">City : {{ $new_enrollment->student->city }}</h6>
                </li>
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-home text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Country : {{ $new_enrollment->student->country }}</h6>
                </li>

            @if ($new_enrollment->student->student_id)
                <li class="d-flex align-items-center gap-3 mb-4">
                    <i class="ti ti-id-badge text-dark fs-6"></i>
                    <h6 class="fs-4 fw-semibold mb-0">Id : {{ $new_enrollment->student->student_id }}</h6>
                </li>
            @endif
        </ul>
    </div>
</div>

<form method="POST" action="{{ route('admin.newenrollments.update', $new_enrollment->id) }}"
    enctype="multipart/form-data" autocomplete="off" id="student-form">
    @csrf
    @method('PUT')

    <div class="row mt-5">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5> Add Student Details </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <input type="hidden" class="form-control" name="student_id"
                            value="{{ $new_enrollment->student->id }}">

                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Employee *</label>
                                <select class="form-control select2" name="employee_ids[]" multiple>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ in_array($employee->id, (array) $new_enrollment->employee_ids) ? 'selected' : '' }}>
                                        {{ $employee->user->first_name }} {{ $employee->user->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div id="employee_ids-error" class="text-danger"></div>
                            </div>

                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Batch *</label>
                            <select class="form-control select2" name="batch_id" id="" style="width: 100%;">
                                <option value="">Select Batch</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}"
                                        {{ isset($new_enrollment->batch_id) && $new_enrollment->batch_id == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }} ({{ $batch->status }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="batch_id-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Start Date *</label>
                            <input type="date" class="form-control" name="start_date"
                                value="{{ $new_enrollment->start_date ?? '' }}">
                            <div id="start_date-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">End Date *</label>
                            <input type="date" class="form-control" name="end_date"
                                value="{{ $new_enrollment->end_date ?? '' }}">
                            <div id="end_date-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Receive Date *</label>
                            <input type="date" class="form-control" name="receive_date"
                                value="{{ $new_enrollment->receive_date ?? '' }}">
                            <div id="receive_date-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Fees *</label>
                            <input type="text" class="form-control" name="fees"
                                value="{{ $new_enrollment->fees ?? '' }}">
                            <div id="fees-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Received Fees *</label>
                            <input type="text" class="form-control" name="received_fees"
                                value="{{ $new_enrollment->received_fees ?? '' }}">
                            <div id="received_fees-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Currency *</label>
                            <input type="text" class="form-control" name="currency"
                                value="{{ $new_enrollment->currency ?? '' }}">
                            <div id="currency-error" class="text-danger"></div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <label class="control-label col-form-label">Remark *</label>
                            <textarea class="form-control" name="remark">{{ $new_enrollment->remark ?? '' }}</textarea>
                            <div id="remark-error" class="text-danger"></div>
                        </div>
                    </div>
                </div>
                @php
                    $student_fees = App\Models\StudentFee::where('student_id', $new_enrollment->student->id)->get();
                    // dd($student_fees);
                @endphp
                @if (count($student_fees) < 1)
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary" id="new-enrollment">
                            Save &nbsp; <i class="ti ti-device-floppy"></i>
                        </button>

                        <a href="{{ route('admin.newenrollments.index') }}" class="btn btn-secondary">
                            Cancel &nbsp; <i class="ti ti-arrow-back-up-double"></i>
                        </a>

                        <button type="button" class="btn btn-info" id="student-fee">
                            Confirm Enrollment &nbsp; <i class="ti ti-device-floppy"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<script>

    $(document).on("click", "#new-enrollment, #student-fee", function(e) {
        e.preventDefault();
        $('div[id$="-error"]').empty();

        var form = $('#student-form');
        var url = form.attr('action');
        var formData = new FormData(form[0]); // Get all form fields

        // Identify which button was clicked
        var actionType = $(this).attr('id') === "new-enrollment" ? "new-enrollment" : "student-fee";
        formData.append('type', actionType);

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status === 'success') {
                    toastr.success(data.message, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.newenrollments.index') }}";
                    }, 100);
                } else {
                    toastr.error('There is some error!!');
                }
            },
            error: function(xhr) {
                toastr.error('There are some errors in Form. Please check your inputs');
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#' + key + '-error').html(value);
                });

                // Scroll to first error field
                var firstErrorField = Object.keys(xhr.responseJSON.errors)[0];
                if (firstErrorField) {
                    $('html, body').animate({
                        scrollTop: $('#' + firstErrorField + '-error').offset().top - 200
                    }, 500);
                }
            }
        });
    });
</script>

@endsection
