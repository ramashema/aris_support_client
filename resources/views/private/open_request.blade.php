@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row my-5">
            <div class="col-6 offset-3">
                <h1 class="display-5 my-3">Attend support request</h1>
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
                    <a href="#" class="btn float-end btn-sm btn-success">Reset password</a>
                @else
                    <a href="https://aris.mzumbe.ac.tz" target="_blank" class="btn float-end btn-sm btn-success">Open ARIS</a>
                @endif
            </div>
        </div>
    </div>
@endsection
