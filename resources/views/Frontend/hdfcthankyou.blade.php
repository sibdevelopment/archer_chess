<style>
    #payment-response {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-width: 600px;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #payment-response .response-header {
        text-align: center;
        margin-bottom: 20px;
    }

    #payment-response .response-header h2 {
        color: #333;
        font-size: 24px;
        margin: 0;
    }

    #payment-response .response-message {
        padding: 15px;
        border-left: 5px solid;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    #payment-response .response-message.success {
        border-color: #4CAF50;
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    #payment-response .response-message.error {
        border-color: #f44336;
        background-color: #ffebee;
        color: #c62828;
    }

    #payment-response .response-message .icon {
        font-size: 30px;
        margin-right: 15px;
    }

    #payment-response ul {
        list-style-type: none;
        padding: 0;
        margin: 10px 0;
    }

    #payment-response ul li {
        margin: 5px 0;
    }

    #payment-response a {
        color: #1e88e5;
        text-decoration: none;
        font-weight: bold;
    }

    #payment-response a:hover {
        text-decoration: underline;
    }

    #payment-response .btn {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        color: #fff;
        background-color: #1e88e5;
        text-align: center;
    }

    #payment-response .btn:hover {
        background-color: #1565c0;
    }
</style>
<br><br><br><br>
<br><br><br><br>
<br><br>
<div id="payment-response">
    <div class="response-header">
        <h2>Payment Response</h2>
    </div>

    @if ($response['status'] === 'CHARGED')
        <div class="response-message success">
            <span class="icon">✔️</span>
            <div>
                <p>Payment was successful!</p>
                <ul>
                    <li><strong>Order ID:</strong> {{ $response['order_id'] }}</li>
                    <li><strong>Amount:</strong> {{ $orderDetails['amount'] }} {{ $orderDetails['currency'] }}</li>
                    <li><strong>Customer Email:</strong> {{ $orderDetails['customer_email'] }}</li>
                    <li><strong>Transaction ID:</strong> {{ $orderDetails['id'] }}</li>
                </ul>
                {{-- <a href="{{ $orderDetails['payment_links']['web'] }}" target="_blank" class="btn">View Payment Details</a> --}}
                <a href="/student/login" class="btn">Login</a>
            </div>
        </div>
    @else
        <div class="response-message error">
            <span class="icon">❌</span>
            <div>
                <p>Payment failed or is pending.</p>
                <p><strong>Status:</strong> {{ $orderDetails['status'] }}</p>
                <p>Please contact support for further assistance.</p>
            </div>
        </div>
    @endif
</div>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
<br><br><br>
{{-- <p>{{ json_encode($response, JSON_PRETTY_PRINT) }}</p> --}}

<p>{{ json_encode($orderDetails, JSON_PRETTY_PRINT) }}</p>
