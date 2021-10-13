@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="display-4 my-3">Dashboard</p>
                @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00') || strtotime(auth()->user()->last_login != null) )
                    <p>Last Login: {{ \Carbon\Carbon::createFromTimestamp(strtotime(auth()->user()->last_login))->diffForHumans() }}</p>
                @endif
            </div>

            @if( $support_requests )
                <div class="row">

                    <div class="col-12">
                        <table class="table table-bordered rounded">
                            <tr class="bg-dark text-white">
                                <th>Student Name</th>
                                <th>Request Descriptions</th>
                            </tr>

                            @foreach($support_requests as $support_request)
                                <tr>
                                    <td>{{ $support_request->user->name }}</td>
                                    <td><a href="{{ route('private.open_request', $support_request) }}"> {{ $support_request->descriptions }}</a></td>

{{--                                    @if ($support_request->descriptions == "Password Reset")--}}
{{--                                        <form action="#" method="post">--}}
{{--                                            @csrf--}}
{{--                                            <td><input type="submit" class="btn btn-sm btn-success" value="Reset Password"></td>--}}
{{--                                        </form>--}}
{{--                                    @else--}}
{{--                                        <td><a class="btn btn-sm btn-primary" href="{{ route('private.open_request', $support_request->id) }}">Open</a></td>--}}
{{--                                    @endif--}}


                                </tr>

                            @endforeach
                            <tr>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-muted text-center">--No un-attended requests--</p>
            @endif
        </div>
    </div>
@endsection
