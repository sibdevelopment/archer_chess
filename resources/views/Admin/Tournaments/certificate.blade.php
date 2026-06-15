<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate_name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            position: relative;
        }

        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .certificate-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-name {
            position: absolute;
            top:320px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: bold;
            font-size: 40px;
            white-space: nowrap;
            text-align: center;
            width: 80%; /* Adjust width as needed */
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <img src="file://{{ $certificateUrl }}" alt="Certificate" class="certificate-image">
        <span class="student-name">{{ ucwords(strtolower($studentName)) }}</span>
    </div>
</body>
</html>
