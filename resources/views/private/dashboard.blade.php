@extends('layout.app')

@section('contents')
    <p>Name: {{ auth()->user()->name }}</p>

    @if( strtotime(auth()->user()->last_login ) != strtotime('0000-00-00 00:00:00'))
        <p>Last Login: {{ auth()->user()->last_login }}</p>
    @endif

@endsection
