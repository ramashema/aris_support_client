@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row my-5">
            <div class="col-6 offset-3">
                <h1 class="display-5 my-3 pb-3 border-bottom">Attend support request</h1>
                <!-- The messages sections -->

                @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

            <!-- End of the message section -->
                <div class="my-2">
                    <a class="badge bg-dark" href="{{ route('private.dashboard') }}" style="text-decoration: none; color: white">Home</a>
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark text-white" style="width: 30%">Student Name</th>
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

                    @if($support_request->attended)
                        <tr>
                            <td colspan="2" class="bg-success text-white text-center">
                                This case has already been attended
                            </td>
                        </tr>
                    @endif

                </table>


                @if ($support_request->descriptions == 'Password Reset')

                    <form action="{{ route('student.password_reset', [$support_request]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn float-end btn-sm btn-success">Reset password</button>
                    </form>
                @else
                    @if (!$support_request->attended)
                        <form action="{{ route('attend_other_support', [$support_request]) }}" method="post">
                            @csrf
                            <button type="submit" class="btn  btn-sm btn-success float-end">Attend</button>
                        </form>
                    @else
                        <a class="btn btn-sm btn-success float-end" href="https://aris.mzumbe.ac.tz" target="_blank">Open Aris</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
