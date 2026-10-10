@extends('layouts.admin')
@section('title') Create Shift Handover @endsection
@section('content')
<form method="POST" action="{{ route('admin.shift-handovers.store') }}">
    @csrf
    <div class="card w-100">
        <div class="card-header"><h5>Shift Handover | Create & Saved</h5></div>
        <div class="card-body border-top">
            @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
            <p class="text-muted">Enter handover and assign only to another active employee from the same role. It will appear in that employee's assigned work.</p>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label col-form-label">Related Type <sup class="tcul-star-restrict">*</sup></label>
                    <select name="related_type" class="form-select">
                        @foreach (['Enquiry', 'Demo', 'Student', 'Other'] as $type)
                            <option value="{{ $type }}" @selected(old('related_type') === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Student / Lead Name <sup class="tcul-star-restrict">*</sup></label>
                    <input type="text" name="related_name" class="form-control" value="{{ old('related_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="control-label col-form-label">Country</label>
                    <select name="country" class="form-select select2">
                        <option value="">Select Country</option>
                        {!! countryOptionsHtml(old('country')) !!}
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Last Action <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="last_action" class="form-control" rows="3">{{ old('last_action') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Exact Next Action <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="next_action" class="form-control" rows="3">{{ old('next_action') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Assign To <sup class="tcul-star-restrict">*</sup></label>
                    <select name="assigned_to" class="form-select select2">
                        <option value="">Select Same-Role Employee</option>
                        @foreach ($assignees as $assignee)
                            <option value="{{ $assignee->id }}" @selected(old('assigned_to') == $assignee->id)>{{ $assignee->full_name }}</option>
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
                    <label class="control-label col-form-label">Notes</label>
                    <textarea name="source_note" class="form-control" rows="3">{{ old('source_note') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save <i class="ti ti-device-floppy"></i></button>
            <a href="{{ route('admin.shift-handovers.index') }}" class="btn btn-secondary">Cancel <i class="ti ti-arrow-back-up-double"></i></a>
        </div>
    </div>
</form>
@endsection
