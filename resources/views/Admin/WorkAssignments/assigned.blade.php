@extends('layouts.admin')
@section('title') Assigned Work @endsection
@section('content')
@if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
<div class="card w-100">
    <div class="card-header px-4 py-3 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-title fw-semibold mb-0">Assigned Work</h5>
                <small>PENDING = action required | DONE = completed | CANCEL = no longer required | REASSIGN = another employee should handle it.</small>
            </div>
            <div class="col-md-6">
                <form method="GET" class="text-end">
                    <select name="type" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="HANDOVER" @selected(request('type') === 'HANDOVER')>Shift Handover</option>
                        <option value="TASK" @selected(request('type') === 'TASK')>Task / Issue</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table border table-bordered table-sm align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Actions</th>
                        <th>Task ID</th>
                        <th>Type</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Related Type</th>
                        <th>Name / Student</th>
                        <th>Country</th>
                        <th>What To Do</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Done / Cancel Reason</th>
                        <th>Action Date & Time</th>
                        <th>Assignee Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td>{{ $loop->iteration + (($assignments->currentPage() - 1) * $assignments->perPage()) }}</td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-info" href="{{ route('admin.work-assignments.show', $assignment->route_key) }}"><i class="fa fa-eye"></i></a>
                                @include('Admin.WorkAssignments._status_actions')
                            </td>
                            <td>WA-{{ $assignment->id }}</td>
                            <td>{{ $assignment->type }}</td>
                            <td>{{ optional($assignment->creator)->full_name }}</td>
                            <td>{{ optional($assignment->created_at)->format('d-M-Y h:i A') }}</td>
                            <td>{{ $assignment->related_type }}</td>
                            <td>{{ $assignment->related_name }}</td>
                            <td>{{ $assignment->country }}</td>
                            <td>{{ $assignment->type === 'HANDOVER' ? $assignment->next_action : $assignment->requirement }}</td>
                            <td><span class="badge bg-info">{{ $assignment->priority }}</span></td>
                            <td><span class="badge bg-{{ $assignment->status === 'DONE' ? 'success' : ($assignment->status === 'CANCELLED' ? 'danger' : 'warning') }}">{{ $assignment->status }}</span></td>
                            <td>{{ $assignment->final_reason }}</td>
                            <td>{{ optional($assignment->action_at)->format('d-M-Y h:i A') }}</td>
                            <td>{{ $assignment->assignee_note }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="15" class="text-center">No assigned work found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assignments->links() }}
    </div>
</div>
@endsection
