@extends('layouts.admin')
@section('title')
Coach
@endsection
@section('content')
<form method="POST"
    action="{{ Route::is('admin.coaches.create') ? route('admin.coaches.store') : route('admin.coaches.update', ['coach' => $coach->route_key]) }}"
    method="POST" enctype="multipart/form-data" autocomplete="off" id="coaches-form">
    @csrf
    {{ Route::is('admin.coaches.create') ? '' : method_field('PUT') }}
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5> {{ Route::is('admin.coaches.create') ? 'Create' : 'Edit' }} Coach </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <h6 class="text-warning fs-4">Personal Info :</h6>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">First Name</label>
                            <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{ isset($coach) ? $coach->user->first_name : '' }}"/>
                            <div id="first_name-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Last Name</label>
                            <input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{ isset($coach) ? $coach->user->last_name : '' }}"/>
                            <div id="last_name-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Mobile</label>
                            <input type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{ isset($coach) ? $coach->user->mobile : '' }}"/>
                            <div id="mobile-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">PAN</label>
                            <input type="text" class="form-control" placeholder="PAN" name="pan_number" value="{{ isset($coach) ? $coach->pan_number : '' }}"/>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Country <sup class="tcul-star-restrict">*</sup></label>
                            <select class="form-control select2" name="country[]" multiple="multiple">
                                <option value="USA" {{ (isset($coach) && in_array('USA', $coach->country ?? [])) ? 'selected' : '' }}>USA</option>
                                <option value="CANADA" {{ (isset($coach) && in_array('CANADA', $coach->country ?? [])) ? 'selected' : '' }}>CANADA</option>
                                <option value="AUSTRALIA" {{ (isset($coach) && in_array('AUSTRALIA', $coach->country ?? [])) ? 'selected' : '' }}>AUSTRALIA</option>
                                <option value="NEWZEALAND" {{ (isset($coach) && in_array('NEWZEALAND', $coach->country ?? [])) ? 'selected' : '' }}>NEW ZEALAND</option>
                                <option value="INDIA" {{ (isset($coach) && in_array('INDIA', $coach->country ?? [])) ? 'selected' : '' }}>INDIA</option>
                                <option value="UAE" {{ (isset($coach) && in_array('UAE', $coach->country ?? [])) ? 'selected' : '' }}>UAE</option>
                                <option value="UK" {{ (isset($coach) && in_array('UK', $coach->country ?? [])) ? 'selected' : '' }}>UK</option>
                                <option value="SINGAPORE" {{ (isset($coach) && in_array('SINGAPORE', $coach->country ?? [])) ? 'selected' : '' }}>SINGAPORE</option>
                                <option value="QATAR" {{ (isset($coach) && in_array('QATAR', $coach->countries ?? [])) ? 'selected' : '' }}>QATAR</option>
                                <option value="BAHRAIN" {{ (isset($coach) && in_array('BAHRAIN', $coach->countries ?? [])) ? 'selected' : '' }}>BAHRAIN</option>
                                <option value="KUWAIT" {{ (isset($coach) && in_array('KUWAIT', $coach->countries ?? [])) ? 'selected' : '' }}>KUWAIT</option>
                                <option value="EUROPEAN UNION" {{ (isset($coach) && in_array('EUROPEAN UNION', $coach->countries ?? [])) ? 'selected' : '' }}>EUROPEAN UNION</option>
                                <option value="OMAN" {{ (isset($coach) && in_array('OMAN', $coach->countries ?? [])) ? 'selected' : '' }}>OMAN</option>
                            </select>
                            <div id="country-error" style="color:red"></div>
                        </div>
                    </div>
                    <br/>
                    <hr/>
                    <br/>
                    <div class="row">
                        <h6 class="text-warning fs-4">Zoom & Portal Details :</h6>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom Account Id </label>
                            <input type="text" class="form-control" placeholder="Zoom Id" name="zoom_id"
                                value="{{ isset($coach) ? $coach->zoom_id : '' }}" />
                            <div id="zoom_id-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom User Id </label>
                            <input type="text" class="form-control" placeholder="Zoom User ID" name="zoom_user_id"
                                value="{{ isset($coach) ? $coach->zoom_user_id : '' }}" />
                            <div id="zoom_user_id-error" style="color:red"></div>
                        </div>
                        {{-- <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom Password </label>
                            <input type="text" class="form-control" placeholder="Zoom Password" name="zoom_password"
                                value="{{ isset($coach) ? $coach->zoom_password : '' }}" />
                            <div id="zoom_password-error" style="color:red"></div>
                        </div> --}}
                        {{-- <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom Link </label>
                            <input type="text" class="form-control" placeholder="Zoom link" name="zoom_link"
                                value="{{ isset($coach) ? $coach->zoom_link : '' }}" />
                            <div id="zoom_link-error" style="color:red"></div>
                        </div> --}}
                        {{-- <div class="col-sm-12 col-md-4"></div> --}}
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom Api Key </label>
                            <input type="text" class="form-control" placeholder="Api Key" name="zoom_api_key"
                                value="{{ isset($coach) ? $coach->zoom_api_key : '' }}" /> 
                            <div id="zoom_api_key-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Zoom Client Secret Key </label>
                            <input type="text" class="form-control" placeholder="Api Secret Key" name="zoom_client_secret"
                                value="{{ isset($coach) ? $coach->zoom_client_secret : '' }}" /> 
                            <div id="zoom_client_secret-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Portal Id </label>
                            <input type="text" class="form-control" placeholder="Portal Id" name="portal_id"
                                value="{{ isset($coach) ? $coach->portal_id : '' }}" />
                            <div id="portal_id-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <label class="control-label col-form-label">Portal Password </label>
                            <input type="text" class="form-control" placeholder="Portal Password" name="portal_password"
                                value="{{ isset($coach) ? $coach->portal_password : '' }}" />
                            <div id="portal_password-error" style="color:red"></div>
                        </div>
                    </div>
                    <br/>
                    <hr/>
                    <br/>
                    <div class="row">
                        <h6 class="text-warning fs-4">Login Info :</h6>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">E-Mail</label>
                            <input type="text" class="form-control" placeholder="E-Mail" name="email"
                                value="{{ isset($coach) ? $coach->user->email : '' }}" />
                            <div id="email-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">Select</option>
                                @if(isset($coach) && $coach->user->status == 'ACTIVE')
                                <option value="ACTIVE" selected>Active</option>
                                @else
                                <option value="ACTIVE">Active</option>
                                @endif

                                @if(isset($coach) && $coach->user->status == 'INACTIVE')
                                <option value="INACTIVE" selected>Inactive</option>
                                @else
                                <option value="INACTIVE">Inactive</option>
                                @endif
                            </select>
                            <div id="status-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Password</label>
                            <input type="text" class="form-control" placeholder="Password" name="password" value="{{ $decrypt_password ?? '' }}"/>
                            <div id="password-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Confirm Password</label>
                            <input type="text" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ $decrypt_password ?? '' }}"/>
                            <div id="password_confirmation-error" style="color:red"></div>
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
                    <a href="{{ route('admin.coaches.index') }}" type="button" class="btn btn-secondary">
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
    $('#coaches-form').submit(function (e) {
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
                        window.location.href = "{!! route('admin.coaches.index') !!}";
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
