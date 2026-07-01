@extends('layouts.admin')
@section('title')
    Batches
@endsection
@section('content')

    @php
        $user = auth()->user();
        $role = $user->getRoleNames()->toArray();
        $coachId = null;
        if (in_array('Coach', $role) && $user->coach) {
            $coachId = $user->coach->id;
        }

        $coach = App\Models\Coach::find($coachId);
        if (!empty($coach->status) && $coach->status == 'INACTIVE') {
            Auth::logout();
            return view('Admin.Auth.login');
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
                    <!-- Filters :: -->
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-3 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Batches</h5>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <select name="weekday" id="weekday" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="">Select Weekday</option>
                                    <option value="MONDAY">MONDAY</option>
                                    <option value="TUESDAY">TUESDAY</option>
                                    <option value="WEDNESDAY">WEDNESDAY</option>
                                    <option value="THURSDAY">THURSDAY</option>
                                    <option value="FRIDAY">FRIDAY</option>
                                    <option value="SATURDAY">SATURDAY</option>
                                    <option value="SUNDAY">SUNDAY</option>
                                </select>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <select name="country" id="country" class="select2 form-select form-select-sm pure-white"
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
                                <select name="is_time" id="is_time" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="">Select Time</option>
                                    <option value="YES">YES</option>
                                    <option value="">NO</option>
                                </select>
                            </div>
                            @if (!$isCoach)
                                <div class="col-3 d-flex justify-content-end">
                                    <a href="{{ route('admin.batchs.create') }}" class="btn btn-info">
                                        Create Batches &nbsp; <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-3">
                                <select name="status" id="status" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example" @if ($isCoach) disabled @endif>
                                    <option value="">Select Status</option>
                                    <option value="ACTIVE" @if ($isCoach) selected @endif>Active</option>
                                    <option value="UPCOMING">Upcoming</option>
                                    <option value="INACTIVE">Inactive</option>
                                    <option value="STANDBY">Standby</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example" @if ($isCoach) disabled @endif>
                                    <option value="">Select Coach</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <select name="student" id="student" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="">Select Student</option>
                                </select>
                            </div>
                            <div class="col-3 ">
                                <select name="level" id="level" class="select2 form-select form-select-sm pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="">Select Level</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Data Table Headers :: -->
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
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Action</h6>
                                            </th>
                                        @endif
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                        </th>
                                        <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Kids Zone Name</h6>
                                        </th>
                                        <!-- <th width="20%">
                                                <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                            </th> -->
                                        @if (!$isCoach)
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Assign</h6>
                                            </th>
                                            {{-- <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Country</h6>
                                            </th> --}}
                                        @endif
                                        @if (!$isCoach)
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Start Url</h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Join Url</h6>
                                            </th>
                                        @endif
                                        <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Completed Session ( Timeline )</h6>
                                        </th>
                                        @if ($isAdminOrSuperAdmin)
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Created By </h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Updated By </h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Created At </h6>
                                            </th>
                                            <th width="5%">
                                                <h6 class="fs-3 fw-semibold mb-0">Updated At </h6>
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ------------------------------------------------------------------- :: -->
    <!-- Status Change Modal :: -->
    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog"
        aria-labelledby="statusChangeModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <form id="statusChangeForm" action="{{ route('admin.batchs.change.status') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="batchId" name="batch_id">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <select class="form-select" id="status" name="status">
                                    <option selected disabled hidden>select status ...</option>
                                    <option value="ACTIVE">Active</option>
                                    <option value="UPCOMING">Upcoming</option>
                                    <option value="INACTIVE">Inactive</option>
                                    <option value="STANDBY">StandBy</option>
                                </select>
                                <div id="status-error" style="color:red"></div>
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
                    Are you sure you want to delete this Batch data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="changeCoachModal" tabindex="-1" role="dialog"
        aria-labelledby="changeCoachModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog" role="document">
            <form id="changeCoachForm" action="" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Coach</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="coach-modal-body">
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

    <script type="text/javascript">
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

        $(document).ready(function() {
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

            // Fetch students
            function fetchStudents(coachId = null) {
                var data = coachId ? {
                    coach_id: coachId
                } : {};
                fetchData('{{ route('admin.batch.get.students') }}', data, function(data) {
                    var studentSelect = $('#student');
                    populateDropdown(studentSelect, data, 'Select Student', function(index, student) {
                        studentSelect.append('<option value="' + student.id + '">' + student
                            .first_name + ' ' + student.last_name + '</option>');
                    });
                });
            }

            // Initial fetch of coaches and students
            fetchCoaches();
            fetchStudents();

            // Fetch students when a coach is selected
            $('#coach').change(function() {
                var coachId = $(this).val();
                fetchStudents(coachId);
            });

            // Fetch coaches when a student is selected
            $('#student').change(function() {
                var studentId = $(this).val();
                fetchCoaches(studentId);
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
                pageLength: 100,
                ajax: {
                    url: '{!! route('admin.batchs.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                        d.coach = $('#coach').val();
                        d.level = $('#level').val();
                        d.student = $('#student').val();
                        d.country = $('#country').val();
                        d.weekday = $('#weekday').val();
                        d.is_time = $('#is_time').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    @if (!$isCoach) // -------------------------------------------------------
                        {
                            data: 'action',
                            name: 'batchs.id',
                            orderable: false,
                            searchable: false
                        },
                    @endif {
                        data: 'status',
                        name: 'batchs.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'batchs.name',
                        orderable: false
                    },
                    {
                        data: 'kids_zone_name',
                        name: 'batchs.kids_zone_name',
                        orderable: false
                    },
                    //{data: 'coach_id',name: 'batchs.coach_id', orderable: false},
                    @if (!$isCoach) // -------------------------------------------------------
                        {
                            data: 'assign',
                            name: 'batchs.id',
                            orderable: false,
                            searchable: false
                        },
                    @endif 
                    @if (!$isCoach) // -------------------------------------------------------
                        
                    {
                        data: 'start_url',
                        name: 'batchs.start_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'join_url',
                        name: 'batchs.join_url',
                        orderable: false,
                        searchable: false
                    },
                        @endif
                    {
                        data: 'timeline',
                        name: 'batchs.timeline',
                        orderable: false,
                        searchable: false
                    },
                    @if ($isAdminOrSuperAdmin) // -------------------------------------------------------
                        {
                            data: 'created_by',
                            name: 'batchs.created_by',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'updated_by',
                            name: 'batchs.updated_by',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'created_at',
                            name: 'batchs.created_at',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'updated_at',
                            name: 'batchs.updated_at',
                            orderable: false,
                            searchable: false
                        },
                    @endif
                ],
                order: [],
                columnDefs: [{
                    @if (!$isCoach)
                        targets: [0, 1, 7, 8],
                    @else
                        targets: [0, 1],
                    @endif
                    className: "text-center"
                }, ],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1");
            $('#status').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#coach').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#level').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#student').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#country').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#weekday').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
            $('#is_time').on('change', function() {
                dataTable.ajax.reload(null, false);
            });

            // Trigger the data table reload on page load if a coach is selected
            if (defaultCoachId) {
                dataTable.ajax.reload();
            }
        });

        // Status Change Modal ::
        // $(document).on('click', '.changebatch-coach-btn', function() { 
        //     console.log('Change Coach Button Clicked');
        //     $('#changeCoachModal').modal('show');
        // });

        $(document).on('click', '.changebatch-coach-btn', function() {
            let batchId = $(this).data('batch-id');

            $.ajax({
                url: "{{ route('admin.get.coaches') }}",
                type: 'GET',
                data: {
                    batch_id: batchId
                },
                success: function(response) {
                    $('#coach-modal-body').html(response);
                    $('#changeCoachModal').modal('show');
                },
                error: function(data) {
                    if (data.status === 403 && data.responseJSON?.error) {
                        toastr.error(data.responseJSON.error, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    } else {
                        toastr.error('Something went wrong. Please try again.', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                }
            });
        });

        $(document).on('click', '.batch-status-switch', function() {
            var id = $(this).data('id');
            var routeKey = $(this).data('routekey');
            var currentStatus = $(this).data('status');
            $('#batchId').val(id);
            $('#routeKey').val(routeKey);
            $('#status').val('');
            $('#status option').show();

            if (currentStatus === 'UPCOMING') {
                $('#status option[value="ACTIVE"]').hide();
                $('#status option[value="STANDBY"]').hide();
                $('#status option[value="UPCOMING"]').hide();
            }
        });

        $('#changeCoachForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('admin.batchs.change.coach') }}',
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#ajax-loader').show();
                },
                complete: function() {
                    $('#ajax-loader').hide();
                },
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                        $('#changeCoachModal').modal('hide');
                        if ($.fn.DataTable.isDataTable("#datatable")) {
                            $('#datatable').DataTable().draw();
                        }
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(xhr.responseJSON.error);
                }
            });
        });

        $('#statusChangeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('batch_id', $('#batchId').val());
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

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var batchId = $(this).data('batch-id');
            $('#confirmDelete').data('batch-id', batchId);
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on('click', '#confirmDelete', function() {
            var batchId = $(this).data('batch-id');
            $.ajax({
                url: "{{ route('admin.batchs.destroy', '') }}" + '/' + batchId,
                type: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    _method: 'DELETE',
                    batch_id: batchId
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
