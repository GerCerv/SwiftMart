<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Hello, {{ $user->name }}</h2>
    <p>Thank you for signing up! Please click the link below to verify your email:</p>
    <a href="{{ url('/verify-email/'.$user->id) }}">Verify Email</a>
    <p>If you didn't register, you can ignore this email.</p>
</body>
</html>
