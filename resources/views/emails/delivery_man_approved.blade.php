<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Delivery Team</title>
</head>
<body>
    <h1>Welcome, {{ $name }}!</h1>
    <p>Your account has been approved by the admin.</p>
    <p>You can now log in to our system using:</p>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Password: 123456</li>
    </ul>
    <p>Please change your password after logging in for security purposes.</p>
    <p>Best regards,<br>Marketplace Admin Team</p>
</body>
</html>