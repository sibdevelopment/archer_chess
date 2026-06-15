@extends('layouts.admin')
@section('title')
    Student Fees
@endsection
<style>
     
</style>
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
        #datatable tbody tr td {
            color: black !important;
        }

        div.dataTables_wrapper div.dataTables_filter label {
            font-weight: normal;
            white-space: nowrap;
            text-align: left;
            display: none;
        }

        .dataTables_filter,
        .dataTables_length {
            margin-bottom: 0px !important;
            margin-top: 0px !important;
            text-align: right !important;
        }
    </style>
    <section>
        <div class="row">
            <div class="col-3">
                <a href="/admin/students" class="btn btn-primary mb-3">
                    <span>
                        <i class="ti ti-chevron-left mr-4"></i>
                    </span>Back</a>
                <a href="/admin/students/{{ $student->id }}/student_fees"
                    class="card w-100 position-relative overflow-hidden mb-1 text-white bg-info">
                    <div class="p-3 fw-semibold">
                        Student : {{ $student->first_name }} {{ $student->last_name }}
                    </div>
                </a>
                <div class="card w-100 position-relative overflow-hidden" style="margin-bottom:2%;">
                    <div class="card-header px-4 py-3 border-bottom">
                        @if (!empty($student->age))
                            <p class="text-black fw-semibold">Age : {{ $student->age }}</p>
                        @endif
                        @if (!empty($student->mobile))
                            <p class="text-black fw-semibold">Mobile: {{ $student->mobile }}</p>
                        @endif
                        @if (!empty($student->address))
                            <p class="text-black fw-semibold">Address: {{ $student->address }}</p>
                        @endif
                        @if (!empty($student->email))
                            <p class="text-black fw-semibold">Email: {{ $student->email }}</p>
                        @endif
                        @if (!empty($student->student_id))
                            <p class="text-black fw-semibold">ID: {{ $student->student_id }}</p>
                        @endif
                        @if (!empty($student->status))
                            <p class="text-black fw-semibold">Status : {{ ucwords(strtolower($student->status)) }}</p>
                        @endif

                    </div>
                </div>
                <div class="con">
                    @php
                        $student_latest_batch = $student->latestBatch;
                    @endphp

                    @if ($student_latest_batch)
                        <div class="card text-white bg-success mb-1 shadow-sm rounded-3">
                            <div class="card-header fw-bold fs-3">
                                Batch Details
                            </div>
                            <div class="card-bodya p-3">
                                <div class="row mb-2">
                                    <div class="col-sm-4 fw-semibold">Batch Name:</div>
                                    <div class="col-sm-8">{{ $student_latest_batch->batch->name }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 fw-semibold">Start Date:</div>
                                    <div class="col-sm-8">
                                        {{ \Carbon\Carbon::parse($student_latest_batch->batch->start_date)->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 fw-semibold">End Date:</div>
                                    <div class="col-sm-8">
                                        {{ \Carbon\Carbon::parse($student_latest_batch->batch->end_date)->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card text-white bg-danger mb-3 shadow-sm rounded-3">
                            <div class="card-body">
                                <div class="text-center fw-semibold">
                                    No Batch Assigned
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            <div class="col-9">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm text-black">Student Fees </h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.students.student_fees.create', ['student' => $student->id]) }}"
                                    class="btn bg-info text-white fw-semibold">
                                    Create Fees
                                    &nbsp;
                                    <i class="ti ti-plus fw-semibold"></i>
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
                                        @if (auth()->check() && (auth()->user()->student_fees_edit === 'YES' || $isAdminOrSuperAdmin))
                                            <th width="3%">
                                                <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                            </th>
                                        @endif
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">PDF</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Receive Date</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Currency</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Monthly Fees</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Total Amount Paid</h6>
                                        </th>
                                        @if ($isAdminOrSuperAdmin)
                                            <th width="">
                                                <h6 class="fs-3 fw-semibold mb-0">Created By</h6>
                                            </th>
                                            <th width="">
                                                <h6 class="fs-3 fw-semibold mb-0">Updated By</h6>
                                            </th>
                                        @endif
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
                    Are you sure you want to delete this student fees data?
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
                pageLength: 30,
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.students.student_fees.data', ['student' => $student->id]) !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.student_id = $('#student_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    @if (auth()->check() && auth()->user()->student_fees_edit === 'YES')
                        {
                            data: 'action',
                            name: 'student_fees.id',
                            orderable: false,
                            searchable: false
                        },
                    @elseif (auth()->check() && $isAdminOrSuperAdmin)
                        {
                            data: 'action',
                            name: 'student_fees.id',
                            orderable: false,
                            searchable: false
                        },
                    @endif
                    { data: 'pdf', name: 'pdf', orderable: false, searchable: false },
                    {
                        data: 'status',
                        name: 'student_fees.status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'student_fees.date',
                        orderable: false
                    },
                    {
                        data: 'receive_date',
                        name: 'student_fees.receive_date',
                        orderable: false
                    },
                    {
                        data: 'currency',
                        name: 'student_fees.currency',
                        orderable: false
                    },
                    {
                        data: 'monthly_fees',
                        name: 'student_fees.monthly_fees',
                        orderable: false
                    },
                    {
                        data: 'total_amount_paid',
                        name: 'student_fees.total_amount_paid',
                        orderable: false
                    },
                    @if ($isAdminOrSuperAdmin)
                        {
                            data: 'created_by',
                            name: 'student_fees.created_by',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'updated_by',
                            name: 'student_fees.updated_by',
                            orderable: false,
                            searchable: false
                        },
                    @endif

                ],
                order: [],
                columnDefs: [{
                    targets: [0, 2],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            $('#student_id').change(function(e) {
                e.preventDefault();
                dataTable.draw();
            });
        });

        $(document).on('change', '.student_fee-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');

            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.students.student_fees.change.status', ['student' => $student->routekey]) }}",
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
            var studentId = $(this).data('student-id');
            var studentFeeId = $(this).data('student_fee-id');
            var deleteUrl = '/admin/students/' + studentId + '/student_fees/' + studentFeeId;
            $('#confirmDelete').data('delete-url', deleteUrl);
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on('click', '#confirmDelete', function() {
            var deleteUrl = $(this).data('delete-url');
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(data) {
                    $('#datatable').DataTable().draw(false);
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
