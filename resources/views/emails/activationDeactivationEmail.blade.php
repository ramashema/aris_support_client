@component('mail::message')
# {{ $email_details['title'] }}

<h2>Hello, {{ $email_details['name'] }}</h2>

<p>
@if($email_details['password'])
    Your account has been activated, and you new password is "{{ $email_details['password'] }}".
    This password is not known by administrator, but you can change immediately after logged in.
@else
    Your account has been deactivated, therefore you won't be able to logging.
    If you want to know why this is happening, please contact System Administrator
@endif
</p>

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
