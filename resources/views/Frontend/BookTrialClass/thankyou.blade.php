@extends('layouts.frontend')
@section('title')
Thank You | ArcherKids
@endsection
@section('head')
<!-- Meta Pixel Code -->
<script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return; n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
        n.queue = []; t = b.createElement(e); t.async = !0;
        t.src = v; s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '777952784455654');
    fbq('track', 'PageView');
    fbq('track', 'CompleteRegistration');
</script>
<noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=777952784455654&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->
@endsection

@section('content')
<style>
    .thankyou-box {
        text-align: center;
        padding: 30px;
        /* border: 1px solid #ddd; */
        border-radius: 10px;
        background: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .thankyou-box-img img {
        max-width: 100%;
        height: auto;
        margin-bottom: 20px;
    }

    .thankyou-box h3 {
        font-size: 32px;
        color: #333;
        margin-bottom: 20px;
    }

    .thankyou-box p {
        font-size: 18px;
        color: #555;
        margin-bottom: 15px;
        line-height: 1.6;
    }
    .bg-none {
        background: none!important;
        border: none;
        box-shadow: none;
    }
    .signin-box {
        text-align: center;
        padding: 30px;
        border: 1px solid #ececec;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container my-5">
    <div class="row align-items-start thankyou-box">
        <div class="col-md-8">
            <div class="thankyou-box bg-none">
                <div class="thankyou-box-img">
                    {{-- <img src="/frontend/tcul_img/thankyou.svg" alt="Thank You" style="height: 180px;"> --}}
                </div>
                <h3>Thank You for Booking a Trial Class!</h3>
                <p>Dear {{ session('demo_lead_enquiry')['kids_first_name'] ?? 'Guest' }},</p>
                <p>We have received your trial class enquiry and it has been successfully recorded in our system. Our
                    team will review your enquiry and get back to you shortly with further details.</p>
                <p>If you have any questions or need further assistance, please do not hesitate to reach out to us.
                    Thank you for choosing ArcherKids. We look forward to providing you with an excellent learning
                    experience.</p>

                <div class="all-btn all-category text-center">
                    <a href="/" class="btn btn-primary">Back to home</a>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-xxl-4">
            <div class="signin-box">
                <div class="authentication-login bg-body">
                        <div class="text-center">
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <h4 class="mb-3 fs-7 fw-bolder">Welcome to Archer Chess Academy</h4>
                        <form method="POST" action="{{ route('student.login.store') }}" style="text-align: left;">
                            @csrf
                            <input type="hidden" name="identity" value="Student">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Mobile</label>
                                <input type="text" class="form-control" name="mobile" value="{{ isset($user) ? $user->mobile : old('mobile') }}">

                                @error('mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-4">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="text" class="form-control" name="password" value="{{ isset($user) ? $user->device_id : old('password') }}">

                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign
                                In</button>
                            <p style="font-size: 15px;">Note: Please sign in to view your student profile.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
