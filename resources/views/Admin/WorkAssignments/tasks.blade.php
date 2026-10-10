@extends('layouts.admin')
@section('title') Task Assignment @endsection
@section('content')
@if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
<div class="card w-100">
    <div class="card-header px-4 py-3 border-bottom">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-title fw-semibold mb-0">Issue / Task | Create & Saved</h5>
                <small>Employee Name: {{ auth()->user()->full_name }}</small>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.task-assignments.create') }}" class="btn btn-info">Create Task <i class="ti ti-plus"></i></a>
                <a href="{{ route('admin.work-assignments.assigned', ['type' => 'TASK']) }}" class="btn btn-primary">Assigned Work</a>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        @include('Admin.WorkAssignments._counters')
        @include('Admin.WorkAssignments._table', ['mode' => 'task'])
    </div>
</div>
@endsection
