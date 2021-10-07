@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 p-5 my-5 border bg-white rounded">
                <h1 class="text-center display-4 mb-4 border-bottom">Login</h1>

                <form action="{{ route('auth.login') }}" method="post">
                    @csrf
                    {{--Login form--}}
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
                </form>
            </div>
        </div>
    </div>
@endsection