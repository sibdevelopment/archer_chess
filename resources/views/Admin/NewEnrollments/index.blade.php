@extends('layouts.admin')
@section('title')
    New Enrollments
@endsection
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px;
        }
    </style>
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
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-2 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">New Enrollments</h5>
                            </div>
                            <div class="col-4 d-flex justify-content-end">
                                <select name="country" id="country" class="form-control select2"
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
                            <div class="col-4 d-flex justify-content-end">
                                <select class="form-control select2" name="created_by" id="created_by">
                                    <option value="">Select Created By</option>
                                    @foreach ($created_bys as $created_by)
                                        <option value="{{ $created_by->id }}">
                                            {{ $created_by->first_name }} {{ $created_by->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 d-flex justify-content-end align-items-center gap-2">
                                <a href="{{ route('admin.newenrollments.export') }}" class="btn btn-secondary">
                                    Export
                                    &nbsp;
                                    <i class="ti ti-table-export"></i>
                                </a>
                            </div>
                            <div class="col-12 d-flex justify-content-evenly align-items-center gap-2 mt-3">
                                <div class="row justify-content-end">
                                    @if ($user->id == 1)
                                        <div class="col-3 d-flex justify-content-end">
                                            <select class="form-control select2" name="employee_id" id="employee_id">
                                                <option value="">Select a Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->user->first_name }} {{ $employee->user->last_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-3 d-flex justify-content-end">
                                        <select class="form-control select2 batch_id" name="batch_id" id="batch_id">
                                            <option value="">Select a Batch</option>
                                            @foreach ($batches as $batch)
                                                <option value="{{ $batch->id }}">
                                                    {{ $batch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3 d-flex justify-content-end">
                                        <select class="form-control select2" name="payment_level_id" id="payment_level_id">
                                            <option value="">Select a Payment Level</option>
                                            @foreach ($payment_levels as $payment_level)
                                                <option value="{{ $payment_level->id }}">
                                                    {{ $payment_level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3 d-flex justify-content-end">
                                        <div class="input-group">
                                            <input name="start_date" id="start_date" type="text"
                                                class="form-control daterange" value=""
                                                placeholder="Select a start date range" />
                                            <span class="input-group-text">
                                                <i class="ti ti-calendar fs-5"></i>
                                            </span>
                                        </div>
                                    </div>
                                    {{-- <div class="col-3 d-flex justify-content-end">
                                        <input type="date" class="form-control" name="start_date" id="start_date">
                                    </div> --}}
                                </div>
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
                                        <th width="2%">
                                            <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                        </th>
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Portal ID</h6>
                                        </th>
                                        <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Full Name</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Mobile</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Payment Level</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Employee</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Start Date</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">End Date</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Receive Date</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Currency</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Fees</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Received Fees</h6>
                                        </th>
                                        <th width="30%">
                                            <h6 class="fs-3 fw-semibold mb-0">Remark</h6>
                                        </th>
                                        <th width="30%">
                                            <h6 class="fs-3 fw-semibold mb-0">Created By</h6>
                                        </th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script type="text/javascript">
        $(".daterange").daterangepicker();
        $(document).ready(function() {
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('#date').val('');
            $('#start_date').val('');
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
                    url: '{!! route('admin.newenrollments.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.batch_id = $('#batch_id').val();
                        d.payment_level_id = $('#payment_level_id').val();
                        d.start_date = $('#start_date').val();
                        d.employee_id = $('#employee_id').val();
                        d.country = $('#country').val();
                        d.created_by = $('#created_by').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'new_enrollments.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'student_id',
                        name: 'students.student_id',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'students.first_name',
                        orderable: false
                    },
                    {
                        data: 'mobile',
                        name: 'students.mobile',
                        orderable: false
                    },
                    {
                        data: 'country',
                        name: 'students.country',
                        orderable: false
                    },
                    {
                        data: 'payment_level',
                        name: 'payment_level',
                        orderable: false
                    },
                    {
                        data: 'batch',
                        name: 'batchs.name', // ✅ This is an actual column now from leftJoin
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'employee',
                        name: 'employee_users.first_name',
                        orderable: false
                    },
                    {
                        data: 'start_date',
                        name: 'new_enrollments.start_date',
                        orderable: false
                    },
                    {
                        data: 'end_date',
                        name: 'new_enrollments.end_date',
                        orderable: false
                    },
                    {
                        data: 'receive_date',
                        name: 'new_enrollments.receive_date',
                        orderable: false
                    },
                    {
                        data: 'currency',
                        name: 'new_enrollments.currency',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'fees',
                        name: 'new_enrollments.fees',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'received_fees',
                        name: 'new_enrollments.received_fees',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'remark',
                        name: 'new_enrollments.remark',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'created_by',
                        name: 'new_enrollments.created_by',
                        orderable: false
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
            $('#batch_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#payment_level_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#start_date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#employee_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            $('#created_by').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
    </script>
@endsection
