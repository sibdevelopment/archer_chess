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
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Shipping Policy</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Shipping Policy </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!------------------------------------------------------------------------------------------->

    <section class="course-content pb-0" style="background: none">
        <div class="container">
            <div class="section-header aos pb-3" data-aos="fade-up">
                <div class="section-sub-head">
                    <span style="color: rgb(255, 87, 34);">
                        Shipping Policy (For Online Services Only)
                    </span>
                </div>
            </div>
            <div class="blog">
                <div class="blog-content">
                    <p style="font-size: 30px; color: #22100d;">
                        Archer Chess Academy provides online classes and digital services only. We do not ship any physical
                        products through courier or speed post.
                    </p><br>

                    <p>
                        <b style="font-size: 20px;">Service Delivery Timeline:</b> <br>
                        Online class access, onboarding details, and confirmation messages are delivered via WhatsApp,
                        email, and SMS within 10–15 days from the date of order and successful payment, or as per the
                        timeline agreed at the time of order confirmation.
                    </p></br>

                    <p>
                        <b style="font-size: 20px;">Delay Disclaimer:</b> <br>
                        Archer Chess Academy is not liable for any delay caused due to technical issues, service provider
                        delays, or incorrect contact information provided by the user. We guarantee to send class
                        confirmation and access details within 10–15 days from the date of order and payment, unless a
                        different delivery date has been mutually agreed upon.
                    </p></br>
                    <p>
                        <b style="font-size: 20px;">Delivery Address / Contact Details:</b> <br>
                        All service confirmations and communication will be sent to the buyer’s registered email ID,
                        WhatsApp number, or mobile number provided at the time of purchase.
                    </p></br>

                    <p>
                        <b style="font-size: 20px;">Support:</b><br>
                        For any issues in accessing or utilizing our online services, please contact our helpdesk: <br>
                        Phone: +91 915 273 4675 <br>
                        Email: support@archerchessacademy.com <br>
                    </p>
                </div>
            </div>
        </div>
    </section>


@endsection
