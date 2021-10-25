<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ARIS Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous" defer></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
            <a class="navbar-brand mx-4" href="{{route('request.homepage')}}">Aris Support</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end mx-4" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    @guest
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item active">
                            <span class="text-white mx-3">Name: {{ auth()->user()->name }}</span>
                        </li>

                        <li class="nav-item active">
                            <form action="{{ route('auth.logout') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                            </form>

                        </li>
                    @endauth
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#">Features</a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="#">Pricing</a>--}}
{{--                    </li>--}}
                </ul>
{{--                <span class="navbar-text">--}}
{{--                    Navbar text with an inline element--}}
{{--                </span>--}}
            </div>
    </nav>

    @yield('contents')


    <script type="text/javascript" src="app_script.js"></script>
</body>
