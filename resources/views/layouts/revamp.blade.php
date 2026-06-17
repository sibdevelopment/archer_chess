<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Archers Chess Academy is an online chess learning platform dedicated to providing high-quality chess education to students of all ages and skill levels. Our academy offers a comprehensive curriculum designed to help students develop their chess skills, strategic thinking, and love for the game. With experienced coaches, interactive lessons, and a supportive community, Archers Chess Academy is the perfect place for aspiring chess players to learn, grow, and excel in the world of chess.">
    <meta name="keywords"
        content="Archers Chess Academy, Chess Learning, Online Chess Education, Chess Coaching, Chess Lessons, Chess Community, Chess Skills, Strategic Thinking, Chess Curriculum">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>{{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="/frontend/tcul_img/home/archer_favicon.png" type="image/png">
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

    <link rel="stylesheet" href="/frontend1/assets/css/tcul1.css">
    <link rel="stylesheet" href="/frontend1/assets/css/custom.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pangolin&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>

    <style>
        /* Mobile */
        @media (max-width: 767px) {
                .top-header{
                padding-left: 4px !important;
                padding-right: 4px !important;
                padding-top: 12px !important;
                padding-bottom: 12px !important;
            }
        }

         .tcul-floating_btn a {
            text-decoration: none;
        }

        .tcul-floating_btn_contact {
            position: fixed;
            bottom: 110px;
            right: 2px;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .tcul-floating_btn_contact_mobile {
            position: fixed;
            bottom: 5px;
            right: 2px;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .tcul-floating_btn_whatsapp {
            position: fixed;
            bottom: 50px;
            right: 2px;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .tcul-floating_btn_whatsapp_mobile {
            position: fixed;
            bottom: 5px;
            right: 0px;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .tcul-floating_btn_country {
            position: fixed;
            bottom: 170px;
            /* Adjust the position as needed */
            right: 2px;
            width: 80px;
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        @keyframes pulsing {
            to {
                box-shadow: 0 0 0 30px rgba(232, 76, 61, 0);
            }
        }

        .tcul-phone_icon {
            background-color: #007bff;
            colornew_enrollment: #fff;
            width: 40px;
            height: 40px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            /* box-shadow: 2px 2px 3px rgb(153, 153, 153); */
            display: flex;
            align-items: center;
            justify-content: center;
            /* transform: translatey(0px);
        animation: pulse 1.5s infinite; */
            /* box-shadow: 0 0 0 0 #007bff; */
            /* -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
        -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
        -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
        animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1); */
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
        }

        .tcul-contact_icon {
            background-color: #42db87;
            color: #fff;
            width: 280px;
            height: 50px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translatey(0px);
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 0 0 #42db87;
            -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
            transition: all 300ms ease-in-out;
        }

        .tcul-contact_icon_mobile {
            background-color: #42db87;
            color: #fff;
            width: 50px;
            height: 50px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translatey(0px);
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 0 0 #42db87;
            -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
            transition: all 300ms ease-in-out;
        }

        .tcul-country_icon {
            background-color: #42db87;
            color: #fff;
            width: 240px;
            height: 50px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translatey(0px);
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 0 0 #42db87;
            -webkit-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -moz-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            -ms-animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            animation: pulsing 1.25s infinite cubic-bezier(0.66, 0, 0, 1);
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
            transition: all 300ms ease-in-out;
        }

        .tcul-text_icon {
            margin-top: 8px;
            color: #707070;
            font-size: 13px;
        }


        .book-class-online {
            background-color: #fff;
            color: #fff;
            width: 280px;
            height: 50px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
            transition: background-color 0.3s, color 0.3s;
            /* Smooth transition for hover effect */
        }

        .book-class-online:hover {
            box-shadow: rgba(255, 255, 255, 0.15) 0px 5px 15px 0px;
        }

        .contact-us {
            background-color: #fff;
            color: #fff;
            width: 240px;
            height: 50px;
            font-size: 20px;
            border-radius: 50px;
            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            /* box-shadow: 0 0 0 0 #007bff; */
            font-weight: normal;
            font-family: sans-serif;
            text-decoration: none !important;
        }

        .cursor {
            display: block;
            width: 20px;
            height: 20px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%);
            border-radius: 50%;
            background: transparent;
            pointer-events: none;
            z-index: 111;
            border: 1px solid #000;
            transition: all 0.2s ease-out;
            animation: moveCursor1 .5s infinite alternate;
        }

        .expand {
            background: transparent;
            animation: moveCursor2 .5s forwards;
            border: 1px solid #000;
        }
        @media (max-width: 767px) {
            .hide-mobile{
                display: none !important;
            }
            .tcul-contact_icon{
                width: 60px;
            }
            .tcul-floating_btn_whatsapp{
                bottom: 50%;
                right: 20px !important;
            }
            .tcul-floating_btn_contact{
                bottom: 10px;
                left: 120px !important;
            }
        }
    </style>
</head>

<body style="overflow-x: hidden;">

    <div class="tcul-floating_btn_contact" style="right: 130px;">
        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal">
            <div class="book-class-online">
                <img src="/frontend/assets/img/icon/icon-07.svg" alt="Img" class="img-fluid"
                    style=" width: 30px; height: auto;">
                <h6 class="mt-1 fs-14" style="color: #002058;"> &nbsp; BOOK YOUR FREE TRIAL</h6>
            </div>
        </a>
    </div>
    <div class="tcul-floating_btn_whatsapp" style="right: 130px;">
        <a target="_blank" href="https://api.whatsapp.com/send?phone=9152734675&text=hii">
            <div class="tcul-contact_icon">
                <img src="/frontend1/tcul-img/icons/whatsapp.svg" alt="">
                <h6 class="fs-14 hide-mobile" style="color: #fff;"> &nbsp; &nbsp; CHAT WITH US</h6>
            </div>
        </a>
    </div>
    {{-- <div class="tcul-floating-icons">
        <a target="_blank" href="https://wa.me/+919152734675" aria-label="Chat on WhatsApp">
            <div class="tcul-contact-icon whatsapp-icon">
                <i class="ph-bold ph-whatsapp-logo"></i>
            </div>
        </a>
    </div> --}}

    <!--==================== Preloader Start ====================-->
    {{-- <div class="loader-mask">
        <div class="loader">
            <div></div>
            <div></div>
        </div>
    </div> --}}
    <!--==================== Preloader End ====================-->

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

        {{-- <div class="mobile-menu__inner">
            <a href="/design/home" class="mobile-menu__logo">
                <img src="/frontend1/tcul-img/img/archer-new-logo.png" alt="Logo">
            </a>
            <div class="mobile-menu__menu">
                <!-- Nav menu Start -->
                <ul class="nav-menu d-lg-flex align-items-center nav-menu--mobile d-block tw-mt-8">
                    <li class="nav-menu__item has-submenu activePage">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Home</a>
                    </li>
                    <li class="nav-menu__item has-submenu position-relative">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Pages</a>
                        <ul
                            class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="#"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    About Us</a>
                            </li>
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="#"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Pricing Plan</a>
                            </li>
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="javascript:void(0)"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Image Gallery</a>
                            </li>
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="#"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    FAQ’s</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-menu__item">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Teachers</a>
                    </li>
                    <li class="nav-menu__item has-submenu position-relative">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Programs</a>
                        <ul
                            class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="javascript:void(0)"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Our Programs</a>
                            </li>
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="javascript:void(0)"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Program Details</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-menu__item has-submenu position-relative">
                        <a href="javascript:void(0)"
                            class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Blog</a>
                        <ul
                            class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="javascript:void(0)"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Our Blog & News</a>
                            </li>
                            <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                <a href="javascript:void(0)"
                                    class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                    Blog Details</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-menu__item">
                        <a href="#"
                            class="nav-menu__link text-neutral-950 tw-py-5 fw-medium w-100">Contact</a>
                    </li>
                </ul>
                <!-- Nav menu End  -->

            </div>
        </div> --}}
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->

    <!-- ================================ top header start ================================ -->
    <div class="bg-white">
        <div class="container menu-header">
            <div
                class="tw-pt-4 tw-pb-4 tw-px-6 border-bottom border-neutral-400 border-top-0 border-start-0 border-end-0 tw-border-dashed top-header">
                <div class="d-flex align-items-center tw-gap-3 justify-content-between">
                    <div class="d-xxl-block d-lg-block d-none">
                        <div class="d-flex align-items-center tw-gap-6">
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-phone"></i>
                                </span>
                                <a href="tel:+919152734675"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    +91-9152734675
                                </a>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-envelope-simple"></i>
                                </span>
                                <a href="mailto:support@archerchessacademy.com"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    support@archerchessacademy.com
                                </a>
                            </div>
                            <div class="d-flex align-items-center tw-gap-2 flex-wrap">
                                <span
                                    class="tw-w-8 tw-h-8 rounded-circle bg-main-two-600 d-flex align-items-center justify-content-center tw-text-base text-white">
                                    <i class="ph-fill ph-map-pin"></i>
                                </span>
                                <span class="fw-normal tw-text-305 text-black hover-text-main-600 tw-duration-300">
                                    Bhayandar West - 401101, Mumbai
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center tw-gap-4 mobile-justify-btwn">
                        <div class="d-flex align-items-center tw-gap-105">
                            <span class="tw-text-xl text-main-600 ">
                                <i class="ph-fill ph-user"></i>
                            </span>
                            <div class="d-flex align-items-center tw-gap-1">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600">
                                    Login
                                </a>
                                <span class="fw-normal text-black tw-text-305">
                                    /
                                </span>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                    class="fw-normal tw-text-305 text-black hover-text-main-600">
                                    Register
                                </a>
                            </div>
                        </div>
                        <div class="d-sm-block d-block">
                            <div class="d-flex align-items-center tw-gap-1">
                                <img src="/frontend1/tcul-img/img/india.svg" alt="img">
                                <select class="border-0 form-select" onchange="openCountrySite(this)">
                                    <option value="">Country</option>
                                    <option value="https://archerchessacademy.com/online-chess/india">India</option>
                                    <option value="https://archerchessacademy.com/online-chess/usa">USA</option>
                                    <option value="https://archerchessacademy.com/online-chess/middle-east">Middle East</option>
                                    <option value="https://archerchessacademy.com/online-chess/singapore">Singapore</option>
                                    <option value="https://archerchessacademy.com/online-chess/uae">UAE</option>
                                    <option value="https://archerchessacademy.com/online-chess/united-kingdom">United Kingdom</option>
                                    <option value="https://archerchessacademy.com/online-chess/australia">Australia</option>
                                    <option value="https://archerchessacademy.com/online-chess/canada">Canada</option>
                                    <option value="https://archerchessacademy.com/online-chess/european-union">European Union</option>
                                </select>

                            </div>
                        </div>
                        <div class="d-sm-block d-none">
                            <ul class="d-flex align-items-center tw-gap-3">
                                <li>
                                    <a target="_blank" href="https://www.facebook.com/makekidssmarter"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-fill ph-facebook-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://wa.me/919152734675"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.youtube.com/@archerchessacademy4376"
                                        class="tw-text-xl text-black hover-text-main-600 tw-duration-300">
                                        <i class="ph-fill ph-youtube-logo"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.instagram.com/archerchessacademy/"
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
    </div>
    <!-- ================================ top header end ================================ -->

    <div class="search_popup">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="search_wrapper">
                        <div class="search_top d-flex justify-content-between align-items-center">
                            <div class="search_logo">
                                <a href="/design/home">
                                    <img src="/frontend1/tcul-img/img/archer-new-logo.png" alt="Logo">
                                </a>
                            </div>
                            <div class="search_close">
                                <button type="button" class="search_close_btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17 1L1 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 1L17 17" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="search_form">
                            <form action="#">
                                <div class="search_input">
                                    <input class="search-input-field" type="text"
                                        placeholder="Type here to search...">
                                    <span class="search-focus-border"></span>
                                    <button type="submit">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-popup-overlay"></div>


    <!-- ==================== Header Start Here ==================== -->
    <header class="header bg-main-two-200 tw-transition-all tw-z-99">
        <div class="container container-two">
            <nav
                class="d-flex align-items-center justify-content-between position-relative bg-white tw-p-4 tw-rounded-bottom-16-px mobile-center mobile-logo-space">
                <!-- Logo Start -->
                <div class="logo">
                    <a href="/design/home" class="link">
                        <img src="/frontend1/tcul-img/img/archer-new-logo.png" alt="Logo" class="max-w-200-px">
                    </a>
                </div>
                <!-- Logo End  -->

                <!-- Menu Start  -->
                <div class="header-menu d-xl-block d-none">
                    <!-- Nav menu Start -->
                    <ul class="nav-menu d-lg-flex align-items-center tw-gap-6">
                        <li class="nav-menu__item">
                            <a href="/design/home"
                                class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Home</a>
                        </li>
                        <li class="nav-menu__item has-submenu position-relative">
                            <a href="#curriculum"
                                class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Programs</a>
                            <ul
                                class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#curriculum"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Beginners Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#curriculum"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Intermediate Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#curriculum0"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Advanced Chess Classes</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#curriculum"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Grand Master Chess Classes</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-menu__item has-submenu position-relative">
                            <a href="javascript:void(0)"
                                class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">About</a>
                            <ul
                                class="nav-submenu scroll-sm position-absolute start-0 top-100 tw-w-max bg-white tw-rounded-md overflow-hidden tw-p-2 tw-duration-200 tw-z-99">
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#about"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        About Us</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Book a Trial</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#gallery"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        Gallery</a>
                                </li>
                                <li class="nav-submenu__item d-block tw-rounded tw-duration-200 position-relative">
                                    <a href="#faq"
                                        class="nav-submenu__link hover-bg-neutral-200 text-neutral-950 fw-medium w-100 d-block tw-py-2 tw-px-305 tw-rounded hover-text-neutral-950">
                                        FAQ’s</a>
                                </li>
                            </ul>
                        </li>
                       
                        <li class="nav-menu__item">
                            <a href="#coaches"
                                class="nav-menu__link tw-pe-5 text-neutral-950 tw-py-5 fw-medium w-100">Coaches</a>
                        </li>
                        <li class="nav-menu__item">
                            <a href="#contact"
                                class="nav-menu__link text-neutral-950 tw-py-5 fw-medium w-100">Contact</a>
                        </li>
                    </ul>
                    <!-- Nav menu End  -->

                </div>
                <!-- Menu End  -->

                <!-- Header Right start -->
                <div class="d-flex align-items-center tw-gap-4 d-xl-block d-lg-block d-none">
                    <div class="d-flex align-items-center tw-gap-4">
                        <div class="d-md-block d-none">
                            <button type="button"
                                class="open-search tw-text-6 tw-w-13 tw-h-13 bg-main-two-50 text-main-600 rounded-circle d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </button>
                        </div>
                        <div class="d-sm-block d-none">
                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                class="btn btn-main hover-style-one button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-header"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Book Your Free Trial </span>
                                <span
                                    class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                </span>
                            </a>
                        </div>
                    </div>
                    <button type="button"
                        class="toggle-mobileMenu leading-none d-xl-none ms-3 text-neutral-800 tw-text-9">
                        <i class="ph ph-list"></i>
                    </button>
                </div>
                <!-- Header Right End  -->
            </nav>
        </div>
    </header>
    <!-- ==================== Header End Here ==================== -->


    @yield('content')



    <!-- ==================== Footer Start Here ==================== -->
    <footer class="footer bg-main-two-300 position-relative z-2">
        <img src="/frontend1/assets/images/shape/our-program-shape1.png" alt="shape"
            class="position-absolute top-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/footer-element1.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 tw-mt-240-px d-xl-block d-none z-n1 animation-upDown footer-girraf">
        <img src="/frontend1/tcul-img/img/footer-element2.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-200-px tw-me-130-px d-xl-block d-none z-n1 animation-upDown">
        <img src="/frontend1/tcul-img/img/footer-element3.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-mb-242-px tw-me-130-px d-xl-block d-none z-n1 animation-scalation footer-pencil">
        <div class="py-110">
            <div class="tw-mt-210-px">
                <div class="container container-two">
                    <div class="row gy-5">
                        <div class="col-xl-4 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="100">
                            <div>
                                <a href="/design/home" class="tw-mb-2">
                                    <img src="/frontend1/tcul-img/img/archer-new-logo.png" alt="logo">
                                </a>
                                <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-6">Give your child the opportunity to learn and master chess from the comfort of their home.</p>
                                <ul class="d-flex align-items-center tw-gap-2 flex-wrap mt-3">
                                    <li>
                                        <a target="_blank" href="https://www.facebook.com/makekidssmarter"
                                            class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                            <i class="ph-fill ph-facebook-logo"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://wa.me/919152734675"
                                            class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                            <i class="ph-fill ph-whatsapp-logo"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://www.linkedin.com/company/archer-chess-academy/"
                                            class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                            <i class="ph-fill ph-linkedin-logo"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://www.instagram.com/archerchessacademy/"
                                            class="tw-w-9 tw-h-9 bg-white text-black tw-rounded-lg hover-bg-main-600 hover-text-white tw-duration-300 tw-text-405 d-flex align-items-center justify-content-center">
                                            <i class="ph-fill ph-instagram-logo"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://www.youtube.com/@archerchessacademy4376"
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
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="200">
                            <div>
                                <h1 class="fw-bold text-neutral-950 h5">Quick Links</h1>
                                <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                <div class="d-flex flex-column tw-gap-3">
                                    <a href="#about"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        About
                                    </a>

                                    <a href="#about"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Why Archer
                                    </a>

                                    <a href="#curriculum"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Courses
                                    </a>

                                    <a href="#coaches"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Our Coaches
                                    </a>

                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Contact
                                    </a>

                                    <a href="#gallery"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Gallery
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-duration="600" data-aos-delay="300">
                            <div>
                                <h1 class="fw-bold text-neutral-950 h5">Recent Posts</h1>
                                <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                    <span>
                                        <img src="/frontend1/assets/images/thumbs/footer-img1.png" alt="img">
                                    </span>
                                    <div class="">
                                        <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                            <span class="tw-text-5 text-main-600 ">
                                                <i class="ph-bold ph-calendar-dots"></i>
                                            </span>
                                            <span class="fw-semibold tw-text-305 text-main-600">
                                                Jan 26, 2026
                                            </span>
                                        </div>
                                        <a href="javascript:void(0)"
                                            class="fw-bold tw-text-4 text-neutral-950 hover-text-main-600">Mastering the Opening: The Archer’s Approach to the First 10 Moves</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-4 flex-sm-nowrap flex-wrap tw-mb-4">
                                    <span>
                                        <img src="/frontend1/assets/images/thumbs/footer-img2.png" alt="img">
                                    </span>
                                    <div class="">
                                        <div class="d-flex align-items-center tw-gap-105 tw-mb-1">
                                            <span class="tw-text-5 text-main-600 ">
                                                <i class="ph-bold ph-calendar-dots"></i>
                                            </span>
                                            <span class="fw-semibold tw-text-305 text-main-600">
                                                Jan 20, 2026
                                            </span>
                                        </div>
                                        <a href="javascript:void(0)"
                                            class="fw-bold tw-text-4 text-neutral-950 hover-text-main-600">Archer Chess Academy – Building Future Grandmasters from.. </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-up"
                            data-aos-duration="800" data-aos-delay="200">
                            <div>
                                <h2 class="fw-bold text-neutral-950 h5">Useful Links</h2>
                                <span class="tw-w-82-px tw-h-05 tw-border-gradient tw-mb-7 tw-mt-5"></span>
                                <div class="d-flex flex-column tw-gap-3">
                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Book A Trial Class
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Privacy Policy
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Shipping Policy
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Terms Of Service
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Refund & Cancellation
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Blog
                                    </a>

                                    <a href="javascript:void(0)"
                                        class="tw-text-4 text-paragraph-600 d-flex align-items-center tw-gap-2 hover-text-main-600 tw-duration-300">
                                        <span class="tw-text-405">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                        Event
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="w-100 tw-border-primary-600 tw-mt-15 tw-mb-15"></span>
                    <div class="row gy-4">
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="100">
                            <div class="d-flex align-items-center tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-main-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon1.png" alt="icon">
                                </span>
                                <div class="text-center">
                                    <span class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Call Us 7/24
                                    </span>
                                    <h2 class="h5">
                                        <a href="tel:+919152734675" class="fw-semibold text-neutral-950">
                                            +91-9152734675
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="200">
                            <div class="d-flex align-items-center tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon2.png" alt="icon">
                                </span>
                                <div class="text-center">
                                    <span class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Email Us
                                    </span>
                                    <h2 class="h5">
                                        <a href="mailto:support@archerchessacademy.com" class="fw-semibold text-neutral-950">
                                            support@archerchessacademy.com
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-duration="600"
                            data-aos-delay="300">
                            <div class="d-flex align-items-center tw-gap-4 flex-column flex-wrap">
                                <span
                                    class="tw-w-15 tw-h-15 bg-pink-600 text-white d-flex align-items-center justify-content-center tw-text-7 rounded-circle">
                                    <img src="/frontend1/assets/images/icon/footer-icon3.png" alt="icon">
                                </span>
                                <div class="text-center">
                                    <span class="fw-medium tw-text-4 text-paragraph-600 tw-mb-2">
                                        Address
                                    </span>
                                    <h1 class="fw-semibold text-neutral-950 h5">
                                        Archer Chess Academy Pvt. Ltd. <br/>
                                       5A, Shatrunjay Height Co-Op Housing Society, Near Kailash Mansarovar, Bhayandar West - 401101, Mumbai

                                    </h1>
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

    {{-- Student Registration Modal --}}
    <div class="modal fade" id="registrationModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 tw-rounded-2xl bg-main-two-300 position-relative z-2">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title text-main-600">Student Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Country*</label>
                                <select class="form-control border-0 tw-py-3">
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
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Timezone*</label>
                                <select class="form-control border-0 tw-py-3">
                                    <option>Select Time Zone</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">City*</label>
                                <input type="text" class="form-control border-0 tw-py-3" placeholder="Enter your city">
                            </div>
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Kid's Full Name*</label>
                                <input type="text" class="form-control border-0 tw-py-3" placeholder="Enter kid's full name">
                            </div>
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Age*</label>
                                <input type="number" class="form-control border-0 tw-py-3" placeholder="Enter kid's age">
                            </div>
                            <!-- WhatsApp -->
                            <div class="col-md-6">
                                <label class="text-neutral-950">WhatsApp Number*</label>
                                <input id="phone_modal" type="tel" placeholder="Enter WhatsApp number"
                                    class="tw-py-4 tw-px-6 bg-white w-100 border-0 fw-normal tw-text-305 tw-rounded-2xl text-neutral-600">
                            </div>

                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Email*</label>
                                <input type="email" class="form-control border-0 tw-py-3" placeholder="Enter your email">
                            </div>
                            <div class="col-md-6">
                                <label class="text-neutral-950 ">Language Preference*</label>
                                <select class="form-control border-0 tw-py-3">
                                    <option>Select language preference</option>
                                    <option>Agree (English)</option>
                                    <option>Kid is not comfortable in English</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a href="javascript:void(0)" data-bs-dismiss="modal" data-bs-target="#thankYouModal" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                        <span class="button__flair" style="translate: none; rotate: none; scale: none; transform: translate(120%, -13.6364%) scale(0, 0);"></span>
                        <span class="button__label">Submit</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                    {{-- <button class="btn btn-success">Submit</button> --}}
                </div>
                <div style="background-image: url(/frontend1/assets/images/bg/footer-bottom-bg-img1.png);" class="bg-img">
                    <div class="container">
                        <div class="d-flex align-items-center tw-gap-3 flex-wrap justify-content-between tw-pt-5 tw-pb-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Beginners Curriculum Modal --}}
    <div class="modal fade" id="BeginnersCurriculumModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 tw-rounded-2xl bg-main-two-300 position-relative z-2">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title text-main-600"> Beginner Course Topics (for Course Section) <br/> <span class="fs-20">Topics covered in the course:</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Introduction to Chess
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Chessboard Setup
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            How Each Piece Moves
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Special Moves (Castling, En Passant, Promotion)
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Basic Opening Principles
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Simple Tactics (Fork, Pin, Skewer) 
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Check, Checkmate & Stalemate
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Basic Endgames
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Beginner Strategies
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                           Practice Games
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                        <span class="button__flair" style="translate: none; rotate: none; scale: none; transform: translate(120%, -13.6364%) scale(0, 0);"></span>
                        <span class="button__label">Book Your Trial</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                </div>
                <div style="background-image: url(/frontend1/assets/images/bg/footer-bottom-bg-img1.png);" class="bg-img">
                    <div class="container">
                        <div class="d-flex align-items-center tw-gap-3 flex-wrap justify-content-between tw-pt-5 tw-pb-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Intermediate Curriculum Modal --}}
    <div class="modal fade" id="IntermediateCurriculumModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content border-0 tw-rounded-2xl bg-main-two-300 position-relative z-2">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title text-main-600">Intermediate Program Curriculum <br/> <span class="fs-20">Topics covered in the course:</span> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row gy-4">
                           <div class="col-lg-12">
    
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Review of Chess Fundamentals
                                        </h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Advanced Tactical Patterns
                                        </h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Calculation Techniques
                                        </h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Opening Principles & Common Openings
                                        </h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Middlegame Planning
                                        </h6>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Positional Play
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Pawn Structures
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Attack and Defense Strategies
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Intermediate Endgames
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Analyzing Your Own Games
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                        <span class="button__flair" style="translate: none; rotate: none; scale: none; transform: translate(120%, -13.6364%) scale(0, 0);"></span>
                        <span class="button__label">Book Your Trial</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                </div>
                <div style="background-image: url(/frontend1/assets/images/bg/footer-bottom-bg-img1.png);" class="bg-img">
                    <div class="container">
                        <div class="d-flex align-items-center tw-gap-3 flex-wrap justify-content-between tw-pt-5 tw-pb-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Curriculum Modal --}}
    <div class="modal fade" id="AdvancedCurriculumModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 tw-rounded-2xl bg-main-two-300 position-relative z-2">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title text-main-600">Advanced Program Curriculum <br/> <span class="fs-20">Topics covered in the course:</span> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Advanced Tactical Combinations

                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Deep Calculation & Candidate Moves
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Advanced Opening Preparation
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Positional Sacrifices
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Strategic Planning in Complex Positions
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Advanced Pawn Structures
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Pro-Level Middlegame Strategies
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Advanced Endgame Techniques
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Game Analysis & Improvement Methods
                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center tw-gap-3 tw-mb-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                                    <span class="modal-icon tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                                        <i class="ph ph-caret-double-right"></i>
                                    </span>
                                    <div>
                                        <h6 class="fw-semibold text-neutral-950 fs-18">
                                            Tournament Preparation & Psychological Skills
                                        </h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#registrationModal" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                        <span class="button__flair" style="translate: none; rotate: none; scale: none; transform: translate(120%, -13.6364%) scale(0, 0);"></span>
                        <span class="button__label">Book Your Trial</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                </div>
                <div style="background-image: url(/frontend1/assets/images/bg/footer-bottom-bg-img1.png);" class="bg-img">
                    <div class="container">
                        <div class="d-flex align-items-center tw-gap-3 flex-wrap justify-content-between tw-pt-5 tw-pb-6">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Thank you modal  -->
    <div class="modal fade" id="thankYouModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content text-center p-4 border-0 tw-rounded-2xl position-relative z-2">
                <div class="modal-body">
                    <div class="error-page-image d-flex justify-content-center wow fadeInUp">
                        <div style="width: 180px; height: 180px;" id="lottie"></div>
                    </div>
                    <h4 class="mt-3">Thank You!</h4>
                    <p>
                        Your registration has been submitted successfully.
                    </p>

                    <a href="/design/home" class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4" data-block="button">
                        <span class="button__flair" style="translate: none; rotate: none; scale: none; transform: translate(120%, -13.6364%) scale(0, 0);"></span>
                        <span class="button__label">Back to Home</span>
                        <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                            <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                        </span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.0/lottie.min.js"></script> 
    <script>
        // Load local Lottie animation
        lottie.loadAnimation({
        container: document.getElementById('lottie'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/frontend1/tcul-img/img/icons/success.json'
        });
    </script>

    {{-- International Telephone Input --}}
    <script>
        const phoneContact = document.querySelector("#phone_contact");
        const phoneModal = document.querySelector("#phone_modal");

            window.intlTelInput(phoneContact, {
            initialCountry: "in",
            separateDialCode: true
            });

            window.intlTelInput(phoneModal, {
            initialCountry: "in",
            separateDialCode: true
        });
    </script>

    <script>
        function openCountrySite(select){
            const url = select.value;
            if(url){
                window.open(url, "_blank");
                select.selectedIndex = 0;
            }
        }
    </script>

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
