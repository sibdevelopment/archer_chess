@extends('layouts.admin')
@section('title')
    Certificates
@endsection
@section('content')

<style>
    .image-container {
        position: relative;
    }

    .certificate-preview {
        height: 360px;
        width: 100%;
        object-fit: contain;
        background: #f8f4ea;
    }

    .certificate-name {
        position: absolute;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #0d2246;
        font-size: 12px;
        white-space: nowrap;
        font-weight: 700;
        text-align: center;
        width: 80%;
    }

    .certificate-date {
        position: absolute;
        transform: translate(-50%, -50%);
        color: #0d2246;
        font-size: 9px;
        white-space: nowrap;
        font-weight: 700;
        text-align: center;
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
                        <img src="/backend/tcul-imgs/bl_1.jpg" alt="" class="certificate-preview">
                        <span class="certificate-name" style="top: {{ $certificateDefinitions['BL']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                        <span class="certificate-date" style="top: {{ $certificateDefinitions['BL']['date_top'] }}; left: {{ $certificateDefinitions['BL']['date_left'] }};">{{ $certificateIssueDates['BL'] ?? '' }}</span>
                        @if($certificatesLevel['level_1'] == false)
                            <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['BL']['label'] }}</h6>
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
                            <img src="/backend/tcul-imgs/iml_1.jpg" alt="" class="certificate-preview">
                            <span class="certificate-name" style="top: {{ $certificateDefinitions['IML_1']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                            <span class="certificate-date" style="top: {{ $certificateDefinitions['IML_1']['date_top'] }}; left: {{ $certificateDefinitions['IML_1']['date_left'] }};">{{ $certificateIssueDates['IML_1'] ?? '' }}</span>
                            @if($certificatesLevel['level_2'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['IML_1']['label'] }}</h6>
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
                            <img src="/backend/tcul-imgs/iml_2.jpg" alt="" class="certificate-preview">
                            <span class="certificate-name" style="top: {{ $certificateDefinitions['IML_2']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                            <span class="certificate-date" style="top: {{ $certificateDefinitions['IML_2']['date_top'] }}; left: {{ $certificateDefinitions['IML_2']['date_left'] }};">{{ $certificateIssueDates['IML_2'] ?? '' }}</span>
                            @if($certificatesLevel['level_3'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['IML_2']['label'] }}</h6>
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
                            <img src="/backend/tcul-imgs/Advanced_level_1.jpg" alt="" class="certificate-preview">
                            <span class="certificate-name" style="top: {{ $certificateDefinitions['Advanced_level_1']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                            <span class="certificate-date" style="top: {{ $certificateDefinitions['Advanced_level_1']['date_top'] }}; left: {{ $certificateDefinitions['Advanced_level_1']['date_left'] }};">{{ $certificateIssueDates['Advanced_level_1'] ?? '' }}</span>
                            @if($certificatesLevel['level_4'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['Advanced_level_1']['label'] }}</h6>
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
                            <img src="/backend/tcul-imgs/Advanced_level_2.jpg" alt="" class="certificate-preview">
                            <span class="certificate-name" style="top: {{ $certificateDefinitions['Advanced_level_2']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                            <span class="certificate-date" style="top: {{ $certificateDefinitions['Advanced_level_2']['date_top'] }}; left: {{ $certificateDefinitions['Advanced_level_2']['date_left'] }};">{{ $certificateIssueDates['Advanced_level_2'] ?? '' }}</span>
                            @if($certificatesLevel['level_5'] == false)
                                <!-- Lock icon overlay -->
                               <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['Advanced_level_2']['label'] }}</h6>
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
                            <img src="/backend/tcul-imgs/Advanced_level_3.jpg" alt="" class="certificate-preview">
                            <span class="certificate-name" style="top: {{ $certificateDefinitions['Advanced_level_3']['name_top'] }};">{{ isset($student->full_name) ? $student->full_name : '' }}</span>
                            <span class="certificate-date" style="top: {{ $certificateDefinitions['Advanced_level_3']['date_top'] }}; left: {{ $certificateDefinitions['Advanced_level_3']['date_left'] }};">{{ $certificateIssueDates['Advanced_level_3'] ?? '' }}</span>
                            @if($certificatesLevel['level_6'] == false)
                                <!-- Lock icon overlay -->
                                <div class="icon-overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5);">
                                    <i class="fas fa-lock lock-icon" style="font-size: 50px; color: rgba(255, 255, 255, 0.8);"></i>
                                </div>
                            @endif
                        </div>
                    <div class="p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-semibold mb-0 fs-4">{{ $certificateDefinitions['Advanced_level_3']['label'] }}</h6>
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
