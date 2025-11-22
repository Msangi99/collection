@php
    /** @var string $error */
    /** @var string|null $transToken */
    /** @var array|SimpleXMLElement|null $responseData */
    /** @var array $queryParams */
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Payment Error</title>
</head>

<body>
    <h2>Payment Verification Failed</h2>
    <pre>
Error: {{ $error }}
@if ($transToken)
Transaction Token: {{ $transToken }}
@endif
@if ($responseData)
Response Data:
@php
    print_r($responseData);
@endphp
@endif
Query Parameters:
@php
    print_r($queryParams);
@endphp
    </pre>
</body>

</html>
