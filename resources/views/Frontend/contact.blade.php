@extends('layouts.frontend')
@section('title')
    Page Name | Website Name
@endsection
@section('content')
    <div class="breadcrumb-bar page-banner breadcrumb-bar-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Contact</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- --------------------------------------------------------------------------------------------------->


    <!-- Contact Us Section :: -->
    <section id="contact" class="section" style="padding: 40px 0 !important;">
        <div class="container">
            <div class="row mt-4 align-items-center">
                <div class="col-lg-6">
                    <div class="section-header aos" data-aos="fade-up">
                        <div class="section-sub-head">
                            <span>Customer satisfaction is essential</span>
                            <h2> Contact Us </h2>
                        </div>
                        <div class="all-btn all-category d-flex align-items-center">
                            <!-- <a href="course-list.html" class="btn btn-primary">All Courses</a> -->
                        </div>
                    </div>
                    <div class="section-text aos pb-4" data-aos="fade-up">
                        <p class="mb-0">Your feedback and inquiries are important to us. Reach out to our team for any
                            questions or support you may need, and we'll get back to you as soon as possible.</p>
                    </div>
                    <div class="d-flex">
                        <div class="get-certified certified-group blur-border d-flex">
                            <div class=" d-flex align-items-center">
                                <div class="blur-box">
                                    <div class="certified-img ">
                                        <img src="/frontend/assets/img/icon/icon-3.svg" alt="Img" class="img-fluid">
                                    </div>
                                </div>
                                <ul>
                                    <li> We hope you’ll call, email, or utilize our Live Chat feature anytime you have a
                                        question or concern.</li>
                                    <li> Live Chat to look for the “Online – Click here to chat” pop up in the bottom right
                                        corner.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="get-certified certified-group blur-border d-flex">
                            <div class="d-flex align-items-center">
                                <div class="blur-box">
                                    <div class="certified-img ">
                                        <img src="/frontend/assets/img/icon/icon-2.svg" alt="Img" class="img-fluid">
                                    </div>
                                </div>
                                <ul>
                                    <li>&nbsp; Support : Monday - Saturday 24 hour</li>
                                    <li>&nbsp; +91-9152734675 &nbsp; | &nbsp; support@archerchessacademy.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="support-wrap">
                        <h5>Get in touch with us!</h5>
                        <form action="#">
                            <div class="input-block">
                                <label>First Name</label>
                                <input type="text" class="form-control" placeholder="Enter your first Name">
                            </div>
                            <div class="input-block">
                                <label>Email</label>
                                <input type="text" class="form-control" placeholder="Enter your email address">
                            </div>
                            <div class="input-block">
                                <label>Subject</label>
                                <input type="text" class="form-control" placeholder="Enter your Subject">
                            </div>
                            <div class="input-block">
                                <label>Description</label>
                                <textarea class="form-control" placeholder="Write down here" rows="4"></textarea>
                            </div>
                            <button class="btn btn-submit text-center">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3765.642430891318!2d72.84157427374308!3d19.297909844971525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b02c0b821505%3A0x7fcb82953b8ca8bf!2sArcher%20Chess%20Academy!5e0!3m2!1sen!2sin!4v1774939643530!5m2!1sen!2sin" width="100" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </section>
@endsection
