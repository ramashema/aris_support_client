@component('mail::message')
# Account Successfully created
<h5>Hello, {{ $user_details['name'] }}!</h5>


<p>You have been registered to ARIS as {{ $user_details['privilege'] }}, your account details are as follows:</p>
<p><b>Username: </b>{{ $user_details['email'] }}</p>
<p><b>Password: </b>{{ $user_details['password'] }}</p>

<p><a href="{{ route('auth.login') }}">Click here to login into your account</a></p>

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
