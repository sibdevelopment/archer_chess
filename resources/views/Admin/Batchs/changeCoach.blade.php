<input type="hidden" id="batchId" name="batch_id" value="{{ $batch_id }}">
<div class="col-md-12">
    <fieldset class="form-group">
        <label for="coach" class="form-label">Coach</label>
        <select class="form-select" id="coach" name="coach_id">
            <option selected disabled>Select coach ...</option>
            @foreach ($coaches as $key => $coach)
                @php 
                    $check_is_demo_assign = $coach->isDemoAssign($batch_id);
                @endphp 
                <option value="{{ $coach->id }}" {{ $coach->id == $current_coach_id ? 'selected' : '' }}>
                    {{ $coach->user->first_name }} {{ $coach->user->last_name }} @if($check_is_demo_assign == '11') (Demo) @endisset
                </option>
                
            @endforeach
        </select>
        <div id="coach-error" style="color:red"></div>
    </fieldset>
</div>
