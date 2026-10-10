<div class="row mb-4">
    @foreach ([['Created', $counts['created']], ['Pending', $counts['pending']], ['Critical', $counts['critical']], ['Done', $counts['done']], ['Cancelled', $counts['cancelled']], ['Reassigned', $counts['reassigned']]] as $counter)
        <div class="col-md-2 mb-2">
            <div class="border rounded p-3 bg-light">
                <div class="fs-3 text-muted">{{ $counter[0] }}</div>
                <div class="fs-7 fw-semibold">{{ $counter[1] }}</div>
            </div>
        </div>
    @endforeach
</div>
