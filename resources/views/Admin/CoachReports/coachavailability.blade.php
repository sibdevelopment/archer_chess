<style>
    .table>:not(caption)>*>* {
    padding: 16px 5px !important;
    color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color)));
    background-color: var(--bs-table-bg);
    border-bottom-width: var(--bs-border-width);
    box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state,var(--bs-table-bg-type,var(--bs-table-accent-bg)))
}
</style>
<table class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg" style="border-radius: 8px;">
    <thead>
        <tr>
            <th>Title</th>
            <th>Start</th>
            <th>Color</th>
            <th>Text Color</th>
            <th>Slot</th>
        </tr>
    </thead>
    <tbody>
        @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule['title'] }}</td>
                <td>{{ $schedule['start'] }}</td>
                <td>{{ $schedule['color'] }}</td>
                <td>{{ $schedule['textColor'] }}</td>
                <td>{{ $schedule['slot'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>