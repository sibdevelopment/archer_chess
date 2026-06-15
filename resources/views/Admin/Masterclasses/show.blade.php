<div class="row">
    @foreach ($masterclass_logs as $key => $masterclass_log)
        <div class="col-md-4">
            {{-- <p> {{ $key + 1 }}) {{ $masterclass_log->student->first_name }} ({{ $masterclass_log->student->country }}) ({{ $masterclass_log->student->student_id }})</p> --}}
            <p> {{ $key + 1 }}) {{ $masterclass_log->student->first_name }} {{ $masterclass_log->student->user->mobile }} {{ $masterclass_log->student->user->device_id }}</p>
        </div>
    @endforeach
</div>
