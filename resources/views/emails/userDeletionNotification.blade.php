@component('mail::message')
# {{ $email_details['title'] }}

<h2>Hello, {{ $email_details['name'] }}!</h2>
<p>This is to notify you that you account have been deleted, If you want to know the reason for this action please contact Administrator.</p>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
