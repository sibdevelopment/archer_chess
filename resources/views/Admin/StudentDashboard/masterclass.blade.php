@extends('layouts.admin')
@section('title')
    Master class
@endsection
@section('content')
    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        Master class </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/admin/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Master class</li>
                            {{-- <li class="breadcrumb-item" aria-current="page">{{ $student->country }}</li> --}}
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
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead>
                            <tr class="text-muted fw-semibold">
                                <th scope="col" class="">Master Class Name</th>
                                <th scope="col" class="text-center">Date & Time</th>
                                <th scope="col" class="text-center">Chapter Name</th>
                                <th scope="col" class="text-center">Home Work</th>
                                <th scope="col" class="text-center">Recording</th>
                                <th scope="col" class="text-center">Link</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            @if (!empty($masterclassDatas) && $masterclassDatas->isNotEmpty())
                                @foreach ($masterclassDatas as $masterclass)
                                    <tr>
                                        <td class="">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">
                                                        {{ is_array($masterclass->name) ? implode(', ', $masterclass->name) : $masterclass->name }}
                                                        {{-- {{ is_array($masterclass->country) ? implode(', ', $masterclass->country) : $masterclass->country }} --}}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                {{ isset($masterclass->time)
                                                    ? convertTimeZomeWiseTime(
                                                        \Carbon\Carbon::parse($masterclass->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($masterclass->time)->format('g:i A'),
                                                        $student->id,
                                                    )
                                                    : '' }}
                                                    <br>
                                                {{ isset($masterclass->date)
                                                    ? convertTimeZomeWiseDate(
                                                        \Carbon\Carbon::parse($masterclass->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($masterclass->time)->format('g:i A'),
                                                        $student->id,
                                                    )
                                                    : '' }}
                                            </h6>
                                        </td>
                                        @php
                                            $coach_attendance = App\Models\CoachAttendance::where('masterclass_id', $masterclass->id)
                                                ->first();
                                        @endphp
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                {{ !empty($coach_attendance) ? $coach_attendance->chapter_name : 'N/A' }}
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                @if (!empty($coach_attendance) && !empty($coach_attendance->homework_link))
                                                    <a href="{{ $coach_attendance->homework_link }}" target="_blank"
                                                        class="btn btn-primary btn-sm">
                                                        View
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                @if (!empty($coach_attendance) && !empty($coach_attendance->recording_link))
                                                    <a href="{{ $coach_attendance->recording_link }}" target="_blank"
                                                        class="btn btn-primary btn-sm">
                                                        View
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </h6>
                                        </td>
                                        @php
                                            $masterclasscurrentDateTime = Carbon\Carbon::now();
                                            $masterclassDateTime = Carbon\Carbon::parse(
                                                $masterclass->date .
                                                    ' ' .
                                                    $masterclass->time,
                                            );
                                            $masterclassshowJoinButton = $masterclasscurrentDateTime->between(
                                                $masterclassDateTime->copy()->subMinutes(5),
                                                $masterclassDateTime->copy()->addMinutes(60),
                                            );
                                        @endphp
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                @if ($masterclassshowJoinButton)
                                                    <a href="{{ $masterclass->join_url }}" target="_blank"
                                                        class="btn btn-danger btn-sm">
                                                        Join
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-danger btn-sm disabled">
                                                        Join
                                                    </a>
                                                @endif

                                            </h6>
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
