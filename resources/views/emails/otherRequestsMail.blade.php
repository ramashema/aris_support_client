@component('mail::message')
# {{ $details_to_email['title'] }}

<p>Dear {{ $details_to_email['full_name'] }} <b>[{{ $details_to_email['username'] }}]</b>.</p>
<p>ARIS team has attended your reported case, please verify that your problem has been resolve and if not, please feel free to let the team know that the issue has not been resolved!</p>


@component('mail::button', ['url' => 'https://aris.mzumbe.ac.tz'])
Open ARIS
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
