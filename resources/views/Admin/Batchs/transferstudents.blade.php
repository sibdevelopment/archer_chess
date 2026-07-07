<form method="POST" action="{{ route('admin.batchs.transfer.student.redirect', ['batch' => $batch->id]) }}">
    @csrf
    <div class="modal-header border-bottom">
        <h4 class="text-dark">Transfer Batch Students</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <p class="mb-1"><strong>Source Batch:</strong> {{ $batch->name }}</p>
            <p class="mb-1"><strong>Country:</strong> {{ implode(', ', $batch->country ?? []) }}</p>
            <p class="mb-0"><strong>Students:</strong> {{ $studentBatches->count() }}</p>
        </div>

        @if ($studentBatches->isEmpty())
            <div class="alert alert-warning mb-0">
                No active students are available in this source batch.
            </div>
        @else
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Level</th>
                            <th>Current Window</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentBatches as $studentBatch)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ optional($studentBatch->student)->first_name }}
                                    {{ optional($studentBatch->student)->last_name }}
                                    @if (optional($studentBatch->student)->student_id)
                                        ({{ optional($studentBatch->student)->student_id }})
                                    @endif
                                </td>
                                <td>{{ optional($studentBatch->level)->name ?? '-' }}</td>
                                <td>
                                    {{ $studentBatch->start_date ? \Carbon\Carbon::parse($studentBatch->start_date)->format('d-M-Y') : '-' }}
                                    -
                                    {{ $studentBatch->end_date ? \Carbon\Carbon::parse($studentBatch->end_date)->format('d-M-Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="control-label col-form-label">Target Batch <sup class="tcul-star-restrict">*</sup></label>
                    <select name="target_batch_id" class="form-control select2" required>
                        <option value="">Select target batch</option>
                        @foreach ($targetBatches as $targetBatch)
                            <option value="{{ $targetBatch->id }}">
                                {{ $targetBatch->name }}
                                ({{ $targetBatch->status }}{{ $targetBatch->is_one_to_one ? ', 1-1' : '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('target_batch_id')
                        <div style="color:red">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Cutoff / Start Date <sup class="tcul-star-restrict">*</sup></label>
                    <input type="date" name="cutoff_date" class="form-control" value="{{ $defaultCutoffDate }}" required>
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">End Date <sup class="tcul-star-restrict">*</sup></label>
                    <input type="date" name="end_date" class="form-control" value="{{ $prefillEndDate ? \Carbon\Carbon::parse($prefillEndDate)->format('Y-m-d') : '' }}" required>
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Remaining Sessions <sup class="tcul-star-restrict">*</sup></label>
                    <input type="number" name="number_of_sessions" class="form-control" value="{{ $remainingSessions }}" min="1" required>
                </div>
            </div>

            @if ($targetBatches->isEmpty())
                <div class="alert alert-warning mt-3 mb-0">
                    No valid country-matched target batch is available for this transfer.
                </div>
            @endif
        @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
        @if ($studentBatches->isNotEmpty() && $targetBatches->isNotEmpty())
            <button type="submit" class="btn btn-primary">Continue</button>
        @endif
    </div>
</form>
