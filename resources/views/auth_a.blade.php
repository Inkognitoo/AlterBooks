<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link href="/css/auth_a/style.css?v=1.0.2" rel="stylesheet" type="text/css">
    <link href="/css/lib/ripple.css" rel="stylesheet" type="text/css">
    <script src="/js/lib/ripple.js"></script>
    <title>
        AlterBooks
    </title>
</head>
<body>
<div class="auth-box">
    <h2>Авторизация</h2>
    <hr />
    <form class="auth-form">
        <h3>введите логин и пароль</h3>
        <input name="name" type="text" placeholder="логин / e-mail">
        <input name="password" type="password" placeholder="пароль">
        <div class="checkbox checkbox-box">
        <label>
            <input type="checkbox"><span class="checkbox-material"><span class="check"></span></span> запомнить
        </label>
        </div>
        <button data-ripple class="btn">войти</button>
        <div class="link-box">
            <a href="#" class="forget-password-link">забыли пароль?</a>
            <a href="#" class="registration-link">регистрация</a>
        </div>
    </form>
    <span>или</span>
    <div class="auth-form-sn">
        <h3>используйте аккаунт в соц. сети:</h3>
        <div>
            <a href="#" class="vk-link"><img src="img/vk.svg"></a>
            <a href="#" class="fb-link"><img src="img/facebook.svg"></a>
            <a href="#" class="tw-link"><img src="img/twitter.svg"></a>
            <a href="#" class="gg-link"><img src="img/google.svg"></a>
            <a href="#" class="ok-link"><img src="img/ok.svg"></a>
        </div>
    </div>
</div>

<script>
    //применение эффекта к кнопкам
    Array.prototype.forEach.call(document.querySelectorAll('[data-ripple]'), function(element){
        new RippleEffect(element);
    });
</script>

</body>

</html>