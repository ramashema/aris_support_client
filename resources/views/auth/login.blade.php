@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 p-5 mt-5 border bg-white rounded">
                <h1 class="text-center display-4 mb-4 border-bottom">Login</h1>

                <form action="{{ route('auth.login') }}" method="post">
                    @csrf
                    {{--Login form--}}
                    @if(session()->has('error'))
                        <div class="alert alert-danger text-center">{{ session('error') }}</div>
                    @endif

                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control @error('email') border-danger  @enderror" type="email" id="email" name="email" placeholder="e.g user@mzumbe.ac.tz" required>
                    </div>

                    <div style="color: red">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group my-2">
                        <label for="password">Password</label>
                        <input class="form-control @error('email') border-danger  @enderror" type="password" id="password" name="password" placeholder="Your password" required>
                    </div>

                    <div style="color: red">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>

                    <div class="form-group my-3">
                        <button class="btn btn-success w-100" type="submit">Login</button>
                    </div>

                    <div>
                        <a href="{{ route('user.password_reset') }}" style="text-decoration: none">Forgot your password?</a>
                    </div>
                </form>
            </div>
            <div class="text-center mt-3 text-muted">
                ZamoTechnologies &copy;{{ \Carbon\Carbon::now()->year }}
            </div>
        </div>
    </div>
@endsection
