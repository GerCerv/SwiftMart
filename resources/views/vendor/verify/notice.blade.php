<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <div>
        <h2>Your account is not verified yet!</h2>
        <p>Please check your email for the verification link.</p>
        <a href="{{ route('vendor.resend.verification') }}">Resend Verification Email</a>
    </div>
</body>
</html>
