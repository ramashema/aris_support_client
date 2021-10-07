@extends('layout.app')

@section('contents')
    Name: {{ auth()->user()->name }}
    Last Login: {{ auth()->user()->last_login }}
@endsection
