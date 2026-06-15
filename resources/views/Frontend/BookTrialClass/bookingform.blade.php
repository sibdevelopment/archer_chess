{{-- <!-- Add Bootstrap + FontAwesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
    .chess-form {
        background: linear-gradient(135deg, #fff8e1, #e1f5fe);
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        padding: 30px;
        max-width: 900px;
        margin: auto;
    }

    .chess-form h5 {
        color: #ff5364;
        font-weight: 700;
        text-align: center;
    }

    .chess-form p {
        font-size: 14px;
        color: #444;
    }

    .input-block {
        margin-bottom: 20px;
    }

    .input-block label {
        font-weight: 600;
        font-size: 14px;
    }

    .input-block input,
    .input-block select {
        border-radius: 12px;
        border: 1px solid #ccc;
        padding: 10px 15px;
        font-size: 14px;
    }

    .btn-chess {
        background: #ff5364;
        border: none;
        border-radius: 30px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: bold;
        color: white;
        transition: 0.3s;
    }

    .btn-chess:hover {
        background: #e13a4a;
        transform: scale(1.05);
    }

    .chess-icon {
        font-size: 20px;
        margin-right: 6px;
        color: #ff9800;
    }

    .form-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
</style>

<div class="chess-form">
    <div class="form-header mb-3">
        <i class="fas fa-chess-knight chess-icon"></i>
        <h5 class="mb-0">Book a Free Trial</h5>
        <i class="fas fa-chess-rook chess-icon"></i>
    </div>
    <p class="text-center">
        Welcome! Please fill out this form to confirm your booking.<br>
        <span style="color:red; font-weight:600;">Note:</span> We will only communicate by <b>WhatsApp</b>, so please
        provide your WhatsApp number.
    </p>

    <form method="POST" enctype="multipart/form-data" autocomplete="off" id="confirmbooking-form">
        @csrf
        <div class="row">
            <!-- Country -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="country"><i class="fas fa-globe-americas chess-icon"></i> Country*</label>
                    <select class="form-control" id="country" name="country">
                        <option value="">Select Country</option>
                        <option>USA</option>
                        <option>CANADA</option>
                        <option>AUSTRALIA</option>
                        <option>NEW ZEALAND</option>
                        <option>INDIA</option>
                        <option>UAE</option>
                        <option>UK</option>
                        <option>SINGAPORE</option>
                        <option>SOUTH AFRICA</option>
                        <option>QATAR</option>
                        <option>BAHRAIN</option>
                        <option>KUWAIT</option>
                    </select>
                </div>
            </div>
            <!-- Timezone -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="timezone"><i class="fas fa-clock chess-icon"></i> Timezone*</label>
                    <select class="form-control" id="timezone" name="timezone">
                        <option value="" disabled selected>Select Time Zone</option>
                    </select>
                </div>
            </div>
            <!-- City -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="city"><i class="fas fa-city chess-icon"></i> City*</label>
                    <input type="text" class="form-control" id="city" name="city"
                        placeholder="Enter your city">
                </div>
            </div>
            <div class="col-lg-6" style="display: none;">
                <div class="input-block">
                    <label class="form-control-label" for="duration">Duration*</label>
                    <select class="form-control" id="duration_selection" name="duration">
                        <option value="25_minutes" selected>25 Minutes</option>
                    </select>
                    <div id="duration-error" style="color:red"></div>
                </div>
            </div>

            <!-- Kid Name -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="kids_first_name"><i class="fas fa-user chess-icon"></i> Kid's Full Name*</label>
                    <input type="text" class="form-control" id="kids_first_name" name="kids_first_name"
                        placeholder="Enter kid's full name">
                </div>
            </div>
            <!-- Age -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="age"><i class="fas fa-child chess-icon"></i> Age*</label>
                    <input type="number" class="form-control" id="age" name="age"
                        placeholder="Enter kid's age">
                </div>
            </div>
            <!-- Mobile -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="phone"><i class="fab fa-whatsapp chess-icon"></i> WhatsApp Number*</label>
                    <input type="tel" class="form-control" id="phone" name="mobile"
                        placeholder="Enter WhatsApp number">
                    <div id="trial_mobile-error" style="color:red"></div>
                    <div id="mobile-error" style="color:red"></div>
                </div>
            </div>
            <!-- Email -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="email"><i class="fas fa-envelope chess-icon"></i> Email*</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your email">
                </div>
            </div>
            <!-- Language -->
            <div class="col-md-6">
                <div class="input-block">
                    <label for="language_preference"><i class="fas fa-language chess-icon"></i> Language
                        Preference*</label>
                    <select class="form-control" id="language_preference" name="language_preference">
                        <option value="" disabled selected>Select language preference</option>
                        <option value="agree">Agree (English)</option>
                        <option value="not_comfortable">Kid is not comfortable in English</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-center mt-4">
            <button class="btn btn-chess">
                <i class="fas fa-chess-king me-2"></i> Submit
            </button>
        </div>
    </form>
</div>

<script>
    const input = document.querySelector("#phone");
    const iti = window.intlTelInput(input, {
        initialCountry: "", // blank to show "select code" behavior
        separateDialCode: true,
        preferredCountries: ["in", "us", "gb"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
    });

    $('form').on('submit', function(e) {
        const selectedCountry = iti.getSelectedCountryData();

        // Remove existing hidden input (if any)
        $('input[name="country_code"]').remove();

        // Create new hidden country_code input
        if (selectedCountry && selectedCountry.dialCode) {
            const hiddenInput = $('<input>', {
                type: 'hidden',
                name: 'country_code',
                value: '+' + selectedCountry.dialCode
            });
            $(this).append(hiddenInput);
        }
    });
    
    $(document).ready(function() {
        // Extract utm_source and utm_medium from URL
        const urlParams = new URLSearchParams(window.location.search);
        const utmSource = urlParams.get('utm_source');
        const utmMedium = urlParams.get('utm_medium');

        if (utmSource) {
            $('#utm_source').val(utmSource);
        }
        if (utmMedium) {
            $('#utm_medium').val(utmMedium);
        }

        // Submit form
        $('#confirmbooking-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            const url = "{{ route('confirm.trial.class') }}";
            const loadingToast = toastr.info('Processing your request...', {
                timeOut: 0,
                extendedTimeOut: 0
            });

            $('#modal-loading').modal('show');

            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.clear(loadingToast);
                    $('#modal-loading').modal('hide');

                    if (data.status === 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 100,
                            closeButton: true,
                            onHidden: function() {
                                window.location.href =
                                    "{{ route('book.trial.class.thankyou') }}?user_id=" +
                                    data.user_id;
                            }
                        });
                    } else {
                        toastr.error('There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr) {
                    toastr.clear(loadingToast);
                    $('#modal-loading').modal('hide');

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let firstErrorField = null;

                        if (errors.mobile || errors.country_code) {
                            let combinedError = '';
                            if (errors.mobile) combinedError += errors.mobile[0] + '<br>';
                            if (errors.country_code) combinedError += errors.country_code[
                                0];
                            $('#trial_mobile-error').html(combinedError);
                            delete errors.mobile;
                            delete errors.country_code;
                            firstErrorField = $('#phone');
                        } else {
                            $('#trial_mobile-error').html('');
                        }

                        $.each(errors, function(key, value) {
                            const errorDiv = $('#' + key + '-error');
                            const inputField = $('[name="' + key + '"]');

                            if (errorDiv.length) {
                                errorDiv.html(value[0]);
                            } else if (inputField.length) {
                                inputField.after('<div id="' + key +
                                    '-error" class="text-danger">' + value[0] +
                                    '</div>');
                            }

                            if (!firstErrorField) {
                                firstErrorField = inputField;
                            }
                        });
                    } else {
                        toastr.error(
                            'There was an error submitting the form. Please try again.',
                            '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        console.error("Error submitting form:", xhr);
                    }
                }
            });
        });

        const timezones = {
            'USA': ['Mountain Time', 'Eastern Time', 'Central Time', 'Pacific Time', 'Alaska Time',
                'Hawaii-Aleutian Time'
            ],
            'CANADA': ['Mountain Time', 'Eastern Time', 'Central Time', 'Pacific Time', 'Alaska Time',
                'Hawaii-Aleutian Time'
            ],
            'NEWZEALAND': ['New Zealand Daylight Time', 'New Zealand Standard Time'],
            'AUSTRALIA': ['Australia/Perth', 'Australia/Darwin', 'Australia/Brisbane', 'Australia/Adelaide',
                'Australia/Sydney'
            ],
            'UK': ['British Summer Time', 'Greenwich Mean Time'],
            'INDIA': ['Indian Standard Time'],
            'UAE': ['Gulf Standard Time'],
            'SINGAPORE': ['Singapore Standard Time'],
            'SOUTH AFRICA': ['South Africa Standard Time'],
            'QATAR': ['Arabian Standard Time']
        };

        function updateTimezones(country) {
            const $timezone = $('#timezone');
            $timezone.empty();

            if (timezones[country]) {
                if (['INDIA', 'UAE', 'SINGAPORE'].includes(country) && timezones[country].length === 1) {
                    $timezone.append(
                        `<option value="${timezones[country][0]}" selected>${timezones[country][0]}</option>`
                    );
                } else {
                    $timezone.append(`<option value="" disabled selected>Select Time Zone</option>`);
                    timezones[country].forEach(function(tz) {
                        $timezone.append(`<option value="${tz}">${tz}</option>`);
                    });
                }
            }
        }

        function fetchTimeSlots() {
            const country = $('#country').val();
            const timezone = $('#timezone').val();
            const date = $('#date').val();

            if (country && timezone && date) {
                $.ajax({
                    url: '{{ route('get.time.slots') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        country: country,
                        timezone: timezone,
                        date: date
                    },
                    success: function(response) {
                        const $time = $('#time');
                        const $timeError = $('#time-error');
                        $time.empty().append(
                            '<option value="" disabled selected>Select Time Slot</option>');
                        response.time_slots.forEach(function(slot) {
                            $time.append(`<option value="${slot}">${slot}</option>`);
                        });
                        $timeError.html(
                            `Available time : ${response.country_start_time.substring(0, 5)} to ${response.country_end_time.substring(0, 5)}`
                        );
                    },
                    error: function() {
                        $('#time').empty().append(
                            '<option value="" disabled selected>Select Time Slot</option>');
                        $('#time-error').html(
                            'No slots available for selected country/timezone/date.');
                    }
                });
            }
        }

        function formatDate(date) {
            return date.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }).replace(/ /g, ' ');
        }

        const maxDate = new Date();
        maxDate.setDate(maxDate.getDate() + 7);

        function validateDate() {
            const $dateInput = $('#date');
            const $dateError = $('#date-error');
            const dateValue = new Date($dateInput.val());
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (!$dateInput.val()) {
                $dateError.text('The date field is required.');
                return false;
            } else if (dateValue < today) {
                $dateError.text('The date must be today or later.');
                return false;
            } else if (dateValue > maxDate) {
                $dateError.text(`The date must be before or equal to ${formatDate(maxDate)}.`);
                return false;
            } else {
                $dateError.text('');
                return true;
            }
        }

        function validateTime() {
            const $dateInput = $('#date');
            const $timeInput = $('#time');
            const $timeError = $('#time-error');

            const dateValue = $dateInput.val();
            const timeValue = $timeInput.val();
            const bookingDateTime = new Date(`${dateValue}T${timeValue}`);
            const minTime = new Date();
            minTime.setHours(minTime.getHours() + 2);

            if (!timeValue) {
                $timeError.text('The time field is required.');
                return false;
            } else if (!/^\d{2}:\d{2}:\d{2}$/.test(timeValue)) {
                $timeError.text('The time must be in the format HH:mm:ss.');
                return false;
            } else if (bookingDateTime < minTime) {
                $timeError.text('Bookings cannot be made within 2 hours of the current time.');
                return false;
            } else {
                $timeError.text('');
                return true;
            }
        }

        // Bind change events
        $('#country').on('change', function() {
            updateTimezones($(this).val());
            fetchTimeSlots();
        });

        $('#timezone').on('change', fetchTimeSlots);
        $('#date').on('change', function() {
            if (validateDate()) {
                fetchTimeSlots();
            }
        });

        $('#time').on('change', validateTime);

        $('form').on('submit', function(event) {
            const isDateValid = validateDate();
            const isTimeValid = validateTime();

            if (!isDateValid || !isTimeValid) {
                event.preventDefault();
            }
        });

        // Initialize on page load
        const preselectedCountry = $('#country').val();
        if (preselectedCountry) {
            updateTimezones(preselectedCountry);
        }
    });


 
</script> --}}
