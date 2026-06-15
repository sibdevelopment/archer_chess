# Website Enquiries Module

## Purpose

Website Enquiries stores general enquiries submitted from website/contact forms.

This module is informational and separate from the trial-class lead pipeline. A Website Enquiry does not automatically become a Demo Lead or Student in the current admin flow.

## Sidebar Path

```text
Operations > Website Enquiries
```

## Main Routes

```php
Route::resource('enquiries', EnquiryController::class);
Route::post('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');
Route::post('enquiries/list', [EnquiryController::class, 'list'])->name('enquiries.list');
```

## Main Files

```text
app/Http/Controllers/Admin/EnquiryController.php
app/Http/Controllers/Frontend/HomeController.php
app/Models/Enquiry.php
resources/views/Admin/Enquiries/*
```

## Database Table

```text
enquiries
```

## Important Fields

```text
first_name
last_name
email
mobile
country
message
created_at
updated_at
```

## Admin Features

- View enquiry list
- Filter/search enquiries
- View enquiry detail
- Delete enquiry

## Data Flow

```text
Website/contact form
        |
        v
enquiries table
        |
        v
Admin > Website Enquiries
```

## Controller Behavior

### Listing

Main method:

```php
EnquiryController::data()
```

The method loads enquiry records and returns them through DataTables.

It supports date filtering:

```text
created_at between selected date range
```

### View Details

Main method:

```php
EnquiryController::show()
```

This opens the enquiry detail modal/view.

### Delete

Main method:

```php
EnquiryController::destroy()
```

This deletes the enquiry record.

## Client Workflow

1. Go to Operations > Website Enquiries.
2. Review submitted enquiries.
3. Open details if required.
4. Contact the person manually if needed.
5. Delete irrelevant enquiries if required.

## Developer Notes

This module is not connected to:

```text
demoleadenquiries
demoleads
students
student_batches
```

If the client wants Website Enquiries to become Lead Enquiries or Demo Leads, a new conversion flow should be implemented.

