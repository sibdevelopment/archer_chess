# Gallery Module

## Purpose

Gallery manages website gallery groups and their images.

There are two gallery storage patterns in the code:

1. Gallery records with five image arrays: `images_1` to `images_5`.
2. Nested gallery image records in `gallery_images`.

## Sidebar Path

```text
Master > Gallery
```

## Main Routes

Gallery:

```php
Route::resource('galleries', GalleryController::class);
Route::post('galleries/data', [GalleryController::class, 'data'])->name('galleries.data');
Route::post('galleries/list', [GalleryController::class, 'list'])->name('galleries.list');
Route::post('galleries/change-status', [GalleryController::class, 'changeStatus'])->name('galleries.change.status');
```

Nested Gallery Images:

```php
Route::prefix('galleries/{gallery}')->name('galleries.')->group(function () {
    Route::resource('gallery_images', GalleryImagesController::class);
    Route::post('gallery_images/data', [GalleryImagesController::class, 'data'])->name('galleryImages.data');
    Route::post('gallery_images/list', [GalleryImagesController::class, 'list'])->name('galleryImages.list');
    Route::post('gallery_images/change-status', [GalleryImagesController::class, 'changeStatus'])->name('galleryImages.change.status');
});
```

## Main Files

```text
app/Http/Controllers/Admin/GalleryController.php
app/Http/Controllers/Admin/GalleryImagesController.php
app/Models/Gallery.php
app/Models/GalleryImage.php
resources/views/Admin/Galleries/index.blade.php
resources/views/Admin/Galleries/form.blade.php
resources/views/Admin/Galleries/GalleryImages/index.blade.php
resources/views/Admin/Galleries/GalleryImages/form.blade.php
database/migrations/2025_07_08_184227_create_galleries_table.php
database/migrations/2025_07_08_184640_create_gallery_images_table.php
```

## Tables

```text
galleries
gallery_images
```

Gallery fields:

```text
title
status
index
images_1
images_2
images_3
images_4
images_5
created_by
updated_by
```

Gallery image fields:

```text
gallery_id
image
status
index
created_by
updated_by
```

## Gallery Model

Fillable:

```text
title
status
index
```

Casts:

```text
images_1 array
images_2 array
images_3 array
images_4 array
images_5 array
```

Relationship:

```php
galleryImages()
```

## Gallery Listing

Main method:

```php
GalleryController::data()
```

Columns:

```text
Preview Image
Status
Action
```

Preview image logic:

```text
Find first non-empty image array from images_1 to images_5
Show first image
If no image exists, show "No Image"
```

## Gallery Store

Main method:

```php
GalleryController::store()
```

Validation:

```text
title required and unique
images_1 to images_5 optional arrays
image files allowed: jpeg, png, jpg, webp, svg, gif
```

Index:

```text
new index = max(galleries.index) + 1
```

Images are stored under:

```text
gallery
```

Each image field stores an array of uploaded paths.

## Gallery Update

Main method:

```php
GalleryController::update()
```

Validation:

```text
title required and unique except current gallery
images_1 to images_5 optional file arrays
```

Update supports:

- Deleting selected existing images
- Uploading new images
- Appending new uploads to existing image arrays
- Updating title

## Gallery Status Change

Main method:

```php
GalleryController::changeStatus()
```

Updates:

```text
galleries.status
```

## Nested Gallery Images

Main controller:

```php
GalleryImagesController
```

This controller manages image rows inside `gallery_images`.

Listing filters by:

```text
gallery_id
```

Store validation:

```text
image required
image max 5 MB
```

Store path:

```text
images
```

Index:

```text
new index = max index inside selected gallery + 1
```

Status toggle updates:

```text
gallery_images.status
```

## Developer Notes

- `galleries` migration shown does not include `images_1` to `images_5`, so those columns are likely added by later migrations or the local schema has drifted. Confirm DB schema before deploying.
- Gallery update deletes files using `Storage::exists()` without specifying the public disk, while uploads use public disk. This may fail to delete public-disk files depending on filesystem config.
- GalleryImages update does not delete the old image when replacing it.
- There are two gallery image systems, so clarify which one the frontend actually uses before refactoring.

