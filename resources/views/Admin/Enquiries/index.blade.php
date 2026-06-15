@extends('layouts.admin')
@section('title') Enquiries @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-9 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Website Enquiry</h5>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <div class="input-group">
                                <input name="date" id="date" type="text" class="form-control daterange" />
                                <span class="input-group-text">
                                    <i class="ti ti-calendar fs-5"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Profile</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Date-Time</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">E-Mail</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Message</h6>
                                </th>
                                @can('enquiry-destroy')
                                    <th>
                                        <h6 class="fs-3 fw-semibold mb-0">Delete</h6>
                                    </th>
                                @endcan

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
        
<script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
<script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /*******************************************/
    // Basic Date Range Picker
    /*******************************************/
    $(".daterange").daterangepicker();
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        $('#date').val('');
    }); 
    $(function() {
        var columns = [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'action', name: 'enquiries.id', searchable: false},
            {data: 'datetime', name: 'enquiries.created_at'},
            {data: 'first_name', name: 'enquiries.first_name'},
            {data: 'country', name: 'enquiries.country'},
            {data: 'email', name: 'enquiries.email'},
            {data: 'mobile', name: 'enquiries.mobile'},
            {data: 'message', name: 'enquiries.message'},
        ];

        // Conditionally add Delete column if user has permission
        @if(hasPermission('enquiry-destroy'))
            columns.push({data: 'delete', name: 'enquiries.id', searchable: false, orderable: false});
        @endif

        var dataTable = $('#datatable').DataTable({
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            pageLength: 25,
            scrollX: false,
            ajax: {
                url: '{!! route('admin.enquiries.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.date = $('#date').val();
                }
            },
            columns: columns,
            order: [],
            columnDefs: [
                { targets: [0, 1], className: "text-center" },
                @if(hasPermission('enquiry-destroy'))
                    { targets: [columns.length - 1], className: "text-center" },
                @endif
            ],
        });

        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
            .addClass("btn btn-primary mr-1");

        $('#date').on('change', function () {
            dataTable.ajax.reload(null, false);
        });
    });



    $(document).on('click', '.delete-enquiry', function () {
        let deleteUrl = $(this).data('url');
        console.log(deleteUrl);
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST', // Laravel likes POST + _method override
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire("Deleted!", response.message, "success");
                            $('#datatable').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function () {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });

</script>
@endsection