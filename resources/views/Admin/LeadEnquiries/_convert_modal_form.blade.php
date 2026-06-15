<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name <sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" name="first_name" value="{{ $enquiry->kids_first_name }}">
        <div id="first_name-error" class="text-danger small"></div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="text" class="form-control" name="email" value="{{ $enquiry->user->email ?? '' }}">
        <div id="email-error" class="text-danger small"></div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Age</label>
        <input type="number" class="form-control" name="age" value="{{ $enquiry->age }}">
        <div id="age-error" class="text-danger small"></div>
    </div>
    <div class="col-md-8">
        <label class="form-label">Mobile <sup class="text-danger">*</sup></label>
        <input type="text" class="form-control" name="mobile" value="{{ $enquiry->mobile }}" id="convert_mobile">
        <div id="mobile-error" class="text-danger small"></div>
    </div>

    <div class="col-md-6">
        <label class="form-label">City</label>
        <input type="text" class="form-control" name="city" value="{{ $enquiry->city }}">
        <div id="city-error" class="text-danger small"></div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Country <sup class="text-danger">*</sup></label>
        <select class="form-select" name="convert_country" id="convert_country">
            <option value="">Select Country</option>
            @foreach ($allCountries as $c)
                <option value="{{ $c }}"
                    {{ strtoupper(trim($enquiry->country ?? '')) === strtoupper(trim($c)) ? 'selected' : '' }}>
                    {{ $c }}
                </option>
            @endforeach
        </select>

        <div id="country-error" class="text-danger small"></div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Timezone <sup class="text-danger">*</sup></label>
        <select class="form-select" name="kids_time_zone" id="convert_timezone">
            @if ($enquiry->timezone)
                <option selected value="{{ $enquiry->timezone }}">{{ $enquiry->timezone }}</option>
            @else
                <option value="" selected disabled>Select Time Zone</option>
            @endif
        </select>
        <div id="kids_time_zone-error" class="text-danger small"></div>
    </div>

    <div class="col-md-3">
        <label class="form-label">IST Date <sup class="text-danger">*</sup></label>
        <input type="date" class="form-control" name="ist_date" id="ist_date" value="{{ $enquiry->ist_date }}">
        <div id="date-error" class="text-danger small"></div>
    </div>
    <div class="col-md-3">
        <label class="form-label">IST Time <sup class="text-danger">*</sup></label>
        <input type="time" class="form-control" name="time" id="convert_time" value="{{ $enquiry->ist_time }}">
        <div id="time-error" class="text-danger small"></div>
    </div>

    <div class="col-md-3">
        <label class="form-label">Kids Date</label>
        <input type="date" class="form-control" name="kids_date" id="convert_kids_date" value="{{ $enquiry->date }}"
            readonly>
        <div id="kids_date-error" class="text-danger small"></div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Kids Time</label>
        <input type="time" class="form-control" name="kids_time" id="convert_kids_time" value="{{ $enquiry->time }}"
            readonly>
        <div id="kids_time-error" class="text-danger small"></div>
    </div>

    <div class="col-12">
        <label class="form-label">Remark</label>
        <input type="text" class="form-control" name="remark" value="">
        <div id="remark-error" class="text-danger small"></div>
    </div>
</div>

<script>
    (function() {
        // fetch timezones when country changes (reusing your endpoint)
        function loadConvertTimezones(selected) {
            var c = $('#convert_country').val();
            if (!c) return;
            $.get('{{ route('admin.timezones.get.timezones') }}', {
                    country: c
                })
                .done(function(data) {
                    var $tz = $('#convert_timezone');
                    $tz.empty().append('<option disabled selected>Select Time Zone</option>');
                    $.each(data, function(groupLabel, timezones) {
                        var $grp = $('<optgroup/>', {
                            label: groupLabel
                        });
                        $.each(timezones, function(val, label) {
                            var $opt = $('<option/>', {
                                value: val,
                                text: label
                            });
                            if (selected && selected === val) $opt.attr('selected', true);
                            $grp.append($opt);
                        });
                        $tz.append($grp);
                    });
                });
        }

        $('#convert_country').off('change.convert').on('change.convert', function() {
            loadConvertTimezones();
        });

        // IST -> Kids conversion using your existing route
        function pushConvertForTZ() {
            $.post('{{ route('admin.demoleads.processDateTimeZone') }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                date: $('#ist_date').val(),
                time: $('#convert_time').val(),
                timeZone: $('#convert_timezone').val()
            }).done(function(resp) {
                if (resp && resp.convertedDateTime) {
                    const [d, t] = resp.convertedDateTime.split(' ');
                    $('#convert_kids_date').val(d);
                    $('#convert_kids_time').val(t.substring(0, 5));
                }
            });
        }
        $('#convert_timezone, #ist_date, #convert_time')
            .off('change.convert').on('change.convert', pushConvertForTZ);

        // initial load
        if ($('#convert_country').val()) {
            loadConvertTimezones($('#convert_timezone').val());
        }
    })();
</script>
