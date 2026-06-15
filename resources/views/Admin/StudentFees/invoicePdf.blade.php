<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        /* .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            padding: 20mm;
            background: #fff;
        } */

        /* table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 11px;
            color: #555;
            padding-bottom: 6px;
        }

        td {
            padding: 6px 0;
            vertical-align: top;
        } */

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }

        .bold {
            font-weight: bold;
        }

        .muted {
            color: #777;
        }

        .amount {
            text-align: right;
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
        }

        .paid {
            font-weight: bold;
            font-size: 14px;
            color: #2e7d32;
        }

        .email-link {
            font-weight: bold;
            text-decoration: underline;
            color: #1a32b9;
        }
    </style>
</head>

<body>

    <div class="page">

        <!-- HEADER -->
        <table width="100%">
            <tr>
                <td>
                    <img src="{{ $logoPath }}" style="height:80px;">

                    <p class="muted">
                        Archer Chess Academy<br>
                        support@archerchessacademy.com<br>
                        --<br>
                    </p>
                    <p>
                        Shop No. 05, Shatrunjay Heights, Temba Road,<br>
                        Swami Satyanand Maharaj Marg, Bhayandar West,<br>
                        Mira-Bhayandar, Thane – 401101 (Maharashtra)
                    </p>
                </td>

                <td width="40%" style="vertical-align: top;">
                    <div class="invoice-title" style="margin-top: 50px;">INVOICE</div>
                    <p>Invoice # <span class="bold">{{ $student_fee->id }}</span></p>
                    <p>Invoice Date <span class="bold">{{ \Carbon\Carbon::parse($student_fee->receive_date)->format('M d, Y') }}</span></p>
                    <p>Invoice Amount <span class="bold">{{ $student_fee->currency }}
                            {{ $student_fee->total_amount_paid }}</span></p>
                    <p class="paid">PAID</p>
                </td>
            </tr>
        </table>

        <hr>

        <!-- BILLED TO -->
        <table width="100%">
            <tr>
                <td width="60%" style="vertical-align: top;">
                    <h3>BILLED TO</h3>
                    <p>
                        {{ $student->first_name}} {{ $student->last_name}}<br>
                        <span class="bold">Country: {{ $student->country }}</span><br>
                        <span class="bold">Mobile: {{ $student->mobile }}</span><br>
                        <span class="bold">Email:
                        <a href="mailto:{{ $student->user->email }}" class="email-link">
                            {{ $student->user->email }}
                        </a></span><br>
                        <span class="bold">Id: {{ $student->student_id }}</span>
                    </p>
                </td>

                <td width="40%" style="vertical-align: top;">
                    <h3>SUBSCRIPTION</h3>
                    <p>
                        Billing Period <span class="bold"> {{ \Carbon\Carbon::parse($student_fee->start_date)->format('M d, Y') }} to
                            {{ \Carbon\Carbon::parse($student_fee->end_date)->format('M d, Y') }}</span><br>
                        Next Billing Date <span class="bold">{{ \Carbon\Carbon::parse($student_fee->end_date)->addDay()->format('M d, Y') }}</span><br>
                        Login URL<br>
                        <span class="bold">https://archerchessacademy.com/student/login</span>
                    </p>
                </td>
            </tr>
        </table>

        <hr>

        <!-- DESCRIPTION -->
        <table style="width:100%;">
            <tr>
                <th style="text-align:left;">DESCRIPTION</th>
                <th class="amount" style="text-align:right;">AMOUNT<br>(Currency)</th>
            </tr>
        </table>
        <hr>
        <table  style="width:100%;">
            <tr>
                <td class="bold" style="text-align:left;">{{ $student->latestBatch->level->name ?? 'N/A' }}</td>
                <td class="amount" style="text-align:right;">{{ $student_fee->currency }}<br>{{ $student_fee->total_amount_paid }}</td>
            </tr>
        </table>

        <hr>

        <!-- TOTAL -->
        <table style="width:100%;">
            <tr>
                <td class="right bold">Total {{ $student_fee->currency }} {{ $student_fee->total_amount_paid }}</td>
            </tr>
            <tr>
                <td class="right muted">Payments ({{ $student_fee->currency }} {{ $student_fee->total_amount_paid }})</td>
            </tr>
            <tr>
                <td class="right">Amount Due (Currency) <span class=" bold">{{ $student_fee->currency }} 0.00</span>
                </td>
            </tr>
        </table>

        <hr>

        <!-- PAYMENTS -->
        <p class="bold">PAYMENTS</p>
        <p><span class="bold">{{ $student_fee->currency }} {{ $student_fee->total_amount_paid }}</span> was paid on
            {{ \Carbon\Carbon::parse($student_fee->receive_date)->format('M d, Y') }}</p>

        <hr>

        <!-- NOTES -->
        <p class="bold">NOTES</p>
        <p>
            All monthly & usage payments are non-refundable. Questions? Contact us at support@archerchessacademy.com
        </p>

    </div>

</body>

</html>
