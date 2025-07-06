@component('mail::message')
# Hello {{ $vendor->name }}!

Thank you for registering as a vendor with {{ config('app.name') }}.

Please click the button below to verify your email address:

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent