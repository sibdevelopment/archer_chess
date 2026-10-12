# Committed Changes: September to October 2026

## Scope

This reconciles all 46 commits after documentation checkpoint `4337d69` through `8c59cf1`. It describes final behavior after follow-up fixes. Uncommitted regular-batch Zoom work is documented separately in `developer/REGULAR-BATCH-ZOOM-OCCURRENCES.md`.

## Roles And Employee Leave

- Roles support active/inactive status and filtering; system roles are protected.
- Roles assigned to active users cannot be deactivated until assignments are rotated.
- Role APIs return active non-system roles, and country controls support Select All.
- Employee Leave is permission-controlled and stored in `employee_leave_requests`.
- Employees manage their own pending requests; authorized reviewers approve/reject all requests.
- Coaches are denied access. Rejection requires a reason, and decisions store actor/time.
- Same-day leave must end after it starts and cannot use 12:00 AM as end-of-day.
- Only one permission-gated Employee Leave sidebar entry is shown.

## Countries, Pricing, And Timezones

- Country definitions are centralized in `config/countries.php`.
- Malaysia and Hong Kong are supported across admin records, filters, frontend forms, and pricing.
- Payment levels include `malaysia_fees` and `hong_kong_fees`.
- Country-specific timezone labels replace generic labels in lead/booking flows.
- Meet Our Kids/Tutors search wiring was corrected.

## Frontend Tracking And Consent

- Frontend layouts and landing pages use GTM container `GTM-KCPKNMQ`.
- Booking forms retain `utm_source` and `utm_medium` in session storage and save them on lead enquiries.
- Cookie/privacy pages and necessary/analytics/marketing preferences were added.
- Choices persist in local storage and a one-year cookie; optional storage defaults to denied where consent is required.
- UK and European Union landing pages wait for consent before GTM.
- Other country landing pages load GTM immediately without the banner.
- The main website keeps cookie consent for every region.

## Coach Leave And Availability

- `BatchOccurrenceService` centralizes approved leave effects and occurrence decisions.
- Only approved leave affects cover-ups, attendance, compensation, and penalties.
- Removing approval cleans stale artifacts; backdated approval waives penalties without historical cover-ups/date shifts.
- `CoachAvailabilityService` is reused by demos, cover-ups, leave replacement, masterclasses, and batch reassignment.
- Availability charts show occupied periods and gaps.
- Past conflicts and blank period rows no longer break future availability decisions.
- Same-day schedules cannot use 12:00 AM as an end-of-day value.

## Sequential Payment Levels And Fees

- `PaymentLevelService` calculates the next unpaid level from fee history with a legacy batch fallback.
- Students may pay the next one or next three levels; three levels receive a 10 percent discount.
- Pricing/currency uses the normalized country fee column.
- `student_fees.payment_level_id` and `new_enrollments.payment_level_id` preserve progression.
- Manual/generated fees, payments, enrollment, change class, and reminders share this logic.
- Advance fees can be created five days early.
- Fee-due processing activates a valid advance fee instead of marking the student due.
- Cancellation compensation shifts the covering fee and cascades later advance rows.
- Inactive/standby student status can store `status_reason`.

## Coach, Batch, Dashboard, And Reports

- Coach status changes use the student-style status/reason interaction.
- Inactive coach filtering is in Coach Reports.
- Batch coach filters retain assigned coaches when availability filtering would omit them.
- Missed-session status actions retain WhatsApp follow-up.
- Midnight demo/batch timestamps are normalized to prevent false penalties.
- Configured late-penalty handling uses the corrected five-minute threshold.

## Fee-Due WhatsApp Tracking

- Authorized non-coach admins can open a fee-due WhatsApp message from Students.
- Message amount/currency/payment level comes from `PaymentLevelService`.
- `fee_due_whatsapp_notifications` stores student, fee, sender, amount, currency, message, and time.
- One record per student/fee cycle prevents duplicate tracking.
- A sent action becomes a greyed but clickable history icon showing sender and timestamp.

## Student Homework Safety

- Homework links are validated before rendering.
- Missing/invalid URLs show `Homework link unavailable`.
- Valid links come from coach-submitted attendance data.

## Repository Hygiene

- `ZOOM-WATI-IMPLEMENTATION-ESTIMATE.md` is ignored as a local estimate artifact.

## Commit Coverage

The generated table below accounts for every commit in the audited range.

| Date | Commit | Change |
| --- | --- | --- |
| 2026-09-01 | `23c1fdf` | Add role status controls and country select all |
| 2026-09-01 | `09d620c` | Add employee leave requests |
| 2026-09-01 | `ecd4d17` | Fix employee leave sidebar icon |
| 2026-09-01 | `860c1f9` | Filter roles by status |
| 2026-09-02 | `30dd085` | Fix meet our section search |
| 2026-09-02 | `1ddb324` | Add Malaysia and Hong Kong support |
| 2026-09-02 | `c146af8` | Use country-specific timezone labels |
| 2026-09-08 | `dac35f6` | Replace landing tracking with new GTM |
| 2026-09-08 | `783057c` | Capture UTM values in booking forms |
| 2026-09-08 | `391da67` | Refine leave access and demo scheduling rules |
| 2026-09-08 | `a78d80f` | Show employee leave tab for super admin |
| 2026-09-08 | `3cd2245` | Fix employee leave role permissions |
| 2026-09-09 | `d2edc6e` | Normalize progressive coach leave handling |
| 2026-09-09 | `04d6af2` | Centralize coach availability checks |
| 2026-09-09 | `efcb91c` | Show availability gaps in coach chart |
| 2026-09-09 | `9400fbc` | Refine coach availability and batch reassignment controls |
| 2026-09-09 | `230dde0` | Fix coach availability filter script data |
| 2026-09-10 | `6f76272` | Ignore past dates in batch coach availability |
| 2026-09-11 | `5580563` | Implement sequential student payment levels |
| 2026-09-11 | `4d6df5c` | Align fee due messages with payment levels |
| 2026-09-15 | `2242133` | Require payment levels for generated fee rows |
| 2026-09-16 | `f952993` | Refine fee and coach workflow handling |
| 2026-09-16 | `8d2e9dd` | Align coach status change UI with students |
| 2026-09-16 | `6eb78b3` | Move inactive coach filter to coach reports |
| 2026-09-17 | `12ac6bb` | Align enrollment payment levels and coach filters |
| 2026-09-17 | `84ee4ff` | Fix batch coach filter and missed session actions |
| 2026-09-17 | `1a109a0` | Include assigned batches in coach filter |
| 2026-09-17 | `4f82adc` | Restore missed session status WhatsApp action |
| 2026-09-24 | `fb4406f` | Centralize countries and employee leave access |
| 2026-09-25 | `f1feafb` | Hide employee leave tab for coaches |
| 2026-09-26 | `3907e6f` | Add frontend cookie consent controls |
| 2026-09-28 | `9fc7950` | Fix midnight penalties and fee due WhatsApp tracking |
| 2026-09-28 | `a10f2d1` | Fix fee due WhatsApp notification URL |
| 2026-09-28 | `f7fe420` | Fix fee due WhatsApp role guard |
| 2026-09-28 | `1e6d838` | Allow admins to send fee due WhatsApp |
| 2026-09-28 | `c13234b` | Fix fee due WhatsApp permission check |
| 2026-09-28 | `f2de7d2` | Show fee due WhatsApp sender |
| 2026-09-28 | `eafc073` | Show fee due WhatsApp details modal |
| 2026-09-28 | `b7a5fa4` | Grey sent fee due WhatsApp icon |
| 2026-09-28 | `bed5799` | Improve sent WhatsApp icon contrast |
| 2026-09-29 | `c9986f4` | Guard student homework links |
| 2026-10-05 | `3533e4a` | Remove duplicate employee leave sidebar link |
| 2026-10-05 | `b7c601e` | Fix compensation for advance fee rows |
| 2026-10-05 | `b2b8722` | Handle blank coach availability periods |
| 2026-10-07 | `9b4daa0` | Scope landing page consent to UK and EU |
| 2026-10-10 | `8c59cf1` | Ignore Zoom WATI estimate document |
