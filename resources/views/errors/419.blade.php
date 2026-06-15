<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Session Expired</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 50px;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }

        h1 {
            font-size: 48px;
            color: #ff4444;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/backend/images/ArcherKids-logo.png" width="180" alt="" />
        <h1>419</h1>
        <h2>Session Expired</h2>
        <p>Your session has expired. Please refresh the page or log in again.</p>
        {{-- <a href="javascript:history.back()" class="btn btn-primary">Go Back</a> --}}
        <a href="/login" class="btn btn-success">Login Again</a>
    </div>
</body>
</html>
