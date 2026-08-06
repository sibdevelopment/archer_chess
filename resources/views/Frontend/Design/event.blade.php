@extends('layouts.revamp')
@section('title', 'Event')
@section('content')
    <style>
        .animation-item {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .icon-circle {
            width: 32px;
            height: 32px;
            background: rgb(255, 136, 176);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }
        @media (max-width: 767px) {
            .footer .tw-mt-210-px{
                margin-top:20px;
            }
            .gy-5{
                --bs-gutter-y: 1.5rem;
            }
            .bg-main-two-300{
                margin-top:3rem
            }
        }
    </style>

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
                            <h6 class="text-center tw-mb-6 text-neutral-950">EVERY EVENT IS NEW CHANCE TO GROW</h6>
                            <h3 class="text-center tw-mb-6 text-neutral-950">Events</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li>
                                    <a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405">
                                        <i class="las la-home"></i> Home
                                    </a>
                                </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li><span class="text-main-600 hover-text-main-700 tw-text-405">Events</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ====================== our events section start ======================= -->
    <div class="py-110">
        <div class="container">
            <div class="row gy-4">
                @forelse(($events ?? collect()) as $event)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                        <div class="animation-item">
                            <span class="w-100 tw-mb-6 overflow-hidden tw-rounded-top-20-px">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : '/frontend1/tcul-img/img/blog.png' }}"
                                    alt="{{ $event->title }}" class="w-100 course-item__img tw-duration-300"
                                    style="aspect-ratio:16/9;object-fit:cover;">
                            </span>

                            <div class="tw-px-7">
                                <div class="d-flex align-items-center tw-gap-2 tw-mb-3">
                                    <i class="ph-bold ph-calendar-dots text-main-600"></i>
                                    <span class="fw-normal tw-text-4 text-neutral-600">
                                        {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d F, Y') : '' }}
                                    </span>
                                </div>

                                <h5 class="fw-bold text-neutral-950 tw-mb-3">
                                    {{ $event->title }}
                                </h5>

                                @if($event->location)
                                    <div class="d-flex align-items-start tw-gap-2 tw-mb-3">
                                        <span class="icon-circle">
                                            <i class="ph-fill ph-map-pin-area"></i>
                                        </span>

                                        <span class="fw-normal tw-text-4 text-neutral-600">
                                            {{ $event->location }}
                                        </span>
                                    </div>
                                @endif

                                @if($event->short_description)
                                    <p class="fw-normal tw-text-4 text-neutral-600 tw-mb-4">
                                        {{ \Illuminate\Support\Str::limit($event->short_description, 160) }}
                                    </p>
                                @endif

                                @if($event->link)
                                    <a href="{{ $event->link }}" target="_blank" rel="noopener"
                                        class="btn btn-main-two d-inline-flex align-items-center tw-gap-2">
                                        Register
                                        <img src="/frontend1/assets/images/icon/banner-icon-white.png" alt="icon">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center bg-white tw-rounded-20-px tw-p-10">
                            <p class="mb-0 text-neutral-600">No events available.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- ====================== our events section end ======================= -->
@endsection
