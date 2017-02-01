<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="/css/auth_a/style.css" rel="stylesheet" type="text/css">
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
        <div class="checkbox-box">
            <input id="remember_me" name="remember_me" type="checkbox" class="checkbox checkbox--red">
            <label for="remember_me">запомнить</label>
        </div>
        <button data-ripple class="btn">войти</button>
        <div class="link-box">
            <a href="#" class="forget-password-link">забыли пароль?</a>
            <a href="#" class="registration-link">регистрация</a>
        </div>
    </form>
    <span>или</span>
    <form class="auth-form-sn">
        <h3>используйте аккаунт в соц. сети:</h3>
        <div>
            <a href="#" class="vk-link"></a>
            <a href="#" class="fb-link"></a>
            <a href="#" class="tw-link"></a>
            <a href="#" class="gg-link"></a>
            <a href="#" class="ok-link"></a>
        </div>
    </form>
</div>

<script>
    //применение эффекта к кнопкам
    Array.prototype.forEach.call(document.querySelectorAll('[data-ripple]'), function(element){
        new RippleEffect(element);
    });
</script>

</body>

</html>