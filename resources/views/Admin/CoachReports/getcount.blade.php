<!-- Header End -->
<div class="card" style="margin-bottom: 0px !important;">
    <div class="card-header">
        <h5 class="card-title">This Month Report</h5>
    </div>
    <div class="card-body p-3">
        <p class="card-subtitle mb-0">Tracking progress from the 1st of this month until today, Providing an
            overview of
            achievements and activities.</p>
        <!-- ------------------------------------------------------------------------- :: -->
        <div class="container-fluid mt-4">
            <div class="row">
                <!-- ------------------------------------------------------------------------- :: -->
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-color-swatch text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="batchCompletedData" class="btn btn-danger custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Total Batches Completed</h5>
                                <span class="fs-8 fw-bold custom-color  ">{{ $completedBatchesCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------------------- :: -->
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-users text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="batchStudent" class="btn btn-danger  custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Total Batch Student </h5>
                                <span class="fs-8 fw-bold custom-color">{{ $totalStudentsBatchesCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------------------- :: -->
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-school text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="demoCompletedData" class="btn btn-danger  custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">
                                        Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Demo Student </h5>
                                <span class="fs-8 fw-bold custom-color">{{ $completedDemosCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ------------------------------------------------------------------------- :: -->
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-calendar-minus text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="leaveTakenData" class="btn btn-danger  custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">
                                        Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Leaves Approved</h5>
                                <span class="fs-8 fw-bold custom-color">{{ $approvedLeavesCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-calendar-minus text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="masterclassTakenData" class="btn btn-danger  custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">
                                        Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Masterclass</h5>
                                <span class="fs-8 fw-bold custom-color">{{ $masterclassCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-calendar-minus text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="coverupclassTakenData" class="btn btn-danger  custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">
                                        Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Coverup Class </h5>
                                <span class="fs-8 fw-bold custom-color">{{ $coverupclassCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-6 col-xl-2 mt-2">
                    <div class="card bg-light-danger shadow-none" style="margin-bottom: 0px !important;">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center">
                                <div class="round rounded custom-bg-color d-flex align-items-center justify-content-center"
                                    style="width: 30px; height: 30px;">
                                    <i class="ti ti-clock-hour-4 text-white fs-5"></i>
                                </div>
                                <div class="ms-auto text-info d-flex align-items-center">
                                    <button type="submit" id="delayedBatchesData" class="btn btn-danger custom-bg-color"
                                        data-coach-id="{{ $coachId }}" style=" height: 30px; line-height: 15px;">
                                        Details</button>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-semibold fs-3">Delayed Batches</h5>
                                <span class="fs-8 fw-bold custom-color">{{ $delayedBatchesCount }}</span>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- ------------------------------------------------------------------------- :: -->
            </div>
        </div>
        <!-- ------------------------------------------------------------------------- :: -->
        <!-- ------------------------------------------------------------------------- :: -->
    </div>
</div>
