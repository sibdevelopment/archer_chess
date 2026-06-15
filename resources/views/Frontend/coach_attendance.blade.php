<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Coach Attendance Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Coach Name</th>
                    <th>Batch Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coachAttendances as $coach_attendance)
                    <tr @if($coach_attendance->is_duplicate) style="background-color: #ffcccc;" @endif>

                        <td>{{ $coach_attendance->coach->user->first_name }} {{ $coach_attendance->coach->user->last_name }} ({{ $coach_attendance->id }})</td>
                        <td>{{ $coach_attendance->batch ? $coach_attendance->batch->name : 'N/A' }} ({{ $coach_attendance->batch_id }})</td>
                        <td>{{ \Carbon\Carbon::parse($coach_attendance->date)->format('d/m/Y') }}</td>
                        <td>{{ $coach_attendance->time }}</td>
                        <td>{{ ucfirst($coach_attendance->status) }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</body>

</html>
