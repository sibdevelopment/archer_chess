
@extends('layouts.frontend')
@section('title')
Thankyou | ArcherKids
@endsection
@section('content')

<style>
    .error-box .btn-primary:hover, .error-box .btn-primary:active:not(:disabled):not(.disabled) {
        background: #917cf6;
    }
    .error-box .btn-primary {
        background: none;
    }
</style>

<div class="main-wrapper row align-items-center my-5">
    <div class="error-box col-md-6">
        <div class="error-box-img">
            <img src="/frontend/tcul_img/thankyou.svg" alt="Img" class="img-fluid">
        </div>
    </div>
    <div class="error-box col-md-6">
        <h3 class="h2 mb-3 text-dark">Thankyou for contacting us!</h3>
        <p class="h4 font-weight-normal">Your message is currently under review. We will get back to you as soon as possible</p>
        <div class="all-btn all-category text-center">
            <a href="/" class="btn btn-primary">Back to home</a>
        </div>
    </div>
</div>

@endsection
