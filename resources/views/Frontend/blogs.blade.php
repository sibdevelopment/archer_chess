@extends('layouts.frontend')
@section('title')
    Page Name | Website Name
@endsection
@section('content')
    <div class="breadcrumb-bar page-banner breadcrumb-bar-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Blog</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Blog</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <section class="course-content">
        <div class="container">
            <div class="row masonry-blog-blk">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog grid-blog">
                            <div class="blog-image">
                                <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}">
                                    <img class="img-fluid" src="{{ asset(Storage::url($blog->cover_img)) }}"
                                        alt="Post Image">
                                </a>
                            </div>
                            <div class="blog-grid-box masonry-box">
                                <div class="blog-info clearfix">
                                    <div class="post-left">
                                        <ul>
                                            <li>
                                                <img class="img-fluid" src="/frontend/assets/img/icon/icon-22.svg"
                                                    alt="Img">
                                                {{ $blog->date }}
                                            </li>

                                            <li>
                                                <img class="img-fluid" src="/frontend/assets/img/icon/icon-23.svg"
                                                    alt="Img">
                                                {{ $blog->label }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <h3 class="blog-title">
                                    <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <div class="blog-content blog-read">
                                    <p>{{ $blog->short_description }}</p>
                                    <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}"
                                        class="read-more btn btn-primary">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- ----- :: -->
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- -------------------------------------------------------------------------------------------------- :: -->
@endsection
