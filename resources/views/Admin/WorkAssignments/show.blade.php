@extends('layouts.admin')
@section('title') Work Assignment @endsection
@section('content')
<div class="card w-100">
    <div class="card-header"><h5>WA-{{ $assignment->id }} | {{ $assignment->type }}</h5></div>
    <div class="card-body border-top">
        <div class="row">
            <div class="col-md-4"><strong>Status:</strong> {{ $assignment->status }}</div>
            <div class="col-md-4"><strong>Priority:</strong> {{ $assignment->priority }}</div>
            <div class="col-md-4"><strong>Current Owner:</strong> {{ optional($assignment->currentOwner)->full_name }}</div>
            <div class="col-md-4 mt-2"><strong>Created By:</strong> {{ optional($assignment->creator)->full_name }}</div>
            <div class="col-md-4 mt-2"><strong>Assigned To:</strong> {{ optional($assignment->assignedTo)->full_name }}</div>
            <div class="col-md-4 mt-2"><strong>Country:</strong> {{ $assignment->country }}</div>
            <div class="col-md-4 mt-2"><strong>Related Type:</strong> {{ $assignment->related_type }}</div>
            <div class="col-md-8 mt-2"><strong>Name / ERP ID:</strong> {{ $assignment->related_name }}</div>
            <div class="col-md-6 mt-3"><strong>Last Action:</strong><br>{{ $assignment->last_action }}</div>
            <div class="col-md-6 mt-3"><strong>Next Action / Requirement:</strong><br>{{ $assignment->next_action ?: $assignment->requirement }}</div>
            <div class="col-md-12 mt-3"><strong>Source Note:</strong><br>{{ $assignment->source_note }}</div>
            <div class="col-md-12 mt-3"><strong>Final Reason / Assignee Note:</strong><br>{{ $assignment->final_reason ?: $assignment->assignee_note }}</div>
        </div>
        <hr>
        <h5>Tracking History</h5>
        <div class="table-responsive">
            <table class="table border table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>Action By</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignment->histories as $history)
                        <tr>
                            <td>{{ optional($history->created_at)->format('d-M-Y h:i A') }}</td>
                            <td>{{ $history->action }}</td>
                            <td>{{ $history->status }}</td>
                            <td>{{ optional($history->actionBy)->full_name }}</td>
                            <td>{{ optional($history->fromUser)->full_name }}</td>
                            <td>{{ optional($history->toUser)->full_name }}</td>
                            <td>{{ $history->note }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No history found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
