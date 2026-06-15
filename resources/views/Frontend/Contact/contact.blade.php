@extends('layouts.frontend')
@section('title')
    Contact Us - Archer Chess Academy
@endsection
@section('content')
    <style>
        .loading-spinner {
            width: 30px;
            height: 30px;
            border: 2px solid indigo;
            border-radius: 50%;
            border-top-color: #0001;
            display: inline-block;
            animation: loadingspinner .7s linear infinite;
        }

        @keyframes loadingspinner {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }
    </style>
    <div class="breadcrumb-bar page-banner breadcrumb-bar-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Contact</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------------------------------------------- :: -->


    <!-- Contact Us Section :: -->
    <section id="contact" class="section" style="padding: 40px 0 !important;">
        <div class="container">
            <div class="row mt-4 align-items-center">
                <div class="col-lg-6">
                    <div class="section-header aos" data-aos="fade-up">
                        <div class="section-sub-head">
                            <span>Customer satisfaction is essential</span>
                            <h2> Contact Us </h2>
                        </div>
                        <div class="all-btn all-category d-flex align-items-center">
                            <!-- <a href="course-list.html" class="btn btn-primary">All Courses</a> -->
                        </div>
                    </div>
                    <div class="section-text aos pb-4" data-aos="fade-up">
                        <p class="mb-0">Your feedback and inquiries are important to us. Reach out to our team for any
                            questions or support you may need, and we'll get back to you as soon as possible.</p>
                    </div>
                    <div class="d-flex">
                        <div class="get-certified certified-group blur-border d-flex">
                            <div class=" d-flex align-items-center">
                                <div class="blur-box">
                                    <div class="certified-img ">
                                        <img src="/frontend/assets/img/icon/icon-3.svg" alt="Img" class="img-fluid">
                                    </div>
                                </div>
                                <ul>
                                    <li> We hope you’ll call, email, or utilize our Live Chat feature anytime you have a
                                        question or concern.</li>
                                    <li> Live Chat to look for the “Online – Click here to chat” pop up in the bottom right
                                        corner.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="get-certified certified-group blur-border d-flex">
                            <div class="d-flex align-items-center">
                                <div class="blur-box">
                                    <div class="certified-img ">
                                        <img src="/frontend/assets/img/icon/icon-2.svg" alt="Img" class="img-fluid">
                                    </div>
                                </div>
                                <ul>
                                    <li>&nbsp; Support : Monday - Saturday 24 hour</li>
                                    <li>&nbsp; +91-9152734675 &nbsp; | &nbsp; support@archerchessacademy.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="support-wrap">
                        <h5>Get in touch with us!</h5>
                        <form method="POST" enctype="multipart/form-data" autocomplete="off" id="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-block">
                                        <label class="form-control-label" for="first_name">Name*</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter your first Name">
                                        <div id="name-error" style="color:red"></div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                <div class="input-block">
                                    <label class="form-control-label" for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last Name">
                                    <div id="last_name-error" style="color:red"></div>
                                </div>
                            </div> --}}
                                <div class="col-lg-6">
                                    <div class="input-block">
                                        <label class="form-control-label" for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Enter your email address">
                                        <div id="email-error" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-block">
                                        <label class="form-control-label" for="country">Country *</label>
                                        <select id="feesCountry" class="form-select" name="country">
                                            <option value="">-- Select Country --</option>
                                            <option value="USA">USA</option>
                                            <option value="CANADA">CANADA</option>
                                            <option value="AUSTRALIA">AUSTRALIA</option>
                                            <option value="NEWZEALAND">NEW ZEALAND</option>
                                            <option value="INDIA">INDIA</option>
                                            <option value="UAE">UAE</option>
                                            <option value="UK">UK</option>
                                            <option value="SINGAPORE">SINGAPORE</option>
                                            <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                            <option value="QATAR">QATAR</option>
                                            <option value="BAHRAIN">BAHRAIN</option>
                                            <option value="KUWAIT">KUWAIT</option>
                                        </select>
                                        <div id="country-error" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-block">
                                        <label class="form-control-label" for="mobile">Mobile*</label>
                                        <input type="text" class="form-control mobile-enquiry-popup" id="mobile"
                                            name="mobile" placeholder="Enter your Mobile">
                                        <div id="mobile-error" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-block">
                                        <label class="form-control-label" for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            placeholder="Enter your Subject">
                                        <div id="subject-error" style="color:red"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-block">
                                        <label class="form-control-label" for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Write down here" rows="4"></textarea>
                                        <div id="description-error" style="color:red"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="country_code" id="country_code">
                                <input type="hidden" name="country_iso2" id="country_iso2">
                                <input type="hidden" name="full_mobile" id="full_mobile">

                                <div class="col-lg-12 text-center">
                                    <button class="btn btn-submit" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            {{-- <iframe src="https://maps.google.com/maps?q=A-501%20%2C%20Vishnu%20Apartment%20Phase%202%20%2CNaigaon%20East%2CVasai%20Virar%2C%20Mumbai%2C%20Maharashtra%20401208&t=m&z=10&output=embed&iwloc=near" allowfullscreen="" width="100%" height="450px%"  style="border: 1px solid #e9ecef; border-radius: 10px;"></iframe> --}}
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241133.46717263266!2d72.68521884282376!3d19.20784616739755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b02c0b821505%3A0x7fcb82953b8ca8bf!2sArcher%20Chess%20Academy!5e0!3m2!1sen!2sin!4v1759562323608!5m2!1sen!2sin"
                width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>


    <!-- Loader Modal -->
    <div class="modal" id="modal-loading" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loading-spinner mb-2"></div>
                    <div>Loading</div>
                </div>
            </div>
        </div>
    </div>
    <!-- -->
    <script>
        const input = document.querySelector("#mobile");
        const iti = window.intlTelInput(input, {
            initialCountry: "in", // default country (India)
            separateDialCode: true, // shows country code separately
            preferredCountries: ["in", "us", "ae", "au", "uk"],
        });

        // Optional: store full number in a hidden field
        const form = document.getElementById("contact-form");
        form.addEventListener("submit", function() {
            const fullNumber = iti.getNumber(); // e.g. +919876543210
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "full_mobile";
            hiddenInput.value = fullNumber;
            form.appendChild(hiddenInput);
        });
    </script>

    <script>
        $(document).ready(function() {

            // Function to handle form submission
            function attachFormSubmitHandler() {
                $('#contact-form').submit(function(e) {
                    e.preventDefault();
                    $('div[id$="-error"]').empty();

                    // ---- get dial code / iso / full number from intl-tel-input ----
                    const data = iti
                        .getSelectedCountryData(); // { dialCode: "91", iso2: "in", name: "India (…)" }


                    // Fill hidden fields
                    $('#country_code').val('+' + data.dialCode);
                    $('#country_iso2').val((data.iso2 || '').toUpperCase());
                    $('#full_mobile').val(iti.getNumber()); // e.g. +919876543210 (E.164)

                    const form = $(this);
                    const url = "{{ route('contact.submit') }}";

                    const loadingToast = toastr.info('Processing your request...', {
                        timeOut: 0,
                        extendedTimeOut: 0
                    });
                    $('#modal-loading').modal('show');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData(this), // <-- now includes the hidden fields
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            toastr.clear(loadingToast);
                            $('#modal-loading').modal('hide');
                            if (data.status == 'success') {
                                toastr.success(data.message, '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true
                                });
                                form[0].reset();
                                window.location.href = "{{ route('thankyou') }}";
                            } else {
                                toastr.error('There is some error!!', '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true
                                });
                            }
                        },
                        error: function(xhr) {
                            toastr.clear(loadingToast);
                            $('#modal-loading').modal('hide');
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    var errorDiv = $('#' + key + '-error');
                                    if (errorDiv.length) {
                                        errorDiv.html(value[0]);
                                    } else {
                                        var inputField = $('[name="' + key + '"]');
                                        if (inputField.length) {
                                            inputField.after('<div id="' + key +
                                                '-error" class="text-danger">' +
                                                value[0] + '</div>');
                                        }
                                    }
                                });
                            } else {
                                console.error("Error submitting form:", xhr);
                            }
                        }
                    });
                });

            }
            attachFormSubmitHandler();
        });
    </script>
@endsection
