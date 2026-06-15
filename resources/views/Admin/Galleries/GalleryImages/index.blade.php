@extends('layouts.admin')
@section('title')
    Gallery Images
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-3">
                <div class="col-md-12 col-lg-12">
                    <div class="card rounded-3 card-hover">
                        <a href="{{ route('admin.galleries.index', ['gallery' => $gallery]) }}" class="stretched-link"></a>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="flex-shrink-0"><i class="ti ti-photo text-warning display-6"></i></span>
                                <div class="ms-4">
                                    <h4 class="card-title text-dark"> {{ $gallery->title }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center justify-content-start gap-3">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Gallery Images</h5>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <a href="{{ route('admin.galleries.index') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center me-2">
                                    <i class="ti ti-arrow-left me-1"></i> Back
                                </a>
                                <a href="{{ route('admin.galleries.gallery_images.create', ['gallery' => $gallery]) }}"
                                    class="btn btn-info">
                                    Create &nbsp; <i class="ti ti-plus"></i>
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
                                        <th width="1%">
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
                                        <th width="50%">
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
                    url: '{!! route('admin.galleries.galleryImages.data', ['gallery' => $gallery]) !!}',
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
                        name: 'galleryImages.id',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'status',
                        name: 'galleryImages.status',
                        orderable: false,
                        searchable: false,
                    },
                    // {
                    //     data: 'index',
                    //     name: 'galleryImages.index'
                    // },
                    {
                        data: 'image',
                        name: 'galleryImages.image',
                        render: function(data, type, full, meta) {
                            var imageUrl = '{{ asset(Storage::url(':filename')) }}';
                            return '<img src="' + imageUrl.replace(':filename', data) +
                                '" alt="Slider Photo" class="img-fluid" style="max-width: 80px; max-height: 80px;">';
                        },
                        orderable: false,
                        searchable: false,
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

        $(document).on('change', '.galleryImages-status-switch', function(e) {
            e.preventDefault();
            var routeKey = $(this).data('routekey');
            var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
            $.ajax({
                url: "{{ route('admin.galleries.galleryImages.change.status', ['gallery' => $gallery]) }}",
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
