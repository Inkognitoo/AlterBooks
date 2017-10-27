<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fix.css') }}" rel="stylesheet">

    <!--Icons-->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/img/icon-16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/img/icon-32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/img/icon-180.png" sizes="180x180">
    <link rel="icon" type="image/png" href="/img/icon-192.png" sizes="192x192">
</head>
<body class="temp-body">
    <div class="temp-content" id="app">
        <nav class="temp-header navbar navbar-default navbar-static-top">
            <div class="temp-header__container container">
                <div class="temp-header-navigation navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="temp-header-navigation-button navbar-toggle collapsed"
                            data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="temp-header-navigation-button__element-main sr-only">Навигация</span>
                        <span class="temp-header-navigation-button__element icon-bar"></span>
                        <span class="temp-header-navigation-button__element icon-bar"></span>
                        <span class="temp-header-navigation-button__element icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="temp-header-navigation__logo navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="temp-header-users collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="temp-header-users-element nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="temp-header-users-element temp-header-users-element_right nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li>
                                <a class="temp-header-users-menu__element" href="{{ route('login') }}">
                                Вход
                                </a>
                            </li>
                            <li>
                                <a class="temp-header-users-menu__element" href="{{ route('register') }}">
                                Регистрация
                                </a>
                            </li>
                        @else
                            <li class="temp-header-users-menu dropdown">
                                <a href="#" class="temp-header-users-menu__name-placeholder dropdown-toggle"
                                   data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->nickname }} <span class="caret"></span>
                                </a>

                                <ul class="temp-header-users-menu-content dropdown-menu" role="menu">
                                    @if (!Request::route()->named('user.show') || (Request::route()->named('user.show') && Auth::user()->id !== $user->id))
                                        <li>
                                            <a class="temp-header-users-menu-content__element"
                                               href="{{ route('user.show', ['id' => Auth::user()->id]) }}">
                                                Профиль
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="temp-header-users-menu-content__element" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выход
                                        </a>

                                        <form class="temp-header-users-menu-content__logout-form" id="logout-form"
                                              action="{{ route('logout') }}" method="POST">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
