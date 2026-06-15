@extends('layouts.admin')
@section('title')
    Blogs
@endsection
@section('content')
    <form method="POST"
        action="{{ Route::is('admin.blogs.create') ? route('admin.blogs.store') : route('admin.blogs.update', ['blog' => $blog->id]) }}"
        enctype="multipart/form-data" autocomplete="off" id="blog-form">
        @csrf
        {{ Route::is('admin.blogs.create') ? '' : method_field('PUT') }}
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> {{ Route::is('admin.blogs.create') ? 'Create' : 'Edit' }} Blog </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            {{-- <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Index<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Enter index" name="index"
                                    value="{{ isset($blog) ? $blog->index : '' }}" />
                                <div id="index-error" style="color:red"></div>
                            </div> --}}
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Date<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="date" class="form-control" placeholder="Date" name="date"
                                    value="{{ isset($blog) ? $blog->date : '' }}" />
                                <div id="date-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Category<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Enter Category" name="label"
                                    value="{{ isset($blog) ? $blog->label : '' }}" />
                                <div id="label-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Name<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Enter Name" name="title"
                                    id="title" value="{{ isset($blog) ? $blog->title : '' }}" />
                                <div id="title-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Slug<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <input type="text" class="form-control" placeholder="Slug" name="slug" id="slug"
                                    value="{{ isset($blog) ? $blog->slug : '' }}" />
                                <div id="slug-error" style="color:red"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> Description </h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Short Description<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <textarea class="form-control" placeholder="Enter Short Description" name="short_description">{{ isset($blog) ? $blog->short_description : '' }}</textarea>
                                <div id="short_description-error" style="color:red"></div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Description<sup
                                        class="tcul-star-restrict">*</sup></label>
                                <textarea class="form-control summernote" placeholder="Enter Description" name="description">
                                    {{ isset($blog) ? $blog->description : '' }}
                                </textarea>
                                <div id="description-error" style="color:red"></div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> Upload Image </h5>
                    </div>
                    <div class="card-body border-top">

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Cover Image <sup class="text-danger">(416 x 227
                                        )*</sup></label>
                                <input type="file" class="form-control" name="cover_img"/>
                                <div id="cover_img-error" style="color:red"></div>
                                @if (isset($blog) && $blog->cover_img)
                                    <img src="{{ asset('storage/' . $blog->cover_img) }}" alt="Cover Image"
                                        style="max-width: 150px; margin-top:10px;">
                                @endif
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <label class="control-label col-form-label">Main Image <sup class="text-danger">(996 x 600
                                        )*</sup></label>
                                <input type="file" class="form-control" name="main_img"/>
                                <div id="main_img-error" style="color:red"></div>
                                @if (isset($blog) && $blog->main_img)
                                    <img src="{{ asset('storage/' . $blog->main_img) }}" alt="Main Image"
                                        style="max-width: 150px; margin-top:10px;">
                                @endif
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-header">
                        <h5> Meta Description</h5>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Meta Title</label>
                                <textarea class="form-control" placeholder="Meta Title" name="meta_title">{{ isset($blog) ? $blog->meta_title : '' }}</textarea>
                                <div id="meta_title-error" style="color:red"></div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <label class="control-label col-form-label">Meta Description</label>
                                <textarea class="form-control" placeholder="Meta Description" name="meta_description">{{ isset($blog) ? $blog->meta_description : '' }}</textarea>
                                <div id="meta_description-error" style="color:red"></div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                Save
                &nbsp;
                <i class="ti ti-device-floppy"></i>
            </button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('admin.blogs.index') }}" type="button" class="btn btn-secondary">
                Cancel
                &nbsp;
                <i class="ti ti-arrow-back-up-double"></i>
            </a>
        </div>
    </form>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

    <script>
        $('#blog-form').submit(function(e) {
            e.preventDefault();
            $('div[id$="-error"]').empty();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = "{!! route('admin.blogs.index') !!}";
                        }, 100);
                    } else {
                        toastr.error('There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    toastr.error('There are some errors in Form. Please check your inputs', '', {
                        showMethod: "slideDown",
                        hideMethod: "slideUp",
                        timeOut: 1500,
                        closeButton: true,
                    });
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('#' + key + '-error').html(value);
                    });
                    $('html, body').animate({
                        scrollTop: $('#' + Object.keys(xhr.responseJSON.errors)[0] + '-error')
                            .offset().top - 200
                    }, 500);
                }
            });
        });
        //text area
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300, // Set height for editor
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript', 'fontname', 'fontsize',
                        'color'
                    ]],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'table']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ]
            });
        });

        //slug
        $('#title').on('input', function() {
            const title = $(this).val();
            const slug = slugify(title);
            $('#slug').val(slug);
        });

        function slugify(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/[^a-z0-9-]/g, '') // Remove any non-alphanumeric or hyphen characters
                .replace(/-+/g, '-'); // Remove consecutive hyphens
        }
    </script>
@endsection
