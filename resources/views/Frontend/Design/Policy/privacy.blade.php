@extends('layouts.revamp')
@section('title', 'Privacy & Policy')
@section('content')

    <head>
        <style>
            /* Card Style */
            .policy-card {
                background: #fff;
                padding: 25px 30px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            }

            /* List Style */
            .policy-list {
                padding-left: 18px;
                margin-bottom: 20px;
            }

            .policy-list li {
                margin-bottom: 12px;
                line-height: 1.7;
                color: #555;
            }

            /* Highlight Box */
            .highlight-box {
                background: #fff6f6;
                border-left: 4px solid #ff5a3c;
                padding: 18px 20px;
                border-radius: 10px;
                margin-top: 20px;
            }

            .highlight-box p {
                font-size: 15px;
                color: #555;
                line-height: 1.6;
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
                            <h3 class="text-center tw-mb-6 text-neutral-950"> Privacy & Policy</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Privacy & Policy </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!------------------------------------------------------------------------------------------->

    <section class="course-content py-2" style="background: none;">
        <div class="container">

            <!-- Centered Layout -->
            <div class="row justify-content-center">
                <div class="col-lg-12">

                    <!-- Heading -->
                    <div class="section-header text-start mb-4" data-aos="fade-up">
                        <span class="fw-normal text-main-600 mb-2 d-block">
                            Privacy & Policy
                        </span>

                        <h2 style="font-size: 32px;">
                            We at Archer Chess Academy are committed to creating a safe and secure online environment for
                            you!
                        </h2>
                    </div>

                    <!-- Content Card -->
                    <div class="policy-card">

                        <ol class="policy-list">
                            <li>
                                We strive to give you access to and control over the information you provide us, and we
                                protect your information.
                            </li>
                            <li>
                                We take extra precautions for learners under the age of 15 by not allowing them to post or
                                disclose personal information.
                            </li>
                            <li>
                                We do not display advertising on our website. Our mission is to provide a better learning
                                experience.
                            </li>
                            <li>
                                Our Privacy Policy explains what information we collect, how we use it, and how we protect
                                it. By using our service, you agree to these practices.
                            </li>
                        </ol>

                        <!-- Highlight Box -->
                        <div class="highlight-box">
                            <p>
                                All verbal and written communication with your coach and management is strictly confidential
                                unless approved by you.
                            </p>

                            <p class="mb-0">
                                We are committed to protecting your privacy and ensuring that your personal information is
                                handled securely and lawfully.
                            </p>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>

    <!------------------------------------------------------------------------------------------->

    <section class="course-content py-2" style="background:none;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">

                    <!-- Heading -->
                    <div class="section-header text-start mb-2">
                        <h4 class="fw-bold policy-heading">
                            What information do we collect?
                        </h4>
                    </div>

                    <!-- Card -->
                    <div class="bg-white rounded shadow-sm policy-card">
                        <ol class="mb-0 policy-list">
                            <li>
                                When you register on our site, subscribe to our newsletter or fill out a form, we collect
                                information from you.
                            </li>
                            <li>
                                When confirming or registering on our site, you may be asked to enter your name, email
                                address, mailing address, or phone number. However, you may also visit our site anonymously.
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!------------------------------------------------------------------------------------------->

    <section class="course-content py-2" style="background:none;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">

                    <!-- Heading -->
                    <div class="section-header text-start mb-2">
                        <h4 class="fw-bold policy-heading">
                            What do we use your information for?
                        </h4>
                    </div>

                    <!-- Card -->
                    <div class="bg-white rounded shadow-sm policy-card">
                        <ol class="mb-0 policy-list">
                            <li>To personalize your experience and better respond to your individual needs.</li>
                            <li>To improve our website based on the feedback and information we receive.</li>
                            <li>To improve customer service and support your requests more effectively.</li>
                            <li>To administer contests, promotions, surveys, or other features.</li>
                            <li>To send periodic emails regarding your enquiry, updates, tournaments, and other services.
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!------------------------------------------------------------------------------------------->

    <section class="section py-2">
        <div class="container">

            <div class="row align-items-center justify-content-center">

                <!-- Left Image -->
                <div class="col-lg-5 col-md-6 mb-4 mb-md-0">
                    <img src="/frontend/assets/img/share.png" alt="Img" class="img-fluid rounded">
                </div>

                <!-- Right Content -->
                <div class="col-lg-6 col-md-6">

                    <h2 class="fw-bold mb-4 text-center text-md-start">
                        Frequently Asked Questions
                    </h2>

                    <!-- FAQ 1 -->
                    <div class="mb-3 border rounded p-3">
                        <a class="fw-semibold d-block text-dark" data-bs-toggle="collapse" href="#collapseOne">
                            How do we protect your information?
                        </a>
                        <div id="collapseOne" class="collapse mt-2">
                            <p class="mb-0 text-muted">
                                We implement a variety of security measures to maintain the safety of your personal
                                information when you enter, submit, or access your personal information.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="mb-3 border rounded p-3">
                        <a class="fw-semibold d-block text-dark" data-bs-toggle="collapse" href="#course2">
                            Do we use cookies?
                        </a>
                        <div id="course2" class="collapse mt-2">
                            <p class="mb-0 text-muted">
                                Yes, Cookies are small files that a site or its service provider transfers to your computer
                                hard drive through your Web browser (if you allow), enabling the sites or service providers’
                                systems to recognize your browser and capture and remember certain information. We use
                                cookies to help us remember and process the items in your shopping cart, understand and save
                                your preferences for future visits and keep track of advertisements. If you prefer, you can
                                choose to have your computer warn you each time a cookie is being sent, or you can choose to
                                turn off all cookies via your browser settings. Like most websites, some of our services may
                                not function properly if you turn your cookies off.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border rounded p-3">
                        <a class="fw-semibold d-block text-dark" data-bs-toggle="collapse" href="#course3">
                            Do we disclose any information to outside parties?
                        </a>
                        <div id="course3" class="collapse mt-2">
                            <p class="mb-0 text-muted">
                                We do not sell, trade, or otherwise transfer your personally identifiable information to
                                outside parties. This does not include trusted third parties who assist us in operating our
                                website, conducting our business, or servicing you, so long as those parties agree to keep
                                this information confidential. We may also release your information when we believe release
                                is appropriate to comply with the law, enforce our site policies, or protect our or others’
                                rights, property, or safety. However, non-personally identifiable visitor information may be
                                provided to other parties for marketing, advertising, or other uses.


                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
@endsection
