<div class="table-responsive">
    <table class="table border table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Action</th>
                <th>Task ID</th>
                <th>Created By</th>
                <th>Created On</th>
                <th>Related Type</th>
                <th>Name / Student</th>
                <th>Country</th>
                <th>{{ $mode === 'handover' ? 'Last Action' : 'What Is Required?' }}</th>
                @if ($mode === 'handover')
                    <th>Exact Next Action</th>
                @endif
                <th>Priority</th>
                <th>Status</th>
                <th>Current Owner</th>
                <th>Final Result / Cancel Reason</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assignments as $assignment)
                <tr>
                    <td>{{ $loop->iteration + (($assignments->currentPage() - 1) * $assignments->perPage()) }}</td>
                    <td>
                        <a class="badge bg-info" href="{{ route('admin.work-assignments.show', $assignment->route_key) }}"><i class="fa fa-eye"></i></a>
                    </td>
                    <td>WA-{{ $assignment->id }}</td>
                    <td>{{ optional($assignment->creator)->full_name }}</td>
                    <td>{{ optional($assignment->created_at)->format('d-M-Y h:i A') }}</td>
                    <td>{{ $assignment->related_type }}</td>
                    <td>{{ $assignment->related_name }}</td>
                    <td>{{ $assignment->country }}</td>
                    <td>{{ $mode === 'handover' ? $assignment->last_action : $assignment->requirement }}</td>
                    @if ($mode === 'handover')
                        <td>{{ $assignment->next_action }}</td>
                    @endif
                    <td><span class="badge bg-info">{{ $assignment->priority }}</span></td>
                    <td><span class="badge bg-{{ $assignment->status === 'DONE' ? 'success' : ($assignment->status === 'CANCELLED' ? 'danger' : 'warning') }}">{{ $assignment->status }}</span></td>
                    <td>{{ optional($assignment->currentOwner)->full_name }}</td>
                    <td>{{ $assignment->final_reason }}</td>
                    <td>{{ optional($assignment->updated_at)->format('d-M-Y h:i A') }}</td>
                </tr>
            @empty
                <tr><td colspan="{{ $mode === 'handover' ? 15 : 14 }}" class="text-center">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $assignments->links() }}
