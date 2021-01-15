<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="container-fluid">

        <div class="row">
            <header style='width:100%;height: 200px;'>
                <img src="/images/header.jpg" style='width:100%;height:100%;object-fit:cover'>
            </header>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light" role="group" aria-label="Basic example" style="width:100%;box-shadow:0 0 2px 2px rgba(0,0,0,0.1);padding-left: 10px;padding-right: 10px;">
            <ul class="navbar-nav mr-auto" id="navbar">
                @if(Request::is('/'))
                <li class="nav-item"><a type="button" class="nav-link active" href="/">Главная</a></li>
                @else
                <li class="nav-item"><a type="button" class="nav-link" href="/">Главная</a></li>
                @endif
                @if(Request::is('searchtour'))
                <li class="nav-item"><a type="button" class="nav-link active" href="/searchtour">Поиск туров</a></li>
                @else
                <li class="nav-item"><a type="button" class="nav-link" href="/searchtour">Поиск туров</a></li>
                @endif
                @if(Request::is('hottour'))
                <li class="nav-item"><a type="button" class="nav-link active" href="/hottour">Горящие туры</a></li>
                @else
                <li class="nav-item"><a type="button" class="nav-link" href="/hottour">Горящие туры</a></li>
                @endif
                @if(Request::is('hotels'))
                <li class="nav-item"><a type="button" class="nav-link active" href="/hotels">Отели</a></li>
                @else
                <li class="nav-item"><a type="button" class="nav-link" href="/hotels">Отели</a></li>
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <ul class="navbar-nav ml-auto">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                </ul>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Выход') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <script src="{{ asset('js/app.js') }}"></script>
    </div>
</body>
</html>
