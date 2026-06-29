<?php


use App\Models\Batch;
use App\Models\Order;
use Razorpay\Api\Api;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudentBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\DataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CrudController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\HdfcPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DemoLeadController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\TimezoneController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MeetOurKidController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\Admin\ChangeclassController;
use App\Http\Controllers\Admin\LeadEnquiryController;
use App\Http\Controllers\Admin\MasterclassController;
use App\Http\Controllers\Admin\BatchStudentController;
use App\Http\Controllers\Admin\CoverupclassController;
use App\Http\Controllers\Admin\DemoSessionsController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\MeetOurTutorController;
use App\Http\Controllers\Admin\PaymentLevelController;
use App\Http\Controllers\Admin\BatchScheduleController;
use App\Http\Controllers\Admin\GalleryImagesController;
use App\Http\Controllers\Admin\NewEnrollmentController;
use App\Http\Controllers\Admin\StudentDashboardController;
use App\Http\Controllers\Admin\CoachAvailabilityController;
use Illuminate\Support\Facades\Log;

// Admin Routes
Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard ------------------------------
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('dashboard/1', [DashboardController::class, 'index1'])->name('dashboard.index1');
        Route::post('dashboard/get/students', [DashboardController::class, 'studentData'])->name('dashboard.get.students');
        Route::post('dashboard/get/students/missed/sessions', [DashboardController::class, 'missedSessionsData'])->name('dashboard.get.students.missed.sessions');
        Route::post('dashboard/get/batches', [DashboardController::class, 'batchData'])->name('dashboard.get.batches');
      
        // Coach Availability ------------------------------
        Route::get('availability/index', [DashboardController::class, 'availabilityIndex'])->name('availability.index');
        Route::get('availability', [DashboardController::class, 'availability'])->name('availability');
        Route::get('coach/get-unmarked-attendance', [DashboardController::class, 'getUnmarkedAttendance'])->name('coach.get.unmarked.attendance');

        // Roles ------------------------------
        Route::resource('roles', RoleController::class);
        Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::post('roles/list', [RoleController::class, 'list'])->name('roles.list');

        // Permissions ------------------------------
        Route::get('roles/{role}/permission/show', [RoleController::class, 'permissionsShow'])->name('roles.permissions.show');
        Route::post('roles/{role}/permission/update', [RoleController::class, 'permissionsUpdate'])->name('roles.permissions.update');

        // Employees ------------------------------
        Route::resource('employees', EmployeeController::class);
        Route::post('employees/data', [EmployeeController::class, 'data'])->name('employees.data');
        Route::post('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
        Route::post('employees/change-status', [EmployeeController::class, 'changeStatus'])->name('employees.change.status');

        // Coaches ------------------------------
        Route::resource('coaches', CoachController::class);
        Route::post('coaches/data', [CoachController::class, 'data'])->name('coaches.data');
        Route::post('coaches/list', [CoachController::class, 'list'])->name('coaches.list');
        Route::post('coaches/change-status', [CoachController::class, 'changeStatus'])->name('coaches.change.status');

        // Coach Availabilities ------------------------------
        Route::prefix('coaches/{coach}')->name('coaches.')->group(function () {
            Route::resource('coach_availabilities', CoachAvailabilityController::class);
            Route::post('coach_availabilities/data', [CoachAvailabilityController::class, 'data'])->name('coach_availabilities.data');
            Route::post('coach_availabilities/change-status', [CoachAvailabilityController::class, 'changeStatus'])->name('coach_availabilities.change.status');
            Route::get('edit_availabilities', [CoachAvailabilityController::class, 'editAll'])->name('coach_availabilities.editAll');
            Route::put('update_availabilities', [CoachAvailabilityController::class, 'updateAll'])->name('coach_availabilities.updateAll');
        });
        Route::post('coach_availabilities/list', [CoachAvailabilityController::class, 'list'])->name('coach_availabilities.list');

        // Coach Availabilities -> Add Day ------------------------------
        Route::post('coach_availabilities/add-day', [CoachAvailabilityController::class, 'addDay'])->name('coach_availabilities.add_day');
        Route::post('coach_availabilities/edit-day', [CoachAvailabilityController::class, 'editDay'])->name('coach_availabilities.edit_day');
        Route::post('coach_availabilities/delete-day', [CoachAvailabilityController::class, 'deleteDay'])->name('coach_availabilities.delete_day');

        // Coach Availabilities -> day -> Add Period ------------------------------
        Route::post('coach_availabilities/add-day-period', [CoachAvailabilityController::class, 'addPeriod'])->name('coach_availabilities.add_day_period');
        Route::post('coach_availabilities/edit-period', [CoachAvailabilityController::class, 'editPeriod'])->name('coach_availabilities.edit_period');
        Route::post('coach_availabilities/delete-period', [CoachAvailabilityController::class, 'deletePeriod'])->name('coach_availabilities.delete_period');

        //Enquiries ------------------------------
        Route::resource('enquiries', EnquiryController::class);
        Route::post('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');
        Route::post('enquiries/list', [EnquiryController::class, 'list'])->name('enquiries.list');

        // Lead Enquiry ------------------------------
        Route::resource('leadenquiries', LeadEnquiryController::class);
        Route::post('leadenquiries/data', [LeadEnquiryController::class, 'data'])->name('leadenquiries.data');
        Route::post('leadenquiries/list', [LeadEnquiryController::class, 'list'])->name('leadenquiries.list');
        Route::post('leadenquiries/change-status', [LeadEnquiryController::class, 'changeStatus'])->name('leadenquiries.change.status');
        Route::get('leadenquiries/convert/{leadenquiry}', [LeadEnquiryController::class, 'convertToDemoLead'])->name('leadenquiries.convert');
        Route::post('leadenquiries/reject/{leadenquiry}', [LeadEnquiryController::class, 'rejectTheDemoLead'])->name('leadenquiries.reject');

        // --- Lead Enquiries convert popup + save ---
        Route::get('/admin/leadenquiries/{id}/convert-form', [App\Http\Controllers\Admin\LeadEnquiryController::class,'convertForm'])
            ->name('leadenquiries.convert.form');

        Route::post('/admin/leadenquiries/{id}/convert-store', [App\Http\Controllers\Admin\LeadEnquiryController::class,'convertStore'])
            ->name('leadenquiries.convert.store');


        // Demo Leads ------------------------------
        Route::resource('demoleads', DemoLeadController::class);
        Route::post('demoleads/data', [DemoLeadController::class, 'data'])->name('demoleads.data');
        Route::post('demoleads/export', [DemoLeadController::class, 'export'])->name('demoleads.export');
        Route::post('demoleads/list', [DemoLeadController::class, 'list'])->name('demoleads.list');
        Route::post('demoleads/change-status', [DemoLeadController::class, 'changeStatus'])->name('demoleads.change.status');
        Route::get('demoleads/{demolead}/convert', [DemoLeadController::class, 'convertToStudent'])->name('demoleads.convert');
        Route::post('demoleads/{demolead}/convert/save', [DemoLeadController::class, 'saveConvertedStudent'])->name('demoleads.convert.save');
        Route::post('demoleads/processDateTimeZone', [DemoLeadController::class, 'processDateTimeZone'])->name('demoleads.processDateTimeZone');
        Route::post('demoleads/check-mobile', [DemoLeadController::class, 'checkMobileUniqueness'])->name('demoleads.check.mobile');
        Route::get('demoleads/get/timezones', [DemoLeadController::class, 'getTimezones'])->name('demoleads.get.timezones');
        Route::post('demoleads/getRemark', [DemoLeadController::class, 'getRemark'])->name('demoleads.getRemark');
        Route::post('demoleads/getReason', [DemoLeadController::class, 'getReason'])->name('demoleads.getReason');

        Route::prefix('demoleads/{demolead}')->name('demoleads.')->group(function () {
            Route::resource('demo_sessions', DemoSessionsController::class);
            Route::post('demo_sessions/data', [DemoSessionsController::class, 'data'])->name('demo_sessions.data');
            Route::post('demo_sessions/change-status', [DemoSessionsController::class, 'changeStatus'])->name('demo_sessions.change.status');
        });
        Route::get('demo_sessions/coach_availability', [DemoSessionsController::class, 'getCoachAvailability'])->name('demo_sessions.coach_availability');
        Route::get('demo_sessions/available_slots', [DemoSessionsController::class, 'getAvailableSlots'])->name('demo_sessions.available_slots');

        // New Enrollments ------------------------------
        Route::resource('newenrollments', NewEnrollmentController::class);
        Route::post('newenrollments/data', [NewEnrollmentController::class, 'data'])->name('newenrollments.data');
        Route::post('newenrollments/list', [NewEnrollmentController::class, 'list'])->name('newenrollments.list');
        Route::get('newenrollments/excel/export', [NewEnrollmentController::class, 'export'])->name('newenrollments.export');

        // Students ------------------------------
        Route::resource('students', StudentController::class);
        Route::post('students/data', [StudentController::class, 'data'])->name('students.data');
        Route::post('students/export', [StudentController::class, 'export'])->name('students.export');
        Route::post('students/list', [StudentController::class, 'list'])->name('students.list');
        Route::post('students/masterclass_tounament/list', [StudentController::class, 'masterclassTounamentlist'])->name('students.masterclassTounamentlist');
        Route::post('students/change-status', [StudentController::class, 'changeStatus'])->name('students.change.status');
        Route::get('students/get/coaches', [StudentController::class, 'getCoaches'])->name('students.get.coaches');
        Route::get('students/get/batches', [StudentController::class, 'getBatches'])->name('students.get.batches');
        Route::post('students/delete/attendance', [StudentController::class, 'deleteStudentAtendance'])->name('students.delete.attendance');
        Route::post('students/batches/delete/attendance', [StudentController::class, 'deleteStudentBatchAtendance'])->name('students.delete.batch.attendance');

        // Students Fee ------------------------------
        Route::prefix('students/{student}')->name('students.')->group(function () {
            Route::resource('student_fees', StudentFeeController::class);
            Route::post('student_fees/data', [StudentFeeController::class, 'data'])->name('student_fees.data');
            Route::post('student_fees/change-status', [StudentFeeController::class, 'changeStatus'])->name('student_fees.change.status');
            Route::get('student_fees/{student_fee}/invoice', [StudentFeeController::class, 'downloadInvoice'])->name('student_fees.invoice');
        });
        Route::post('student_fees/list', [StudentFeeController::class, 'list'])->name('student_fees.list');

        // Batches ------------------------------
        Route::resource('batchs', BatchController::class);
        Route::post('batchs/data', [BatchController::class, 'data'])->name('batchs.data');
        Route::post('batchs/list', [BatchController::class, 'list'])->name('batchs.list');
        Route::post('batchs/masterclass_tounament/list', [BatchController::class, 'masterclassTounamentlist'])->name('batchs.masterclassTounamentlist');
        Route::post('batchs/change-status', [BatchController::class, 'changeStatus'])->name('batchs.change.status');
        Route::post('batchs/available-coaches', [BatchController::class, 'checkSchedule'])->name('batchs.available.coaches');
        Route::get('batch/get/coaches', [BatchController::class, 'getCoaches'])->name('batch.get.coaches');
        Route::get('batch/get/students', [BatchController::class, 'getStudents'])->name('batch.get.students');
        Route::post('batches/check-name', [BatchController::class, 'checkName'])->name('batchs.check.name');
        Route::post('batches/attendance', [BatchController::class, 'batchAttendance'])->name('batchs.attendance');
        Route::get('/batches/get-coaches', [BatchController::class, 'changeCoaches'])->name('get.coaches');
        Route::post('batches/change/coach', [BatchController::class, 'changeCoach'])->name('batchs.change.coach');

        // Assign Batch Students ------------------------------
        Route::get('batchs/{batch}/assign/student', [BatchController::class, 'assignBatchToStudent'])->name('batchs.assign.student');
        Route::post('batchs/{batch}/assign/student/save', [BatchController::class, 'saveAssignedStudent'])->name('batchs.assigned.student.save');
        Route::get('batchs/{batch}/reassign/student', [BatchController::class, 'reassignBatchToStudentModal'])->name('batchs.reassign.student');
        Route::post('batchs/{batch}/reassign/student/save', [BatchController::class, 'saveReassignedStudent'])->name('batchs.reassigned.student.save');
        Route::post('batchs/check/schedule', [BatchController::class, 'checkSchedule'])->name('batchs.check.schedule');

        // Batches Schedule -> Add Weekday ------------------------------
        Route::post('batch_schedules/add-weekday', [BatchController::class, 'addWeekday'])->name('batch_schedules.add_weekday');
        Route::post('batch_schedules/edit-weekday', [BatchController::class, 'editWeekDay'])->name('batch_schedules.edit_weekday');
        Route::post('batch_schedules/delete-weekday', [BatchController::class, 'deleteWeekDay'])->name('batch_schedules.delete_weekday');

        // Batches Schedule ------------------------------
        Route::prefix('batchs/{batch}')->name('batchs.')->group(function () {
            Route::resource('batch_schedules', BatchScheduleController::class);
            Route::post('batch_schedules/data', [BatchScheduleController::class, 'data'])->name('batch_schedules.data');
            Route::post('batch_schedules/change-status', [BatchScheduleController::class, 'changeStatus'])->name('batch_schedules.change.status');
        });
        Route::post('batch_schedules/list', [BatchScheduleController::class, 'list'])->name('batch_schedules.list');

        // Change Class ------------------------------     
        Route::resource('changeclasses', ChangeclassController::class);
        Route::post('changeclasses/data', [ChangeclassController::class, 'data'])->name('changeclasses.data');
        Route::post('changeclasses/list', [ChangeclassController::class, 'list'])->name('changeclasses.list');
        Route::get('changeclasses/excel/export', [ChangeclassController::class, 'export'])->name('changeclasses.export');

        // Coverup Class ------------------------------
        Route::resource('coverupclasses', CoverupclassController::class);
        Route::post('coverupclasses/data', [CoverupclassController::class, 'data'])->name('coverupclasses.data');
        Route::post('coverupclasses/list', [CoverupclassController::class, 'list'])->name('coverupclasses.list');
        Route::post('coverupclasses/get/coach', [CoverupclassController::class, 'getCoach'])->name('coverupclasses.change_coach.get.coach');
        Route::post('coverupclasses/change/coach', [CoverupclassController::class, 'changeCoach'])->name('coverupclasses.change_coach');

        // Report -------------------------------
        Route::resource('reports', ReportController::class);
        Route::post('reports/data', [ReportController::class, 'data'])->name('reports.data');
        Route::post('reports/list', [ReportController::class, 'list'])->name('reports.list');
        Route::post('reports/change-status', [ReportController::class, 'changeStatus'])->name('reports.change.status');
        Route::post('reports/{coachId}/download', [ReportController::class, 'downloadReport'])->name('reports.download');
        Route::get('reports/{coachId}/get-counts', [ReportController::class, 'getCounts'])->name('reports.get.counts');
        Route::get('reports/{coachId}/schedule', [ReportController::class, 'getSchedule'])->name('reports.getSchedule');
        Route::get('reports/{coachId}/calendar', [ReportController::class, 'getCalendarData'])->name('reports.calendar');
        Route::get('reports/{coachId}/get/availability', [ReportController::class, 'getAvailability'])->name('reports.getAvailability');
        Route::get('reports/schedule/batch/demo/data', [ReportController::class, 'reportsScheduleData'])->name('reports.scheduleData');
        Route::get('reports/{coachId}/attendance', [ReportController::class, 'getAttendanceData'])->name('reports.getAttendanceData');
        Route::post('reports/{coachId}/demo-attendance', [ReportController::class, 'demoAttendance'])->name('reports.demoAttendance');
        Route::post('reports/{coachId}/batch-attendance', [ReportController::class, 'batchAttendance'])->name('reports.batchAttendance');
        Route::get('reports/batchstudent/countrydata', [ReportController::class, 'batchStudentCountryData'])->name('batchstudent.countrydata');
        Route::get('reports/batch/completed/data', [ReportController::class, 'batchCompletedData'])->name('batch.completed.data');
        Route::get('reports/delayed-batches/data', [ReportController::class, 'delayedBatchesReportData'])->name('reports.delayed.batches.data');
        Route::get('reports/coverupclass/completed/data', [ReportController::class, 'coverupclassCompletedData'])->name('coverupclass.completed.data');
        Route::get('reports/demo/completed/data', [ReportController::class, 'demoCompletedData'])->name('demo.completed.data');
        Route::get('reports/coach/leave/data', [ReportController::class, 'coachLeaveData'])->name('coach.leave.data');
        Route::get('reports/coach/masterclass/attendance/data', [ReportController::class, 'coachMasterClassAttendanceData'])->name('coach.masterclass.attendance.data');

        // Leave Requests ------------------------------
        Route::resource('leaverequests', LeaveRequestController::class);
        Route::post('leaverequests/data', [LeaveRequestController::class, 'data'])->name('leaverequests.data');
        Route::post('leaverequests/list', [LeaveRequestController::class, 'list'])->name('leaverequests.list');
        Route::post('leaverequests/change-status', [LeaveRequestController::class, 'changeStatus'])->name('leaverequests.change.status');
        Route::post('leaverequests/get-affected-data', [LeaveRequestController::class, 'getAffectedData'])->name('leaverequests.get.affected.data');

        // Feedbacks ------------------------------
        Route::resource('feedbacks', FeedbackController::class);
        Route::post('feedbacks/data', [FeedbackController::class, 'data'])->name('feedbacks.data');
        
        //Levels ------------------------------
        Route::resource('levels', LevelController::class);
        Route::post('levels/data', [LevelController::class, 'data'])->name('levels.data');
        Route::post('levels/list', [LevelController::class, 'list'])->name('levels.list');
        Route::post('levels/change-status', [LevelController::class, 'changeStatus'])->name('levels.change.status');
      
        // Payment_Level ------------------------------
        Route::resource('paymentlevels', PaymentLevelController::class);
        Route::post('paymentlevels/data', [PaymentLevelController::class, 'data'])->name('paymentlevels.data');
        Route::post('paymentlevels/{id}/edit', [PaymentLevelController::class, 'edit'])->name('paymentlevels.editt');
        Route::post('paymentlevels/list', [PaymentLevelController::class, 'list'])->name('paymentlevels.list');
        Route::post('paymentlevels/change-status', [PaymentLevelController::class, 'changeStatus'])->name('paymentlevels.change.status');

        // Holidays ------------------------------
        Route::resource('holidays', HolidayController::class);
        Route::post('holidays/data', [HolidayController::class, 'data'])->name('holidays.data');
        Route::post('holidays/list', [HolidayController::class, 'list'])->name('holidays.list');
        Route::post('holidays/change-status', [HolidayController::class, 'changeStatus'])->name('holidays.change.status');

        // Masterclass ------------------------------
        Route::resource('masterclasses', MasterclassController::class);
        Route::post('masterclasses/data', [MasterclassController::class, 'data'])->name('masterclasses.data');
        Route::post('masterclasses/list', [MasterclassController::class, 'list'])->name('masterclasses.list');
        Route::post('masterclasses/change-status', [MasterclassController::class, 'changeStatus'])->name('masterclasses.change.status');

        //Tournaments
        Route::resource('tournaments', TournamentController::class);
        Route::post('tournaments/data', [TournamentController::class, 'data'])->name('tournaments.data');
        Route::post('tournaments/list', [TournamentController::class, 'list'])->name('tournaments.list');
        Route::post('tournaments/change-status', [TournamentController::class, 'changeStatus'])->name('tournaments.change.status');
        Route::get('tournaments/view/certificate/{tournament}', [TournamentController::class, 'viewCertificate']);

 
        // Blogs ------------------------------
        Route::resource('blogs', BlogController::class);
        Route::post('blogs/data', [BlogController::class, 'data'])->name('blogs.data');
        Route::post('blogs/list', [BlogController::class, 'list'])->name('blogs.list');
        Route::post('blogs/change-status', [BlogController::class, 'changeStatus'])->name('blogs.change.status');
        Route::post('blogs/change-home_featured-status', [BlogController::class, 'changeHomeFeaturedStatus'])->name('blogs.change.home_featured.status');

        // MeetOurKids ------------------------------
        Route::resource('meet-our-kids', MeetOurKidController::class);
        Route::post('meet-our-kids/data', [MeetOurKidController::class, 'data'])->name('meet-our-kids.data');
        Route::post('meetourkids/list', [MeetOurKidController::class, 'list'])->name('meetourkids.list');
        Route::post('meetourkids/change-status', [MeetOurKidController::class, 'changeStatus'])->name('meetourkids.change.status');

        // MeetOurTutors ------------------------------
        Route::resource('meet-our-tutors', MeetOurTutorController::class);
        Route::post('meet-our-tutors/data', [MeetOurTutorController::class, 'data'])->name('meet-our-tutors.data');
        Route::post('meet-our-tutors/change-status', [MeetOurTutorController::class, 'changeStatus'])->name('meet-our-tutors.change.status');

        // Gallery ------------------------------
        Route::resource('galleries', GalleryController::class);
        Route::post('galleries/data', [GalleryController::class, 'data'])->name('galleries.data');
        Route::post('galleries/list', [GalleryController::class, 'list'])->name('galleries.list');
        Route::post('galleries/change-status', [GalleryController::class, 'changeStatus'])->name('galleries.change.status');

        // Gallery Images ------------------------------
        Route::prefix('galleries/{gallery}')->name('galleries.')->group(function () {
            Route::resource('gallery_images', GalleryImagesController::class);
            Route::post('gallery_images/data', [GalleryImagesController::class, 'data'])->name('galleryImages.data');
            Route::post('gallery_images/list', [GalleryImagesController::class, 'list'])->name('galleryImages.list');
            Route::post('gallery_images/change-status', [GalleryImagesController::class, 'changeStatus'])->name('galleryImages.change.status');
        });

        // Event ------------------------------
        Route::resource('events', EventController::class);
        Route::post('events/data', [EventController::class, 'data'])->name('events.data');
        Route::post('events/list', [EventController::class, 'list'])->name('events.list');
        Route::post('events/change-status', [EventController::class, 'changeStatus'])->name('events.change.status');



        // -------------------------------------------------------
        // Student Dashboard routes ------------------------------
        Route::get('/student-dashboard', [StudentDashboardController::class, 'studentDashboard'])->name('student.dashboard');
        Route::get('/student-batches', [StudentDashboardController::class, 'studentBatches'])->name('student.batches');
        Route::get('/student-certificates', [StudentDashboardController::class, 'studentCertificates'])->name('student.certificates');
        Route::get('/student-certificates/download/pdf/{student}', [StudentDashboardController::class, 'studentCertificatesPdf'])->name('student.certificates.pdf');
        Route::get('/student-homework', [StudentDashboardController::class, 'studentHomework'])->name('student.homework');
        Route::get('/student-tournaments', [StudentDashboardController::class, 'studentTournaments'])->name('student.tournaments');
        Route::get('/student-masterclasses', [StudentDashboardController::class, 'studentMasterclasses'])->name('student.masterclasses');
        Route::get('/student-tournaments/certificate/{tournament}', [StudentDashboardController::class, 'tournamentCertificate'])->name('student.tournament.certificate');
        Route::get('/student-fees', [StudentDashboardController::class, 'studentFees'])->name('student.fees');
        Route::get('/student-recordings', [StudentDashboardController::class, 'studentRecordings'])->name('student.recordings');
        Route::post('/student-dashboard/mark-attendance', [StudentDashboardController::class, 'markAttendance'])->name('student-dashboard.mark-attendance');
        Route::get('/student-dashboard/join-class', [StudentDashboardController::class, 'joinClass'])->name('student-dashboard.join-class');

        // -------------------------------------------------------
        // Coach Dashboard routes ------------------------------
        Route::get('dashboard/{coachId}/schedule', [DashboardController::class, 'getSchedule'])->name('dashboard.getSchedule');
        Route::get('dashboard/{coachId}/calendar', [DashboardController::class, 'getCalendarData'])->name('dashboard.calendar');
        Route::get('dashboard/{coachId}/attendance', [DashboardController::class, 'getAttendanceData'])->name('dashboard.getAttendanceData');
        Route::post('dashboard/{coachId}/demo-attendance', [DashboardController::class, 'demoAttendance'])->name('dashboard.demoAttendance');
        Route::post('dashboard/{coachId}/batch-attendance', [DashboardController::class, 'batchAttendance'])->name('dashboard.batchAttendance');
        Route::post('dashboard/{batchId}/pre-batch-attendance', [DashboardController::class, 'preBatchAttendance'])->name('dashboard.pre.batchAttendance');
        Route::post('dashboard/get/coach/master/class', [DashboardController::class, 'getCoachMasteClass'])->name('dashboard.get.coach.master.class');
        Route::post('dashboard/mark/master/class/attendance', [DashboardController::class, 'markMasterClassAttendance'])->name('dashboard.mark.master.class.attendance');
        Route::post('dashboard/mark/pre/master/class/attendance', [DashboardController::class, 'markPreMasterClassAttendance'])->name('dashboard.mark.pre.master.class.attendance');

      
    
        //Timezones We will delete this later (after October) ------------------------------
        // Route::resource('timezones', TimezoneController::class);
        // Route::post('timezones/data', [TimezoneController::class, 'data'])->name('timezones.data');
        // Route::post('timezones/list', [TimezoneController::class, 'list'])->name('timezones.list');
        // Route::post('timezones/change-status', [TimezoneController::class, 'changeStatus'])->name('timezones.change.status');
        // Route::post('timezones/processDateTimeZone', [TimezoneController::class, 'processDateTimeZone'])->name('timezones.processDateTimeZone');
        Route::get('timezones/get/timezones', [TimezoneController::class, 'getTimezones'])->name('timezones.get.timezones');

        // Camera Check just R&D for now
        Route::post('/employee/camera-check', [StudentController::class, 'storeaaa'])->name('employee.camera.check');
        Route::get('admin/employees/{employee}/camera-history', [\App\Http\Controllers\Admin\EmployeeCameraHistoryController::class, 'index'])->name('admin.employees.camera.history');
        Route::post('admin/employees/{employee}/camera-history/data', [\App\Http\Controllers\Admin\EmployeeCameraHistoryController::class, 'data'])->name('admin.employees.camera.history.data');
    });
});

// Data Related Routes
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::get('/fees_records/{year}/{month}/{country}', [DataController::class, 'feesList'])->name('fees.list');
    Route::get('/cancel_batches/{fromdate}/{todate}', [DataController::class, 'cancelBatchList'])->name('cancel.batch.list');
    Route::get('/fees_collection/{year}/{month}/{date}/{country?}', [DataController::class, 'feesCollection'])->name('fees.collection');
    Route::get('/multiple_batch', [DataController::class, 'multiple_batch'])->name('multiple_batch');
    Route::get('/multiple_student_in_batch', [DataController::class, 'multiple_student_in_batch'])->name('multiple_student_in_batch');
    Route::get('/multiple_student_attendance', [DataController::class, 'multiple_student_attendance'])->name('multiple_student_attendance');
    Route::get('/same_day_multiple_student_attendance', [DataController::class, 'same_day_multiple_student_attendance'])->name('same_day_multiple_student_attendance');
    Route::get('/dummy', [DataController::class, 'dummy'])->name('dummy');
    Route::get('/same_users', [DataController::class, 'sameUsers'])->name('same.users');
    Route::get('/coach-attendance', [DataController::class, 'coachAttendance'])->name('coach.attendance');
    Route::get('/get/zoom/users', [DataController::class, 'getZoomUsers'])->name('get.zoom.users');
    Route::get('/student_inactive/{month}', [DataController::class, 'student_inactive'])->name('student_inactive');
    Route::get('/student/cancelled/attendance', [DataController::class, 'student_cancelled_attendance'])->name('student_cancelled_attendance');
    Route::post('/student-attendance/update-date', [DataController::class, 'updateFeeEndDate'])->name('students.updateFeeEndDate');
});


Route::post('/razorpay/verify', function (Request $request) {
    // Use config instead of hardcoding ideally
    $api = new Api('rzp_live_eckVmG8LHU5uhu', 'yN3zXf5cmDKzcgcYn8fWoEoC');
    // $api = new Api('rzp_test_RLrov8eGceCpPt', 'tWqTNh7WveDI7oSqKFeoj446');

    try {
        // 1. Fetch payment
        $payment = $api->payment->fetch($request->payment_id);

        // 2. If it's only authorized, capture it
        if ($payment->status === 'authorized') {
            // amount is in paise; use the same amount Razorpay shows
            $payment = $payment->capture([
                'amount' => $payment->amount,
            ]);
        }

        // 3. If still not captured, treat as failure
        if ($payment->status !== 'captured') {
            Log::warning('Razorpay payment not captured', [
                'payment_id' => $request->payment_id,
                'status'     => $payment->status,
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Payment is not captured yet. Please try again later.',
            ]);
        }

        // ---- From here, payment is definitely captured ----

        $student = Student::findOrFail($request->student_id);

        $order = new Order();
        $order->student_id          = $student->id;
        $order->razorpay_payment_id = $payment->id;
        $order->amount              = $request->amount;   // usually in rupees (your choice)
        $order->currency            = $payment->currency;

        $order->razorpay_data       = json_encode($payment->toArray());
        $order->status              = $payment->status;   // 'captured'
        $order->save();

        // Create StudentFee
        $studentfee = new StudentFee();
        $studentfee->student_id        = $student->id;
        $studentfee->start_date        = date('Y-m-d');
        $studentfee->end_date          = date('Y-m-d', strtotime('+15 days'));
        $studentfee->monthly_fees      = $request->amount;
        $studentfee->total_amount_paid = $request->amount;
        $studentfee->receive_date      = date('Y-m-d');
        $studentfee->status            = 'ACTIVE';
        $studentfee->save();
        $studentBatchStartDate = Carbon::parse($studentfee->start_date)->toDateString();

        // Link order ↔ student_fee
        $order->student_fee_id = $studentfee->id;
        $order->save();
        $old_status                    = $student->status;

        // Student updates
        $student->lastpayment_level_id = $request->payment_level_id;
        $student->status               = 'ACTIVE';
        $student->save();

        if ($old_status == 'FEESDUE') {
            $student_latest_batch = StudentBatch::where('student_id', $student->id)->latest('created_at')->first();
            if ($student_latest_batch) {
                $batch = Batch::where('id', $student_latest_batch->batch_id)->first();
                if ($batch) {
                    $latest_reassign_batch = Batch::where('parent_id', $batch->parent_id)->where('status', '!=', 'INACTIVE')->latest('created_at')->first();
                    if ($latest_reassign_batch) {
                        $last_student = StudentBatch::where('batch_id', $latest_reassign_batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                        $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $latest_reassign_batch->id)->first();
                        if (isset($student_batch)) {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $student_batch->batch_id;
                            $sudentBatch->coach_id = $student_batch->coach_id;
                            $sudentBatch->level_id = $student_batch->level_id;
                            $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                            $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                            $sudentBatch->status = $student_batch->status;
                            $sudentBatch->is_fees_due = 0;
                            $sudentBatch->start_date = $studentBatchStartDate;
                            $sudentBatch->end_date = $student_batch->batch->end_date;
                            $sudentBatch->status = 'ACTIVE';
                            $sudentBatch->save();
                        } else {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $last_student->batch_id;
                            $sudentBatch->coach_id = $last_student->coach_id;
                            $sudentBatch->level_id = $last_student->level_id;
                            $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                            $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                            $sudentBatch->status = $last_student->status;
                            $sudentBatch->is_fees_due = $last_student->is_fees_due;
                            $sudentBatch->start_date = $studentBatchStartDate;
                            $sudentBatch->end_date = $last_student->end_date;
                            $sudentBatch->created_by = $last_student->created_by;
                            $sudentBatch->updated_by = $last_student->updated_by;
                            $sudentBatch->save();
                        }
                    } else {
                        $last_student = StudentBatch::where('batch_id', $batch->id)->where('student_id', '!=', $student->id)->latest('created_at')->first();
                        $student_batch = StudentBatch::where('student_id', $student->id)->where('batch_id', $batch->id)->first();
                        if (isset($student_batch)) {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $student_batch->batch_id;
                            $sudentBatch->coach_id = $student_batch->coach_id;
                            $sudentBatch->level_id = $student_batch->level_id;
                            $sudentBatch->number_of_sessions = $student_batch->number_of_sessions;
                            $sudentBatch->confirm_reassign = $student_batch->confirm_reassign;
                            $sudentBatch->status = $student_batch->status;
                            $sudentBatch->is_fees_due = 0;
                            $sudentBatch->start_date = $studentBatchStartDate;
                            $sudentBatch->end_date = $student_batch->batch->end_date;
                            $sudentBatch->status = 'ACTIVE';
                            $sudentBatch->save();
                        } else {
                            $sudentBatch = new StudentBatch();
                            $sudentBatch->student_id = $student->id;
                            $sudentBatch->batch_id = $last_student->batch_id;
                            $sudentBatch->coach_id = $last_student->coach_id;
                            $sudentBatch->level_id = $last_student->level_id;
                            $sudentBatch->number_of_sessions = $last_student->number_of_sessions;
                            $sudentBatch->confirm_reassign = $last_student->confirm_reassign;
                            $sudentBatch->status = $last_student->status;
                            $sudentBatch->is_fees_due = $last_student->is_fees_due;
                            $sudentBatch->start_date = $studentBatchStartDate;
                            $sudentBatch->end_date = $last_student->end_date;
                            $sudentBatch->created_by = $last_student->created_by;
                            $sudentBatch->updated_by = $last_student->updated_by;
                            $sudentBatch->save();
                        }
                    }
                }
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Payment verified and processed successfully',
        ]);

    } catch (\Exception $e) {
        Log::error('Razorpay verify error', [
            'message'    => $e->getMessage(),
            'payment_id' => $request->payment_id ?? null,
        ]);

        return response()->json([
            'status'  => 'error',
            'message' => 'Something went wrong while verifying payment.',
        ], 500);
    }
});


Route::post('/create/order', [HdfcPaymentController::class, 'createOrder'])->name('create.order');
Route::post('/hdfc/payment', [HdfcPaymentController::class, 'createSession'])->name('hdfc.payment');

Route::get('/admin/dashboard/dashboard', [HomeController::class, 'index']);
Route::get('/admin/dashboard/dummy', [HomeController::class, 'dummy']);

Route::get('/get-timezones', [HomeController::class, 'getTimezones'])->name('get.timezones');
