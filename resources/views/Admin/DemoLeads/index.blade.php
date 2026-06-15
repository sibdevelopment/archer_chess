@extends('layouts.admin')
@section('title')
    Demo Leads
@endsection
@section('content')

    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $coachId = null;
        if (in_array('Coach', $role) && $user->coach) {
            $coachId = $user->coach->id;
        }
        $isCoach = in_array('Coach', $role);
        $isAdminOrSuperAdmin = in_array('Admin', $role) || in_array('SuperAdmin', $role);

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
        /* Compact, neat button styling */
        #statusButtonsWrapper .status-ui-btn {
            min-width: 110px;
            /* gives consistent button width */
            padding: 0.375rem 0.6rem;
            /* slightly tighter than default */
            border-radius: 6px;
            white-space: nowrap;
        }

        /* Active visual state */
        #statusButtonsWrapper .status-ui-btn.active {
            background-color: #0d6efd;
            /* Bootstrap primary */
            color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 1px 0 rgba(13, 110, 253, 0.15);
        }

        /* On very small screens make buttons full width stacked */
        @media (max-width: 575.98px) {
            #statusButtonsWrapper .status-ui-btn {
                flex: 1 1 100%;
                min-width: 0;
            }
        }
    </style>

    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <form action="{{ route('admin.demoleads.export') }}" method="POST" enctype="multipart/form-data"
                        id="exportDemoLeadsForm">
                        @csrf
                        <div class="card-header px-4 py-3 border-bottom">
                            <div class="row">
                                <div class="col-5 d-flex justify-content-start">
                                    <h5 class="card-title fw-semibold mb-0 lh-sm">Demo Leads</h5>
                                </div>
                                <div class="col-4 d-flex justify-content-end">
                                    <div class="input-group">
                                        <input name="date" id="date" type="text"
                                            class="form-control daterange" />
                                        <span class="input-group-text">
                                            <i class="ti ti-calendar fs-5"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    <a href="{{ route('admin.demoleads.create') }}" class="btn btn-info">
                                        Create Demo Leads
                                        &nbsp;
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-3 d-flex justify-content-end">
                                    <select name="coach" id="coach"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Coach</option>
                                        @foreach ($coaches as $coach)
                                            <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    <select name="country" id="country"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
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
                                    <select name="level" id="level"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Level</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 d-flex justify-content-end">
                                    <select name="status" id="status"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Status</option>
                                        <option value="SCHEDULED">SCHEDULED</option>
                                        <option value="RESCHEDULED">RESCHEDULED</option>
                                        <option value="DEMO DONE">DEMO DONE</option>
                                        <option value="CANCELLED">CANCELLED</option>
                                        <option value="CONVERTED">CONVERTED</option>
                                        <option value="ROWLEAD">ROWLEAD</option>
                                        <option value="INTERESTED">INTERESTED</option>
                                        <option value="NOT INTERESTED">NOT INTERESTED</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6 text-end"></div>
                                @if ($isAdminOrSuperAdmin)
                                    <div class="col-2 text-end">
                                        <button type="submit" class="form-control text-white text-center"
                                            style="background: #ff4d4d;"> <i class="ti ti-file-spreadsheet"></i>
                                            Export</button>
                                    </div>
                                @endif
                                <div class="col-2 d-flex justify-content-end">
                                    <select name="employee_id" id="employee_id"
                                        class="form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->user->id }}">{{ $employee->user->first_name }}
                                                {{ $employee->user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 text-end">
                                    <select name="is_hide" id="is_hide" class="form-select form-select-sm   pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="0">Active</option>
                                        <option value="1 ">Deleted</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Improved status buttons UI — logic (onclick) unchanged -->
                    <div class="row mb-2 mt-2">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center" id="statusButtonsWrapper">
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val(''); $('#status').trigger('change');">All</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('SCHEDULED'); $('#status').trigger('change');">Scheduled</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('RESCHEDULED'); $('#status').trigger('change');">Rescheduled</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('DEMO DONE'); $('#status').trigger('change');">Demo
                                    Done</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('CANCELLED'); $('#status').trigger('change');">Cancelled</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('CONVERTED'); $('#status').trigger('change');">Converted</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('ROWLEAD'); $('#status').trigger('change');">Rowlead</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('INTERESTED'); $('#status').trigger('change');">Interested</button>

                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('NOT INTERESTED'); $('#status').trigger('change');">Not
                                    Interested</button>
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
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Reason</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0"> Status</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0"> Demo Session</h6>
                                        </th>
                                        <th width="15%">
                                            <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Date Time</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Convert</h6>
                                        </th>
                                        @if ($isAdminOrSuperAdmin)
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Created By </h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Updated By </h6>
                                            </th>
                                        @endif
                                        {{-- <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Remark</h6>
                                        </th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Status Change Modal :: -->
    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog"
        aria-labelledby="statusChangeModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <form id="statusChangeForm" action="{{ route('admin.demoleads.change.status') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="demoleadId" name="demolead_id">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <select class="form-select" id="status" name="status">
                                    <option selected disabled hidden>select status ...</option>
                                    <option value="SCHEDULED">SCHEDULED</option>
                                    <option value="RESCHEDULED">RESCHEDULED</option>
                                    <option value="DEMO DONE">DEMO DONE</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                    <option value="CONVERTED">CONVERTED</option>
                                    <option value="ROWLEAD">ROWLEAD</option>
                                    <option value="INTERESTED">INTERESTED</option>
                                    <option value="NOT INTERESTED">NOT INTERESTED</option>
                                </select>
                                <div id="status-error" style="color:red"></div>
                            </fieldset>
                        </div>
                        <div class="col-md-12 mt-3">
                            <fieldset class="form-group">
                                <label for="reason" class="form-label">Reason</label>
                                <input type="text" class="form-control" id="reason" name="reason"
                                    placeholder="Enter reason">
                                <div id="reason-error" style="color:red"></div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <img id="ajax-loader" class="Loader" style="width: 6%; display: none !important;"
                            src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt="">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Convert To Student Modal :: -->
    <div class="modal fade text-left" id="convertModal" tabindex="-1" role="dialog"
        aria-labelledby="convertModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog modal-lg" role="document">
            <form id="convertForm" action="" method="POST">
                @csrf
                <input type="hidden" id="demoleadIdConvert" name="demolead_id">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h4 class="text-dark">Convert To Student</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to convert this demo lead to a student?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Convert</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade text-left" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header rounded" style="background-color: #539BFF !important;">
                    <h5 class="modal-title text-white" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Demo Lead data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remark Modal -->
    <div class="modal fade text-left" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel"
        aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="remarkModalLabel">Full Remark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Full remark will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reason Modal -->
    <div class="modal fade text-left" id="reasonModal" tabindex="-1" role="dialog" aria-labelledby="reasonModalLabel"
        aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="reasonModalLabel">Full Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Full reason will be displayed here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script type="text/javascript">
        $(".daterange").daterangepicker();

        $(document).ready(function() {
            $('.daterange').daterangepicker({
                autoUpdateInput: false, // DON'T set a value automatically
                locale: {
                    format: 'MM/DD/YYYY'
                },
                opens: 'left' // optional
            });

            // When user applies a range, fill the input and trigger change
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(
                    picker.startDate.format('MM/DD/YYYY') +
                    ' - ' +
                    picker.endDate.format('MM/DD/YYYY')
                );
                $(this).trigger('change'); // so your DataTable listener sees it
            });

            // When user cancels, clear the input and trigger change
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $(this).trigger('change');
            });

            // Ensure input starts empty
            $('#date').val('');
        });


        $(document).on('submit', '#exportDemoLeadsForm', function(e) {
            e.preventDefault();

            var form = $(this);
            var url = form.attr('action');
            var method = form.attr('method');

            $.ajax({
                url: url,
                type: method,
                data: form.serialize(),
                xhrFields: {
                    responseType: 'blob' // so we can handle file download
                },
                success: function(data, status, xhr) {
                    // Create a link to download the CSV file
                    var blob = new Blob([data], {
                        type: 'text/csv'
                    });
                    var downloadUrl = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = downloadUrl;

                    // Try to get filename from response headers
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('filename=') !== -1) {
                        filename = disposition.split('filename=')[1].replace(/"/g, '');
                    } else {
                        filename = 'export.csv';
                    }

                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    window.URL.revokeObjectURL(downloadUrl);
                },
                error: function() {
                    alert('Error exporting file.');
                }
            });
        });

        // DataTable ::
        $(function() {
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                pageLength: 50,
                ajax: {
                    url: '{!! route('admin.demoleads.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                        d.level = $('#level').val();
                        d.country = $('#country').val();
                        d.coach = $('#coach').val();
                        d.date = $('#date').val();
                        d.sequence = $('#sequence').val();
                        d.is_hide = $('#is_hide').val();
                        d.employee_id = $('#employee_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'demoleads.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'reason',
                        name: 'demoleads.reason',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'demoleads.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'demosession',
                        name: 'demoleads.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'first_name',
                        name: 'demoleads.first_name',
                        orderable: false
                    },
                    //{data: 'age',name: 'demoleads.age'},
                    {
                        data: 'mobile',
                        name: 'demoleads.mobile',
                        orderable: false
                    },
                    //{data: 'address',name: 'demoleads.address', orderable: false},
                    {
                        data: 'date_time',
                        name: 'demoleads.date',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'convert',
                        name: 'demoleads.id',
                        orderable: false,
                        searchable: false
                    },

                    @if ($isAdminOrSuperAdmin)
                        {
                            data: 'created_by',
                            name: 'demoleads.created_by',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'updated_by',
                            name: 'demoleads.updated_by',
                            orderable: false,
                            searchable: false
                        },
                    @endif
                    // {
                    //     data: 'remark',
                    //     name: 'demoleads.remark',
                    //     orderable: false
                    // },

                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
            });
            $ // ✅ Add Bootstrap classes to DataTable export buttons
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            // ✅ Common reload function for all filter fields
            const filterSelectors = [
                '#status',
                '#level',
                '#country',
                '#coach',
                '#date',
                '#sequence',
                '#is_hide',
                '#employee_id'
            ];

            // Reload DataTable when any filter changes
            $(filterSelectors.join(', ')).on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            // ✅ Handle date range cancel (clear + reload)
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                dataTable.ajax.reload(null, false);
            });

        });

        // Status Change Modal ::
        $(document).on('click', '.demolead-status-switch', function() {
            var id = $(this).data('id');
            var routeKey = $(this).data('routekey');
            $('#demoleadId').val(id);
            $('#routeKey').val(routeKey);
        });

        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('demolead_id', $('#demoleadId').val());
            formData.append('route_key', $('#routeKey').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#ajax-loader').show();
                },
                complete: function() {
                    $('#ajax-loader').hide();
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                        $('#statusChangeModal').modal('hide');
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw();
                        }
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while changing the status.');
                    }
                }
            });
        });

        // Convert To Student Modal ::
        $(document).on('click', '.convert-to-student', function() {
            var id = $(this).data('id');
            $('#demoleadIdConvert').val(id);
        });

        $('#convertForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#demoleadIdConvert').val();
            var formData = new FormData(this);
            formData.append('demolead_id', id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/demoleads/' + id + '/convert',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                    $('#convertModal').modal('hide');
                    if ($.fn.DataTable.isDataTable("#datatable")) {
                        $('#datatable').DataTable().draw();
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while converting the demo lead.');
                    }
                }
            });
        });

        // Delete Confirmation Modal ::
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var demoleadId = $(this).data('demolead-id');
            $('#confirmDelete').data('demolead-id', demoleadId);
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on('click', '#confirmDelete', function() {
            var demoleadId = $(this).data('demolead-id');
            $.ajax({
                url: "{{ route('admin.demoleads.destroy', '') }}" + '/' + demoleadId,
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    _method: 'DELETE',
                    demolead_id: demoleadId
                },
                success: function(data) {
                    $('#datatable').DataTable().draw(
                        false); // Redraw the DataTable without resetting the paging
                    $('#deleteConfirmationModal').modal('hide'); // Hide the confirmation modal
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

        // Show the full remark in the modal ::
        $(document).on('click', '.demoLeadEnquiry-remark', function(e) {
            e.preventDefault();
            var demoleadId = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{ route('admin.demoleads.getRemark') }}', // Define this route in your routes file
                data: {
                    _token: '{{ csrf_token() }}',
                    id: demoleadId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Display the full remark in the modal
                        $('#remarkModal .modal-body').text(response.remark);
                        $('#remarkModal').modal('show');
                    } else {

                    }
                },
                error: function(xhr) {

                }
            });
        });

        // Show the full reason in the modal ::
        $(document).on('click', '.demoLeadEnquiry-reason', function(e) {
            e.preventDefault();
            var demoleadId = $(this).attr('data-id');

            // Make an AJAX call to fetch the full reason
            $.ajax({
                type: "POST",
                url: '{{ route('admin.demoleads.getReason') }}', // Define this route in your routes file
                data: {
                    _token: '{{ csrf_token() }}',
                    id: demoleadId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Display the full reason in the modal
                        $('#reasonModal .modal-body').text(response.reason);
                        $('#reasonModal').modal('show');
                    } else {
                        toastr.error(response.message || 'There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error('An unexpected error occurred. Please try again later.', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                }
            });
        });

        // Show the full remark in the modal ::
        $(document).on('click', '.demoLeadEnquiry-remark', function(e) {
            e.preventDefault();
            var fullRemark = $(this).data('remark'); // from data-remark if present
            var demoleadId = $(this).attr('data-id'); // may be undefined
            if (fullRemark !== undefined && fullRemark !== '') {
                $('#remarkModal .modal-body').text(fullRemark);
                $('#remarkModal').modal('show');
                return;
            }
        });
    </script>
@endsection
