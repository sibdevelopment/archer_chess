@extends('layouts.admin')
@section('title')
    Change Batches
@endsection
@section('content')
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-2 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Change Batches</h5>
                            </div>
                            <div class="col-10 d-flex justify-content-end align-items-center gap-2">
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
                                        <input type="date" class="form-control" name="start_date" id="start_date">
                                    </div>
                                </div>
                                {{-- <a href="{{ route('admin.newenrollments.export') }}" class="btn btn-secondary">
                                    Export
                                    &nbsp;
                                    <i class="ti ti-table-export"></i>
                                </a> --}}
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
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- ------------------------------------------------------------------- :: -->


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
                    url: '{!! route('admin.changeclasses.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.batch_id = $('#batch_id').val();
                        d.payment_level_id = $('#payment_level_id').val();
                        d.start_date = $('#start_date').val();
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
                        name: 'changeclasses.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'student_id',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'mobile',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'country',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'payment_level',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'batch',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'employee',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'start_date',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'end_date',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'receive_date',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'currency',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'fees',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'received_fees',
                        name: 'changeclasses.id',
                        orderable: false
                    },
                    {
                        data: 'remark',
                        name: 'changeclasses.remark',
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
        });


    </script>


@endsection
