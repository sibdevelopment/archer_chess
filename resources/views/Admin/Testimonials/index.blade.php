@extends('layouts.admin')
@section('title')
    Testimonials
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-7 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Testimonials</h5>
                            </div>
                            <div class="col-5 d-flex justify-content-end">
                                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-info">
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
                                        <th width="3%"><h6 class="fs-3 fw-semibold mb-0">#</h6></th>
                                        <th width="3%"><h6 class="fs-3 fw-semibold mb-0">Edit</h6></th>
                                        <th width="3%"><h6 class="fs-3 fw-semibold mb-0">Status</h6></th>
                                        <th><h6 class="fs-3 fw-semibold mb-0">Name</h6></th>
                                        <th><h6 class="fs-3 fw-semibold mb-0">Designation</h6></th>
                                        <th><h6 class="fs-3 fw-semibold mb-0">Rating</h6></th>
                                        <th><h6 class="fs-3 fw-semibold mb-0">Order</h6></th>
                                        <th><h6 class="fs-3 fw-semibold mb-0">Image</h6></th>
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
            var dataTable = $('#datatable').DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollX: false,
                ajax: {
                    url: '{!! route('admin.testimonials.data') !!}',
                    type: 'POST',
                    data: function(d) {
                        d._token = $('meta[name=csrf-token]').attr('content');
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'action', name: 'testimonials.id', searchable: false, orderable: false},
                    {data: 'status', name: 'testimonials.id', searchable: false, orderable: false},
                    {data: 'name', name: 'testimonials.name', orderable: false, searchable: true},
                    {data: 'designation', name: 'testimonials.designation', orderable: false, searchable: true},
                    {data: 'rating', name: 'testimonials.rating', orderable: false, searchable: true},
                    {data: 'display_order', name: 'testimonials.display_order', orderable: true, searchable: false},
                    {
                        data: 'image',
                        name: 'testimonials.image',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (!data) {
                                return '';
                            }
                            var imageUrl = data.startsWith('/') || data.startsWith('http')
                                ? data
                                : '{{ asset(Storage::url(':filename')) }}'.replace(':filename', data);
                            return '<img src="' + imageUrl +
                                '" alt="Testimonial Photo" class="img-fluid" style="width: 70px; height: 70px; object-fit: cover;">';
                        }
                    },
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1, 2, 5, 6, 7],
                    className: "text-center"
                }],
            });
            $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");
        });

        $(document).on('change', '.testimonials-status-switch', function(e) {
            e.preventDefault();

            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';

            $.ajax({
                url: "{{ route('admin.testimonials.change.status') }}",
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
