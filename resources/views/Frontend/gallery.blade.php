@extends('layouts.frontend')
@section('title')
    Archerkids | Gallery
@endsection
@section('content')

    <style>
        .blog-five-item .product-img-five {
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
        }

        .blog-five-item .product-img-five img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .blog-five-footer h3 a {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 3.2em;
            line-height: 1.6em;
            color: #000;
            text-decoration: none;
        }
    </style>

    <style>
        .hover-shadow:hover {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
        }



        .about-section .about-image {
            background: #fff;
            border: 1px solid #e0ebff;
            box-shadow: 0 4px 34px #e0ebff;
            border-radius: 10px;
            height: 300px;
            -webkit-transition: all 1.5s;
            transition: all 1.5s;
            margin-bottom: 24px;
        }

        .student-widget-group {
            background-image: url(/frontend/assets/img/bg/breadcrumb-bar.png) !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .student-widget-group:hover {
            background: #413655;
            color: #fff;
            transition: all .5s ease;
            -moz-transition: all .5s ease;
            -o-transition: all .5s ease;
            -ms-transition: all .5s ease;
            -webkit-transition: all .5s ease;
        }

        .student-widget-group:hover h4 {
            color: #fff;
        }

        .student-widget-group:hover h2 {
            color: #fff;
        }

        .student-widget-group:hover h3 {
            color: #fff;
        }

        .student-widget-group:hover ul li {
            color: #fff;
        }

        .instructor-box {
            position: relative;
            overflow: hidden;
        }

        .instructor-box:hover {
            background-color: #413655 !important;
            color: white !important;
        }

        .instructor-box .coach-info {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white !important;
        }

        .instructor-box:hover .coach-info {
            display: block;
        }

        .instructor-box:hover .instructor-content,
        .instructor-box:hover .instructor-img {
            opacity: 0.3;
        }

        .courses-img {
            width: 100%;
            height: 200px;
            /* Set a fixed height */
            object-fit: cover;
            /* Ensure the image covers the entire area */
        }
    </style>
    
    <section class="section trend-course" style="padding: 50px 0 !important">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="section-sub-head">
                    <span>What’s New</span>
                    <h2>GALLERY</h2>
                </div>
            </div>
            <div class="section-text aos" data-aos="fade-up">
                <p class="mb-0">Explore Our Gallery.</p>
            </div>

            @foreach ($galleries as $gallery)
                @php
                    $hasImages = false;

                    // Check if any of images_1 to images_5 has valid images
                    for ($i = 1; $i <= 5; $i++) {
                        $field = 'images_' . $i;
                        if (!empty($gallery->$field) && is_array($gallery->$field) && count($gallery->$field)) {
                            $hasImages = true;
                            break;
                        }
                    }
                @endphp

                @if ($hasImages)
                    <div class="mb-5">
                        <h4 class="mb-3" data-aos="fade-up">{{ $gallery->title }}</h4>

                        <div class="owl-carousel instructors-course owl-theme aos" data-aos="fade-up">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $imageField = 'images_' . $i;
                                    $images = $gallery->$imageField ?? [];
                                @endphp

                                @if (is_array($images))
                                    @foreach ($images as $image)
                                        <div class="item">
                                            <div class="instructors-widget">
                                                <div class="instructors-img" style="border-radius: 10px !important; height: 200px; overflow: hidden;">
                                                    <a href="{{ Storage::url($image) }}" target="_blank">
                                                        <img class="img-fluid" alt="Gallery Image" src="{{ Storage::url($image) }}" style="height: 100% !important;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endfor
                        </div>
                    </div>
                @endif
            @endforeach



        </div>

    </section>
@endsection