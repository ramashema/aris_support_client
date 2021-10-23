@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <p class="display-4 my-3 pb-3 border-bottom">Dashboard</p>

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
                    <span class="badge bg-dark">Unattended Requests ({{ $support_requests->total() }})</span>
                    <span class="badge bg-light text-dark border"><a href="{{route('private.dashboard.attended')}}" style="text-decoration: none; color: black">Attended Requests</a></span>
                    @if (auth()->user()->privilege == 'admin' )
                        <span class="badge border text-primary float-end mx-2"><a href="{{ route('auth.register') }}" style="text-decoration: none">Register new user</a></span>
                        <span class="badge border text-primary float-end"><a href="#" style="text-decoration: none">Users</a></span>
                    @endif
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-8 offset-2">
                @if(count($support_requests) == 0)
                {{--  Checking if the there is no unattended requests    --}}
                    <p class="text-muted text-center">-- No un-attended requests , please come back later --</p>
                @else
                    <p class="text-muted">
                        Showing {{($support_requests->currentPage()-1)* $support_requests->perPage()+($support_requests->total() ? 1:0)}} to {{($support_requests->currentPage()-1)*$support_requests->perPage()+count($support_requests)}}  of  {{$support_requests->total()}}  Results
                    </p>
                    <table class="table table-striped table-bordered shadow">
                        <tr class="bg-dark text-white">
                            <th class="text-center">#</th>
                            <th>Student Name</th>
                            <th>Request Descriptions</th>
                            <th>Requested</th>
                        </tr>
                        {{--     Define the variable counter for counting the requsts      --}}


                        @foreach($support_requests as $support_request)
                            <tr>
                                <td class="text-center">{{ ($support_requests->currentPage()-1) * $support_requests->perPage() + $loop->iteration }}.</td>
                                <td>{{ $support_request->user->name }}</td>
                                <td><a href="{{ route('private.open_request', $support_request) }} " style="text-decoration: none; color: black"> {{ $support_request->descriptions }}</a></td>
                                <td>
                                    <p class="badge text-white @if (\Carbon\Carbon::createFromTimestamp( strtotime($support_request->created_at ))->diff(\Carbon\Carbon::now())->days > 3) bg-danger @else bg-dark @endif">{{ \Carbon\Carbon::createFromTimestamp( strtotime($support_request->created_at ))->diffForHumans()}}</p>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                <div>
                    {!! $support_requests->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
