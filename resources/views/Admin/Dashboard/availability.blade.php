<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Time</th>
            @foreach($weekdays as $dayInfo)
                <th>
                    {{ $dayInfo['day'] }} <br>
                    <small>{{ $dayInfo['date'] }}</small>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($displaySlots as $slot)
            <tr>
                <td>{{ $slot }}</td>
                @foreach($weekdays as $dayInfo)
                    <td style="background-color: {{ $grid[$slot][$dayInfo['day']]['color'] }}" class="text-white">
                        {{ $grid[$slot][$dayInfo['day']]['status'] }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
