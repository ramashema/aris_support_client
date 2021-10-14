@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <p class="display-4 my-3">Dashboard</p>
                @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00') || strtotime(auth()->user()->last_login != null) )
                    <p>Last Login: {{ \Carbon\Carbon::createFromTimestamp(strtotime(auth()->user()->last_login))->diffForHumans() }}</p>
                @endif
            </div>

        </div>

        <div class="row">
            <div class="col-8 offset-2">
                @empty( $support_requests )
                    <p class="text-muted text-center">--No un-attended requests--</p>
                @else


                <!-- The messages sections -->

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

                <!-- End of the message section -->

                    <table class="table table-bordered rounded">
                        <tr class="bg-dark text-white">
                            <th>Student Name</th>
                            <th>Request Descriptions</th>
                        </tr>

                        @foreach($support_requests as $support_request)
                            <tr>
                                <td>{{ $support_request->user->name }}</td>
                                <td><a href="{{ route('private.open_request', $support_request) }}"> {{ $support_request->descriptions }}</a></td>
                            </tr>
                        @endforeach
                        <tr>
                        </tr>
                    </table>

                @endempty
            </div>
        </div>
    </div>
@endsection
