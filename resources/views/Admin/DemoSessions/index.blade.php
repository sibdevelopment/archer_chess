@extends('layouts.admin')
@section('title')
    Demo Sessions
@endsection
@section('content')
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
                <a href="/admin/demoleads" class="btn btn-primary mb-3">
                    <span>
                        <i class="ti ti-chevron-left mr-4"></i>
                    </span>Back</a>
                <a href="/admin/demoleads/{{ $demolead->id }}/demo_sessions"
                    class="card w-100 position-relative overflow-hidden mb-1 text-white bg-info">
                    <div class="p-3 fw-semibold">
                        Student : {{ $demolead->first_name }} {{ $demolead->last_name }}
                    </div>
                </a>
                <div class="card w-100 position-relative overflow-hidden" style="margin-bottom:2%;">
                    <div class="card-header px-4 py-3 border-bottom">
                        @if (!empty($demolead->age))
                            <p class="text-black fw-semibold">Age : {{ $demolead->age }}</p>
                        @endif
                        @if (!empty($demolead->mobile))
                            <p class="text-black fw-semibold">Mobile: {{ $demolead->mobile }}</p>
                        @endif
                        @if (!empty($demolead->address))
                            <p class="text-black fw-semibold">Address: {{ $demolead->address }}</p>
                        @endif
                        @if (!empty($demolead->remark))
                            <p class="text-black fw-semibold">Remark: {{ $demolead->remark }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm text-black">Demo Sessions</h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.demoleads.demo_sessions.create', ['demolead' => $demolead->id]) }}"
                                    class="btn bg-info text-white fw-semibold">
                                    Create Demo Sessions
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
                                        {{-- <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                        </th> --}}
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Student Name</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Time</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Start Url</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Join Url</h6>
                                        </th>
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">Recording</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Slot</h6>
                                        </th>
                                        <th width="">
                                            <h6 class="fs-3 fw-semibold mb-0">Level</h6>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

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
                    url: '{!! route('admin.demoleads.demo_sessions.data', ['demolead' => $demolead->id]) !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                        d.demolead_id = $('#demolead_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, 
                    {
                        data: 'status',
                        name: 'demosessions.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'demolead_id',
                        name: 'demosessions.demolead_id',
                        orderable: false
                    },
                    {
                        data: 'date',
                        name: 'demosessions.date',
                        orderable: false
                    },
                    {
                        data: 'time',
                        name: 'demosessions.time',
                        orderable: false
                    },
                    {
                        data: 'start_url',
                        name: 'demosessions.start_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'join_url',
                        name: 'demosessions.join_url',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'recording',
                        name: 'recording',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'coach_id',
                        name: 'demosessions.coach_id',
                        orderable: false
                    },
                    {
                        data: 'slot',
                        name: 'demosessions.slot',
                        orderable: false
                    },
                    {
                        data: 'level',
                        name: 'demosessions.level',
                        orderable: false
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 2, 6, 7, 8],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

            $('#demolead_id').change(function(e) {
                e.preventDefault();
                dataTable.draw();
            });
        });

        $(document).on('change', '.demosession-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.demoleads.demo_sessions.change.status', ['demolead' => $demolead->routekey]) }}",
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
    </script>
@endsection
