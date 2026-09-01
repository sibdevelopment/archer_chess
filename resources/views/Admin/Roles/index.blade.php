@extends('layouts.admin')
@section('title') Roles @endsection
@section('content')
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
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Roles</h5>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <select name="status" id="status" class="form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example">
                                <option value="ACTIVE" selected>Active</option>
                                <option value="INACTIVE">Inactive</option>
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-info">
                                Create The Roles 
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
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                </th>
                                <th width="8%">
                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                </th>
                                <th width="15%">
                                    <h6 class="fs-3 fw-semibold mb-0">Permissions</h6>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
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
                url: '{!! route('admin.roles.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.status = $('#status').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'roles.id',searchable: false},
				{data: 'name',name: 'roles.name'},
                {data: 'status',name: 'roles.status',searchable: false},
				{data: 'permissions',name: 'roles.id'},
            ],
            order: [],
            columnDefs: [
                { targets: [0], className: "text-center"},
            ],
        }); 
        $( 
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");

        $('#status').on('change', function() {
            dataTable.ajax.reload(null, false);
        });

        $(document).on('change', '.role-status-switch', function() {
            var $switch = $(this);
            var status = $switch.is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                url: '{!! route('admin.roles.change.status') !!}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    route_key: $switch.data('routekey'),
                    status: status
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        dataTable.ajax.reload(null, false);
                    } else {
                        $switch.prop('checked', !$switch.is(':checked'));
                        toastr.error(response.message || 'There is some error!!');
                    }
                },
                error: function(xhr) {
                    $switch.prop('checked', !$switch.is(':checked'));
                    toastr.error(xhr.responseJSON?.message || 'There is some error!!', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 2500,
                        closeButton: true,
                    });
                }
            });
        });
    });
</script>
@endsection
