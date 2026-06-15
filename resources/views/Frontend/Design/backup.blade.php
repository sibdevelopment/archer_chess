<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Kidschool - Education, Kindergarten, Preschool, and Children Learning Center HTML Template. Creative design, fully responsive, and easy to customize for daycare centers, nursery schools, and childcare institutions.">
    <meta name="keywords"
        content="Kidschool, Kindergarten, Preschool, Nursery, Daycare, Children Education, Kids Learning Center, School Website Template, Creative Kids Template, Bootstrap Kidschool">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Archer Chess Academy</title>
    <!-- Favicon -->
    <link rel="icon" href="https://archerchessacademy.com/frontend/tcul_img/home/archer_favicon.png" type="image/png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/frontend1/assets/css/bootstrap.min.css">
    <!-- swiper -->
    <link rel="stylesheet" href="/frontend1/assets/css/swiper-bundle.min.css">
    <!-- magnipic -->
    <link rel="stylesheet" href="/frontend1/assets/css/magnipic-popup.css">
    <!-- aos -->
    <link rel="stylesheet" href="/frontend1/assets/css/aos.css">
    <!-- Main css -->
    <link rel="stylesheet" href="/frontend1/assets/css/main.css">
    <link rel="stylesheet" href="/frontend1/assets/css/tcul.css">
    <link rel="stylesheet" href="/frontend1/assets/css/custom.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>

<body>

    <!--==================== Overlay Start ====================-->
    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- Custom Toast Message start -->
    <div id="toast-container"></div>
    <!-- Custom Toast Message End -->

    <!-- ==================== Scroll to Top End Here ==================== -->
    <div class="progress-wrap cursor-big">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- ==================== Scroll to Top End Here ==================== -->

    <!-- Custom Cursor Start -->
    <div class="cursor"></div>
    <span class="dot"></span>
    <!-- Custom Cursor End -->

    <!-- ==================== Mobile Menu Start Here ==================== -->
    <div
        class="mobile-menu d-xl-none d-block scroll-sm position-fixed bg-white tw-w-300-px tw-h-screen overflow-y-auto tw-p-6 tw-z-999 tw--translate-x-full tw-pb-68 ">

        <button type="button"
            class="close-button position-absolute tw-end-0 top-0 tw-me-2 tw-mt-2 tw-w-605 tw-h-605 rounded-circle d-flex justify-content-center align-items-center text-white bg-neutral-500 hover-bg-neutral-900 hover-text-white">
            <i class="ph ph-x"></i>
        </button>

        <div class="mobile-menu__inner">
            <a href="/design/home" class="mobile-menu__logo">
                <img src="/frontend1/tcul-img/logo/archer-logo.svg" alt="Logo">
            </a>
            <div class="mobile-menu__menu">
                <!-- Nav menu Start -->
                <ul class="nav-menu d-lg-flex align-items-center nav-menu--mobile d-block tw-mt-8">

                    <li class="nav-menu__item">
                        <a href="#0" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Events</a>
                    </li>

                    <li class="nav-menu__item">
                        <a href="#0" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Blogs</a>
                    </li>

                    <li class="nav-menu__item">
                        <a href="#0" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Gallery</a>
                    </li>

                    <!-- Courses Dropdown -->
                    <li class="nav-menu__item has-submenu position-relative">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">
                            Courses
                        </a>

                        <ul class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">

                            <li class="nav-submenu__item">
                                <a href="#0"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded">
                                    Beginners Chess Classes
                                </a>
                            </li>

                            <li class="nav-submenu__item">
                                <a href="#0"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded">
                                    Intermediate Chess Classes
                                </a>
                            </li>

                            <li class="nav-submenu__item">
                                <a href="#0"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded">
                                    Advanced Chess Classes
                                </a>
                            </li>

                            <li class="nav-submenu__item">
                                <a href="#0"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded">
                                    Expert Chess Classes
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-menu__item">
                        <a href="#0" class="nav-menu__link text-neutral-950 tw-py-5 fw-medium w-100">Contact</a>
                    </li>

                </ul>

            </div>
        </div>
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->

    <!-- ================================ top header start ================================ -->
    {{-- <div class="bg-white">
        <div class="container">
            <div
                class="tw-pt-6 tw-pb-4 tw-px-6 border-bottom border-neutral-400 border-top-0 border-start-0 border-end-0 tw-border-dashed">
                <div class="d-flex align-items-center tw-gap-3 justify-content-between">
                    <div class="d-xxl-block d-none">
                        <div class="d-flex align-items-center tw-gap-6">
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-phone"></i>
                                </span>
                                <a href="tel:+1.809.659.8654"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    +1.809.659.8654
                                </a>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-envelope-simple"></i>
                                </span>
                                <a href="mailto:kidschool@gmail.com"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    kidschool@gmail.com
                                </a>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-map-pin"></i>
                                </span>
                                <span class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    Graaf Florisstraat 22-A, 3021 CH Rotterdam
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center tw-gap-4">
                        <div class="d-flex align-items-center tw-gap-105">
                            <span class="tw-text-xl text-main-600 ">
                                <i class="ph-bold ph-question"></i>
                            </span>
                            <a href="#0" class="fw-normal tw-text-305 text-black hover-text-main-600">
                                FAQ’s
                            </a>
                        </div>
                        <div class="d-flex align-items-center tw-gap-105">
                            <span class="tw-text-xl text-main-600 ">
                                <i class="ph-fill ph-user"></i>
                            </span>
                            <div class="d-flex align-items-center tw-gap-1">
                                <a href="#0"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600">
                                    Login
                                </a>
                                <span class="fw-normal text-black tw-text-305">
                                    /
                                </span>
                                <a href="javascriot:void(0)"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600">
                                    Register
                                </a>
                            </div>
                        </div>
                        <div class="d-sm-block d-none">
                            <div class="d-flex align-items-center tw-gap-1">
                                <img src="/frontend1/assets/images/thumbs/flag-img1.png" alt="img">
                                <select class="border-0 form-select">
                                    <option value="1">EN</option>
                                    <option value="1">BN</option>
                                    <option value="1">HI</option>
                                    <option value="1">AR</option>
                                    <option value="1">FR</option>
                                    <option value="1">ES</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-sm-block d-none">
                            <ul class="d-flex align-items-center tw-gap-3">
                                <li>
                                    <a href="https://www.facebook.com/"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-fill ph-facebook-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.twitter.com/"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-bold ph-x-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.twitter.com/"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-fill ph-twitter-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-bold ph-instagram-logo"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- ================================ top header end ================================ -->


    <!-- ==================== Header Start Here ==================== -->
    <header class="header tw-transition-all tw-z-99">
        <div class="container container-two">
            <nav
                class="d-flex align-items-center justify-content-between position-relative bg-white tw-p-5 tw-rounded-bottom-16-px">
                <!-- Logo Start -->
                <div class="logo">
                    <a href="/design/home" class="link">
                        <img src="/frontend1/tcul-img/logo/archer-logo.svg" alt="Logo" class="max-w-200-px">
                    </a>
                </div>
                <!-- Logo End  -->

                <!-- Menu Start  -->
                <div class="header-menu d-xl-block d-none">
                    <!-- Nav menu Start -->
                    <ul class="nav-menu d-lg-flex align-items-center tw-gap-6">
                        {{-- <li class="nav-menu__item activePage">
                            <a href="javascript:void(0)" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Home</a>
                        </li> --}}
                        <li class="nav-menu__item activePage">
                            <a href="javascript:void(0)" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Events</a>
                        </li>
                        <li class="nav-menu__item activePage">
                            <a href="javascript:void(0)" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Blogs</a>
                        </li>
                        <li class="nav-menu__item activePage">
                            <a href="javascript:void(0)" class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Gallery</a>
                        </li>
                        <li class="nav-menu__item has-submenu position-relative">
                            <a href="javascript:void(0)"
                                class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Courses</a>
                            <ul
                                class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#0"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Beginners Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#0"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Intermediate Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#0"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Advanced Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#0"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Expert Chess Classes</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-menu__item">
                            <a href="#0"
                                class="nav-menu__link text-neutral-950 tw-py-5 fw-medium w-100">Contact</a>
                        </li>
                    </ul>
                    <!-- Nav menu End  -->

                </div>
                <!-- Menu End  -->

                <!-- Header Right start -->
                <div class="d-flex align-items-center tw-gap-4">
                    <div class="d-flex align-items-center tw-gap-4">
                        <div class="d-sm-block d-none">
                            <a href="#0" class="px-3 btn btn-main hover-style-one button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-2 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-header" data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Our Classroom</span>
                                <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                </span>
                            </a>
                        </div>
                        <div class="d-md-block d-none">
                            <button type="button" class="login-btn open-search tw-text-4 tw-h-13 bg-main-two-50 text-main-600 rounded-2 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                             Login
                            </button>
                        </div>
                        <div class="d-xl-block d-none">
                            <a href="javascript:void(0)" class="login-btn tw-text-4 tw-h-13 bg-main-two-50 text-main-600 rounded-2 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                Sign Up
                            </a>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="toggle-mobileMenu leading-none d-xl-none ms-3 text-neutral-800 tw-text-9"
                        >
                        <i class="ph ph-list"></i>
                    </button>
                </div>
                <!-- Header Right End  -->
            </nav>
        </div>
    </header>
    <!-- ==================== Header End Here ==================== -->


    <!-- ======================= banner section start ======================= -->
    <section style="background-image: url(/frontend1/tcul-img/home/hero-section.webp);"
        class="bg-img tw-mt--100-px">
        <div class="bg-purple-400 tw-pt-200px tw-pb-260px position-relative z-2">
            <img src="/frontend1/assets/images/shape/banner-four-shape1.png" alt="shape"
                class="position-absolute bottom-0 tw-start-0 w-100">
            {{-- <img src="/frontend1/assets/images/shape/kindergarden-shape1.png" alt="shape"
                class="position-absolute top-0 tw-start-0 mt-120 animation-upDown d-xl-block d-none z-n1"> --}}
            {{-- <img src="/frontend1/tcul-img/home/hero-bottom.png" alt="shape"
                class="position-absolute bottom-0 tw-start-0 tw-mb-110-px animation-upDown d-xl-block d-none z-n1"> --}}
            {{-- <img src="/frontend1/assets/images/shape/kindergarden-shape3.png" alt="shape"
                class="position-absolute top-0 tw-end-0 tw-mt-130-px tw-me-400-px animation-upDown d-xl-block d-none z-n1"> --}}
            {{-- <img src="/frontend1/assets/images/shape/banner-four-shape2.png" alt="shape"
                class="position-absolute top-0 tw-end-0 tw-mt-200-px tw-me-100-px animation-scalation d-xl-block d-none z-n1"> --}}
            {{-- <img src="/frontend1/assets/images/shape/banner-four-shape3.png" alt="shape"
                class="position-absolute bottom-0 tw-start-0 tw-mb-150-px tw-ms-400-px animation-scalation d-xl-block d-none z-n1"> --}}
            <div class="container max-w-1600-px">
                <div class="d-flex align-items-center tw-gap-5 justify-content-between mobile-flex">
                    {{-- <span class="tw-mt--100-px d-xl-block d-none">
                        <img src="/frontend1/tcul-img/home/hero2.png" alt="img">
                    </span> --}}
                    <div class="d-flex align-items-center justify-content-center mx-auto">
                        <div class="max-w-780-px w-100 text-center">
                            {{-- <h1 class="fw-normal text-main-two-300 tw-mb-5 h6">
                                About Our Kindergarten & Kids School
                            </h1> --}}
                            <h1 class="tw-text-100-px fw-bold text-white line-height-50-px tw-mb-7 text-start hero-header">
                                From Beginner to Pro – Online Chess Classes for All Levels.
                            </h1>
                            <h1 class="fw-normal text-main-two-300 tw-mb-5 h6 text-start">
                                Join Archer’s online chess classes and take your game to the next level with expert guidance.
                            </h1>
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap justify-content-start">
                                <a href="#0"
                                    class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <span class="button__label">Book Your Free Trial</span>
                                    <span
                                        class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                        <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                    </span>
                                </a>
                                {{-- <a href="#0"
                                    class="btn btn-main-four hover-style-three button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                    data-block="button">
                                    <span class="button__flair"></span>
                                    <span class="button__label">Book A Visit</span>
                                    <span
                                        class="text-main-two-600 tw-text-xl group-hover-text-white tw-duration-500 position-relative">
                                        <i class="ph-bold ph-arrow-right"></i>
                                    </span>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                    <span class="tw-mb--130-px d-xl-block">
                        <img src="/frontend1/tcul-img/home/hero-right.png" alt="img">
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================== 4 Features ====================== -->
    <div class="tw-mb-0 py-50">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <a href="#curriculum">
                        <div class="banner-bottom-three-musk-img1">
                            <div class="tw-py-8 tw-px-5 animation-item ">
                                <div class="d-flex align-items-start tw-gap-5 justify-content-center flex-sm-nowrap flex-wrap">
                                    <img width="65" src="/frontend1/tcul-img/home/curr.png" alt="img" class="animate__heartBeat">
                                    <div>
                                        <span class="fw-bold tw-text-405 text-white tw-mb-3">
                                            Curriculum
                                        </span>
                                        <p class="fw-normal tw-text-4 text-white">Structured chess learning for kids</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <a href="#courses">
                        <div class="banner-bottom-three-musk-img2">
                            <div class="tw-py-8 tw-px-5 animation-item ">
                                <div class="d-flex align-items-start tw-gap-5 justify-content-center flex-sm-nowrap flex-wrap">
                                    <img width="65" src="/frontend1/tcul-img/home/course.png" alt="img" class="animate__wobble">
                                    <div>
                                        <span class="fw-bold tw-text-405 text-white tw-mb-3">
                                            Courses
                                        </span>
                                        <p class="fw-normal tw-text-4 text-white">Step-by-step chess skill development</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <a href="#tutors">
                        <div class="banner-bottom-three-musk-img3">
                            <div class="tw-py-8 tw-px-5 animation-item ">
                                <div class="d-flex align-items-start tw-gap-5 justify-content-center flex-sm-nowrap flex-wrap">
                                    <img src="/frontend1/assets/images/icon/banner-bottom-three-icon2.png" alt="img" class="animate__bounce">
                                    <div>
                                        <span class="fw-bold tw-text-405 text-white tw-mb-3">
                                            Tutors
                                        </span>
                                        <p class="fw-normal tw-text-4 text-white">Experienced chess coaches for kids</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <a href="#our-kids">
                        <div class="banner-bottom-three-musk-img1 banner-bottom-four-musk-img1">
                            <div class="tw-py-8 tw-px-5 animation-item ">
                                <div class="d-flex align-items-start tw-gap-5 justify-content-center flex-sm-nowrap flex-wrap">
                                    <img src="/frontend1/assets/images/icon/banner-bottom-three-icon3.png" alt="img" class="animate__heartBeat">
                                    <div>
                                        <span class="fw-bold tw-text-405 text-white tw-mb-3">
                                            Meet Our Kids
                                        </span>
                                        <p class="fw-normal tw-text-4 text-white">Young champions learning chess</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================== meet our expert =========================== -->
    <section class="tw-mb-15 py-50">
        <div class="container max-w-1380-px">
            <div class="max-w-550-px w-100 text-center mx-auto tw-mb-5">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-2">
                   What’s New
                </span>
                <h2 class="fw-bold text-neutral-950 h4">
                   Meet Our Experts
                </h2>
            </div>
            <div class="bg-main-two-200 tw-py-5 tw-ps-5 tw-pe-8 tw-rounded-xl position-relative z-2">
                <img src="/frontend1/assets/images/shape/choos-us-four-shape1.png" alt="shape"
                    class="position-absolute bottom-0 tw-start-0 w-100 z-n1">
                <div
                    class="bg-main-600 tw-px-8 tw-py-10 position-absolute top-0 tw-start-0 tw-rounded-lg tw-mt-130-px tw-ms--72-px medya-ms-0 animation-upDown">
                    <h2 class="text-white fw-bold tw-text-44-px tw-mb-3 h4">
                        2600+
                    </h2>
                    <span class="fw-bold tw-text-4 text-white">
                      FIDE Rating
                    </span>
                </div>
                <div class="row gy-5 align-items-center">
                    <div class="col-xl-6">
                        <div class="bg-img">
                            <img src="/frontend1/tcul-img/home/experts.png" alt="img"
                                class="bg-img">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="max-w-600-px">
                                <h1 class="fw-bold text-neutral-950 tw-mb-8 h4">
                                    Grandmaster MS Thejkumar
                                </h1>
                                <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-5">
                                    GM. Thejkumar is a distinguished chess Grandmaster with a career highlighted by significant national and international achievements. His tactical brilliance and deep understanding of chess have led to numerous victories in prestigious tournaments, earning him widespread recognition. Beyond his competitive success, he has made a lasting impact as a mentor, guiding and developing young talent within the chess community. His contributions have been honored with various awards, reflecting his influence and dedication to the sport. GM. Thejkumar's legacy extends beyond his own achievements, as he continues to shape the future of chess through his mentorship and leadership.
                                </p>
                                <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-2">
                                    <div>
                                        <div class="d-flex align-items-center tw-gap-2 tw-mb-4">
                                            <span class="tw-text-405 text-main-600">
                                                <i class="ph-bold ph-check"></i>
                                            </span>
                                            <span class="fw-normal tw-text-4 text-paragraph-500">
                                                Eklavya award winner
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center tw-gap-2 tw-mb-4">
                                            <span class="tw-text-405 text-main-600">
                                                <i class="ph-bold ph-check"></i>
                                            </span>
                                            <span class="fw-normal tw-text-4 text-paragraph-500">
                                                1st grandmaster of state of Karnataka
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-5 flex-wrap">
                                    <a href="#0"
                                        class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                                        data-block="button">
                                        <span class="button__flair"></span>
                                        <span class="button__label">Start Learning Today</span>
                                        <span
                                            class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                            <img src="/frontend1/assets/images/icon/banner-icon-white.png"
                                                alt="icon">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!-- ============================ Why Archer ? ============================ -->
    <section class="py-80 bg-purple-500 position-relative z-2 overflow-hidden">
        <img src="/frontend1/assets/images/shape/our-kindergarden-four-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        <img src="/frontend1/assets/images/shape/our-kindergarden-four-shape2.png" alt="shape"
            class="position-absolute top-0 d-xl-block d-none tw-end-0">
        {{-- <img src="/frontend1/assets/images/shape/our-kindergarden-four-shape3.png" alt="shape"
            class="position-absolute tw-start-0 tw-mb-80-px tw-ms-160-px animation-scalation bottom-0 d-xl-block d-none"> --}}
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <span class="fw-normal tw-text-405 text-main-two-500 tw-mb-6">
                        Innovative Learning
                    </span>
                    <h2 class="fw-bold text-white tw-mb-4 h4">
                       Why Archer ?
                    </h2>
                    <p class="fw-normal tw-text-4 text-white tw-mb-10">
                        Utilizing a decade of experience, we have created online chess classes and a support platform catering to children between the ages of 4 to 15. Our team of hand-picked, monitored, and extensively trained Coaches ensures the provision of exceptional service, with all Coaches being certified professionals. Through our Remote Learning program, you can access our interactive and practical tutoring sessions from anywhere, using your desktop, tablet, or mobile phone. Our chess coaching has consistently yielded results, with nearly all our students surpassing expected performance levels upon completing their sessions with us.
                    </p>
                    <div class="d-flex align-items-center tw-gap-3 flex-wrap tw-mb-80-px">
                        <a href="#0"
                            class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                            data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Book Your Free Trial</span>
                            <span
                                class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </a>
                        <a href="#0"
                            class="btn btn-main-four hover-style-three button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4"
                            data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Start Learning Today</span>
                            <span
                                class="text-main-two-600 tw-text-xl group-hover-text-white tw-duration-500 position-relative">
                                <i class="ph-bold ph-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="w-100">
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/learning.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    Play & Learn Global
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/fide.png"
                                        alt="icon" class="animate__wobble">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    FIDE-Rated Trainers
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/affordable.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    Affordable Price
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/coaching.png"
                                        alt="icon" class="animate__wobble">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    Individual Coaching
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/group-coaching.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    Group Coaching
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/practical-platform.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    Attractive Practical Platform
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap tw-mb-7 animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/state-preparation.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    
                                    District and State Preparation
                                </h1>
                            </div>
                            <div class="d-flex align-items-center tw-gap-5 flex-wrap animation-item">
                                <span class="">
                                    <img src="/frontend1/tcul-img/home/national-preparation.png"
                                        alt="icon" class="animate__bounce">
                                </span>
                                <h1 class="fw-bold text-white h5">
                                    National and International Preparation
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ Unleash Your Potential ============================ -->
    <section id="curriculum" class="py-60 position-relative">
        {{-- <img src="/frontend1/assets/images/shape/footer-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me-100-px animation-upDown d-xl-block d-none z-n1"> --}}
        <div class="container max-w-1450-px">
            <div class="d-flex align-items-center tw-gap-4 justify-content-between tw-mb-15">
                {{-- <img src="/frontend1/assets/images/shape/our-program-four-shape1.png" alt="shape"
                    class="animation-scalation d-xl-block d-none"> --}}
                <div class="d-flex align-items-center justify-content-center mx-auto">
                    <div class="max-w-750-px w-100 text-center mx-auto">
                        <span class="fw-normal tw-text-405 text-main-600 tw-mb-2">
                           Unleash Your Potential
                        </span>
                        <h2 class="fw-bold text-neutral-950 h4">
                           Curriculum
                        </h2>
                        <p>Explore our carefully curated courses designed to elevate your chess skills, whether you're a beginner or an aspiring master. Each course is tailored to ensure maximum learning and development.</p>
                    </div>
                </div>
                {{-- <img src="/frontend1/assets/images/shape/banner-shape3.png" alt="shape"
                    class="animation-upDown d-xl-block d-none"> --}}
            </div>
            <div class="grid-col-4">
                <div class="" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <a href="#0"
                        style="background-image: url(/frontend1/assets/images/bg/our-program-three-bg-img1.png);"
                        class="bg-img tw-pt-7 tw-pb-17 animation-item tw-px-6 text-center tw-transform-roted-7">
                        <span class="w-100 tw-mb-4 overflow-hidden tw-rounded-3xl" style="background-color: #fff;">
                            <img src="https://archerchessacademy.com/frontend/tcul_img/webp/home/home-course-1.webp" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <h1 class="fw-bold tw-text-30-px text-white d-block tw-mb-3 h5">
                           Beginners
                        </h1>
                        <p class="fw-normal tw-text-4 text-white tw-mb-8">
                            Are you looking to learn the basics of chess? Our beginner's course is designed to introduce you to the fundamentals of the game.
                        </p>
                        <span
                            class="tw-w-10 tw-h-10 border-white border tw-text-405 text-white rounded-circle d-flex justify-content-center hover-bg-white hover-text-neutral-950 tw-duration-300 align-items-center mx-auto">
                            <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </a>
                </div>
                <div class="" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <a href="#0"
                        style="background-image: url(/frontend1/assets/images/bg/our-program-three-bg-img2.png);"
                        class="bg-img tw-pt-7 tw-pb-17 animation-item tw-px-6 text-center tw-transform-roted--9 tw_ms--26-px">
                        <span class="w-100 tw-mb-4 overflow-hidden tw-rounded-3xl" style="background-color: #fff;">
                            <img src="https://archerchessacademy.com/frontend/tcul_img/webp/home/home-course-2.webp" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <h1 class="fw-bold tw-text-30-px text-white d-block tw-mb-3 h5">
                            Intermediate
                        </h1>
                        <p class="fw-normal tw-text-4 text-white tw-mb-8">
                           The intermediate level marks a significant milestone in one's journey towards mastery. At this stage, players have gained a solid understanding of the game's fundamentals and are ready to delve deeper into strategic concepts and tactical intricacies.
                        </p>
                        <span
                            class="tw-w-10 tw-h-10 border-white border tw-text-405 text-white rounded-circle d-flex justify-content-center hover-bg-white hover-text-neutral-950 tw-duration-300 align-items-center mx-auto">
                            <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </a>
                </div>
                <div class="" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <a href="#0"
                        style="background-image: url(/frontend1/assets/images/bg/our-process-four-bg-img1.png);"
                        class="bg-img tw-pt-7 tw-pb-17 animation-item tw-px-6 text-center tw-transform-roted-7 tw-ms--60-px">
                        <span class="w-100 tw-mb-4 overflow-hidden tw-rounded-3xl" style="background-color: #fff;">
                            <img src="https://archerchessacademy.com/frontend/tcul_img/webp/home/home-course-3.webp" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <h1 class="fw-bold tw-text-30-px text-white d-block tw-mb-3 h5">
                            Advanced
                        </h1>
                        <p class="fw-normal tw-text-4 text-white tw-mb-8">
                           Advanced players possess a deep knowledge of opening theory, enabling them to navigate through the complexities of various openings with precision and flexibility. 
                        </p>
                        <span
                            class="tw-w-10 tw-h-10 border-white border tw-text-405 text-white rounded-circle d-flex justify-content-center hover-bg-white hover-text-neutral-950 tw-duration-300 align-items-center mx-auto">
                            <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </a>
                </div>
                <div class="" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                    <a href="#0"
                        style="background-image: url(/frontend1/assets/images/bg/our-program-three-bg-img3.png);"
                        class="bg-img tw-pt-7 tw-pb-17 animation-item tw-px-6 text-center tw_transform-roted--9 tw-ms--99-px">
                        <span class="w-100 tw-mb-4 overflow-hidden tw-rounded-3xl" style="background-color: #fff;">
                            <img src="https://archerchessacademy.com/frontend/tcul_img/webp/home/home-course-4.webp" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <h1 class="fw-bold tw-text-30-px text-white d-block tw-mb-3 h5">
                           Expert
                        </h1>
                        <p class="fw-normal tw-text-4 text-white tw-mb-8">
                            At the expert level in chess, players demonstrate a deep understanding of both tactics and strategy, consistently making precise moves while foreseeing complex combinations many moves ahead.
                        </p>
                        <span
                            class="tw-w-10 tw-h-10 border-white border tw-text-405 text-white rounded-circle d-flex justify-content-center hover-bg-white hover-text-neutral-950 tw-duration-300 align-items-center mx-auto">
                            <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================ Course Structure ============================ -->
    <section id="courses" class="py-80 bg-purple-500 position-relative z-2 overflow-hidden">
         <img src="/frontend1/assets/images/shape/our-kindergarden-four-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        <img src="/frontend1/assets/images/shape/our-kindergarden-four-shape2.png" alt="shape"
            class="position-absolute top-0 d-xl-block d-none tw-end-0">
        <div class="container mw-95">
            <div class="text-center tw-mb-12">
                <h4 class="fw-bold text-white">
                   Course Structure
                </h4>
                <p class="text-white">From beginner to expert – structured online chess classes with weekly sessions, tournaments, puzzles, and GM-led activities.</p>
            </div>
            <div class="row gy-4 align-items-stretch">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="accordion-shadow bg-white tw-rounded-3xl tw-px-3 tw-pt-6 tw-pb-6 h-100">
                        <div class="d-flex align-items-center tw-gap-3 justify-content-center flex-wrap">
                            <div class="text-center">
                                <span class="fw-bold tw-text-6 text-main-600 tw-mb-0">
                                    Beginners
                                </span>
                                <div class="d-flex align-items-end tw-gap-1">
                                    <span class="fw-normal tw-text-4 text-paragraph-600">
                                       Explore The World Of Chess
                                    </span>
                                </div>
                            </div>
                            {{-- <img src="/frontend1/assets/images/icon/our-pricing-icon1.png" alt="icon" class="tw-w-16 tw-h-16"> --}}
                        </div>
                        <span class="w-100 bg-main-two-300 tw-h-px tw-mb-4 tw-mt-2"></span>
                        <div class="tw-mb-7">
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    30 Training Sessions – step-by-step foundation
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    15+ Online Tournaments – real practice
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    15+ Opening Classes – chess openings & strategies 
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Live Puzzle Homework – daily practice portal
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    PDF Theory Material – GM-recommended content
                                </span>
                            </div>
                        </div>
                        <a href="#0" class="btn btn-main-two w-100 hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Explore More</span>
                            <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="accordion-shadow bg-white tw-rounded-3xl tw-px-3 tw-pt-6 tw-pb-6 h-100">
                        <div class="d-flex align-items-center tw-gap-3 justify-content-center flex-wrap">
                            <div class="text-center">
                                <span class="fw-bold tw-text-6 text-gren-600 tw-mb-0">
                                Intermediate
                                </span>
                                <div class="d-flex align-items-end tw-gap-1">
                                    <span class="fw-normal tw-text-4 text-paragraph-600">
                                        Road to Championship
                                    </span>
                                </div>
                            </div>
                            {{-- <img src="/frontend1/assets/images/icon/our-pricing-icon2.png" alt="icon" class="tw-w-16 tw-h-16"> --}}
                        </div>
                        <span class="w-100 bg-main-two-300 tw-h-px tw-mb-4 tw-mt-2"></span>
                        <div class="tw-mb-7">
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    60 Training Sessions – strategy & endgames
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    30+ Online Tournaments – sharpen skills
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    30+ Opening Classes – advanced variations 
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Live Puzzle Homework – daily challenges
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    PDF Theory Material – structured GM notes
                                </span>
                            </div>
                        </div>
                        <a href="#0" class="btn btn-main-two w-100 hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Explore More</span>
                            <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="accordion-shadow bg-white tw-rounded-3xl tw-px-3 tw-pt-6 tw-pb-6 h-100">
                        <div class="d-flex align-items-center tw-gap-3 justify-content-center flex-wrap">
                            <div class="text-center">
                                <span class="fw-bold tw-text-6 text-main-600 tw-mb-0">
                                Advanced
                                </span>
                                <div class="d-flex align-items-end tw-gap-1">
                                    <span class="fw-normal tw-text-4 text-paragraph-600">
                                       Ready To Get FIDE Rating
                                    </span>
                                </div>
                            </div>
                            {{-- <img src="/frontend1/assets/images/icon/our-pricing-icon4.png" alt="icon" class="tw-w-16 tw-h-16"> --}}
                        </div>
                        <span class="w-100 bg-main-two-300 tw-h-px tw-mb-4 tw-mt-2"></span>
                        <div class="tw-mb-7">
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    60 Training Sessions – advanced strategy
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    C30+ Online Tournaments – stronger opponents
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                   30+ Opening Classes – deep strategic theory
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Live Puzzle Homework – tactical exercises
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-5">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    PDF Theory Material – GM designed content
                                </span>
                            </div>
                        </div>
                        <a href="#0" class="btn btn-main-two w-100 hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-mb-2" data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Explore More</span>
                            <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="accordion-shadow bg-white tw-rounded-3xl tw-px-3 tw-pt-6 tw-pb-6 h-100">
                        <div class="d-flex align-items-center tw-gap-3 justify-content-center flex-wrap">
                            <div class="text-center">
                                <span class="fw-bold tw-text-6 text-gren-600 tw-mb-0">
                               Expert
                                </span>
                                <div class="d-flex align-items-end tw-gap-1">
                                    <span class="fw-normal tw-text-4 text-paragraph-600">
                                        FIDE Rating Course
                                    </span>
                                </div>
                            </div>
                            {{-- <img src="/frontend1/assets/images/icon/our-pricing-icon4.png" alt="icon" class="tw-w-16 tw-h-16"> --}}
                        </div>
                        <span class="w-100 bg-main-two-300 tw-h-px tw-mb-4 tw-mt-2"></span>
                        <div class="tw-mb-7">
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Training Sessions – By GM/IM/FM Coaches
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Online Tournaments – pro-level practice
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Opening Repertoire Classes – By GM/IM/FM Coaches
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    Live Puzzle Homework – high-level tactics
                                </span>
                            </div>
                            <div class="d-flex align-items-center tw-gap-1 tw-mb-3">
                                <span class="tw-text-4 text-main-two-600">
                                    <i class="ph-bold ph-check"></i>
                                </span>
                                <span class="fw-normal tw-text-4 text-paragraph-600">
                                    PDF Theory Material – GM structured learning
                                </span>
                            </div>
                        </div>
                        <a href="#0" class="btn btn-main-two w-100 hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Explore More</span>
                            <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
    <!-- ====================== testimonial section start ====================== -->
    <section class="py-50 tw-pt-220-px bg-main-two-100 position-relative z-2">
        <img src="/frontend1/assets/images/shape/choos-us-four-shape1.png" alt="shape" class="position-absolute bottom-0 tw-start-0 w-100 z-n1">
        <div class="container max-w-1380-px">
            <div class="row gy-4 align-items-center">
                <div class="col-xl-5">
                    <div>
                        {{-- <span class="fw-normal tw-text-405 text-main-600 tw-mb-2" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="100">
                           They Trusted us
                        </span> --}}
                        <h2 class="fw-bold text-neutral-950 tw-mb-0 h4" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="200">Because, Parents Trusted us</h2>
                        <p class="fw-normal tw-text-5 text-paragraph-500 tw-mb-6 aos-init aos-animate" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">Our success comes from the happiness and growth of our students.</p>

                        <div class="d-flex align-items-center tw-gap-5 flex-wrap" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="300">
                            <div>
                                <img src="/frontend1/assets/images/thumbs/testimonial-three-img1.png" alt="img"
                                    class="hover-scale-2 tw-duration-300">
                                <img src="/frontend1/assets/images/thumbs/testimonial-three-img2.png" alt="img"
                                    class="tw-ms--26-px hover-scale-2 tw-duration-300">
                                <img src="/frontend1/assets/images/thumbs/testimonial-three-img3.png" alt="img"
                                    class="tw-ms--26-px">
                            </div>
                            <div>
                                <img src="/frontend1/assets/images/logo/goole-logo.png" alt="logo"
                                    class="tw-mb-1">
                                <div class="d-flex align-items-center tw-gap-2">
                                    <span class="fw-bold tw-text-7 text-neutral-950">
                                        4.8
                                    </span>
                                    <span class="fw-normal text-neutral-600 tw-text-405">From 250+ reviews</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7" data-aos="zoom-in" data-aos-duration="1500">
                    <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-md-nowrap flex-wrap">
                        <button type="button" id="testimonial-three-button-prev"
                            class="tw-w-13 tw-h-13 bg-main-100 text-main-600 rounded-circle hover-bg-main-600 hover-text-white tw-duration-300 tw-text-6 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="ph-bold ph-arrow-left"></i>
                        </button>
                        <div class="max-w-580-px w-100 position-relative">
                            <div class="swiper testimonial-three-button-slider">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div
                                            class="bg-white tw-rounded-3xl tw-border-teacher-main-600 tw-py-16 tw-px-12">
                                            <div class="">
                                                <ul class="d-flex align-items-center tw-gap-1 tw-mb-4">
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                </ul>
                                                <p class="fw-bold tw-text-6 text-neutral-950 line-height-40-px">
                                                    "It is awesome to connect with your academy, feeling more comfortable and
                                            logical than others. Thanks to Archer Academy 🙏🏻 "
                                                </p>
                                            </div>
                                        </div>
                                        <div class="tw-mt-6 d-flex align-items-center tw-gap-3">
                                            <span>
                                                <img src="/frontend1/assets/images/thumbs/testimonial-three-img4.png"
                                                    alt="img">
                                            </span>
                                            <div>
                                                <span class="fw-bold tw-text-405 text-neutral-950 d-block tw-mb-1">
                                                    John Smith
                                                </span>
                                                <span class="fw-normal tw-text-405 text-neutral-600">
                                                    Student Father
                                                </span>
                                            </div>
                                        </div>
                                        <div class="position-absolute bottom-0 tw-end-0 tw-me-9 tw-mb-6">
                                            <img src="/frontend1/assets/images/icon/testimonial-three-icon1.png"
                                                alt="icon">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div
                                            class="bg-white tw-rounded-3xl tw-border-teacher-main-600 tw-py-16 tw-px-12">
                                            <div class="">
                                                <ul class="d-flex align-items-center tw-gap-1 tw-mb-4">
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                    <li class="tw-text-6 text-main-600">
                                                        <i class="ph-fill ph-star"></i>
                                                    </li>
                                                </ul>
                                                <p class="fw-bold tw-text-6 text-neutral-950 line-height-40-px">
                                                   "The instructors have been incredibly knowledgeable and skilled, and they
                                            have done an amazing job of conveying their expertise in a way that is
                                            easy to understand and follow. The lessons are well-planned and
                                            organized, and the materials provided are informative and helpful."
                                                </p>
                                            </div>
                                        </div>
                                        <div class="tw-mt-6 d-flex align-items-center tw-gap-3">
                                            <span>
                                                <img src="/frontend1/assets/images/thumbs/testimonial-three-img4.png"
                                                    alt="img">
                                            </span>
                                            <div>
                                                <span class="fw-bold tw-text-405 text-neutral-950 d-block tw-mb-1">
                                                    John Smith
                                                </span>
                                                <span class="fw-normal tw-text-405 text-neutral-600">
                                                    Student Father
                                                </span>
                                            </div>
                                        </div>
                                        <div class="position-absolute bottom-0 tw-end-0 tw-me-9 tw-mb-6">
                                            <img src="/frontend1/assets/images/icon/testimonial-three-icon1.png"
                                                alt="icon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="testimonial-three-button-next"
                            class="tw-w-13 tw-h-13 bg-main-100 text-main-600 rounded-circle hover-bg-main-600 hover-text-white tw-duration-300 tw-text-6 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- ========================== our team section start ========================== -->
    <section id="tutors" class="py-80 position-relative">
        <img src="/frontend1/assets/images/shape/our-team-shape1.png" alt="shape" class="position-absolute bottom-0 tw-end-0 tw-me-130-px tw-mb-110-px d-lg-block d-none z-n1 animation-upDown">
        {{-- <img src="/frontend1/assets/images/shape/our-team-shape2.png" alt="shape" class="position-absolute top-0 tw-start-0 d-lg-block d-none tw-mt-186-px tw-ms-200-px animation-upDown"> --}}
        <div class="container">
            <div class="text-center tw-mb-12">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Our Teachers</span>
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">Meet With Our Experienced <br> Kids Teachers</h2>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 " data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                    <div class="our-teacher-thumb tw-transform-roted-5">
                        <div class="position-relative bg-img">
                            <a href="#0" class="bg-img">
                                <img src="/frontend1/tcul-img/home/tutor1.png" alt="img" class="bg-img">
                            </a>
                        </div>
                        <div class="text-center tw-mt-6">
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                    Samyak Nayak
                                </a>
                            </h2>
                            <span class="fw-normal tw-text-405 text-neutral-600">Fide Rating : 2000</span>
                            <span class="fw-normal tw-text-405 text-main-two-600"><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 " data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                    <div class="our-teacher-thumb tw-transform-roted--5">
                        <div class="position-relative bg-img">
                            <a href="#0" class="bg-img">
                                <img src="/frontend1/tcul-img/home/tutor2.png" alt="img" class="bg-img">
                            </a>
                        </div>
                        <div class="text-center tw-mt-6">
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                Toyesh Singh
                                </a>
                            </h2>
                            <span class="fw-normal tw-text-405 text-neutral-600">Fide Rating : 2000</span>
                            <span class="fw-normal tw-text-405 text-main-two-600"><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 " data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                    <div class="our-teacher-thumb tw-transform-roted-5">
                        <div class="position-relative bg-img">
                            <a href="#0" class="bg-img">
                                <img src="/frontend1/tcul-img/home/tutor3.png" alt="img" class="bg-img">
                            </a>
                        </div>
                        <div class="text-center tw-mt-6">
                            <h2 class="h5">
                                <a href="#0" class="fw-bold text-neutral-950 tw-mb-2">
                                Dr. Sunanya
                                </a>
                            </h2>
                            <span class="fw-normal tw-text-405 text-neutral-600">Fide Rating : 2000</span>
                            <span class="fw-normal tw-text-405 text-main-two-600"><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================== Achievements ====================== -->
    <div class="py-50">
        <div class="container">
            <div class="text-center tw-mb-12">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-2" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="100">Proven Success</span>
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">Achievements</h2>
                <p>Over the years, our students have achieved remarkable success, consistently outperforming their peers in tournaments and chess rankings. Our dedication to excellence is reflected in the numerous awards and accolades our students have garnered.</p>
            </div>
            <div class="row gy-4">
                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <div class="d-flex align-items-start tw-gap-5 animation-item">
                    <span>
                    <img src="/frontend1/assets/images/icon/our-galler-bottom-icon1.png" alt="icon" class="animate__swing">
                    </span>
                    <div>
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-3 d-block">TRAINED STUDENTS</span>
                    <h3 class="fw-bold text-main-600 counter">5000+</h3>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="d-flex align-items-start tw-gap-5 animation-item">
                    <span>
                    <img src="/frontend1/tcul-img/home/fide.png" alt="icon" class="animate__swing">
                    </span>
                    <div>
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-3 d-block">FIDE RATED</span>
                    <h3 class="fw-bold text-main-600 counter">52+</h3>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <div class="d-flex align-items-start tw-gap-5 animation-item">
                    <span>
                    <img src="/frontend1/tcul-img/home/countries.png" alt="icon" class="animate__swing">
                    </span>
                    <div>
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-3 d-block">COUNTRIES</span>
                    <h3 class="fw-bold text-main-600 counter">8+</h3>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                <div class="d-flex align-items-start tw-gap-5 animation-item">
                    <span>
                    <img src="/frontend1/tcul-img/home/champions.png" alt="icon" class="animate__swing">
                    </span>
                    <div>
                    <span class="fw-semibold tw-text-405 text-neutral-600 tw-mb-3 d-block">CHAMPIONS</span>
                    <h3 class="fw-bold text-main-600 counter">100+</h3>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================== Our Archer Kids ========================== -->
    <div id="our-kids" class="py-50">
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
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery2.png" alt="">
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery3.png" alt="">
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery4.png" alt="">
                    </div>
                </div>
                <div class="swiper-slide">
                    <div>
                        <img src="/frontend1/tcul-img/gallery/gallery5.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <!-- =========================== Book a Free Trial =========================== -->
    <section class="py-50 position-relative">
        {{-- <img src="/frontend1/assets/images/shape/brand-slider-shape1.png" alt="shape" class="position-absolute bottom-0 tw-end-0 tw-me-180-px tw-mb--70-px z-3"> --}}
        <div class="container">
            <div class="tw-mb-15">
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Book a Free Trial</h2>

                <span class="fw-normal text-paragraph-500 text-main-600 tw-mb-0 aos-init aos-animate" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Welcome! Please fill out this form to confirm your booking.</span> <br>
                <p><span class="fw-normal text-paragraph-500 text-main-600 tw-mb-2 aos-init aos-animate" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">Note: </span>We will only communicate by <b>WhatsApp</b>, so please provide your WhatsApp number.</p>
                
            </div>
            <div class="row gy-4">
                <div class="col-lg-12">
                    <form action="#">
                        <div class="row gy-4">

                            <!-- Country -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Country*</label>
                                <select class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                                    <option>Select Country</option>
                                    <option>India</option>
                                    <option>USA</option>
                                    <option>UAE</option>
                                    <option>UK</option>
                                    <option>Canada</option>
                                </select>
                            </div>

                            <!-- Timezone -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Timezone*</label>
                                <select class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                                    <option>Select Time Zone</option>
                                    <option>IST</option>
                                    <option>EST</option>
                                    <option>GMT</option>
                                    <option>GST</option>
                                </select>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">City*</label>
                                <input type="text"
                                    placeholder="Enter your city"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                            </div>

                            <!-- Kid Name -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Kid's Full Name*</label>
                                <input type="text"
                                    placeholder="Enter kid's full name"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                            </div>

                            <!-- Age -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Age*</label>
                                <input type="number"
                                    placeholder="Enter kid's age"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                            </div>

                            <!-- WhatsApp -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">WhatsApp Number*</label>
                                <input type="tel"
                                    placeholder="Enter WhatsApp number"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Email*</label>
                                <input type="email"
                                    placeholder="Enter your email"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                            </div>

                            <!-- Language -->
                            <div class="col-md-6">
                                <label class="fw-semibold tw-text-4 mb-2">Language Preference*</label>
                                <select class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                                    <option>Select language preference</option>
                                    <option>English</option>
                                    <option>German</option>
                                    <option>French</option>
                                    <option>Spanish</option>
                                </select>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <button type="submit" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-mt-5" data-block="button" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                <span class="button__flair"></span>
                <span class="button__label">Submit</span>
                <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                </span>
            </button>
        </div>
    </section>


    <!-- ========================= Latest Blogs  ========================= -->
    <section class="py-50 position-relative">
        {{-- <img src="/frontend1/assets/images/shape/blog-shape1.png" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-260-px tw-me-130-px z-n1 d-xl-block d-none animation-upDown">
        <img src="/frontend1/assets/images/shape/blog-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 z-n1 d-xl-block d-none animation-upDown tw-mt--170-px tw-ms-100-px"> --}}
        <div class="container">
            <div class="text-center tw-mb-12">
                {{-- <span class="fw-normal tw-text-405 text-main-600 tw-mb-6" data-aos="fade-up"
                    data-aos-duration="600" data-aos-delay="100">Latest Blogs</span> --}}
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">Latest Blogs</h2>
                <p>Follow the latest and most useful articles on that student's blog</p>
            </div>
            <div class="row gy-5">
                <div class="col-xl-4 col-lg-6 col-md-6" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="100">
                    <div class="animation-item">
                        <span class="overflow-hidden tw-rounded-20-px tw-rounded-top-20-px w-100">
                            <img src="https://archerchessacademy.com/storage/blog/cover_img/AnPazn9epomZDL3EqzTAw6lB3bUKRapCVDbIo5tl.jpg" alt="img"
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
                            <img src="https://archerchessacademy.com/storage/blog/cover_img/p44cjFmyFTnOt4r1MyaDFf9YdBvucjSlfyrYZfVz.jpg" alt="img"
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
                            <img src="https://archerchessacademy.com/storage/blog/cover_img/zxssjfhXfGC4xICZspI5Ks3Ne6ZKJ47fXr3nwxT9.jpg" alt="img"
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
    </section>

    <!-- ========================= FAQ's  ========================= -->

    <section class="py-50">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-5" data-aos="zoom-in" data-aos-duration="1500">
                    <span class="bg-img">
                        <img src="/frontend1/tcul-img/home/faq.png" alt="img" class="bg-img">
                    </span>
                </div>
                <div class="col-lg-7">
                    <div class="tw-ps-8">
                        <div class="tw-mb-6">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-5" data-aos-delay="100" data-aos="fade-up" data-aos-duration="600">
                                FAQ’s
                            </span>
                            <h4 class="fw-bold text-neutral-950" data-aos-delay="200" data-aos="fade-up" data-aos-duration="600">
                                Most frequently asked questions
                            </h4>
                            <p>Here are the most frequently asked questions you may check before getting started</p>
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

    
    <!-- ==================== Footer Start Here ==================== -->
    <footer class="footer bg-main-two-300 position-relative z-2">
        <img src="/frontend1/assets/images/shape/our-program-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        {{-- <img src="/frontend1/assets/images/shape/newslater-three-shape2.png" alt="shape"
            class="position-absolute top-0 tw-start-0 animation-upDown d-xl-block d-none tw-ms-140-px"> --}}
        <div class="py-50">
            <div class="mt-110">
                <!-- =================== newslater section start =================== -->
                {{-- <div class="pb-110 position-relative">
                    <img src="/frontend1/assets/images/shape/newslater-three-shape1.png" alt="shape"
                        class="position-absolute tw-end-0 top-0 d-xl-block d-none animation-upDown">
                    <div class="container max-w-780-px">
                        <div class="text-center">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-6" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="100">
                                Subscribe Newsletter
                            </span>
                            <h2 class="fw-bold text-neutral-950 tw-mb-6 h4" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="200">
                                Subscribe to our newsletter<br> for daily updates
                            </h2>
                            <form action="#" data-aos="fade-up" data-aos-duration="800"
                                data-aos-delay="300">
                                <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap">
                                    <input type="email" placeholder="Enter your email"
                                        class="fw-normal tw-text-4 text-neutral-600 tw-py-4 tw-px-6 w-100 tw-rounded-2xl border focus-visible-border-main-600">
                                    <button type="submit"
                                        class="btn btn-main hover-style-one button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-whiter-600 max-w-200-px w-100"
                                        data-block="button">
                                        <span class="button__flair"></span>
                                        <span class="button__label">Subscribe Now</span>
                                        <span
                                            class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                            <img src="/frontend1/assets/images/icon/banner-icon-white.png"
                                                alt="icon">
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}
                <!-- =================== newslater section end =================== -->

                <!-- =================== footer top section start ==================== -->
                <div class="container">
                    <div class="row gy-4">
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800"
                            data-aos-delay="100">
                            <div class="d-flex align-items-start tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-main-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon1.png" alt="icon">
                                </span>
                                <div>
                                    <span
                                        class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Call Us
                                    </span>
                                    <h2 class="h5">
                                        <a href="tel:+919152734675" class="fw-semibold text-neutral-950">
                                           +91-9152734675
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-duration="800"
                            data-aos-delay="300">
                            <div class="d-flex align-items-start tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-pink-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon3.png" alt="icon">
                                </span>
                                <div>
                                    <span class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Location
                                    </span>
                                    <h2 class="fw-semibold text-neutral-950 h5">
                                        5A, Shatrunjay Height Co-Op Housing Society, Near Kailash Mansarovar, Bhayandar West - 401101, Mumbai
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="800"
                            data-aos-delay="200">
                            <div class="d-flex align-items-start tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon2.png" alt="icon">
                                </span>
                                <div>
                                    <span class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Support
                                    </span>
                                    <h2 class="h5">
                                        <a href="mailto:support@archerchessacademy.com" class="fw-semibold text-neutral-950">
                                            support@archerchessacademy.com
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="w-100 tw-border-primary-600 tw-mt-15 tw-mb-15"></span>
                </div>
                <!-- =================== footer top section end ==================== -->
                <div class="position-relative">
                    <img src="/frontend1/assets/images/shape/newsletter-shape2.png" alt="shape"
                        class="position-absolute top-0 tw-start-0 tw-mt-15 tw-ms-50-px animation-rotate-right d-xl-block d-none">
                    <img src="/frontend1/tcul-img/home/footer-icon.svg" alt="shape"
                        class="position-absolute top-0 tw-end-0 tw-mt-15  tw-me-50-px animation-scalation d-xl-block d-none">
                    <div class="container container-two">
                        <div class="row gy-5">
                            <div class="col-xl-3 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="100">
                                <div>
                                    <a href="/design/home" class="tw-mb-2">
                                        <img src="/frontend1/tcul-img/logo/archer-logo.svg" alt="logo">
                                    </a>
                                    <p class="fw-normal tw-text-4 text-neutral-950 tw-mb-6">Give your child the opportunity to learn and master chess from the comfort of their home.</p>
                                    
                                    <div class="d-flex align-items-center tw-gap-4 flex-wrap">
                                        <img class="w-25" src="https://archerchessacademy.com/frontend/tcul_img/webp/iso-certification.webp" alt="logo">
                                        <p class="fw-semibold tw-text-4 text-neutral-950 tw-mb-6">ISO CERTIFIED</p>
                                    </div>
                                    
                                    <ul class="d-flex align-items-center tw-gap-2 flex-wrap mt-3">
                                        <li>
                                            <a target="_blank" href="https://www.facebook.com/makekidssmarter"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-facebook-logo"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://x.com/ArcherChess"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-twitter-logo"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://www.youtube.com/@archerchessacademy4376"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-linkedin-logo"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://www.youtube.com/"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-youtube-logo"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="mailto:support@archerchessacademy.com"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-envelope"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://www.instagram.com/archerchessacademy/"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-instagram-logo"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="https://www.linkedin.com/company/archer-chess-academy/"
                                                class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                                <i class="ph-fill ph-linkedin-logo"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="200">
                                <div>
                                    <h2 class="fw-bold text-neutral-950 h5">Quick Links</h2>
                                    <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                    <div class="d-flex flex-column tw-gap-3">
                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            About
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Why Archer
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Courses
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Our Tutors
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Contact
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Gallery
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="200">
                                <div>
                                    <h2 class="fw-bold text-neutral-950 h5">Useful Links</h2>
                                    <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                    <div class="d-flex flex-column tw-gap-3">
                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Book A Trial Class
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Privacy Policy
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Shipping Policy
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Terms Of Service
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Refund & Cancellation
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Blog
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            Event
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="300">
                                <div>
                                    <h2 class="fw-bold text-neutral-950 h5">Online Chess</h2>
                                    <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                    <div class="d-flex flex-column tw-gap-3">
                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            INDIA
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            USA
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            MIDDLE EAST
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            SINGAPORE
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            UAE
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            UNITED KINGDOM
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            AUSTRALIA
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            CANADA
                                        </a>

                                        <a href="#0"
                                            class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                            <span class="tw-text-405">
                                                <i class="ph-bold ph-caret-double-right"></i>
                                            </span>
                                            EUROPEAN UNION
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                                data-aos-duration="800" data-aos-delay="350">
                                <div>
                                    <h2 class="fw-bold text-neutral-950 h5">Address</h2>
                                    <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                    <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                        <div class="">
                                            <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                                <span class="tw-text-4 text-main-600 ">
                                                    <i class="ph-fill ph-crown"></i>
                                                </span>
                                                <span class="fw-medium tw-text-305 text-paragraph-600">
                                                    Archer Chess Academy Private Limited
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                        <div class="">
                                            <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                                <span class="tw-text-4 text-main-600 ">
                                                    <i class="ph-fill ph-navigation-arrow"></i>
                                                </span>
                                                <span class="fw-medium tw-text-305 text-paragraph-600 tw-text-4">
                                                     5A, Shatrunjay Height Co-Op Housing Society, Near Kailash Mansarovar, Bhayandar West - 401101, Mumbai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                        <div class="">
                                            <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                                <span class="tw-text-4 text-main-600 ">
                                                    <i class="ph-fill ph-envelope"></i>
                                                </span>
                                                <span class="fw-medium tw-text-305 text-paragraph-600 tw-text-4">
                                                    support@archerchessacademy.com
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                        <div class="">
                                            <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                                <span class="tw-text-4 text-main-600 ">
                                                    <i class="ph-fill ph-phone-call"></i>
                                                </span>
                                                <span class="fw-medium tw-text-305 text-paragraph-600 tw-text-4">
                                                    91-9152734675
                                                </span>
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
        <!-- ====================== footer bottom section start ====================== -->
        <div style="background-image: url(/frontend1/assets/images/bg/footer-bottom-bg-img1.png);" class="bg-img">
            <div class="container">
                <div class="d-flex align-items-center tw-gap-3 flex-wrap justify-content-between tw-pt-14 tw-pb-6">
                    <span class="fw-normal tw-text-4 text-black">© 2026 Archer Chess Academy. All Rights Reserved</span>
                    <div class="d-flex align-items-center tw-gap-4 flex-wrap">
                        <a href="javascript:void(0)"
                            class="fw-normal tw-text-4 text-black hover-text-black hover-underline tw-duration-300">
                            Developed by <a href="https://technicul.com/" target="_blank">Technicul Cloud LLP</a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ====================== footer bottom section end ====================== -->
    </footer>
    <!-- ==================== Footer End Here ==================== -->


    <!-- Jquery js -->
    <script src="/frontend1/assets/js/jquery-3.7.1.min.js"></script>
    <!-- phosphor Js -->
    <script src="/frontend1/assets/js/phosphor-icon.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="/frontend1/assets/js/boostrap.bundle.min.js"></script>
    <!-- swiper js -->
    <script src="/frontend1/assets/js/swiper-bundle.min.js"></script>
    <!-- aos -->
    <script src="/frontend1/assets/js/aos.js"></script>
    <!-- magnipic -->
    <script src="/frontend1/assets/js/magnipic-popup.js"></script>
    <!-- couterup -->
    <script src="/frontend1/assets/js/counterup.min.js"></script>
    <!-- GSAP js -->
    <script src="/frontend1/assets/js/gsap.min.js"></script>
    <!-- SplitText -->
    <script src="/frontend1/assets/js/SplitText.min.js"></script>
    <!-- Scroll Trigger -->
    <script src="/frontend1/assets/js/ScrollTrigger.min.js"></script>
    <!-- custom GSAP -->
    <script src="/frontend1/assets/js/custom-gsap.js"></script>

    <!-- main js -->
    <script src="/frontend1/assets/js/main.js"></script>


</body>

</html>
