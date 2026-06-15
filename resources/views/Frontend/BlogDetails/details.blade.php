@extends('layouts.frontend')
@section('title')
    {{ $blog->meta_title }} | Archer Kids
@endsection
<meta name="title" content="{{ $blog->meta_title }}" />
<meta name="description" content="{{ $blog->meta_description }}" />
@section('content')
    <div class="breadcrumb-bar breadcrumb-bar-info">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Blog Details</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="/blog">Blogs</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="course-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="blog">
                        <div class="blog-image">
                            <a href="#"><img class="img-fluid" src="{{ asset(Storage::url($blog->main_img)) }}"
                                    alt="Post Image" style="width: 100%; height: 500px; object-fit: contain;"></a>
                        </div>
                        <div class="blog-info clearfix">
                            <div class="post-left">
                                <ul>
                                    <li>
                                        <div class="post-author">
                                            <a href="#"><img src="/frontend/tcul_img/home/archer_favicon.png"
                                                    alt="Post Author"> <span>Archer Chess</span></a>
                                        </div>
                                    </li>
                                    <!-- ------------------ :: -->
                                    <li>
                                        <img class="img-fluid" src="/frontend/assets/img/icon/icon-22.svg" alt="Img">
                                        {{ $blog->date }}
                                    </li>

                                    <!-- ------------------ :: -->

                                    <li>
                                        <img class="img-fluid" src="/frontend/assets/img/icon/icon-23.svg" alt="Img">
                                        {{ $blog->label }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- ------------------ :: -->
                        <h3 class="blog-title"><a href="#"> {{ $blog->title }}</a></h3>
                        <!-- ------------------ :: -->
                        <div class="blog-content">
                            <p> {!! $blog->description !!}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 sidebar-right theiaStickySidebar">
                    <div class="card post-widget blog-widget">
                        <div class="card-header">
                            <h4 class="card-title">Recent Blogs</h4>
                        </div>
                        <div class="card-body">
                            <ul class="latest-posts">
                                @foreach ($similarBlogs as $similarBlog)
                                    <li>
                                        <div class="post-thumb">
                                            <a href="{{ route('blog.details', ['slug' => $similarBlog->slug]) }}">
                                                <img class="img-fluid" src="{{ asset(Storage::url($similarBlog->main_img)) }}" alt="Img">
                                            </a>
                                        </div>
                                        <div class="post-info">
                                            <h4>
                                                <a href="{{ route('blog.details', ['slug' => $similarBlog->slug]) }}">{{ $similarBlog->title }}</a>
                                            </h4>
                                            <p><img class="img-fluid" src="/frontend/assets/img/icon/icon-22.svg" alt="Img">{{ $blog->date }}</p>
                                        </div>
                                    </li> 
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
