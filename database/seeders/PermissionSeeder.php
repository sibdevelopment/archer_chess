<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Permissiongroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private $permissions = [
        // 'Crud' => [
        //     'controller' => 'Admin\CrudController',
        //     'permissions' => [
        //         'crud-view' => [
        //             'index',
        //             'data',
        //         ],
        //         'crud-store' => [
        //             'create',
        //             'store',
        //         ],
        //         'crud-update' => [
        //             'edit',
        //             'update',
        //         ],
        //         'crud-generate' => [
        //             'generate',
        //             'undo',
        //         ],
        //     ]
        // ],
        'Dashboard' => [
            'controller' => 'Admin\DashboardController',
            'permissions' => [
                'dashboard-view' => [
                    'index1',
                    'index',
                    'markMasterClassAttendance',
                    'markPreMasterClassAttendance',
                    'getSchedule',
                    'getCalendarData',
                    'getAttendanceData',
                    'demoAttendance',
                    'batchAttendance',
                    'studentData',
                    'batchData',
                    'getCoachMasteClass',
                    'preBatchAttendance',
                    'missedSessionsData',
                    'getUnmarkedAttendance',
                ],
                'availability-view' => [
                    'availabilityIndex',
                    'availability',
                ],
            ],
        ],
        'Role' => [
            'controller' => 'Admin\RoleController',
            'permissions' => [
                'role-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'role-store' => [
                    'create',
                    'store',
                ],
                'role-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
                'role-permission' => [
                    'permissionsShow',
                    'permissionsUpdate',
                ],
            ],
        ],
        // 'User' => [
        //     'controller' => 'Admin\UserController',
        //     'permissions' => [
        //         'user-view' => [
        //             'index',
        //             'data',
        //             'list',
        //             'show',
        //         ],
        //         'user-store' => [
        //             // 'create',
        //             // 'store',
        //         ],
        //         'user-update' => [
        //             // 'edit',
        //             // 'update',
        //             'changeStatus',
        //         ],
        //     ]
        // ],
        'Employee' => [
            'controller' => 'Admin\EmployeeController',
            'permissions' => [
                'employee-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'employee-store' => [
                    'create',
                    'store',
                ],
                'employee-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Enquiry' => [
            'controller' => 'Admin\EnquiryController',
            'permissions' => [
                'enquiry-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'enquiry-store' => [],
                'enquiry-update' => [],
                'enquiry-destroy' => [
                    'destroy',
                ],
            ],
        ],
        // 'Setting' => [
        //     'controller' => 'Admin\SettingController',
        //     'permissions' => [
        //         'setting-view' => [
        //             'index',
        //             'data',
        //             'list',
        //             'show',
        //             'getDataPage',
        //             'updateDataPage',
        //         ],
        //         'setting-store' => [
        //             'create',
        //             'store',
        //         ],
        //         'setting-update' => [
        //             'edit',
        //             'update',
        //             'changeStatus',
        //         ],
        //     ]
        // ],
        'Level' => [
            'controller' => 'Admin\LevelController',
            'permissions' => [
                'levels-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                ],
                'levels-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'levels-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],
        ],
        'Coach' => [
            'controller' => 'Admin\CoachController',
            'permissions' => [
                'coachs-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                ],
                'coachs-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'coachs-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],
        ],
        'CoachAvailability' => [
            'controller' => 'Admin\CoachAvailabilityController',
            'permissions' => [
                'coachavailability-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'addDay',
                    'addPeriod',
                ],
                'coachavailability-store' => [
                    'create',
                    'store',
                    'addDay',
                    'addPeriod',
                ],
                'coachavailability-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'addDay',
                    'addPeriod',
                    'editAll',
                    'updateAll',
                    'editDay',
                    'editPeriod',
                    'updateAll',
                ],
            ],
        ],
        'DemoLead' => [
            'controller' => 'Admin\DemoLeadController',
            'permissions' => [
                'demolead-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'convertToStudent',
                    'saveConvertedStudent',
                    'processDateTimeZone',
                    'destroy',
                    'checkMobileUniqueness',
                    'getTimezones',
                    'getRemark',
                    'getReason',
                    'getDemoRecordings',
                    'export',
                ],
                'demolead-store' => [
                    'create',
                    'store',
                    'convertToStudent',
                    'saveConvertedStudent',
                    'processDateTimeZone',
                    'destroy',
                    'checkMobileUniqueness',
                    'getTimezones',
                    'getRemark',
                    'getReason',
                ],
                'demolead-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'convertToStudent',
                    'saveConvertedStudent',
                    'processDateTimeZone',
                    'destroy',
                    'checkMobileUniqueness',
                    'getTimezones',
                    'getRemark',
                    'getReason',
                ],
            ],
        ],
        'DemoSessions' => [
            'controller' => 'Admin\DemoSessionsController',
            'permissions' => [
                'demosession-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'getCoachAvailability',
                    'getAvailableSlots',
                    'destroy',
                ],
                'demosession-store' => [
                    'create',
                    'store',
                    'getCoachAvailability',
                    'getAvailableSlots',
                    'destroy',
                ],
                'demosession-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'getCoachAvailability',
                    'getAvailableSlots',
                    'destroy',
                ],
            ],
        ],
        'Student' => [
            'controller' => 'Admin\StudentController',
            'permissions' => [
                'students-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                    'getCoaches',
                    'getBatches',
                    'deleteStudentAtendance',
                    'masterclassTounamentlist',
                    'deleteStudentBatchAtendance',
                    'export',
                    'storeaaa',
                ],
                'students-store' => [
                    'create',
                    'store',
                    'destroy',
                    'getCoaches',
                    'getBatches',
                ],
                'students-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                    'getCoaches',
                    'getBatches',
                ],
            ],
        ],
        'StudentFee' => [
            'controller' => 'Admin\StudentFeeController',
            'permissions' => [
                'studentfee-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                    'downloadInvoice',
                ],
                'studentfee-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'studentfee-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],

        ],
        'Batch' => [
            'controller' => 'Admin\BatchController',
            'permissions' => [
                'batchs-view' => [
                    'batchAttendance',
                    'index',
                    'data',
                    'list',
                    'show',
                    'assignBatchToStudent',
                    'saveAssignedStudent',
                    'reassignBatchToStudentModal',
                    'saveReassignedStudent',
                    'addWeekday',
                    'editWeekDay',
                    'destroy',
                    'getCoaches',
                    'getStudents',
                    'masterclassTounamentlist',
                    'checkName',
                    'checkSchedule',
                    'changeCoaches',
                    'changeCoach',
                ],
                'batchs-store' => [
                    'create',
                    'store',
                    'assignBatchToStudent',
                    'saveAssignedStudent',
                    'reassignBatchToStudentModal',
                    'saveReassignedStudent',
                    'addWeekday',
                    'editWeekDay',
                    'destroy',
                    'getCoaches',
                    'getStudents',
                ],
                'batchs-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'assignBatchToStudent',
                    'saveAssignedStudent',
                    'reassignBatchToStudentModal',
                    'saveReassignedStudent',
                    'addWeekday',
                    'editWeekDay',
                    'destroy',
                    'getCoaches',
                    'getStudents',
                ],
            ],
        ],
        'BatchSchedule' => [
            'controller' => 'Admin\BatchScheduleController',
            'permissions' => [
                'batchschedule-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'batchschedule-store' => [
                    'create',
                    'store',
                ],
                'batchschedule-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Holiday' => [
            'controller' => 'Admin\HolidayController',
            'permissions' => [
                'holidays-view' => [
                    'index',
                    'data',
                    'destroy',
                ],
                'holidays-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'holidays-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],
        ],
        'LeaveRequest' => [
            'controller' => 'Admin\LeaveRequestController',
            'permissions' => [
                'leaverequests-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'getAffectedData',
                ],
                'leaverequests-store' => [
                    'create',
                    'store',
                    'getAffectedData',
                ],
                'leaverequests-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'getAffectedData',
                ],
            ],
        ],
        'Report' => [
            'controller' => 'Admin\ReportController',
            'permissions' => [
                'reports-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'getSchedule',
                    'getCalendarData',
                    'downloadReport',
                    'getCounts',
                    'reportsScheduleData',
                    'getAttendanceData',
                    'batchAttendance',
                    'demoAttendance',
                    'getAvailability',
                    'batchStudentCountryData',
                    'batchCompletedData',
                    'demoCompletedData',
                    'coachLeaveData',
                    'coachMasterClassAttendanceData',
                    'coverupclassCompletedData',
                    'delayedBatchesReportData',
                ],
                'reports-store' => [
                    'create',
                    'store',
                    'getSchedule',
                    'getCalendarData',
                    'downloadReport',
                    'getCounts',
                    'reportsScheduleData',
                    'getAttendanceData',
                    'batchAttendance',
                    'demoAttendance',
                    'getAvailability',
                    'batchStudentCountryData',
                    'batchCompletedData',
                    'demoCompletedData',
                    'coachLeaveData',
                    'delayedBatchesReportData',
                ],
                'reports-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'getSchedule',
                    'getCalendarData',
                    'downloadReport',
                    'getCounts',
                    'reportsScheduleData',
                    'getAttendanceData',
                    'batchAttendance',
                    'demoAttendance',
                    'getAvailability',
                    'batchStudentCountryData',
                    'batchCompletedData',
                    'demoCompletedData',
                    'coachLeaveData',
                    'delayedBatchesReportData',
                ],
                'reports-attedance' => [
                    'batchAttendance'
                ],
            ],
        ],
        'LeadEnquiry' => [
            'controller' => 'Admin\LeadEnquiryController',
            'permissions' => [
                'leadenquiry-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                    'convertToDemoLead',
                    'rejectTheDemoLead',
                    'convertForm',
                    'convertStore',
                ],
                'leadenquiry-store' => [
                    'create',
                    'store',
                    'destroy',
                    'convertToDemoLead',
                    'rejectTheDemoLead',
                ],
                'leadenquiry-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                    'convertToDemoLead',
                    'rejectTheDemoLead',
                ],
            ],

        ],
        'Timezone' => [
            'controller' => 'Admin\TimezoneController',
            'permissions' => [
                'timezone-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                    'processDateTimeZone',
                    'getTimezones',
                ],
                'timezone-store' => [
                    'create',
                    'store',
                    'destroy',
                    'processDateTimeZone',
                    'getTimezones',
                ],
                'timezone-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                    'processDateTimeZone',
                    'getTimezones',
                ],
            ],
        ],

        'Tournament' => [
            'controller' => 'Admin\TournamentController',
            'permissions' => [
                'tournaments-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                    'viewCertificate',
                ],
                'tournaments-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'tournaments-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],
        ],
        'Masterclass' => [
            'controller' => 'Admin\MasterclassController',
            'permissions' => [
                'masterclasses-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                ],
                'masterclasses-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'masterclasses-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],

            ],
        ],
        'StudentDashboard' => [
            'controller' => 'Admin\StudentDashboardController',
            'permissions' => [
                'student-dashboard-view' => [
                    'studentDashboard',
                    'markAttendance',
                ],
                'student-store' => [
                    'create',

                ],
                'student-update' => [
                    'edit',

                ],
                'student-batches' => [
                    'studentBatches',
                ],
                'student-certificates' => [
                    'studentCertificates',
                    'studentCertificatesPdf',
                ],
                'student-homework' => [
                    'studentHomework',
                ],
                'student-recording' => [
                    'studentRecordings',
                ],
                'student-tournaments' => [
                    'studentTournaments',
                    'tournamentCertificate',
                ],
                'student-masterclasses' => [
                    'studentMasterclasses',
                ],
                'student-fees' => [
                    'studentFees',
                ],
            ],
        ],
        'Feedback' => [
            'controller' => 'Admin\FeedbackController',
            'permissions' => [
                'feedback-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'destroy',
                ],
                'feedback-store' => [
                    'create',
                    'store',
                    'destroy',
                ],
                'feedback-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'destroy',
                ],
            ],
        ],
        'PaymentLevel' => [
            'controller' => 'Admin\PaymentLevelController',
            'permissions' => [
                'paymentlevels-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'paymentlevels-store' => [
                    'create',
                    'store',
                ],
                'paymentlevels-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Coverupclass' => [
            'controller' => 'Admin\CoverupclassController',
            'permissions' => [
                'coverupclasses-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'getCoach',
                    'changeCoach',
                ],
                'coverupclasses-store' => [
                    'create',
                    'store',
                ],
                'coverupclasses-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'NewEnrollment' => [
            'controller' => 'Admin\NewEnrollmentController',
            'permissions' => [
                'new-enrollments-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'export',
                ],
                'new-enrollments-store' => [
                    'create',
                    'store',
                ],
                'new-enrollments-update' => [
                    'edit',
                    'update',
                ],
            ],
        ],
        'Changeclass' => [
            'controller' => 'Admin\ChangeclassController',
            'permissions' => [
                'change-classes-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                    'export',
                ],
                'change-classes-store' => [
                    'create',
                    'store',
                ],
                'change-classes-update' => [
                    'edit',
                    'update',
                ],
            ],
        ],
        'Blog' => [
            'controller' => 'Admin\BlogController',
            'permissions' => [
                'blogs-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'blogs-store' => [
                    'create',
                    'store',
                ],
                'blogs-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'changeHomeFeaturedStatus',
                ],
            ],
        ],
        'MeetOurKid' => [
            'controller' => 'Admin\MeetOurKidController',
            'permissions' => [
                'meetourkids-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'meetourkids-store' => [
                    'create',
                    'store',
                ],
                'meetourkids-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                    'changeHomeFeaturedStatus',
                ],
            ],
        ],
        'MeetOurTutor' => [
            'controller' => 'Admin\MeetOurTutorController',
            'permissions' => [
                'meetourtutors-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'meetourtutors-store' => [
                    'create',
                    'store',
                ],
                'meetourtutors-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Gallery' => [
            'controller' => 'Admin\GalleryController',
            'permissions' => [
                'galleries-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'galleries-store' => [
                    'create',
                    'store',
                ],
                'galleries-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'GalleryImage' => [
            'controller' => 'Admin\GalleryImagesController',
            'permissions' => [
                'gallery-image-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'gallery-image-store' => [
                    'create',
                    'store',
                ],
                'gallery-image-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Event' => [
            'controller' => 'Admin\EventController',
            'permissions' => [
                'events-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'events-store' => [
                    'create',
                    'store',
                ],
                'events-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        'Camera' => [
            'controller' => 'Admin\CameraController',
            'permissions' => [
                'camera-view' => [
                    'index',
                    'data',
                    'list',
                    'show',
                ],
                'camera-store' => [
                    'create',
                    'store',
                ],
                'camera-update' => [
                    'edit',
                    'update',
                    'changeStatus',
                ],
            ],
        ],
        //End of Permission Arr
    ];

    public $roles = [
        'SuperAdmin' => [
            #All Permission
            'availability-view',
            //Crud
            // 'crud-view',
            // 'crud-store',
            // 'crud-update',
            // 'crud-generate',

            #User
            // 'user-view',
            // 'user-store',
            // 'user-update',

            #Setting
            // 'setting-view',
            // 'setting-store',
            // 'setting-update',


            #camera
            'camera-view',
            'camera-store',
            'camera-update',


            #Feedback
            'feedback-view',
            'feedback-store',
            'feedback-update',

            #Dashboard
            'dashboard-view',

            #Roles
            'role-view',
            'role-store',
            'role-update',
            'role-permission',

            #Employee
            'employee-view',
            'employee-store',
            'employee-update',

            #PaymentLevel
            'paymentlevels-view',
            'paymentlevels-store',
            'paymentlevels-update',

            #Level
            'levels-view',
            'levels-store',
            'levels-update',

            #Coach
            'coachs-view',
            'coachs-store',
            'coachs-update',

            #CoachAvailability
            'coachavailability-view',
            'coachavailability-store',
            'coachavailability-update',

            #DemoLead
            'demolead-view',
            'demolead-store',
            'demolead-update',

            #demosession
            'demosession-view',
            'demosession-store',
            'demosession-update',

            #Student
            'students-view',
            'students-store',
            'students-update',

            #Student Fee
            'studentfee-view',
            'studentfee-store',
            'studentfee-update',

            #Batch
            'batchs-view',
            'batchs-store',
            'batchs-update',

            #BatchSchedule
            'batchschedule-view',
            'batchschedule-store',
            'batchschedule-update',

            #BatchStudent
            'batchstudent-view',
            'batchstudent-store',
            'batchstudent-update',

            #Holiday
            'holidays-view',
            'holidays-store',
            'holidays-update',

            #LeaveRequest
            'leaverequests-view',
            'leaverequests-store',
            'leaverequests-update',

            #Report
            'reports-view',
            'reports-store',
            'reports-update',
            'reports-attedance',

            #LeadEnquiry
            'leadenquiry-view',
            'leadenquiry-store',
            'leadenquiry-update',

            #LeadEnquiry
            'leadenquiry-view',
            'leadenquiry-store',
            'leadenquiry-update',

            #timezone
            'timezone-view',
            'timezone-store',
            'timezone-update',

            #Enquiry
            'enquiry-view',
            'enquiry-store',
            'enquiry-update',
            'enquiry-destroy',

            #masterclass
            'masterclasses-view',
            'masterclasses-store',
            'masterclasses-update',

            #tournament
            'tournaments-view',
            'tournaments-store',
            'tournaments-update',

            #student
            'student-dashboard-view',
            'student-batches',
            'student-certificates',
            'student-homework',
            'student-recording',
            'student-tournaments',
            'student-masterclasses',

            #Coverupclass
            'coverupclasses-view',
            'coverupclasses-store',
            'coverupclasses-update',

            #NewEnrollment
            'new-enrollments-view',
            'new-enrollments-store',
            'new-enrollments-update',

            #Change Class
            'change-classes-view',
            'change-classes-store',
            'change-classes-update',

            #Blog
            'blogs-view',
            'blogs-store',
            'blogs-update',

            #MeetOurKid
            'meetourkids-view',
            'meetourkids-store',
            'meetourkids-update',

            #MeetOurTutor
            'meetourtutors-view',
            'meetourtutors-store',
            'meetourtutors-update',

            #MeetOurKid
            'meetourkids-view',
            'meetourkids-store',
            'meetourkids-update',

            #Gallery
            'galleries-view',
            'galleries-store',
            'galleries-update',

            #GalleryImages
            'gallery-image-view',
            'gallery-image-store',
            'gallery-image-update',

            #Event
            'events-view',
            'events-store',
            'events-update',
            
            'data-view'
            //End of Role Permission
        ],
        'User' => [],
        'Coach' => [
            #Dashboard
            'dashboard-view',

            #camera
            'camera-view',
            'camera-store',
            'camera-update',

            #LeaveRequest
            'leaverequests-view',
            'leaverequests-store',
            'leaverequests-update',

            #Report
            'reports-view',
            'reports-store',
            'reports-update',

            #Student
            'students-view',
            'students-store',
            'students-update',

            #Batch
            'batchs-view',
            'batchs-store',
            'batchs-update',

            #BatchSchedule
            'batchschedule-view',
            'batchschedule-store',
            'batchschedule-update',

            #BatchStudent
            'batchstudent-view',
            'batchstudent-store',
            'batchstudent-update',

            'data-view'
            //End of Role Permission

        ],
        'Student' => [
            'student-dashboard-view',
            'student-batches',
            'student-certificates',
            'student-homework',
            'student-recording',
            'student-tournaments',
            'student-masterclasses',
            'feedback-view',
            'feedback-store',
            'feedback-update',
            'student-fees',
        ],
    ];

    private $users = [
        [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'roles' => [
                'SuperAdmin',
            ],
            'mobile' => '9999999999',
            'email' => 'super@technicul.com',
            'password' => 'super@123@technicul',
        ],
        [
            'first_name' => 'Archer',
            'last_name' => 'Academy',
            'roles' => [
                'SuperAdmin',
            ],
            'mobile' => '9999999999',
            'email' => 'scbhattu@gmail.com',
            'password' => 'maharaja@1',
        ],
        [
            'first_name' => 'Archer',
            'last_name' => 'Academy',
            'roles' => [
                'SuperAdmin',
            ],
            'mobile' => '9999999991',
            'email' => 'jchessdevelopers@gmail.com',
            'password' => 'Archer@2026',
        ],
    ];

    public function run()
    {
        #Groups & Permission
        $this->deletePermissions();
        $this->createPermissions();

        #Create Roles
        $this->createRoles();

        #Create Users
        $this->createUsers();
    }

    private function deletePermissions()
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $delete_permission = true;
            foreach ($this->permissions as $group => $data) {
                foreach ($data['permissions'] as $p_name => $methods) {
                    if ($p_name == $permission->name) {
                        $delete_permission = false;
                    }
                }
            }
            if ($delete_permission) {
                $permission->delete();
            }
        }

        $permission_groups = Permissiongroup::all();
        foreach ($permission_groups as $permission_group) {
            $delete_permissiongroup = true;
            foreach ($this->permissions as $group => $data) {
                if ($group == $permission_group->name) {
                    $delete_permissiongroup = false;
                }
            }
            if ($delete_permissiongroup) {
                $permission_group->delete();
            }
        }
    }

    private function createPermissions()
    {
        foreach ($this->permissions as $group => $data) {
            $permissiongroup = Permissiongroup::where('name', $group)->first();
            if (!$permissiongroup) {
                $permissiongroup = new Permissiongroup;
                $permissiongroup->name = $group;
                $permissiongroup->controller = $data['controller'];
                $permissiongroup->save();
            } else {
                $permissiongroup->controller = $data['controller'];
                $permissiongroup->save();
            }

            foreach ($data['permissions'] as $permissions_name => $methods) {
                $permission = Permission::where('permissiongroup_id', $permissiongroup->id)->where('name', $permissions_name)->first();
                if (!$permission) {
                    $permission = new Permission;
                    $permission->permissiongroup_id = $permissiongroup->id;
                    $permission->name = $permissions_name;
                    $permission->methods = $methods;
                    $permission->guard_name = config('auth.defaults.guard');
                    $permission->save();
                } else {
                    $permission->methods = $methods;
                    $permission->save();
                }
            }
        }
    }

    private function createRoles()
    {
        foreach ($this->roles as $role_name => $permissions) {
            $role = Role::where('name', $role_name)->first();
            if (!$role) {
                $role = new Role;
                $role->name = $role_name;
                // $role->guard_name = config('auth.defaults.guard');
                $role->save();
            }
            $permission_ids = Permission::whereIn('name', $permissions)->pluck('id');
            $role->syncPermissions($permission_ids);
        }
    }

    private function createUsers()
    {
        foreach ($this->users as $data) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = new User;
                $user->email = $data['email'];
                $user->mobile = $data['mobile'];
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $user->password = \Hash::make($data['password']);
                $user->save();
            } else {
                $user->first_name = $data['first_name'];
                $user->last_name = $data['last_name'];
                $user->password = \Hash::make($data['password']);
                $user->save();
            }

            #Assign Role & Sync Permission to User
            $user->assignRole($data['roles']);

            #Sync All Roles Permissions to User
            $all_permissions = collect();
            // dd($data['roles']);
            foreach ($data['roles'] as $role_name) {
                $role = Role::where('name', $role_name)->first();
                $permissions = $role->permissions()->pluck('name')->toArray();
                // dd($permissions);
                foreach ($permissions as $permission) {
                    $all_permissions->push($permission);
                }
            }
            $user->syncPermissions($all_permissions);
        }
        //$this->seedTimezones();
    }

    // private function seedTimezones()
    // {
    //     $countries = [
    //         'USA' => [
    //             'Eastern Daylight Time', 'Central Daylight Time', 'Mountain Daylight Time',
    //             'Pacific Daylight Time', 'Alaska Daylight Time', 'Mountain Standard Time',
    //             'Eastern Standard Time', 'Central Standard Time', 'Pacific Standard Time',
    //         ],
    //         'CANADA' => [
    //             'Eastern Daylight Time', 'Central Daylight Time', 'Mountain Daylight Time',
    //             'Pacific Daylight Time', 'Alaska Daylight Time', 'Mountain Standard Time',
    //             'Eastern Standard Time', 'Central Standard Time', 'Pacific Standard Time',
    //         ],
    //         'AUSTRALIA' => [
    //             'Australian Western Standard Time', 'Australian Eastern Standard Time',
    //             'Australian Central Standard Time',
    //         ],
    //         'NEWZEALAND' => [
    //             'New Zealand Daylight Time', 'New Zealand Standard Time',
    //         ],
    //         'INDIA' => [
    //             'Indian Standard Time',
    //         ],
    //         'UAE' => [
    //             'Gulf Standard Time',
    //         ],
    //         'UK' => [
    //             'British Summer Time', 'Greenwich Mean Time',
    //         ],
    //         'SINGAPORE' => [
    //             'Singapore Standard Time',
    //         ],
    //     ];

    //     $weekdays = [
    //         'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY',
    //     ];

    //     foreach ($countries as $country => $timezones) {
    //         foreach ($weekdays as $weekday) {
    //             foreach ($timezones as $timezone) {
    //                 // Check if the combination already exists
    //                 $exists = Timezone::where('country', $country)
    //                     ->where('weekday', $weekday)
    //                     ->where('timezone', $timezone)
    //                     ->exists();

    //                 if (!$exists) {
    //                     Timezone::create([
    //                         'country' => $country,
    //                         'weekday' => $weekday,
    //                         'timezone' => $timezone,
    //                         'india_start_time' => null,
    //                         'india_end_time' => null,
    //                         'country_start_time' => null,
    //                         'country_end_time' => null,
    //                         'status' => 'INACTIVE',
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }

}
