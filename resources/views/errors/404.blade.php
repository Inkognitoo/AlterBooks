<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>Page Not Found</title>
    <link href="{{ mix('/css/404.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,900&amp;subset=cyrillic" rel="stylesheet">
    <meta name="viewport" content="width=device-width">

    <!--Icons-->
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" href={{url("/img/icon-16.png")}} sizes="16x16">
    <link rel="icon" type="image/png" href={{url("/img/icon-32.png")}} sizes="32x32">
    <link rel="icon" type="image/png" href={{url("/img/icon-180.png")}} sizes="180x180">
    <link rel="icon" type="image/png" href={{url("/img/icon-192.png")}} sizes="192x192">
</head>
<body>
<main class="container">
    <div class="container__circle"></div>
    <div class="container__sign">
        <span class="container__sign-big">404</span>
        <span class="container__sign-small">page not found</span>
    </div>
    <div class="container__fox"></div>
</main>
</body>
</html>