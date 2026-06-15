@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="card p-3 shadow-sm">
        <h4 class="fs-5">Student Details</h4>
        <div class="row">
            <form class="row align-items-end mb-4 justify-content-end">
                <!-- From Date -->
                <div class="col-md-3">
                    <div class="input-group">
                        <input name="date" id="date" type="text" class="form-control daterange"
                            placeholder="Select a lead date range" />
                        <span class="input-group-text">
                            <i class="ti ti-calendar fs-5"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="order_country" id="order_country" class="form-select form-control">
                        aria-label=".form-select-sm example">
                        <option value="">Select Country</option>
                        <option value="USA">USA</option>
                        <option value="CANADA">CANADA</option>
                        <option value="AUSTRALIA">AUSTRALIA</option>
                        <option value="NEWZEALAND">NEW ZEALAND</option>
                        <option value="INDIA">INDIA</option>
                        <option value="UAE">UAE</option>
                        <option value="UK">UK</option>
                        <option value="SINGAPORE">SINGAPORE</option>
                        <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                        <option value="EUROPEAN UNION">EUROPEAN UNION</option>
                        <option value="QATAR">QATAR</option>
                        <option value="BAHRAIN">BAHRAIN</option>
                        <option value="KUWAIT">KUWAIT</option>
                        <option value="OMAN">OMAN</option>
                    </select>
                </div>
            </form>
            <!-- Stat Card 1 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid red; background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">500</h2>
                            <p class="mb-0 text-black">Total Student</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid red; background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">80000₹</i></h2>
                            <p class="mb-0 text-black">Total Student Fees</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid red; background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">500</h2>
                            <p class="mb-0 text-black">Total Batches</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid red; background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">500</h2>
                            <p class="mb-0 text-black">Total Batches</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Card 5 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid red; background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">500</h2>
                            <p class="mb-0 text-black">Total Student</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Card 6 -->
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-danger" style="border: 2px solid rgb(255, 0, 0); background-color: white;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold text-black">500</h2>
                            <p class="mb-0 text-black">Total Student</p>
                        </div>
                        <div>
                            <i class="ti ti-user fs-8 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section>
        <!-- Graph User Vise -->
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Graph User Vise</h5>
                            <div class="d-flex gap-2">
                                <!-- Date Range Filter -->
                                <div class="input-group input-group-sm" style="max-width: 180px;">
                                    <input name="date" id="date" type="text"
                                        class="form-control form-control-sm daterange" placeholder="Date Range" />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-6"></i>
                                    </span>
                                </div>

                                <!-- Country Dropdown Filter -->
                                <select name="order_country" id="order_country" class="form-select form-control"
                                    style="max-width: 150px;">
                                    <option value="">Country</option>
                                    <option value="USA">USA</option>
                                    <option value="CANADA">CANADA</option>
                                    <option value="AUSTRALIA">AUSTRALIA</option>
                                    <option value="NEWZEALAND">NEW ZEALAND</option>
                                    <option value="INDIA">INDIA</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                    <option value="SINGAPORE">SINGAPORE</option>
                                    <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                </select>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div id="chart-line-basic"></div>
                    </div>
                </div>
            </div>
            <!-- Order Graph -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Order Graph</h5>
                            <div class="d-flex gap-2">
                                <!-- Date Range Filter -->
                                <div class="input-group input-group-sm" style="max-width: 180px;">
                                    <input name="order_date" id="order_date" type="text"
                                        class="form-control form-control-sm daterange" placeholder="Date Range" />
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar fs-6"></i>
                                    </span>
                                </div>

                                <select name="order_country" id="order_country" class="form-select form-control"
                                    style="max-width: 150px;">
                                    <option value="">Country</option>
                                    <option value="USA">USA</option>
                                    <option value="CANADA">CANADA</option>
                                    <option value="AUSTRALIA">AUSTRALIA</option>
                                    <option value="NEWZEALAND">NEW ZEALAND</option>
                                    <option value="INDIA">INDIA</option>
                                    <option value="UAE">UAE</option>
                                    <option value="UK">UK</option>
                                    <option value="SINGAPORE">SINGAPORE</option>
                                    <option value="SOUTH AFRICA">SOUTH AFRICA</option>
                                </select>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div id="chart-line-zoomable"></div>
                    </div>
                </div>
            </div>

        </div>
    </section>



    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/backend/dist/libs/bootstrap-material-datetimepicker/node_modules/moment/moment.js"></script>
    <script src="/backend/dist/libs/daterangepicker/daterangepicker.js"></script>

    <script>
        $(".daterange").daterangepicker();
        $(document).ready(function() {
            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            $('#date').val('');
            $('#demo_date').val('');
            $('#country').select2();
        });
    </script>
    <script src="/backend/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/backend/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/backend/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/backend/dist/js/app.min.js"></script>
    <script src="/backend/dist/js/app.init.js"></script>
    <script src="/backend/dist/js/app-style-switcher.js"></script>
    <script src="/backend/dist/js/sidebarmenu.js"></script>
    <script src="/backend/dist/js/custom.js"></script>
    <script src="/backend/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="/backend/dist/js/apex-chart/apex.line.init.js"></script>
@endsection
