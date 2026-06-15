@extends('layouts.admin')
@section('title')
   Coach Availability
@endsection
@section('content')
    <style>
    .permission-container {
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

    .table>:not(caption)>*>* {
        padding: 3px 3px !important;
    }
    </style>

  <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <!-- Filters :: -->
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-3 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Coach Availability</h5>
                            </div> 
                            <div class="col-3">
                                <select name="coach" id="coach" class="select2 form-select form-select-sm pure-white" aria-label=".form-select-sm example">
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}">{{ $coach->user->first_name }} {{ $coach->user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-3">
                                <input type="date" id="date" class="form-control form-control-sm pure-white" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Data Table Headers :: -->
                    <div class="card-body p-4" id="availability">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {

            // Function to fetch availability
            function fetchAvailability() {
                let coachId = $('#coach').val();   // selected coach
                let date    = $('#date').val();    // selected date

                if (!coachId || !date) return;

                let url = "{{ route('admin.availability') }}"; // correct route

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        date: date,
                        coach_id: coachId
                    }, // send selected date & coach_id
                    beforeSend: function() {
                        $('#availability').html('<p>Loading availability...</p>');
                    },
                    success: function(response) {
                        $('#availability').html(response); // append returned table
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $('#availability').html('<p class="text-danger">Error fetching availability. Please try again.</p>');
                    }
                });
            }

            // Trigger AJAX when coach or date changes
            $('#coach').on('change', fetchAvailability);
            $('#date').on('change', fetchAvailability);

            // Load availability initially on page load
            fetchAvailability();
        });


    </script>
@endsection
