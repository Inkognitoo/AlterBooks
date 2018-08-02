<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api_token" content="{{ optional(Auth::user())->api_token }}">

    <title>@yield('title')</title>

    <link rel="canonical" href="@yield('canonical', url()->current())"/>

    <!-- Styles -->
    <link href="{{ mix('/css/normalize.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/style-1.0.css') }}" rel="stylesheet">

    <!--Icons-->
    <link rel="manifest" href={{url('/manifest.json')}}>
    <link rel="icon" type="image/png" href={{url('/img/icon-16.png')}} sizes="16x16">
    <link rel="icon" type="image/png" href={{url('/img/icon-32.png')}} sizes="32x32">
    <link rel="icon" type="image/png" href={{url('/img/icon-180.png')}} sizes="180x180">
    <link rel="icon" type="image/png" href={{url('/img/icon-192.png')}} sizes="192x192">
</head>
<body class="body" data-status="modal-close">

<!-- HEADER -->
<header class="header">
    <div class="header-main">
        <div class="header-main__area">
            <a class="header-project-name"
               href="{{ url('/') }}"></a>
            <div class="header-main-buttons" data-auth="{{ Auth::check() ? 'true' : 'false' }}">

                <!-- Authentication Links -->
                @guest
                    <a class="header-main-buttons__element button-registration"
                       href="{{ route('register') }}">
                        {{ t('app.button', 'Регистрация') }}
                    </a>

                    <div class="header-main-buttons__element button-authentication modal-button"
                         id="button-authentication"
                         data-modal-number="0">
                        {{ t('app.button', 'Вход') }}
                    </div>
                @else
                    <input class="header-main-buttons__element_user-active" type="checkbox" id="user">
                    <label class="header-main-buttons__element header-main-buttons__element_user button-user"
                           for="user"
                           title="{{ Auth::user()->nickname }}">
                        {{ Auth::user()->email }}
                    </label>

                    <div class="header-user"
                         data-status="modal-close">
                        <div class="header-user__title">
                            <div class="header-user__avatar"
                                 style="background-image: url('{{ Auth::user()->avatar_url }}')"></div>
                            <div class="header-user-namespace">
                                <div class="header-user-namespace__name">
                                    {{ Auth::user()->surname }} {{ Auth::user()->name }}
                                </div>
                                <div class="header-user-namespace__nickname">
                                    {{ Auth::user()->nickname }}
                                </div>
                            </div>
                        </div>
                        <div class="header-user-main">
                            <a class="header-user-main-element"
                               href="{{ Auth::user()->url }}">
                                <div class="header-user-main-element__logo header-user-main-element__logo_home"></div>
                                <div class="header-user-main-element__text">
                                    {{ t('app.button', 'мой профиль') }}
                                </div>
                            </a>
                            <hr class="header-user-main__hr">
                            <a class="header-user-main-element header-user-main-element_disabled"
                               title="находится в разработке">
                                <div class="header-user-main-element__logo header-user-main-element__logo_library"></div>
                                <div class="header-user-main-element__text header-user-main-element__text">
                                    {{ t('app.button', 'моя библиотека') }}
                                </div>
                            </a>
                            <a class="header-user-main__button button"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ t('app.button', 'выход') }}
                            </a>

                            <form class="temp-header-users-menu-content__logout-form" id="logout-form"
                                  action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                @endguest

            </div>
        </div>
    </div>
    <nav class="header-navigation">
        <a class="header-navigation__element header-navigation__element_disabled">
            {{ t('app.button', 'ТОПы') }}
        </a>
        <div class="header-navigation__hr"></div>
        <a class="header-navigation__element"
           href="{{ route('book.books-list') }}">
            {{ t('app.button', 'Список&nbsp;книг') }}
        </a>
        <div class="header-navigation__hr"></div>
        <a class="header-navigation__element header-navigation__element_disabled">
            {{ t('app.button', 'Блог') }}
        </a>
    </nav>
</header>

<main class="container body__main">
    <div class="row row-center">
        @yield('content')
    </div>
</main>

@include("auth.login")

<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>