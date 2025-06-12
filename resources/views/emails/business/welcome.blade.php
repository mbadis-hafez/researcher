@component('mail::message')
# Welcome to {{ config('app.name') }}, {{ $client->full_name }}!

Thank you for registering as a business client. We're excited to have you on board!

**Your Details:**  
- **Name:** {{ $client->full_name }}  
- **Email:** {{ $client->email }}  
- **Job Title:** {{ $client->job_title }}  
- **Business Type:** {{ $client->business_type }}  

@component('mail::button', ['url' => route('verification.verify', ['id' => $client->id, 'hash' => sha1($client->email)])])
Verify Email Address
@endcomponent

Please verify your email address to complete your registration. If you didn't create an account, no further action is required.

Thanks,  
{{ config('app.name') }} Team

@component('mail::subcopy')
If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:  
[{{ route('verification.verify', ['id' => $client->id, 'hash' => sha1($client->email)]) }}]({{ route('verification.verify', ['id' => $client->id, 'hash' => sha1($client->email)]) }})
@endcomponent
@endcomponent