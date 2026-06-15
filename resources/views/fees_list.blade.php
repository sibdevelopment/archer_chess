<div class="container my-4">
    <!-- 🚀 Buttons for Switching Tables -->
    <div class="text-center mb-4">
        <button class="btn btn-primary switch-btn" onclick="showTable('fees_due_inactive', this)">Fees Due & Inactive</button>
        <button class="btn btn-success switch-btn" onclick="showTable('fees_entered', this)">Current Month Fees Entered</button>
        <button class="btn btn-danger switch-btn" onclick="showTable('fees_due', this)">Current Month Fees Due</button>
        <button class="btn btn-danger switch-btn" onclick="showTable('fees_enter_creation_date', this)">Fees Enter Creation Date</button>
    </div>

    <!-- 🚀 Fees Due and Inactive Students -->
    <div id="fees_due_inactive" class="fees-table">
        <h3 class="table-title">Fees Due and Inactive Students</h3>
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Currency</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Receive Date</th>
                        <th>Monthly Amount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMonthlyFees = 0;
                        $totalAmountPaid = 0;
                    @endphp
                    @foreach ($fees_due_and_inactive as $index => $fees_due_and_inacti)
                        @php
                        // dd($fees_due_and_inacti);
                            $totalMonthlyFees += $fees_due_and_inacti->monthly_fees;
                            $totalAmountPaid += $fees_due_and_inacti->total_amount_paid;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fees_due_and_inacti->student->first_name }} {{ $fees_due_and_inacti->student->last_name }} <br> {{ $fees_due_and_inacti->student->student_id }}</td>
                            <td>{{ $fees_due_and_inacti->currency }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_due_and_inacti->start_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_due_and_inacti->end_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_due_and_inacti->receive_date)->format('d M, Y') }}</td>
                            <td class="text-success"><strong>{{ number_format($fees_due_and_inacti->monthly_fees, 2) }}</strong></td>
                            <td class="text-danger"><strong>{{ number_format($fees_due_and_inacti->total_amount_paid, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    <!-- 🚀 Total Row -->
                    <tr class="table-footer">
                        <td colspan="5" class="text-right"></td>
                        <td colspan="1" class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-success"><strong>{{ number_format($totalMonthlyFees, 2) }}</strong></td>
                        <td class="text-danger"><strong>{{ number_format($totalAmountPaid, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🚀 Current Month Fees Entered -->
    <div id="fees_entered" class="fees-table hidden">
        <h3 class="table-title">Current Month Fees Entered</h3>
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Currency</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Receive Date</th>
                        <th>Monthly Amount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMonthlyFees = 0;
                        $totalAmountPaid = 0;
                    @endphp
                    @foreach ($current_month_fees_enter as $index => $current_month_fee)
                        @php
                            $totalMonthlyFees += $current_month_fee->monthly_fees;
                            $totalAmountPaid += $current_month_fee->total_amount_paid;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $current_month_fee->student->first_name }} {{ $current_month_fee->student->last_name }}
                                <br> {{ $current_month_fee->student->student_id }}
                            </td>
                            <td>{{ $current_month_fee->currency }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee->start_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee->end_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee->receive_date)->format('d M, Y') }}</td>
                            <td class="text-success"><strong>{{ number_format($current_month_fee->monthly_fees, 2) }}</strong></td>
                            <td class="text-danger"><strong>{{ number_format($current_month_fee->total_amount_paid, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    <!-- 🚀 Total Row -->
                    <tr class="table-footer">
                        <td colspan="5" class="text-right"></td>
                        <td colspan="1" class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-success"><strong>{{ number_format($totalMonthlyFees, 2) }}</strong></td>
                        <td class="text-danger"><strong>{{ number_format($totalAmountPaid, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🚀 Current Month Fees Due -->
    <div id="fees_due" class="fees-table hidden">
        <h3 class="table-title">Current Month Fees Due</h3>
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Currency</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Receive Date</th>
                        <th>Monthly Amount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMonthlyFees = 0;
                        $totalAmountPaid = 0;
                    @endphp
                    @foreach ($current_month_fees_due as $index => $current_month_fee_due)
                        @php
                            $totalMonthlyFees += $current_month_fee_due->monthly_fees;
                            $totalAmountPaid += $current_month_fee_due->total_amount_paid;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $current_month_fee_due->student->first_name }} {{ $current_month_fee_due->student->last_name }}
                                <br> {{ $current_month_fee_due->student->student_id }}
                            </td>
                            <td>{{ $current_month_fee_due->currency }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee_due->start_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee_due->end_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($current_month_fee_due->receive_date)->format('d M, Y') }}</td>
                            <td class="text-success"><strong>{{ number_format($current_month_fee_due->monthly_fees, 2) }}</strong></td>
                            <td class="text-danger"><strong>{{ number_format($current_month_fee_due->total_amount_paid, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    <!-- 🚀 Total Row -->
                    <tr class="table-footer">
                        <td colspan="5" class="text-right"></td>
                        <td colspan="1" class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-success"><strong>{{ number_format($totalMonthlyFees, 2) }}</strong></td>
                        <td class="text-danger"><strong>{{ number_format($totalAmountPaid, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🚀 Fees Enter Creation Date -->

    {{-- $fees_enter_by_creation_date --}}

    <div id="fees_enter_creation_date" class="fees-table hidden">
        <h3 class="table-title">Fees Enter Creation Date</h3>
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Currency</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Receive Date</th>
                        <th>Monthly Amount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMonthlyFees = 0;
                        $totalAmountPaid = 0;
                    @endphp
                    @foreach ($fees_enter_by_creation_date as $index => $fees_enter_by_creation_date)
                        @php
                            $totalMonthlyFees += $fees_enter_by_creation_date->monthly_fees;
                            $totalAmountPaid += $fees_enter_by_creation_date->total_amount_paid;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fees_enter_by_creation_date->student->first_name }} {{ $fees_enter_by_creation_date->student->last_name }}
                                        <br> {{ $fees_enter_by_creation_date->student->student_id }}
                            </td>
                            <td>{{ $fees_enter_by_creation_date->currency }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_enter_by_creation_date->start_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_enter_by_creation_date->end_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($fees_enter_by_creation_date->receive_date)->format('d M, Y') }}</td>
                            <td class="text-success"><strong>{{ number_format($fees_enter_by_creation_date->monthly_fees, 2) }}</strong></td>
                            <td class="text-danger"><strong>{{ number_format($fees_enter_by_creation_date->total_amount_paid, 2) }}</strong></td>
                        </tr>
                    @endforeach
                    <!-- 🚀 Total Row -->
                    <tr class="table-footer">
                        <td colspan="5" class="text-right"></td>
                        <td colspan="1" class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-success"><strong>{{ number_format($totalMonthlyFees, 2) }}</strong></td>
                        <td class="text-danger"><strong>{{ number_format($totalAmountPaid, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide all tables except the first one (Fees Due & Inactive)
        document.querySelectorAll('.fees-table').forEach((table, index) => {
            if (index !== 0) {
                table.style.display = 'none';
            }
        });
    });

    function showTable(tableId) {
        // Hide all tables
        document.querySelectorAll('.fees-table').forEach(table => {
            table.style.display = 'none';
        });

        // Show the selected table
        document.getElementById(tableId).style.display = 'block';
    }
</script>

<!-- 🚀 JavaScript to Handle Button Clicks -->
<script>
    function showTable(tableId) {
        // Hide all tables
        document.querySelectorAll('.fees-table').forEach(table => {
            table.style.display = 'none';
        });

        // Show the selected table
        document.getElementById(tableId).style.display = 'block';
    }
</script>

<style>
    .table-footer {
    background-color: #f1f1f1;
    font-weight: bold;
}
.text-right {
    text-align: right;
}

    /* Custom Styling */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    }

    .custom-table thead {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
    }

    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .custom-table tbody td {
        padding: 12px;
        text-align: center;
        font-size: 14px;
        border-bottom: 1px solid #ddd;
    }

    .text-success {
        color: #28a745 !important;
        font-weight: bold;
    }

    .text-danger {
        color: #dc3545 !important;
        font-weight: bold;
    }

    .container {
        max-width: 95%;
    }

    /* Button Styling */
    .btn {
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
    }
</style>
