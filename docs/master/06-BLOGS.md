# Blogs Module

## Purpose

Blogs manage website article content, SEO metadata, cover image, main image, and home featured status.

## Sidebar Path

```text
Master > Blogs
```

## Main Routes

```php
Route::resource('blogs', BlogController::class);
Route::post('blogs/data', [BlogController::class, 'data'])->name('blogs.data');
Route::post('blogs/list', [BlogController::class, 'list'])->name('blogs.list');
Route::post('blogs/change-status', [BlogController::class, 'changeStatus'])->name('blogs.change.status');
Route::post('blogs/change-home_featured-status', [BlogController::class, 'changeHomeFeaturedStatus'])->name('blogs.change.home_featured.status');
```

Frontend routes reference blog pages through `HomeController` design routes.

## Main Files

```text
app/Http/Controllers/Admin/BlogController.php
app/Models/Blog.php
resources/views/Admin/Blogs/index.blade.php
resources/views/Admin/Blogs/form.blade.php
resources/views/Admin/Blogs/show.blade.php
database/migrations/2025_05_05_160523_create_blogs_table.php
```

## Table

```text
blogs
```

Fields:

```text
index
date
label
title
short_description
description
cover_img
main_img
slug
meta_title
meta_description
status
home_featured
created_by
updated_by
```

## Listing Behavior

Main method:

```php
BlogController::data()
```

Rows are ordered by date descending.

Columns include:

```text
Index
Date
Label
Title
Short Description
Description
Cover Image
Main Image
Slug
Meta Title
Meta Description
Home Featured
Status
Action
```

Long text is trimmed for label, title, and short description.

Images are shown from:

```text
storage/{cover_img}
storage/{main_img}
```

## Store Validation

Main method:

```php
BlogController::store()
```

Required:

```text
date
label
title
short_description
description
cover_img
main_img
slug
```

Image rules:

```text
cover_img: image, jpeg/png/jpg/gif/svg, max 2048 KB, exactly 416x227
main_img: image, jpeg/png/jpg/gif/svg, max 2048 KB, exactly 996x600
```

Upload paths:

```text
blog/cover_img
blog/main_img
```

## Update Logic

Main method:

```php
BlogController::update()
```

On update, images become optional:

```text
cover_img nullable
main_img nullable
```

If a new image is uploaded:

- Old image is deleted from public disk if it exists.
- New image is stored.

## Show Logic

Main method:

```php
BlogController::show()
```

Returns the admin blog show view.

## Status Toggles

Main methods:

```php
BlogController::changeStatus()
BlogController::changeHomeFeaturedStatus()
```

`status` controls whether the blog is active.

`home_featured` controls whether the blog is featured on the home/front-facing area.

## JSON List

Main method:

```php
BlogController::list()
```

Returns all blogs as JSON.

## Developer Notes

- `changeStatus()` and `changeHomeFeaturedStatus()` build response messages using `$blog->name`, but Blog model uses `title`, not `name`.
- Slug is required but not automatically generated in the controller.
- Image dimensions are strict. Uploads will fail if dimensions do not exactly match.
- Destroy method is empty; deletion is not implemented.

