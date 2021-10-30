@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 p-5 my-5 border bg-white rounded">
                <h1 class="text-center display-6 mb-4 border-bottom">Reset password</h1>


                <form action="{{ route('user.process_password_reset_request') }}" method="post">
                    @csrf

                    @if($success )
                        <div class="alert alert-info text-center">{{ $success }}</div>
                    @endif


                    <div class="form-group my-2">
                        <label for="email">Email</label>
                        <input class="form-control @error('email') border-danger  @enderror" type="email" id="email" name="email" placeholder="Your Email">
                    </div>

                    <div class="text-danger">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group my-3">
                        <button class="btn btn-success w-100" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
