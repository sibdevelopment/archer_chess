@if (in_array($assignment->status, ['PENDING', 'REASSIGNED']) && ($assignment->current_owner_id === auth()->id() || auth()->user()->hasRole('SuperAdmin')))
    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#doneModal{{ $assignment->id }}">Done</button>
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $assignment->id }}">Cancel</button>
    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reassignModal{{ $assignment->id }}">Reassign</button>

    <div class="modal fade" id="doneModal{{ $assignment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.work-assignments.status', $assignment->route_key) }}">
                @csrf
                <input type="hidden" name="status" value="DONE">
                <div class="modal-header"><h5 class="modal-title">Mark Done</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Assignee Notes</label>
                    <textarea name="assignee_note" class="form-control" rows="3"></textarea>
                </div>
                <div class="modal-footer"><button class="btn btn-success">Save</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="cancelModal{{ $assignment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.work-assignments.status', $assignment->route_key) }}">
                @csrf
                <input type="hidden" name="status" value="CANCELLED">
                <div class="modal-header"><h5 class="modal-title">Cancel Work</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Cancel Reason <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="final_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer"><button class="btn btn-danger">Cancel Work</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="reassignModal{{ $assignment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.work-assignments.reassign', $assignment->route_key) }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Reassign Work</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Reassign To <sup class="tcul-star-restrict">*</sup></label>
                    <select name="reassign_to" class="form-select" required>
                        <option value="">Select Employee</option>
                        @foreach (($assigneesByType[$assignment->type] ?? collect()) as $employeeUser)
                            <option value="{{ $employeeUser->id }}">{{ $employeeUser->full_name }} - {{ $employeeUser->getRoleNames()->implode(', ') }}</option>
                        @endforeach
                    </select>
                    <label class="form-label mt-3">Reason / Note <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="note" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer"><button class="btn btn-warning">Reassign</button></div>
            </form>
        </div>
    </div>
@endif
