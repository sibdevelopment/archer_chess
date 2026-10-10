@extends('layouts.admin')
@section('title') Personal Follow-Up @endsection
@section('content')
@php
    $isEdit = isset($followUp);
@endphp
<form method="POST" action="{{ $isEdit ? route('admin.personal-follow-ups.update', $followUp->route_key) : route('admin.personal-follow-ups.store') }}">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div class="card w-100">
        <div class="card-header"><h5>{{ $isEdit ? 'Edit' : 'Create' }} Personal Follow-Up</h5></div>
        <div class="card-body border-top">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label col-form-label">Follow-Up Date & Time <sup class="tcul-star-restrict">*</sup></label>
                    <input type="datetime-local" name="follow_up_at" class="form-control" value="{{ old('follow_up_at', $isEdit && $followUp->follow_up_at ? $followUp->follow_up_at->format('Y-m-d\\TH:i') : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Related To</label>
                    <input type="text" name="related_to" class="form-control" value="{{ old('related_to', $followUp->related_to ?? '') }}" placeholder="Enquiry / Demo / Student / Internal">
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Name / ERP ID</label>
                    <input type="text" name="related_name" class="form-control" value="{{ old('related_name', $followUp->related_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Follow-Up Type</label>
                    <input type="text" name="follow_up_type" class="form-control" value="{{ old('follow_up_type', $followUp->follow_up_type ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Priority <sup class="tcul-star-restrict">*</sup></label>
                    <select name="priority" class="form-select">
                        @foreach (['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'] as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $followUp->priority ?? 'MEDIUM') === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label col-form-label">Status <sup class="tcul-star-restrict">*</sup></label>
                    <select name="status" class="form-select">
                        @foreach (['PENDING', 'DONE', 'CANCELLED'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $followUp->status ?? 'PENDING') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="control-label col-form-label">What To Do <sup class="tcul-star-restrict">*</sup></label>
                    <textarea name="what_to_do" class="form-control" rows="3">{{ old('what_to_do', $followUp->what_to_do ?? '') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="control-label col-form-label">Reason / Note</label>
                    <textarea name="reason_note" class="form-control" rows="3">{{ old('reason_note', $followUp->reason_note ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save <i class="ti ti-device-floppy"></i></button>
            <a href="{{ route('admin.personal-follow-ups.index') }}" class="btn btn-secondary">Cancel <i class="ti ti-arrow-back-up-double"></i></a>
        </div>
    </div>
</form>
@endsection
