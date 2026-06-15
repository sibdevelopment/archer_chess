@extends('layouts.admin')
@section('title') Roles @endsection
@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <form method="POST" action="{{ Route::is('admin.roles.create') ? route('admin.roles.store') : route('admin.roles.update', ['role' => $role->route_key]) }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="role-form">
                @csrf
                {{ Route::is('admin.roles.create') ? '' : method_field('PUT') }}
                <div class="card-header">
                    <h5>
                        {{ Route::is('admin.roles.create') ? 'Create' : 'Edit' }} Role
                    </h5>
                </div>
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Role Name</label>
                            <input type="text" class="form-control" placeholder="Role Name" name="name" value="{{ isset($role) ? $role->name : '' }}"/>
                            <div id="name-error" style="color:red"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="control-label col-form-label">Countries <sup class="tcul-star-restrict">*</sup></label>
                            <select class="form-control" name="countries[]" multiple id="countries-select">
                                <option value="USA" {{ (isset($role) && in_array('USA', $role->countries ?? [])) ? 'selected' : '' }}>USA</option>
                                <option value="CANADA" {{ (isset($role) && in_array('CANADA', $role->countries ?? [])) ? 'selected' : '' }}>CANADA</option>
                                <option value="AUSTRALIA" {{ (isset($role) && in_array('AUSTRALIA', $role->countries ?? [])) ? 'selected' : '' }}>AUSTRALIA</option>
                                <option value="NEWZEALAND" {{ (isset($role) && in_array('NEWZEALAND', $role->countries ?? [])) ? 'selected' : '' }}>NEW ZEALAND</option>
                                <option value="INDIA" {{ (isset($role) && in_array('INDIA', $role->countries ?? [])) ? 'selected' : '' }}>INDIA</option>
                                <option value="UAE" {{ (isset($role) && in_array('UAE', $role->countries ?? [])) ? 'selected' : '' }}>UAE</option>
                                <option value="UK" {{ (isset($role) && in_array('UK', $role->countries ?? [])) ? 'selected' : '' }}>UK</option>
                                <option value="SINGAPORE" {{ (isset($role) && in_array('SINGAPORE', $role->countries ?? [])) ? 'selected' : '' }}>SINGAPORE</option>
                                <option value="SOUTH AFRICA" {{ (isset($role) && in_array('SOUTH AFRICA', $role->countries ?? [])) ? 'selected' : '' }}>SOUTH AFRICA</option>
                                <option value="QATAR" {{ (isset($role) && in_array('QATAR', $role->countries ?? [])) ? 'selected' : '' }}>QATAR</option>
                                <option value="BAHRAIN" {{ (isset($role) && in_array('BAHRAIN', $role->countries ?? [])) ? 'selected' : '' }}>BAHRAIN</option>
                                <option value="KUWAIT" {{ (isset($role) && in_array('KUWAIT', $role->countries ?? [])) ? 'selected' : '' }}>KUWAIT</option>
                                <option value="EUROPEAN UNION" {{ (isset($role) && in_array('EUROPEAN UNION', $role->countries ?? [])) ? 'selected' : '' }}>EUROPEAN UNION</option>
                                <option value="OMAN" {{ (isset($role) && in_array('OMAN', $role->countries ?? [])) ? 'selected' : '' }}>OMAN</option>
                            </select>
                            <div id="countries-error" style="color:red"></div>
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
                    <a href="{{ route('admin.roles.index') }}" type="button" class="btn btn-secondary">
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
        $('#countries-select').select2({
            allowClear: true
        });
    });
    
    $('#role-form').submit(function(e) {
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
            processData:false,
            success: function(data){
                if(data.status == 'success'){
                    toastr.success(data.message,'',{   
                        showMethod: "slideDown", 
                        hideMethod: "slideUp", 
                        timeOut: 1500, 
                        closeButton: true,
                    });
                    setTimeout(function(){
                        window.location.href = "{!! route('admin.roles.index') !!}";
                    }, 100);
                }else{
                    toastr.error('There is some error!!','',{   
                        showMethod: "slideDown", 
                        hideMethod: "slideUp", 
                        timeOut: 1500, 
                        closeButton: true,
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(xhr.responseJSON.message,'',{   
                    showMethod: "slideDown", 
                    hideMethod: "slideUp", 
                    timeOut: 1500, 
                    closeButton: true,
                });
                $.each(xhr.responseJSON.errors, function(key,value) {
                    $('#'+key+'-error').html(value);
                });
                $('html, body').animate({
                    scrollTop: $('#'+Object.keys(xhr.responseJSON.errors)[0]+'-error').offset().top - 200
                }, 500);
            }
        });
    });
</script>
@endsection