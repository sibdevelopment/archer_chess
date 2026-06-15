@extends('layouts.admin')
@section('title')
    Tournament
@endsection
@section('content')
    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Tournament</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/admin/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tournament</li>
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
                                <th scope="col" class="">Tournament Name</th>
                                <th scope="col" class="text-center">Date & Time</th>
                                <th scope="col" class="text-center">Link</th>
                                <th scope="col" class="text-center">Certificate</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            @if (!empty($tournamentDatas) && $tournamentDatas->isNotEmpty())
                            @foreach ($tournamentDatas as $tournament)
                                @php
                                    // Fetch tournament details
                                    $batchIds = !empty($tournament->batch_ids) ? ($tournament->batch_ids) : [];
                                    $levelIds = !empty($tournament->level_ids) ? ($tournament->level_ids) : [];

                                    // Get batch and student details
                                    $batches = App\Models\Batch::whereIn('id', $batchIds)->pluck('name')->toArray();
                                    $level = App\Models\Level::whereIn('id', $levelIds)->pluck('name')->toArray();
                                    $currentDateTime = Carbon\Carbon::now();
                                    $tournamentDateTime = Carbon\Carbon::parse($tournament->date . ' ' . $tournament->time)->addMinutes(30); // Add 5 minutes to the start time
                                    $showButton = $currentDateTime->gte($tournamentDateTime);
                                @endphp
                                    <tr>
                                        <td class="">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">
                                                      {{ $tournament->name }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                {{ isset($tournament->time)
                                                    ? convertTimeZomeWiseTime(
                                                        \Carbon\Carbon::parse($tournament->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($tournament->time)->format('g:i A'),
                                                        $student->id,
                                                    )
                                                    :
                                                    '' }}
                                                |{{ isset($tournament->date)
                                                    ? convertTimeZomeWiseDate(
                                                        \Carbon\Carbon::parse($tournament->date)->format('j, M Y'),
                                                        \Carbon\Carbon::parse($tournament->time)->format('g:i A'),
                                                        $student->id,
                                                    )
                                                    : '' }}
                                            </h6>
                                        </td>

                                        @php
                                            $currentDate = Carbon\Carbon::now()->toDateString(); // Get the current date (YYYY-MM-DD)
                                            $tournamentDate = Carbon\Carbon::parse($tournament->date)->toDateString(); // Get the tournament date (YYYY-MM-DD)
                                        @endphp

                                        <td class="text-center">
                                            <h6 class="fw-semibold mb-1">
                                                {{-- Show the button if the tournament date is today or in the future --}}
                                                @if ($currentDate <= $tournamentDate)
                                                    <a href="{{ $tournament->link }}" target="_blank" class="btn btn-danger btn-sm">
                                                        Join
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-danger btn-sm disabled">
                                                        Join
                                                    </a>
                                                @endif
                                            </h6>
                                        </td>




                                        <td class="text-center">
                                            @if ($showButton)
                                                @if (!empty($tournament->certificate) && !empty($tournament->certificate['path']))
                                                    <h6 class="fw-semibold mb-1">
                                                        <a href="{{ route('admin.student.tournament.certificate', ['tournament' => $tournament->route_key]) }}" class="btn btn-success btn-sm">Download Certificate</a>
                                                    </h6>
                                                @else
                                                    <h6 class="fw-semibold mb-1">
                                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm">Certificate not available</a>
                                                    </h6>
                                                @endif
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
