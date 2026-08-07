<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $studentId }}</title>
    <style>
        /* Ensure page settings */
        @page {
            margin: 0;
            size: A4 portrait;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .certificate-bg {
            width: 100%;
            height: 100%;
        }

        .student-name {
            position: absolute;
            top: {{ $certificate['name_top'] }};
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: {{ $certificate['font_size'] }};
            color: #0d2246;
            white-space: nowrap;
            text-align: center;
            width: 80%;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <img src="{{ storage_path('certificates/' . $certificate['image']) }}" alt="Certificate" class="certificate-bg">
        <span class="student-name">{{ ucwords(strtolower($full_name)) }}</span>
    </div>
</body>
</html>
