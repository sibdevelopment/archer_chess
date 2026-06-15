@extends('layouts.admin')
@section('title')
    Access Denied
@endsection
@section('content')
    <style>
        .permission-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .permission-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .permission-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .permission-box h1 {
            font-size: 80px;
            color: #FF6347;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .permission-box h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .permission-box p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .permission-box a {
            display: inline-block;
            text-decoration: none;
            background-color: #FF6347;
            color: #ffffff;
            padding: 12px 32px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .permission-box a:hover {
            background-color: #FF4500;
            transform: scale(1.05);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .permission-box {
                padding: 20px;
                max-width: 90%;
            }

            .permission-box h1 {
                font-size: 60px;
            }

            .permission-box h2 {
                font-size: 20px;
            }

            .permission-box p {
                font-size: 14px;
            }

            .permission-box a {
                padding: 10px 24px;
                font-size: 14px;
            }
        }
    </style>

    <div class="permission-container">
        <div class="permission-box">
            <h1>Access Denied</h1>
            <h2>This Dashboard is for Testing Only</h2>
            <p>Please use the live version of this dashboard. This environment is for testing purposes only.</p>
            <a href="https://archerchessacademy.com">Go to Live Version</a>
        </div>
    </div>
@endsection
