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
            width: 595pt;
            height: 842pt;
        }

        .certificate-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 595pt;
            height: 842pt;
        }

        .student-name {
            position: absolute;
            top: {{ $certificate['pdf_name_top'] }};
            left: 0;
            font-weight: bold;
            font-size: {{ $certificate['pdf_font_size'] }};
            color: #0d2246;
            white-space: nowrap;
            text-align: center;
            width: 595pt;
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
