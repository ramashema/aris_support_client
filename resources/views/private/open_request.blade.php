@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row my-5">
            <div class="col-6 offset-3">
                <h1 class="display-5 my-3">Attend support request</h1>
                <div class="my-2">
                    <a class="badge bg-dark" href="{{ route('private.dashboard') }}">Home</a>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark text-white">Student Name</th>
                        <td>{{ $support_request->user->name }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-white">Descriptions</th>
                        <td>{{ $support_request->descriptions }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-white">Requested</th>
                        <td>{{ \Carbon\Carbon::createFromTimestamp( strtotime($support_request->created_at ))->diffForHumans()}}</td>
                    </tr>

                </table>

                @if ($support_request->descriptions == 'Password Reset')
                    <form action="{{ route('user.password_reset', [$support_request->user, $support_request]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn float-end btn-sm btn-success">Reset password</button>
                    </form>
                @else
                    <a href="https://aris.mzumbe.ac.tz" target="_blank" class="btn float-end btn-sm btn-success">Open ARIS</a>
                @endif
            </div>
        </div>
    </div>
@endsection
