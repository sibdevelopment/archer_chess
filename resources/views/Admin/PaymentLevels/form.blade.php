@extends('layouts.admin')
@section('title')
    Payment Levels
@endsection
@section('content')
    <form id="paymentlevel-form" method="POST"
        action="{{ Route::is('admin.paymentlevels.create')
            ? route('admin.paymentlevels.store')
            : route('admin.paymentlevels.update', ['paymentlevel' => $paymentlevel->id ?? '']) }}"
        enctype="multipart/form-data" autocomplete="off">
        @csrf
        @if (!Route::is('admin.paymentlevels.create'))
            @method('PUT')
        @endif
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5>
                            {{ Route::is('admin.paymentlevels.create') ? 'Create' : 'Edit' }} Payment Levels
                        </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label for="name" class="control-label col-form-label">Name *</label>
                                <input type="text" class="form-control" placeholder="Enter Name" name="name"
                                    id="name"
                                    value="{{ old('name', isset($paymentlevel) ? $paymentlevel->name : '') }}" />
                                <div id="name-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="level_id" class="control-label col-form-label">Level *</label>
                                <select class="form-control" name="level_id" id="level_id">
                                    <option value="">Select a level</option>
                                    @foreach ($level_ids as $level_id)
                                        <option value="{{ $level_id->id }}"
                                            {{ old('level_id', isset($paymentlevel) && $paymentlevel->level_id == $level_id->id ? 'selected' : '') }}>
                                            {{ $level_id->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="level_id-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <label for="sequence" class="control-label col-form-label">Sequence *</label>
                                <input type="text" class="form-control" placeholder="Enter Sequence.." name="sequence"
                                    id="sequence"
                                    value="{{ old('sequence', isset($paymentlevel) ? $paymentlevel->sequence : '') }}" />
                                <div id="sequence-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top ">
                        <div class="text-center">
                            <h4 for="fees" class="control-label col-form-label text-center">Country Fees Wise</h4>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">USA (USD)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="usa_fees"
                                    id="usa_fees"
                                    value="{{ old('usa_fees', isset($paymentlevel) ? $paymentlevel->usa_fees : '') }}" />
                                <div id="usa_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">CANADA (CAD)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="canada_fees"
                                    id="canada_fees"
                                    value="{{ old('canada_fees', isset($paymentlevel) ? $paymentlevel->canada_fees : '') }}" />
                                <div id="canada_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">AUSTRALIA (AUD)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="australia_fees"
                                    id="australia_fees"
                                    value="{{ old('australia_fees', isset($paymentlevel) ? $paymentlevel->australia_fees : '') }}" />
                                <div id="australia_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">NEW ZEALAND (?)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="newzealand_fees"
                                    id="newzealand_fees"
                                    value="{{ old('newzealand_fees', isset($paymentlevel) ? $paymentlevel->newzealand_fees : '') }}" />
                                <div id="newzealand_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">INDIA (INR)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="india_fees"
                                    id="india_fees"
                                    value="{{ old('india_fees', isset($paymentlevel) ? $paymentlevel->india_fees : '') }}" />
                                <div id="india_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">UAE (AED)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="uae_fees"
                                    id="uae_fees"
                                    value="{{ old('uae_fees', isset($paymentlevel) ? $paymentlevel->uae_fees : '') }}" />
                                <div id="uae_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">UK (GBP)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="uk_fees"
                                    id="uk_fees"
                                    value="{{ old('uk_fees', isset($paymentlevel) ? $paymentlevel->uk_fees : '') }}" />
                                <div id="uk_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">QATAR (QAR)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="qatar_fees"
                                    id="qatar_fees"
                                    value="{{ old('qatar_fees', isset($paymentlevel) ? $paymentlevel->qatar_fees : '') }}" />
                                <div id="qatar_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">SINGAPORE (SGD)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees"
                                    name="singapore_fees" id="singapore_fees"
                                    value="{{ old('singapore_fees', isset($paymentlevel) ? $paymentlevel->singapore_fees : '') }}" />
                                <div id="singapore_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">EUROPEAN UNION (EUR)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees"
                                    name="european_union_fees" id="european_union_fees"
                                    value="{{ old('european_union_fees', isset($paymentlevel) ? $paymentlevel->european_union_fees : '') }}" />
                                <div id="european_union_fees-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label for="fees" class="control-label col-form-label">OMAN (OMR)*</label>
                                <input type="number" class="form-control" placeholder="Enter Fees" name="oman_fees"
                                    id="oman_fees"
                                    value="{{ old('oman_fees', isset($paymentlevel) ? $paymentlevel->oman_fees : '') }}" />
                                <div id="oman_fees-error" style="color:red"></div>
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
                        <a href="{{ route('admin.paymentlevels.index') }}" type="button" class="btn btn-secondary">
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
        $('#paymentlevel-form').submit(function(e) {
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
                            window.location.href = "{!! route('admin.paymentlevels.index') !!}";
                        }, 500); // Increased timeout for better user experience
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
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] +
                                    '-error')
                                .offset().top - 200
                        }, 500);
                    }
                }
            });
        });
    </script>
@endsection
