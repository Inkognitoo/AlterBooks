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
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
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
        </style>

        <!--Icons-->
        <link rel="manifest" href="/manifest.json">
        <link rel="icon" type="image/png" href="/img/icon-16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/img/icon-32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/img/icon-180.png" sizes="180x180">
        <link rel="icon" type="image/png" href="/img/icon-192.png" sizes="192x192">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
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

            <div class="content">
                <div class="title m-b-md">
                    AlterBooks
                </div>

                <div>
                    <h2>Список пользователей</h2>
                    @foreach ($users as $user)
                        <a href="{{ $user['href'] }}">{{ $user['nickname'] }}</a>{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </div>

                <div>
                    <h2>Список книг</h2>
                    @foreach ($books as $book)
                        <a href="{{ $book['href'] }}">{{ $book['title'] }}</a>{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>
