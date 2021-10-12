@extends('layout.app')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="display-4 my-3">Dashboard</p>
                @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00') || strtotime(auth()->user()->last_login != null) )
                    <p>Last Login: {{ \Carbon\Carbon::createFromTimestamp(strtotime(auth()->user()->last_login))->diffForHumans() }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
