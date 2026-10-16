@extends('layouts.admin')
@section('title') WhatsApp Notifications @endsection
@section('content')
<section>
    <div class="row">
        <div class="col-12">
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-header px-4 py-3 border-bottom">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <h5 class="card-title fw-semibold mb-0 lh-sm">WhatsApp Notifications</h5>
                        <form method="GET" class="d-flex flex-wrap gap-2">
                            <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Student, number, or template">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All types</option>
                                <option value="CLASS_REMINDER" @selected(request('type') === 'CLASS_REMINDER')>Class reminder</option>
                                <option value="FEE_DUE" @selected(request('type') === 'FEE_DUE')>Fee due</option>
                            </select>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All statuses</option>
                                @foreach (['QUEUED', 'PROCESSING', 'ACCEPTED', 'SENT', 'DELIVERED', 'READ', 'FAILED', 'SKIPPED'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table border table-bordered table-sm text-nowrap mb-3 align-middle">
                            <thead class="text-dark fs-3">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Student</th>
                                    <th>Recipient</th>
                                    <th>Template</th>
                                    <th>Class / Fee</th>
                                    <th>Status</th>
                                    <th>Attempts</th>
                                    <th>Initiated By</th>
                                    <th>Created</th>
                                    <th>Error</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    @php
                                        $studentName = trim(($notification->student?->first_name ?? '') . ' ' . ($notification->student?->last_name ?? '')) ?: 'N/A';
                                        $initiator = trim(($notification->initiatedBy?->first_name ?? '') . ' ' . ($notification->initiatedBy?->last_name ?? '')) ?: 'System';
                                        $badge = match ($notification->status) {
                                            'READ', 'DELIVERED' => 'success',
                                            'FAILED' => 'danger',
                                            'SKIPPED' => 'secondary',
                                            'SENT', 'ACCEPTED' => 'info',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $notification->id }}</td>
                                        <td>{{ str_replace('_', ' ', $notification->type) }}</td>
                                        <td>{{ $studentName }}</td>
                                        <td>{{ $notification->recipient }}</td>
                                        <td>{{ $notification->template_name }}</td>
                                        <td>
                                            @if ($notification->type === 'CLASS_REMINDER')
                                                {{ $notification->batchClassSession?->batch?->name ?? 'N/A' }}<br>
                                                <small>{{ optional($notification->batchClassSession?->scheduled_start_at)->format('d-M-Y h:i A') }}</small>
                                            @else
                                                {{ $notification->amount ?? 'N/A' }} {{ $notification->currency }}
                                            @endif
                                        </td>
                                        <td><span class="badge bg-{{ $badge }}">{{ $notification->status }}</span></td>
                                        <td>{{ $notification->attempts }}</td>
                                        <td>{{ $initiator }}</td>
                                        <td>{{ $notification->created_at?->format('d-M-Y h:i A') }}</td>
                                        <td class="text-wrap" style="max-width: 260px;">{{ $notification->last_error ?: '-' }}</td>
                                        <td>
                                            @if ($notification->canRetry())
                                                <form method="POST" action="{{ route('admin.wati-notifications.retry', $notification) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Retry notification">
                                                        <i class="ti ti-refresh"></i>
                                                    </button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="12" class="text-center py-4">No WhatsApp notifications found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
