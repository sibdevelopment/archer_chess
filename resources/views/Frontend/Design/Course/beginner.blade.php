@extends('layouts.revamp')
@section('title', 'Chess for Beginner')
@section('content')

    <head>
        <style>
            .curriculum-card {
                padding: 20px;
                border-radius: 12px;
                transition: 0.3s;
            }

            .curriculum-card img {
                width: 180px;
                height: 180px;
                object-fit: cover;
                border-radius: 50%;
                margin-bottom: 15px;
            }

            .curriculum-card h5 {
                font-weight: 600;
                margin-bottom: 10px;
            }

            .curriculum-card p {
                font-size: 14px;
                color: #6c757d;
                min-height: 60px;
                /* important for equal height */
            }

            .curriculum-card:hover {
                transform: translateY(-5px);
            }
        </style>

        <style>
            .curriculum-section {
                padding: 80px 0;
            }

            .curriculum-title {
                color: #002058;
            }

            /* Card layout */
            .curriculum-card {
                display: flex;
                align-items: flex-start;
                gap: 15px;
                padding: 20px;
                border-radius: 14px;
                background: #ffffff;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                transition: 0.3s;
            }

            /* Image left */
            .curriculum-card img {
                width: 70px;
                height: 70px;
                object-fit: cover;
                border-radius: 10px;
                flex-shrink: 0;
            }

            /* Text */
            .curriculum-card .content h5 {
                font-weight: 600;
                margin-bottom: 6px;
                color: #0f172a;
            }

            .curriculum-card .content p {
                font-size: 14px;
                color: #64748b;
                margin: 0;
                line-height: 1.6;
            }

            /* Hover effect */
            .curriculum-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            }
        </style>
    </head>

    <!-- ====================== banner section start ====================== -->
    <section class="breadcrumb pt-60 pb-20 bg-main-two-200 position-relative">
        <img src="/frontend1/assets/images/shape/banner-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-15 d-lg-block d-none animation-upDown">
        <img src="/frontend1/tcul-img/img/expert-1.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-h-100-px tw-ms-250-px d-lg-block d-none"
            style="bottom: 10px; left: -80px; ">

        <div class="tw-mb-140-px w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div>
                            <h3 class="text-center tw-mb-6 text-neutral-950">Chess for Beginner</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Chess for Beginner
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-80">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-wrap tw-mb-2">
                <div>
                    <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        About the Course</h2>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-12 col-md-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="w-100 tw-mb-0">
                        <div class="d-flex align-items-center tw-gap-3 tw-mb-3">
                            <span class="fw-medium tw-text-5 text-paragraph-500">Are you looking to learn the basics of
                                chess? Our beginner's course is designed to introduce
                                you to the fundamentals of the game. Whether you're completely new to chess or looking to
                                refresh your knowledge, this course will cover everything you need to know to start playing
                                confidently. From understanding how the pieces move to learning basic strategies and
                                tactics, we'll guide you through each step of your chess journey. By the end of the course,
                                you'll have a solid foundation to build upon as you continue to explore the fascinating
                                world of chess.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ======================= why choose us bottom section start ======================= -->
    <div style="background-image: url(/frontend1/assets/images/bg/choose-us-three-bottom-bg-img.png);" class="bg-img">
        <div class="py-110">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon1.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Puzzle assigned
                                </span>
                                <h3 class="fw-normal text-main-600 counter">500+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
                            <span>
                                <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Extra Class
                                </span>
                                <h3 class="fw-normal text-main-600 counter">12+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
                            <span>
                                <img src="/frontend1/tcul-img/icons/age.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Age Group
                                </span>
                                <h3 class="fw-normal text-main-600 counter">5+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="350">
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon4.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Students
                                </span>
                                <h3 class="fw-normal text-main-600 counter">1500+</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ======================= why choose us section start ======================= -->
    {{-- <section class="curriculum-section py-80">
        <div class="container">

            <div class="text-center mb-4">
                <h2 class="fw-bold curriculum-title text-neutral-950">Curriculum</h2>
            </div>

            <div class="row gy-4">

                <!-- Card 1 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img1.png" alt="">
                        <div class="content">
                            <h5>Introduction</h5>
                            <p>
                                Let's get started with an introduction to the board- naming the squares and how to set the
                                board. Learn about the pieces of chess board and their movement, followed by a short review.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img2.png" alt="">
                        <div class="content">
                            <h5>Gaining Material</h5>
                            <p>
                                In this module we cover pawn promotion, draw and how to capture an unprotected piece. Also
                                learn how to gain by exchange.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img3.png" alt="">
                        <div class="content">
                            <h5>Attack</h5>
                            <p>
                                You know that developing is very important in the opening, but how does one win with a lead
                                in development? The key is to "attack". Learn how to create an attack and polish your
                                knowledge with a test review.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img4.png" alt="">
                        <div class="content">
                            <h5>Defence</h5>
                            <p>
                                We have learnt how to attack, but how do we avoid or protect yourself against an undesirable
                                outcome? This module is an introduction to "defense".
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img3.png" alt="">
                        <div class="content">
                            <h5>Special Moves</h5>
                            <p>
                                Get introduced to the first special rule of chess- castling.Halfway through the beginner
                                module- it's now time to learn another special move, called "En passant".
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="col-md-6">
                    <div class="curriculum-card h-100">
                        <img src="/frontend1/assets/images/thumbs/why-choose-us-three-img4.png" alt="">
                        <div class="content">
                            <h5>Mate</h5>
                            <p>
                                Did you know that one side can force your king to the edge of the board? Learn about the
                                double rook checkmate for your winning move! Also learn about the famous "mate" or checkmate
                                move.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section> --}}

    {{-- Curriculum Section --}}
    <section class="py-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12 mt-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold curriculum-title text-neutral-950">Curriculum</h2>
                    </div>
                    <div>
                        <div class="row gy-5">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course1.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Introduction</span>
                                    <p class="fw-normal text-paragraph-500">Let's get started with an introduction to the board-
                                        naming the squares and how to set the board. Learn about the pieces of chess board and
                                        their movement, followed by a short review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Gaining material
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> In this module we cover pawn promotion, draw and
                                        how to capture an unprotected piece. Also learn how to gain by exchange.

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course3.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Attack</span>
                                    <p class="fw-normal text-paragraph-500"> You know that developing is very important in the
                                        opening, but how does one win with a lead in development? The key is to "attack". Learn
                                        how to create an attack and polish your knowledge with a test review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Defence</span>
                                    <p class="fw-normal text-paragraph-500">You know that developing is very important in the
                                        opening, but how does one win with a lead in development? The key is to "attack". Learn
                                        how to create an attack and polish your knowledge with a test review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course5.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Special Move</span>
                                    <p class="fw-normal text-paragraph-500"> Get introduced to the first special rule of chess-
                                        castling.Halfway through the beginner module- it's now time to learn another special
                                        move, called "En passant".</p>
                                </div>
                            </div>


                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course6.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Mate</span>
                                    <p class="fw-normal text-paragraph-500"> Did you know that one side can force your king to
                                        the edge of the board? Learn about the double rook checkmate for your winning move! Also
                                        learn about the famous "mate" or checkmate move.

                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
