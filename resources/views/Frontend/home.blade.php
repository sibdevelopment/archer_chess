@extends('layouts.frontend')
@section('title')
    Best Online Chess Classes for Kids | Archer's Chess Academy
@endsection
@section('content')
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

        /* Force font-display swap for all @font-face */
        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-style: normal;
            font-weight: 900;
            src: url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-solid-900.woff2') format('woff2');
            font-display: swap;
        }

        @font-face {
            font-family: 'Font Awesome 6 Brands';
            font-style: normal;
            font-weight: 400;
            src: url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/webfonts/fa-brands-400.woff2') format('woff2');
            font-display: swap;
        }

        @font-face {
            font-family: 'Feather';
            src: url('/frontend/assets/fonts/Feather144f.ttf?sdxovp') format('truetype');
            font-display: swap;
        }

        .blog-five-item .product-img-five {
            height: 350px;
            overflow: hidden;
            border-radius: 8px;
        }

        .blog-five-item .product-img-five img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }

        .blog-five-footer h3 a {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 3.2em;
            line-height: 1.6em;
            color: #000;
            text-decoration: none;
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

        .hover-shadow:hover {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
        }

        .about-section .about-image {
            background: #fff;
            border: 1px solid #e0ebff;
            box-shadow: 0 4px 34px #e0ebff;
            border-radius: 10px;
            height: 300px;
            -webkit-transition: all 1.5s;
            transition: all 1.5s;
            margin-bottom: 24px;
        }

        .student-widget-group {
            background-image: url(/frontend/tcul_img/webp/breadcrumb-bar.webp) !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .student-widget-group:hover {
            background: #413655;
            color: #fff;
            transition: all .5s ease;
            -moz-transition: all .5s ease;
            -o-transition: all .5s ease;
            -ms-transition: all .5s ease;
            -webkit-transition: all .5s ease;
        }

        .student-widget-group:hover h4 {
            color: #fff;
        }

        .student-widget-group:hover h2 {
            color: #fff;
        }

        .student-widget-group:hover h3 {
            color: #fff;
        }

        .student-widget-group:hover ul li {
            color: #fff;
        }

        .instructor-box {
            position: relative;
            overflow: hidden;
        }

        .instructor-box:hover {
            background-color: #413655 !important;
            color: white !important;
        }

        .instructor-box .coach-info {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white !important;
        }

        .instructor-box:hover .coach-info {
            display: block;
        }

        .instructor-box:hover .instructor-content,
        .instructor-box:hover .instructor-img {
            opacity: 0.3;
        }

        .courses-img {
            width: 100%;
            height: 200px;
            /* Set a fixed height */
            object-fit: cover;
            /* Ensure the image covers the entire area */
        }

        .bad {
            left: 100px;
            top: 50px;
        }

        .good {
            transform: translate(100px, 50px);
        }

        /* Banner section background */
        #banner {
            background: linear-gradient(120deg, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            padding: 80px 0;
        }

        /* Headline with gradient text */
        #banner h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            /* responsive scaling */
            font-weight: 800;
            line-height: 1.2;
            background: linear-gradient(90deg, #ffffff, #d1d1ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        /* Sub text */
        #banner p {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            margin-bottom: 30px;
            color: #f0f0f0;
        }

        /* Book Trial Button */
        .book-trial-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            /* red-pink gradient */
            color: #fff;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .book-trial-btn:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        .book-trial-btn img {
            width: 28px;
            height: auto;
        }

        /* Banner image animation */
        .girl-slide-img img {
            max-width: 100%;
            height: auto;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Mobile friendly alignment */
        @media (max-width: 768px) {
            #banner {
                text-align: center;
                padding: 120px 30px 20px;
            }

            .book-trial-btn {
                margin: auto;
            }

            .girl-slide-img {
                margin-top: 30px;
            }
        }
    </style>

    <script>
        function safeUpdate(callback) {
            requestAnimationFrame(callback);
        }
        safeUpdate(() => {
            // Example: update slider item
            document.querySelector(".slide").style.transform = "translateX(100px)";
        });
        let scrollTimer;
        window.addEventListener("scroll", () => {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                safeUpdate(() => {
                    // put your scroll code here
                });
            }, 100);
        });
    </script>

    <section id="banner" class="home-slide d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-md-7">
                    <div class="home-slide-face aos">
                        <div class="home-slide-text">
                            <h1>From Beginner to Pro – Online Chess Classes for All Levels.</h1>
                            <p>Join Archer’s online chess classes and take your game to the next level with expert guidance.
                            </p>
                        </div>

                        <!-- CTA -->
                        <div class="mt-3">
                            <a href="#trail_form" class="book-trial-btn">
                                <img fetchpriority="high" loading="eager" src="/frontend/assets/img/icon/icon-07.svg"
                                    alt="Icon">
                                Book Your Free Trial
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="col-md-5 text-center">
                    <div class="girl-slide-img aos">
                        <img fetchpriority="high" loading="eager" src="/frontend/tcul_img/webp/home/home-banner-0.webp"
                            alt="Chess Student">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section student-course">
        <div class="container">
            <div class="course-widget">
                <div class="row text-center">
                    <div class="col-lg-3 col-md-6 col-6 ">
                        <div class="course-full-width">
                            <div class="blur-border course-radius align-items-start">
                                <a href="#course">
                                    <div class="online-course align-items-center justify-content-center feature_course">
                                        <div class="course-img text-center">
                                            <img class="feature_img" src="/frontend/assets/img/pencil-icon.svg"
                                                alt="Img">
                                        </div>
                                        <div class="course-inner-content pb-2" style="margin-left: 0px !important">
                                            <h4>Curriculum</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex col-6 ">
                        <div class="course-full-width">
                            <div class="blur-border course-radius">
                                <a href="#pricing">
                                    <div class="online-course align-items-center justify-content-center  feature_course">
                                        <div class="course-img text-center">
                                            <img class="feature_img" src="/frontend/assets/img/cources-icon.svg"
                                                alt="Img">
                                        </div>
                                        <div class="course-inner-content pb-2" style="margin-left: 0px !important">
                                            <h4>Courses</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex col-6 ">
                        <div class="course-full-width">
                            <div class="blur-border course-radius">
                                <a href="#tutor">
                                    <div class="online-course align-items-center justify-content-center feature_course">
                                        <div class="course-img text-center">
                                            <img class="feature_img" src="/frontend/assets/img/certificate-icon.svg"
                                                alt="Img">
                                        </div>
                                        <div class="course-inner-content pb-2" style="margin-left: 0px !important">
                                            <h4>Tutors</h3>
                                                <p></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex col-6 col-sm-6 ">
                        <div class="course-full-width">
                            <div class="blur-border course-radius">
                                <a href="#kid">
                                    <div class="online-course align-items-center justify-content-center feature_course">
                                        <div class="course-img text-center">
                                            <img class="feature_img" src="/frontend/assets/img/gratuate-icon.svg"
                                                alt="Img">
                                        </div>
                                        <div class="course-inner-content pb-2" style="margin-left: 0px !important">
                                            <h4><span class="banner-counter">Meet</span> Our Kids</h3>
                                                <p></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- -------------------------------------------------------------------------------------------------- :: -->

    <section class="share-knowledge-five">
        <div class="container">
            <div class="row align-items-center">
                <div class="section-header aos" data-aos="fade-up">
                    <div class="section-sub-head mb-4">
                        <span>What’s New</span>
                        <h2>Meet Our Experts </h2>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 aos-init aos-animate" data-aos="fade-down">
                    <div class="section-five-sub">
                        <div class="header-five-title">
                            <h2>Grandmaster MS Thejkumar</h2>
                        </div>
                        <div class="career-five-content">
                            <p>GM. Thejkumar is a distinguished chess Grandmaster with a career highlighted by
                                significant
                                national and international achievements. His tactical brilliance and deep understanding of
                                chess
                                have led to numerous victories in prestigious tournaments, earning him widespread
                                recognition.
                                Beyond his competitive success, he has made a lasting impact as a mentor, guiding and
                                developing
                                young talent within the chess community. His contributions have been honored with various
                                awards, reflecting his influence and dedication to the sport. GM. Thejkumar's legacy extends
                                beyond his own achievements, as he continues to shape the future of chess through his
                                mentorship
                                and leadership.</p>
                        </div>
                        <div class="knowledge-list-five">
                            <ul>
                                <li>
                                    <div class="knowledge-list-group">
                                        <img src="/frontend/assets/img/icon/award-new.svg" alt="Img">
                                        <p>Eklavya award winner</p>
                                    </div>
                                </li>
                                <li class="mb-0">
                                    <div class="knowledge-list-group">
                                        <img src="/frontend/assets/img/icon/award-new.svg" alt="Img">
                                        <p>1st grandmaster of state of Karnataka</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <a href="/#trail_form" class="learn-more-five book-trial-btn">Start Learning Today</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 aos-init aos-animate" data-aos="fade-down">
                    <div class="joing-count-five text-center">
                        <img src="/frontend/tcul_img/webp/expert_1.webp" alt="Img">
                        <div class="joing-count-five-one course-count">
                            <h3 class="joing-count-number"><span class="">2600</span><span>+</span></h3>
                            <p class="joing-count-text">Rating</p>
                        </div>
                        <div class="joing-count-five-three course-count">
                            <h3 class="joing-count-number"><span class="">100</span><span>+</span></h3>
                            <p class="joing-count-text">Tournaments</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-6 col-md-6 col-sm-12 mt-5 aos-init aos-animate" data-aos="fade-down">
                    <div class="joing-count-five text-center">
                        <img src="/frontend/tcul_img/webp/expert_2.webp" alt="Img">
                        <div class="joing-count-five-one course-count">
                            <h3 class="joing-count-number"><span class="counterUp">2300</span><span>+</span></h3>
                            <p class="joing-count-text">Rating</p>
                        </div>
                        <div class="joing-count-five-three course-count">
                            <h3 class="joing-count-number"><span class="counterUp">100</span><span>+</span></h3>
                            <p class="joing-count-text">Tournaments</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 mt-5 aos-init aos-animate" data-aos="fade-down">
                    <div class="section-five-sub">
                        <div class="header-five-title">
                            <h2>Fide master Joydeep datta</h2>
                        </div>
                        <div class="career-five-content">
                            <p>Joydeep Dutta is a FIDE Master with a peak rating of 2355 and over a decade of
                                experience in
                                coaching chess. He has a proven competitive track record, including numerous prestigious
                                titles
                                such as the West Bengal Open State Chess Championship and the Asian University Championship.
                                Joydeep has successfully mentored top players, including Grandmasters and International
                                Masters.
                                He is skilled in creating tailored training programs and interactive learning environments,
                                with
                                a focus on student engagement and development. Fluent in English, Bengali, and Hindi, he
                                combines strong communication skills with deep expertise in chess strategy, tournament
                                preparation, and curriculum design.</p>
                        </div>
                        <div class="knowledge-list-five">
                            <ul>
                                <li>
                                    <div class="knowledge-list-group">
                                        <img src="/frontend/assets/img/icon/award-new.svg" alt="Img">
                                        <p>Mentor to 2 Grandmasters and 2 International Masters</p>
                                    </div>
                                </li>
                                <li class="mb-0">
                                    <div class="knowledge-list-group">
                                        <img src="/frontend/assets/img/icon/award-new.svg" alt="Img">
                                        <p>Multiple prestigious titles, including 40+ State Championships</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <a href="/#trail_form" class="learn-more-five book-trial-btn">Start Learning Today</a>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    <section id="whyarcher" class="section how-it-works"
        style="background: #2d0160; position: relative; padding: 40px 0 !important;">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="section-sub-head">
                    <span style="color: #fff !important;">Innovative Learning</span>
                    <h2 style="color: #ffdb20 !important;">Why Archer ?</h2>
                </div>
            </div>
            <div class="section-text aos" data-aos="fade-up" style="max-width: 1200px !important;">
                <p class="fs-13" style="color: #fff; font-weight: 400; font-size: 18px;">Utilizing a decade of
                    experience, we have created online chess classes and a support platform catering to
                    children between the ages of 4 to 15. Our team of hand-picked, monitored, and extensively trained
                    Coaches ensures the provision of exceptional service, with all Coaches being certified professionals.
                    Through our Remote Learning program, you can access our interactive and practical tutoring sessions from
                    anywhere, using your desktop, tablet, or mobile phone. Our chess coaching has consistently yielded
                    results, with nearly all our students surpassing expected performance levels upon completing their
                    sessions with us.</p>
            </div>
            <div class="owl-carousel mentoring-course owl-theme aos" data-aos="fade-up"
                style="margin-top: 20px !important;">
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/world-map.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class="feature-text">Play & Learn Global</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/FIDE-rated.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class="feature-text">FIDE-Rated Trainers</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/money.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class="feature-text">Affordable Price</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/coaching.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class="feature-text">Individual Coaching</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/group-coaching.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class="feature-text">Group Coaching</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/chess-board.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class=""
                                    style="font-size: 18px;
                            font-weight: 700;
                            padding-top: 25px;
                            transition: all .5s ease;
                            -moz-transition: all .5s ease;
                            -o-transition: all .5s ease;
                            -ms-transition: all .5s ease;
                            -webkit-transition: all .5s ease;
                            margin: 0 auto 15px;">
                                    Attractive Practical <br> Platform</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/home/three-medal.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class=""
                                    style="font-size: 18px;
                            font-weight: 700;
                            padding-top: 25px;
                            transition: all .5s ease;
                            -moz-transition: all .5s ease;
                            -o-transition: all .5s ease;
                            -ms-transition: all .5s ease;
                            -webkit-transition: all .5s ease;
                            margin: 0 auto 15px;">
                                    District and State <br> Preparation</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feature-box text-center ">
                    <div class="feature-bg">
                        <div class="feature-header">
                            <div class="feature-icon" style="width: 120px !important; height: 120px !important;">
                                <img src="/frontend/tcul_img/webp/international-prepration.webp" alt="Img">
                            </div>
                            <div class="feature-cont">
                                <div class=""
                                    style="font-size: 18px;
                            font-weight: 700;
                            padding-top: 25px;
                            transition: all .5s ease;
                            -moz-transition: all .5s ease;
                            -o-transition: all .5s ease;
                            -ms-transition: all .5s ease;
                            -webkit-transition: all .5s ease;
                            margin: 0 auto 15px;">
                                    National and International <br> Preparation</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="course" class=" section trend-course" style="padding: 40px 0 !important;">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="section-sub-head">
                    <span>Unleash Your Potential</span>
                    <h2>Curriculum</h2>
                </div>
            </div>
            <div class="section-text aos pb-4" data-aos="fade-up">
                <p class="mb-0">Explore our carefully curated courses designed to elevate your chess skills, whether
                    you're
                    a beginner or an aspiring master. Each course is tailored to ensure maximum learning and development.
                </p>
            </div>
            <div class="row">
                <div class="col-6 col-xxl-3 col-md-3 d-flex px-1">
                    <div class="course-box flex-fill">
                        <div class="product">
                            <div class="product-img">
                                <a href="/online-chess-course-for-beginners">
                                    <img class="img-fluid courses-img" alt="Img"
                                        src="/frontend/tcul_img/webp/home/home-course-1.webp">
                                </a>
                                <div class="price">
                                    <h3>Explore in Details</h3>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3 class="title instructor-text">
                                    <a href="/online-chess-course-for-beginners">Beginners</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xxl-3 col-md-3 d-flex px-1">
                    <div class="course-box flex-fill">
                        <div class="product">
                            <div class="product-img">
                                <a href="/online-chess-course-for-intermediate">
                                    <img class="img-fluid courses-img" alt="Img"
                                        src="/frontend/tcul_img/webp/home/home-course-2.webp">
                                </a>
                                <div class="price">
                                    <h3>Explore in Details</h3>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3 class="title instructor-text">
                                    <a href="/online-chess-course-for-intermediate">Intermediate</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xxl-3 col-md-3 d-flex px-1">
                    <div class="course-box flex-fill">
                        <div class="product">
                            <div class="product-img">
                                <a href="/online-chess-course-for-advanced">
                                    <img class="img-fluid courses-img" alt="Img"
                                        src="/frontend/tcul_img/webp/home/home-course-3.webp">
                                </a>
                                <div class="price">
                                    <h3>Explore in Details</h3>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3 class="title instructor-text">
                                    <a href="/online-chess-course-for-advanced">Advanced</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xxl-3 col-md-3 d-flex px-1">
                    <div class="course-box flex-fill">
                        <div class="product">
                            <div class="product-img">
                                <a href="/online-chess-course-for-expert">
                                    <img class="img-fluid courses-img" alt="Img"
                                        src="/frontend/tcul_img/webp/home/home-course-4.webp">
                                </a>
                                <div class="price">
                                    <h3>Explore in Details</h3>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3 class="title instructor-text">
                                    <a href="/online-chess-course-for-expert">Expert</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section :: -->
    {{-- @if ($country !== 'UK' && $country !== 'AUSTRALIA') --}}
        <section class="section trend-course" style="padding: 40px 0; background:none;  background: #2d0160;"
            id="dynamic-course-pricing">
        </section>
    {{-- @endif --}}

    <!-- Achievements Section :: -->
    <section id="achievement" class="about-section section how-it-works trend-course">
        <div class="container">
            <div class="section-header aos justify-content-center" data-aos="fade-up">
                <div class="section-sub-head text-center">
                    <span>Proven Success</span>
                    <h2>Achievements</h2>
                </div>
            </div>
            <div class="section-text aos text-center" data-aos="fade-up" style="max-width: 100%;">
                <p>Over the years, our students have achieved remarkable success, consistently outperforming their peers in
                    tournaments and chess rankings. Our dedication to excellence is reflected in the numerous awards and
                    accolades our students have garnered.</p>
            </div>

            <div class="enroll-group aos aos-init aos-animate my-5" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="total-course d-flex align-items-center">
                            <div class="blur-border">
                                <div class="enroll-img ">
                                    <img src="/frontend/assets/img/icon/icon-07.svg" alt="Img" class="img-fluid">
                                </div>
                            </div>
                            <div class="course-count">
                                <h3>5,000+</h3>
                                <p>TRAINED STUDENTS</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="total-course d-flex align-items-center">
                            <div class="blur-border">
                                <div class="enroll-img ">
                                    <img src="/frontend/assets/img/icon/icon-08.svg" alt="Img" class="img-fluid">
                                </div>
                            </div>
                            <div class="course-count">
                                <h3>52+</h3>
                                <p>FIDE RATED</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="total-course d-flex align-items-center">
                            <div class="blur-border">
                                <div class="enroll-img ">
                                    <img src="/frontend/assets/img/icon/icon-09.svg" alt="Img" class="img-fluid">
                                </div>
                            </div>
                            <div class="course-count">
                                <h3>8+</h3>
                                <p>COUNTRIES</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="total-course d-flex align-items-center">
                            <div class="blur-border">
                                <div class="enroll-img ">
                                    <img src="/frontend/assets/img/icon-three/award.svg" alt="certified">
                                </div>
                            </div>
                            <div class="course-count">
                                <h3>100+</h3>
                                <p>CHAMPIONS</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- -------------------------------------------------------------------------------------------------- :: -->


    <!-- Tutors Section :: -->
    <section class="blogs-section-five" id="tutor">
        <div class="container">
            <div class="section-sub-head mb-5">
                <span>Where Learning Thrives</span>
                <h2>Meet Our Tutors</h2>
            </div>
            <div class="owl-carousel home-five-blog owl-theme">
                @foreach ($meetourtutors as $tutor)
                    <div class="blog-five-item">
                        <div class="product-img-five">
                            <a href="#">
                                <img class="img-fluid" alt="Img" src="{{ Storage::url($tutor->image) }}">
                            </a>
                        </div>
                        <div class="blog-box-content" style="padding: 12px !important;">
                            <div class="blog-five-header d-flex align-items-center justify-content-between">
                                <div class="blog-five-text">
                                    <p>{{ $tutor->name }}</p>
                                </div>
                            </div>
                            <div class="info-five-middle"
                                style="padding-bottom: 10px !important; margin-bottom: 10px !important; border-bottom: 0px solid #f1f1f1 !important;">
                                <div class="rating-img">
                                    {{-- <span class="me-2"><i class="fa-solid fa-book-open"></i></span> --}}
                                    <p>{{ $tutor->designation }}</p>
                                </div>
                                <div class="course-view-five align-items-center">
                                    <span class=""><a href="#"><i
                                                class="fa-regular fa-clockss"></i></a></span>
                                    <p>{{ $tutor->rating }}</p> &nbsp;
                                    <div class="blog-five-year">
                                        <div class="course-share-five">
                                            <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--
                            <div class="blog-five-footer">
                                <p class="mb-0" style="min-height: 200px !important;"><b> Dr.Sunayana</b> a motivated and goal-oriented Chess Instructor, finds that coaching helps
                                    her understand and organize her knowledge of chess in a clear way. She enjoys explaining
                                    essential concepts to children and finds joy in sharing her understanding and enjoyment of
                                    chess.
                                </p>
                            </div>
                            --}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- -------------------------------------------------------------------------------------------------- :: -->


    <!-- Testimonial Section ::-->

    @if (desktop())
        <section class="testimonial-section-five">
            <div class="container">
                <div class="header-five-title text-center" data-aos="fade-down">
                    <h2 class="text-warning">They Trusted us</h2>
                    <p class="text-white">We are a very happy because we have a happy customer </p>
                </div>
                <div class="testimonial-slider-five">
                    <div class="testimonial-five lazy slider">
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"Under ACA’s superb guidance, my son got an international Rating. Thanks
                                            to team ACA.
                                            My Devesh has been learning with ACA last 5 years and won so many
                                            tournaments."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Devesh’s Mom</h5>
                                            <p>Mumbai</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/devesh.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"It’s awesome. Initially, I thought only for two months, and I didn’t know
                                            whether my
                                            kid would like this or not. But now she has been learning for the last
                                            year and
                                            really enjoying the class and wants to continue till her goal. The
                                            Sunday tournament
                                            is a highlight, and it really helps kids to improve their gaming. The
                                            teaching and
                                            explanation of the class are awesome, and she is enjoying it."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Architha FIDE Rated Player</h5>
                                            <p>Prayagraaj</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/male.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"It is awesome to connect with your academy, feeling more comfortable and
                                            logical than others. Thanks to Archer Academy 🙏🏻 "</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Agharsh Raam</h5>
                                            <p>Bengaluru</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/aagarsh-ram.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"We are very happy with your immense dedication and support from the very
                                            first day. Aarav has improved a lot. Hoping the same in future too.Thank
                                            you, team ACA"</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5> Aarav and Abhitha</h5>
                                            <p>Dubai</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/male.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My Sanchi has been Learning Chess with ACA since she was 6 years and won
                                            Mumbai District Championship Under 6 Category. She has also won so many
                                            tournaments in his category and the interschool Chess Championship 2022,
                                            all thanks to ACA! Now She is preparing for Fide Rated Tournament, and
                                            ACA gives all details of the official tournament and guides from time to
                                            time."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Sanchi Mom</h5>
                                            <p>Mumbai</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/sanchi.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My son Raghul is only 6 years, and now he has won so many tournaments in
                                            his category and wants to become Grandmaster, all thanks to ACA! Regular
                                            classes, practice tournaments, and a structured curriculum is the
                                            opportunity to approach learning chess in the best way possible."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Raghul's Mom</h5>
                                            <p>Chennai</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/raghul.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My Reyansh Started Learning Chess with ACA during 2nd lockdown within one
                                            year. He got his FIDE rating and won so many tournaments. Thanks to Team
                                            ACA."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Reyansh's Mom</h5>
                                            <p>Surat</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/reyansh-patel.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"The instructors have been incredibly knowledgeable and skilled, and they
                                            have done an amazing job of conveying their expertise in a way that is
                                            easy to understand and follow. The lessons are well-planned and
                                            organized, and the materials provided are informative and helpful."</p>
                                    </div>
                                    <div class="testimonial-users-group d-flex align-items-center justify-content-between">
                                        <div class="testimonial-users">
                                            <h5>Thirth Kodre's father</h5>
                                            <p>Pune</p>
                                        </div>
                                        <div class="testimonial-ratings-five d-inline-flex align-items-center">
                                            <div class="rating" data-rating="4.8">4.8 <span>ratings</span></div>
                                            {{-- <div class="rating">
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star filled"></i>
                                                <i class="fas fa-star"></i>
                                                <p class="d-inline-block">4.8<span>ratings</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-image">
                                    <img src="/frontend/tcul_img/webp/review/tirth-kodre.webp" alt="Img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (mobile())
        <section class="testimonial-section-five">
            <div class="container">
                <div class="header-five-title text-center" data-aos="fade-down">
                    <h2 class="text-warning">They Trusted us</h2>
                    <p class="text-white">We are a very happy because we have a happy customer </p>
                </div>
                <div class="testimonial-slider-five">
                    <div class="testimonial-five lazy slider">
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"Under ACA’s superb guidance, my son got an international Rating. Thanks
                                            to team ACA.
                                            My Devesh has been learning with ACA last 5 years and won so many
                                            tournaments."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/devesh.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Devesh’s Mom</h5>
                                                        <p>Mumbai</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"It’s awesome. Initially, I thought only for two months, and I didn’t know
                                            whether my
                                            kid would like this or not. But now she has been learning for the last
                                            year and
                                            really enjoying the class and wants to continue till her goal. The
                                            Sunday tournament
                                            is a highlight, and it really helps kids to improve their gaming. The
                                            teaching and
                                            explanation of the class are awesome, and she is enjoying it."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/male.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Architha FIDE Rated Player</h5>
                                                        <p>Prayagraaj</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"It is awesome to connect with your academy, feeling more comfortable and
                                            logical than others. Thanks to Archer Academy 🙏🏻 "</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/aagarsh-ram.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Agharsh Raam</h5>
                                                        <p>Bengaluru</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"We are very happy with your immense dedication and support from the very
                                            first day. Aarav has improved a lot. Hoping the same in future too.Thank
                                            you, team ACA"</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/male.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5> Aarav and Abhitha</h5>
                                                        <p>Dubai</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My Sanchi has been Learning Chess with ACA since she was 6 years and won
                                            Mumbai District Championship Under 6 Category. She has also won so many
                                            tournaments in his category and the interschool Chess Championship 2022,
                                            all thanks to ACA! Now She is preparing for Fide Rated Tournament, and
                                            ACA gives all details of the official tournament and guides from time to
                                            time."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/sanchi.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Sanchi Mom</h5>
                                                        <p>Mumbai</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                        <p class="d-flex justify-content-end">4.5<span>ratings</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My son Raghul is only 6 years, and now he has won so many tournaments in
                                            his category and wants to become Grandmaster, all thanks to ACA! Regular
                                            classes, practice tournaments, and a structured curriculum is the
                                            opportunity to approach learning chess in the best way possible."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/raghul.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Raghul's Mom</h5>
                                                        <p>Chennai</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                        <p class="d-flex justify-content-end">4.5<span>ratings</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"My Reyansh Started Learning Chess with ACA during 2nd lockdown within one
                                            year. He got his FIDE rating and won so many tournaments. Thanks to Team
                                            ACA."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/reyansh-patel.webp"
                                                    alt="Img" class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Reyansh's Mom</h5>
                                                        <p>Surat</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                        <p class="d-flex justify-content-end">4.5<span>ratings</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-carousel">
                            <div class="testimonial-item">
                                <div class="testimonial-content-five">
                                    <div class="testimonial-text">
                                        <p>"The instructors have been incredibly knowledgeable and skilled, and they
                                            have done an amazing job of conveying their expertise in a way that is
                                            easy to understand and follow. The lessons are well-planned and
                                            organized, and the materials provided are informative and helpful."</p>
                                    </div>
                                    <div class="testimonial-users-group">
                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-12 responsive-img">
                                                <img src="/frontend/tcul_img/webp/review/tirth-kodre.webp" alt="Img"
                                                    class="br-50 mw-20">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="testimonial-users">
                                                        <h5>Thirth Kodre's father</h5>
                                                        <p>Pune</p>
                                                    </div>
                                                    <div class="testimonial-ratings-five">
                                                        <div class="rating" data-rating="4.5">4.5 <span>ratings</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="section become-instructors aos" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 col-md-6 d-flex">
                    <div class="student-mentor cube-instuctor ">
                        <h4>Unlock Your Potential</h4>
                        <div class="row">
                            <div class="col-lg-7 col-md-12">
                                <div class="top-instructors">
                                    <p>Learn from top instructors and gain access to a world of knowledge on Mentoring.</p>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12">
                                <div class="mentor-img">
                                    <img class="img-fluid" alt="Img" src="/frontend/tcul_img/webp/become-02.webp">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-md-6 d-flex">
                    <div class="student-mentor yellow-mentor">
                        <h4>Experience Learning Like Never Before</h4>
                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <div class="top-instructors">
                                    <p>Enquire now or book a demo session to experience our courses firsthand. Decide
                                        whether you want to join the course after the demo session.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="mentor-img">
                                    <img class="img-fluid" alt="Img" src="/frontend/tcul_img/webp/become-01.webp">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                                        <img class="img-fluid" loading="lazy" alt="Img"
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

    <div class="page-content pt-4" id="trail_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 mx-auto">
                    <div class="support-wrap trend-course" id=""
                        style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;">
                        <div class="chess-form">
                            <div class="form-header mb-3">
                                <i class="fas fa-chess-knight chess-icon"></i>
                                <h5 class="mb-0">Book a Free Trial</h5>
                                <i class="fas fa-chess-rook chess-icon"></i>
                            </div>
                            <p class="text-center">
                                Welcome! Please fill out this form to confirm your booking.<br>
                                <span style="color:red; font-weight:600;">Note:</span> We will only communicate by
                                <b>WhatsApp</b>, so please
                                provide your WhatsApp number.
                            </p>

                            <form method="POST" enctype="multipart/form-data" autocomplete="off"
                                id="confirmbooking-form">
                                @csrf
                                <div class="row">
                                    <!-- Country -->
                                    <div class="col-md-6">
                                        <div class="input-block">
                                            <label for="country"><i class="fas fa-globe-americas chess-icon"></i>
                                                Country*</label>
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
                                                <option>EUROPEAN UNION</option>
                                                <option>OMAN</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- country code hiden --}}
                                    <input type="hidden" id="booktrial_country_code" name="country_code" value="">
                                    <!-- Timezone -->
                                    <div class="col-md-6">
                                        <div class="input-block">
                                            <label for="timezone"><i class="fas fa-clock chess-icon"></i>
                                                Timezone*</label>
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
                                            <label for="kids_first_name"><i class="fas fa-user chess-icon"></i> Kid's Full
                                                Name*</label>
                                            <input type="text" class="form-control" id="kids_first_name"
                                                name="kids_first_name" placeholder="Enter kid's full name">
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
                                            <label for="phone"><i class="fab fa-whatsapp chess-icon"></i> WhatsApp
                                                Number*</label>
                                            <input type="tel" class="form-control" id="phone" name="mobile"
                                                placeholder="Enter WhatsApp number">
                                            <div id="trial_mobile-error" style="color:red"></div>
                                            <div id="mobile-error" style="color:red"></div>
                                        </div>
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="input-block">
                                            <label for="email"><i class="fas fa-envelope chess-icon"></i>
                                                Email*</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter your email">
                                        </div>
                                    </div>
                                    <!-- Language -->
                                    <div class="col-md-6">
                                        <div class="input-block">
                                            <label for="language_preference"><i class="fas fa-language chess-icon"></i>
                                                Language
                                                Preference*</label>
                                            <select class="form-control" id="language_preference"
                                                name="language_preference">
                                                <option value="" disabled selected>Select language preference
                                                </option>
                                                <option value="agree">Agree (English)</option>
                                                <option value="not_comfortable">Kid is not comfortable in English</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button class="btn btn-chess">
                                        <i class="fas fa-chess-king me-2"></i> Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="blogs-section-five">
        <div class="container">
            <div class="header-five-title text-center" data-aos="fade-down">
                <h2>Latest Blogs</h2>
                <p>Follow the latest and most useful articles on that student's blog</p>
            </div>
            <div class="owl-carousel home-five-blog owl-theme">
                @foreach ($blogs as $blog)
                    <div class="blog-five-item">
                        <div class="product-img-five">
                            <a href="/blog/{{ $blog->slug }}">
                                <img loading="lazy" class="img-fluid" alt="Img"
                                    src="{{ asset(Storage::url($blog->cover_img)) }}">
                            </a>
                        </div>
                        <div class="blog-box-content">
                            <div class="blog-five-header d-flex align-items-center justify-content-between">
                                <!-- <div class="blog-five-text">
                                                                                                                                                                        <p>Graphical Design</p>
                                                                                                                                                                    </div> -->
                                <div class="blog-five-year">
                                    <span class="me-2"><i class="fa-solid fa-calendar-days"></i></span>
                                    <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="blog-five-footer">
                                <h3><a href="/blog/{{ $blog->slug }}">{{ $blog->title }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQs Section :: -->
    <div id="faq" class="help-sec" style="padding: 40px 0 !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="help-title">
                        <h1>Most frequently asked questions</h1>
                        <p>Here are the most frequently asked questions you may check before getting started</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqone" aria-expanded="false">How do I
                                enroll my child in a course at Archer Chess Academy?</a>
                        </h6>
                        <div id="faqone" class="collapse">
                            <div class="faq-detail">
                                <p>Enrollment is simple. Just visit our website, choose the course level that fits your
                                    child, and fill out the registration form. Our team will guide you step by step to get
                                    started.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqtwo" aria-expanded="false">Do you
                                offer a trial class before enrolling?</a>
                        </h6>
                        <div id="faqtwo" class="collapse">
                            <div class="faq-detail">
                                <p>Yes, absolutely. We understand parents want to be sure before committing. That’s why we
                                    offer a free demo class so you and your child can experience our teaching style
                                    firsthand.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqthree" aria-expanded="false">What
                                makes Archer Chess Academy different from other academies?</a>
                        </h6>
                        <div id="faqthree" class="collapse">
                            <div class="faq-detail">
                                <p>We don’t just teach moves—we build thinkers. Our structured programs focus on tactics,
                                    strategy, and decision-making skills, while also making learning fun and engaging. Each
                                    child receives personal attention, even in group classes.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqfour" aria-expanded="false">How are
                                the coaches selected?</a>
                        </h6>
                        <div id="faqfour" class="collapse">
                            <div class="faq-detail">
                                <p>All our tutors are FIDE-rated players with proven coaching experience. Beyond chess
                                    skills, we look for passion, patience, and the ability to connect with students of all
                                    ages.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqfive" aria-expanded="false">What
                                age groups can join your academy?</a>
                        </h6>
                        <div id="faqfive" class="collapse">
                            <div class="faq-detail">
                                <p>We welcome learners from 5 years old all the way to adults. Our teaching style and
                                    curriculum are tailored to the student’s age and skill level.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqsix" aria-expanded="false">Are the
                                classes online or offline?</a>
                        </h6>
                        <div id="faqsix" class="collapse">
                            <div class="faq-detail">
                                <p>Currently, we offer **live online sessions**, making it easy for students across India
                                    and abroad to join. For special events and tournaments, we sometimes organize offline
                                    workshops.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqseven" aria-expanded="false">Will
                                my child get enough attention in group classes?</a>
                        </h6>
                        <div id="faqseven" class="collapse">
                            <div class="faq-detail">
                                <p>Yes. Our groups are intentionally kept small so every student gets the guidance they
                                    need. If you prefer, we also offer **one-on-one sessions** for focused learning.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqeight" aria-expanded="false">How do
                                you track a student’s progress?</a>
                        </h6>
                        <div id="faqeight" class="collapse">
                            <div class="faq-detail">
                                <p>We track progress through regular assessments, puzzle-solving, and game analysis. Parents
                                    receive updates and reports so they can follow their child’s growth.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqnine" aria-expanded="false">Do you
                                prepare students for tournaments?</a>
                        </h6>
                        <div id="faqnine" class="collapse">
                            <div class="faq-detail">
                                <p>Yes, we do. We train students not only in chess skills but also in tournament preparation
                                    — including opening repertoire, time management, and handling pressure during games.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqten" aria-expanded="false">What
                                happens if we miss a class?</a>
                        </h6>
                        <div id="faqten" class="collapse">
                            <div class="faq-detail">
                                <p>No worries. We provide class recordings for revision, and in certain cases, we arrange a
                                    **make-up session** depending on the course package.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqeleven" aria-expanded="false">How
                                long does it take to see improvement in chess?</a>
                        </h6>
                        <div id="faqeleven" class="collapse">
                            <div class="faq-detail">
                                <p>Every child is unique. Some start showing results in a few weeks, while others may take
                                    months. What we guarantee is consistent improvement if students practice regularly and
                                    attend sessions sincerely.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqtwelve" aria-expanded="false">Can
                                parents sit in during classes?</a>
                        </h6>
                        <div id="faqtwelve" class="collapse">
                            <div class="faq-detail">
                                <p>We encourage parents to attend the first demo or initial sessions. Later, we suggest
                                    giving the child independent space to build confidence and focus.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqthirteen" aria-expanded="false">Do
                                you offer international coaching?</a>
                        </h6>
                        <div id="faqthirteen" class="collapse">
                            <div class="faq-detail">
                                <p>Yes. Since our classes are online, we coach students worldwide. Our flexible timings make
                                    it easier for international learners to join.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqthirfourteen"
                                aria-expanded="false">What life skills can chess give my child?</a>
                        </h6>
                        <div id="faqthirfourteen" class="collapse">
                            <div class="faq-detail">
                                <p>Chess teaches patience, critical thinking, problem-solving, focus, and responsibility for
                                    one’s decisions. These skills go beyond the board and help children in academics and
                                    life.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqfifteen" aria-expanded="false">How
                                do I get in touch if I have more questions?</a>
                        </h6>
                        <div id="faqfifteen" class="collapse">
                            <div class="faq-detail">
                                <p>You can contact us anytime through the **Contact Us** form on our website, email, or
                                    WhatsApp. Our support team responds quickly to assist parents and students.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-card">
                        <h6 class="faq-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqsixteen" aria-expanded="false">Do
                                Grandmasters and titled players in Archer Chess Academy only add branding, or do they also
                                teach?</a>
                        </h6>
                        <div id="faqsixteen" class="collapse">
                            <div class="faq-detail">
                                <p>At Archer Chess Academy, our Grandmasters and titled coaches are not just for branding.
                                    They actively take sessions, conduct workshops, analyze games, and mentor students
                                    directly. Their guidance gives students exposure to world-class chess insights, while
                                    our regular coaches handle consistent day-to-day learning and practice.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------------------------------------------- :: -->

    <div class="modal fade" id="user-enquiry-modal" tabindex="-1" aria-labelledby="user-enquiry-label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg rounded-3">

                <!-- Modal Header (optional) -->
                <div class="modal-header border-0">
                    <!-- Optional close button -->
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-3">
                    <h2 class="text-center fw-bold mb-2 text-primary">Welcome!</h2>
                    <p class="text-center text-muted mb-2">Please enter your name and mobile number to continue.</p>
                    <form id="enquirypopup-form" action="{{ route('enquiry.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-1">
                            <div class="col-6">
                                <label for="feesCountry" class="form-label fw-semibold"> Country</label>
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
                                    <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                                    <option value="OMAN">OMAN</option>
                                </select>
                                <div id="country-error" class="text-danger mt-1">
                                </div>
                            </div>
                            <input type="hidden" name="country_code" id="mobilepopup_country_code" value="">

                            <div class="col-6">
                                <label for="feesTimezone" class="form-label fw-semibold"> Timezone</label>
                                <select id="feesTimezone" class="form-select" name="timezone">
                                    <option value="">-- Select Timezone --</option>
                                </select>
                                <div id="timezone-error" class="text-danger mt-1">
                                </div>
                            </div>
                             <div class="col-12">
                                <label for="enquiry-name" class="form-label fw-semibold">Name</label>
                                <input type="text" name="first_name" class="form-control"
                                    placeholder="Enter your full name">
                                <div id="first_name-error" class="text-danger mt-1"></div>
                            </div>
                            <div class="mb-3">
                                <label for="mobilePopup" class="form-label fw-semibold">Mobile</label>
                                <input type="tel" id="mobilePopup" name="mobilePopup" class="form-control"
                                    placeholder="Enter your mobile number">
                                <div id="mobilePopup-error" class="text-danger mt-1"></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-50"
                                    style="background-color: #0d6efd !important; border: 1px solid #0d6efd;">Submit</button>
                                <button type="button" class="btn btn-secondary w-50"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 bg-light">
                    <div class="row w-100 text-center">
                        {{-- <div class="col-4"></div> --}}
                        <div class="col-6 text-center">
                            <img src="/frontend/tcul_img/webp/iso-certification.webp" alt="ISO Certified"
                                class="img-fluid mb-2" style="max-width: 50px;">
                            <h6 class="fw-bold text-primary small">ISO CERTIFIED</h6>
                        </div>
                        {{-- <div class="col-4"></div> --}}
                        <div class="col-6 text-center">
                            <img src="/frontend/tcul_img/fide.png" alt="US Chess Affiliate" class="img-fluid mb-2"
                                style="max-width: 50px; height: 50px;">
                            <h6 class="fw-bold text-primary small">FIDE</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Map popup country → booking form country value (and iso2 for phone)
        const countryMap = {
            'USA': {
                booking: 'USA',
                iso2: 'us',
                dial: '1'
            },
            'CANADA': {
                booking: 'CANADA',
                iso2: 'ca',
                dial: '1'
            },
            'AUSTRALIA': {
                booking: 'AUSTRALIA',
                iso2: 'au',
                dial: '61'
            },
            'NEWZEALAND': {
                booking: 'NEW ZEALAND',
                iso2: 'nz',
                dial: '64'
            }, // popup has no space
            'NEW ZEALAND': {
                booking: 'NEW ZEALAND',
                iso2: 'nz',
                dial: '64'
            },
            'INDIA': {
                booking: 'INDIA',
                iso2: 'in',
                dial: '91'
            },
            'UAE': {
                booking: 'UAE',
                iso2: 'ae',
                dial: '971'
            },
            'UK': {
                booking: 'UK',
                iso2: 'gb',
                dial: '44'
            },
            'SINGAPORE': {
                booking: 'SINGAPORE',
                iso2: 'sg',
                dial: '65'
            },
            'SOUTH AFRICA': {
                booking: 'SOUTH AFRICA',
                iso2: 'za',
                dial: '27'
            },
            'QATAR': {
                booking: 'QATAR',
                iso2: 'qa',
                dial: '974'
            },
            'BAHRAIN': {
                booking: 'BAHRAIN',
                iso2: 'bh',
                dial: '973'
            },
            'KUWAIT': {
                booking: 'KUWAIT',
                iso2: 'kw',
                dial: '965'
            },
            'EUROPEAN UNION': {
                booking: 'EUROPEAN UNION',
                iso2: 'eu',
                dial: '358' // Finland's code as representative
            },
            'OMAN': {
                booking: 'OMAN',
                iso2: 'om',
                dial: '968'
            }
        };
    </script>
    <script>
        // map popup select value -> ISO2 for intl-tel-input
        const isoMap = {
            'USA': 'us',
            'CANADA': 'ca',
            'AUSTRALIA': 'au',
            'NEWZEALAND': 'nz', // your popup option without space
            'NEW ZEALAND': 'nz',
            'INDIA': 'in',
            'UAE': 'ae',
            'UK': 'gb',
            'SINGAPORE': 'sg',
            'SOUTH AFRICA': 'za',
            'QATAR': 'qa',
            'BAHRAIN': 'bh',
            'KUWAIT': 'kw'
        };

        // init intl-tel-input on popup field
        const popupInput = document.querySelector('#mobilePopup');
        const itiPopup = window.intlTelInput(popupInput, {
            initialCountry: "", // start blank, user picks from select
            separateDialCode: true,
            preferredCountries: ["in", "us", "gb"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
        });
        
        // when popup country changes, set hidden input for backend

        // when popup country changes, set ITI country
        $('#feesCountry').on('change', function() {
            const iso2 = isoMap[$(this).val()] || '';
            if (iso2) itiPopup.setCountry(iso2);
        });

         
        // optional UX: prevent non-digit chars (except +, space) in the popup input
        $('#mobilePopup').on('input', function() {
            this.value = this.value.replace(/[^\d\s()+-]/g, '');
        });

        
    </script>
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

            $('#enquirypopup-form').submit(function(e) {
                const sel = itiPopup.getSelectedCountryData();
                if (sel && sel.dialCode) {
                    $('#mobilepopup_country_code').val('+' + sel.dialCode);
                }
                
                e.preventDefault();
                $('div[id$="-error"]').empty();
                var form = $(this);
                var url = form.attr('action');
                console.log('Form URL:', url);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            // 1) Read popup values BEFORE closing
                            const popupName = $('input[name="first_name"]').val()?.trim() || '';
                            const popupMobile = $('input[name="mobilePopup"]').val()?.trim() || '';
                            const popupCountry = $('#feesCountry').val() || '';
                            const popupTimezone = $('#feesTimezone').val() || '';

                            // alert(popupMobile);

                            // 2) Prefill Name
                            if (popupName) {
                                $('#kids_first_name').val(popupName);
                            }

                            // 2b) Prefill Country + Timezone
                            if (popupCountry) {
                                // Set country and rebuild timezone list
                                $('#country').val(popupCountry);
                                updateTimezones(popupCountry); // call directly instead of trigger('change')

                                // Wait for timezones to load (they load synchronously)
                                setTimeout(() => {
                                    // Ensure timezone option exists before selecting
                                    if ($('#timezone option[value="' + popupTimezone + '"]').length > 0) {
                                        $('#timezone').val(popupTimezone);
                                    }
                                }, 100);
                            }



                            // 3) Prefill phone with dial code (do NOT set country select)
                            try {
                                // get dial code from popup's iti instance (not the trial form one)
                                const popupSel = itiPopup
                            .getSelectedCountryData(); // <-- from your popup init
                                const dial = popupSel?.dialCode ? ('+' + popupSel.dialCode) :
                                '';

                                if (typeof iti !== 'undefined' &&
                                    iti) { // trial form iti instance
                                    if (popupMobile) {
                                        // normalize: if user already typed a '+'-number, use as-is; else prepend dial code
                                        const cleaned = popupMobile.replace(/\s+/g, '');
                                        const e164 = cleaned.startsWith('+') ? cleaned : (dial +
                                            cleaned.replace(/^\+/, ''));
                                        iti.setNumber(
                                        e164); // this sets dial code + number and updates the flag automatically
                                    } else if (dial) {
                                        // no number, but at least reflect the dial code (caret at end)
                                        iti.setNumber(dial);
                                    }
                                } else {
                                    // fallback if iti isn't available
                                    if (popupMobile) $('#phone').val(popupMobile);
                                }
                            } catch (e) {
                                console.warn('intlTelInput set failed', e);
                                if (popupMobile) $('#phone').val(popupMobile);
                            }


                            // 4) Focus next field
                            setTimeout(() => {
                                $('#city').focus();
                            }, 50);

                            // Toast + close + scroll
                            toastr.success(data.message, '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true
                            });
                            setTimeout(function() {
                                $('#user-enquiry-modal').modal('hide');
                                $('html, body').animate({
                                    scrollTop: $('#trail_form').offset().top -
                                        100
                                }, 500);
                            }, 100);

                        } else {
                            toastr.error('There is some error!!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true
                            });
                        }
                    },

                    error: function(xhr, ajaxOptions, thrownError) {
                        toastr.error(xhr.responseJSON.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON
                                    .errors)[0] + '-error')
                                .offset().top - 200
                        }, 500);
                    }
                });
            });
        });

        const input = document.querySelector("#phone");
        const iti = window.intlTelInput(input, {
            initialCountry: "", // blank to show "select code" behavior
            separateDialCode: true,
            preferredCountries: ["in", "us", "gb"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
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
            'QATAR': ['Arabian Standard Time'],
            'BAHRAIN': ['Arabian Standard Time'],
            'KUWAIT': ['Arabian Standard Time'],
            'EUROPEAN UNION': ['Central European Time', 'Eastern European Time', 'Western European Time'],
            'OMAN': ['Arabian Standard Time']
        };


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
                 // read from the main form's intl-tel-input instance
                const sel = iti.getSelectedCountryData();
                if (sel && sel.dialCode) {
                    $('#booktrial_country_code').val('+' + sel.dialCode);
                } else {
                    $('#booktrial_country_code').val('');
                }

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
            $('#country').on('change', function () {
                const iso2 = isoMap[$(this).val()] || '';
                if (iso2) iti.setCountry(iso2);   // keep the phone flag/dial code in sync
                updateTimezones($(this).val());
                fetchTimeSlots();
            });


            // Prefill Country + Timezone
            $('#feesCountry').on('change', function() {
                updatePopUpTimezones($(this).val());
                fetchTimeSlots();
            });

            // Separate function for popup timezone update
            function updatePopUpTimezones(country) {
                const $timezone = $('#feesTimezone');
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
    </script>
    <script>
        // Fix country key mismatches for the timezones map
        const TZ_COUNTRY_ALIAS = {
            'NEW ZEALAND': 'NEWZEALAND'
        };

        // Optional: preferred default tz per country (falls back to first item if not found)
        const DEFAULT_TZ = {
            'USA': 'Eastern Time',
            'CANADA': 'Eastern Time',
            'AUSTRALIA': 'Australia/Sydney',
            'UK': 'British Summer Time',
            'INDIA': 'Indian Standard Time',
            'UAE': 'Gulf Standard Time',
            'SINGAPORE': 'Singapore Standard Time',
            'SOUTH AFRICA': 'South Africa Standard Time',
            'QATAR': 'Arabian Standard Time'
            // 'NEWZEALAND': 'New Zealand Standard Time'  // add if you like
        };

        function normalizeCountryKey(country) {
            return TZ_COUNTRY_ALIAS[country] || country;
        }

        // REPLACE your existing updateTimezones with this:
        function updateTimezones(country) {
            const $timezone = $('#timezone');
            $timezone.empty();

            const key = normalizeCountryKey(country);
            const list = timezones[key];

            if (!list || !list.length) return;

            // Build options
            // If only one tz, select it directly
            if (list.length === 1) {
                const only = list[0];
                $timezone.append(`<option value="${only}" selected>${only}</option>`);
                // Trigger change so downstream code (like fetching slots) runs
                $timezone.trigger('change');
                return;
            }

            // Multiple options
            $timezone.append(`<option value="" disabled selected>Select Time Zone</option>`);
            list.forEach(function(tz) {
                $timezone.append(`<option value="${tz}">${tz}</option>`);
            });

            // Auto-select preferred default if present, else first item
            const preferred = DEFAULT_TZ[key];
            let pick = (preferred && list.includes(preferred)) ? preferred : list[0];

            // Set and trigger change
            $timezone.val(pick);
            // If the preferred wasn't first, ensure the "selected" attribute reflects it:
            $timezone.find('option').prop('selected', false);
            $timezone.find(`option[value="${pick.replace(/"/g, '&quot;')}"]`).prop('selected', true);

            $timezone.trigger('change');
        }

        // Keep your existing bindings:
        // $('#country').on('change', function() {
        //   updateTimezones($(this).val());
        //   fetchTimeSlots();
        // });
    </script>

@endsection
