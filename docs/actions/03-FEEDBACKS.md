# Feedbacks Module

## Purpose

Feedbacks stores and displays feedback submitted from the student dashboard.

It is mainly a read/review module for admin users. Students submit feedback, and admin users open the Feedbacks screen to review it.

## Sidebar Path

```text
Actions > Feedbacks
```

## Main Routes

```php
Route::resource('feedbacks', FeedbackController::class);
Route::post('feedbacks/data', [FeedbackController::class, 'data'])->name('feedbacks.data');
```

Important route usage:

```text
GET /admin/feedbacks
POST /admin/feedbacks/data
POST /admin/feedbacks
GET /admin/feedbacks/{feedback}
PUT/PATCH /admin/feedbacks/{feedback}
```

## Main Files

```text
app/Http/Controllers/Admin/FeedbackController.php
app/Models/Feedback.php
resources/views/Admin/Feedback/index.blade.php
resources/views/Admin/Feedback/show.blade.php
resources/views/Admin/StudentDashboard/dashboard.blade.php
```

## Database Table

The model expects a feedback table with these important fields:

```text
user_id
student_id
coach_id
full_name
feedback
status
```

Model:

```text
app/Models/Feedback.php
```

Fillable fields:

```php
protected $fillable = ['user_id','student_id','coach_id','full_name','feedback','status'];
```

## Relationships

The Feedback model has these relationships:

```php
user()
student()
coach()
```

Meaning:

- `user_id` connects feedback to the logged-in user.
- `student_id` can connect feedback to a student record.
- `coach_id` connects feedback to a coach.

Coach name shown in the listing comes from:

```text
feedback -> coach -> user -> full_name
```

## Feedback Listing

Main method:

```php
FeedbackController::index()
```

View:

```text
resources/views/Admin/Feedback/index.blade.php
```

The listing uses server-side DataTables.

Columns:

```text
#
Actions
Name
Coach
Feedback
```

Export buttons:

```text
Copy
CSV
Excel
PDF
Print
```

The table loads data from:

```php
FeedbackController::data()
```

## Data Loading Logic

Main method:

```php
FeedbackController::data()
```

Query:

```text
Feedback records ordered by id descending
```

Displayed fields:

```text
full_name
coach
feedback
action
```

Feedback message is trimmed:

```text
first 97 characters plus ...
```

The action column contains an eye icon that opens the feedback details in a modal.

## Show Feedback

Main method:

```php
FeedbackController::show()
```

View:

```text
resources/views/Admin/Feedback/show.blade.php
```

This view is opened from the listing action button.

## Student Dashboard Feedback Submission

Form location:

```text
resources/views/Admin/StudentDashboard/dashboard.blade.php
```

The form posts to:

```php
route('admin.feedbacks.store')
```

This means feedback submission reuses the admin feedback resource route even when submitted from the student dashboard area.

## Store Feedback

Main method:

```php
FeedbackController::store()
```

Validation:

```text
full_name required
coach_id required
feedback required
```

Flow:

1. Validate request.
2. Read logged-in user with `Auth::user()`.
3. Create new `Feedback`.
4. Fill request data.
5. Save feedback.
6. Return JSON success response.

Success response:

```json
{
  "status": "success",
  "message": "Feedback Created Successfully"
}
```

## Update Feedback

Main method:

```php
FeedbackController::update()
```

Validation is the same as store:

```text
full_name required
coach_id required
feedback required
```

Flow:

1. Validate request.
2. Read logged-in user.
3. Fill existing feedback record.
4. Save feedback.
5. Return JSON success response.

Success response:

```json
{
  "status": "success",
  "message": "Feedback Update Successfully"
}
```

## Create, Edit, Destroy

These resource methods exist but are empty:

```php
create()
edit()
destroy()
```

Current behavior:

- There is no dedicated create screen in the Feedbacks admin module.
- There is no dedicated edit screen in the Feedbacks admin module.
- There is no delete behavior implemented in the controller.

## Important Validation Messages

Custom messages:

```text
full_name.required = The name is required.
coach_id.required = The coach is required.
feedback.required = The feedback field is required.
```

## Permission Notes

Feedback permissions are seeded under the Feedback group in:

```text
database/seeders/PermissionSeeder.php
```

The sidebar shows the module through the admin navbar when feedback access is available.

## Developer Notes

### user_id Assignment Bug

In both store and update, the controller has:

```php
$feedback->user_id == $user->id;
```

This is a comparison, not an assignment.

Expected assignment would be:

```php
$feedback->user_id = $user->id;
```

Current impact:

```text
The logged-in user id may not be saved into feedbacks.user_id.
```

### Mobile Field Mismatch

`FeedbackController::data()` contains a `mobile` column formatter:

```php
return isset($feedback->mobile) ? $feedback->mobile : 'N/A';
```

But the visible table does not include a mobile column, and the Feedback model fillable list does not include `mobile`.

Current impact:

```text
Mobile is not part of the visible Feedbacks listing table.
If mobile is required later, confirm the database column and model fillable field first.
```

### Feedback Is Mostly Read-Only In Admin

Admin can list and view feedback. The controller has update support, but the current UI does not expose a normal edit page.

## End-To-End Flow

```text
Student opens dashboard
Student submits feedback form
POST admin.feedbacks.store
FeedbackController validates and saves feedback
Admin opens Actions > Feedbacks
DataTables loads admin.feedbacks.data
Admin clicks eye icon
Feedback detail opens in modal
```

