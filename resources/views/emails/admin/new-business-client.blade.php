@component('mail::message')
# New Business Client Registration

A new business client has registered:

**Client Details:**  
- **Name:** {{ $client->full_name }}  
- **Email:** {{ $client->email }}  
- **Job Title:** {{ $client->job_title }}  
- **Phone:** {{ $client->phone }}  
- **Business Type:** {{ $client->business_type }}  
@if($client->specific_business_type)
- **Specific Business Type:** {{ $client->specific_business_type }}  
@endif

**Registration Time:** {{ $client->created_at->format('F j, Y \a\t g:i a') }}  

@component('mail::button', ['url' => route('admin.clients.show', $client->id)])
View Client Dashboard
@endcomponent
@endcomponent