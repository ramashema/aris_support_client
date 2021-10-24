@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 my-5">

                <p class="display-5 my-3 pb-3 border-bottom">{{ $user->name  }}</p>

                {{--    The messages sections  --}}

                @if(session('error'))
                    <p class="alert alert-danger text-center">{{ session('error') }}</p>
                @endif

                @if(session('success'))
                    <p class="alert alert-success text-center">{{ session('success') }}</p>
                @endif

                {{--End of the message section--}}

                <div>
                    @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00') || strtotime(auth()->user()->last_login != null) )
                        <p class="badge bg-success text-white">Last Login: {{ \Carbon\Carbon::createFromTimestamp(strtotime(auth()->user()->last_login))->diffForHumans() }}</p>
                    @endif
                </div>
                <div class="mb-3 pt-2 pb-2 px-1">
                    <span class="badge bg-light text-dark border"><a href="{{route('private.dashboard')}}" style="text-decoration: none; color: black">Unattended Requests</a></span>
                    <span class="badge bg-light text-dark border"><a href="{{route('private.dashboard.attended')}}" style="text-decoration: none; color: black">Attended Requests</a></span>
                    @if (auth()->user()->privilege == 'admin' )
                        <span class="badge border text-primary float-end mx-2"><a href="{{ route('auth.register') }}" style="text-decoration: none">Register new user</a></span>
                        <span class="badge border bg-primary float-end"><a href="{{ route('private.users_list')  }}" style="text-decoration: none; color: white">Users</a></span>
                    @endif
                </div>

                <table class="table table-bordered shadow">
{{--                    <tr>--}}
{{--                        <th class="bg-dark text-light text-center display-6" colspan="2">--}}
{{--                            {{ $user->name }}--}}
{{--                        </th>--}}
{{--                    </tr>--}}
                    <tr>
                        <th class="bg-dark text-light">Email Address</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Email Verified</th>
                        <td>@if($user->email_verified_at) {{ \Carbon\Carbon::createFromTimestamp(strtotime($user->email_verified_at))->diffForHumans() }} @else Not Verified @endif</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Account Created</th>
                        <td>{{ \Carbon\Carbon::createFromTimestamp(strtotime($user->created_at))->diffForHumans() }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Last Login</th>
                        <td>@if ($user->last_login) {{ \Carbon\Carbon::createFromTimestamp(strtotime($user->last_login))->diffForHumans() }} @else Never @endif</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Privilege</th>
                        <td>{{ $user->privilege }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Active</th>
                        <td> @if($user->is_active) Active @else Inactive @endif</td>
                    </tr>
                </table>

                <span><a class="btn btn-sm btn-danger mx-2 float-end" href="{{ route('user.delete', $user) }}">Delete</a></span>
                @if ($user->initial_password_isset)
                    <span><a class="btn btn-sm btn-primary float-end" href="{{route('user.activate_deactivate', $user)}}">@if($user->is_active)Deactivate @else Activate @endif</a></span>
                @else
                    <span><a class="btn btn-sm btn-primary float-end" href="{{route('user.activate_deactivate', $user)}}">Deactivate</a></span>
                @endif


            </div>
        </div>
    </div>

@endsection
