<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Kurale&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <title>AlterBooks</title>
</head>
<body>
    <div class="container">
        <div class="row header">
            <h1><span class="red">A</span>lter<span class="red">B</span>ooks</h1>
            <p class="moto">книги для людей, а не издательств</p>
        </div>
        <div id="dynamic-wrapper">
            <div class="row mainform">
                <form name="subscribeform" id="subscribe_form">
                    <input type="text" class="textfield" placeholder="Введите ваш e-mail...">
                    <button class="button" id="send_button"><span class="button-sign">ПОДПИСАТЬСЯ</span></button>
                </form>
            </div>
        </div>
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