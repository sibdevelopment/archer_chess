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
                            <div class="col-2 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Masterclasses</h5>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <select name="coach_id" id="coach_id" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="">Select Coach</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                            {{ $coach->user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <div class="input-group">
                                    <input name="date" id="date" type="text" class="form-control daterange"
                                        placeholder="Search Date ..." />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <select name="country" id="country" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
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
                            <div class="col-3 d-flex justify-content-end">
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
                                            <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Time</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Start Url</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Join Url</h6>
                                        </th>
                                        {{-- <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Batches</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Levels</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Students</h6>
                                        </th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

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

    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script>
        $(".daterange").daterangepicker();
    </script>

    <script type="text/javascript">
        $('#date').val('');
        $(document).on('click', '.copy-link', function() {
            const url = $(this).data('url');
            console.log('Copying URL:', url);
            const $tempInput = $('<input>');
            $('body').append($tempInput);
            $tempInput.val(url).select();
            document.execCommand('copy');
            $tempInput.remove();
            alert('Link copied to clipboard!');
        });

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
                        d.country = $('#country').val();
                        d.coach_id = $('#coach_id').val();
                        d.date = $('#date').val();
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
                        data: 'time',
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
                        data: 'country',
                        name: 'country',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'start_url',
                        name: 'masterclasses.start_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'join_url',
                        name: 'masterclasses.join_url',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 8, 9],
                    className: "text-center"
                }, ],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");
            $('#status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#coach_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });

        $(document).on('change', '.masterclass-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.masterclasses.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    route_key: routeKey,
                    status: status
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw();
                        }
                    } else {
                        toastr.error(data.message, '', {
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


        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var masterclassId = $(this).data('masterclass-id');
            $('#confirmDelete').data('masterclass-id', masterclassId);
            $('#deleteConfirmationModal').modal('show');
        });
        $(document).on('click', '#confirmDelete', function() {
            var masterclassId = $(this).data('masterclass-id');
            $.ajax({
                url: "{{ route('admin.masterclasses.destroy', '') }}" + '/' + masterclassId,
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    _method: 'DELETE',
                    masterclass_id: masterclassId
                },
                success: function(data) {
                    $('#datatable').DataTable().draw(
                        false);
                    $('#deleteConfirmationModal').modal('hide');
                    toastr.success(data.success, '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                },
                error: function(data) {
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
