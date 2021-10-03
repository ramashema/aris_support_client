@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 bg-light p-4 my-5 border">
                <h1 class="text-center display-4 my-3">Fill in the form</h1>
                <form action="{{ route('request.process') }}" method="post">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="registration_number">Registration Number</label>
                        <input type="text" class="form-control" id="registration_number" name="registration_number" placeholder="Your Registration Number">

                        <div style="color: red">
                            @error('registration_number')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email address">

                        <div style="color: red">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="descriptions">Request Description</label>
                        <select class="form-control" id="descriptions" name="descriptions">
                            <option selected disabled>--choose the problem--</option>
                            <option value="Password Reset">Password Reset</option>
                            <option value="others">Other problems</option>
                        </select>
                    </div>

                    <div id="other_descriptions" class="form-group mt-2" style="display: none">
                        <label for="others">If other please describe</label>
                        <textarea id="others" class="form-control" name="others" placeholder="Describe your problem here..."></textarea>
                    </div>

                    <div class="my-2">
                        <input type="submit" class="btn btn-primary" value="Request Support">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
