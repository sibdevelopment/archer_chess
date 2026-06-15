@extends('layouts.revamp')
@section('title', 'Home')
@section('content')

    <head>
        <style>
            .icon-circle {
                width: 60px;
                height: 60px;
                background: rgb(255, 136, 176);
                /* pink */
                color: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                flex-shrink: 0;
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
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Contact Us</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Contact Us </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================== contact us section start =========================== -->
    <section class="py-50">
        <div class="container">
            <div class="tw-mb-15">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-5" data-aos="fade-up" data-aos-duration="800"
                    data-aos-delay="100">Contact Us</span>
                <h4 class="fw-bold text-neutral-950" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">Contact
                    Us
                    For Your Kids Learning</h4>
            </div>
            <div class="row gy-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center tw-gap-3 tw-mb-10" data-aos="fade-up" data-aos-duration="800"
                        data-aos-delay="100">
                        <span
                            class="tw-w-15 tw-h-15 bg-main-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                            <i class="ph-fill ph-phone"></i>
                        </span>
                        <div>
                            <span class="fw-medium tw-text-4 text-paragraph-500 tw-mb-1 d-block">Call Us 7/24</span>
                            <h5>
                                <a href="tel:+919152734675" class="fw-semibold text-neutral-950">
                                    +91-9152734675
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center tw-gap-3 tw-mb-10" data-aos="fade-up" data-aos-duration="800"
                        data-aos-delay="200">
                        <span class="icon-circle">
                            <i class="ph-fill ph-map-pin-area"></i>
                        </span>

                        <div>
                            <span class="fw-medium tw-text-4 text-paragraph-500 tw-mb-1 d-block">Location</span>
                            <h5 class="fw-semibold text-neutral-950">
                                Archer Chess Academy Pvt. Ltd.
                                5A, Shatrunjay Height Co-Op Housing Society, Near Kailash Mansarovar, Bhayandar West -
                                401101, Mumbai
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-center tw-gap-3" data-aos="fade-up" data-aos-duration="800"
                        data-aos-delay="300">
                        <span
                            class="tw-w-15 tw-h-15 bg-main-two-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-7">
                            <i class="ph-bold ph-envelope"></i>
                        </span>
                        <div>
                            <span class="fw-medium tw-text-4 text-paragraph-500 tw-mb-1 d-block">Make a Quote</span>
                            <h5>
                                <a href="mailto:support@archerchessacademy.com" class="fw-semibold text-neutral-950">
                                    support@archerchessacademy.com
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form action="#">
                        <div class="row gy-4">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                <input type="text"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600"
                                    placeholder="Name">
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                <input type="email"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600"
                                    placeholder="Email">
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                                <input type="number"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600"
                                    placeholder="Phone">
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                                <select
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600">
                                    <option value="" disabled selected>Select Country</option>
                                    <option value="India">India</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                </select>
                            </div>
                            <div class="col-sm-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                                <input type="text"
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600"
                                    placeholder="Subject">
                            </div>
                            <div class="col-sm-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                                <textarea
                                    class="tw-py-3 tw-px-5 bg-main-two-50 border-neutral-100 border tw-rounded-md fw-normal tw-text-4 text-neutral-600 w-100 focus-visible-border-main-600 tw-h-170-px"
                                    placeholder="Your message"></textarea>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-mt-5"
                            data-block="button" data-aos="fade-up" data-aos-duration="800" data-aos-delay="350">
                            <span class="button__flair"></span>
                            <span class="button__label">Send Your Message</span>
                            <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-50">
        <div class="container">
            <div class="row gy-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.642430891318!2d72.84157427374308!3d19.297909844971525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b02c0b821505%3A0x7fcb82953b8ca8bf!2sArcher%20Chess%20Academy!5e0!3m2!1sen!2sin!4v1774939643530!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

@endsection
