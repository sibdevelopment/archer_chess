<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <select name="new_assign_coach_id" id="new_assign_coach_id" class="select2 form-select form-select-sm pure-white" aria-label=".form-select-sm example">
            <option value="">Select Coach</option>
            @foreach ($coaches as $coach)
                <option value="{{ $coach->id }}">{{ $coach->user->first_name }} {{ $coach->user->last_name }}</option>
            @endforeach
        </select>
        <input type="coverup_class_id" name="coverup_class_id" id="coverup_class_id" value="{{ $coverup_class_id }}" hidden>

    </div>

</div>
