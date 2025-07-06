@component('mail::message')
# Hello {{ $vendor->name }},

We’re excited to let you know that your vendor account ({{ $vendor->store_name }}) has been **approved** by the admin.

You can now log in and start managing your products.

@component('mail::button', ['url' => url('/vendor/login')])
Login to Your Account
@endcomponent

Thanks,  
{{ config('app.name') }} Team
@endcomponent
