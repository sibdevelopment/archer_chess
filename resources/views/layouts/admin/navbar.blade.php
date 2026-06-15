@php
    $user = \Auth::user();
    $roleName = $user->getRoleNames()->first();
@endphp
<nav class="sidebar-nav scroll-sidebar" data-simplebar>
    @if ($roleName != 'Student')
        <ul id="sidebarnav">
            @can('dashboard-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.dashboard.*')) active @endif"
                        href="{{ route('admin.dashboard.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-aperture"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
            @endcan

            <!-- User Management -->
            @canany(['role-view', 'employee-view', 'coachs-view', 'students-view'])
                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Users </span>
                </li>
            @endcanany

            @can('availability-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.availability.index')) active @endif"
                        href="{{ route('admin.availability.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-report"></i>
                        </span>
                        <span class="hide-menu">Coach Availability</span>
                    </a>
                </li>
            @endcan

            @can('role-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.roles.*')) active @endif"
                        href="{{ route('admin.roles.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-check"></i>
                        </span>
                        <span class="hide-menu">Roles</span>
                    </a>
                </li>
            @endcan

            @can('employee-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.employees.*')) active @endif"
                        href="{{ route('admin.employees.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-circle"></i>
                        </span>
                        <span class="hide-menu">Employees</span>
                    </a>
                </li>
            @endcan

            @can('coachs-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.coaches.*')) active @endif"
                        href="{{ route('admin.coaches.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-exclamation"></i>
                        </span>
                        <span class="hide-menu capitalize">Coaches</span>
                    </a>
                </li>
            @endcan

            <!-- Operations Management -->
            @canany(['batchs-view', 'demolead-view', 'leadenquiry-view'])
                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Operations</span>
                </li>
            @endcanany

            @can('enquiry-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.enquiries.*')) active @endif"
                        href="{{ route('admin.enquiries.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Website Enquiries</span>
                    </a>
                </li>
            @endcan

            @can('leadenquiry-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.leadenquiries.*')) active @endif"
                        href="{{ route('admin.leadenquiries.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-loader"></i>
                        </span>
                        <span class="hide-menu capitalize">Lead Enquiries</span>
                    </a>
                </li>
            @endcan

            @can('demolead-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.demoleads.*')) active @endif"
                        href="{{ route('admin.demoleads.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-headset"></i>
                        </span>
                        <span class="hide-menu capitalize">Demo Leads</span>
                    </a>
                </li>
            @endcan

            @can('new-enrollments-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.newenrollments.*')) active @endif"
                        href="{{ route('admin.newenrollments.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-headset"></i>
                        </span>
                        <span class="hide-menu capitalize">New Enrollments</span>
                    </a>
                </li>
            @endcan

            @can('students-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.students.*')) active @endif"
                        href="{{ route('admin.students.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-school"></i>
                        </span>
                        <span class="hide-menu capitalize">Students</span>
                    </a>
                </li>
            @endcan

            
            @can('change-classes-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.changeclasses.*')) active @endif"
                        href="{{ route('admin.changeclasses.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-refresh"></i>
                        </span>
                        <span class="hide-menu capitalize">Change Batches</span>
                    </a>
                </li>
            @endcan

            @can('coverupclasses-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.coverupclasses.*')) active @endif"
                        href="{{ route('admin.coverupclasses.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu capitalize">Cover Up Classes</span>
                    </a>
                </li>
            @endcan
            
            @can('batchs-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.batchs.*')) active @endif"
                        href="{{ route('admin.batchs.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-color-swatch"></i>
                        </span>
                        <span class="hide-menu capitalize">Batches</span>
                    </a>
                </li>
            @endcan


            @canany(['leaverequest-view', 'reports-view'])
                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Actions</span>
                </li>
            @endcanany

            <!-- Actions Management -->
            @can('reports-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.reports.*')) active @endif"
                        href="{{ route('admin.reports.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-chart-infographic"></i>
                        </span>
                        <span class="hide-menu capitalize">Reports</span>
                    </a>
                </li>
            @endcan

            @can('leaverequests-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.leaverequests.*')) active @endif"
                        href="{{ route('admin.leaverequests.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu capitalize">Leave Requests</span>
                    </a>
                </li>
            @endcan

            @can('feedback-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.feedbacks.*')) active @endif"
                        href="{{ route('admin.feedbacks.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-message-circle"></i>
                        </span>
                        <span class="hide-menu">Feedbacks</span>
                    </a>
                </li>
            @endcan

            <!-- MASTER :: -->
            @canany(['holidays-view', 'levels-view'])
                <li class="nav-small-cap mt-2">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Master </span>
                </li>
            @endcanany

            @can('levels-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.levels.*')) active @endif"
                        href="{{ route('admin.levels.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-chart-arrows"></i>
                        </span>
                        <span class="hide-menu capitalize">Levels</span>
                    </a>
                </li>
            @endcan

            @can('paymentlevels-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.paymentlevels.*')) active @endif"
                        href="{{ route('admin.paymentlevels.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-devices-dollar"></i>
                        </span>
                        <span class="hide-menu">Payment Level</span>
                    </a>
                </li>
            @endcan

            @can('holidays-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.holidays.*')) active @endif"
                        href="{{ route('admin.holidays.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-cog"></i>
                        </span>
                        <span class="hide-menu capitalize">Holidays</span>
                    </a>
                </li>
            @endcan

            @can('masterclasses-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.masterclasses.*')) active @endif"
                        href="{{ route('admin.masterclasses.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-chess-bishop"></i>
                        </span>
                        <span class="hide-menu capitalize">Master Classes</span>
                    </a>
                </li>
            @endcan

            @can('tournaments-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.tournaments.*')) active @endif"
                        href="{{ route('admin.tournaments.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-go-game"></i>
                        </span>
                        <span class="hide-menu capitalize">Tournaments</span>
                    </a>
                </li>
            @endcan

            @can('blogs-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.blogs.*')) active @endif"
                        href="{{ route('admin.blogs.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-topology-star-3"></i>
                        </span>
                        <span class="hide-menu capitalize">Blogs</span>
                    </a>
                </li>
            @endcan

            @can('meetourkids-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.meet-our-kids.*')) active @endif"
                        href="{{ route('admin.meet-our-kids.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-topology-star-3"></i>
                        </span>
                        <span class="hide-menu capitalize">Meet Our Kids</span>
                    </a>
                </li>
            @endcan

            @can('meetourtutors-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.meet-our-tutors.*')) active @endif"
                        href="{{ route('admin.meet-our-tutors.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-topology-star-3"></i>
                        </span>
                        <span class="hide-menu capitalize">Meet Our Tutors</span>
                    </a>
                </li>
            @endcan

            @can('galleries-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.galleries.*')) active @endif"
                        href="{{ route('admin.galleries.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-photo"></i>
                        </span>
                        <span class="hide-menu capitalize">Gallery</span>
                    </a>
                </li>
            @endcan

            @can('events-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.events.*')) active @endif"
                        href="{{ route('admin.events.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu capitalize">Events</span>
                    </a>
                </li>
            @endcan

            @if ((auth()->check() && auth()->user()->component_tab === 'YES') || in_array(auth()->id(), [13, 14, 15, 16, 17, 1]))
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-components"></i>
                        </span>
                        <span class="hide-menu">Component</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        {{-- <li class="sidebar-item">
                            <a href="{{ route('multiple_batch') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Multiple Batch</span>
                            </a>
                        </li> --}}

                        {{-- <li class="sidebar-item">
                            <a href="{{ route('multiple_student_in_batch') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Multiple Student in Batch</span>
                            </a>
                        </li> --}}

                        <li class="sidebar-item">
                            <a href="{{ route('multiple_student_attendance') }}" target="_blank"
                                class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Multiple Std Attendance</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="{{ route('same_day_multiple_student_attendance') }}" target="_blank"
                                class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Same Day Attendance</span>
                            </a>
                        </li>

                        {{-- <li class="sidebar-item">
                            <a href="{{ route('dummy') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Dummy Page</span>
                            </a>
                        </li> --}}

                        {{-- <li class="sidebar-item">
                            <a href="{{ route('same.users') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Same Users</span>
                            </a>
                        </li> --}}

                        <li class="sidebar-item">
                            <a href="{{ route('coach.attendance') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Coach Attendance</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#feesRecordModal"
                                class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Fees Record</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#feesCollectionModal" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Fees Collection</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#cancelBatchListModal" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Cancel Batch List</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#studentInactiveListModal" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Student Inactive</span>
                            </a>
                        </li>


                        <li class="sidebar-item">
                            <a href="{{ route('student_cancelled_attendance') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Student Cancelled Batch</span>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif

        </ul>

        <div class="unlimited-access hide-menu position-relative my-7 rounded"
            style="background-color: #fff !important; border: 3px solid #FF4D4D !important;">
            <div class="d-flex">
                <div class="unlimited-access-title">
                    <h6 class="fw-semibold fs-4 text-dark w-85" style="margin-bottom: 0rem !important;">
                        Together <br>
                        We Are Archer Kids
                    </h6>
                </div>
                <span>
                    <i class="ti ti-chess-king d-block"
                        style="font-size: 4rem!important; color: #FF4D4D !important;"></i>
                </span>
            </div>
        </div>
    @endif

    @php
        $current_domain = request()->getHttpHost();
    @endphp

    <hr>
    @if ($roleName == 'Student') 
        <ul>
            @can('student-dashboard-view')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.dashboard')) active @endif"
                        href="{{ route('admin.student.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-aperture"></i>
                        </span>
                        <span class="hide-menu capitalize">Dashboard</span>
                    </a>
                </li>
            @endcan
            @can('student-batches')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.batches')) active @endif"
                        href="{{ route('admin.student.batches') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-school"></i>
                        </span>
                        <span class="hide-menu capitalize">Class Details</span>
                    </a>
                </li>
            @endcan
            @can('student-recording')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.recordings')) active @endif"
                        href="{{ route('admin.student.recordings') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-video"></i>
                        </span>
                        <span class="hide-menu capitalize">Recordings</span>
                    </a>
                </li>
            @endcan

            @can('student-homework')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.homework')) active @endif"
                        href="{{ route('admin.student.homework') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-notes"></i>
                        </span>
                        <span class="hide-menu capitalize">HomeWork</span>
                    </a>
                </li>
            @endcan
            @can('student-certificates')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.certificate')) active @endif"
                        href="{{ route('admin.student.certificates') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-certificate"></i>
                        </span>
                        <span class="hide-menu capitalize">Certificates</span>
                    </a>
                </li>
            @endcan

            @can('student-tournaments')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.tournaments')) active @endif"
                        href="{{ route('admin.student.tournaments') }}" aria-expanded="false">
                        <span>
                            <i class="fas fa-trophy"></i> <!-- Changed icon to Font Awesome trophy -->
                        </span>
                        <span class="hide-menu capitalize">Tournaments</span>
                    </a>
                </li>
            @endcan

            @can('student-masterclasses')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.masterclasses')) active @endif"
                        href="{{ route('admin.student.masterclasses') }}" aria-expanded="false">
                        <span>
                            <i class="fas fa-chess"></i> <!-- Changed icon to Font Awesome chess -->
                        </span>
                        <span class="hide-menu capitalize">Master Classes</span>
                    </a>
                </li>
            @endcan

            @can('student-fees')
                <li class="sidebar-item">
                    <a class="sidebar-link @if (Route::is('admin.student.fees')) active @endif"
                        href="{{ route('admin.student.fees') }}" aria-expanded="false">
                        <span>
                            <i class="fas fa-chess"></i> <!-- Changed icon to Font Awesome chess -->
                        </span>
                        <span class="hide-menu capitalize">Fees Details</span>
                    </a>
                </li>
            @endcan
        </ul>
    @endif
</nav>
