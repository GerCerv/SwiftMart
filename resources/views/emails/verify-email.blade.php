<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>Please click the button below to verify your email address.</p>
    
    <a href="{{ $verificationUrl }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;">
        Verify Email Address
    </a>
    
    <p>If you did not create an account, no further action is required.</p>
    
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>