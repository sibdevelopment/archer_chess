@extends('layouts.frontend')
@section('title')
    Page Name | Website Name
@endsection
@section('content')
    <style>
        #bookingforms-stages {
            overflow: hidden;
            box-sizing: border-box;
        }

        #no-bg-image.new-course:before {
            background-image: none !important;
        }
    </style>
    <div class="breadcrumb-bar page-banner breadcrumb-bar-info mb-0">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Book A Trial Class</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Book A Trial Class</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------------------------------------------- :: -->

    <section id="no-bg-image" class="section new-course" style="background:none; background-image: none!important;">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="section-sub-head">
                    <span>Book A Trial Class</span>
                    <h2>Book Your 25-Minute Free Session</h2>
                </div>
                <div class="all-btn all-category d-flex align-items-center">
                </div>
            </div>
            <div class="section-text aos mb-3" data-aos="fade-up" style="max-width: 1000px !important;">
                <p class="mb-0">Unlock your potential on the chessboard with our 25-minute free session. Discover
                    strategies, tactics, and improve your game with expert guidance!</p>
            </div>
            <div class="section-text aos" data-aos="fade-up" style="max-width: 1000px !important;">
                {{-- <p><i class="fa-regular fa-clock"></i> &nbsp; 25 minutes</p> --}}
                <p class="mb-0">Dear Parents, This is one on one 25 Mins level assessment class, if booking slot is not
                    available academy can change your demo timing as per your preference.
                    Thanks for understanding</p>
            </div>
        </div>
    </section>


    <div class="page-content pt-4" style="background:none;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 mx-auto">
                    <div class="support-wrap trend-course" id="bookingforms-stages"
                        style="box-shadow: 0 4px 100px #fbebeb;border: 1px solid #ffe0e0;">
                        <!-- ----------------------------------------------------------------- :: -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section :: -->
    <!-- -------------------------------------------------------------------------------------------------- :: -->
    <section class="section trend-course" style="padding: 40px 0; background:none;" id="dynamic-course-pricing">

    </section>


    <!-- -------------------------------------------------------------------------------------------------- :: -->
    <script>
        $(document).ready(function() {
            // Function to load booking form
            function loadBookingForm() {
                $.ajax({
                    url: "{{ route('load.booking.form') }}",
                    method: "GET",
                    success: function(response) {
                        $('#bookingforms-stages').html(response);
                        attachFormSubmitHandler
                            (); // Attach the form submit handler after loading the form
                    },
                    error: function(xhr) {
                        console.error("Error loading booking form:", xhr);
                    }
                });
            }

            // Function to handle form submission
            function attachFormSubmitHandler() {
                $('#confirmbooking-form').submit(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    $('div[id$="-error"]').empty(); // Clear previous error messages
                    var form = $(this);
                    var url = "{{ route('confirm.trial.class') }}";
                    var loadingToast = toastr.info('Processing your request...', {
                        timeOut: 0,
                        extendedTimeOut: 0
                    }); // Show loading toast
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            toastr.clear(loadingToast); // Clear loading toast
                            if (data.status == 'success') {
                                toastr.success(data.message, '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true,
                                });
                                // Call the function to load the verification mail form
                                loadVerificationMailForm();
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
                            toastr.clear(loadingToast); // Clear loading toast
                            if (xhr.status === 422) { // Laravel validation error status code
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    var errorDiv = $('#' + key + '-error');
                                    if (errorDiv.length) {
                                        errorDiv.html(value[0]);
                                    } else {
                                        // If the error div does not exist, create it
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

            // Function to load verification mail form
            function loadVerificationMailForm() {
                var loadingToast = toastr.info('Processing your request...', {
                    timeOut: 0,
                    extendedTimeOut: 0
                }); // Show loading toast
                $.ajax({
                    url: "{{ route('load.verification.mail.form') }}",
                    method: "GET",
                    success: function(response) {
                        toastr.clear(loadingToast); // Clear loading toast
                        $('#bookingforms-stages').html(response);
                        attachVerificationFormSubmitHandler
                            (); // Attach the form submit handler for verification form
                    },
                    error: function(xhr) {
                        toastr.clear(loadingToast); // Clear loading toast
                        toastr.error('An error occurred while loading the verification mail form.');
                        console.error("Error loading verification mail form:", xhr);
                    }
                });
            }

            // Function to handle verification form submission
            function attachVerificationFormSubmitHandler() {
                $('#verifymail-form').submit(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    $('div[id$="-error"]').empty(); // Clear previous error messages
                    var form = $(this);
                    var url = "{{ route('verify.user.lead') }}";
                    var loadingToast = toastr.info('Processing your request...', {
                        timeOut: 0,
                        extendedTimeOut: 0
                    }); // Show loading toast
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            toastr.clear(loadingToast); // Clear loading toast
                            if (data.status == 'success') {
                                toastr.success(data.message, '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true,
                                });
                                // Call the function to load the booking success content
                                loadBookingSuccess();
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
                            toastr.clear(loadingToast); // Clear loading toast
                            if (xhr.status === 422) { // Laravel validation error status code
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    var errorDiv = $('#' + key + '-error');
                                    if (errorDiv.length) {
                                        errorDiv.html(value[0]);
                                    } else {
                                        // If the error div does not exist, create it
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

            // Function to load booking success content
            function loadBookingSuccess() {
                var loadingToast = toastr.info('Processing your request...', {
                    timeOut: 0,
                    extendedTimeOut: 0
                }); // Show loading toast
                $.ajax({
                    url: "{{ route('load.booking.success') }}",
                    method: "GET",
                    success: function(response) {
                        toastr.clear(loadingToast); // Clear loading toast
                        $('#bookingforms-stages').html(response);
                    },
                    error: function(xhr) {
                        toastr.clear(loadingToast); // Clear loading toast
                        toastr.error('An error occurred while loading the booking success content.');
                        console.error("Error loading booking success content:", xhr);
                    }
                });
            }

            // Call the function to load the booking form on page load
            loadBookingForm();
        });
    </script>
@endsection
