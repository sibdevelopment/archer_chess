@extends('layouts.admin')
@section('title') Batch Schedules @endsection
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
     .dataTables_filter, .dataTables_length {
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        text-align: right !important;
    }
</style>
<section>
    <div class="row">
        <div class="col-3">
            <a href="/admin/batchs" class="btn btn-primary mb-3">
                <span>
                    <i class="ti ti-chevron-left mr-4"></i>
                </span>Back</a>
            <a href="/admin/batchs/{{ $batch->id }}/batch_schedules" class="card w-100 position-relative overflow-hidden mb-1 text-white bg-info">
                <div class="p-3 fw-semibold">
                    Batch : {{ $batch->name }} 
                </div>
            </a>
            <div class="card w-100 position-relative overflow-hidden" style="margin-bottom:2%;">
                <div class="card-header px-4 py-3 border-bottom">
                    @if(!empty($batch->coach_id))
                        <p class="text-black fw-semibold">Coach : {{ $batch->coach->user->first_name }} {{ $batch->coach->user->last_name }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm text-black">Batch Schedule</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <a href="{{ route('admin.batchs.batch_schedules.create',['batch' => $batch->id]) }}" class="btn bg-info text-white fw-semibold">
                                Create Batch Schedules
                                &nbsp;
                                <i class="ti ti-plus fw-semibold"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">Edit</h6>
                                </th>
                                <th width="3%">
                                    <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                </th>
                                <th width="">
                                    <h6 class="fs-3 fw-semibold mb-0">Day</h6>
                                </th>
                                <th width="">
                                    <h6 class="fs-3 fw-semibold mb-0">From Time </h6>
                                </th>
                                <th width="">
                                    <h6 class="fs-3 fw-semibold mb-0">To Time</h6>
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
            pageLength: 30,
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            scrollX:false,
            ajax: {
                url: '{!! route('admin.batchs.batch_schedules.data', ['batch' => $batch->id]) !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.demolead_id=$('#demolead_id').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action',name: 'batch_schedules.id', orderable: false, searchable: false},
                {data: 'status',name: 'batch_schedules.id', orderable: false, searchable: false},
				{data: 'weekday',name: 'batch_schedules.weekday', orderable: false},
				{data: 'from_time',name: 'batch_schedules.from_time', orderable: false},
				{data: 'to_time',name: 'batch_schedules.to_time', orderable: false},
            ],
            order: [],
            columnDefs: [
                { targets: [0,2], className: "text-center"},
            ],
        });
        $(
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");

        $('#demolead_id').change(function (e) {
            e.preventDefault();
            dataTable.draw();
        });
    });

    $(document).on('change', '.batch_schedule-status-switch', function(e){
        e.preventDefault();
        var routeKey = $(this).data('routekey');
        var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
        $.ajax({
            url: "{{ route('admin.batchs.batch_schedules.change.status', ['batch' => $batch->routekey]) }}",
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                route_key: routeKey,
                status: status
            },
            success: function(data) {
                if(data.status == 'success'){
                    toastr.success(data.message,'',{
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    if($.fn.DataTable.isDataTable("#datatable")){
                        $('#datatable').DataTable().draw();
                    }
                }else{
                    toastr.error(data.message,'',{
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
