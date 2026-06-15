@extends('layouts.admin')
@section('title') Feedbacks @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Feedbacks</h5>
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
                                    <h6 class="fs-3 fw-semibold mb-0">Name</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                </th>
                                <th>
                                    <h6 class="fs-3 fw-semibold mb-0">Feedback</h6>
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
            scrollX:false,
            pageLength: 100,
            ajax: {
                url: '{!! route('admin.feedbacks.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    // d.date = $('#date').val();
                }
            },

            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'enquiries.id', orderable: false, searchable: false},
				{data: 'full_name',name: 'enquiries.full_name', orderable: false, searchable: true},
				{data: 'coach',name: 'coach', orderable: false, searchable: false},
                {data: 'feedback',name: 'enquiries.feedback' , orderable: false, searchable: true},
            ],
            order: [],
            columnDefs: [
                { targets: [0,1], className: "text-center"},
            ],
        });
        $(
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");

        // $('#date').on('change', function () {
        //     dataTable.ajax.reload(null, false);
        // });
    });
</script>
@endsection
