@extends('layouts.revamp')
@section('title', 'Online Chess Classes for Beginners - Archer Chess Academy')
@section('description', 'Online chess classes for beginners aged 5+. Learn piece moves, castling, tactics and checkmates with experienced coaches. Book a free trial class.')
@section('head')
    <link rel="canonical" href="https://www.archerchessacademy.com/online-chess-classes-for-beginners">
@endsection
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
           @media (max-width: 767px) {
                .footer .tw-mt-210-px{
                    margin-top:20px;
                }
                .gy-5{
                    --bs-gutter-y: 1.5rem;
                }
                .bg-main-two-300{
                    margin-top:3rem
                }
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
                            <h1 class="text-center tw-mb-6 text-neutral-950">Online Chess Classes for Beginners</h1>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Online Chess Classes for Beginners
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-80 bg-main-50">
        <div class="container text-center">
            <h2 class="fw-bold text-neutral-950 tw-mb-4">Learn Chess Online from Beginner Level</h2>
            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-6">Join a live online beginner chess class with a FIDE-rated coach. See how it works and check the level before you enroll, with no obligation.</p>
            <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-white"
                data-block="button">
                <span class="button__flair"></span>
                <span class="button__label">Book Free Trial Class</span>
                <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="">
                </span>
            </a>
        </div>
    </section>

    <section class="py-80">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-wrap tw-mb-2">
                <div>
                    <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        Beginner Chess Classes for Kids</h2>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-12 col-md-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="w-100 tw-mb-0">
                        <div class="d-flex flex-column tw-gap-3 tw-mb-3">
                            <p class="fw-medium tw-text-5 text-paragraph-500">Never played chess before? Our beginner chess course takes your child from “how does the knight move?” to playing complete games with confidence. Lessons are live and interactive with FIDE-rated coaches, and they follow a curriculum curated by Grandmaster MS Thej Kumar. Classes run on a FIDE Online Recognised Platform in small batches, so every child gets personal attention.</p>
                            <p class="fw-medium tw-text-5 text-paragraph-500">Kids learn the board and pieces, special moves, simple tactics, checkmates and basic endgames, then practise with puzzles and games after every class. By the end, they have the foundation to move up to our intermediate course.</p>
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
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-lg-column">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon1.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Puzzle assigned
                                </span>
                                <h3 class="fw-normal text-main-600 counter numberFont">500+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        <div class="d-flex align-items-start tw-gap-4 animation-item flex-lg-column">
                            <span>
                                <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Extra Class
                                </span>
                                <h3 class="fw-normal text-main-600 counter numberFont">12+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                        <div class="d-flex align-items-start tw-gap-4 animation-item flex-lg-column">
                            <span>
                                <img src="/frontend1/tcul-img/icons/age.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Age Group
                                </span>
                                <h3 class="fw-normal text-main-600 counter numberFont">5+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="350">
                        <div class="d-flex align-items-start tw-gap-4 animation-item flex-lg-column">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon4.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Students
                                </span>
                                <h3 class="fw-normal text-main-600 counter numberFont">1500+</h3>
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
                        <h2 class="fw-bold curriculum-title text-neutral-950">Curriculum at the Beginner Level</h2>
                    </div>
                    <div>
                        <div class="row gy-5">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course1.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Introduction</h3>
                                    <p class="fw-normal text-paragraph-500">Let's get started with an introduction to the board-
                                        naming the squares and how to set the board. Learn about the pieces of chess board and
                                        their movement, followed by a short review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Gaining material</h3>
                                    <p class="fw-normal text-paragraph-500"> In this module we cover pawn promotion, draw and
                                        how to capture an unprotected piece. Also learn how to gain by exchange.

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course3.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Attack</h3>
                                    <p class="fw-normal text-paragraph-500"> You know that developing is very important in the
                                        opening, but how does one win with a lead in development? The key is to "attack". Learn
                                        how to create an attack and polish your knowledge with a test review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Defence</h3>
                                    <p class="fw-normal text-paragraph-500">You know that developing is very important in the
                                        opening, but how does one win with a lead in development? The key is to "attack". Learn
                                        how to create an attack and polish your knowledge with a test review.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course5.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Special Move</h3>
                                    <p class="fw-normal text-paragraph-500"> Get introduced to the first special rule of chess-
                                        castling.Halfway through the beginner module- it's now time to learn another special
                                        move, called "En passant".</p>
                                </div>
                            </div>


                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100 mobileItem">
                                    <img src="/frontend1/tcul-img/icons/course6.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce">
                                    <h3 class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Mate</h3>
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

    <section class="py-80 bg-main-50">
        <div class="container">
            <div class="text-center tw-mb-8">
                <h2 class="fw-bold text-neutral-950">Frequently Asked Questions</h2>
            </div>
            <div class="accordion" id="beginnerCourseFaq">
                @php
                    $beginnerFaqs = [
                        ['Who can join the beginner chess classes?', 'Anyone who is new to chess can join, including children who have never touched a chessboard. Our chess classes for beginners are designed for kids aged 5 to 12, and no prior knowledge or experience is needed. If your child already knows a few moves, our coaches will place them at the right starting point.'],
                        ['Do we need a chessboard or any special equipment?', 'No. A laptop, tablet or phone with a stable internet connection is enough to begin. At Archer Chess Academy our online classes offer interactive digital boards. A physical chessboard is optional, though many children enjoy practising the moves on a real board at home.'],
                        ['Is there a free beginner chess class I can try first?', 'Yes. You can book our free trial class to see how our coaches teach and how your child responds, before you commit to anything. Just click “Book Free Trial Class”, pick a convenient slot, and we will take care of the rest.'],
                        ['How do I choose the best online chess classes for beginners?', '<p>Look for these things:</p><ul><li>Coaches with FIDE ratings and real teaching experience</li><li>A clear, step-by-step curriculum rather than random lessons</li><li>Live, interactive classes instead of pre-recorded videos</li><li>Small groups or one-on-one options, so every child gets attention</li><li>A free trial class, so you can judge the teaching style first</li></ul><p>Archer Chess Academy offers all five, and our curriculum is curated by Grandmaster Thejkumar M. S.</p>'],
                        ['What will my child learn in the beginner course?', 'Your child will learn how to set up the board, move each piece, win material, attack and defend, play special moves like castling and "EN passant", and deliver checkmate. By the end, they can play a complete game with confidence and are ready for the intermediate course.'],
                        ['Are the classes available Worldwide?', 'Yes. Our online chess classes are open to students across globe, and you can join from any city without travelling to a center. Choose a time slot that fits your child’s school schedule and learn from home.'],
                        ['How long does it take to learn chess as a beginner?', 'Most children can learn the rules and play simple games within a few weeks of regular classes. Becoming a confident player takes longer and depends on practice, age and consistency. Our coaches track progress at every stage, and students can move on to our intermediate course when they are ready.'],
                        ['Can my child move to intermediate and advanced classes later?', 'Yes. Our courses follow a clear path from beginner to intermediate to expert. Once your child is comfortable with the basics, our coach will recommend the next level.'],
                    ];
                @endphp
                @foreach ($beginnerFaqs as $index => $faq)
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="beginnerFaqHeading{{ $index }}">
                            <button class="accordion-button {{ $index ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                data-bs-target="#beginnerFaqAnswer{{ $index }}" aria-expanded="{{ $index ? 'false' : 'true' }}"
                                aria-controls="beginnerFaqAnswer{{ $index }}">{{ $faq[0] }}</button>
                        </h3>
                        <div id="beginnerFaqAnswer{{ $index }}" class="accordion-collapse collapse {{ $index ? '' : 'show' }}"
                            aria-labelledby="beginnerFaqHeading{{ $index }}" data-bs-parent="#beginnerCourseFaq">
                            <div class="accordion-body">{!! $faq[1] !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
