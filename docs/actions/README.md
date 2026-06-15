# Actions Module Documentation Index

This folder contains dedicated documentation for the Actions sidebar module.

The Actions module is different from Operations. Operations creates and manages business records such as leads, students, batches, and cover-up classes. Actions is where the team reviews activity, handles coach leave, records attendance from reports, and reads student feedback.

## Sidebar Modules

```text
Actions > Reports
Actions > Leave Requests
Actions > Feedbacks
```

## Documents

```text
01-REPORTS.md
02-LEAVE-REQUESTS.md
03-FEEDBACKS.md
```

## Main Code Area

```text
routes/backend.php
app/Http/Controllers/Admin/ReportController.php
app/Http/Controllers/Admin/LeaveRequestController.php
app/Http/Controllers/Admin/FeedbackController.php
resources/views/Admin/CoachReports/*
resources/views/Admin/LeaveRequests/*
resources/views/Admin/Feedback/*
```

## Suggested Reading Order

1. Read `01-REPORTS.md` to understand coach performance, schedules, calendars, and attendance updates.
2. Read `02-LEAVE-REQUESTS.md` to understand how coach leave affects batches and cover-up assignment.
3. Read `03-FEEDBACKS.md` to understand how student feedback is submitted and viewed.

