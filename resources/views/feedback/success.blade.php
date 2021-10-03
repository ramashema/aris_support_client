@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row my-5">
            <div class="col-6 offset-3 text-center">
                @if($success)
                    <h3 class="text-success display-4">Success</h3>
                    <p>{{ $success }}</p>
                    <a href="{{ route('request.homepage') }}" class="btn btn-primary" title="Go Back Home">Go Back</a>
                @endif

{{--                @if($error)--}}
{{--                        <h3 class="text-center text-danger display-4">Error</h3>--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
@endsection
