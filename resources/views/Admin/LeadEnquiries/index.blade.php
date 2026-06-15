@extends('layouts.admin')
@section('title')
    Lead Enquiries
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
                            <div class="col-2 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Lead Enquiries</h5>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <div class="input-group">
                                    <input name="date" id="date" type="text" class="form-control daterange"
                                        placeholder="Select a lead date range" />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <div class="input-group">
                                    <input name="demo_date" id="demo_date" type="text" class="form-control daterange"
                                        placeholder="Select a demo date range" />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <select name="country" id="country" class=" form-select form-select-sm pure-white"
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
                                <select name="status" id="status" class="form-select form-select-sm   pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="ACTIVE ">ACTIVE</option>
                                    <option value="CONVERTED ">CONVERTED</option>
                                    <option value="REJECTED ">REJECTED</option>
                                    <option value=" ">All Status</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-10 text-end"></div>
                            <div class="col-2 text-end">
                                <select name="is_hide" id="is_hide" class="form-select form-select-sm   pure-white"
                                    aria-label=".form-select-sm example">
                                    <option value="0">Active</option>
                                    <option value="1 ">Deleted</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm mb-0 align-middle" id="datatable">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Actions</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Lead : Date | Time</h6>
                                        </th>
                                        <th width="15% !important">
                                            <h6 class="fs-3 fw-semibold mb-0"> Name</h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0"> Mobile</h6>
                                        </th>
                                        <th width="105% !important">
                                            <h6 class="fs-3 fw-semibold mb-0"> Country</h6>
                                        </th>
                                        <th width="0% !important">
                                            <h6 class="fs-3 fw-semibold mb-0">Age</h6>
                                        </th>
                                        <th width="0% !important">
                                            <h6 class="fs-3 fw-semibold mb-0">Language Preference</h6>
                                        </th>
                                        {{-- <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Utm Source </h6>
                                        </th>
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Utm Medium </h6>
                                        </th> --}}
                                        <th>
                                            <h6 class="fs-3 fw-semibold mb-0">Remark </h6>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="convertModalLabel">Convert to Demo Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="convertForm">
                    <div class="modal-body">
                        <div id="convertFormBody" class="container-fluid">
                            <!-- form partial loads here -->
                            <div class="text-center py-5" id="convertLoading">Loading...</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            Save & Convert
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Status Change Modal :: -->
    <div class="modal fade text-left" id="statusChangeModal" tabindex="-1" role="dialog"
        aria-labelledby="statusChangeModalLabel" aria-hidden="true" style="z-index: 9999 !important;">
        <div class="modal-dialog modal-lg" role="document">
            <form id="statusChangeForm" action="{{ route('admin.leadenquiries.change.status') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="text-dark">Change Status</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="leadenquiryId" name="leadenquiry_id">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <input type="hidden" id="routeKey" name="route_key">
                                <select class="form-select" id="status" name="status">
                                    <option selected disabled hidden>select status ...</option>
                                    <option value="CONVERTED">CONVERTED</option>
                                    <option value="REJECTED">REJECTED</option>

                                    <option value="ACTIVE">ACTIVE</option>
                                    <option value="INACTIVE">INACTIVE</option>
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
                    Are you sure you want to delete this Lead Enquiry data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="remarkForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="remarkModalLabel">Reject Lead</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="remarkInput" class="form-label">Please enter a remark:</label>
                            <textarea class="form-control" id="remarkInput" name="remark" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="remarkUrl" />
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Reject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script type="text/javascript">
        $(".daterange").daterangepicker();

        $(document).ready(function() {
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('#date').val('');
            $('#demo_date').val('');
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
                    url: '{!! route('admin.leadenquiries.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.status = $('#status').val();
                        d.date = $('#date').val();
                        d.demo_date = $('#demo_date').val();
                        d.country = $('#country').val();
                        d.is_hide = $('#is_hide').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'demoleadenquiries.id',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'demoleadenquiries.status',
                        orderable: false,
                        searchable: false
                    },
                    //{ data: 'email_verified', name: 'demoleadenquiries.email_verified', searchable: false, orderable: false },
                    {
                        data: 'datetime',
                        name: 'demoleadenquiries.created_at',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'full_name',
                        name: 'demoleadenquiries.kids_first_name',
                        render: function(data, type, row) {
                            return '<img src="/backend/dist/images/svgs/icon-account.svg" width="15" height="15" class="" alt="" /> &nbsp; ' +
                                data;
                        }
                    },
                    {
                        data: 'mobile',
                        name: 'demoleadenquiries.mobile',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'country',
                        name: 'demoleadenquiries.country',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'age',
                        name: 'demoleadenquiries.age',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'language_preference',
                        name: 'demoleadenquiries.language_preference',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'remark',
                        name: 'demoleadenquiries.remark',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],

            });


            $(document).on('click', '.convert-btn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href'); // existing href like .../convert/{id}
                // Extract id safely from href (last segment)
                var id = url.split('/').pop();

                $('#convertFormBody').html('<div class="text-center py-5">Loading...</div>');
                $('#convertModal').modal('show');

                // 1) Load form
                $.get("{{ route('admin.leadenquiries.convert.form', ':id') }}".replace(':id', id))
                    .done(function(resp) {
                        if (resp.success) {
                            $('#convertFormBody').html(resp.html);
                            // stash the POST target on the form element
                            $('#convertForm').data('save-url',
                                "{{ route('admin.leadenquiries.convert.store', ':id') }}".replace(
                                    ':id', id));
                        } else {
                            toastr.error('Failed to load form.');
                        }
                    })
                    .fail(function() {
                        toastr.error('Failed to load form.');
                    });
            });

            // 2) Submit form
            $('#convertForm').on('submit', function(e) {
                // error cleanup
                $('#convertFormBody').find('.text-danger.small').text('');

                e.preventDefault();
                var postUrl = $('#convertForm').data('save-url');
                var $btn = $(this).find('button[type=submit]');
                $btn.prop('disabled', true);

                // collect fields from the injected partial
                var payload = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    first_name: $('input[name="first_name"]').val(),
                    email: $('input[name="email"]').val(),
                    age: $('input[name="age"]').val(),
                    mobile: $('input[name="mobile"]').val(),
                    city: $('input[name="city"]').val(),
                    country: $('select[name="convert_country"]').val(),
                    kids_time_zone: $('select[name="kids_time_zone"]').val(),
                    date: $('input[name="ist_date"]').val(),
                    time: $('input[name="time"]').val(),
                    kids_date: $('input[name="kids_date"]').val(),
                    kids_time: $('input[name="kids_time"]').val(),
                    remark: $('input[name="remark"]').val()
                };

                var loadingToast = toastr.info('Saving & converting...', {
                    timeOut: 0,
                    extendedTimeOut: 0
                });

                $.post(postUrl, payload)
                    .done(function(resp) {
                        toastr.clear(loadingToast);
                        if (resp.success) {
                            $('#convertModal').modal('hide');
                            toastr.success(resp.message || 'Converted!');
                            $('#datatable').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(resp.message || 'Save failed.');
                        }
                    })
                    .fail(function(xhr) {
                        toastr.clear(loadingToast);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // show first error near fields
                            $.each(xhr.responseJSON.errors, function(k, v) {
                                $('#' + k + '-error').text(v[0]);
                            });
                            toastr.error('Please fix the highlighted errors.');
                        } else {
                            toastr.error('An error occurred.');
                        }
                    })
                    .always(function() {
                        $btn.prop('disabled', false);
                    });
            });


            $(document).on('click', '.reject-btn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#remarkUrl').val(url);
                $('#remarkInput').val('');
                $('#remarkModal').modal('show');
            });

            $('#remarkForm').on('submit', function(e) {
                e.preventDefault();
                var url = $('#remarkUrl').val();
                var remark = $('#remarkInput').val();

                var loadingToast = toastr.info('Processing your request...', {
                    timeOut: 0,
                    extendedTimeOut: 0
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        remark: remark,
                        _token: $('meta[name="csrf-token"]').attr('content') // If using Laravel
                    },
                    success: function(response) {
                        toastr.clear(loadingToast);
                        $('#remarkModal').modal('hide');
                        if (response.success) {
                            dataTable.ajax.reload();
                            toastr.success(response.message);
                            setTimeout(function() {
                                toastr.success('Success! The lead has been rejected.');
                            }, 500);
                        } else {
                            toastr.error(response.message || 'Rejection failed.');
                        }
                    },
                    error: function() {
                        toastr.clear(loadingToast);
                        toastr.error('An error occurred.');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var enquiryId = $(this).data('enquiry-id');
                $('#confirmDelete').data('enquiry-id', enquiryId);
                $('#deleteConfirmationModal').modal('show');
            });

            $(document).on('click', '#confirmDelete', function() {
                var enquiryId = $(this).data('enquiry-id');
                var url = "{{ route('admin.leadenquiries.destroy', '') }}" + '/' + enquiryId;
                var loadingToast = toastr.info('Processing your request...', {
                    timeOut: 0,
                    extendedTimeOut: 0
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        _method: 'DELETE',
                        enquiry_id: enquiryId
                    },
                    success: function(response) {
                        toastr.clear(loadingToast);
                        if (response.success) {
                            dataTable.ajax.reload();
                            $('#deleteConfirmationModal').modal('hide');
                            toastr.success(response.message);
                        } else {
                            toastr.error('Deletion failed.');
                        }
                    },
                    error: function() {
                        toastr.clear(loadingToast);
                        $('#deleteConfirmationModal').modal('hide');
                        toastr.error('An error occurred.');
                    }
                });
            });

            // Add Bootstrap styling to DataTable export buttons
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel")
                .addClass("btn btn-primary mr-1");

            // Reload datatable whenever any filter changes
            $('#status, #date, #demo_date, #country, #is_hide').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });
    </script>
@endsection
