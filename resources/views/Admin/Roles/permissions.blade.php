@extends('layouts.admin')
@section('title') Permissions @endsection
@section('content')
<form action="{{ route('admin.roles.permissions.update',['role' => $role->route_key ]) }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="permission-form">
    @csrf
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="px-4 py-3 border-bottom">
                        <h5 class="mb-0">{{$role->name}} - Permissions</h5>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table border table-bordered text-nowrap customize-table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
                                        <tr>
                                            <th width="10%"><h6 class="fs-4 fw-semibold mb-0">Module</h6></th>
                                            <th width="3%"><h6 class="fs-4 fw-semibold mb-0">View</h6></th>
                                            <th width="3%"><h6 class="fs-4 fw-semibold mb-0">Create</h6></th>
                                            <th width="3%"><h6 class="fs-4 fw-semibold mb-0">Update</h6></th>
                                            <th><h6 class="fs-4 fw-semibold mb-0">Other</h6></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions_groups as $permissions_group)
                                        <tr>
                                            <!-- Module :: -->
                                            <td>
                                                @if ($permissions_group->name === 'Level')
                                                    <i class="ti ti-chart-arrows"></i> &nbsp; Level
                                                @elseif ($permissions_group->name === 'Dashboard')
                                                <i class="ti ti-aperture"></i> &nbsp; Dashboard
                                                @elseif ($permissions_group->name === 'Role')
                                                <i class="ti ti-user-check"></i> &nbsp; Role
                                                @elseif ($permissions_group->name === 'Employee')
                                                <i class="ti ti-user-circle"></i> &nbsp; Employee
                                                @elseif ($permissions_group->name === 'Coach')
                                                    <i class="ti ti-user-exclamation"></i> &nbsp;    Coach
                                                @elseif ($permissions_group->name === 'CoachAvailability')
                                                    <i class="ti ti-user-exclamation"></i> &nbsp; Coach Availability	
                                                @elseif ($permissions_group->name === 'DemoLead')
                                                    <i class="ti ti-headset"></i> &nbsp;    Demo Lead
                                                @elseif ($permissions_group->name === 'DemoSessions')
                                                    <i class="ti ti-headset"></i> &nbsp;     Demo Session
                                                @elseif ($permissions_group->name === 'Student')
                                                    <i class="ti ti-school"></i> &nbsp;    Student
                                                @elseif ($permissions_group->name === 'StudentFee')
                                                    <i class="ti ti-school"></i> &nbsp;    Student Fee 
                                                @elseif ($permissions_group->name === 'Batch')
                                                    <i class="ti ti-color-swatch"></i> &nbsp;    Batch
                                                @elseif ($permissions_group->name === 'BatchSchedule')
                                                    <i class="ti ti-messages"></i> &nbsp;    Batch Schedule
                                                @elseif ($permissions_group->name === 'Holiday')
                                                    <i class="ti ti-calendar-cog"></i> &nbsp;   Holiday
                                                @elseif ($permissions_group->name === 'LeaveRequest')
                                                <i class="ti ti-calendar"></i> &nbsp;  Leave Request
                                                @elseif ($permissions_group->name === 'Report')
                                                <i class="ti ti-note"></i> &nbsp; Coach Report
                                                @else
                                                    {{$permissions_group->name}}
                                                @endif
                                            </td>
                                                                                        @php 
                                                $permission_group_name_string = strtolower(str_replace(' ','',$permissions_group->name)).'-'; 
                                            @endphp
                                            @foreach($permissions_group->permissions as $permission)
                                                @php $checked = false; @endphp
                                                @foreach($role_permissions as $role_permission)
                                                    @if($permission->id == $role_permission->id)
                                                        @php $checked = true; @endphp
                                                    @endif
                                                @endforeach       
                                            
                                                @php 
                                                    $p = str_replace($permission_group_name_string,'',$permission->name); 
                                                    $p = preg_replace('/[^a-zA-Z0-9]/', '', $p); 
                                                @endphp
                                            
                                                <!-- Skip the iteration if the word is "Levels-view" -->
                                                @if(strtolower($permission->name) == 'levels-view')
                                                    @continue
                                                @endif
                                            
                                                <td class="align-middle">
                                                    @if($p != 'view' && $p != 'store' && $p != 'update')
                                                        <br/>
                                                        <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{$permission->name}}" @if($checked) checked @endif>
                                                        <br/>
                                                        <span class="mb-5">{{ucwords($p)}}</span>
                                                    @else
                                                        <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{$permission->name}}" @if($checked) checked @endif>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>

                                    {{-- 
                                    <tbody>
                                        @foreach ($permissions_groups as $permissions_group)
                                        <tr>
                                            <td>
                                                {{$permissions_group->name}}
                                            </td>
                                            @php 
                                                $permission_group_name_string = strtolower(str_replace(' ','',$permissions_group->name)).'-'; 
                                            @endphp
                                            @foreach($permissions_group->permissions as $permission)
                                                @php $checked = false; @endphp
                                                @foreach($role_permissions as $role_permission)
                                                    @if($permission->id == $role_permission->id)
                                                        @php $checked = true; @endphp
                                                    @endif
                                                @endforeach       
                                                
                                                @php 
                                                    $p = str_replace($permission_group_name_string,'',$permission->name); 
                                                    preg_replace('/[^a-zA-Z0-9]/', '', $p); 
                                                @endphp
                                                <td class="align-middle">
                                                    @if($p != 'view' && $p != 'store' && $p != 'update')
                                                        <br/>
                                                        <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{$permission->name}}" @if($checked) checked @endif>
                                                        <br/>
                                                        <span class="mb-5">{{ucwords($p)}}</span>
                                                    @else
                                                        <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{$permission->name}}" @if($checked) checked @endif>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    --}}
                                </table>
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
                </div>
            </div>
        </div>
    </section>
</form>
<script>
$('#permission-form').submit(function(e) {
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
                toastr.success(data.message);
                setTimeout(function(){
                    window.location.href = "{!! route('admin.roles.index') !!}";
                }, 500);
            }else{

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