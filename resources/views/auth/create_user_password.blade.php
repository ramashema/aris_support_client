@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 p-5 my-5 border bg-white rounded">
                <h1 class="text-center display-6 mb-4 border-bottom">Create password</h1>


                <form action="{{ route('auth.create_user_password', auth()->user()) }}" method="post">
                    @csrf

                    @if($success)
                        <div class="alert alert-success text-center">{{ $success }}</div>
                    @endif


                    <div class="form-group my-2">
                        <label for="password">Password</label>
                        <input class="form-control @error('password') border-danger  @enderror" type="password" id="password" name="password" placeholder="Your password" required>
                    </div>

                    <div style="color: red">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="confirm_password">Confirm Password</label>
                        <input class="form-control @error('password_confirmation') border-danger  @enderror" type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                    </div>

                    <div style="color: red">
                        @error('password_confirmation')
                        {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group my-3">
                        <button class="btn btn-success w-100" type="submit">Create Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
