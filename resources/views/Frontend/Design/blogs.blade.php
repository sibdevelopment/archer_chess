@extends('layouts.revamp')
@section('title', 'Blog')
@section('content')

    <head>
        <style>
            .arrow-btn {
                width: 40px;
                height: 40px;
                min-width: 40px;
                border: 1.5px solid #ff5a3c;
                border-radius: 50%;

                display: flex;
                align-items: center;
                justify-content: center;

                color: #ff5a3c;
                transition: 0.3s;
            }

            .arrow-btn:hover {
                background: #ff5a3c;
                color: #fff;
            }
        </style>
    </head>

    <!-- ==================== Breadcrumb Start Here ==================== -->
    <section class="breadcrumb pt-60 pb-20 bg-main-two-200 position-relative">
        <img src="/frontend1/assets/images/shape/banner-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-15 d-lg-block d-none animation-upDown">
        <img src="/frontend1/tcul-img/img/expert-1.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-h-100-px tw-ms-250-px d-lg-block d-none">
        <div class="tw-mb-140-px w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div>
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Our Blogs </h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Our Blogs </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->
    <!-- ====================== our blog section start ======================= -->
    <div class="py-110">
        <div class="container">
            <div class="row gy-4">

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4" >
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="animation-item">
                        <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                            <img src="/frontend1/tcul-img/img/blog.png" alt="img"
                                class="w-100 course-item__img tw-duration-300">
                        </span>
                        <div class="tw-px-7">
                            <div class="d-flex align-items-center tw-gap-4 flex-wrap tw-mb-4">

                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-dots"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        12 June, 2025
                                    </span>
                                </div>
                                <div class="d-flex align-items-center tw-gap-105">
                                    <span class="tw-text-405 text-main-600">
                                        <img src="/frontend/assets/img/icon/icon-23.svg" alt="icon" class="tw-mb-4">
                                    </span>
                                    <span class="fw-normal tw-text-4 text-neutral-600" style="font-size: 14px;">
                                        Endgame Mastery — King + Pawn Endings
                                    </span>
                                </div>
                            </div>
                            <h2 class="tw-mb-4 h5" style="font-size: 18px;">
                                <a href="/design/blog_details" class="fw-bold text-neutral-950">
                                    How to Encourage Independence in Preschoolers
                                </a>
                            </h2>
                            <div class="d-flex justify-content-between align-items-center">

                                <!-- Left Content -->
                                <div style="max-width: 85%;">
                                    <p class="fw-normal tw-text-4 text-neutral-600 mb-0" style="line-height:1.6;">
                                        Improve your chess skills with expert tips, strategies, and practical insights
                                        designed for players of all levels.
                                    </p>
                                </div>

                                <!-- Arrow -->
                                <a href="/design/blog_details" class="arrow-btn">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tw-mt-12 d-flex align-items-center justify-content-center tw-gap-2 flex-wrap">
                <button type="button"
                    class="tw-w-12 tw-h-12 border-main-600 border tw-text-5 text-main-600 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300 rounded-circle">
                    01
                </button>
                <button type="button"
                    class="tw-w-12 tw-h-12 border-main-600 border tw-text-5 text-main-600 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300 rounded-circle">
                    02
                </button>
                <button type="button"
                    class="tw-w-12 tw-h-12 border-main-600 border tw-text-5 text-main-600 d-flex align-items-center justify-content-center hover-bg-main-600 hover-text-white tw-duration-300 rounded-circle">
                    <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- ====================== our blog section end ======================= -->

@endsection
