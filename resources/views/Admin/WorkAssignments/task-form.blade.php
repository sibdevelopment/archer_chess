@extends('layouts.admin')
@section('title') Create Task @endsection
@section('content')
<form method="POST" action="{{ route('admin.task-assignments.store') }}">
    @csrf
    <div class="card w-100">
        <div class="card-header"><h5>Issue / Task | Create & Saved</h5></div>
        <div class="card-body border-top">
            @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            <p class="text-muted">Select Enquiry / Demo / Student, write what is required, and assign to any active employee role.</p>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label col-form-label">Related Type <sup class="tcul-star-restrict">*</sup></label>
                    <select name="related_type" class="form-select">
                        @foreach (['Enquiry', 'Demo', 'Student', 'Internal', 'Other'] as $type)
                            <option value="{{ $type }}" @selected(old('related_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Name / ERP ID</label>
                    <input type="text" name="related_name" class="form-control" value="{{ old('related_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Country</label>
                    <select name="country" class="form-select select2">
                        <option value="">Select Country</option>
                        {!! countryOptionsHtml(old('country')) !!}
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="control-label col-form-label">What Is Required? <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="requirement" class="form-control" rows="4">{{ old('requirement') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Assign To <sup class="tcul-star-restrict">*</sup></label>
                    <select name="assigned_to" class="form-select select2">
                        <option value="">Select Employee</option>
                        @foreach ($assignees as $assignee)
                            <option value="{{ $assignee->id }}" @selected(old('assigned_to') == $assignee->id)>{{ $assignee->full_name }} - {{ $assignee->getRoleNames()->implode(', ') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Priority <sup class="tcul-star-restrict">*</sup></label>
                    <select name="priority" class="form-select">
                        @foreach (['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'] as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', 'MEDIUM') === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="control-label col-form-label">Source Notes / Reference</label>
                    <textarea name="source_note" class="form-control" rows="3">{{ old('source_note') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save <i class="ti ti-device-floppy"></i></button>
            <a href="{{ route('admin.task-assignments.index') }}" class="btn btn-secondary">Cancel <i class="ti ti-arrow-back-up-double"></i></a>
        </div>
    </div>
</form>
@endsection
