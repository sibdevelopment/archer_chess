<form action="{{ route('admin.batchs.reassigned.student.save', ['batch' => $batch->id]) }}" method="post" enctype="multipart/form-data" id="reassignForm">
    @csrf
    <!-- Hidden input for batch ID -->
    <input type="hidden" name="batch_id" value="{{ $batch->id }}">

    <div class="modal-header border-bottom">
        <h5 class="modal-title" id="reassignModalLabel">Reassign Batch</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to reassign this batch?</p>
        <div class="row align-items-center">
            <div class="col-lg-12 order-last">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center gap-3 mb-4">
                        <i class="ti ti-color-swatch text-dark fs-6"></i>
                        <h6 class="fs-4 fw-semibold mb-0">Batch : {{ $batch->name }}</h6>
                    </li>
                    <li class="d-flex align-items-center gap-3 mb-4">
                        <i class="ti ti-user-circle text-dark fs-6"></i>
                        <h6 class="fs-4 fw-semibold mb-0">Coach : {{ $batch->coach->user->first_name }}{{ $batch->coach->user->last_name }} </h6>
                    </li>
                    <li class="d-flex align-items-center gap-3 mb-2">
                        <i class="ti ti-fingerprint text-dark fs-6"></i>
                        <h6 class="fs-4 fw-semibold mb-0 {{ ($batch->status == 'ACTIVE' ? 'text-success' : 'text-danger' ) }}">
                            Status : {{ ucwords(strtolower($batch->status)) }}
                        </h6>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Yes, Reassign</button>
        <img id="ajax-loader" class="Loader" style="width: 6%; display: none !important;" src="https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif" alt="">
    </div>
</form>