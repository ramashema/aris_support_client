@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <p class="display-4 my-3 pb-3 border-bottom">Dashboard</p>
                @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00') || strtotime(auth()->user()->last_login != null) )
                    <p class="badge bg-success text-white">Last Login: {{ \Carbon\Carbon::createFromTimestamp(strtotime(auth()->user()->last_login))->diffForHumans() }}</p>
                @endif
                <div class="mb-3">
                    <span class="badge bg-light text-dark border"><a href="{{route('private.dashboard')}} " style="text-decoration: none; color: black">Unattended Requests</a></span>
                    <span class="badge bg-dark">Attended Requests</span>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-8 offset-2">
                @if(count($support_requests) == 0)
                    {{--  Checking if the there is no unattended requests    --}}
                    <p class="text-muted text-center">-- No un-attended requests , please come back later --</p>
                @else

                    {{--    The messages sections  --}}
                    @if (session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger text-center">
                            {{ session('error') }}
                        </div>
                    @endif


                    {{--End of the message section--}}
                        <table class="table table-striped shadow">
                            <tr class="bg-dark text-white">
                                <th>Student Name</th>
                                <th>Request Descriptions</th>
                                <th>Requested</th>
                            </tr>

                            @foreach($support_requests as $support_request)
                                <tr>
                                    <td>{{ $support_request->user->name }}</td>
                                    <td><a href="{{ route('private.open_request', $support_request) }} " style="text-decoration: none; color: black"> {{ $support_request->descriptions }}</a></td>
                                    <td>
                                        <p class="badge bg-dark text-white ">{{ \Carbon\Carbon::createFromTimestamp( strtotime($support_request->created_at ))->diffForHumans()}}</p>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                            </tr>
                        </table>

                @endif
                <div>
                    {!! $support_requests->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
