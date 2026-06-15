<div class="col-md-3"></div>
<div>
    <div id="whole-div-{{ $coachavailability->id }}" class="whole-div-{{ $coachavailability->id }}"
        data-property-id="{{ $coachavailability->id }}"
        style="border: 2px solid #2E5984; padding: 13px 25px 13px 25px; margin-top: 13px; margin-bottom: 13px; border-radius: 10px;">
        <div class="row">
            <div class="col-md-6  d-flex justify-content-start">
                <select id="day_of_week-{{ $coachavailability->id }}" name="day_of_week[{{ $coachavailability->id }}]"
                    class="form-control day_of_week" data-unique-row-id="{{ $coachavailability->id }}">
                    <option value="">Select Day</option>
                    <option value="Monday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Monday' ? 'selected' : '' }}>
                        Monday</option>
                    <option value="Tuesday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Tuesday' ? 'selected' : '' }}>
                        Tuesday</option>
                    <option value="Wednesday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Wednesday' ? 'selected' : '' }}>
                        Wednesday</option>
                    <option value="Thursday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Thursday' ? 'selected' : '' }}>
                        Thursday</option>
                    <option value="Friday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Friday' ? 'selected' : '' }}>
                        Friday</option>
                    <option value="Saturday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Saturday' ? 'selected' : '' }}>
                        Saturday</option>
                    <option value="Sunday"
                        {{ isset($coachavailability->day_of_week) && $coachavailability->day_of_week == 'Sunday' ? 'selected' : '' }}>
                        Sunday</option>
                </select>
            </div>
            <div class="col-md-6 ">
                <button type="button" class="btn btn-danger btn-sm float-right day-remove-btn"
                    data-unique-row-id="{{ $coachavailability->id }}"><i class="fa fa-trash"></i> </button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 mt-2">
                <div id="period_name-error" style="color:red"></div>
                <button type="button " id="add-period-btn-{{ $coachavailability->id }}"
                    data-unique-row-id="{{ $coachavailability->id }}"
                    class="btn btn-primary btn-sm float-right add-period-btn">
                    Add Period &nbsp; <i class="fa fa-plus"></i>
                </button>
                <div class="card-body">
                    <div id="period-div-{{ $coachavailability->id }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
