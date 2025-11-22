@php
/** @var string $message */
/** @var string|null $transToken */
/** @var array $queryParams */
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Payment Canceled</title>
</head>
<body>
    <h2>Transaction Canceled</h2>
    <pre>
{{ $message }}
@if($transToken)
Transaction Token: {{ $transToken }}
@endif
Query Parameters:
@php
print_r($queryParams);
@endphp
    </pre>
</body>
</html>