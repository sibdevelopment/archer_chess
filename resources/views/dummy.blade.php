<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <style>
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table thead tr {
            border-bottom: 1px solid #ddd;
            background-color:
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e9e9e9;
        }

        /* Table Header Styling */
        thead {
            background-color: #4CAF50;
            color: white;
        }

        /* Add padding and margin for the page */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        /* Styling for cards or overall layout */
        .container {
            max-width: 1200px;
            margin: auto;
        }

        .table-wrapper {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Coach ID</th>
                        <th>Batch ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentAttendance as $key => $student)
                    @php
                        $studentinfo = App\Models\Student::where('id', $student->student_id)->first();
                        $batchinfo = App\Models\Batch::where('id', $student->batch_id)->first();

                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $studentinfo->first_name }} {{ $studentinfo->last_name }}</td>
                        <td>{{ $student->student_id }}</td>
                        <td>{{ $student->coach_id }}</td>
                        <td>{{ $student->batch_id }} - {{ $batchinfo->name }}</td>
                        <td>{{ $student->date }}</td>
                        <td>{{ $student->time }}</td>
                        <td>{{ $student->duplicate_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <style>
        @media print, screen and (forced-colors: active) {
            canvas {
                visibility: hidden;
            }
            body::before {
                content: "Screenshots are disabled.";
                display: block;
                text-align: center;
                margin-top: 20px;
            }
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            font-size: 1.5rem; /* Adjust the font size for smaller screens */
            margin: 10px;
        }

        canvas {
            border: 1px solid #ccc;
            margin-top: 20px;
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.2rem; /* Smaller font size for mobile */
            }
            canvas {
                max-width: 90%; /* Reduce canvas size for small screens */
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1rem; /* Adjust heading size for very small screens */
            }
            canvas {
                max-width: 100%; /* Ensure the canvas fits the screen */
            }
        }
    </style>
    <title>Archer Kids</title>
</head>
<body>
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;"></div>

    <h1>Mastering Fundamental Mathematics</h1>
    <canvas id="pdfCanvas"></canvas>
    <script>
        // Dynamically insert the public path to your PDF file
        const publicPath = '{{ asset('Intermediate(Syllabus).pdf') }}';

        // PDF.js library usage
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');

        pdfjsLib.getDocument(publicPath).promise.then((pdf) => {
            // Load the first page of the PDF
            pdf.getPage(1).then((page) => {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                // Render the page onto the canvas
                page.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
            });
        }).catch(error => {
            console.error('Error loading PDF:', error);
        });
    </script>
    <script>
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });
    </script>
    <script>
        document.addEventListener('keydown', function (e) {
            // Disable PrintScreen
            if (e.key === "PrintScreen") {
                alert("Screenshots are disabled.");
                e.preventDefault();
            }
            // Disable Save (Ctrl+S or Command+S)
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                alert("Saving is disabled.");
                e.preventDefault();
            }
        });
    </script>
</body>
</html> --}}
