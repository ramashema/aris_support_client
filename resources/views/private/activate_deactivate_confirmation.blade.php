@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 my-5">
                @if($user->is_active)
                    <h2 class="display-6 border-bottom pb-2">User deactivation confirmation</h2>
                    <p>
                        You are about to deactivate a system user. Do you confirm your action?<br>
                        <small class="text-muted">Note: User (Account Owner) will be notified about this.</small>
                           <form action="{{ route('user.activate_deactivate', $user) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-danger" type="submit">Yes, I confirm</button>
                            </form>

                            <a class="btn btn-primary btn-sm my-2" href="{{ route('private.user', $user) }}">No, Go back</a>

                    </p>
                @else
                    <h2 class="display-6 border-bottom pb-2">User activation confirmation</h2>
                    <p>
                        You are about to activate a system user. Do you confirm your action?<br>
                        <small class="text-muted">Note: User (Account Owner) will be notified about this.</small>
                    <form action="{{ route('user.activate_deactivate', $user) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger" type="submit">Yes, I confirm</button>
                    </form>

                    <a class="btn btn-primary btn-sm my-2" href="{{ route('private.user', $user) }}">No, Go back</a>

                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

