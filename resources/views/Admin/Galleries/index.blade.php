@extends('layouts.admin')
@section('title')
    Gallery
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Galleries</h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.galleries.create') }}" class="btn btn-info">
                                    Create
                                    &nbsp;
                                    <i class="ti ti-plus"></i>
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
                                        <th width="5%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                        </th>
                                        <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                        </th>
                                        {{-- <th width="1%">
                                            <h6 class="fs-3 fw-semibold mb-0">Index</h6>
                                        </th> --}}
                                        {{-- <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Images</h6>
                                        </th> --}}
                                        <th width="80%">
                                            <h6 class="fs-3 fw-semibold mb-0">Title</h6>
                                        </th>
                                         <th width="10%">
                                            <h6 class="fs-3 fw-semibold mb-0">Images</h6>
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
        $(function() {
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.galleries.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'galleries.id',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'status',
                        name: 'galleries.status'
                    },
                    // {
                    //     data: 'index',
                    //     name: 'galleries.index'
                    // },
                    {
                        data: 'title',
                        name: 'galleries.title',
                        searchable: true
                    },  
                     {
                        data: 'images',
                        name: 'galleries.images',
                        searchable: false,
                        orderable: false,
                    },


                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    className: "text-center"
                }, ],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");
        });

        $(document).on('change', '.gallery-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.galleries.change.status') }}",
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
