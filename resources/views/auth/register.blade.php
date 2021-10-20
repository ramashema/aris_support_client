@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-4 offset-4 p-5 my-5 border bg-white rounded">
                <h1 class="text-center display-4 mb-4 border-bottom">Fill in the form</h1>

                {{--    The messages sections [this is repeating code should be considered to be moved to component]  --}}
                @if (session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif
                {{--End of the message section--}}

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control @error('first_name') border-danger  @enderror" id="first_name" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">

                        <div class="text-danger">
                            @error('first_name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control @error('middle_name') border-danger  @enderror" id="middle_name" name="middle_name" placeholder="Middle Name" value="{{ old('middle_name') }}">

                        <div class="text-danger">
                            @error('middle_name')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="surname">SurName</label>
                        <input type="text" class="form-control @error('surname') border-danger  @enderror" id="surname" name="surname" placeholder="Last Name" value="{{ old('surname') }}">

                        <div class="text-danger">
                            @error('surname')
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
                        <label for="privilege">Privilege</label>
                        <select class="form-control @error('privilege') border-danger  @enderror" id="privilege" name="privilege"  >
                            <option selected disabled>--choose privilege group--</option>
                            <option value="support_team">Support team</option>
                            <option value="admin">Administrator</option>
                            <option disabled>Student(currently not applicable)</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <input type="submit" class="btn btn-success btn-sm w-100" value="Register user">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{--<script>--}}
{{--    window.onload = () => {--}}
{{--        // select the elements you want to work with--}}
{{--        let descriptionSelection = document.querySelector("#descriptions");--}}
{{--        let otherDescriptionField = document.querySelector("#other_descriptions");--}}

{{--        descriptionSelection.addEventListener('change', () => {--}}
{{--            // check the if the value of select is equal for other--}}
{{--            if (descriptionSelection.value === "others"){--}}
{{--                otherDescriptionField.style.display = 'block';--}}
{{--            } else {--}}
{{--                otherDescriptionField.style.display = 'none';--}}
{{--            }--}}
{{--        });--}}
{{--    }--}}
{{--</script>--}}


