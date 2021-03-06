<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <small>Pazzle Side</small>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <form action="/Register_Order" method="get">
                            @csrf
                            <button class="btn btn-link" type="submit">
                                Origin Item Order
                            </button>
                        </form>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-link" href="/Confirmation_Cart">
                            Cart
                        </a>
                    </li>
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <form action="/History_Cart" method="get">
                                @csrf
                                <button class="btn btn-link" value="{{Auth::user()->id}}" name="userid" type="submit">
                                    History Cart
                                </button>
                            </form>
                        </li>

                        <li class="nav-item">
                            <form action="/All_Address" method="get">
                                @csrf
                                <button type="submit" value="{{Auth::user()->id}}" name="userid" class="btn btn-link">
                                    Address
                                </button>
                            </form>
                        </li>


                        <li class="nav-item">
                            <form action="/Edit_User" method="get">
                                @csrf
                                <button type="submit" value="{{Auth::user()->id}}" name="userid" class="btn btn-link">
                                    Setting
                                </button>
                            </form>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>



                        @if(Auth::user()->rank == 1)
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    AdminMenu <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a href="/admin/All_Address" class="dropdown-item">Address Management</a>
                                    <a href="/admin/All_Item" class="dropdown-item">Item Management</a>
                                    <a href="/admin/All_ItemComment" class="dropdown-item">Item Comment Management</a>
                                    <a href="/admin/All_Order" class="dropdown-item">Order Management</a>
                                    <a href="/admin/All_Peas" class="dropdown-item">Peas Management</a>
                                    <a href="/admin/All_Size" class="dropdown-item">Size Management</a>
                                    <a href="/admin/All_Spot" class="dropdown-item">Spot Management</a>
                                    <a href="/admin/All_SpotComment" class="dropdown-item">Spot Comment Management</a>
                                    <a href="/admin/All_User" class="dropdown-item">User Management</a>
                                </div>
                            </li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <main>
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
