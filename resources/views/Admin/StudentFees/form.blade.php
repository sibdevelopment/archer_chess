@extends('layouts.admin')
@section('title')
Demo Session
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.students.student_fees.create') ? route('admin.students.student_fees.store', ['student' => $student->id]) : route('admin.students.student_fees.update', ['student' => $student->id, 'student_fee' => $student_fee->id]) }}"
        method="POST" enctype="multipart/form-data" autocomplete="off" id="student_fee-form">
        @csrf
        {{ Route::is('admin.students.student_fees.create') ? '' : method_field('PUT') }}
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <div class="row">

            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5 class="text-black"> {{ Route::is('admin.students.student_fees.create') ? 'Create' : 'Edit' }} Student Fees </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">Start Date </label>
                                <input type="date" class="form-control" name="start_date" value="{{ isset($student_fee) ? $student_fee->start_date : '' }}" />
                                <div id="start_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">End Date </label>
                                <input type="date" class="form-control" name="end_date" value="{{ isset($student_fee) ? $student_fee->end_date : '' }}" />
                                <div id="end_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">Fees Receive Date </label>
                                <input type="date" class="form-control" name="receive_date" value="{{ isset($student_fee) ? $student_fee->receive_date : '' }}" />
                                <div id="receive_date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">Currency</label>
                                <input type="text" class="form-control" name="currency" value="{{ isset($student_fee) ? $student_fee->currency : '' }}" placeholder="Enter currency" />
                                <div id="currency-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">Monthly Fees</label>
                                <input type="number" class="form-control" name="monthly_fees" value="{{ isset($student_fee) ? $student_fee->monthly_fees : '' }}" placeholder="Enter monthly fees" />
                                <div id="monthly_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label class="control-label col-form-label">Total Amount Paid</label>
                                <input type="number" class="form-control" name="total_amount_paid" value="{{ isset($student_fee) ? $student_fee->total_amount_paid : '' }}" placeholder="Enter total amount paid" />
                                <div id="total_amount_paid-error" style="color:red"></div>
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
                        <a href="{{ route('admin.students.student_fees.index', ['student' => $student->id]) }}"
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

        $('#student_fee-form').submit(function(e) {
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
                            window.location.href = '{!! route('admin.students.student_fees.index', ['student' => $student->id]) !!}';
                        }, 100);
                    } else {
                        // Use the error message from the response
                        toastr.error(data.message || 'There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // Check if the response has a JSON body with a message
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    } else {
                        // Fallback error message
                        toastr.error('There are some errors in Form. Please check your inputs', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                
                    // Display form validation errors if any
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                                .offset().top - 200
                        }, 500);
                    }
                }
            });
        });
    </script>
@endsection
