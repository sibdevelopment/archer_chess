<style>
    .login-wrapper {
        max-width: 100%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        justify-content: center;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        -webkit-flex-wrap: wrap;
    }

    /* Default styles for the image */
    .responsive-img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    /* Media query for mobile view */
    @media (max-width: 768px) {
        .responsive-img {
            width: 90%;
            /* Reduce the width for mobile view */
        }
    }

    /* Default styles for the image */
    .logo-img {
        max-width: 30%;
        height: auto;
    }

    /* Media query for mobile view */
    @media (max-width: 768px) {
        .logo-img {
            width: 20%;
            /* Reduce the width for mobile view */
        }
    }
</style>
<div class="login-wrapper" style="height: 60vh !important;">
    <div class="loginbox p-4">
        <div class="w-100">
            <div class="section-header aos mt-4 mb-0" data-aos="fade-up">
                <div class="section-sub-head feature-head text-center">
                    <h2> Success </h2>
                    <h4
                        style="color: #f66962; font-size: 20px; font-weight: 700; padding-bottom: 18px; display: block; letter-spacing: .9px;">
                        Your lead has been successfully received and your email has been verified. <br>
                        Thank you for verifying your email. You will receive a confirmation email shortly.
                    </h4>
                    <img class="responsive-img" src="/frontend/tcul_img/email-success.jpg" alt="Email Success">
                </div>
            </div>
            {{--
            <div class="icon-group aos" data-aos="fade-up">
                <div class="offset-lg-1 col-lg-10 mx-auto">
                    <div class="row justify-content-center">
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-6.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-5.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-4.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-3.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-2.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-1.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-2.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-3.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-4.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-5.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="total-course d-flex align-items-center justify-content-center">
                                <div class="blur-border"
                                    style="box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;">
                                    <div class="enroll-img">
                                        <img src="/frontend/tcul_img/chess/chess-pieces-6.png" alt="Img"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>
</div>