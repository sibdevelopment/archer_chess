@extends('layouts.admin')
@section('title')
    Access Denied
@endsection
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <div class="container-fluid">
        @php
            $day = \Carbon\Carbon::now()->format('D'); // e.g., FRI
            $month = \Carbon\Carbon::now()->format('M'); // e.g., MAY
            $year = \Carbon\Carbon::now()->format('Y'); // e.g., 2025
        @endphp
        <style>
            .calendar-icon {
                position: relative;
                width: 50px;
                height: 50px;
            }

            .calendar-icon i {
                font-size: 2rem;
            }

            .calendar-text {
                position: absolute;
                top: 6px;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 0.65rem;
                font-weight: bold;
                color: white;
                text-transform: uppercase;
            }
        </style>
        <div class="row">
            <!-- Left Column: Plain Layout -->
            <div class="col-md-6 mb-5">
                <h4 class="mb-4 fw-bold text-dark">Collection</h4>

                <!-- Date Selection -->
                <div class="mb-4 d-flex justify-content-between">
                    <div class="me-2 w-50">
                        <label for="start_date1" class="form-label fw-semibold">Start Date</label>
                        <input type="date" id="start_date1" class="form-control rounded shadow-sm">
                    </div>
                    <div class="ms-2 w-50">
                        <label for="end_date1" class="form-label fw-semibold">End Date</label>
                        <input type="date" id="end_date1" class="form-control rounded shadow-sm">
                    </div>
                </div>

                <!-- Collection Summary Card -->
                <div class="card w-100 shadow-sm border rounded-3">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="fw-semibold mb-0">Collections (Between Selected Dates)</h6>
                    </div>
                    <div class="card-body px-4 py-3">
                        <!-- Total Collection -->
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <span class="fw-semibold text-muted">TOTAL</span>
                            <span class="fw-bold fs-5 text-dark">1,050</span>
                        </div>
                        <hr>

                        <!-- Country-wise Collections -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">India</span>
                            <span class="fw-medium text-dark">₹500</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">USA</span>
                            <span class="fw-medium text-dark">$300</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">UK</span>
                            <span class="fw-medium text-dark">£250</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body rounded-4">
            <h5 class="card-title text-dark fw-semibold">New Students</h5>
            <p class="card-subtitle mb-0">Count</p>
            <div class="row mt-2">
                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-primary">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-primary rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $day }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white py-2 px-3 rounded-pill fw-semibold">Today</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">7</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">5</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">3</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">1</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">2</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">6</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">5</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">2</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">4</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-danger">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-danger rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $month }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-danger text-white py-2 px-3 rounded-pill fw-semibold">Month</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">15</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">33</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">18</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">10</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">19</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">60</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">35</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">55</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">23</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-success">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-success rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $year }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-success text-white py-2 px-3 rounded-pill fw-semibold">Year</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">320</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">469</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">751</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">100</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">536</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">986</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">568</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">124</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">253</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card-body">
            <h5 class="card-title text-dark fw-semibold">Lead Enquiries</h5>
            <p class="card-subtitle mb-0">Count</p>
            <div class="row mt-2">
                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-primary">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-primary rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $day }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white py-2 px-3 rounded-pill fw-semibold">Today</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">7</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">5</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">3</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">1</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">2</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">6</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">5</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">2</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">4</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-danger">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-danger rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $month }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-danger text-white py-2 px-3 rounded-pill fw-semibold">Month</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">15</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">33</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">18</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">10</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">19</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">60</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">35</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">55</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">23</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-4 bg-light-success">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="calendar-icon bg-success rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar text-white"></i>
                                        <span class="calendar-text mt-3">{{ $year }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-success text-white py-2 px-3 rounded-pill fw-semibold">Year</span>
                            </div>

                            <!-- Row 1 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">Australia</p>
                                    <h5 class="fw-bold text-dark">320</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Canada</p>
                                    <h5 class="fw-bold text-dark">469</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">India</p>
                                    <h5 class="fw-bold text-dark">751</h5>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="row text-center mb-3">
                                <div class="col">
                                    <p class="text-dark mb-1">New Zealand</p>
                                    <h5 class="fw-bold text-dark">100</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Singapore</p>
                                    <h5 class="fw-bold text-dark">536</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">UAE</p>
                                    <h5 class="fw-bold text-dark">986</h5>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-dark mb-1">UK</p>
                                    <h5 class="fw-bold text-dark">568</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">US</p>
                                    <h5 class="fw-bold text-dark">124</h5>
                                </div>
                                <div class="col">
                                    <p class="text-dark mb-1">Qatar</p>
                                    <h5 class="fw-bold text-dark">253</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h5 class="card-title text-dark fw-semibold mb-0">Employee Performance Report</h5>

                    <div class="d-flex gap-3 ms-auto">
                        <!-- Lead Date Picker -->
                        <div class="input-group">
                            <input name="start_date" id="start_date" type="text" class="form-control daterange"
                                placeholder="Start Fees Date ..." />
                            <span class="input-group-text">
                                <i class="ti ti-calendar fs-5"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Employee Name</th>
                                <th>Students Generated</th>
                                <th>Lead Enquiries</th>
                                <th>Demo Leads</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $key => $employee)
                                <tr>
                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                    <td class="text-start text-dark">{{ $employee->user->first_name }}
                                        {{ $employee->user->last_name }}</td>
                                    <td>12</td>
                                    <td>8</td>
                                    <td>3</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('.daterange').daterangepicker({
                opens: 'right',
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endsection
