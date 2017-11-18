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
    <link rel="stylesheet" href="/css/style.css">

    <!--Icons-->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href="/img/icon-16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/img/icon-32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/img/icon-180.png" sizes="180x180">
    <link rel="icon" type="image/png" href="/img/icon-192.png" sizes="192x192">
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
        <div class="landing-books">
            <div class="landing-books__title">
                Новинки
            </div>
            <div class="landing-books__area">
                <div class="landing-books-elements">
                    @foreach($books as $book)
                        <div class="landing-book">
                            <div class="landing-book-cover">
                                <img src="
                                    @if (filled($book['cover']))
                                        {{ $book['cover']}}
                                    @else
                                        /img/default_book_cover.png
                                    @endif
                                " class="landing-book-cover__image">
                            </div>
                            <div class="landing-book__title">
                                {{ $book['title']}}
                            </div>
                            <div class="landing-book__read">
                                <form class="landing-book__read landing-form" action="{{ $book['href'] }}">
                                    <button class="landing-button">
                                        Профиль
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="landing-books-more">
                    <form class="landing-form landing-books-more" action="{{ route('book.books-list') }}">
                        <button class="landing-button landing-books-more__button">
                            Больше книг
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>