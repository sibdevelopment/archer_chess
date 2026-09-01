@extends('layouts.admin')
@section('title')
    Employee Leave Requests
@endsection
@section('content')
    @php
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('SuperAdmin');
    @endphp
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-2">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Employee Leave</h5>
                            </div>
                            <div class="col-md-2">
                                @if ($isSuperAdmin)
                                    <select name="employee_id" id="employee_id" class="select2 form-select form-select-sm pure-white">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->user->full_name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-2">
                                @if ($isSuperAdmin)
                                    <select name="role" id="role" class="select2 form-select form-select-sm pure-white">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <select name="leave_status" id="leave_status" class="form-select form-select-sm pure-white">
                                    <option value="">Select Status</option>
                                    <option value="PENDING">Pending</option>
                                    <option value="APPROVED">Approved</option>
                                    <option value="REJECTED">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="leave_type" id="leave_type" class="form-select form-select-sm pure-white">
                                    <option value="">Select Type</option>
                                    <option value="FULL DAY">Full Day</option>
                                    <option value="HALF DAY">Half Day</option>
                                    <option value="SHORT LEAVE">Short Leave</option>
                                    <option value="SICK LEAVE">Sick Leave</option>
                                    <option value="EMERGENCY">Emergency</option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                @can('employeeleaverequests-store')
                                    <a href="{{ route('admin.employeeleaverequests.create') }}" class="btn btn-info">
                                        Create
                                        &nbsp;
                                        <i class="ti ti-plus"></i>
                                    </a>
                                @endcan
                            </div>
                            <div class="col-md-2 offset-md-4">
                                <input type="date" class="form-control form-control-sm pure-white" id="from_date" name="from_date" />
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control form-control-sm pure-white" id="to_date" name="to_date" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Employee</th>
                                        <th>Role</th>
                                        <th>Type</th>
                                        <th>Timeline</th>
                                        <th>Reason</th>
                                        <th>Rejected Reason</th>
                                        <th>Applied On</th>
                                        <th>Approved/Rejected By</th>
                                        <th>Approval Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="statusChangeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="employee_leave_request_id" name="employee_leave_request_id">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option selected disabled hidden>Select status...</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                            </select>
                            <div id="status-error" style="color:red"></div>
                        </div>
                        <div class="mb-3" id="rejectionReasonWrap" style="display:none;">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"></textarea>
                            <div id="rejection_reason-error" style="color:red"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <img id="ajax-loader" class="Loader" style="display: none;" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt="">
                    </div>
                </div>
            </form>
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
                scrollX: true,
                pageLength: 100,
                ajax: {
                    url: '{!! route('admin.employeeleaverequests.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.employee_id = $('#employee_id').val();
                        d.role = $('#role').val();
                        d.status = $('#leave_status').val();
                        d.leave_type = $('#leave_type').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'action', name: 'employee_leave_requests.id', orderable: false, searchable: false},
                    {data: 'status', name: 'employee_leave_requests.status', orderable: false, searchable: false},
                    {data: 'employee_name', name: 'employee_name', orderable: false},
                    {data: 'role_name', name: 'role_name', orderable: false, searchable: false},
                    {data: 'leave_type', name: 'employee_leave_requests.leave_type', orderable: false},
                    {data: 'timeline', name: 'employee_leave_requests.id', orderable: false, searchable: false},
                    {data: 'reason', name: 'employee_leave_requests.reason', orderable: false},
                    {data: 'rejection_reason', name: 'employee_leave_requests.rejection_reason', orderable: false},
                    {data: 'applied_on', name: 'employee_leave_requests.created_at', orderable: false, searchable: false},
                    {data: 'approved_by_name', name: 'approved_by_name', orderable: false, searchable: false},
                    {data: 'approved_at', name: 'employee_leave_requests.approved_at', orderable: false, searchable: false},
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2],
                    className: "text-center"
                }],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");
            $('#employee_id, #role, #leave_status, #leave_type, #from_date, #to_date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });

        $(document).on('click', '.employeeleave-status-switch', function() {
            $('#employee_leave_request_id').val($(this).data('id'));
            $('#status').val('');
            $('#rejection_reason').val('');
            $('#rejectionReasonWrap').hide();
            $('div[id$="-error"]').empty();
        });

        $('#status').on('change', function() {
            $('#rejectionReasonWrap').toggle($(this).val() === 'REJECTED');
        });

        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            $('#ajax-loader').show();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.employeeleaverequests.change.status') }}',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#ajax-loader').hide();
                    $('#statusChangeModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    $('#ajax-loader').hide();
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value[0]);
                        });
                        return;
                    }
                    toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                }
            });
        });
    </script>
@endsection
