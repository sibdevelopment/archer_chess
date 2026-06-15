@extends('layouts.admin')
@section('title')
Masterclasses
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-7 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Masterclasses</h5>
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <a href="{{ route('admin.masterclasses.create') }}" class="btn btn-info">
                                    Create Masterclass
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
                                id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">date</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Masterclass Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Student</h6>
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
                    Are you sure you want to delete this Holiday data?
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
                scrollX: false,
                pageLength: 100,
                ajax: {
                    url: '{!! route('admin.masterclasses.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'masterclasses.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'masterclasses.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'masterclasses.name',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'masterclasses.name',
                        orderable: false
                    },
                    {
                        data: 'coach',
                        name: 'coach',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batches',
                        name: 'batches',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'levels',
                        name: 'levels',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'students',
                        name: 'students',
                        orderable: false,
                        searchable: false
                    },

                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");
            $('#status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
    </script>
@endsection
