@php
/** @var string $transToken */
/** @var SimpleXMLElement $responseData */
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <h2>Payment Verification Successful</h2>
    <pre>
Transaction Token: {{ $transToken }}

Response Data:
@php
print_r($responseData);
@endphp
    </pre>
</body>
</html>