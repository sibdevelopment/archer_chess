
<div>
    <div id="whole-div-{{ $batch_schedule->id }}" class="whole-div-{{ $batch_schedule->id }}"
        data-property-id="{{ $batch_schedule->id }}"
        style="border: 2px solid #2E5984; padding: 13px 25px 13px 25px; margin-top: 13px; margin-bottom: 13px; border-radius: 10px;">

        <div class="row">
            <div class="col-sm-4 col-md-4">
                <label class="control-label col-form-label">Select Day </label>
                <select id="weekday-{{ $batch_schedule->id }}"
                        name="weekday[{{ $batch_schedule->id }}]" required
                        class="form-control weekday" data-unique-row-id="{{ $batch_schedule->id }}">
                    <option value="">Select Day</option>
                    <option value="Monday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Monday' ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Friday' ? 'selected' : '' }}>Friday</option>
                    <option value="Saturday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="Sunday" {{ isset($batch_schedule->weekday) && $batch_schedule->weekday == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                </select>
                <div id="weekday-error" style="color:red"></div>
            </div>
            <div class="col-sm-3 col-md-3">
                <label class="control-label col-form-label">From Time </label>
                <input type="time"
                       class="form-control from_time"
                       name="from_time[{{ $batch_schedule->id }}]" required
                       id="from_time-{{ $batch_schedule->id }}"
                       value="{{ isset($batch_schedule) ? $batch_schedule->from_time : '' }}"
                       data-unique-row-id="{{ $batch_schedule->id }}" />
                <div id="from_time-error-{{ $batch_schedule->id }}" style="color:red"></div>
            </div>
            <div class="col-sm-3 col-md-3">
                <label class="control-label col-form-label">To Time </label>
                <input type="time"
                       class="form-control to_time" required
                       name="to_time[{{ $batch_schedule->id }}]"
                       id="to_time-{{ $batch_schedule->id }}"
                       value="{{ isset($batch_schedule) ? $batch_schedule->to_time : '' }}"
                       data-unique-row-id="{{ $batch_schedule->id }}" />
                <div id="to_time-error-{{ $batch_schedule->id }}" style="color:red"></div>
            </div>
            <div class="col-sm-2 col-md-2   d-flex justify-content-end">
                <button type="button" class="mt-5 btn btn-danger btn-sm float-right day-remove-btn"
                    data-unique-row-id="{{ $batch_schedule->id }}"><i class="fa fa-trash"></i> </button>
            </div>
        </div>
    </div>
</div>

