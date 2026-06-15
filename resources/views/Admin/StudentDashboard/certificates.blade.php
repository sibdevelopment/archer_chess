@extends('layouts.admin')
@section('title')
    Certificates
@endsection
@section('content')

<style>
    .image-container {
        position: relative;
    }

    /* .blurred-image {
        filter: blur(8px);
    } */

    /* .icon-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    } */

    /* .lock-icon {
        font-size: 50px;
        color: #000;
    } */
</style>

    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Certificates</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/admin/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Certificates</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../backend/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                    <div style="position: relative; display: inline-block;">
                        <img src="/backend/tcul-imgs/bl_1.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                        <span style="position: absolute; top:48%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                            <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                        </span>
                        @if($certificatesLevel['level_1'] == false)
                            <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Beginner Level</h6>
                        </div>
                        @if($certificatesLevel['level_1'] == true)
                        <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'BL']) }}" style="">
                            <i class="ti ti-download" style="font-size: 20px;"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                        <div style="position: relative; display: inline-block;">
                            <img src="/backend/tcul-imgs/iml_1.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                            <span style="position: absolute; top:53%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                                <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                            </span>
                            @if($certificatesLevel['level_2'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Intermediate</h6>
                        </div>
                        @if($certificatesLevel['level_2'] == true)
                            <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'IML_1']) }}">
                                <i class="ti ti-download" style="font-size: 20px;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                        <div style="position: relative; display: inline-block;">
                            <img src="/backend/tcul-imgs/iml_2.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                            <span style="position: absolute; top:53%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                                <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                            </span>
                            @if($certificatesLevel['level_3'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Advance Level</h6>
                            {{-- <span class="text-dark fs-2">Wed, Dec 14, 2023</span> --}}
                        </div>
                        @if($certificatesLevel['level_3'] == true)
                            <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'IML_2']) }}">
                                <i class="ti ti-download" style="font-size: 20px;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                        <div style="position: relative; display: inline-block;">
                            <img src="/backend/tcul-imgs/Advanced_level_1.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                            <span style="position: absolute; top:51%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                                <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                            </span>
                            @if($certificatesLevel['level_4'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Advance Level 2</h6>
                        </div>
                        @if($certificatesLevel['level_4'] == true)
                            <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'Advanced_level_1']) }}">
                                <i class="ti ti-download" style="font-size: 20px;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                        <div style="position: relative; display: inline-block;">
                            <img src="/backend/tcul-imgs/Advanced_level_2.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                            <span style="position: absolute; top:56%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                                <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                            </span>
                            @if($certificatesLevel['level_5'] == false)
                                <!-- Lock icon overlay -->
                               <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Expert-1 (Module-1)</h6>
                        </div>
                        @if($certificatesLevel['level_5'] == true)
                            <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'Advanced_level_2']) }}">
                                <i class="ti ti-download" style="font-size: 20px;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card hover-img overflow-hidden rounded-2">
                <div class="card-body p-0">
                        <div style="position: relative; display: inline-block;">
                            <img src="/backend/tcul-imgs/Advanced_level_3.jpg" alt="" class="img-fluid w-100 object-fit-cover" style="height: 250px;">
                            <span style="position: absolute; top:52%; left: 50%; transform: translate(-50%, -50%); color: rgb(1, 1, 1); font-size: 12px; white-space: nowrap;">
                                <b>{{ isset($student->full_name) ? $student->full_name : '' }}</b>
                            </span>
                            @if($certificatesLevel['level_6'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">Expert-1 (Module-2)</h6>
                        </div>
                        @if($certificatesLevel['level_6'] == true)
                            <a target="_blank" href="{{ route('admin.student.certificates.pdf', ['student' => $student->id ,'level'=>'Advanced_level_3']) }}">
                                <i class="ti ti-download" style="font-size: 20px;"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
