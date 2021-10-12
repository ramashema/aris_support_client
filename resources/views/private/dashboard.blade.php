@extends('layout.app')

@section('contents')
    <p>Name: {{ auth()->user()->name }}</p>
    <p>Last Login: {{ auth()->user()->last_login }}</p>
@endsection
