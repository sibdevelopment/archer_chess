@extends('layouts.admin')
@section('title') Level @endsection
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
                        <div class="col-7 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Level</h5>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="status" id="status"
                                class="form-select form-select-sm   pure-white"
                                aria-label=".form-select-sm example">
                                <option value="ACTIVE">Active</option>
                                <option value="">All</option>
                                <option value="INACTIVE ">Inactive</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <a href="{{ route('admin.levels.create') }}" class="btn btn-info">
                                Create The Levels
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
                                    <h6 class="fs-3 fw-semibold mb-0">Name</h6>
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
                Are you sure you want to delete this Level data? 
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
                url: '{!! route('admin.levels.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.status = $('#status').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'levels.id', orderable: false, searchable: false},
                {data: 'status',name: 'levels.id', orderable: false, searchable: false},
                {data: 'name',name: 'levels.name', orderable: false},
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
    });
    
    $(document).on('change', '.level-status-switch', function(e){
        e.preventDefault();
        var routeKey = $(this).data('routekey');
        var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
        $.ajax({
            url: "{{ route('admin.levels.change.status') }}",
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
        var levelId = $(this).data('level-id');
        $('#confirmDelete').data('level-id', levelId);
        $('#deleteConfirmationModal').modal('show');
    });
    $(document).on('click', '#confirmDelete', function () {
        var levelId = $(this).data('level-id'); 
        $.ajax({
            url: "{{ route('admin.levels.destroy', '') }}" + '/' + levelId,
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                _method: 'DELETE',
                level_id: levelId
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
