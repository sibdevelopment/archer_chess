@extends('layouts.revamp')
@section('title', 'Home')
@section('content')
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
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Image Gallery</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Image Gallery </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ===================== image gallery section start ========================= -->
    <div class="py-110">
        <div class="container">
            <div class="row gy-4">

                <!-- Image -->
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">

                        <img src="/frontend1/tcul-img/gallery/gallery1.png" class="w-100 square-img">

                        <a href="/frontend1/tcul-img/gallery/gallery1.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">

                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>

                        </a>

                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery2.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery2.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery3.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery3.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery4.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery4.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery4.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery4.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery3.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery3.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">
                        <img src="/frontend1/tcul-img/gallery/gallery2.png" class="w-100 square-img">
                        <a href="/frontend1/tcul-img/gallery/gallery2.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">
                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-thumb position-relative">

                        <img src="/frontend1/tcul-img/gallery/gallery1.png" class="w-100 square-img">

                        <a href="/frontend1/tcul-img/gallery/gallery1.png"
                            class="gallery-thumb__link-two gallery-popup position-absolute top-50 start-50 translate-middle">

                            <span class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                <i class="ph-bold ph-plus text-main-600"></i>
                            </span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ===================== image gallery section end ========================= -->




@endsection
