<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AlterBooks</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
            background-color: #f5f8fa;
        }
        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }
        .title {
            font-size: 84px;
        }
        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .m-b-md {
            margin-bottom: 30px;
        }
        .img-cover {
            width: 100px;
        }
    </style>
    <link rel="stylesheet" href=" {{ mix('/css/style.css') }}">

    <!--Icons-->
    <link rel="manifest" href={{url('/manifest.json')}}>
    <link rel="icon" type="image/png" href={{url('/img/icon-16.png')}} sizes="16x16">
    <link rel="icon" type="image/png" href={{url('/img/icon-32.png')}} sizes="32x32">
    <link rel="icon" type="image/png" href={{url('/img/icon-180.png')}} sizes="180x180">
    <link rel="icon" type="image/png" href={{url('/img/icon-192.png')}} sizes="192x192">
</head>
<body>
<div class="landing">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ route('user.show', ['id' => Auth::user()->id]) }}">Профиль</a>
            @else
                <a href="{{ route('login') }}">Вход</a>
                <a href="{{ route('register') }}">Регистрация</a>
            @endauth
        </div>
    @endif
    <main class="landing-content">
        <div class="landing-content__title title m-b-md">AlterBooks</div>
        <div class="landing-element landing-books">
            <div class="landing-element__title landing-books__title">
                Новинки
            </div>
            <div class="landing-element__area landing-books__area">
                <div class="landing-element__part">
                    @foreach($books as $book)
                        @include('landing.book', ['book' => $book])
                    @endforeach
                </div>
                <div class="landing-more">
                    <div class="landing-form landing-more">
                        <a type="button" class="landing-button landing-more__button"
                           href="{{ route('book.books-list') }}">
                            Больше книг
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="landing-element landing-users">
            <div class="landing-element__title landing-users__title">
                Пользователи
            </div>
            <div class="landing-element__area landing-users__area">
                <div class="landing-element__part">
                    @foreach($users as $user)
                        @include('landing.user', ['user' => $user])
                    @endforeach
                </div>
                <div class="landing-more">
                    <div class="landing-form landing-more">
                        <a type="button" class="landing-button landing-more__button"
                           href="{{ route('user.users-list') }}">
                            Все пользователи
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>