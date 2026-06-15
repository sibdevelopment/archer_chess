@extends('layouts.revamp')
@section('title', 'Home')
@section('content')

    <!--  ======= banner section start  ======= -->
    <section class="bg-main-two-200 pt-74 pb-120 position-relative z-3 overflow-hidden">
        <img src="/frontend1/assets/images/shape/banner-shape1.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 z-n1 d-lg-block d-none w-100">
        <img src="/frontend1/assets/images/shape/banner-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 z-1 d-lg-block d-none w-100">
        <img src="/frontend1/tcul-img/img/expert-2.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me-8 z-n1 tw-mb-134-px d-xxl-block d-none animation-upDown">
        <img src="/frontend1/tcul-img/img/footer-element2.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-mb-244-px d-xxl-block d-none animation-upDown z-n1">
        <img src="/frontend1/assets/images/shape/banner-shape5.png" alt="shape"
            class="position-absolute tw-start-0 bottom-0 tw-ms-17 z-n1 animation-upDown d-xxl-block d-none">
        <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
            class="position-absolute tw-end-0 top-0 tw-mt-17 tw-me-82-px d-xxl-block d-none animation-scalation">
        <img src="/frontend1/assets/images/shape/banner-shape7.png" alt="shape"
            class="position-absolute top-0 tw-start-0 tw-mt-15 tw-ms-104-px animate__wobble__two d-xxl-block d-none">
        <img src="/frontend1/assets/images/shape/banner-shape-6.png" alt="shape"
            class="position-absolute top-0 tw-end-0 d-xxl-block d-none">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div>
                        <h2 class="fw-bold text-neutral-950 tw-mb-5 text-middle" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="100">From Beginner to Pro – Online Chess Classes for All Levels.</h2>
                        <p class="tw-text-lg fw-normal text-paragraph-600 tw-mb-8 max-w-550-px w-100 text-middle"
                            data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">Join Archer’s online chess classes and take your game to the next level with expert guidance.</p>
                        <div class="d-flex align-items-center tw-gap-4 flex-wrap" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="300">
                            <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Explore More</span>
                                <span
                                    class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                </span>
                            </a>
                            <div class="d-flex align-items-center tw-gap-3">
                                <span class="">
                                    <img src="/frontend1/assets/images/icon/banner-icon1.png" style="rotate: 110deg;" class="mobile-icon" alt="icon">
                                </span>
                                <div>
                                    <span class="fw-normal tw-text-4 text-black tw-mb-2">Call Now</span>
                                    <h2 class="h5">
                                        <a href="tel:+919152734675"
                                            class="fw-bold text-main-600 hover-underline tw-duration-300 mobile-no">
                                            +91-9152734675
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="zoom-in" data-aos-duration="1500">
                    <div class="d-flex align-items-center justify-content-center bg-img">
                        <img src="/frontend1/tcul-img/img/hero-section.png" alt="img" class="bg-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= about our kindergarden top section start ======================= -->
    <div class="pt-110">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-wrap">
                <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-4">TRAINED STUDENTS</span>
                    <h1 class="fw-bol text-main-600 counter">15000+</h1>
                </div>
                <span class="tw-h-96-px tw-w-px tw-border-dashed border-neutral-300 border d-xl-block d-none"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="100"></span>
                <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-4">FIDE RATED</span>
                    <h1 class="fw-bol text-main-600 counter">250+</h1>
                </div>
                <span class="tw-h-96-px tw-w-px tw-border-dashed border-neutral-300 border d-xl-block d-none"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="200"></span>
                <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-4">COUNTRIES</span>
                    <h1 class="fw-bol text-main-600 counter">20+</h1>
                </div>
                <span class="tw-h-96-px tw-w-px tw-border-dashed border-neutral-300 border d-xl-block d-none"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="300"></span>
                <div data-aos="fade-up" data-aos-duration="600" data-aos-delay="350">
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-4">CHAMPIONS</span>
                    <h1 class="fw-bol text-main-600 counter">500+</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= about our kindergarden section start ======================= -->
    <section class="py-110 position-relative">
        <img src="/frontend1/tcul-img/img/expert-1.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 d-xl-block d-none animation-upDown tw-mt-8 z-n1">
        <img src="/frontend1/tcul-img/img/expert-2.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 d-xl-block d-none animation-scalation tw-me-144-px z-n1">   
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6" data-aos="zoom-in" data-aos-duration="1500">
                    <div class="position-relative">
                        <span>
                            <img src="/frontend1/tcul-img/img/gm-thejkumar.png" alt="img">
                        </span>
                        <img src="/frontend1/assets/images/shape/kindergarden-shape4.png" alt="shape"
                            class="position-absolute top-0 tw-start-0 tw-mt-234-px tw-ms--72-px d-xl-block d-none animation-upDown">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center">
                        <div class="max-w-520-px w-100 text-middle">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-6 text-middle" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="100">Meet Our Experts</span>
                            <h2 class="text-neutral-950 fw-bold tw-mb-7 h4 text-middle" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="150">Grandmaster MS Thejkumar</h2>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-2 line-height-28-px text-middle"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">GM. Thejkumar is a distinguished chess Grandmaster with a career highlighted by significant national and international achievements. His tactical brilliance and deep understanding of chess have led to numerous victories in prestigious tournaments, earning him widespread recognition. Beyond his competitive success, he has made a lasting impact as a mentor, guiding and developing young talent within the chess community. </p>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-7 line-height-28-px text-middle"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="200"> His contributions have been honored with various awards, reflecting his influence and dedication to the sport. GM. Thejkumar's legacy extends beyond his own achievements, as he continues to shape the future of chess through his mentorship and leadership.</p>
                            {{-- <div class="tw-mb-11" data-aos="fade-up" data-aos-duration="600" data-aos-delay="260">
                                <div class="">
                                    <span
                                        class="text-lg fw-semibold tw-mb-3 d-block text-heading text-capitalize cursor-small">
                                        Creativity
                                    </span>
                                    <div class="progress-container style-yellow" data-percentage="90">
                                        <div class="progress active"></div>
                                        <div class="percentage active cursor-small">0%</div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-wrap tw-mb-7"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                                <div class="d-flex align-items-center tw-gap-4 animation-item">
                                    <span>
                                        <img src="/frontend1/tcul-img/img/eklavya-award.png" alt="icon"
                                            class="animate__swing">
                                    </span>
                                    <h2 class="fw-bold text-neutral-950 line-height-28-px h6">Eklavya award <span><br/></span> winner</h2>
                                </div>
                                <div class="d-flex align-items-center tw-gap-4 animation-item">
                                    <span>
                                        <img src="/frontend1/tcul-img/img/grandmaster.png" alt="icon"
                                            class="animate__swing">
                                    </span>
                                    <h2 class="fw-bold text-neutral-950 line-height-28-px h6">1st grandmaster <span><br/></span> of state of Karnataka</h2>
                                </div>
                            </div>
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="350">
                                <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                    class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-our-kindergarden"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <span class="button__label">Book Your Trial</span>
                                    <span
                                        class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                        <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                    </span>
                                </a>
                                <div class="position-relative">
                                    <div class="d-flex align-items-center tw-gap-4 flex-wrap">
                                        <a href="https://www.youtube.com/watch?v=MFLVmAE4cqg"
                                            class="play-button tw-w-15 tw-h-15 border-main-two-600 border rounded-circle d-flex align-items-center justify-content-center">
                                            <span
                                                class="tw-w-11 tw-h-11 bg-main-two-600 rounded-circle d-flex align-items-center justify-content-center tw-text-4 text-white">
                                                <i class="ph-fill ph-play"></i>
                                            </span>
                                        </a>
                                        <span class="fw-semibold tw-text-4 text-paragraph-600">Intro-Video</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--   slider section start   -->
    <div class="pb-110 position-relative">
        <img src="/frontend1/tcul-img/img/why-us1.png" alt="shape"
            class="position-absolute bottom-100 d-lg-block d-none animation-upDown tw-ms-16 tw-start-0 d-xxl-block d-none">
        <div class="container">
            <div class="swiper brand-swiper__slider gradient-width-200 left-right-gradient left-right-gradient">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer09.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Expert Coaches</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer08.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Live Online Classes</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer07.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Interactive Learning</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer06.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Weekly Tournaments</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer05.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Global Students</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer04.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Individual Coaching</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer03.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">FIDE-Rated Trainers</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer02.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Group Coaching</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="d-flex align-items-center flex-column justify-content-center">
                            <img src="/frontend1/tcul-img/img/icons/why-archer01.png" alt="img">
                            <span class="text-center tw-gap-4 t_orange animation-item">Attractive Practical Platform</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Curriculum  -->
    <section id="curriculum" class="position-relative bg-main-600 py-110 overflow-hidden">
        <img src="/frontend1/assets/images/shape/our-program-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/horse.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-ms-104-px tw-mb-110-px d-lg-block d-none">
        <img src="/frontend1/tcul-img/img/curriculum.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me--22-px tw-mb-202-px d-lg-block d-none animation-upDown">
        <div class="mt-110 position-relative">
            <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
                class="position-absolute top-0 tw-end-0 d-lg-block d-none tw-me-174-px animation-scalation">
            <img src="/frontend1/tcul-img/img/course.svg" alt="shape"
                class="position-absolute top-0 tw-start-0 d-lg-block d-none tw-ms-104-px">
            <div class="container max-w-1500-px w-100">
                <div class="text-center tw-mb-5">
                    {{-- <span class="text-white tw-text-405 fw-normal tw-mb-6" data-aos="fade-up" data-aos-duration="600"
                        data-aos-delay="100">
                       Course Structure
                    </span> --}}
                    <h2 class="fw-bold text-white tw-mb-5 h4" data-aos="fade-up" data-aos-duration="600"
                        data-aos-delay="200">Curriculum</h2>
                    <p class="fw-normal tw-text-4 text-white" data-aos="fade-up" data-aos-duration="600"
                        data-aos-delay="300">Explore our carefully curated courses designed to elevate your chess skills, <br/> whether you're a beginner or an aspiring master. Each course is tailored to ensure maximum learning and development.</p>
                </div>
                <div class="swiper programSwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="bg-white tw-py-4 tw-px-4 tw-rounded-2xl w-100 h-100 tw-transform-roted--5">
                                <span class="tw-mb-5">
                                    <img src="/frontend1/tcul-img/img/program01.png" alt="img">
                                </span>
                                <div class="text-center tw-pb-6 pb-sm-0">
                                    <h2 class="fw-bold tw-text-30-px text-main-600 tw-mb-3 h5">
                                        Beginners
                                    </h2>
                                    <p class="fw-normal tw-text-base text-paragraph-500 tw-mb-4 text-middle">
                                        The Archer Beginner Course  is designed for new chess learners who want to build a strong foundation. Students learn the basic rules, piece movements, simple tactics, checkmates, and fundamental strategies through structured lessons and practice games, helping them develop confidence and a love for chess. ♟️
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#BeginnersCurriculumModal"
                                            class="tw-w-10 tw-h-10 bg-main-600 rounded-circle d-flex align-items-center justify-content-center tw-text-base text-white">
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-white tw-py-4 tw-px-4 tw-rounded-2xl w-100 h-100 tw-transform-roted-5">
                                <span class="tw-mb-5">
                                    <img src="/frontend1/tcul-img/img/program02.png" alt="img">
                                </span>
                                <div class="text-center tw-pb-6 pb-sm-0">
                                    <h2 class="fw-bold tw-text-30-px text-main-600 tw-mb-3 h5">
                                        Intermediate
                                    </h2>
                                    <p class="fw-normal tw-text-base text-paragraph-500 tw-mb-4 text-middle">
                                        The Archer Intermediate Course is designed for players who already understand the basics of chess and want to improve their skills. The course focuses on tactical patterns, opening principles, basic strategy, and essential endgames to help students play stronger games and prepare for competitive tournaments. ♟️
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#IntermediateCurriculumModal"
                                            class="tw-w-10 tw-h-10 bg-main-600 rounded-circle d-flex align-items-center justify-content-center tw-text-base text-white">
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-white tw-py-4 tw-px-4 tw-rounded-2xl w-100 h-100 tw-transform-roted--5">
                                <span class="tw-mb-5">
                                    <img src="/frontend1/tcul-img/img/program03.png" alt="img">
                                </span>
                                <div class="text-center tw-pb-6 pb-sm-0">
                                    <h2 class="fw-bold tw-text-30-px text-main-600 tw-mb-3 h5">
                                        Advanced
                                    </h2>
                                    <p class="fw-normal tw-text-base text-paragraph-500 tw-mb-4 text-middle">
                                       An advanced training program designed for serious chess players preparing to achieve their first FIDE rating. The course focuses on improving tactical calculation. Through structured training, classical practice games, and detailed analysis, players develop the strength and confidence required to compete successfully in FIDE-rated tournaments.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#AdvancedCurriculumModal"
                                            class="tw-w-10 tw-h-10 bg-main-600 rounded-circle d-flex align-items-center justify-content-center tw-text-base text-white">
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-white tw-py-4 tw-px-4 tw-rounded-2xl w-100 h-100 tw-transform-roted-5">
                                <span class="tw-mb-5">
                                    <img src="/frontend1/tcul-img/img/program04.png" alt="img">
                                </span>
                                <div class="text-center tw-pb-6 pb-sm-0">
                                    <h2 class="fw-bold tw-text-30-px text-main-600 tw-mb-3 h5">
                                        Grand Master
                                    </h2>
                                    <p class="fw-normal tw-text-base text-paragraph-500 tw-mb-4 text-middle">
                                        The Chesshika Grandmaster Program is designed for serious FIDE-rated players who aim to improve their rating and compete at higher levels. Opening preparation, middlegame strategy, endgame technique, and tournament skills. This advanced program is open to strong tournament players, national competitors, and titled players
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a target="_blank" href="http://chesshika.com/"
                                            class="tw-w-10 tw-h-10 bg-main-600 rounded-circle d-flex align-items-center justify-content-center tw-text-base text-white">
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Why Choose Us  -->
    <section id="about" class="py-110 position-relative">
        <img src="/frontend1/tcul-img/img/footer-element2.svg" alt="img"
            class="position-absolute bottom-0 tw-start-0 tw-mb-182-px animation-upDown d-xl-block d-none">
        <div class="container">
            <div class="row gy-5">
                <div class="col-xl-6">
                    <div class="tw-mb-10 text-middle">
                        <span class="fw-normal tw-text-405 text-main-600 tw-mb-5 text-middle" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="100">Why Choose Us</span>
                        <h2 class="fw-bold text-neutral-950 tw-mb-5 h4 text-middle" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="150">We provide your child with an Opportunity</h2>
                        <p class="fw-normal tw-text-4 text-paragraph-500 text-middle" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="200">We help young players develop strong chess skills through structured training, expert guidance, and interactive learning. Our programs are designed to build focus, strategic thinking, and confidence while making chess enjoyable for every student.</p>
                    </div>
                    <div class="row gy-4">
                        <div class="col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                            <div class="choose-us-musk-bg-img1 tw-py-11 tw-px-8 text-center tw-rounded-all animation-item tw-rounded-all">
                                <img src="/frontend1/tcul-img/img/icons/students-trained.svg" alt="icon"
                                    class="tw-mb-5 animate__bounce d-block mx-auto">
                                <span class="fw-medium tw-text-405 text-white tw-mb-5">Batches Conducted</span>
                                <h2 class="fw-bol text-white h2">1.5L+</h2>
                            </div>
                        </div>
                        <div class="col-sm-6 col-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
                            <div class="choose-us-musk-bg-img2 tw-py-11 tw-px-8 text-center tw-rounded-all animation-item tw-rounded-all">
                                <img src="/frontend1/tcul-img/img/icons/experience.svg" alt="icon"
                                    class="tw-mb-5 animate__bounce d-block mx-auto">
                                <span class="fw-medium tw-text-405 text-white tw-mb-5">Years of Experience</span>
                                <h2 class="fw-bol text-white h2">10+</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div>
                        <div class="row gy-5">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="why-us bg-main-50 text-middle tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all">
                                    <img src="/frontend1/tcul-img/img/expert-coaches.svg" alt="icon"
                                        class="tw-mb-5 animate__bounce d-block mx-sm-auto">
                                    <span class="fw-medium tw-text-405 text-neutral-950 tw-mb-5">Expert Coaches</span>
                                    <p class="fw-normal text-paragraph-500">Learn from experienced and certified chess coaches who guide students step-by-step, helping them understand strategies, tactics, and advanced gameplay concepts.</p>
                                </div>
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div
                                    class="why-us bg-primary-100 text-middle tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all">
                                    <img src="/frontend1/tcul-img/img/supportive-learning.svg" alt="icon"
                                        class="tw-mb-5 animate__bounce d-block mx-sm-auto">
                                    <span class="fw-medium tw-text-405 text-neutral-950 tw-mb-5">Safe & Supportive Learning</span>
                                    <p class="fw-normal text-paragraph-500">Our online classes provide a safe, friendly, and engaging environment where children can learn, ask questions, and improve their skills with confidence.</p>
                                </div>
                            </div>
                        </div>
                        <span class="d-flex align-items-center justify-content-center" data-aos="zoom-in"
                            data-aos-duration="1500">
                            <img src="/frontend1/tcul-img/img/why-us.png" alt="img"
                                class="tw-mt-5">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Learn Chess from Expert Coaches  -->
    <section style="background-image: url(/frontend1/tcul-img/img/cta-bg.png);" class="bg-img">
        <div class="pb-120 pt-120">
            <div class="container">
                <div class="d-flex align-items-end tw-gap-5 flex-wrap align-middle">
                    <div class="">
                        <h2 class="fw-bold text-white tw-mb-2 h4 text-middle" data-aos="fade-up" data-aos-duration="200"
                            data-aos-delay="100">Learn Chess from <br> Expert Coaches</h2>
                        <p class="fw-normal tw-text-4 text-white tw-mb-10 aos-init aos-animate text-middle" data-aos="fade-up" data-aos-duration="200" data-aos-delay="100">Help your child develop focus, strategy, and confidence through engaging online chess classes guided by experienced coaches.</p>
                        <div class="d-flex align-items-center tw-gap-4 flex-wrap" data-aos="fade-up"
                            data-aos-duration="300" data-aos-delay="100">
                            <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Book Free Trial</span>
                                <span
                                    class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                </span>
                            </a>
                            <div>
                                <div class="d-flex align-items-center tw-gap-3">
                                    <img src="/frontend1/assets/images/icon/about-us-icon1.png" style="rotate: 110deg;" class="mobile-icon" alt="icon">
                                    <div>
                                        <span class="fw-normal tw-text-4 text-white tw-mb-1">Call Now</span>
                                        <h2 class="h5">
                                            <a href="tel:+919152734675" class="fw-bold text-main-two-600 mobile-no">
                                                +91-9152734675
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rotate-text-wrapper" data-aos="zoom-in" data-aos-duration="600"
                        data-aos-delay="300">
                        <a target="_blank" href="https://wa.me/919152734675" class="position-relative pointer-events-auto tw-p-4">
                            <img src="/frontend1/assets/images/shape/totate-text.png" alt="Rotate Text"
                                class="animation-rotate-right">
                            <span class="position-absolute top-0 tw-start-0 top-50 translate-middle tw-start-50">
                                <span
                                    class="bg-white tw-w-100-px tw-h-100-px rounded-circle d-flex align-items-center justify-content-center">
                                    <img src="/frontend1/tcul-img/img/cta-logo.svg" alt="img">
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="tw-mt-6 position-relative">
            <img src="/frontend1/tcul-img/img/cta3.svg" alt="shape"
                class="position-absolute bottom-0 tw-end-0 d-xl-block d-none">
            <div class="d-flex align-items-center tw-gap-15 flex-wrap sm-gap-2">
                <div class="d-flex align-items-center tw-gap-3" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="100">
                    <span>
                        <img src="/frontend1/assets/images/icon/about-us-icon2.png" alt="icon">
                    </span>
                    <span class="fw-bold tw-text-405 text-neutral-950">Expert Chess Coaches</span>
                </div>
                <div class="d-flex align-items-center tw-gap-3" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">
                    <span>
                        <img src="/frontend1/assets/images/icon/about-us-icon2.png" alt="icon">
                    </span>
                    <span class="fw-bold tw-text-405 text-neutral-950">Interactive Online Classes</span>
                </div>
                <div class="d-flex align-items-center tw-gap-3" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="300">
                    <span>
                        <img src="/frontend1/assets/images/icon/about-us-icon2.png" alt="icon">
                    </span>
                    <span class="fw-bold tw-text-405 text-neutral-950">Flexible Learning for Kids</span>
                </div>
            </div>
        </div>
    </div>

    <!--  Coaches -->
    <section id="coaches" class="pt-110 pb-20 position-relative">
        <img src="/frontend1/tcul-img/img/horse.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me-130-px tw-mb-110-px d-lg-block d-none z-n1 animation-upDown">
        <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 d-lg-block d-none tw-mt-186-px tw-ms-200-px animation-upDown">
        <div class="container">
            <div class="text-center tw-mb-12">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-2" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="100">Where Learning Thrives</span>
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">Meet Our Tutors</h2>
            </div>
            <div class="swiper coachSwiper">
                <div class="swiper-wrapper">

                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Samyak Nayak
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2200</span>
                            </div>
                        </div>
                    </div>


                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        <div class="our-teacher-thumb tw-transform-roted--4">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Toyesh Singh
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2500</span>
                            </div>
                        </div>
                    </div>


                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                        <div class="our-teacher-thumb tw-transform-roted-3">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Dr. Sunanya
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2000</span>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Samyak Nayak
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2200</span>
                            </div>
                        </div>
                    </div>


                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        <div class="our-teacher-thumb tw-transform-roted--4">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Toyesh Singh
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2500</span>
                            </div>
                        </div>
                    </div>


                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                        <div class="our-teacher-thumb tw-transform-roted-3">
                            <div class="position-relative bg-img">
                                <a href="#0" class="bg-img">
                                    <img src="/frontend1/tcul-img/img/tutor1.png" alt="img" class="bg-img">
                                </a>
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <a href="https://www.facebook.com/" class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h2 class="h5">
                                    <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                        Dr. Sunanya
                                    </a>
                                </h2>
                                <span class="fw-normal tw-text-405 text-neutral-600 t_green">Fide Rating : 2000</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!--  Course Info  -->
    {{-- <section class="position-relative pt-60 pb-20">
        <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape" class="position-absolute bottom-0 tw-end-0 d-xl-block d-none animation-upDown">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="text-center w-100">
                        <h2 class="fw-bold text-neutral-950 tw-mb-3 h4">Course Structure and Weekly Schedule</h2>
                        <p class="fw-bold text-neutral-950 h6 aos-init aos-animate tw-mb-1">There are <b class="text-dark">3 sessions per week,</b> each lasting 50 minutes.</p>
                        <p class="fw-bold text-neutral-950 h6 aos-init aos-animate tw-mb-7"><b class="text-dark">Timing:</b> Flexible based on the student's level.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="max-w-550-px w-100">
                        <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                            <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                <img src="/frontend1/tcul-img/img/icons/puzzle.png" alt="icon" class="tw-w-17 tw-h-17">
                            </span>
                            <div>
                                <span class="fw-bold t_green fs-20 text-neutral-950 tw-mb-1 d-block">Free Activities</span>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Sunday:</b> Practice Tournament & Extra Session</p>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Assignment:</b> Homework + Class Recordings provided</p>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Assessment:</b> Test Review + Parent Meeting once in a month</p>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Rewards:</b> Monthly Prize Tournament</p>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">GM Camp:</b> Special sessions with Grandmaster coaches</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="max-w-550-px w-100">
                        <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                            <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                <img src="/frontend1/tcul-img/img/icons/badge.png" alt="icon" class="tw-w-17 tw-h-17 ">
                            </span>
                            <div>
                                <span class="fw-bold t_green fs-20 text-neutral-950 tw-mb-1 d-block">Special Features</span>
                                <p class="fw-normal tw-text-4 text-paragraph-500">Weekly Online International FIDE Rating Tournament</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                            <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                <img src="/frontend1/tcul-img/img/icons/discussion.png" alt="icon" class="tw-w-17 tw-h-17 ">
                            </span>
                            <div>
                                <span class="fw-bold t_green fs-20 text-neutral-950 tw-mb-1 d-block">Group Size</span>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Regular Batches:</b> Typically 5 to 6 kids per group</p>
                                <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">With Titled Player (FM, IM, GM):</b> Group size may be 10+ kids</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!--  Course Structure  -->
    <section class="bg-pink-100 position-relative py-110">
        <img src="/frontend1/assets/images/shape/our-program-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/tree.png" alt="shape"
            class="position-absolute top-0 tw-end-0 top-50 animation-upDown d-lg-block d-none">
        <img src="/frontend1/tcul-img/img/rotating.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-mb-134-px tw-ms-110-px d-lg-block d-none animation-rotate">
        <div class="mt-60 position-relative tw-mb-50-px">
            <img src="/frontend1/tcul-img/img/course.svg" alt="shape"
                class="position-absolute top-0 tw-start-0 tw-ms-200-px d-lg-block d-none tw-mt-80-px animation-scalation">
            <div class="container">
                <div class="text-center w-100">
                    <h2 class="fw-bold text-neutral-950 tw-mb-3 h4">Course Structure and Weekly Schedule</h2>
                    <p class="fw-bold text-neutral-950 h6 aos-init aos-animate tw-mb-1">There are <b class="text-dark">3 sessions per week,</b> each lasting 50 minutes.</p>
                    <p class="fw-bold text-neutral-950 h6 aos-init aos-animate tw-mb-7"><b class="text-dark">Timing:</b> Flexible based on the student's level.</p>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="max-w-550-px w-100">
                            <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                                <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                    <img src="/frontend1/tcul-img/img/icons/puzzle.svg" alt="icon" class="tw-w-17 tw-h-17">
                                </span>
                                <div>
                                    <span class="fw-bold t_orange fs-20 text-neutral-950 tw-mb-1 d-block">Free Activities</span>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Sunday:</b> Practice Tournament & Extra Session</p>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Assignment:</b> Homework + Class Recordings provided</p>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Assessment:</b> Test Review + Parent Meeting once in a month</p>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Rewards:</b> Monthly Prize Tournament</p>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">GM Camp:</b> Special sessions with Grandmaster coaches</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="max-w-550-px w-100">
                            <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                                <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                    <img src="/frontend1/tcul-img/img/icons/badge.svg" alt="icon" class="tw-w-17 tw-h-17 ">
                                </span>
                                <div>
                                    <span class="fw-bold t_orange fs-20 text-neutral-950 tw-mb-1 d-block">Special Features</span>
                                    <p class="fw-normal tw-text-4 text-paragraph-500">Weekly Online International FIDE Rating Tournament</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center tw-gap-4 tw-mb-10">
                                <span class="tw-w-17 tw-h-17 flex-shrink-0">
                                    <img src="/frontend1/tcul-img/img/icons/discussion.svg" alt="icon" class="tw-w-17 tw-h-17 ">
                                </span>
                                <div>
                                    <span class="fw-bold t_orange fs-20 text-neutral-950 tw-mb-1 d-block">Group Size</span>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">Regular Batches:</b> Typically 5 to 6 kids per group</p>
                                    <p class="fw-normal tw-text-4 text-paragraph-500"><b class="text-dark">With Titled Player (FM, IM, GM):</b> Group size may be 10+ kids</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="our-pricing-card tw-py-8 tw-px-10 bg-white tw-rounded-3xl d-flex align-items-center tw-gap-4 justify-content-between flex-wrap tw-mb-5 group-item"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="d-flex align-items-center tw-gap-3">
                        <img src="/frontend1/tcul-img/img/beginner.svg" alt="icon"
                            class="group-hover-item-text-invert-black">
                        <span class="our-pircing-text fw-bold tw-text-405 text-neutral-950">Beginners</span>
                    </div>
                    <p class="our-pircing-text max-w-260-px w-100 mw-sm-100 fw-normal tw-text-4 text-paragraph-500 text-middle">Learn piece movements, basic tactics, and simple strategies through fun lessons, puzzles, and practice games.</p>
                    <div class="d-flex align-items-end tw-gap-1">
                        <div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">30 Training Sessions – step-by-step foundation</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">15+ Online Tournaments – real practice</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">PDF Theory Material – GM-recommended content</span>
                            </div>
                        </div>
                        {{-- <h2 class="our-pircing-text tcul-space fw-normal text-main-two-600 h4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2> --}}
                    </div>
                    <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                        class="btn btn-main-two hover-style-two button--stroke active-scale-094 ms-auto tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-main-two-600"
                        data-block="button">
                        <span class="button__flair"></span>
                        <span class="button__label">More Details</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                    <div class="our-pricing-plan-text-bottom w-100">
                        <div class="d-flex align-items-center justify-content-end w-100">
                            <div class="max-w-810-px w-100 mobile-flex-start d-flex align-items-center tw-gap-5 justify-content-end flex-wrap">
                                <div class="">
                                    <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">15+ Opening Classes – chess openings & strategies</span>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">Live Puzzle Homework – daily practice portal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="our-pricing-card tw-py-8 tw-px-10 bg-white tw-rounded-3xl d-flex align-items-center tw-gap-4 justify-content-between flex-wrap tw-mb-5 group-item"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <div class="d-flex align-items-center tw-gap-3">
                        <img src="/frontend1/tcul-img/img/intermediate.svg" alt="icon"
                            class="group-hover-item-text-invert-black">
                        <span class="our-pircing-text fw-bold tw-text-405 text-neutral-950">Intermediate</span>
                    </div>
                    <p class="our-pircing-text max-w-260-px w-100 mw-sm-100 fw-normal tw-text-4 text-paragraph-500 text-middle">Learn deeper strategies, combinations, and endgame techniques while preparing for competitive play.</p>
                    <div class="d-flex align-items-end tw-gap-1">
                        <div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">60 Training Sessions – strategy & endgames</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">30+ Online Tournaments – sharpen skills</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">PDF Theory Material – structured GM notes</span>
                            </div>
                        </div>
                    </div>
                    <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                        class="btn btn-main-two hover-style-two button--stroke active-scale-094 ms-auto tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-main-two-600"
                        data-block="button">
                        <span class="button__flair"></span>
                        <span class="button__label">More Details</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                    <div class="our-pricing-plan-text-bottom w-100">
                        <div class="d-flex align-items-center justify-content-end w-100">
                            <div class="max-w-810-px w-100 mobile-flex-start d-flex align-items-center tw-gap-5 justify-content-end flex-wrap">
                                <div class="">
                                    <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">30+ Opening Classes – advanced variations</span>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">Live Puzzle Homework – daily challenges</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="our-pricing-card tw-py-8 tw-px-10 bg-white tw-rounded-3xl d-flex align-items-center tw-gap-4 justify-content-between flex-wrap tw-mb-5 group-item"
                    data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <div class="d-flex align-items-center tw-gap-3">
                        <img src="/frontend1/tcul-img/img/advanced.svg" alt="icon"
                            class="group-hover-item-text-invert-black">
                        <span class="our-pircing-text fw-bold tw-text-405 text-neutral-950">Advanced</span>
                    </div>
                    <p class="our-pircing-text max-w-260-px mw-sm-100 w-100 fw-normal tw-text-4 text-paragraph-500 text-middle">An advanced training program designed for serious chess players preparing to achieve their first FIDE rating.</p>
                    <div class="d-flex align-items-end tw-gap-1">
                        <div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">90 Training Sessions – advanced strategy</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">45+ Online Tournaments – stronger opponents</span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2">
                                <span class="tw-text-4 text-black">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-black">PDF Theory Material – GM designed content</span>
                            </div>
                        </div>
                    </div>
                    <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                        class="btn btn-main-two hover-style-two button--stroke active-scale-094 ms-auto tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-main-two-600"
                        data-block="button">
                        <span class="button__flair"></span>
                        <span class="button__label">More Details</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                    <div class="our-pricing-plan-text-bottom w-100">
                        <div class="d-flex align-items-center justify-content-end w-100">
                            <div class="max-w-810-px w-100 mobile-flex-start d-flex align-items-center tw-gap-5 justify-content-end flex-wrap">
                                <div class="">
                                    <div class="d-flex align-items-center tw-gap-2 tw-mb-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">45+ Opening Classes – deep theory</span>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-2">
                                        <span class="tw-text-4 text-black">
                                            <i class="ph-bold ph-check"></i>
                                        </span>
                                        <span class="fw-normal tw-text-4 text-black">Live Puzzle Homework – tactical exercises</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
  
    <!--  Videos -->
    <section id="videos" class="pt-60 pb-20 position-relative">
        <img src="/frontend1/tcul-img/img/horse.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me-130-px tw-mb-110-px d-lg-block d-none z-n1 animation-upDown">
        <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 d-lg-block d-none tw-mt-186-px tw-ms-200-px animation-upDown">
        <div class="container">
            <div class="swiper videoSwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonal-ad-3.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad2.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad4.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad5.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad2.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonal-ad-3.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="our-teacher-thumb tw-transform-roted-1">
                            <div class="position-relative bg-img">
                                <video class="bg-img video-item" controls controlslist="nodownload" playsinline>
                                    <source src="/frontend1/tcul-img/img/videos/testimonial-ad3.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- our testimonial top start -->
    <div class="bg-neutral-10 position-relative">
        <img src="/frontend1/tcul-img/img/footer-element2.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-mb-7 tw-ms-12 animation-upDown d-xl-block d-none">
        <div class="tw-mb-242-px">
            {{-- <div class="tw-mt--250-px z-2">
                <div class="container">
                    <div class="position-relative" data-aos="zoom-in" data-aos-duration="1500">
                        <img src="/frontend1/tcul-img/img/video.png" alt="img"
                            class="w-100">
                        <a href="https://www.youtube.com/watch?v=MFLVmAE4cqg"
                            class="play-button play-button-two border-main-two-600 border rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 tw-start-0 top-50 tw-start-50 translate-middle">
                            <img src="/frontend1/assets/images/icon/our-testimonial-top-icon.png" alt="icon"
                                class="tw-w-115-px tw-h-115-px">
                        </a>
                    </div>
                </div>
            </div> --}}
            <!-- our testimonial section start  === -->
            <section class="py-110">
                <div class="container">
                    <div class="text-center tw-mb-10">
                        <span class="fw-normal tw-text-405 text-main-600 tw-mb-6" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="100">Because, Parents Trusted us</span>
                        <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="200">Our success comes from the happiness and <br> growth of our students.</h2>
                    </div>

                    <div class="swiper testimonial-swiper-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600"
                                data-aos-delay="100">
                                <div>
                                    <div
                                        class="tw-py-11 tw-px-10 bg-main-600 tw-rounded-top-bottom-38-px tw-mb-4 position-relative">
                                        <ul class="d-flex align-items-center tw-gap-1 tw-mb-4">
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                        </ul>
                                        <p class="fw-medium line-height-32-px tw-text-405 text-white">My Sanchi has been Learning Chess with ACA since she was 6 years and won
                                            Mumbai District Championship Under 6 Category. She has also won so many
                                            tournaments in his category and the interschool Chess Championship 2022,
                                            all thanks to ACA! Now She is preparing for Fide Rated Tournament, and
                                            ACA gives all details of the official tournament and guides from time to
                                            time.</p>
                                        <img src="/frontend1/assets/images/icon/testimonial-icon1.png"
                                            alt="icon"
                                            class="position-absolute bottom-0 tw-end-0 tw-mb--38-px tw-me-11">
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-3">
                                        <span>
                                            <img src="/frontend1/assets/images/thumbs/testimonial-img1.png"
                                                alt="img">
                                        </span>
                                        <div>
                                            <span class="fw-bold tw-text-405 text-neutral-950 tw-mb-1 d-block ">John
                                                Smith</span>
                                            <span class="fw-normal tw-text-405 text-neutral-600">Student Father</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600"
                                data-aos-delay="200">
                                <div>
                                    <div
                                        class="tw-py-11 tw-px-10 bg-pink-600 tw-rounded-top-bottom-38-px tw-mb-4 position-relative">
                                        <ul class="d-flex align-items-center tw-gap-1 tw-mb-4">
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                        </ul>
                                        <p class="fw-medium line-height-32-px tw-text-405 text-white">The instructors have been incredibly knowledgeable and skilled, and they
                                            have done an amazing job of conveying their expertise in a way that is easy to understand and follow. The lessons are well-planned and organized, and the materials provided are informative and helpful.</p> <br/><br/>
                                        <img src="/frontend1/assets/images/icon/testimonial-icon1.png"
                                            alt="icon"
                                            class="position-absolute bottom-0 tw-end-0 tw-mb--38-px tw-me-11">
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-3">
                                        <span>
                                            <img src="/frontend1/assets/images/thumbs/testimonial-img2.png"
                                                alt="img">
                                        </span>
                                        <div>
                                            <span
                                                class="fw-bold tw-text-405 text-neutral-950 tw-mb-1 d-block ">William
                                                John</span>
                                            <span class="fw-normal tw-text-405 text-neutral-600">Student Father</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-aos="fade-up" data-aos-duration="600"
                                data-aos-delay="300">
                                <div>
                                    <div
                                        class="tw-py-11 tw-px-10 bg-main-two-600 tw-rounded-top-bottom-38-px tw-mb-4 position-relative">
                                        <ul class="d-flex align-items-center tw-gap-1 tw-mb-4">
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                            <li class="tw-text-6 text-white ">
                                                <i class="ph-fill ph-star"></i>
                                            </li>
                                        </ul>
                                        <p class="fw-medium line-height-32-px tw-text-405 text-white">My son Raghul is only 6 years, and now he has won so many tournaments in
                                            his category and wants to become Grandmaster, all thanks to ACA! Regular
                                            classes, practice tournaments, and a structured curriculum is the
                                            opportunity to approach learning chess in the best way possible.</p>
                                        <img src="/frontend1/assets/images/icon/testimonial-icon1.png"
                                            alt="icon"
                                            class="position-absolute bottom-0 tw-end-0 tw-mb--38-px tw-me-11">
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-3">
                                        <span>
                                            <img src="/frontend1/assets/images/thumbs/testimonial-img3.png"
                                                alt="img">
                                        </span>
                                        <div>
                                            <span class="fw-bold tw-text-405 text-neutral-950 tw-mb-1 d-block ">Michel
                                                Smith</span>
                                            <span class="fw-normal tw-text-405 text-neutral-600">Student Father</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- our testimonial section end -->
        </div>
    </div>    

    <!-- Start Your Chess Journey -->
    <section>
        <div class="container">
            <div
                class="bg-main-two-200 tw-px-90-px tw-rounded-4xl position-relative z-2 overflow-hidden tw-mt--242-px">
                <img src="/frontend1/assets/images/shape/banner-shape1.png" alt="shape"
                    class="position-absolute bottom-0 tw-start-0 w-100 ">
                <img src="/frontend1/assets/images/shape/get-start-shape1.png" alt="shape"
                    class="position-absolute bottom-0 tw-start-0 w-100">
                <img src="/frontend1/tcul-img/img/cta1.png" alt="shape"
                    class="position-absolute bottom-0 tw-start-0 tw-start-50 translate-middle d-xl-block d-none">
                <img src="/frontend1/tcul-img/img/cta2.svg" alt="shape"
                    class="position-absolute top-0 tw-end-0 tw-end-50 tw-mt-6 d-xl-block d-none animation-scalation">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="tw-pt-17 pb-110 text-middle chess-journey">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-4 text-middle mb-sm-0" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="100">Start Your Chess Journey</span>
                            <h2 class="fw-bold text-neutral-950 line-height-62-px tw-mb-5 h4 chess-journey text-middle pt-sm-0" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="200">How to enroll your child in Archer Chess Classes?
                            </h2>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-6 text-middle" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="300">Give your child the opportunity to learn chess from expert coaches through interactive online sessions. Our structured programs help kids improve focus, strategy, and confidence while enjoying the game.</p>
                            <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-white"
                                data-block="button" data-aos="fade-up" data-aos-duration="600"
                                data-aos-delay="350">
                                <span class="button__flair"></span>
                                <span class="button__label">Book Free Trial Class</span>
                                <span
                                    class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6" data-aos="zoom-in" data-aos-duration="1500">
                        <div class="d-xl-block d-none">
                            <span class="d-flex align-items-end justify-content-center z-n1 position-relative">
                                <img src="/frontend1/tcul-img/img/cta-img.png" alt="img">
                                <img src="/frontend1/tcul-img/img/cta3.svg" alt="shape"
                                    class="position-absolute top-0 tw-start-0 tw-mt-114-px d-xl-block d-none animation-upDown">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
      
    <!--gallery -->
    <div id="gallery" class="py-50">
        <div class="text-center tw-mb-12">
            <span class="fw-normal tw-text-405 text-main-600 tw-mb-2" data-aos="fade-up" data-aos-duration="600"
                data-aos-delay="100">Our Archer Kids</span>
            <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                data-aos-delay="200">Meet Our Archer Kids</h2>
            <p>Victory Belongs To Those Who Never Surrender!</p>
        </div>
        <div class="swiper teacher-bottom-four-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery1.png" alt="">
                        <h5 class="text-dark mt-3">Diksha</h5>
                        <h6 class="text-dark t_orange">(International Fide rating 1422)</h6>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery2.png" alt="">
                        <h5 class="text-dark mt-3">Diksha</h5>
                        <h6 class="text-dark t_orange">(International Fide rating 1422)</h6>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery3.png" alt="">
                        <h5 class="text-dark mt-3">Diksha</h5>
                        <h6 class="text-dark t_orange">(International Fide rating 1422)</h6>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery4.png" alt="">
                        <h5 class="text-dark mt-3">Diksha</h5>
                        <h6 class="text-dark t_orange">(International Fide rating 1422)</h6>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery5.png" alt="">
                        <h5 class="text-dark mt-3">Diksha</h5>
                        <h6 class="text-dark t_orange">(International Fide rating 1422)</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  FAQ's    -->
    <section id="faq" class="py-110">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-5" data-aos="zoom-in" data-aos-duration="1500">
                    <span class="bg-img">
                        <img src="/frontend1/tcul-img/home/faq.png" alt="img" class="bg-img">
                    </span>
                </div>
                <div class="col-lg-7">
                    <div class="tw-ps-8 ps-0">
                        <div class="tw-mb-6 text-middle">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-5 text-middle" data-aos-delay="100" data-aos="fade-up" data-aos-duration="600">
                                FAQ’s
                            </span>
                            <h4 class="fw-bold text-neutral-950 text-middle" data-aos-delay="200" data-aos="fade-up" data-aos-duration="600">
                                Most frequently asked questions
                            </h4>
                            <p class="text-middle">Here are the most frequently asked questions you may check before getting started</p>
                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item accordion-shadow tw-mb-4 tw-rounded-4xl" data-aos-delay="100" data-aos="fade-up" data-aos-duration="600">
                                <button class="accordion-button fw-bold tw-text-5 text-neutral-950" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How do I enroll my child in a course at Archer Chess Academy?
                                </button>
                                <div id="collapseOne" class="accordion-collapse collapse show"  data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="fw-normal tw-text-4 text-paragraph-500">Enrollment is simple. Just visit our website, choose the course level that fits your
                                    child, and fill out the registration form. Our team will guide you step by step to get
                                    started.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-shadow tw-mb-4 tw-rounded-4xl" data-aos-delay="150" data-aos="fade-up" data-aos-duration="600">
                                <button class="accordion-button collapsed fw-bold tw-text-5 text-neutral-950" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What makes Archer Chess Academy different from other academies?
                                </button>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="fw-normal tw-text-4 text-paragraph-500">We don’t just teach moves—we build thinkers. Our structured programs focus on tactics,
                                    strategy, and decision-making skills, while also making learning fun and engaging. Each
                                    child receives personal attention, even in group classes.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-shadow tw-mb-4 tw-rounded-4xl" data-aos-delay="200" data-aos="fade-up" data-aos-duration="600">
                                <button class="accordion-button collapsed fw-bold tw-text-5 text-neutral-950" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                   What
                                age groups can join your academy?
                                </button>
                                <div id="collapseThree" class="accordion-collapse collapse"  data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="fw-normal tw-text-4 text-paragraph-500">We welcome learners from 5 years old all the way to adults. Our teaching style and
                                    curriculum are tailored to the student’s age and skill level.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-shadow tw-mb-4 tw-rounded-4xl" data-aos-delay="250" data-aos="fade-up" data-aos-duration="600">
                                <button class="accordion-button collapsed fw-bold tw-text-5 text-neutral-950" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                   Will
                                my child get enough attention in group classes?
                                </button>
                                <div id="collapseFour" class="accordion-collapse collapse"  data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="fw-normal tw-text-4 text-paragraph-500">Yes. Our groups are intentionally kept small so every student gets the guidance they
                                    need. If you prefer, we also offer **one-on-one sessions** for focused learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item accordion-shadow tw-rounded-4xl" data-aos-delay="300" data-aos="fade-up" data-aos-duration="600"> 
                                <button class="accordion-button collapsed fw-bold tw-text-5 text-neutral-950" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                   Do you
                                prepare students for tournaments?
                                </button>
                                <div id="collapseFive" class="accordion-collapse collapse"  data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p class="fw-normal tw-text-4 text-paragraph-500">Yes, we do. We train students not only in chess skills but also in tournament preparation
                                    — including opening repertoire, time management, and handling pressure during games.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--   blog section start   -->
    {{-- <section class="py-110 position-relative">
        <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-260-px tw-me-130-px z-n1 d-xl-block d-none animation-upDown">
        <img src="/frontend1/tcul-img/img/footer-element2.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 z-n1 d-xl-block d-none animation-upDown tw-mt--170-px tw-ms-100-px">

        <div class="container">
            <div class="text-center tw-mb-12">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-6" data-aos="fade-up"
                    data-aos-duration="600" data-aos-delay="100">Our Blog & News</span>
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">Follow the latest and most useful articles <br/> on that student's blog</h2>
            </div>
            <div class="row gy-5">
                <div class="col-xl-4 col-lg-6 col-md-6" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="100">
                    <div class="animation-item">
                        <span class="overflow-hidden tw-rounded-20-px tw-rounded-top-20-px w-100">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="tw-duration-300 course-item__img bg-img">
                        </span>
                        <div class="tw-p-7 bg-white tw-rounded-bottom-20-px tw-border-bottom-header">
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-4">
                                <div class="d-flex align-items-center tw-gap-1">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">Feb 21, 2026</span>
                                </div>
                            </div>
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-8">
                                    Mastering the Opening: The Archer’s Approach to the First 10 Moves
                                </a>
                            </h2>
                            <div class="d-flex align-items-center tw-gap-3 justify-content-between flex-wrap">
                                <div class="d-flex align-items-center tw-gap-2">
                                    <div>
                                        <span class="fw-normal tw-text-305 text-neutral-500">Read More</span>
                                    </div>
                                </div>
                                <a href="#0"
                                    class="tw-w-10 tw-h-10 border-main-600 border rounded-circle d-flex align-items-center justify-content-center text-main-600 tw-text-405 hover-bg-main-600 hover-text-white tw-duration-300">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">
                    <div class="animation-item">
                        <span class="overflow-hidden tw-rounded-20-px tw-rounded-top-20-px w-100">
                            <img src="/frontend1/tcul-img/img/blog2.png" alt="img"
                                class="tw-duration-300 course-item__img bg-img">
                        </span>
                        <div class="tw-p-7 bg-white tw-rounded-bottom-20-px tw-border-bottom-header">
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-4">
                                <div class="d-flex align-items-center tw-gap-1">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">Feb 07, 2026</span>
                                </div>
                            </div>
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-8">
                                    Archer Chess Academy – Building Future Grandmasters from..
                                </a>
                            </h2>
                            <div class="d-flex align-items-center tw-gap-3 justify-content-between flex-wrap">
                                <div class="d-flex align-items-center tw-gap-2">
                                    <div>
                                        <span class="fw-normal tw-text-305 text-neutral-500">Read More</span>
                                    </div>
                                </div>
                                <a href="#0"
                                    class="tw-w-10 tw-h-10 border-main-600 border rounded-circle d-flex align-items-center justify-content-center text-main-600 tw-text-405 hover-bg-main-600 hover-text-white tw-duration-300">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="300">
                    <div class="animation-item">
                        <span class="overflow-hidden tw-rounded-20-px tw-rounded-top-20-px w-100">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="tw-duration-300 course-item__img bg-img">
                        </span>
                        <div class="tw-p-7 bg-white tw-rounded-bottom-20-px tw-border-bottom-header">
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-4">
                                <div class="d-flex align-items-center tw-gap-1">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">Feb 14, 2026</span>
                                </div>
                            </div>
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-8">
                                    Archer Chess Academy – Building Smart Minds Through Chess
                                </a>
                            </h2>
                            <div class="d-flex align-items-center tw-gap-3 justify-content-between flex-wrap">
                                <div class="d-flex align-items-center tw-gap-2">
                                    <div>
                                        <span class="fw-normal tw-text-305 text-neutral-500">Read More</span>
                                    </div>
                                </div>
                                <a href="#0"
                                    class="tw-w-10 tw-h-10 border-main-600 border rounded-circle d-flex align-items-center justify-content-center text-main-600 tw-text-405 hover-bg-main-600 hover-text-white tw-duration-300">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!--   blog section end   -->

    <!-- Contact -->
    <section id="contact" class="tw-mb--210-px z-3">
        <div class="container">
            <div class="position-relative">
                <img src="/frontend1/tcul-img/img/horse.svg" alt="shape"
                    class="position-absolute top-0 tw-start-0 tw-mt-4 tw-ms-140-px d-xxl-block d-none shadow-none z-1 animation-upDown">
                <img src="/frontend1/tcul-img/img/rotating.svg" alt="shape"
                    class="position-absolute top-0 tw-end-0 tw-mt-110-px tw-ms-60-px z-1 d-xxl-block d-none animation-rotate-right">
                <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape"
                    class="position-absolute top-0 tw-end-0 tw-mt-10 tw-me-200-px tw-me-200-px animation-scalation d-xxl-block d-none z-1">
                <div class="tw-py-99-px bg-main-600 choose-us-musk-bg-img5 tw-px-8">
                    <div class="max-w-750-px w-100 mx-auto mw-90 mw-sm-100">
                        <div class="text-center tw-mb-8">
                            <span class="fw-normal tw-text-405 text-white tw-mb-6" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="100">Contact Us</span>
                            <h2 class="fw-bold text-white h4" data-aos="fade-up" data-aos-duration="600"
                                data-aos-delay="200">Book your trial class</h2>
                        </div>
                        <form action="#" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                            <div class="row gy-4">
                                <!-- Country -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Country*</label>
                                    <select class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                        <option>Select Country</option>
                                        <option>USA</option>
                                        <option>Canada</option>
                                        <option>Australia</option>
                                        <option>New Zealand</option>
                                        <option>India</option>
                                        <option>UAE</option>
                                        <option>UK</option>
                                        <option>Singapore</option>
                                        <option>South Africa</option>
                                        <option>Qatar</option>
                                        <option>Bahrain</option>
                                        <option>Kuwait</option>
                                    </select>
                                </div>

                                <!-- Timezone -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Timezone*</label>
                                    <select class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                        <option>Select Time Zone</option>
                                        <option>IST</option>
                                        <option>EST</option>
                                        <option>PST</option>
                                    </select>
                                </div>

                                <!-- City -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">City*</label>
                                    <input type="text" placeholder="Enter your city"
                                        class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                </div>

                                <!-- Kid Name -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Kid's Full Name*</label>
                                    <input type="text" placeholder="Enter kid's full name"
                                        class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                </div>

                                <!-- Age -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Age*</label>
                                    <input type="number" placeholder="Enter kid's age"
                                        class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                </div>

                                <!-- WhatsApp -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">WhatsApp Number*</label>
                                    <input id="phone_contact" type="tel" placeholder="Enter WhatsApp number"
                                        class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600" style="width: stretch !important;">
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Email*</label>
                                    <input type="email" placeholder="Enter your email"
                                        class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                </div>

                                <!-- Language -->
                                <div class="col-md-6">
                                    <label class="fw-semibold tw-mb-2 text-white">Language Preference*</label>
                                    <select class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                                        <option>Select language preference</option>
                                        <option>Agree (English)</option>
                                        <option>Kid is not comfortable in English</option>
                                    </select>
                                </div>

                                <!-- Button -->
                                <div class="col-12 text-center tw-mt-10">
                                    <button type="submit"
                                        class="btn btn-main-three hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                        data-block="button">

                                        <span class="button__flair"></span>
                                        <span class="button__label">Submit</span>

                                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                        </span>

                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--  ==== newsletter section end  ==== -->

    <script>
        const videos = document.querySelectorAll(".video-item");

        videos.forEach(video => {

            video.addEventListener("play", function () {

                // Pause other videos
                videos.forEach(v => {
                    if (v !== video) v.pause();
                });

                // Fullscreen
                if (video.requestFullscreen) {
                    video.requestFullscreen();
                } else if (video.webkitRequestFullscreen) {
                    video.webkitRequestFullscreen();
                }

            });

        });
    </script>

    <!-- Curriculum Swiper -->
    <script>
        var swiper = new Swiper(".programSwiper", {
            loop: false,
            initialSlide: 0,
            spaceBetween: 24,

            autoplay: {
                delay: 2000,
                disableOnInteraction: false
            },

            breakpoints: {
                0: {
                    slidesPerView: 1.2
                },
                768: {
                    slidesPerView: 2,
                    autoplay: false
                },
                1024: {
                    slidesPerView: 4
                }
            }
        });
    </script>

    <!-- Coaches Swiper -->
   <script>
        var swiper = new Swiper(".coachSwiper", {
            loop: true,
            spaceBetween: 24,
            initialSlide: 0,

            autoplay: {
                delay: 2200,
                disableOnInteraction: false
            },

            breakpoints: {
                0: {
                    slidesPerView: 1.2
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    </script>
    <!-- Videos -->
   <script>
        var swiper = new Swiper(".videoSwiper", {
            loop: false,
            spaceBetween: 24,
            initialSlide: 0,

            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },

            breakpoints: {
                0: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 5
                }
            }
        });
    </script>

@endsection