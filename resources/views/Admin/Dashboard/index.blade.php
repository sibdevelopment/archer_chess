Demo Details
@extends('layouts.admin')
@section('title')
    Access Denied
@endsection
@section('content')
    <style>.permission-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full height to center vertically */
        background-color: #f9f9f9; /* Slight background to differentiate */
    }

    .permission-box {
        background-color: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); /* Softer shadow for depth */
        max-width: 500px; /* Slightly larger for readability */
        text-align: center;
        transition: all 0.3s ease; /* Smooth transition for hover effects */
    }

    .permission-box:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Hover effect for box */
    }

    .permission-box h1 {
        font-size: 80px; /* Reduced size for better fit */
        color: #FF6347;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .permission-box h2 {
        font-size: 26px;
        color: #333;
        margin-bottom: 20px;
    }

    .permission-box p {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }

    .permission-box a {
        display: inline-block;
        text-decoration: none;
        background-color: #FF6347;
        color: white;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .permission-box a:hover {
        background-color: #FF4500;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .permission-box {
            padding: 20px;
            max-width: 90%;
        }

        .permission-box h1 {
            font-size: 60px; /* Adjusted for smaller screens */
        }

        .permission-box h2 {
            font-size: 22px;
        }

        .permission-box p {
            font-size: 16px;
        }

        .permission-box a {
            padding: 12px 30px;
            font-size: 14px;
        }
    }
    </style>

    <div class="permission-container">
        <div class="permission-box">
            <h1>403</h1>
            <h2>Access Denied</h2>
            <p>Oops! You don't have permission to view this page.</p>
            <a href="/">Go Back to Website</a>
        </div>
    </div>
@endsection
