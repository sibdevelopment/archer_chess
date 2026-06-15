@extends('layouts.admin')
@section('title') Coach Data @endsection
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
            <a href="/admin/coaches" class="btn btn-info mb-3">
                <span>
                    <i class="ti ti-chevron-left mr-4"></i>
                </span>Back
            </a>
            <a href="/admin/coaches/{{ $coach->route_key }}/coach_availabilities"
                class="card w-100 position-relative overflow-hidden mb-1 text-white bg-info">
                <div class="p-3 fw-semibold">
                    @if(!empty($coach->user->first_name))
                    Name : {{ $coach->user->first_name }} {{ $coach->user->last_name }}
                    @endif
                </div>
            </a>
            <div class="card w-100 position-relative overflow-hidden" style="margin-bottom:2%;">
                <div class="card-header px-4 py-3 border-bottom">
                    @if(!empty($coach->user->email))
                    <p class="text-black fw-semibold">Email : {{ $coach->user->email }}</p>
                    @endif
                    @if(!empty($coach->user->mobile))
                    <p class="text-black fw-semibold">Mobile : {{ $coach->user->mobile }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm text-black"> Coach Availability</h5>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            @php
                                $coachAvailability = \App\Models\CoachAvailability::where('coach_id', $coach->id)->first();
                            @endphp
                            @if($coachAvailability)
                                <a href="{{ route('admin.coaches.coach_availabilities.editAll', ['coach' => $coach->route_key]) }}"
                                    class="btn bg-info text-white fw-semibold">
                                    Edit Availability of Coach
                                    &nbsp;
                                    <i class="ti ti-pencil fw-semibold"></i>
                                </a>
                            @else
                                <a href="{{ route('admin.coaches.coach_availabilities.create',['coach' => $coach->route_key]) }}"
                                    class="btn bg-info text-white fw-semibold">
                                    Create Availability of Coach
                                    &nbsp;
                                    <i class="ti ti-plus fw-semibold"></i>
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
                                    <th width="3%">
                                        <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                    </th>
                                    <th width="3%">
                                        <h6 class="fs-3 fw-semibold mb-0">Status</h6>
                                    </th>
                                    <th width="20%">
                                        <h6 class="fs-3 fw-semibold mb-0">Day</h6>
                                    </th>
                                    <th width="">
                                        <h6 class="fs-3 fw-semibold mb-0">Period</h6>
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
    $(function () {
        var dataTable = $('#datatable').DataTable({
            pageLength: 30,
            dom: "Bfrtip",
            buttons: ["copy", "csv", "excel", "pdf", "print"],
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            scrollX: false,
            ajax: {
                url: '{!! route('admin.coaches.coach_availabilities.data', ['coach' => $coach->routekey]) !!}',
                type: 'POST',
                data: function (d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.coach_id = $('#coach_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                {data: 'status',name: 'coach_availabilities.id', orderable: false, searchable: false},
                { data: 'day_of_week', name: 'coach_availabilities.day_of_week', orderable: false },
                { data: 'periods', name: 'coach_availabilities.periods', orderable: false },
            ],
            order: [],
            columnDefs: [
                { targets: [0, 2], className: "text-center" },
            ],
        });
        $(
            ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
        ).addClass("btn btn-primary mr-1");

        $('#coach_id').change(function (e) {
            e.preventDefault();
            dataTable.draw();
        });
    });

    $(document).on('change', '.coachavailability-status-switch', function (e) {
        e.preventDefault();
        var routeKey = $(this).data('routekey');
        var status = $(this).is(':checked') ? 'ACTIVE' : 'INACTIVE';
        $.ajax({
            url: "{{ route('admin.coaches.coach_availabilities.change.status', ['coach' => $coach->routekey]) }}",
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                route_key: routeKey,
                status: status
            },
            success: function (data) {
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
            error: function (data) {
                toastr.error('Something went wrong!');
            }
        });
    });

</script>

@endsection