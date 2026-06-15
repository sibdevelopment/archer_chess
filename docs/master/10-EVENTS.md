# Events Module

## Purpose

Events manages website event content such as event title, image, link, date, mode, description, location, brochure, and status.

## Sidebar Path

```text
Master > Events
```

## Main Routes

```php
Route::resource('events', EventController::class);
Route::post('events/data', [EventController::class, 'data'])->name('events.data');
Route::post('events/list', [EventController::class, 'list'])->name('events.list');
Route::post('events/change-status', [EventController::class, 'changeStatus'])->name('events.change.status');
```

Frontend design route:

```php
Route::get('/design/event', [HomeController::class, 'newEvent'])->name('design.event');
```

## Main Files

```text
app/Http/Controllers/Admin/EventController.php
app/Models/Event.php
resources/views/Admin/Events/index.blade.php
resources/views/Admin/Events/form.blade.php
resources/views/Admin/Events/show.blade.php
database/migrations/2025_07_10_111440_create_events_table.php
```

## Table

```text
events
```

Fields used:

```text
title
image
link
status
index
date
mode
short_description
location
brochure
created_by
updated_by
```

## Model

Fillable:

```text
title
status
index
link
image
date
mode
short_description
location
```

Developer note:

```text
brochure is assigned directly in the controller, but it is not in the model fillable list.
```

## Listing Behavior

Main method:

```php
EventController::data()
```

Columns include:

```text
Status
Image
Date
Link
Action
```

Link behavior:

- If link exists and does not start with `http://` or `https://`, the controller prepends `https://`.
- Link opens in a new tab.

## Store Logic

Main method:

```php
EventController::store()
```

Validation:

```text
title required and unique
link required and URL
image required and image max 5 MB
date required
mode required
short_description required
location required
brochure required
```

Index:

```text
new index = max(events.index) + 1
```

Storage:

```text
image -> images
brochure -> brochures
```

## Show Logic

Main method:

```php
EventController::show()
```

Returns event detail modal/view.

## Update Logic

Main method:

```php
EventController::update()
```

Update changes:

- Title unique rule ignores current event ID.
- Image becomes optional.
- New image replaces image path, but old image is not deleted.
- New brochure replaces brochure path, but old brochure is not deleted.

## Status Change

Main method:

```php
EventController::changeStatus()
```

Updates:

```text
events.status
```

## Developer Notes

- Store validation uses `unique:galleries,title` for event title. This looks incorrect; it should likely be `unique:events,title`.
- Migration shown initially only has title/image/link/status/index fields. Controller expects date/mode/short_description/location/brochure, so confirm later migrations or current DB schema before fresh deployment.
- Brochure is required in validation but not included in Event model fillable. Direct assignment works in controller only when using explicit property assignment.
- Destroy is empty; deletion is not implemented.

