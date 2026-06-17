<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="/frontend/tcul_img/home/archer_favicon.png">
    <link rel="stylesheet" href="/frontend/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/frontend/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/frontend/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/slick/slick.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/slick/slick-theme.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/aos/aos.css">
    <link rel="stylesheet" href="/frontend/assets/css/style.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/feather/feather.css">
    <link rel="stylesheet" href="/frontend/assets/css/technicul.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/swiper/css/swiper.min.css">
    <script src="/backend/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/frontend/assets/js/theme-script.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- in resources/views/layouts/admin.blade.php (inside <head>) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        /* Navbar background */
        .navbar-custom {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
        }

        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 500;
            padding: 8px 15px;
            transition: 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #ffe88f !important;
            /* golden hover */
        }

        /* Dropdown menu */
        .navbar-custom .dropdown-menu {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-custom .dropdown-menu a {
            color: #333 !important;
            font-weight: 500;
        }

        .navbar-custom .dropdown-menu a:hover {
            background: #2575fc;
            color: #fff !important;
        }

        /* Country badge */
        .country-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Auth buttons */
        .btn-login {
            border: 2px solid #fff;
            color: #fff;
            font-weight: 600;
            border-radius: 25px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #fff;
            color: #2575fc;
        }

        .btn-signup {
            background: #ffe88f;
            color: #333;
            font-weight: 700;
            border-radius: 25px;
            transition: 0.3s;
            border: none;
        }

        .btn-signup:hover {
            background: #ffd84a;
            transform: scale(1.05);
        }

        .top-header {
            background-image: url(/frontend/assets/img/bg/breadcrumb-bar.png) !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .social-icon a {
            color: #26292c;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .4s ease 0s;
            width: 30px;
            height: 30px;
            background: 0 0;
            border-radius: 50%;
        }

        /* @media (max-width: 990px) {
            .top-header. .main-menu-wrapper. .header-navbar-rht.tcul {
                display: none;
            }
        }  */

        @media (max-width: 990px) {
            .top-header {
                display: none;
            }
        }

        .add-header-bg {
            background-image: url(/frontend/assets/img/bg/breadcrumb-bar.png) !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;
            transition-duration: .4s;
            background: #fff;
            padding-top: 0;
            margin: 0;
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

        @keyframes moveCursor1 {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(.8);
            }
        }

        @keyframes moveCursor2 {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(2);
            }

            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        @media (min-width: 1025px) {
            .main-menu-wrapper ul.main-nav {
                display: flex !important;
            }

            .navbar-header {
                margin-right: 450px !important;
            }
        }

        .iti {
            width: 100% !important;
        }

        /* Footer Styling */
        .footer {
            background: linear-gradient(120deg, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .footer-top {
            padding: 60px 0 40px 0;
        }

        .footer .footer-title {
            color: #ffe88f;
            /* yellow accent */
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
        }

        /* Footer Links */
        .footer ul li a {
            color: #eaeaea;
            font-size: 14px;
            display: block;
            margin-bottom: 8px;
            transition: color 0.3s;
        }

        .footer ul li a:hover {
            color: #ffe88f;
        }

        /* Footer About */
        .footer-about-content p {
            color: #f0f0f0;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Contact Info */
        .footer-contact-widget p,
        .footer-contact-widget a {
            font-size: 14px;
            color: #fff;
            line-height: 1.8;
        }

        .footer-contact-widget img {
            margin-right: 8px;
            width: 18px;
            height: auto;
        }

        /* Social Icons */
        .social-icon ul {
            padding: 0;
            margin: 10px 0;
            list-style: none;
            display: flex;
            gap: 10px;
        }

        .social-icon ul li a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #fff;
            color: #6a11cb;
            font-size: 16px;
            transition: all 0.3s;
        }

        .social-icon ul li a:hover {
            background: #ffe88f;
            color: #000;
        }

        /* Footer Bottom */
        .footer-bottom {
            background: rgba(0, 0, 0, 0.15);
            padding: 15px 0;
            font-size: 14px;
        }

        .footer-bottom p {
            margin: 0;
            color: #fff;
        }

        .footer-bottom a {
            color: #ffe88f;
            font-weight: 600;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .footer-top {
                padding: 40px 0 20px 0;
            }

            .footer .footer-title {
                font-size: 16px;
            }
        }

        /* Navbar base */
        .navbar-custom {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
        }

        /* Nav links */
        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 600;
            font-size: 16px;
            /* bigger text */
            padding: 10px 18px;
            transition: 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #ffe88f !important;
        }

        /* Dropdown menu */
        .navbar-custom .dropdown-menu {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-custom .dropdown-menu a {
            color: #333 !important;
            font-weight: 500;
            font-size: 15px;
        }

        .navbar-custom .dropdown-menu a:hover {
            background: #2575fc;
            color: #fff !important;
        }

        /* Country Badge */
        .country-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Auth buttons */
        .btn-login {
            border: 2px solid #fff;
            color: #fff;
            font-weight: 600;
            border-radius: 25px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #fff;
            color: #2575fc;
        }

        .navbar-custom .nav-link {
            font-size: 20px;
        }

        .btn-signup {
            background: #ffe88f;
            color: #333;
            font-weight: 700;
            border-radius: 25px;
            transition: 0.3s;
            border: none;
        }

        .btn-signup:hover {
            background: #ffd84a;
            transform: scale(1.05);
        }

        /* Mobile menu fixes */
        @media (max-width: 992px) {
            .navbar-collapse {
                background: #6a11cb;
                padding: 15px;
                border-radius: 12px;
            }

            .navbar-custom .nav-link {
                font-size: 18px;
                padding: 12px;
                display: block;
            }

            .country-badge {
                display: block !important;
                margin: 15px 0;
                text-align: center;
            }

            .d-lg-inline-block {
                display: none !important;
            }

            .auth-mobile {
                margin-top: 10px;
                text-align: center;
            }


        }

        .mx-3 {
            margin-right: 1rem !important;
            margin-left: 0.1rem !important;
        }

        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        /* Prevent content from jumping under the header */
        :root {
            --header-h: 72px;
        }

        /* adjust to match your header height */
        body {
            padding-top: var(--header-h);
        }
    </style>

    <link href="/path/bootstrap.min.css" rel="stylesheet">
    <!-- ...your HTML... -->
    <script src="/path/bootstrap.bundle.min.js"></script> <!-- must be bundle -->


    @php
        $host = request()->getHost();
    @endphp

    @if (Str::contains($host, 'technicul.com'))
        <meta name="robots" content="noindex, nofollow">
        <meta name="Googlebot" content="noindex, nofollow">
    @endif

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PZ3VKNXF');
    </script>
    <!-- End Google Tag Manager -->



    <!-- Meta Pixel Code -->
    <!-- ------------------------------------------------------------------ :: -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '777952784455654');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=777952784455654&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->


    <!-- ------------------------------------------------------------------ :: -->
    <!-- ------------------------------------------------------------------ :: -->
    @if (desktop())
        {{-- <div class="tcul-floating_btn_country" style="right: 110px !important;">
            <a target="_blank" class="change-country" href="">
                <div class="book-class-online">
                    <img src="/frontend/assets/img/icon/icon-20.svg" alt="Img" class="img-fluid"
                        style="
                        width: 30px;
                        height: auto;">
                    <h6 class="mt-2"> &nbsp; CHOOSE YOUR COUNTRY</h6>
                </div>
            </a>
        </div> --}}
        <div class="tcul-floating_btn_contact" style="right: 110px !important;">
            <a target="_self" href="/#trail_form">
                <div class="book-class-online">
                    <img src="/frontend/assets/img/icon/icon-07.svg" alt="Img" class="img-fluid"
                        style=" width: 30px; height: auto;">
                    <h6 class="mt-2"> &nbsp; BOOK YOUR FREE TRIAL</h6>
                </div>
            </a>
        </div>
        <div class="tcul-floating_btn_whatsapp" style="right: 110px !important;">
            <a target="_blank" href="https://api.whatsapp.com/send?phone=9152734675&text=hii">
                <div class="tcul-contact_icon">
                    <i class="fab fa-whatsapp my-float"></i>
                    <h6 class="mt-2" style="color: #fff;"> &nbsp; &nbsp; CHAT WITH US</h6>
                </div>
            </a>
        </div>
    @else
        <div class="tcul-floating_btn_contact_mobile" style="left: 120px !important;">
            <a target="_self" href="/#trail_form">
                <div class="book-class-online">
                    <img src="/frontend/assets/img/icon/icon-07.svg" alt="Img" class="img-fluid"
                        style="
                        width: 30px;
                        height: auto;">
                    <h6 class="mt-2"> &nbsp; BOOK YOUR FREE TRIAL</h6>
                </div>
            </a>
        </div>
        <div class="tcul-floating_btn_whatsapp_mobile" style="right: 5px !important;">
            <a target="_blank" href="https://api.whatsapp.com/send?phone=9152734675&text=hii">
                <div class="tcul-contact_icon_mobile">
                    <i class="fab fa-whatsapp my-float"></i>
                    <h6 class="mt-2" style="color: #fff;"></h6>
                </div>
            </a>
        </div>
    @endif

</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZ3VKNXF" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="cursor"></div>


    <div class="main-wrapper">
        <header class="header">
            <div class="header-fixed">
                <nav class="navbar navbar-expand-lg navbar-custom py-2">
                    <div class="container">

                        <!-- Logo -->
                        <a href="/" class="navbar-brand d-flex align-items-center">
                            <img src="/logo2.png" alt="Logo" class="img-fluid" height="300" width="200">
                        </a>

                        <!-- Mobile Toggle -->
                        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Nav Links -->
                        <div class="collapse navbar-collapse" id="mainNavbar">
                            <ul class="navbar-nav mx-auto">
                                <li class="nav-item mt-0"><a href="{{ route('event') }}" class="nav-link">Events</a>
                                </li>
                                <li class="nav-item mt-0"><a href="{{ route('blog') }}" class="nav-link">Blogs</a>
                                </li>
                                <li class="nav-item mt-0"><a href="/gallery" class="nav-link">Gallery</a></li>
                                </li>

                                <!-- Dropdown -->
                                <li class="nav-item dropdown mt-0">
                                    <a class="nav-link dropdown-toggle" href="#" id="coursesDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Courses
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="coursesDropdown">
                                        <li><a class="dropdown-item"
                                                href="/online-chess-course-for-beginners">Beginner Chess
                                                Classes</a></li>
                                        <li><a class="dropdown-item"
                                                href="/online-chess-course-for-intermediate">Intermediate
                                                Chess Classes</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('explore.course.details.advanced') }}">Advanced Chess
                                                Classes</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('explore.course.details.expert') }}">Expert Chess
                                                Classes</a></li>
                                    </ul>
                                </li>
                            </ul>

                            <!-- Our Classroom Button -->
                            <div class="mx-3">
                                <!-- https://classroom.archerchessacademy.com -->
                                <a href="#" class="btn btn-lg btn-warning px-4 fw-bold shadow-sm">
                                    🏫 Our Classroom
                                </a>
                            </div>

                            <!-- Auth Buttons -->
                            <div class="d-flex gap-2 auth-mobile">
                                <a href="/student/login" class="btn btn-login btn-sm px-3">Login</a>
                                <a href="/#trail_form" class="btn btn-signup btn-sm px-3">Sign Up</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <!-- -------------------------------------------------------------------------------------------------- :: -->


        @yield('content')


        <!-- -------------------------------------------------------------------------------------------------- :: -->
        <footer class="footer footer-two">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <!-- About -->
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <div class="footer-widget footer-about">
                                <div class="footer-logo" style="margin-bottom: 0px !important">
                                    <img src="/logo2.webp" fetchpriority="high" alt="logo" width="200"
                                        class="img-fluid" style="width:200px !important;max-height: 85px !important;">
                                </div>
                                <div class="footer-about-content">
                                    <p style="color: white;">Give your child the opportunity to learn and master chess
                                        from the comfort of
                                        their home.</p>
                                </div>
                                <div class="iso-img-header d-flex align-items-center justify-content-start my-3">
                                    <img src="/frontend/tcul_img/webp/iso-certification.webp" alt="Img"
                                        class="me-1">
                                    <h6 class="mt-2 fw-bold" style="color: #ffe88f;">ISO CERTIFIED</h6>
                                </div>
                                <div class="social-icon">
                                    <ul>
                                        <li><a style="color: white;" href="https://www.facebook.com/makekidssmarter"
                                                target="_blank"><i class="feather-facebook"></i></a></li>
                                        <li><a style="color: white;" href="https://x.com/ArcherChess"
                                                target="_blank"><i class="feather-x"></i></a></li>
                                        <li><a style="color: white;"
                                                href="https://www.youtube.com/@archerchessacademy4376"
                                                target="_blank"><i class="feather-youtube"></i></a></li>
                                        <li><a style="color: white;" href="mailto:support@archerchessacademy.com"
                                                target="_blank"><i class="feather-mail"></i></a></li>
                                        <li><a style="color: white;"
                                                href="https://www.instagram.com/archerchessacademy/"
                                                target="_blank"><i class="feather-instagram"></i></a></li>
                                        <li><a style="color: white;"
                                                href="https://www.linkedin.com/in/archer-chess-academy-736a23327"
                                                target="_blank"><i class="feather-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Quick Links -->
                        <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Quick Links</h2>
                                <ul>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <li><a href="/#whyarcher">Why Archer</a></li>
                                    <li><a href="/#course">Courses</a></li>
                                    <li><a href="/#tutor">Our Tutors</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Country Links -->
                        <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Useful Links</h2>
                                <ul>
                                    <li><a href="/#trail_form">Book A Trial Class</a></li>
                                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                                    <li><a href="{{ route('shipping.policy') }}">Shipping Policy</a></li>
                                    <li><a href="{{ route('terms') }}">Terms Of Service</a></li>
                                    <li><a href="{{ route('refund.policy') }}">Refund & Cancellation</a></li>
                                    <li><a href="{{ route('blog') }}">Blog</a></li>
                                    <li><a href="{{ route('event') }}">Event</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Useful Links -->
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="footer-widget footer-menu">
                                <h2 class="footer-title">Online Chess</h2>
                                <ul>
                                    <li><a href="/online-chess/india" target="_blank">INDIA</a></li>
                                    <li><a href="/online-chess/usa" target="_blank">USA</a></li>
                                    <li><a href="/online-chess/middle-east" target="_blank">MIDDLE EAST</a></li>
                                    <li><a href="/online-chess/singapore" target="_blank">SINGAPORE</a></li>
                                    <li><a href="/online-chess/uae" target="_blank">UAE</a></li>
                                    <li><a href="/online-chess/united-kingdom" target="_blank">UNITED KINGDOM</a></li>
                                    <li><a href="/online-chess/australia" target="_blank">AUSTRALIA</a></li>
                                    <li><a href="/online-chess/canada" target="_blank">CANADA</a></li>
                                    <li><a href="/online-chess/european-union" target="_blank">EUROPEAN UNION</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-lg-2 col-md-6 col-sm-6 col-12">
                            <div class="footer-widget footer-contact">
                                <h2 class="footer-title">Address</h2>
                                <div class="footer-contact-widget">
                                    <p style="color: white;"><img src="/frontend/assets/img/icon/award-new.svg">
                                        Archer Chess Academy Private Limited</p>
                                    <p style="color: white;"><img src="/frontend/assets/img/icon/icon-20.svg"> 5A,
                                        Shatrunjay Height Co-Op
                                        Housing Society, Near Kailash Mansarovar, Bhayandar West - 401101, Mumbai</p>
                                    <p style="color: white;"><img src="/frontend/assets/img/icon/icon-19.svg"> <a
                                            href="mailto:support@archerchessacademy.com">support@archerchessacademy.com</a>
                                    </p>
                                    <p style="color: white;"><img src="/frontend/assets/img/icon/icon-21.svg">
                                        +91-9152734675</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-sm-12">
                            <p class="mb-0">© 2025 Archer Chess Academy. All Rights Reserved</p>
                        </div>
                        <div class="col-md-6 col-sm-12 text-md-end text-sm-start">
                            <p class="mb-0">Developed by <a href="https://technicul.com/" target="_blank">Technicul
                                    Cloud LLP</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- -------------------------------------------------------------------------------------------------- :: -->
    </div>



    <!-- Modal -->
    <div class="modal fade" id="user-country-modal" aria-hidden="true">
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function handleResize() {
                var mobileLogin = document.querySelector('.mobileLogin');
                var mobileCountry = document.querySelector('.mobileCountry');
                if (window.innerWidth >= 765) {
                    if (mobileLogin) mobileLogin.style.display = 'none';
                    if (mobileCountry) mobileCountry.style.display = 'none';
                } else {
                    if (mobileLogin) mobileLogin.style.display = 'inline-block';
                    if (mobileCountry) mobileCountry.style.display = 'inline-block';
                }
            }

            window.addEventListener('resize', handleResize);
            setTimeout(handleResize, 1000); // Initial check after 1 second

            $(document).ready(function() {
                // var country = "{{ session('country', Cookie::get('country', '')) }}";
                // $('#user-enquiry-modal').modal('show');
            });


            // Open the modal when the user clicks on the element with class 'change-country'


            function fetchPricingData() { 
                    $.ajax({
                        url: "{{ route('get.pricing.card') }}",
                        method: 'GET',
                        data: {
                        },
                        success: function(response) {
                            $('#dynamic-course-pricing').html(response);
                        },
                        error: function(xhr, status, error) {
                            toastr.error(
                                'An unexpected error occurred while fetching the pricing data. Please try again.',
                                '', {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 1500,
                                    closeButton: true,
                                });
                        }
                    });
            }

            // Call fetchPricingData on page load
            document.addEventListener('DOMContentLoaded', function() {
                fetchPricingData();
            });
        });
    </script>
    <!-- JS for cursor :: ------------ -->
    <script>
        const cursor = document.querySelector('.cursor');
        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", "top: " + (e.pageY - 10) + "px; left: " + (e.pageX - 10) + "px;")
        });
        document.addEventListener('click', e => {
            cursor.classList.add("expand");
            setTimeout(() => {
                cursor.classList.remove("expand");
            }, 500);
        });
    </script>

    <!-- -------------------------------------------------------------------------------------------------- :: -->
    <!-- -------------------------------------------------------------------------------------------------- :: -->

    <script data-cfasync="false" src="/frontend/assets/js/email-decode.min.js"></script>
    <script src="/frontend/assets/js/jquery-3.7.1.min.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/bootstrap.bundle.min.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/jquery.waypoints.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/jquery.counterup.min.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/plugins/select2/js/select2.min.js"
        type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/owl.carousel.min.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/plugins/slick/slick.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/plugins/aos/aos.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/script.js" type="ad82ed1f8bc95a9fd3f001de-text/javascript"></script>
    <script src="/frontend/assets/js/rocket-loader.min.js" data-cf-settings="ad82ed1f8bc95a9fd3f001de-|49" defer></script>
    <script src="/frontend/assets/plugins/theia-sticky-sidebar/ResizeSensor.js"
        type="b007fff714f69ea162dcd745-text/javascript"></script>
    <script src="/frontend/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"
        type="b007fff714f69ea162dcd745-text/javascript"></script>
    <script src="/backend/dist/js/plugins/toastr-init.js"></script>

    <!-- Toastr JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- after bootstrap.bundle.min.js -->


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var collapseEl = document.getElementById('mainNavbar');
            if (!collapseEl || !window.bootstrap || !bootstrap.Collapse) return;

            var mq = window.matchMedia('(max-width: 991.98px)');
            // Don't auto-toggle on init; reuse instance
            var bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, {
                toggle: false
            });

            var detachFns = [];

            function onScrollLikeEvent() {
                if (!mq.matches) return;
                if (collapseEl.classList.contains('show')) {
                    bsCollapse.hide();
                }
            }

            function attachScrollListeners() {
                // attach once per open
                var onScroll = onScrollLikeEvent;
                var onTouchMove = onScrollLikeEvent;
                var onWheel = onScrollLikeEvent;

                window.addEventListener('scroll', onScroll, {
                    passive: true
                });
                document.addEventListener('touchmove', onTouchMove, {
                    passive: true
                });
                document.addEventListener('wheel', onWheel, {
                    passive: true
                });

                detachFns.push(function() {
                    window.removeEventListener('scroll', onScroll);
                    document.removeEventListener('touchmove', onTouchMove);
                    document.removeEventListener('wheel', onWheel);
                });
            }

            function detachScrollListeners() {
                while (detachFns.length) detachFns.pop()();
            }

            // When the menu opens, start watching scroll; when it closes, stop
            collapseEl.addEventListener('shown.bs.collapse', attachScrollListeners);
            collapseEl.addEventListener('hidden.bs.collapse', detachScrollListeners);

            // Also close when any nav link or dropdown-item is tapped
            document.querySelectorAll('#mainNavbar .nav-link, #mainNavbar .dropdown-item').forEach(function(el) {
                el.addEventListener('click', function() {
                    if (mq.matches && collapseEl.classList.contains('show')) {
                        // let anchors navigate, then close
                        setTimeout(function() {
                            bsCollapse.hide();
                        }, 0);
                    }
                });
            });

            // Safety: if something scrolls before menu “shown” event fired
            window.addEventListener('scroll', onScrollLikeEvent, {
                passive: true
            });
        });
    </script>


</body>

</html>
