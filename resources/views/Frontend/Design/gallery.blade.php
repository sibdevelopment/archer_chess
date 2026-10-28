@extends('layouts.revamp')
@section('title', 'Our Archer Kids - Chess Champions')
@section('description', 'Explore photos of chess champions, student achievements, tournaments, and memorable moments. See our young chess players in action.')
@section('content')
<style>
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
                            <h1 class="text-center tw-mb-6 text-neutral-950">Image Gallery</h1>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li>
                                    <a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405">
                                        <i class="las la-home"></i> Home
                                    </a>
                                </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li><span class="text-main-600 hover-text-main-700 tw-text-405">Image Gallery</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ==================== Breadcrumb End Here ==================== -->

    <!-- ===================== image gallery section start ========================= -->
    <section class="py-110">
        <div class="container">
            <div class="row gy-4">
                @foreach(($meetourkids ?? collect()) as $kid)
                    @php
                        $kidImageUrl = $kid->image ? asset('storage/' . $kid->image) : '/frontend1/tcul-img/gallery/gallery1.png';
                    @endphp
                    <div class="col-lg-4 col-md-6 archer-kid-gallery-item">
                        <a href="{{ $kidImageUrl }}" class="gallery-thumb gallery-popup d-block">
                            <img src="{{ $kidImageUrl }}" alt="{{ $kid->name ?: 'Archer Kid' }}"
                                class="w-100 square-img" style="aspect-ratio:1/1;object-fit:cover;">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- ===================== image gallery section end ========================= -->
@endsection
