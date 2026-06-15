@extends('layouts.admin')
@section('title')
    Payment Levels
@endsection
@section('content')
    <style>
        #datatable tbody tr td {
            color: black !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-7 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Payment Levels</h5>
                            </div>
                            <div class="col-2">
                                <select class="form-control select2" name="level_id" id="level_id"
                                    {{ isset($paymentlevel) ? 'disabled' : '' }}>
                                    <option value="">Select a level</option>
                                    @foreach ($level_ids as $level_id)
                                        <option value="{{ $level_id->id }}"
                                            {{ isset($paymentlevel) && $paymentlevel->level_id == $level_id->id ? 'selected' : '' }}>
                                            {{ $level_id->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3 d-flex justify-content-end">
                                <a href="{{ route('admin.paymentlevels.create') }}" class="btn btn-info">
                                    Create Payment Level &nbsp; <i class="ti ti-plus"></i>
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
                                        <th width="5%">#</th>
                                        <th width="5%">Edit</th>
                                        <th width="5%">Status</th>
                                        <th >Name</th>
                                        <th width="10%">Level</th>
                                        <th width="5%">USA</th>
                                        <th width="5%">CAN</th>
                                        <th width="5%">AUS</th>
                                        <th width="5%">NZ</th>
                                        <th width="5%">IND</th>
                                        <th width="5%">UAE</th>
                                        <th width="5%">UK</th>
                                        <th width="5%">QAT</th>
                                        <th width="5%">SGP</th>
                                        <th width="5%">EU</th>
                                        <th width="5%">OMN</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        $(function() {
            const dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: {
                    url: '{!! route('admin.paymentlevels.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [
                    {
                        data: 'sequence',
                        name: 'sequence',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'paymentlevels.id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'paymentlevels.id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name',
                        orderable: false
                    },
                    {
                        data: 'level_id',
                        name: 'level_id',
                        orderable: false
                    },
                    {
                        data: 'usa_fees',
                        name: 'usa_fees',
                        orderable: false
                    },
                    {
                        data: 'canada_fees',
                        name: 'canada_fees',
                        orderable: false
                    },
                    {
                        data: 'australia_fees',
                        name: 'australia_fees',
                        orderable: false
                    },
                    {
                        data: 'newzealand_fees',
                        name: 'newzealand_fees',
                        orderable: false
                    },
                    {
                        data: 'india_fees',
                        name: 'india_fees',
                        orderable: false
                    },
                    {
                        data: 'uae_fees',
                        name: 'uae_fees',
                        orderable: false
                    },
                    {
                        data: 'uk_fees',
                        name: 'uk_fees',
                        orderable: false
                    },
                    {
                        data: 'qatar_fees',
                        name: 'qatar_fees',
                        orderable: false
                    },
                    {
                        data: 'singapore_fees',
                        name: 'singapore_fees',
                        orderable: false
                    },
                    {
                        data: 'european_union_fees',
                        name: 'european_union_fees',
                        orderable: false
                    } ,
                    {
                        data: 'oman_fees',
                        name: 'oman_fees',
                        orderable: false
                    }
                ],
                columnDefs: [{
                    targets: [0, 2],
                    className: "text-center"
                }, ],
                order: [],
            });

            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass(
                "btn btn-primary mr-1"
            );

            $('#level_id').on('change', function() {
                dataTable.ajax.reload(null, false);
            });
        });

        $(document).on('change', '.paymentlevel-status-switch', function(e) {
            e.preventDefault();
            const routeKey = $(this).data('routekey');
            const status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                url: "{{ route('admin.paymentlevels.change.status') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    route_key: routeKey,
                    status: status
                },
                success: function(data) {
                    if (data.status === 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        $('#datatable').DataTable().draw();
                    } else {
                        toastr.error(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Something went wrong: ' + error, '', {
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
