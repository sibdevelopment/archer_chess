@extends('layouts.admin')
@section('title')
   Demo Recordings
@endsection
@section('content')
    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Recordings</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/admin/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Recordings</li>
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
                        <h3 class="fw-semibold">Recordings</h3>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead>
                            <tr class="text-muted fw-semibold">
                                <th scope="col" class="ps-0">Demo</th>
                                {{-- <th scope="col">Ind Date</th>
                                <th scope="col">Ind Time</th> --}}
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Level</th>
                                <th scope="col">Class</th>
                                <th scope="col">Coach</th>
                                <th scope="col">Link</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            @if (!empty($demoRecordings))
                                @foreach ($demoRecordings as $recording)
                                    @php
                                        // dd(Carbon\Carbon::parse($attendance->date)->format('Y-m-d'));
                                        $latestCoachAttendance = App\Models\CoachAttendance::where(
                                            'batch_id',
                                            $recording->batch_id,
                                        )
                                            ->where('coach_id', $recording->coach_id)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">
                                                        @if (!empty($recording->batch) && !empty($recording->batch))
                                                            {{ $recording->batch->name }}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
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
                                        {{-- <td>
                                            <p class="mb-0 fs-3">
                                                {{ isset($recording->date)
                                                    ? convertTimeZomeWiseDate(
                                                        \Carbon\Carbon::parse($recording->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($recording->time)->format('g:i A'),
                                                        $recording['student_id'],
                                                    )
                                                    : '' }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fs-3">
                                                {{ isset($recording->date)
                                                    ? convertTimeZomeWiseTime(
                                                        \Carbon\Carbon::parse($recording->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($recording->time)->format('g:i A'),
                                                        $recording['student_id'],
                                                    )
                                                    : '' }}

                                            </p>
                                        </td> --}}
                                        <td>
                                            <p class="fs-3 text-dark mb-0">
                                                @if (!empty($recording->batch->level) && !empty($recording->batch->level))
                                                    {{ $recording->batch->level->name }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="fs-3 text-dark mb-0">
                                                @if (!empty($recording->chapter_name))
                                                    {{ $recording->chapter_name }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="fs-3 text-dark mb-0">
                                                @if (!empty($recording->coach) && !empty($recording->coach->user))
                                                    {{ $recording->coach->user->full_name }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            @if (!empty($recording->recording_link))
                                                <a href="{{ !empty($recording->recording_link) ? $recording->recording_link : '#' }}"
                                                    target="_blank">
                                                    <button class="btn btn-outline-danger py-1 px-2 ms-auto">Watch
                                                        Video</button>
                                                </a>
                                            @else
                                                <p class="fs-3 text-dark mb-0">No Recording. Please wait.</p>
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
