@extends('layouts.admin')
@section('title') Coverup Class @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="row">
                        <div class="col-6 d-flex justify-content-start">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Coverup Class</h5>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="batch_id" id="batch_id" class="select2 form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example">
                                <option value="">Select Batch</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="old_coach" id="old_coach" class="select2 form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example">
                                <option value="">Select Coach</option>
                                @foreach ($coaches as $coach)
                                    <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                        {{ $coach->user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2 d-flex justify-content-end">
                            <select name="new_coach" id="new_coach" class="select2 form-select form-select-sm pure-white"
                                aria-label=".form-select-sm example">
                                <option value="">Select Coach</option>
                                @foreach ($coaches as $coach)
                                    <option value="{{ $coach->id }}">{{ $coach->user->first_name }}
                                        {{ $coach->user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-8"></div>
                        <div class="col-md-2 d-flex justify-content-end">
                            <input type="date" class="form-control  pure-white " placeholder="Start Date"
                                id="from_date" name="from_date" value="" />
                        </div>
                        <div class="col-md-2 d-flex justify-content-end">
                            <input type="date" class="form-control  pure-white " placeholder="End Date"
                                id="to_date" name="to_date" value="" />
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                    <table class="table border table-bordered table-sm text-nowrap mb-0 align-middle" id="datatable">
                        <thead class="text-dark fs-3">
                            <tr>
                                <th width="1%">
                                    <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                </th>
                                <th width="1%">
                                    <h6 class="fs-3 fw-semibold mb-0">Change <br>Coach</h6>
                                </th>
                                <th width="5%">
                                    <h6 class="fs-3 fw-semibold mb-0">Date</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Batch</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Batch Schedule</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">Coach</h6>
                                </th>
                                <th width="10%">
                                    <h6 class="fs-3 fw-semibold mb-0">New Coach</h6>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Change Coach Modal -->
<div class="modal fade" id="changeCoachModel" tabindex="-1" aria-labelledby="changeCoachModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeCoachModelLabel">Change Coach</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"  id="change_coach_submit" >Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                url: '{!! route('admin.coverupclasses.data') !!}',
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                    d.batch_id = $('#batch_id').val();
                    d.old_coach = $('#old_coach').val();
                    d.new_coach = $('#new_coach').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'change_coach',name: 'coverupclasses.id', orderable: false,searchable: false},
                {data: 'date',name: 'coverupclasses.id', orderable: false,searchable: false},
                {data: 'batch',name: 'coverupclasses.id', orderable: false,searchable: false},
                {data: 'batchSchedule',name: 'coverupclasses.id', orderable: false,searchable: false},
                {data: 'old_coach',name: 'coverupclasses.id', orderable: false,searchable: false},
                {data: 'new_coach',name: 'coverupclasses.id', orderable: false,searchable: false},
            ],
            order: [],
            columnDefs: [
                { targets: [0,1], className: "text-center"},
            ],
        });
        $(".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel").addClass("btn btn-primary mr-1");

        $('#batch_id').change(function() {
            dataTable.draw();
        });

        $('#old_coach').change(function() {
            dataTable.draw();
        });

        $('#new_coach').change(function() {
            dataTable.draw();
        });

        $('#from_date').change(function() {
            dataTable.draw();
        });

        $('#to_date').change(function() {
            dataTable.draw();
        });

        // Change Coach
        $(document).on('click', '.change_coach', function() {
            // $('#changeCoachModel').modal('show');
            //call ajax
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.coverupclasses.change_coach.get.coach') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    coverup_class_id: id
                },
                success: function(response) {
                    $('#changeCoachModel .modal-body').html(response);
                    $('#changeCoachModel').modal('show');
                }
            });
        });
    });

    $(document).on('click', '#change_coach_submit', function() {
        var id = $('#coverup_class_id').val();
        var new_assign_coach_id = $('#new_assign_coach_id').val();
        $.ajax({
            url: "{{ route('admin.coverupclasses.change_coach') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                coverupclass_id: id,
                new_coach_id: new_assign_coach_id
            },
            success: function(response) {
                if (response.status == 'success') {
                    $('#changeCoachModel').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    });
                    $('#datatable').DataTable().ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            }
        });
    });
</script>
@endsection
