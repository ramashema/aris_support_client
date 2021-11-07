@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 p-5 my-5 border bg-white rounded">
                <h1 class="text-center display-4 mb-4 border-bottom">Fill in the form</h1>

                {{--    The messages sections  --}}
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

                {{--End of the message section--}}

                <form action="{{ route('request.process') }}" method="post">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="registration_number">Registration Number</label>
                        <input type="text" class="form-control @error('registration_number') border-danger  @enderror" id="registration_number" name="registration_number" placeholder="Your Registration Number" value="{{ old('registration_number') }}">

                        <div class="text-danger">
                            @error('registration_number')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') border-danger  @enderror" id="email" name="email" placeholder="Your Email address" value="{{ old('email') }}">

                        <div class="text-danger">
                            @error('email')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="descriptions">Request Description</label>
                        <select class="form-control @error('descriptions') border-danger  @enderror" id="descriptions" name="descriptions"  >
                            <option selected disabled>--choose the problem--</option>
                            <option value="Password Reset">Password Reset</option>
                            <option value="others">Other problems</option>
                        </select>

                        <div class="text-danger">
                            @error('descriptions')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div id="other_descriptions" class="form-group mt-2" style="display: none">
                        <label for="others">If other please describe</label>
                        <textarea id="others" class="form-control @error('others ') border-danger  @enderror" name="others" placeholder="Describe your problem here...">{{ old('others') }}</textarea>
                    </div>

                    <div class="mt-4">
                        <input type="submit" class="btn btn-success btn-sm w-100" value="Please Help">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    window.onload = () => {
    // select the elements you want to work with
        let descriptionSelection = document.querySelector("#descriptions");
        let otherDescriptionField = document.querySelector("#other_descriptions");

        descriptionSelection.addEventListener('change', () => {
            // check the if the value of select is equal for other
            if (descriptionSelection.value === "others"){
                otherDescriptionField.style.display = 'block';
            } else {
                otherDescriptionField.style.display = 'none';
            }
        });
    }
</script>


