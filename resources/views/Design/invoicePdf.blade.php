<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        /* body {
            margin: 0;
            padding: 0;
            background: #fff;
        } */

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            padding: 20mm;
            background: #fff;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            color: #555;
            font-size: 11px;
            padding-bottom: 6px;
        }

        td {
            padding: 6px 0;
            vertical-align: top;
        } 

        .amount {
            text-align: right;
            font-weight: bold;
        }

        /* @media print {
            body {
                background: #fff;
            }
            .page {
                margin: 0;
            }
        }*/
    </style>

</head>

<body>

    <div class="page">

        <!-- HEADER -->
        <table>
            <tr>
                <td>
                    <img src="{{ asset('backend/images/ArcherKids-logo.png') }}" style="height:80px;">
                    <p class="mt-3 text-muted mb-0">
                        Archer Chess Academy<br>
                        support@archerchessacademy.com<br>
                        --<br>
                        Shop No. 05, Shatrunjay Heights, Temba Road
                    </p>
                    <p>
                        Swami Satyanand Maharaj Marg, Bhayandar West,<br>
                        Mira-Bhayandar, Thane – 401101 (Maharashtra)
                    </p>
                </td>
                <td>
                    <h2 class="mt-5 fw-bold ms-5 px-5">INVOICE</h2>
                    <p class="ms-5 px-5">Invoice # <span class="fw-bold">1054981</span></p>
                    <p>Invoice Date <span class="fw-bold">Dec 20, 2025</span></p>
                    <p>Invoice Amount <span class="fw-bold">USD 45.00</span></p>
                    <p class="fw-bold" style="color: #2e7d32">PAID</p>
                </td>
            </tr>
        </table>

        <hr>
        <!-- BILLED TO -->
        <table>
            <tr>
                <th>BILLED TO</th>
                <th>SUBSCRIPTION</th>
            </tr>
            <tr>
                <td>
                    <p>
                        Deepak Kumar<br>
                        <span class="fw-bold">Country: SINGAPORE</span><br>
                        <span class="fw-bold">Mobile: +6598852880</span><br>
                        <span class="fw-bold">Email: </span>
                        <a href="mailto:krithigabose2294@gmail.com"
                            class="fw-bold ">
                            krithigabose2294@gmail.com
                        </a><br>

                        <span class="fw-bold">Id: ARCHERKIDS_102918</span>
                    </p>
                </td>

                <td>
                    <p>
                        Billing Period  <span class="fw-bold">Dec 20, 2025, to Jan 20, 2026</span><br>
                        Next Billing Date  <span class="fw-bold">Jan 21, 2026</span><br>
                        Login URL <br><span class="fw-bold">
                        https://archerchessacademy.com/student/login</span>
                    </p>
                </td>
            </tr>
        </table>

        <hr>
        <!-- DESCRIPTION -->
        <table>
            <tr>
                <th>DESCRIPTION</th>
                <th class="amount">AMOUNT <br>(Currency)</th>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td class="fw-bold">Beginner</td>
                <td class="amount">USD<br>45.00</td>
            </tr>
        </table>

        <hr>
        <!-- TOTAL -->
        <table class="text-end">
            <tr>
                <td class="fw-bold fs-5">Total USD 45.00</td>
            </tr>
            <tr>
                <td class="muted">Payments (USD 45.00)</td>
            </tr>
            <tr>
                <td class="fs-5">Amount Due (Currency) <span class="fw-bold">USD 0.00</span></td>
            </tr>
        </table>

        <hr>
        <!-- PAYMENTS -->
        <th class="fw-bold">PAYMENTS</th>
        <p><span class="fw-bold">USD 45.00</span> was paid on 21 Dec 2025 18:05.</p>

        <hr>
        <!-- NOTES -->
        <th>NOTES</th>
        <p>
            All monthly & usage payments are non-refundable.
            Questions? Contact us at support@archerchessacademy.com
        </p>

    </div>

</body>

</html>
