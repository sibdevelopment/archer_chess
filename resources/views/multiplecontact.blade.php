@php
    use Illuminate\Support\Facades\DB;

    // Fetch duplicate mobiles with associated IDs and names, handle empty names
    $duplicateMobiles = App\Models\Student::query()
        ->select(
            'mobile',
            DB::raw(
                'GROUP_CONCAT(COALESCE(first_name, "Unknown"), " ", COALESCE(last_name, "") SEPARATOR ", ") as names',
            ),
        )
        ->groupBy('mobile')
        ->havingRaw('COUNT(mobile) > 1')
        ->get();
@endphp

@foreach ($duplicateMobiles as $entry)
    <p><strong>Mobile:</strong> {{ $entry->mobile }}</p>
    <p><strong>Names:</strong> {{ $entry->names ?: 'No names available' }}</p>
    <hr>
@endforeach
