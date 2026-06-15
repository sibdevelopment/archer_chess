@extends('layouts.admin')
@section('title')
    Homework
@endsection
@section('content')
<div class="card bg-light-danger shadow-none position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Homework</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted" href="/admin/student-dashboard">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Homework</li>
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
    <section>
        <div class="card w-100">
            <div class="card-body">
                {{-- <div class="d-sm-flex d-block align-items-center justify-content-between mb-7">
                    <div class="mb-3 mb-sm-0">
                        <h3 class="fw-semibold">Homewrok</h3>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead>
                            <tr class="text-muted fw-semibold">
                                <th scope="col" class="ps-0">Batch</th>
                                <th scope="col" class="ps-0">Class</th>
                                {{-- <th scope="col">Ind Date</th>
                                <th scope="col">Ind Time</th> --}}
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Level</th>
                                <th scope="col">Coach</th>
                                <th scope="col">Homework</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            @if(!empty($studentattendance))
                            @foreach($studentattendance as $attendance)
                            @php
                                $latestCoachAttendance = App\Models\CoachAttendance::where('batch_id', $attendance->batch_id)
                                    ->where('coach_id', $attendance->coach_id)
                                    ->first();
                                $masterclassdata = App\Models\MasterclassData::where('student_id', $attendance->student_id)->first();
                            @endphp

                            <tr>
                                <td class="ps-0">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="fw-semibold mb-1">
                                                @if(!empty($attendance->batch) && !empty($attendance->batch))
                                                    {{ $attendance->batch->name }}
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td  class="ps-0">
                                    <p class="fs-3 text-dark mb-0">
                                        @if(!empty($attendance->chapter_name))
                                        {{ $attendance->chapter_name }}
                                        @endif
                                    </p>
                                </td>
                                {{-- <td>
                                    <p class="mb-0 fs-3">
                                        {{ isset($attendance->date) ?  \Carbon\Carbon::parse($attendance->date)->format('j, M Y') : '' }}

                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0 fs-3">
                                        {{ isset($attendance->time) ? \Carbon\Carbon::parse($attendance->time)->format('g:i A') : '' }}
                                    </p>
                                </td> --}}
                                <td>
                                    <p class="mb-0 fs-3">
                                        {{ isset($attendance->date)
                                            ? convertTimeZomeWiseDate(
                                                \Carbon\Carbon::parse($attendance->date)->format('j, M Y'),
                                                \Carbon\Carbon::parse($attendance->time)->format('g:i A'),
                                                $attendance['student_id']
                                            )
                                            : '' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0 fs-3">
                                        {{ isset($attendance->date)
                                            ? convertTimeZomeWiseTime(
                                                \Carbon\Carbon::parse($attendance->date)->format('j, M Y'),
                                                \Carbon\Carbon::parse($attendance->time)->format('g:i A'),
                                                $attendance['student_id'],
                                            )
                                            : '' }}

                                    </p>
                                </td>
                                <td>
                                    <p class="fs-3 text-dark mb-0">
                                        @if(!empty($attendance->batch->level) && !empty($attendance->batch->level))
                                            {{ $attendance->batch->level->name }}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <p class="fs-3 text-dark mb-0">
                                        @if(!empty($attendance->coach) && !empty($attendance->coach->user))
                                            {{ $attendance->coach->user->full_name}}
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    @if(!empty($attendance->homework_link))
                                    <a href="{{ !empty($attendance->homework_link) ? $attendance->homework_link : '#' }}" target="_blank">
                                        <button class="btn btn-outline-danger py-1 px-2 ms-auto">Solve Quize</button>
                                    </a>
                                    @else
                                        <p class="fs-3 text-dark mb-0">No Homework Solve Quize</p>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
