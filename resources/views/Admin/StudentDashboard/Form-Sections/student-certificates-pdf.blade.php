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
            size: A4 landscape;
        }
        body {
            margin: 0;
            padding: 2;
            font-family: Arial, sans-serif;
        }

        .abs {
            position: absolute;
        }

        img {
            height: 100%;
            width: auto;
        }
    </style>
</head>
<body>
    <!-- Certificate Content based on Level -->
    @if($level == 'BL')
        <img src="{{ storage_path('certificates/bl_1.jpg') }}" alt="Expert Certificate">
        <span class="abs" style="top:360px; left:50%; transform: translateX(-50%);font-weight: bold; font-size:40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }}
        </span>
    @elseif($level == 'IML_1')
        <img src="{{ storage_path('certificates/iml_1.jpg') }}" alt="Intermediate Certificate">
        <span class="abs" style="top:400px; left:50%; transform: translateX(-50%);font-weight: bold; font-size:40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }}
        </span>
    @elseif($level == 'IML_2')
        <img src="{{ storage_path('certificates/iml_2.jpg') }}" alt="Beginner Certificate">
        <span class="abs" style="top:390px; left:50%; transform: translateX(-50%);font-weight: bold; font-size: 40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }} 
        </span>
    @elseif($level == 'Advanced_level_1')
        <img src="{{ storage_path('certificates/Advanced_level_1.jpg') }}" alt="Advanced Certificate">
        <span class="abs" style="top:378px; left:50%; transform: translateX(-50%); font-weight: bold; font-size: 40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }}
        </span>
    @elseif($level == 'Advanced_level_2')
        <img src="{{ storage_path('certificates/Advanced_level_2.jpg') }}" alt="Advanced Certificate">
        <span class="abs" style="top:422px; left:50%; transform: translateX(-50%); font-weight: bold; font-size: 40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }}
        </span>
    @elseif($level == 'Advanced_level_3')
        <img src="{{ storage_path('certificates/Advanced_level_3.jpg') }}" alt="Advanced Certificate">
        <span class="abs" style="top:395px; left:50%; transform: translateX(-50%); font-weight: bold; font-size: 40px; white-space: nowrap;">
            {{ ucwords(strtolower($full_name)) }}
        </span>
    @endif
</body>
</html>
