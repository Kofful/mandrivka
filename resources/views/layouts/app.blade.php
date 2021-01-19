<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script src="/ckeditor/ckeditor.js"></script>
    <script>$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });</script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<div id="app">
    <div>
        <div class="w-100">
            <header>
                <img src="/images/header.jpg">
            </header>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light my-navbar w-100" role="group" aria-label="Basic example">
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
                @if(Auth::user() && Auth::user()->is_admin)
                @if(Request::is('admin'))
                <li class="nav-item"><a type="button" class="nav-link active" href="/admin">Администрирование</a></li>
                @else
                <li class="nav-item"><a type="button" class="nav-link" href="/admin">Администрирование</a></li>
                @endif
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Вход') }}</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false" v-pre>
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
    </div>
    <main class="py-4 container-fluid">
        @yield('content')
    </main>

</div>

<div class="col-md-12 col-lg-12 d-xs-none footer">
    <div class="col-sm-4 col-md-4 col-lg-4">
        <footer>
            <div>
                <a href="/">Главная</a><br>
                <a href="/index.php?page=searchtour">Поиск
                    туров</a><br>
                <a href="/index.php?page=searchtour&hot=1">Горящие
                    туры</a><br>
                <a href="/index.php?page=hotels">Отели</a><br>
            </div>
        </footer>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8">
        <p>Контакты:</p>
        <p>+38(093)-333-33-33</p>
        <p>+38(098)-888-88-88</p>
        <p>+38(077)-777-77-77</p>
    </div>
</div>
</body>
</html>
