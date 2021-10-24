@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 my-5">
                    <h2 class="display-6 border-bottom pb-2">User deletion confirmation</h2>
                    <p>
                        You are about to delete a system user. Do you confirm your action?<br>
                        <small class="text-muted">Note: User (Account Owner) will be notified about this.</small>
                    <form action="{{ route('user.delete', $user) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger" type="submit">Yes, I confirm</button>
                    </form>

                    <a class="btn btn-primary btn-sm my-2" href="{{ route('private.user', $user) }}">No, Go back</a>

                    </p>
            </div>
        </div>
    </div>
@endsection

