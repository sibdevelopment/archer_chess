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
@media (max-width: 767.98px) {
    .student-course, .how-it-works, .new-course, .trend-course, .share-knowledge {
        padding: 10px 0;
    }
}
</style>


<div class="login-wrapper" style="height: 100%;">
    <div class="loginbox p-4">
        <div class="w-100">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-5 col-12">
                    <div class="knowledge-img aos" data-aos="fade-up">
                        <img src="/frontend/assets/img/share.png" alt="Img" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <h1 class="fw-bold text-center">Verify Your Email</h1>
                    <form class="" method="POST" enctype="multipart/form-data" autocomplete="off" id="verifymail-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="input-block">
                                    <label class="form-control-label" for="email">Email*</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" value="{{ $demoLeadEnquiry->email }}" readonly>
                                </div>
                                <div id="email-error" style="color:red"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-block">
                                    <label class="form-control-label" for="email">OTP Code*</label>
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter the otp code ..." required>
                                </div>
                                <div id="otp-error" style="color:red"></div>
                            </div>
                            <div class="col-lg-12 payment-btn mt-4">
                                <button class="btn btn-primary" type="submit" style="padding: 10px 20px; font-size: 16px;">Verify</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


