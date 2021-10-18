@component('mail::message')
# {{ $details_to_email['title'] }}

<p>Dear {{$details_to_email['full_name']}}</p>
<p>ARIS team has successful performed password reset to your ARIS account.</p>
<p>Your new login details are:</p>
<p>Username: {{$details_to_email['username']}}<br>Password: {{$details_to_email['password']}}</p>


@component('mail::button', ['url' => 'https://aris.mzumbe.ac.tz'])
Open ARIS
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
