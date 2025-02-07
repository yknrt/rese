<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe決済</title>
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <script>
        function updateAmount() {
            var amount = document.getElementById('amount').value;
            document.getElementById('stripe-button').setAttribute('data-amount', amount);
        }
    </script>
</head>
<body>
    <div class="payment">
        <h2 class="payment-ttl">Stripe決済</h2>
        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <form action="/charge" method="POST" onsubmit="updateAmount()">
            @csrf
            <input type="hidden" name="reservation_id" value="{{ $id }}">
            <label for="amount">金額 (円):</label>
            <input type="number" id="amount" name="amount" min="1" required>
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button" id="stripe-button"
                data-key="{{ env('STRIPE_KEY') }}"
                data-name="Stripe決済デモ"
                data-label="決済をする"
                data-description="これはデモ決済です"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-currency="JPY">
            </script>
        </form>
    </div>
</body>
</html>