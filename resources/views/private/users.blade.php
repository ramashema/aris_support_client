
@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <p class="display-4 my-3 pb-3 border-bottom">List of users</p>

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
                        <span class="badge border bg-primary float-end"><a href="{{ route('private.users_list')  }}" style="text-decoration: none; color: white">Users @if ($users->total() > 0) ({{ ($users->total()) }}) @endif</a></span>
                    @endif
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-8 offset-2">
                @if(count($users) == 0)
                      Checking if the there is any user registered
                    <p class="text-muted text-center">-- No user registered in the system , please come back later --</p>
                @else

                    <p class="text-muted">
                        Showing {{($users->currentPage()-1)* $users->perPage()+($users->total() ? 1:0)}} to {{($users->currentPage()-1)*$users->perPage()+count($users)}}  of  {{$users->total()}}  Results
                    </p>

                    <table class="table table-striped table-bordered shadow">
                        <tr class="bg-dark text-white">
                            <th class="text-center">#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Privilege</th>
                            <th>Last Login</th>
                        </tr>
{{--                             Define the variable counter for counting the requsts--}}


                        @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}.</td>
{{--                                <td class="text-center">{{  $loop->iteration }}.</td>--}}
                                <td><a href="{{ route('private.user', $user) }}" style="text-decoration: none; color: black">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->privilege }}</td>
                                <td>
                                    <p class="badge bg-dark text-white">@if($user->last_login){{ \Carbon\Carbon::createFromTimestamp( strtotime($user->last_login ))->diffForHumans()}} @else Never  @endif</p>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                <div>
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
