<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Kurale&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <title>@yield('title')</title>
</head>
<body>
<div class="container">
    @yield('content')
</div>
<div class="row footer">
    <p class="footer-copyright">
        AlterBooks <span class="footer-copyright-sign">©</span> 2015<br>Inkognitoo
    </p>
</div>
</body>
<script src="/js/reqwest.min.js"></script>
<script src="/js/main.js"></script>
</html>