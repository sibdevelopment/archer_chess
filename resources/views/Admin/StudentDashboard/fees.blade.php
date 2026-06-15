@extends('layouts.admin')
@section('title')
    Fees Details
@endsection
@section('content')

    <style>
        .pay-now-btn {
            display: inline-block;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(90deg, #28a745, #218838);
            color: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pay-now-btn:hover {
            background: linear-gradient(90deg, #218838, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card {
            border-radius: 8px;
        }

        .list-group-item {
            border: none;
            padding: 12px 0;
        }

        .list-group-item:not(:last-child) {
            border-bottom: 1px solid #eaeaea;
        }
    </style>

    @php
        // dd($student);
    @endphp

    {{-- {{ $student->id }} --}}
    <div class="card bg-light-danger shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Fees Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/student-dashboard">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Fees Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../backend/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($studentFees) && $studentFees->count() > 0)
        <div class="container-fluid">
            <div class="product-list">
                <div class="card boder-0">
                    <div class="card-body p-3">
                        @if (!empty($studentFees) && $studentFees->count() > 0)
                            <div class="table-responsive border rounded">
                                <table
                                    class="table table-bordered m-t-30 table-hover contact-list footable footable-5 footable-paging footable-paging-center breakpoint-lg">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Currency</th>
                                            <th scope="col">Monthly Fee</th>
                                            <th scope="col">Total Amount Paid</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentFees as $index => $fee)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($fee->start_date)->format('d-M-Y') }}</td>
                                                @if ($fee->end_date == '3000-01-01')
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                @else
                                                    <td>{{ \Carbon\Carbon::parse($fee->end_date)->format('d-M-Y') }}</td>
                                                @endif
                                                <td>{{ $fee->currency }}</td>
                                                <td>{{ $fee->monthly_fees }}</td>
                                                <td>{{ $fee->total_amount_paid }}</td>
                                                <td>{{ $fee->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @php
        // dd($nextPaymentLevel);
    @endphp
    @if ($nextPaymentLevel && $nextThreePaymentLevels->count() > 0)
        @php
            $student_country = $student->country;
            $nextPaymentLevelAmount = '0';
            $currency = '';
            if ($student_country == 'USA') {
                $nextPaymentLevelAmount = $nextPaymentLevel->usa_fees;
                $currency = 'USD';
            } elseif ($student_country == 'CANADA') {
                $nextPaymentLevelAmount = $nextPaymentLevel->canada_fees;
                $currency = 'CAD';
            } elseif ($student_country == 'AUSTRALIA') {
                $nextPaymentLevelAmount = $nextPaymentLevel->australia_fees;
                $currency = 'AUD';
            } elseif ($student_country == 'NEW ZEALAND') {
                $nextPaymentLevelAmount = $nextPaymentLevel->newzealand_fees;
                $currency = '';
            } elseif ($student_country == 'INDIA') {
                $nextPaymentLevelAmount = $nextPaymentLevel->india_fees;
                $currency = 'INR';
            } elseif ($student_country == 'UAE') {
                $nextPaymentLevelAmount = $nextPaymentLevel->uae_fees;
                $currency = 'AED';
            } elseif ($student_country == 'UK') {
                $nextPaymentLevelAmount = $nextPaymentLevel->uk_fees;
                $currency = 'GBP';
            }elseif ($student_country == 'QATAR') {
                $nextPaymentLevelAmount = $nextPaymentLevel->qatar_fees;
                $currency = 'QAR';
            }elseif ($student_country == 'SINGAPORE') {
                $nextPaymentLevelAmount = $nextPaymentLevel->singapore_fees;
                $currency = 'SGD';
            }elseif ($student_country == 'EUROPEAN UNION') {
                $nextPaymentLevelAmount = $nextPaymentLevel->european_union_fees;
                $currency = 'EUR';
            }elseif ($student_country == 'OMAN') {
                $nextPaymentLevelAmount = $nextPaymentLevel->oman_fees;
                $currency = 'OMR';
            }
            // $currency = 'INR';

        @endphp
        <div class="container-fluid mt-4">
            <!-- Next Payment Level Section -->
            <div class="card shadow-lg border-0 mb-4 rounded-lg">
                <div class="card-body">
                    <h3 class="fw-bold text-primary">Next Payment Level Fees</h3>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="fs-5 mb-2"><strong>Level:</strong> {{ $nextPaymentLevel->name }}</p>
                            <p class="fs-5"><strong>Amount:</strong> {{ $nextPaymentLevelAmount }} {{ $currency }}</p>
                        </div>

                        {{-- HDFC Payment Button --}}
                        {{-- <button type="submit" class="btn btn-primary pay-now-btn me-2 hdfc-btn" id="hdfc-btn"
                            data-amount="{{ $nextPaymentLevelAmount }}">
                            <i class="fas fa-credit-card me-2"></i> Pay {{ $nextPaymentLevelAmount }} {{ $currency }}
                        </button> --}}

                        {{-- Razorpay Payment Button --}}
                        <button class="btn btn-success pay-now-btn"
                            onclick="payWithRazorpay({{ $nextPaymentLevelAmount }}, 'Next Payment Level Fees', '{{ $currency }}', {{ $nextPaymentLevel->id }})">
                            <i class="fas fa-credit-card me-2"></i> Pay {{ $nextPaymentLevelAmount }} {{ $currency }}
                        </button> 
                    </div>
                </div>
            </div>

            <!-- Next 3 Payment Levels Section -->
            @php
                $nextThreePaymentLastLevelId = $nextThreePaymentLevels->last()->id;
                if ($student_country == 'USA') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('usa_fees');
                } elseif ($student_country == 'CANADA') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('canada_fees');
                } elseif ($student_country == 'AUSTRALIA') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('australia_fees');
                } elseif ($student_country == 'NEW ZEALAND') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('newzealand_fees');
                } elseif ($student_country == 'INDIA') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('india_fees');
                } elseif ($student_country == 'UAE') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('uae_fees');
                } elseif ($student_country == 'UK') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('uk_fees');
                } elseif ($student_country == 'QATAR') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('qatar_fees');
                } elseif ($student_country == 'SINGAPORE') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('singapore_fees');
                } elseif ($student_country == 'EUROPEAN UNION') {
                    $nextThreePaymentLevelsAmount = $nextThreePaymentLevels->sum('european_union_fees');
                } elseif ($student_country == 'OMAN') {
                } 
            @endphp
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold text-primary">Next {{ $nextThreePaymentLevels->count() }} Payment Levels</h3>

                        {{-- <button class="btn btn-primary pay-now-btn hdfc-btn"
                            data-amount="{{ $nextThreePaymentLevelsAmount }}">
                            <i class="fas fa-credit-card me-2"></i> Pay {{ $nextThreePaymentLevelsAmount }}
                            {{ $currency }}
                        </button> --}}
                        <button class="btn btn-primary pay-now-btn"
                            onclick="payWithRazorpay({{ $nextThreePaymentLevelsAmount }}, 'Next {{ $nextThreePaymentLevels->count() }} Payment Levels Fees', '{{ $currency }}', {{ $nextThreePaymentLastLevelId }})">
                            <i class="fas fa-credit-card me-2"></i> Pay {{ $nextThreePaymentLevelsAmount }}
                            {{ $currency }}
                        </button>
                        {{-- <button class="btn btn-primary pay-now-btn hdfc-btn" data-amount="{{ $nextThreePaymentLevelsAmount }}">
                            <i class="fas fa-credit-card me-2"></i> Pay {{ $nextThreePaymentLevelsAmount }}
                            {{ $currency }}
                        </button> --}}
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($nextThreePaymentLevels as $nextThreePaymentLevel)
                            @php
                                if ($student_country == 'USA') {
                                    $amount = $nextThreePaymentLevel->usa_fees;
                                } elseif ($student_country == 'CANADA') {
                                    $amount = $nextThreePaymentLevel->canada_fees;
                                } elseif ($student_country == 'AUSTRALIA') {
                                    $amount = $nextThreePaymentLevel->australia_fees;
                                } elseif ($student_country == 'NEW ZEALAND') {
                                    $amount = $nextThreePaymentLevel->newzealand_fees;
                                } elseif ($student_country == 'INDIA') {
                                    $amount = $nextThreePaymentLevel->india_fees;
                                } elseif ($student_country == 'UAE') {
                                    $amount = $nextThreePaymentLevel->uae_fees;
                                } elseif ($student_country == 'UK') {
                                    $amount = $nextThreePaymentLevel->uk_fees;
                                } elseif ($student_country == 'UK') {
                                    $amount = $nextThreePaymentLevel->uk_fees;
                                } elseif ($student_country == 'QATAR') {
                                    $amount = $nextThreePaymentLevel->qatar_fees;
                                } elseif ($student_country == 'SINGAPORE') {
                                    $amount = $nextThreePaymentLevel->singapore_fees;
                                } elseif ($student_country == 'EUROPEAN UNION') {
                                    $amount = $nextThreePaymentLevel->european_union_fees;
                                } elseif ($student_country == 'OMAN') {
                                    $amount = $nextThreePaymentLevel->oman_fees;
                                } 
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1"><strong>Level:</strong> {{ $nextThreePaymentLevel->name }}</p>
                                    <p class="mb-0"><strong>Amount:</strong> {{ $amount }} {{ $currency }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div> 
        </div>
    @endif


    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .pay-now-btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .pay-now-btn:hover {
            background: linear-gradient(90deg, #0056b3, #007bff);
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card {
            border-radius: 16px;
            border: 1px solid #e3e6f0;
        }

        .card-body {
            padding: 24px;
        }

        .list-group-item {
            border: none;
            padding: 15px 0;
            font-size: 16px;
        }

        .list-group-item:not(:last-child) {
            border-bottom: 1px solid #eaeaea;
        }

        .fw-bold {
            font-weight: 700;
        }

        .fs-5 {
            font-size: 1.25rem;
        }

        .text-primary {
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>

    <!-- Success Modal -->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" aria-labelledby="paymentSuccessModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentSuccessModalLabel">Payment Status</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body" id="paymentStatusMessage">
                    <!-- Status message will be inserted dynamically here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        onclick="reloadPage()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loader Modal -->
    <div class="modal fade" id="LoaderModel" tabindex="-1" aria-labelledby="LoaderModelLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-primary mt-2">Processing Payment...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script> 
        $(document).ready(function() {
            $(".hdfc-btn").click(function(e) {
                e.preventDefault();
                var amount = $(this).data('amount');
                $.ajax({
                    url: "{{ route('create.order') }}", // First, create the order
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    data: {
                        amount: amount,
                        // amount: 10,
                        currency: "{{ $currency ?? '' }}",
                        student_id: {{ isset($student) ? $student->id : 0 }},
                        payment_level_id: {{ isset($nextPaymentLevel) ? $nextPaymentLevel->id : 0 }},   

                    },
                    success: function(orderResponse) {
                        if (orderResponse.status === "success") {
                            // Once order is created, call createSession with the order_id
                            $.ajax({
                                url: "{{ route('hdfc.payment') }}", // Now create session
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                },
                                data: {
                                    order_id: orderResponse.order_id,
                                    amount: amount,
                                    currency: "{{ $currency ?? '' }}",
                                    student_id: {{ isset($student) ? $student->id : 0 }}
                                },
                                success: function(sessionResponse) {
                                    if (sessionResponse.redirect_url) {
                                        window.open(sessionResponse.redirect_url,'_blank').focus();
                                    } else {
                                        toastr.error(sessionResponse.message, '', {
                                            showMethod: "slideDown",
                                            hideMethod: "slideUp",
                                            timeOut: 1500,
                                            closeButton: true,
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                    alert(
                                        "An error occurred while processing your request."
                                    );
                                }
                            });
                        } else {
                            toastr.error('Order creation failed!', '', {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 1500,
                                closeButton: true,
                            });
                        }
                    },
                    error: function(xhr) {
                        toastr.error(sessionResponse.message, '', {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 1500,
                            closeButton: true,
                        });
                    }
                });
            });
        });
    </script>

    <script>
        // Function to reload the page when OK is clicked
        function reloadPage() {
            location.reload(); // Refreshes the page
        }
    </script>

    <!-- Include Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function payWithRazorpay(amount, description, currency, paymentLevelId) {

            const options = {
                // key: "rzp_test_RLrov8eGceCpPt",
                key: "rzp_live_eckVmG8LHU5uhu",
                amount: amount * 100,
                currency: currency,
                name: "Archer Kids",
                description: description,
                image: "https://archerchessacademy.com/backend/images/ArcherKids-logo.png",
                handler: function(response) {
                    console.log("Payment Successful!", response);

                    // Show the loader modal
                    var myModal = new bootstrap.Modal(document.getElementById('LoaderModel'));
                    myModal.show();

                    fetch('/razorpay/verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // For Laravel CSRF protection
                        },
                        body: JSON.stringify({
                            payment_id: response.razorpay_payment_id,
                            amount: amount,
                            student_id: {{ isset($student) ? $student->id : 0 }},
                            currency: currency,
                            payment_level_id: paymentLevelId
                        })
                    }).then(res => res.json()).then(data => {
                        console.log("Payment Verification Response:", data);

                        // Hide the loader modal after the response is received
                        myModal.hide();

                        if (data.status === 'success') {
                            // Customize message for success
                            const message = "Payment Successful! Payment ID: " + response
                                .razorpay_payment_id;
                            document.getElementById('paymentStatusMessage').innerText = message;
                            $('#paymentSuccessModal').modal('show'); // Show success modal
                        } else {
                            // Customize message for failure
                            const message = "Payment Failed! Please try again later.";
                            document.getElementById('paymentStatusMessage').innerText = message;
                            $('#paymentSuccessModal').modal('show'); // Show failure modal
                        }

                    }).catch(err => {
                        console.error("Error:", err);

                        // Hide the loader modal if there's an error
                        myModal.hide();
                        alert("Payment Failed! Please try again later.");
                    });
                },
                prefill: {
                    name: '{{ isset($student) ? $student->first_name : '' }}' + ' ' +
                        '{{ isset($student) ?  $student->last_name : ''}}', // Concatenate first and last name
                    email: '{{  isset($student) ? $student->email : '' }}', // Use the student's email
                    contact: '{{  isset($student) ? $student->mobile : ''}}' // Use the student's mobile number
                },
                notes: {
                    Kid_Name: '{{ isset($student) ? $student->first_name : '' }}' + ' ' +
                        '{{ isset($student) ?  $student->last_name : ''}}',
                    Chesslang_ID: '{{ isset($student) ? $student->student_id : 0 }}'
                },
                theme: {
                    color: "#28a745"
                }
            };

            // Open Razorpay Checkout
            const rzp = new Razorpay(options);
            rzp.open();
        }
    </script>

@endsection
