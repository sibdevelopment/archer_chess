# Lead Enquiries Module

## Purpose

Lead Enquiries stores trial-class enquiries before they become Demo Leads.

This is the first main step in the student acquisition pipeline.

## Sidebar Path

```text
Operations > Lead Enquiries
```

## Main Routes

```php
Route::resource('leadenquiries', LeadEnquiryController::class);
Route::post('leadenquiries/data', [LeadEnquiryController::class, 'data'])->name('leadenquiries.data');
Route::post('leadenquiries/list', [LeadEnquiryController::class, 'list'])->name('leadenquiries.list');
Route::post('leadenquiries/change-status', [LeadEnquiryController::class, 'changeStatus'])->name('leadenquiries.change.status');
Route::get('leadenquiries/convert/{leadenquiry}', [LeadEnquiryController::class, 'convertToDemoLead'])->name('leadenquiries.convert');
Route::post('leadenquiries/reject/{leadenquiry}', [LeadEnquiryController::class, 'rejectTheDemoLead'])->name('leadenquiries.reject');
Route::get('/admin/leadenquiries/{id}/convert-form', [LeadEnquiryController::class,'convertForm'])->name('leadenquiries.convert.form');
Route::post('/admin/leadenquiries/{id}/convert-store', [LeadEnquiryController::class,'convertStore'])->name('leadenquiries.convert.store');
```

## Main Files

```text
app/Http/Controllers/Frontend/HomeController.php
app/Http/Controllers/Admin/LeadEnquiryController.php
app/Models/DemoLeadEnquiry.php
resources/views/Admin/LeadEnquiries/*
```

## Database Table

```text
demoleadenquiries
```

## Important Fields

```text
user_id
status
lead_status
kids_first_name
kids_last_name
parent_name
age
dob
mobile
email
city
country
timezone
date
time
ist_date
ist_time
duration
available_device
enrollment_plan
language_preference
level
utm_source
utm_medium
email_verified
mobile_verified
is_hide
```

## Lead Creation Flow

Trial booking forms create Lead Enquiry records.

Main frontend method:

```php
HomeController::storeBookATrailForm()
```

The form validates lead details and creates:

```text
DemoLeadEnquiry
User
Student role on User
```

The created user gets a device/password pattern:

```text
archer@{user_id}
```

## Lead Statuses

```text
ACTIVE
CONVERTED
REJECTED
```

## Convert Lead To Demo Lead

Main methods:

```php
LeadEnquiryController::convertForm()
LeadEnquiryController::convertStore()
LeadEnquiryController::convertToDemoLead()
```

Conversion creates:

```text
users record or updates existing user
demoleads record
```

Conversion updates:

```text
demoleadenquiries.status = CONVERTED
demoleadenquiries.user_id = users.id
demoleads.status = ROWLEAD
```

## Reject Lead

Main method:

```php
LeadEnquiryController::rejectTheDemoLead()
```

Result:

```text
demoleadenquiries.status = REJECTED
```

## Delete / Hide Lead

Lead delete is a soft-style hide:

```text
is_hide = 1
```

## Client Workflow

1. Go to Operations > Lead Enquiries.
2. Check new trial enquiries.
3. Verify child details, parent/contact details, country and timezone.
4. Convert valid lead to Demo Lead.
5. Reject invalid/unqualified lead with remark.
6. Use country/status/date filters for follow-up.

## Developer Notes

Lead Enquiry conversion is the first place where `DemoLead` is created.

If this flow breaks, check:

```text
LeadEnquiryController::convertStore()
DemoLeadEnquiry model
DemoLead model
User role assignment
```

