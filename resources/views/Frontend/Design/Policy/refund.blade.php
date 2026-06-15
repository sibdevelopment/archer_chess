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
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Refund Policy</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Refund Policy </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!------------------------------------------------------------------------------------------->


    <section class="course-content">
        <div class="container">
            <div class="section-header aos pb-4" data-aos="fade-up">
                <div class="section-sub-head">
                      <span class="fw-normal tw-text-405 text-main-600 tw-mb-5">Refund Policy</span>
                    <h2 style="font-size: 32px;">A policy that dictates under what conditions customers can return products
                        they've purchased from your ecommerce store and whether you'll reimburse them or not.</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="blog">
                        <div class="blog-content">
                            <p style="font-size: 16px; color: #22100d;">According to our refund and cancellation policy, any
                                fees that have been paid are non-refundable. Regardless of the circumstances, we do not
                                provide refunds. We have established this policy to ensure transparency and consistency in
                                our business practices. It is essential for our customers to understand that once payment
                                has been made, it is considered final and cannot be reversed.</p>

                            <p style="font-size: 16px; color: #22100d;">We aim to provide high-quality products and services
                                and are committed to delivering on that promise. We value the trust and support of our
                                customers, and we strive to meet their expectations at every step.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
