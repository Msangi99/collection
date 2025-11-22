<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ __('all.airtel_money_payment_tanzania') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>{{ __('all.airtel_money_payment') }}</h3>
        <form action="{{ route('airtel.payment') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">{{ __('all.amount_tzs') }}</label>
                <input type="number" class="form-control" id="amount" name="amount" min="100" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">{{ __('all.phone_number') }}</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <div class="mb-3">
                <label for="reference" class="form-label">{{ __('all.reference') }}</label>
                <input type="text" class="form-control" id="reference" name="reference" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('all.pay_with_airtel_money') }}</button>
        </form>
    </div>
</body>
</html>
