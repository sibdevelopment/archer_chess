@extends('layouts.admin')
@section('title') Coach @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-2 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Coach</h5>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <select name="country" id="country" class="select2 form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                <option value="">Select Country</option>
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
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="day_of_week" id="day_of_week" class="select2 form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                <option value="">Select Day</option>
                                <option value="SUNDAY">Sunday</option>
                                <option value="MONDAY">Monday</option>
                                <option value="TUESDAY">Tuesday</option>
                                <option value="WEDNESDAY">Wednesday</option>
                                <option value="THURSDAY">Thursday</option>
                                <option value="FRIDAY">Friday</option>
                                <option value="SATURDAY">Saturday</option>
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="status" id="status" class="select2 form-select form-select-sm   pure-white" aria-label=".form-select-sm example">
                                {{-- <option value="">Select Status</option> --}}
                                <option value="ACTIVE ">Active</option>
                                <option value="INACTIVE ">Inactive</option>
                                <option value=" ">ALL</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <a href="{{ route('admin.coaches.create') }}" class="btn btn-info">
                                Create The Coachs
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
                                <th width="2%">
                                    <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                </th>
                                <th width="1%">
                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Full Name</h6>
                                </th>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Email</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                </th>
                                <th width="2%">
                                    <h6 class="fs-3 fw-semibold mb-0">Availability</h6>
                                </th>
                                <!-- <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Zoom Id</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Zoom Password</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Portal Id</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Portal Password</h6>
                                </th> -->
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
                Are you sure you want to delete this Coach data?
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
                url: '{!! route('admin.coaches.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.status = $('#status').val();
                    d.day_of_week = $('#day_of_week').val();
                    d.country = $('#country').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'coachs.id', orderable: false, searchable: false},
                {data: 'status',name: 'coachs.id', orderable: false, searchable: false},

                {data: 'first_name',name: 'users.first_name', orderable: false},
                {data: 'mobile',name: 'users.mobile', orderable: false},
                {data: 'email',name: 'users.email', orderable: false},
                {data: 'country',name: 'coachs.country', orderable: false},

                {data: 'coachavailability',name: 'coachs.id', orderable: false, searchable: false},
                // {data: 'zoom_id',name: 'coachs.zoom_id', orderable: false},
                // {data: 'zoom_password',name: 'coachs.zoom_password', orderable: false},
                // {data: 'portal_id',name: 'coachs.portal_id', orderable: false},
                // {data: 'portal_password',name: 'coachs.portal_password', orderable: false},
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
        $('#day_of_week').on('change', function () {
            dataTable.ajax.reload(null, false);
        });

        $('#country').on('change', function () {
            dataTable.ajax.reload(null, false);
        });
    });

    $(document).on('change', '.coach-status-switch', function(e){
        e.preventDefault();
        var routeKey = $(this).data('routekey');
        var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
        $.ajax({
            url: "{{ route('admin.coaches.change.status') }}",
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
        var coachId = $(this).data('coach-id');
        // Set the coachId as a data attribute on the confirm button for later use
        $('#confirmDelete').data('coach-id', coachId);
        // Show the confirmation modal
        $('#deleteConfirmationModal').modal('show');
    });
    $(document).on('click', '#confirmDelete', function () {
        var coachId = $(this).data('coach-id'); // Retrieve the coachId from the confirm button
        $.ajax({
            url: "{{ route('admin.coaches.destroy', '') }}" + '/' + coachId,
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                _method: 'DELETE',
                coach_id: coachId
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
