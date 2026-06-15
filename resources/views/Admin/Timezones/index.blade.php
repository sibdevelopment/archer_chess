@extends('layouts.admin')
@section('title') Timezone @endsection
@section('content')
@php
    $user = auth()->user();
    $role = $user->getRoleNames()->toArray();
    $coachId = null;
    if (in_array("Coach", $role) && $user->coach) {
    $coachId = $user->coach->id;
    }
    $isCoach = in_array("Coach", $role);
    $isAdminOrSuperAdmin = in_array("Admin", $role) || in_array("SuperAdmin", $role);

    // Get the countries the user can see
    $allowedCountries = [];
    if (!$isAdminOrSuperAdmin) {
        $userRole = $user->roles()->first();
        if ($userRole && $userRole->countries) {
            $allowedCountries = json_decode($userRole->countries);
        }
    }
@endphp
<style>
    #datatable tbody tr td {
        color: black !important;
    }
</style>
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-3 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Timezone</h5>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="status" id="status"
                                class="form-select form-select-sm   pure-white"
                                aria-label=".form-select-sm example">
                                <option value="">Select Status</option>
                                <option value="ACTIVE ">Active</option>
                                <option value="INACTIVE ">Inactive</option>
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="country" id="country" class=" form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                <option value="">Select Country</option>
                                @if ($isAdminOrSuperAdmin)
                                    <option value="USA">USA</option>
                                    <option value="CANADA">CANADA</option>
                                    <option value="AUSTRALIA">AUSTRALIA</option>
                                    <option value="NEWZEALAND">NEW ZEALAND</option>
                                    <option value="INDIA">INDIA</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                    <option value="SINGAPORE">SINGAPORE</option>
                                    <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                    <option value="QATAR">QATAR</option>
                                    <option value="BAHRAIN">BAHRAIN</option>
                                    <option value="KUWAIT">KUWAIT</option>
                                    <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                                    <option value="OMAN">OMAN</option>
                                @else
                                    @foreach ($allowedCountries as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="weekday" id="weekday" class="form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                <option value="">Select Weekday</option>
                                <option value="MONDAY">MONDAY</option>
                                <option value="TUESDAY">TUESDAY</option>
                                <option value="WEDNESDAY">WEDNESDAY</option>
                                <option value="THURSDAY">THURSDAY</option>
                                <option value="FRIDAY">FRIDAY</option>
                                <option value="SATURDAY">SATURDAY</option>
                                <option value="SUNDAY">SUNDAY</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <a href="{{ route('admin.timezones.create') }}" class="btn btn-info">
                                Create The Timezones
                                &nbsp;
                                <i class="ti ti-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="1%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Weekday</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Timezone</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">India Start & End Time</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Country Start & End Time</h6>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ------------------------------------------------------------------- :: -->
<!-- Delete Confirmation Modal -->
<div class="modal fade text-left" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" style="z-index: 9999 !important;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header rounded" style="background-color: #539BFF !important;">
                <h5 class="modal-title text-white" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Timezone data? 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function() {
        var dataTable = $('#datatable').DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            scrollX:false,
            pageLength: 100,
            ajax: {
                url: '{!! route('admin.timezones.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.status = $('#status').val();
                    d.country = $('#country').val();
                    d.weekday = $('#weekday').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'timezones.id', orderable: false, searchable: false},
                {data: 'status',name: 'timezones.id', orderable: false, searchable: false},
                {data: 'country',name: 'timezones.country', orderable: false},
                {data: 'weekday',name: 'timezones.weekday', orderable: false, searchable: false},
                {data: 'timezone',name: 'timezones.timezone', orderable: false, searchable: false},
                {data: 'india_time',name: 'timezones.india_time', orderable: false, searchable: false},
                {data: 'country_time',name: 'timezones.country_time', orderable: false, searchable: false},
            ],
            order: [],
            columnDefs: [
                { targets: [0,1], className: "text-center"},
            ],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");
        $('#status').on('change', function () {
            dataTable.ajax.reload(null, false);
        });
        $('#country').on('change', function () {
            dataTable.ajax.reload(null, false);
        });
        $('#weekday').on('change', function () {
            dataTable.ajax.reload(null, false);
        });
    });
    
    $(document).on('change', '.timezone-status-switch', function(e){
        e.preventDefault();
        var routeKey = $(this).data('routekey');
        var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
        $.ajax({
            url: "{{ route('admin.timezones.change.status') }}",
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                route_key: routeKey,
                status: status
            },
            success: function(data) {
                if(data.status == 'success'){
                    toastr.success(data.message,'',{
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    if($.fn.DataTable.isDataTable("#datatable")){
                        $('#datatable').DataTable().draw();
                    }
                }else{
                    toastr.error(data.message,'',{
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            },
            error: function(data) {
                toastr.error('Something went wrong!');
            }
        });
    });
    
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        var timezoneId = $(this).data('timezone-id');
        $('#confirmDelete').data('timezone-id', timezoneId);
        $('#deleteConfirmationModal').modal('show');
    });
    $(document).on('click', '#confirmDelete', function () {
        var timezoneId = $(this).data('timezone-id'); 
        $.ajax({
            url: "{{ route('admin.timezones.destroy', '') }}" + '/' + timezoneId,
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                _method: 'DELETE',
                timezone_id: timezoneId
            },
            success: function (data) {
                $('#datatable').DataTable().draw(false); // Redraw the DataTable without resetting the paging
                $('#deleteConfirmationModal').modal('hide'); // Hide the confirmation modal
                toastr.success(data.success, '', {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    timeOut: 1500,
                    closeButton: true,
                });
            },
            error: function (data) {
                $('#deleteConfirmationModal').modal('hide'); // Hide the confirmation modal
                toastr.error(data.responseJSON.error, '', {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    timeOut: 1500,
                    closeButton: true,
                });
            }
        });
    });
</script>
@endsection
