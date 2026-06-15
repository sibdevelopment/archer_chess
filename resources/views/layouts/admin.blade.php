<!DOCTYPE html>
<html lang="en">

<head>
    <!-- TITLE -->
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="/frontend/tcul_img/home/archer_favicon.png">

    <!-- META -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    
    <!-- <link rel="shortcut icon" type="image/png" href="/favicon.png" /> -->

    <!-- BASIC CSS -->
    <link id="themeColors" rel="stylesheet" href="/backend/dist/css/style.min.css" />
    <!-- TECHNICUL CSS -->
    <link id="themeColors" rel="stylesheet" href="/backend/dist/css/techincul.css" />
    <link id="themeColors" rel="stylesheet" href="/backend/dist/css/adminstyle2.css" />
    <!-- CAROUSEL -->
    <link rel="stylesheet" href="/backend/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">
    <!-- MAIN JQUERY FILE -->
    <script src="/backend/dist/libs/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="/backend/dist/libs/daterangepicker/daterangepicker.css">
    <!-- DATATABLE -->
    <link rel="stylesheet" href="/backend/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <script src="/backend/dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="/backend/dist/js/datatable/datatable-advanced.init.js"></script>
    <!-- SELECT2 -->
    <link rel="stylesheet" href="/backend/dist/libs/select2/dist/css/select2.min.css">
    <!-- SWITCH -->
    <link rel="stylesheet" href="/backend/dist/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css">

    <!-- Other head content -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 40px !important;
            user-select: none;
            -webkit-user-select: none;
        }
    </style>

    <style>
        .custom-color {
            color: #FF4D4D;
        }

        .custom-bg-color {
            background-color: #FF4D4D;
        }
    </style>
    <style>
        /* Base styling for the holiday container */
        .holiday-container {
            background-color: #ffffff;
            /* White background for clean look */
            border-radius: 12px;
            /* Rounded corners */
            padding: 15px;
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
            /* Soft shadow for depth */
            /* margin-top: 30px; */
            transition: all 0.3s ease-in-out;
        }

        /* Title Styling */
        .holiday-title {
            font-size: 28px;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 8px;
        }

        /* Holiday List Styling */
        .holiday-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Individual holiday item styling */
        .holiday-item {
            display: flex;
            align-items: flex-start;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            border-left: 5px solid #007bff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .holiday-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Number Styling */
        .holiday-number {
            font-size: 20px;
            color: #007bff;
            font-weight: 600;
            margin-right: 20px;
        }

        /* Holiday Details Styling */
        .holiday-details {
            flex-grow: 1;
        }

        /* Holiday Name Styling */
        .holiday-name {
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        /* Holiday Date Styling */
        .holiday-date {
            font-size: 14px;
            color: #888;
            font-style: italic;
            margin-top: 5px;
        }

        /* Description Styling */
        .holiday-description p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        /* "Read More" Link Styling */
        .read-more {
            color: #007bff;
            font-weight: bold;
            cursor: pointer;
            text-decoration: underline;
        }

        .read-more:hover {
            color: #0056b3;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 12px;
            /* Rounded corners for modal */
            background-color: #fff;
            border: none;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            /* Modal shadow */
        }

        .modal-header {
            border-bottom: 1px solid #f1f1f1;
        }

        .modal-body {
            padding: 20px;
            font-size: 16px;
            color: #333;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
        }

        .modal-footer .btn-secondary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .modal-footer .btn-secondary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .holiday-container {
                padding: 20px;
            }

            .holiday-title {
                font-size: 24px;
            }

            .holiday-item {
                flex-direction: column;
                padding: 12px 15px;
            }

            .holiday-number {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .holiday-name {
                font-size: 16px;
            }

            .holiday-date {
                font-size: 12px;
            }

            .holiday-description p {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.dashboard.index') }}" class="text-nowrap logo-img">
                        <img src="/backend/images/ArcherKids-logo.png" width="180" alt="" />
                    </a>
                    <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div>
                </div>
                @include('layouts.admin.navbar')
                <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
                    <div class="hstack gap-3">
                        <div class="john-img">
                            <img src="/backend/dist/images/profile/user-1.jpg" class="rounded-circle" width="40"
                                height="40" alt="">
                        </div>
                        <div class="john-title">
                            <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                            <span class="fs-2 text-dark">Designer</span>
                        </div>
                        <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                            aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                            <i class="ti ti-power fs-6"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>
        <div class="body-wrapper">
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="ti ti-search"></i>
                            </a>
                        </li>
                    </ul>

                    <div class="d-block d-lg-none">
                        <img src="/backend/images/ArcherKids-logo.png" width="180" alt="" />
                    </div>
                    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="p-2">
                            <i class="ti ti-dots fs-7"></i>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="javascript:void(0)"
                                class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                aria-controls="offcanvasWithBothOptions">
                                <i class="ti ti-align-justified fs-7"></i>
                            </a>
                            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                                <li class="nav-item dropdown">
                                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="d-flex align-items-center">
                                            <div class="user-profile-img">
                                                <img src="/backend/dist/images/profile/user-1.jpg"
                                                    class="rounded-circle" width="35" height="35"
                                                    alt="" />
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                        aria-labelledby="drop1">
                                        <div class="profile-dropdown position-relative" data-simplebar>
                                            <div class="py-3 px-7 pb-0">
                                                <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                            </div>
                                            <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                <img src="/backend/dist/images/profile/user-1.jpg"
                                                    class="rounded-circle" width="80" height="80"
                                                    alt="" />
                                                <div class="ms-3">
                                                    <h5 class="mb-1 fs-3">
                                                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                                    </h5>
                                                    <span class="mb-1 d-block text-dark">
                                                        @foreach (Auth::user()->getRoleNames() as $role)
                                                            {{ $role }}
                                                        @endforeach
                                                    </span>
                                                    <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                                        <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email }}
                                                    </p>
                                                    <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                                        <i class="ti ti-phone fs-4"></i> {{ Auth::user()->mobile }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-grid py-4 px-7 pt-8">
                                                <a href="#" class="btn btn-outline-primary"
                                                    onclick="$('#logout-form').submit();">Log Out</a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <div class="container-fluid">
                @yield('content')
            </div>
            <div id="chart" style="display:none;"></div>
            <div id="breakup" style="display:none;"></div>
            <div id="earning" style="display:none;"></div>
            <div id="salary" style="display:none;"></div>
            <div id="customers" style="display:none;"></div>
            <div id="projects" style="display:none;"></div>
            <div id="stats" style="display:none;"></div>
        </div>
    </div>


    <div class="modal fade" id="MasterclassAttendanceModal" tabindex="-1" role="dialog"
        aria-labelledby="MasterclassAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header border-1">
                    <h5 class="modal-title" id="MasterclassAttendanceModalLabel">Mark Attendance (Masterclass)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="MasterclassAttendanceData">
                    <form id="MasterclassAttendanceForm" method="POST">
                        <input type="hidden" name="masterclass_id" id="masterclass_id"
                            placeholder="Enter Masterclass ID">
                        <p id="body-text"></p>
                    </form>
                    <div class="row mt-3">
                        <div class="col-6">
                            <input type="text" class="form-control" name="homework_link" id="homework_link_1"
                                value="" placeholder="Homework Link" />
                            <div id="homework_link-error" style="color:red"></div>
                        </div>

                        {{-- <div class="col-6">
                            <input type="text" class="form-control" name="recording_link" id="recording_link_1"
                                value="" placeholder="Recording Link" />
                            <div id="recording_link-error" style="color:red"></div>
                        </div> --}}
                        <div class="col-6">
                            <input type="text" class="form-control" name="chapter_name" id="chapter_name_1"
                                value="" placeholder="Chapter Name" />
                            <div id="chapter_name-error" style="color:red"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-1">
                    <button type="button" class="btn bg-light-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="MasterclassAttendanceForm"
                        class="btn btn-primary masterclass_submit_btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fees Record Modal -->
    <div class="modal fade" id="feesRecordModal" tabindex="-1" aria-labelledby="feesRecordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feesRecordModalLabel">View Fees Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="feesYear" class="form-label">Select Year</label>
                            <select id="feesYear" class="form-select" required>
                                <option value="">-- Select Year --</option>
                                @foreach (range(date('Y'), 2024) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="feesMonth" class="form-label">Select Month</label>
                            <select id="feesMonth" class="form-select" required>
                                <option value="">-- Select Month --</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="feesCountry" class="form-label">Select Country</label>
                            <select id="feesCountry" class="form-select" required>
                                <option value="">-- Select Country --</option>
                                <option value="ALL">ALL</option>
                                <option value="USA">USA</option>
                                <option value="CANADA">CANADA</option>
                                <option value="AUSTRALIA">AUSTRALIA</option>
                                <option value="NEWZEALAND">NEW ZEALAND</option>
                                <option value="INDIA">INDIA</option>
                                <option value="UAE">UAE</option>
                                <option value="UK">UK</option>
                                <option value="SINGAPORE">SINGAPORE</option>
                                <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                <option value="QATAR">QATAR</option>
                                <option value="BAHRAIN">BAHRAIN</option>
                                <option value="KUWAIT">KUWAIT</option>
                                <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                                <option value="OMAN">OMAN</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="feesSubmitBtn" class="btn btn-primary">Open Record</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Fees Record Modal -->
    <div class="modal fade" id="cancelBatchListModal" tabindex="-1" aria-labelledby="cancelBatchListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelBatchListModalLabel">Cancel Batch List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="feesMonth" class="form-label">From Date</label>
                            <input type="date" id="fromDate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="feesMonth" class="form-label">To Date</label>
                            <input type="date" id="toDate" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="cancelBatchListBtn" class="btn btn-primary">Open List</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    {{-- Fees Collection Model --}}
    <div class="modal fade" id="feesCollectionModal" tabindex="-1" aria-labelledby="feesCollectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title" id="feesCollectionModalLabel">View Fees Collection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="collectionYear" class="form-label">Year</label>
                            <input type="number" id="collectionYear" class="form-control" placeholder="e.g. 2025"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="collectionMonth" class="form-label">Month</label>
                            <select id="collectionMonth" class="form-select" required>
                                <option value="">-- Select Month --</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="collectionDate" class="form-label">Date</label>
                            <input type="number" id="collectionDate" class="form-control" placeholder="e.g. 15"
                                min="1" max="31" required>
                        </div>

                        <div class="mb-3">
                            <label for="collectionCountry" class="form-label">Country (optional)</label>
                            <select id="collectionCountry" class="form-select">
                                <option value="">-- Select Country --</option>
                                <option value="USA">USA</option>
                                <option value="CANADA">CANADA</option>
                                <option value="AUSTRALIA">AUSTRALIA</option>
                                <option value="NEWZEALAND">NEW ZEALAND</option>
                                <option value="INDIA">INDIA</option>
                                <option value="UAE">UAE</option>
                                <option value="UK">UK</option>
                                <option value="SINGAPORE">SINGAPORE</option>
                                <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                <option value="QATAR">QATAR</option>
                                <option value="BAHRAIN">BAHRAIN</option>
                                <option value="KUWAIT">KUWAIT</option>
                                <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                                <option value="OMAN">OMAN</option>
                                <!-- Add more as needed -->
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="feesCollectionBtn" class="btn btn-primary">Open
                            Collection</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Student Inactive Record Modal -->
    <div class="modal fade" id="studentInactiveListModal" tabindex="-1" aria-labelledby="studentInactiveListModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentInactiveListModal">View Fees Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="studentInactiveMonth" class="form-label">Select Month</label>
                            <select id="studentInactiveMonth" class="form-select" required>
                                <option value="">-- Select Month --</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="studentInactiveSubmitBtn" class="btn btn-primary">Open
                            Record</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>



    @include('layouts.admin.modals')

    <!-- BOOTSTRAP -->
    <script src="/backend/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- APP -->
    <script src="/backend/dist/js/app.min.js"></script>
    <script src="/backend/dist/js/app.init.js"></script>
    <script src="/backend/dist/js/app-style-switcher.js"></script>

    <!-- CUSTOM -->
    <script src="/backend/dist/js/custom.js"></script>

    <!-- SIMPLEBAR -->
    <script src="/backend/dist/libs/simplebar/dist/simplebar.min.js"></script>

    <!-- SIDEBAR -->
    <script src="/backend/dist/js/sidebarmenu.js"></script>

    <!-- CAROUSEL -->
    <script src="/backend/dist/libs/owl.carousel/dist/owl.carousel.min.js"></script>

    <!-- CHART -->
    <script src="/backend/dist/libs/apexcharts/dist/apexcharts.min.js"></script>

    <!-- DASHBOARD -->
    <script src="/backend/dist/js/dashboard.js"></script>

    <!-- TOASTR -->
    <script src="/backend/dist/js/plugins/toastr-init.js"></script>

    <!-- SELECT2 -->
    <script src="/backend/dist/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="/backend/dist/libs/select2/dist/js/select2.min.js"></script>
    <script src="/backend/dist/js/forms/select2.init.js"></script>

    <!-- SWITCH -->
    <script src="/backend/dist/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
    <script src="/backend/dist/js/forms/bootstrap-switch.js"></script>

    <script>
        $(document).on('click', '.masterclass_attendance_btn', function() {
            event.preventDefault(); // Prevent the default action (scrolling to the top)

            var masterclassId = $(this).data('id');
            var text = $(this).data('text');
            $('#masterclass_id').val(masterclassId);
            $('#body-text').text(text);
            $('#MasterclassAttendanceModal').modal('show');
        });


        $(document).on('submit', '#MasterclassAttendanceForm', function(e) {
            e.preventDefault();
            var masterclassId = $('#masterclass_id').val();

            if (!masterclassId) {
                toastr.error('Masterclass ID cannot be empty.', '', {
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    timeOut: 1500,
                    closeButton: true,
                });
                return;
            }

            $.ajax({
                url: '{!! route('admin.dashboard.mark.master.class.attendance') !!}', // Replace with your endpoint URL
                type: 'POST',
                data: {
                    masterclassId: masterclassId,
                    homework_link: $('#homework_link_1').val(),
                    // recording_link: $('#recording_link_1').val(),
                    chapter_name: $('#chapter_name_1').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                        $('#MasterclassAttendanceModal').modal('hide');

                        window.location.reload();
                    } else {
                        toastr.error(response.message || 'There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#' + key + '-error').html(value);
                        });
                        $('html, body').animate({
                            scrollTop: $('#' + Object.keys(xhr.responseJSON
                                .errors)[0] + '-error').offset().top - 200
                        }, 500);
                    }

                    toastr.error(xhr.responseJSON.message || error ||
                        'There is some error!!', '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,

                        });
                }
            });
        });

        $(document).on('click', '.masterclass-attendance', function(e) {
            e.preventDefault();

            let zoomUrl = $(this).data('zoom-url');
            let attendanceUrl = $(this).data('attendance-url');
            let coachId = $(this).data('coach-id');
            let masterclassId = $(this).data('masterclass-id');
            $.ajax({
                type: "POST",
                url: attendanceUrl,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    coach_id: coachId,
                    id: masterclassId
                },
                success: function(data) {
                    if (data.status === 'success') {
                        toastr.success(data.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });

                        setTimeout(function() {
                            window.open(zoomUrl, '_blank');
                        }, 1000);
                    } else {
                        toastr.error(data.message || 'Error marking attendance.');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || xhr.responseJSON?.error ||
                        'An error occurred';
                    toastr.error(message);
                }
            });
        });



        var loaderImg = $('<img width="50" height="50" src="/Loading_image_1.gif" id="loaderImage"/>');

        loaderImg.hide();

        $(document).on('submit', 'form', function(e) {
            var btn = $(this).find("button[type=submit]");
            btn.hide();
            btn.after(loaderImg);
            loaderImg.show();
        });

        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            $('#loaderImage').hide();
            var btn = $(document).find("button[type=submit]");
            btn.show();
        });

        $(document).ajaxComplete(function(event, jqxhr, settings) {
            $('#loaderImage').hide();
            var btn = $(document).find("button[type=submit]");
            btn.show();
        });
    </script>

    {{-- //fees record JS --}}
    <script>
        $('#feesSubmitBtn').on('click', function() {
            const year = $('#feesYear').val();
            const month = $('#feesMonth').val();
            const country = $('#feesCountry').val();


            if (!month || !country || !year) {
                alert('Please select both month and country and year.');
                return;
            }

            // Add loader image
            const loaderImg = $('<img width="50" height="50" src="/Loading_image_1.gif" id="loaderImage"/>');
            $(this).hide().after(loaderImg);

            const url = `{{ url('/fees_records') }}/${year}/${month}/${country}`;
            window.open(url, '_blank');

            // Reset dropdowns
            $('#feesMonth').val('');
            $('#feesCountry').val('');

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('feesRecordModal'));
            modal.hide();

            // Reset button and remove loader
            $('#feesSubmitBtn').show();
            $('#loaderImage').remove();
        });

        $('#cancelBatchListBtn').on('click', function() {
            const startDate = $('#fromDate').val();
            const endDate = $('#toDate').val();

            if (!startDate || !endDate) {
                alert('Please select both From Date and To Date.');
                return;
            }

            // Add loader image
            const loaderImg = $('<img width="50" height="50" src="/Loading_image_1.gif" id="loaderImage"/>');
            $(this).hide().after(loaderImg);

            const url = `{{ url('/cancel_batches') }}/${startDate}/${endDate}`;
            window.open(url, '_blank');

            // Reset dropdowns
            $('#startDate').val('');
            $('#endDate').val('');

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('cancelBatchListModal'));
            modal.hide();

            // Reset button and remove loader
            $('#cancelBatchListBtn').show();
            $('#loaderImage').remove();
        });
    </script>

    {{-- //fees collection JS --}}
    <script>
        $('#feesCollectionBtn').on('click', function() {
            const year = $('#collectionYear').val();
            const month = $('#collectionMonth').val();
            const date = $('#collectionDate').val();
            const country = $('#collectionCountry').val();

            if (!year || !month || !date) {
                alert('Please enter Year, Month, and Date.');
                return;
            }

            // Add loader
            const loader = $('<img>', {
                src: '/Loading_image_1.gif',
                width: 40,
                height: 40,
                id: 'loaderImage',
                style: 'margin-left: 10px;'
            });
            $(this).hide().after(loader);

            const url = `{{ url('/fees_collection') }}/${year}/${month}/${date}${country ? '/' + country : ''}`;
            window.open(url, '_blank');

            // Reset inputs
            $('#collectionYear').val('');
            $('#collectionMonth').val('');
            $('#collectionDate').val('');
            $('#collectionCountry').val('');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('feesCollectionModal'));
            modal.hide();

            // Cleanup
            $(this).show();
            $('#loaderImage').remove();
        });
    </script>

    {{-- //Student Inactive JS --}}
    <script>
        $('#studentInactiveSubmitBtn').on('click', function() {
            const month = $('#studentInactiveMonth').val();

            if (!month) {
                alert('Please select month.');
                return;
            }

            // Add loader image
            const loaderImg = $('<img width="50" height="50" src="/Loading_image_1.gif" id="loaderImage"/>');
            $(this).hide().after(loaderImg);

            const url = `{{ url('/student_inactive') }}/${month}`;
            window.open(url, '_blank');

            // Reset dropdowns
            $('#studentInactiveMonth').val('');

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('studentInactiveListModal'));
            modal.hide();

            // Reset button and remove loader
            $('#studentInactiveSubmitBtn').show();
            $('#loaderImage').remove();
        });
    </script>


</body>

</html>
