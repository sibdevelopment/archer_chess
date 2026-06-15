<div class="col-md-3"></div>
<div id="whole-div-{{ $coachavailabilityperiod->id }}"
    class="whole-div-{{ $coachavailabilityperiod->id }}"
    style="border: 2px solid #2E5984;padding: 13px 25px 13px 25px;margin-top: 13px;margin-bottom: 13px;border-radius: 10px;">
    <div class="row">
        <div class="col-md-5">
            <input type="time" id="from_period-{{ $coachavailabilityperiod->id }}"
                   name="from_period[{{ $coachavailability->id }}][{{ $coachavailabilityperiod->id }}]"
                   value="{{ isset($coachavailabilityperiod) ? $coachavailabilityperiod->from_period : '' }}"
                   class="form-control from_period"
                   data-unique-row-id="{{ $coachavailabilityperiod->id }}"
                   placeholder="From Time" />
        </div>
        <div class="col-md-5">
            <input type="time" id="to_period-{{ $coachavailabilityperiod->id }}"
                   name="to_period[{{ $coachavailability->id }}][{{ $coachavailabilityperiod->id }}]"
                   value="{{ isset($coachavailabilityperiod) ? $coachavailabilityperiod->to_period : '' }}"
                   class="form-control to_period"
                   data-unique-row-id="{{ $coachavailabilityperiod->id }}"
                   placeholder="To Time" />
        </div>
        <div class="col-2" style="margin-top: 5px;">
            <button type="button" class="btn btn-danger btn-sm float-right period-remove-btn"
                data-unique-row-id="{{ $coachavailabilityperiod->id }}">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>
