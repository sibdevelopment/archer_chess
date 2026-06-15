@extends('layouts.frontend')
@section('title')
    Book A Trial Class - Archer Chess Academy
@endsection
@section('content')
    <style>
        #bookingforms-stages {
            overflow: hidden;
            box-sizing: border-box;
            
        }
         #bookingforms-stages {
            /* background-image: url('/cartoon-chess-seamless-pattern-20560149.webp'); */
            background-size: cover;
            /* fill the box */
            background-position: center;
            /* center image */
            background-repeat: no-repeat;
            border-radius: 12px;
            opacity: 9;
            background-color: #fbe8e8;
        }

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

        .tcul-floating_btn_contact {
            display: none;
        }

        .tcul-floating_btn_country {
            bottom: 110px;
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
    <section class="section new-course page-content pb-33" style="background-image: none;">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="section-sub-head">
                    {{-- <span>Book A Trial Class</span> --}}
                    <h2 class="text-center">Welcome to Archer Chess Academy <br> Where Beginners Become Masters</h2>
                </div>
                <div class="all-btn all-category d-flex align-items-center">
                </div>
            </div>
            <p class="mb-0">Archer Chess Academy is a ISO certified online chess academy for students of all levels and
                all ages, led by Grandmaster MS Thejkumar and FIDE Master Joydeep Datta. Our mission is simple: to provide
                high-quality, structured chess education that guides students from the basics all the way to the Grandmaster
                level.</p>
            <p class="mb-0 mt-2">Whether you're an absolute beginner or an experienced player looking to sharpen your
                skills, Archer offers the right mentorship, tools, and environment to help you thrive.</p>

            <div class="section-text aos" data-aos="fade-up" style="max-width: 1000px !important;">
            </div>
            <div class="section-header aos mb-1" data-aos="fade-up">
            <div class="section-sub-head">
                <h4 class="mt-2" style="font-weight: 700;   color: #0b0b0b;">Welcome To Archer Chess Academy! Book A
                    Level Assessment Class</h4>
            </div>
            <div class="all-btn all-category d-flex align-items-center">
            </div>
        </div>
        <div class="section-text aos" data-aos="fade-up" style="max-width: 1000px !important;">
            <p><i class="fa-regular fa-clock"></i> &nbsp; 25 minutes</p>
            <p class="mb-0">Dear Parents, This is one on one 25 Mins level assessment class, if booking slot is not
                available academy can change your demo timing as per your preference.
                Thanks for understanding</p>
        </div>
            <div class="mt-5">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-6 aos-init aos-animate mb-3" data-aos="fade-up">
                        <div class="course-details-three row justify-content-center" style="height: 100%">
                            <div class="col-12 px-0">
                                <div class="course-img text-center">
                                    <img class="img-fluid" src="/frontend/tcul_img/grandmaster.png" alt="Img">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="course-content-three text-center">
                                    <p>Grandmaster designed curriculum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 aos-init aos-animate mb-3" data-aos="fade-up">
                        <div class="course-details-three row justify-content-center" style="height: 100%">
                            <div class="col-12 px-0">
                                <div class="course-img text-center">
                                    <img class="img-fluid" src="/frontend/tcul_img/trainer.png" alt="Img">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="course-content-three text-center">
                                    <p>Experienced Fide rated Trainer</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 aos-init aos-animate mb-3" data-aos="fade-up">
                        <div class="course-details-three row justify-content-center" style="height: 100%">
                            <div class="col-12 px-0">
                                <div class="course-img text-center">
                                    <img class="img-fluid" src="/frontend/tcul_img/environment.png" alt="Img">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="course-content-three text-center">
                                    <p>Safe environment for kid</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 aos-init aos-animate mb-3" data-aos="fade-up">
                        <div class="course-details-three row justify-content-center" style="height: 100%">
                            <div class="col-12 px-0">
                                <div class="course-img text-center">
                                    <img class="img-fluid" src="/frontend/tcul_img/online-learning.png" alt="Img">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="course-content-three text-center">
                                    <p>Interactive Online Learning</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="page-content pt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 mx-auto">
                    <div class="support-wrap trend-course" id="bookingforms-stages"
                        style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;">
                        <!-- Dynamic content will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pricing Section :: -->
    <!-- -------------------------------------------------------------------------------------------------- :: -->
    @if ($country !== 'UK' && $country !== 'AUSTRALIA')
        <section class="section trend-course" style="padding: 40px 0; background:none; background: #2d0160;"
            id="dynamic-course-pricing">

        </section>
    @endif


    <!-- -------------------------------------------------------------------------------------------------- :: -->
    <!-- Kids Section :: -->
    @if ($meetourkids->isNotEmpty())
        <section id="kid" class="section new-course" style="padding: 40px 0 !important;">
            <div class="container">
                <div class="section-header aos" data-aos="fade-up">
                    <div class="section-sub-head">
                        <span>Guided by Experts</span>
                        <h2>Meet Our Archer Kids</h2>
                    </div>
                </div>
                <div class="section-text aos" data-aos="fade-up">
                    <p class="mb-0">Victory Belongs To Those Who Never Surrender!</p>
                </div>
                <div class="feature-instructors" style="padding-top: 0px !important;">
                    <div class="owl-carousel instructors-course owl-theme aos" data-aos="fade-up">
                        @foreach ($meetourkids as $kid)
                            <div class="instructors-widget">
                                <div class="instructors-img ">
                                    <a href="#kid">
                                        <img class="img-fluid" alt="Img"
                                            src="{{ asset(Storage::url($kid->image)) }}">
                                    </a>
                                </div>
                                @if ($kid->title)
                                    <div class="instructors-content text-center">
                                        <h5><a href="#">{{ $kid->title }}</a></h5>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- -------------------------------------------------------------------------------------------------- :: -->



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
        $(document).ready(function() {

            // Function to load booking form
            function loadBookingForm() {
                $.ajax({
                    url: "{{ route('load.booking.form') }}",
                    method: "GET",
                    success: function(response) {
                        $('#bookingforms-stages').html(response);
                        //attachFormSubmitHandler(); // Attach the form submit handler after loading the form
                    },
                    error: function(xhr) {
                        console.error("Error loading booking form:", xhr);
                    }
                });
            }

            // Call the function to load the booking form on page load
            loadBookingForm();
        });
    </script> 
@endsection
