@extends('layouts.revamp')
@section('title', 'Our FIDE-Rated Online Chess Coaches - Archer Chess Academy')
@section('description', 'Meet FIDE-rated online chess coaches. Learn chess online with experienced coaches offering structured chess coaching online for kids worldwide.')
@section('head')
    <link rel="canonical" href="https://www.archerchessacademy.com/chess-coaches">
@endsection
@section('content')
    <style>
        .coach-grid .tutor-image-frame {
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: #f6f6f6;
        }

        .coach-grid .tutor-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        @media (max-width: 767px) {
            .footer .tw-mt-210-px {
                margin-top: 20px;
            }
        }
    </style>

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
                        <h1 class="text-center tw-mb-6 text-neutral-950">Our FIDE-Rated Online Chess Coaches</h1>
                        <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                            <li>
                                <a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405">
                                    <i class="las la-home"></i> Home
                                </a>
                            </li>
                            <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                            <li><span class="text-main-600 hover-text-main-700 tw-text-405">Chess Coaches</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-110 position-relative">
        <img src="/frontend1/tcul-img/img/expert-1.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 d-xl-block d-none animation-upDown tw-mt-8 z-n1">
        <img src="/frontend1/tcul-img/img/expert-2.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 d-xl-block d-none animation-scalation tw-me-144-px z-n1">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6" data-aos="zoom-in" data-aos-duration="1500">
                    <div class="position-relative">
                        <img src="/frontend1/tcul-img/img/gm-thejkumar.png" alt="Grandmaster Thejkumar M. S." class="w-100">
                        <img src="/frontend1/assets/images/shape/kindergarden-shape4.png" alt="shape"
                            class="position-absolute top-0 tw-start-0 tw-mt-234-px tw-ms--72-px d-xl-block d-none animation-upDown">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center">
                        <div class="max-w-520-px w-100 text-middle">
                            <span class="fw-normal tw-text-405 text-main-600 tw-mb-6 text-middle" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="100">Grandmaster coach at Archer Chess Academy</span>
                            <h2 class="text-neutral-950 fw-bold tw-mb-7 h4 text-middle" data-aos="fade-up"
                                data-aos-duration="600" data-aos-delay="150">Grandmaster Thejkumar M. S.</h2>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-2 line-height-28-px text-middle"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">Thejkumar M. S. is India's 50th grandmaster and the first from Karnataka. He earned the title in September 2017, at 36, after years of steady rating growth and six norms. His FIDE rating peaked at 2500 (FIDE ID 5009154).</p>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-7 line-height-28-px text-middle"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">In June 2018, he won the Karnataka State Level Open Rapid tournament with a perfect 9/9 score. Before that, he represented Southwestern Railway at rating tournaments across India and abroad.</p>
                            <p class="fw-normal tw-text-4 text-paragraph-500 tw-mb-7 line-height-28-px text-middle"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">At Archer Chess Academy, he continues to shape the future of chess through his mentorship and leadership.</p>
                            <div class="d-flex align-items-center textLeft justify-content-between tw-gap-4 flex-wrap tw-mb-7"
                                data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                                <div class="d-flex align-items-center tw-gap-4 animation-item">
                                    <img src="/frontend1/tcul-img/img/eklavya-award.png" alt="Eklavya award" class="animate__swing">
                                    <h3 class="fw-bold text-neutral-950 line-height-28-px h6">Eklavya award<br>winner</h3>
                                </div>
                                <div class="d-flex align-items-center tw-gap-4 animation-item">
                                    <img src="/frontend1/tcul-img/img/grandmaster.png" alt="Grandmaster" class="animate__swing">
                                    <h3 class="fw-bold text-neutral-950 line-height-28-px h6">1st grandmaster<br>of state of Karnataka</h3>
                                </div>
                            </div>
                            <a href="#0" data-bs-toggle="modal" data-bs-target="#registrationModal"
                                class="btn btn-main-two hover-style-two button--stroke active-scale-094 tw-duration-100 d-inline-flex align-items-center justify-content-center tw-gap-5 group active--translate-y-2 tw-rounded-2xl tw-text-4 tw-border-bottom-our-kindergarden"
                                data-block="button">
                                <span class="button__flair"></span>
                                <span class="button__label">Book Your Trial</span>
                                <span class="text-white tw-text-2xl group-hover-text-white tw-duration-500 position-relative">
                                    <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="">
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="coach-grid pt-110 pb-80 position-relative">
        <img src="/frontend1/tcul-img/img/horse.svg" alt="shape"
            class="position-absolute bottom-0 tw-end-0 tw-me-130-px tw-mb-110-px d-lg-block d-none z-n1 animation-upDown">
        <img src="/frontend1/tcul-img/img/blog-element.svg" alt="shape"
            class="position-absolute top-0 tw-start-0 d-lg-block d-none tw-mt-186-px tw-ms-200-px animation-upDown">
        <div class="container">
            <div class="text-center tw-mb-12">
                <span class="fw-normal tw-text-405 text-main-600 tw-mb-2" data-aos="fade-up"
                    data-aos-duration="600" data-aos-delay="100">Where Learning Thrives</span>
                <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600"
                    data-aos-delay="200">Meet Our FIDE-rated Online Chess Tutors</h2>
            </div>
            <div class="row gy-5">
                @foreach ($meetourtutors as $index => $tutor)
                    @php
                        $rotationClasses = ['tw-transform-roted-1', 'tw-transform-roted--4', 'tw-transform-roted-3'];
                        $delay = (($index % 3) + 1) * 100;
                        $tutorImage = $tutor->image ? Storage::url($tutor->image) : '/frontend1/tcul-img/img/tutor1.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 coach-grid-item" data-aos="fade-up" data-aos-duration="600"
                        data-aos-delay="{{ $delay }}">
                        <div class="our-teacher-thumb {{ $rotationClasses[$index % count($rotationClasses)] }} h-100">
                            <div class="position-relative tutor-image-frame">
                                <img src="{{ $tutorImage }}" alt="{{ $tutor->name ?? 'Tutor' }}" class="tutor-image">
                                <div class="our-teacher-link">
                                    <ul class="d-flex align-items-center tw-gap-2 position-absolute tw-start-0 bottom-0 tw-mb-9 translate-middle-x tw-start-50">
                                        <li>
                                            <span class="tw-h-11 bg-white border-neutral-900 border tw-rounded-lg text-black tw-text-5 d-flex align-items-center justify-content-center">
                                                <span class="fw-normal tw-text-405 text-main-two-600">
                                                    @for ($star = 0; $star < 5; $star++)
                                                        <i class="ph-fill ph-star ms-1 fs-22"></i>
                                                    @endfor
                                                </span>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center tw-mt-6">
                                <h3 class="h5 fw-bold text-neutral-950 tw-mb-2">{{ $tutor->name }}</h3>
                                @if (!empty($tutor->designation))
                                    <p class="fw-normal tw-text-405 text-neutral-600 mb-1">{{ $tutor->designation }}</p>
                                @endif
                                @if (!empty($tutor->rating))
                                    <span class="fw-normal tw-text-405 text-neutral-600 t_green">{{ $tutor->rating }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
