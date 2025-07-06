@component('mail::message')
# Hello {{ $vendor->name }},

We’re sorry to let you know that your vendor account ({{ $vendor->store_name }}) has been **suspended** by the admin.



Thanks,  
{{ config('app.name') }} Team
@endcomponent
