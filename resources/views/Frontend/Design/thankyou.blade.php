@extends('layouts.revamp')
@section('title', 'Thank You | ArcherKids')

@push('meta')
    <meta name="description" content="Thank you for booking a trial class with Archer Chess Academy." />
@endpush

@section('head')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'CompleteRegistration');
        }
    </script>
@endsection

@section('content')
    <section class="breadcrumb pt-60 pb-20 bg-main-two-200 position-relative">
        <img src="/frontend1/assets/images/shape/banner-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-15 d-lg-block d-none animation-upDown">
        <div class="tw-mb-120-px w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h3 class="tw-mb-6 text-neutral-950">Thank You</h3>
                        <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                            <li>
                                <a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405">
                                    <i class="las la-home"></i> Home
                                </a>
                            </li>
                            <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                            <li><span class="text-main-600 hover-text-main-700 tw-text-405">Trial Booked</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-110">
        <div class="container">
            <div class="row gy-5 align-items-stretch">
                <div class="col-lg-7">
                    <div class="h-100 bg-main-two-50 tw-rounded-2xl tw-p-10 border">
                        <span
                            class="tw-w-16 tw-h-16 bg-main-600 text-white d-flex align-items-center justify-content-center rounded-circle tw-text-8 tw-mb-7">
                            <i class="ph-bold ph-check"></i>
                        </span>
                        <h3 class="fw-bold text-neutral-950 tw-mb-5">Thank You for Booking a Trial Class!</h3>
                        <p class="fw-semibold tw-text-405 text-neutral-700 tw-mb-4">
                            Dear {{ session('demo_lead_enquiry')['kids_first_name'] ?? 'Guest' }},
                        </p>
                        <p class="fw-normal tw-text-405 text-paragraph-500 tw-mb-4">
                            We have received your trial class enquiry and it has been successfully recorded in our system.
                            Our team will review your enquiry and get back to you shortly with further details.
                        </p>
                        <p class="fw-normal tw-text-405 text-paragraph-500 tw-mb-8">
                            If you have any questions or need further assistance, please reach out to us. Thank you for
                            choosing ArcherKids. We look forward to providing you with an excellent learning experience.
                        </p>
                        <a href="{{ route('home') }}"
                            class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 tw-border-bottom-main-two-600 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl"
                            data-block="button">
                            <span class="button__flair"></span>
                            <span class="button__label">Back to Home</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="h-100 bg-white border tw-rounded-2xl tw-p-10">
                        <h4 class="fw-bold text-neutral-950 tw-mb-3">Welcome to Archer Chess Academy</h4>
                        <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-7">
                            Please sign in to view your student profile.
                        </p>
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('student.login.store') }}">
                            @csrf
                            <input type="hidden" name="identity" value="Student">
                            <div class="tw-mb-5">
                                <label class="fw-semibold text-neutral-950 tw-mb-2">Mobile</label>
                                <input type="text" class="form-control tw-py-4 tw-px-5 tw-rounded-xl" name="mobile"
                                    value="{{ isset($user) ? $user->mobile : old('mobile') }}">
                                @error('mobile')
                                    <div class="text-danger tw-mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="tw-mb-6">
                                <label class="fw-semibold text-neutral-950 tw-mb-2">Password</label>
                                <input type="text" class="form-control tw-py-4 tw-px-5 tw-rounded-xl" name="password"
                                    value="{{ isset($user) ? $user->device_id : old('password') }}">
                                @error('password')
                                    <div class="text-danger tw-mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit"
                                class="btn btn-main hover-style-one button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl w-100"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Sign In</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
