@extends('layouts.revamp')
@section('title', $blog->meta_title ?: $blog->title)
@section('content')
    @push('meta')
        <meta name="title" content="{{ $blog->meta_title ?: $blog->title }}" />
        <meta name="description" content="{{ $blog->meta_description ?: $blog->short_description }}" />
    @endpush
    <style>
        @media (max-width: 767px) {
            .footer .tw-mt-210-px{
                margin-top:20px;
            }
            .gy-5{
                --bs-gutter-y: 1.5rem;
            }
            .tw-px-7{
                padding-inline:5px;
            }
            .mobilePadd{
                padding:15px
            }
            .rightDes{
                width: calc(100% - 115px);
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
                        <div>
                            <h3 class="text-center tw-mb-6 text-neutral-950">Blog Details</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="{{ route('home') }}" class="text-main-600 hover-text-main-700 tw-text-405">
                                        <i class="las la-home"></i> Home</a></li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li><a href="{{ route('blog') }}"
                                        class="text-main-600 hover-text-main-700 tw-text-405">Blogs</a></li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li><span class="text-main-600 hover-text-main-700 tw-text-405">Details</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $image = $blog->main_img ?: $blog->cover_img;
        $imageUrl = $image ? asset(Storage::url($image)) : '/frontend1/tcul-img/img/blog2.png';
        $blogDate = $blog->date ? \Carbon\Carbon::parse($blog->date)->format('d F, Y') : '';
        $descriptionFrameId = 'blog-description-frame-' . $blog->id;
        $descriptionTemplateId = 'blog-description-template-' . $blog->id;
    @endphp

    <div class="py-110">
        <div class="container-fluid px-3 px-xl-5">
            <div class="row gy-5 gx-xl-5 justify-content-between">
                <div class="col-xl-8 col-lg-8">
                    <div>
                        <img src="{{ $imageUrl }}" alt="{{ $blog->title }}" class="bg-img tw-mb-10">
                        <div class="d-flex align-items-center tw-gap-3 flex-wrap tw-mb-6">
                            @if ($blogDate)
                                <div class="d-flex align-items-center tw-gap-1">
                                    <span class="tw-text-405 text-main-600">
                                        <i class="ph-bold ph-calendar-blank"></i>
                                    </span>
                                    <span class="fw-normal tw-text-4 text-paragraph-500">{{ $blogDate }}</span>
                                </div>
                            @endif
                            @if ($blog->label)
                                <span class="tw-w-1 tw-h-1 bg-neutral-600 rounded-circle"></span>
                                <span class="fw-normal tw-text-4 text-paragraph-500">{{ $blog->label }}</span>
                            @endif
                        </div>
                        <h4 class="fw-bold text-neutral-950 tw-text-44-px tw-mb-6">
                            {{ $blog->title }}
                        </h4>
                        <template id="{{ $descriptionTemplateId }}">
                            {!! $blog->description !!}
                        </template>
                        <iframe id="{{ $descriptionFrameId }}" class="revamp-blog-description-frame tw-mb-7"
                            title="{{ $blog->title }} content" loading="lazy"
                            style="width: 100%; min-height: 240px; border: 0; display: block; overflow: hidden;"></iframe>
                        <script>
                            (function() {
                                var frame = document.getElementById(@json($descriptionFrameId));
                                var template = document.getElementById(@json($descriptionTemplateId));

                                if (!frame || !template) {
                                    return;
                                }

                                var frameDoc = '<!doctype html><html><head>' +
                                    '<meta charset="utf-8">' +
                                    '<meta name="viewport" content="width=device-width, initial-scale=1">' +
                                    '<base target="_top">' +
                                    '<style>' +
                                    'html,body{margin:0;padding:0;overflow:hidden;background:transparent;}' +
                                    'body{font-family:Arial,sans-serif;color:#1f2937;line-height:1.6;}' +
                                    '*,*::before,*::after{box-sizing:border-box;}' +
                                    'img,video,iframe{max-width:100%;height:auto;}' +
                                    'table{width:100%;max-width:100%;border-collapse:collapse;}' +
                                    '</style>' +
                                    '</head><body>' +
                                    template.innerHTML +
                                    '<style>' +
                                    '.hero{min-height:auto!important;padding:56px 6% 44px!important;}' +
                                    '.hero-left{padding-bottom:0!important;}' +
                                    '.hero-inner{align-items:center!important;gap:32px!important;}' +
                                    '.hero-right img{height:min(300px,42vw)!important;}' +
                                    '@media(max-width:767px){.hero{padding:40px 5% 32px!important;}.hero-inner{gap:24px!important;}.hero-right img{height:240px!important;}}' +
                                    '</style>' +
                                    '<script>' +
                                    '(function(){' +
                                    'function sendHeight(){' +
                                    'var body=document.body,html=document.documentElement;' +
                                    'var height=Math.max(body.scrollHeight,body.offsetHeight,html.clientHeight,html.scrollHeight,html.offsetHeight);' +
                                    'parent.postMessage({type:"revamp-blog-description-height",id:' + JSON.stringify(frame.id) + ',height:height},"*");' +
                                    '}' +
                                    'window.addEventListener("load",sendHeight);' +
                                    'if("ResizeObserver" in window){new ResizeObserver(sendHeight).observe(document.body);}' +
                                    'setTimeout(sendHeight,300);setTimeout(sendHeight,1000);' +
                                    '})();' +
                                    '<\/script>' +
                                    '</body></html>';

                                window.addEventListener('message', function(event) {
                                    if (!event.data || event.data.type !== 'revamp-blog-description-height' || event.data.id !== frame.id) {
                                        return;
                                    }

                                    var height = parseInt(event.data.height, 10);
                                    frame.style.height = Math.max(height || 0, 240) + 'px';
                                });

                                frame.srcdoc = frameDoc;
                            })();
                        </script>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 ms-xl-auto">
                    <div class="tw-py-705 tw-ps-705 tw-pe-6 border tw-rounded-xl tw-mb-10 bg-white mobilePadd">
                        <h5 class="fw-bold text-neutral-950 tw-mb-6">Recent Blogs</h5>
                        @forelse ($similarBlogs as $similarBlog)
                            @php
                                $similarImage = $similarBlog->cover_img ?: $similarBlog->main_img;
                                $similarImageUrl = $similarImage ? asset(Storage::url($similarImage)) : '/frontend1/assets/images/thumbs/blog-details-img9.png';
                                $similarDate = $similarBlog->date ? \Carbon\Carbon::parse($similarBlog->date)->format('d F, Y') : '';
                            @endphp
                            <div class="d-flex align-items-center tw-gap-5 tw-mb-6 flex-wrap">
                                <img src="{{ $similarImageUrl }}" alt="{{ $similarBlog->title }}"
                                    style="width: 92px; height: 72px; object-fit: cover;">
                                <div class="rightDes">
                                    <span class="fw-medium tw-text-305 text-main-600 tw-mb-3 d-block">
                                        {{ $similarDate }}
                                    </span>
                                    <a href="{{ route('blog.details', ['slug' => $similarBlog->slug]) }}"
                                        class="fw-semibold tw-text-4 text-neutral-950 hover-text-main-600 tw-duration-300">
                                        {{ $similarBlog->title }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="fw-normal tw-text-4 text-paragraph-500 mb-0">No recent blogs available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
