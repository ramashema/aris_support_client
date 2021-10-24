@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 my-5">
                {{ $user }}
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark text-light text-center display-6" colspan="2">
                            {{ $user->name }}
                        </th>
                    </tr>
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
                        <td>{{ \Carbon\Carbon::createFromTimestamp(strtotime($user->last_login))->diffForHumans() }}</td>
                    </tr>

                    <tr>
                        <th class="bg-dark text-light">Privilege</th>
                        <td>{{ $user->privilege }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

@endsection
