@component('mail::message')
# Account Password Reset

<h1>Hello, {{ $details['name'] }}</h1>

<p>You are receiving this because you have requested password reset for your account. <br>
    Your temporally password is <b>{{ $details['password'] }}</b></p>

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
