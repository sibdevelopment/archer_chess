@extends('layouts.admin')
@section('title')
    Leave Request
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
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-3 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Leave Request</h5>
                            </div>
                            <div class="col-3">
                                @if (!$isCoach)
                                    <select name="coach" id="coach"
                                        class="select2 form-select form-select-sm pure-white"
                                        aria-label=".form-select-sm example">x
                                        <option value="">Select Coach</option>
                                        @foreach ($coaches as $coach)
                                            <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                                {{ $coach->user->last_name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <input type="date" class="form-control  pure-white " placeholder="Start Date"
                                    id="from_date" name="from_date" value="" />
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <input type="date" class="form-control  pure-white " placeholder="End Date"
                                    id="to_date" name="to_date" value="" />
                            </div>
                            <div class="col-2 d-flex justify-content-end">

                                @php
                                    $user = auth()->user();
                                    $role = $user->getRoleNames()->toArray();
                                @endphp
                                @if (in_array('Coach', $role))
                                    <a href="{{ route('admin.leaverequests.create') }}" class="btn btn-info">
                                        Create
                                        &nbsp;
                                        <i class="ti ti-plus"></i>
                                    </a>
                                @endif
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
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                        </th>
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                        </th>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Timeline</h6>
                                        </th>
                                        <th width="20%">
                                            <h6 class="fs-3 fw-semibold mb-0">Reason</h6>
                                        </th>
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
        <div class="modal-dialog" role="document">
            <form id="statusChangeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="leaverequestId" name="leaverequest_id">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <select class="form-select" id="status" name="status">
                                    <option selected disabled hidden>Select status...</option>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                    <option value="APPROVED">Approved</option>
                                    <option value="REJECTED">Rejected</option>
                                </select>
                                <div id="status-error" style="color:red"></div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <img id="ajax-loader" class="Loader" style="display: none;"
                            src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt="">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal :: -->
    <div class="modal fade text-left" id="confirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="text-dark">Confirm Approval</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="confirmationForm" enctype="multipart/form-data">
                        <p id="modal-heading">By approving this leave request, the following data will be affected:</p>
                        <table class="table table-bordered" id="affectedDataTable">
                            <thead>
                                <tr>
                                    <th>Batch</th>
                                    <th>Total Missed Session</th>
                                    <th>Schedules</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmApprovalButton" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // -------------------------------------------------------------------------------
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
                    url: '{!! route('admin.leaverequests.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.coach = $('#coach').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'leaverequests.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'leaverequests.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'coach_name',
                        name: 'coach_name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'timeline',
                        name: 'leaverequests.id',
                        orderable: false
                    },
                    {
                        data: 'reason',
                        name: 'leaverequests.reason',
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
            $('#from_date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#to_date').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });


        // -------------------------------------------------------------------------------
        $(document).on('click', '.leaverequest-status-switch', function() {
            var id = $(this).data('id');
            var routeKey = $(this).data('routekey');
            $('#leaverequestId').val(id);
            $('#routeKey').val(routeKey);
        });


        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('leaverequest_id', $('#leaverequestId').val());
            formData.append('route_key', $('#routeKey').val());

            if (formData.get('status') === 'APPROVED') {
                $('#statusChangeModal').modal('hide');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.leaverequests.get.affected.data') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Populate affected data table
                        var affectedDataTable = $('#affectedDataTable tbody');
                        affectedDataTable.empty();

                        if (response.affectedData.length === 0) {
                            affectedDataTable.append(
                                '<tr><td colspan="3">No affected data found. You can proceed.</td></tr>'
                                );
                        } else {
                            var arrKey = 0;
                            $.each(response.affectedData, function(key, batch) {

                                // Start building the HTML for schedules table
                                var schedulesHtml =
                                    '<table class="table table-bordered"><thead><tr><th>Weekday</th><th>From Time</th><th>To Time</th><th>Missed Sessions</th><th>Cover By</th></tr></thead><tbody>';

                                $.each(batch.schedules, function(day, schedule) {
                                    schedulesHtml += '<input type="hidden" name="affectedData['+arrKey+'][batch_id]" value="'+ batch.id +'">';
                                    schedulesHtml += '<input type="hidden" name="affectedData['+arrKey+'][schedule_id]" value="'+ schedule.id +'">';

                                    var coachDropdown = '<select class="form-select select2" name="affectedData['+arrKey+'][coach_id]">';
                                    coachDropdown += '<option value="">Select Coach</option>';
                                        $.each(schedule.coaches, function(index, coach) {
                                            var coachFirstName = '';
                                            var coachLastName = '';
                                        if (coach.user) {
                                            var coachFirstName = coach.user.first_name;
                                            var coachLastName = coach.user.last_name;
                                        }

                                            coachDropdown += '<option value="' + coach.id + '">' + coachFirstName +
                                                ' ' + coachLastName + '</option>';
                                        });
                                        coachDropdown += '</select>';

                                    // Append row data for each schedule
                                    schedulesHtml += '<tr>' +
                                        '<td>' + schedule.weekday + '</td>' +
                                        '<td>' + schedule.from_time + '</td>' +
                                        '<td>' + schedule.to_time + '</td>' +
                                        '<td>' + schedule.missedSessions + '</td>' +
                                        '<td>' + coachDropdown + '</td>' +
                                        '</tr>';

                                    arrKey++;
                                });

                                schedulesHtml += '</tbody></table>';

                                // Append each batch's data to the main table
                                affectedDataTable.append('<tr><td>' + batch.name + '</td><td>' +
                                    batch.missedCount + '</td><td>' + schedulesHtml +
                                    '</td></tr>');
                            });
                        }
                        $('#confirmationModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#status-error').text('An error occurred. Please try again.');
                    }
                });
            } else {
                $('#ajax-loader').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('admin.leaverequests.change.status') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#ajax-loader').hide();
                        $('#statusChangeModal').modal('hide');
                        $('#datatable').DataTable().ajax.reload();
                        toastr.success('Status changed successfully');
                    },
                    error: function(xhr) {
                        $('#ajax-loader').hide();
                        if (xhr.status === 403) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            $('#status-error').text('An error occurred. Please try again.');
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            }
        });


        $('#confirmApprovalButton').on('click', function() {
            var isConfirmed = confirm("Are you sure you want to proceed? This action cannot be undone. Please check the data carefully.");
            if (isConfirmed) {
               $('#confirmationForm').submit();
            } else {
                alert("Approval cancelled.");
            }
        });



        $(document).on('submit','#confirmationForm', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('leaverequest_id', $('#leaverequestId').val());
            formData.append('route_key', $('#routeKey').val());
            formData.append('status', $('#status').val());

            $('#ajax-loader').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.leaverequests.change.status') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#ajax-loader').hide();
                    $('#statusChangeModal').modal('hide');
                    $('#confirmationModal').modal('hide');
                    $('#datatable').DataTable().ajax.reload();
                    toastr.success('Status changed successfully');
                },
                error: function(xhr) {
                    $('#ajax-loader').hide();
                    if (xhr.status === 403) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        $('#status-error').text('An error occurred. Please try again.');
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        });


        // -------------------------------------------------------------------------------
    </script>
@endsection
