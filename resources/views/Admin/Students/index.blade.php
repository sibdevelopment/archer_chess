@extends('layouts.admin')
@section('title')
    Students
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
                    <div class="card-header px-4 py-3 border-bottom">
                        <form action="{{ route('admin.students.export') }}" method="POST" enctype="multipart/form-data"
                            id="exportForm">
                            @csrf
                            <div class="row">
                                <div class="col-3 d-flex justify-content-start">
                                    <h5 class="card-title fw-semibold mb-0 lh-sm">Students</h5>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    @if (!$isCoach)
                                        <div class="input-group">
                                            <input name="start_date" id="start_date" type="text"
                                                class="form-control daterange" placeholder="Start Fees Date ..." />
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar fs-5"></i>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    @if (!$isCoach)
                                        <div class="input-group">
                                            <input name="date" id="date" type="text"
                                                class="form-control daterange" placeholder="End Fees Date ..." />
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar fs-5"></i>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                @if (!$isCoach)
                                    <div class="col-3 d-flex justify-content-end">
                                        <a href="{{ route('admin.students.create') }}" class="btn btn-info">
                                            Create Students
                                            &nbsp;
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-2">
                                {{-- <div class="col-3 d-flex justify-content-end">
                                    <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Coach</option>
                                    </select>
                                </div>
                                <div class="col-3 d-flex justify-content-end">
                                    <select name="batch" id="batch" class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Batch</option>
                                    </select>
                                </div> --}}
                                @if ($isAdminOrSuperAdmin)
                                    <div class="col-3 d-flex justify-content-end">
                                        <select name="coach_id[]" id="coach_id"
                                            class="select2 form-select form-select-sm   pure-white"
                                            aria-label=".form-select-sm example" multiple>
                                            @foreach ($coaches as $coach)
                                                <option value="{{ $coach->id }}"
                                                    {{ $coachId && $coachId == $coach->id ? 'selected' : '' }}>
                                                    {{ $coach->user->first_name }} {{ $coach->user->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-3 d-flex justify-content-end">
                                    <select name="batch" id="batch"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 d-flex justify-content-end ">
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
                                <div class="col-2 d-flex justify-content-end ">
                                    <select name="weekday" id="weekday"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Weekday</option>
                                        <option value="Monday">MONDAY</option>
                                        <option value="Tuesday">TUESDAY</option>
                                        <option value="Wednesday">WEDNESDAY</option>
                                        <option value="Thursday">THURSDAY</option>
                                        <option value="Friday">FRIDAY</option>
                                        <option value="Saturday">SATURDAY</option>
                                        <option value="Sunday">SUNDAY</option>
                                    </select>

                                </div>
                                <div class="col-2 d-flex justify-content-end">
                                    <select name="status" id="status"
                                        class="select2 form-select form-select-sm   pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Status</option>
                                        <option value="ACTIVE">Active</option>
                                        <option value="INACTIVE">Inactive</option>
                                        <option value="STANDBY">StandBy</option>
                                        <option value="FEESDUE">Fees Due</option>
                                        <option value="CHANGECLASS">Change Class</option>
                                        <option value="CURRENT_DAY">Current Day Fees Due</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-2 d-flex justify-content-end">
                                    <input type="text" class="form-control" id="user_id" name="user_id"
                                        placeholder="Search by User ID"
                                        style="border: var(--bs-border-width) solid #000000;">
                                </div>
                                {{-- <div class="col-2 d-flex justify-content-end">
                                    <select name="coach" id="coach"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Coach</option>
                                    </select>
                                {{-- <div class="col-2 d-flex justify-content-end">
                                    <select name="level_id" id="level_id"
                                        class="select2 form-select form-select-sm   pure-white"
                                        aria-label=".form-select-sm example">
                                        <option value="">Select Level</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                @if ($isAdminOrSuperAdmin)
                                    <div class="col-2 d-flex justify-content-end">
                                        <button type="submit" class="form-control text-white text-center"
                                            style="background: #ff4d4d;"> <i
                                                class="ti ti-file-spreadsheet"></i>Export</button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="row mb-2 mt-2">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center"
                                id="statusButtonsWrapper">
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val(''); $('#status').trigger('change');">All</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('ACTIVE'); $('#status').trigger('change');">Active</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('INACTIVE'); $('#status').trigger('change');">Inactive</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('STANDBY'); $('#status').trigger('change');">StandBy</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('FEESDUE'); $('#status').trigger('change');">Fees
                                    Due</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('CHANGECLASS'); $('#status').trigger('change');">Change
                                    Class</button>
                                <button class="btn btn-outline-primary btn-sm status-ui-btn"
                                    onclick="$('#status').val('CURRENT_DAY'); $('#status').trigger('change');">Current Day
                                    Fees Due</button>   
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle"
                                id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        @if (!$isCoach)
                                            <th width="3%">
                                                <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                            </th>
                                            <th width="3%">
                                                <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                            </th>
                                        @endif
                                        <th width="15%">
                                            <h6 class="fs-3 fw-semibold mb-0">Full Name</h6>
                                        </th>
                                        <th width="15%">
                                            <h6 class="fs-3 fw-semibold mb-0">Timezone</h6>
                                        </th>
                                        <!-- <th width="5%">
                                                                                                    <h6 class="fs-3 fw-semibold mb-0">Age</h6>
                                                                                                </th> -->
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0"> ID</h6>
                                        </th>
                                        @if (!$isCoach)
                                            <th width="3%">
                                                <h6 class="fs-3 fw-semibold mb-0"> Fees | End Date</h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                            </th>
                                        @endif
                                        @if ($isAdminOrSuperAdmin)
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Created By </h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Updated By </h6>
                                            </th>
                                        @endif
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Batchschedule</h6>
                                        </th>
                                        <!-- <th width="10%">
                                                                                                    <h6 class="fs-3 fw-semibold mb-0">Email</h6>
                                                                                                </th> -->
                                        <!-- <th width="10%">
                                                                                                    <h6 class="fs-3 fw-semibold mb-0">Address</h6>
                                                                                                </th> -->


                                        {{-- <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Last Payment Level ID</h6>
                                        </th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>


    <!-- Status Change Modal :: -->
    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog"
        aria-labelledby="statusChangeModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog modal-md" role="document">
            <form id="statusChangeForm" action="{{ route('admin.students.change.status') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="studentId" name="student_id">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <select class="form-select" id="model-status" name="status">
                                    <option selected disabled hidden>select status ...</option>
                                    {{-- <option value="ACTIVE">Active</option> --}}
                                    <option value="INACTIVE">Inactive</option>
                                    <option value="STANDBY">StandBy</option>
                                    {{-- <option value="FEESDUE">Fees Due</option> --}}
                                    <option value="CHANGECLASS">Change Class</option>
                                </select>
                                <div id="status-error" style="color:red"></div>
                            </fieldset>
                        </div>
                        <br>
                        <div class="col-md-12" id="employee_div">
                            <fieldset class="form-group">
                                <label for="employee" class="form-label">Assign Employee</label>
                                <select name="employee_id" id="employee_id" class="form-select">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->user->first_name }}
                                            {{ $employee->user->last_name }}</option>
                                    @endforeach
                                </select>
                                <div id="employee_id-error" style="color:red"></div>
                            </fieldset>
                        </div>
                        <br>
                        <div class="col-md-12" id="remark_div">
                            <fieldset class="form-group">
                                <label for="employee" class="form-label">Remark</label>
                                <textarea name="remark" id="remark" placeholder="Enter remark" class="form-control" cols="30"
                                    rows="3"></textarea>
                                <div id="remark-error" style="color:red"></div>
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

    <!-- ------------------------------------------------------------------- :: -->


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
                    Are you sure you want to delete this student data?
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
    <script>
        $(document).on('submit', '#exportForm', function(e) {
            e.preventDefault(); // stop normal form submit

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


        $(document).ready(function() {
            $('#employee_div').hide();
            $('#remark_div').hide();
        });

        // -- ------------------------------------------------------------------- :: -->
        $(document).ready(function() {

            // add placeholder to the coach_id
            $('#coach_id').select2({
                placeholder: "Select Coach",
            });

            $('#model-status').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue == 'CHANGECLASS') {
                    $('#employee_div').show();
                    $('#remark_div').show();
                } else {
                    $('#employee_div').hide();
                    $('#remark_div').hide();
                }
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('#date').val('');
            $('#start_date').val('');

            var userRole = @json($role);
            var defaultCoachId = @json($coachId);

            function fetchData(url, data, successCallback) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
                    success: successCallback,
                    error: function(xhr, status, error) {
                        console.error('Error fetching data from ' + url + ':', error);
                    }
                });
            }

            function populateDropdown(selectElement, data, defaultOptionText, itemCallback) {
                selectElement.empty();
                selectElement.append('<option value="">' + defaultOptionText + '</option>');
                $.each(data, itemCallback);
            }
            // Fetch coaches
            function fetchCoaches(batchId = null) {
                var data = batchId ? {
                    batch_id: batchId
                } : {};
                fetchData('{{ route('admin.students.get.coaches') }}', data, function(data) {
                    var coachSelect = $('#coach');
                    populateDropdown(coachSelect, data, 'Select Coach', function(index, coach) {
                        if (coach.user) {
                            var option = $('<option></option>')
                                .attr('value', coach.id)
                                .text(coach.user.first_name + ' ' + coach.user.last_name);
                            if (defaultCoachId && coach.id == defaultCoachId) {
                                option.attr('selected', 'selected');
                            }
                            coachSelect.append(option);
                        } else {
                            console.warn('Coach user data missing for coach:', coach);
                        }
                    });
                    // Trigger change event if defaultCoachId is set
                    if (defaultCoachId) {
                        coachSelect.trigger('change');
                    }
                });
            }
            // Fetch batches
            function fetchBatches(coachId = null) {
                var data = coachId ? {
                    coach_id: coachId
                } : {};
                fetchData('{{ route('admin.students.get.batches') }}', data, function(data) {
                    var batchSelect = $('#batch');
                    populateDropdown(batchSelect, data, 'Select Batch', function(index, batch) {
                        batchSelect.append('<option value="' + batch.id + '">' + batch.name +
                            '</option>');
                    });
                });
            }
            // Initial fetch of batches and coaches
            fetchBatches();
            fetchCoaches();

            // Fetch batches when a coach is selected
            $('#coach').change(function() {
                var coachId = $(this).val();
                fetchBatches(coachId);
            });

            // Fetch coaches when a batch is selected
            $('#batch').change(function() {
                var batchId = $(this).val();
                fetchCoaches(batchId);
            });
        });

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
                    url: '{!! route('admin.students.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                        d.country = $('#country').val();
                        d.batch = $('#batch').val();
                        d.coach = $('#coach').val();
                        d.date = $('#date').val();
                        d.start_date = $('#start_date').val();
                        d.weekday = $('#weekday').val();
                        d.level_id = $('#level_id').val();
                        d.coach_id = $('#coach_id').val();
                        d.user_id = $('#user_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    @if (!$isCoach)
                        {
                            data: 'action',
                            name: 'students.id',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'status',
                            name: 'students.id',
                            orderable: false,
                            searchable: false
                        },
                    @endif

                    {
                        data: 'first_name',
                        name: 'students.first_name',
                        orderable: false
                    }, {
                        data: 'timezone',
                        name: 'students.timezone',
                        orderable: false
                    },
                    // {data: 'age',name: 'students.age', orderable: false},
                    {
                        data: 'student_id',
                        name: 'students.student_id',
                        orderable: false
                    },
                    @if (!$isCoach)
                        {
                            data: 'student_fees',
                            name: 'students.id',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'mobile',
                            name: 'students.mobile',
                            orderable: false
                        },
                    @endif
                    @if ($isAdminOrSuperAdmin)
                        {
                            data: 'created_by',
                            name: 'students.created_by',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'updated_by',
                            name: 'students.updated_by',
                            orderable: false,
                            searchable: false
                        },
                    @endif {
                        data: 'batch',
                        name: 'students.id',
                        orderable: false
                    },
                    {
                        data: 'batch_schedule',
                        name: 'students.id',
                        orderable: false
                    },
                    //{data: 'email',name: 'students.email', orderable: false},
                    // {data: 'address',name: 'students.address', orderable: false},
                    // {
                    //     data: 'batch',
                    //     name: 'students.batch',
                    //     orderable: false,
                    //     searchable: false
                    // },
                    // {
                    //     data: 'last_payment_level_id',
                    //     name: 'students.last_payment_level_id',
                    //     orderable: false,
                    //     searchable: false
                    // },
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
            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#batch').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#start_date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#level_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#weekday').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#user_id').on('keyup', function() {
                dataTable.ajax.reload(null, false);
            });
            // Trigger the data table reload on page load if a coach is selected
            // if (defaultCoachId) {
            //     dataTable.ajax.reload();
            // }
        });

        // Status Change Modal ::
        $(document).on('click', '.student-status-switch', function() {
            var id = $(this).data('id');
            var routeKey = $(this).data('routekey');
            var status = $(this).data('status');
            $('#studentId').val(id);
            $('#routeKey').val(routeKey);
        });

        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('student_id', $('#studentId').val());
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
                        $('#statusChangeForm')[0].reset();

                        $('#statusChangeModal').modal('hide');
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('div[id$="-error"]').empty();
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });

        $(document).on('change', '.student-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.students.change.status') }}",
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
                            $('#datatable').DataTable().ajax.reload();
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
            var studentId = $(this).data('student-id');
            // Set the studentId as a data attribute on the confirm button for later use
            $('#confirmDelete').data('student-id', studentId);
            // Show the confirmation modal
            $('#deleteConfirmationModal').modal('show');
        });
        $(document).on('click', '#confirmDelete', function() {
            var studentId = $(this).data('student-id'); // Retrieve the studentId from the confirm button
            $.ajax({
                url: "{{ route('admin.students.destroy', '') }}" + '/' + studentId,
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    _method: 'DELETE',
                    student_id: studentId
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
    </script>
@endsection
