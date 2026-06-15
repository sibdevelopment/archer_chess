<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <p>Redirecting to payment gateway...</p>
    <form id="paymentForm" action="{{ $redirectUrl }}" method="POST">
        @foreach ($postData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <script type="text/javascript">
        document.getElementById('paymentForm').submit();
    </script>
</body>
</html>
