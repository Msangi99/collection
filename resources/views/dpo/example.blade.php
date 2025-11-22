<!DOCTYPE html>
<html>
<head>
    <title>Initiate Payment</title>
</head>
<body>
    <h2>Initiate DPO Payment</h2>
    <form action="{{ route('dpo.initiate') }}" method="POST">
        @csrf
        <div>
            <label>Amount (TZS):</label>
            <input type="number" name="amount" value="1000" required>
        </div>
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" value="John" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" value="Doe" required>
        </div>
        <div>
            <label>Phone:</label>
            <input type="text" name="phone" value="+255696646570" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="john.doe@example.com" required>
        </div>
        <button type="submit">Pay Now</button>
    </form>
</body>
</html>