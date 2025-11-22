<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome, {{ $data->name }}</h1>
    <p>Dear {{ $data->name }},</p>
    <p>You have requested to reset your password. Please use the following code to proceed:</p>
    <p><strong>Reset Code: {{ $data->remember_token }}</strong></p>
    <p>Enter this code on the password reset page to create a new password. This code will expire in 30 minutes for security reasons.</p>
    <p>If you did not request a password reset, please ignore this email or contact our support team.</p>
    <p>Best regards,<br>Your Application Team</p>
</body>
</html>