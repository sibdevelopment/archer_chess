@extends('layouts.admin')
@section('title') Personal Follow-Up @endsection
@section('content')
<section>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card w-100">
        <div class="card-header px-4 py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title fw-semibold mb-0">Personal Follow-Up List</h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('admin.personal-follow-ups.create') }}" class="btn btn-info">Create Follow-Up <i class="ti ti-plus"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                @foreach ([['Due Today', $counts['due_today']], ['Upcoming', $counts['upcoming']], ['Overdue', $counts['overdue']], ['Done', $counts['done']]] as $counter)
                    <div class="col-md-3 mb-2">
                        <div class="border rounded p-3 bg-light">
                            <div class="fs-3 text-muted">{{ $counter[0] }}</div>
                            <div class="fs-7 fw-semibold">{{ $counter[1] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <form class="row mb-3" method="GET">
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach (['PENDING', 'DONE', 'CANCELLED'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table border table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Follow-Up Date & Time</th>
                            <th>Related To</th>
                            <th>Name / ERP ID</th>
                            <th>Follow-Up Type</th>
                            <th>What To Do</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Reason / Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($followUps as $followUp)
                            <tr>
                                <td>{{ $loop->iteration + (($followUps->currentPage() - 1) * $followUps->perPage()) }}</td>
                                <td>{{ optional($followUp->follow_up_at)->format('d-M-Y h:i A') }}</td>
                                <td>{{ $followUp->related_to }}</td>
                                <td>{{ $followUp->related_name }}</td>
                                <td>{{ $followUp->follow_up_type }}</td>
                                <td>{{ $followUp->what_to_do }}</td>
                                <td><span class="badge bg-info">{{ $followUp->priority }}</span></td>
                                <td><span class="badge bg-{{ $followUp->status === 'DONE' ? 'success' : ($followUp->status === 'CANCELLED' ? 'danger' : 'warning') }}">{{ $followUp->status }}</span></td>
                                <td>{{ $followUp->reason_note }}</td>
                                <td><a href="{{ route('admin.personal-follow-ups.edit', $followUp->route_key) }}" class="badge bg-warning"><i class="fa fa-edit"></i></a></td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center">No follow-ups found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $followUps->links() }}
        </div>
    </div>
</section>
@endsection
