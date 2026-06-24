@extends('layouts.frontend')

@section('title')
    Pay Now | Archer Chess Academy
@endsection

@section('content')
    <div class="breadcrumb-bar page-banner breadcrumb-bar-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Pay Now</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pay Now</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="course-content" style="background: none;">
        <div class="container">
            <div class="section-header aos pb-4" data-aos="fade-up">
                <div class="section-sub-head">
                    <span>Secure Payment</span>
                    <h2>Select Your Country To Complete Payment</h2>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($paymentLinks as $country => $link)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h5 class="mb-3">{{ $country }}</h5>
                                <p class="text-muted flex-grow-1 mb-4">Proceed to the payment page for {{ $country }}.</p>
                                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-primary w-100">
                                    Pay Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
