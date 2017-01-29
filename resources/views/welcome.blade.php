<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
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
        <button class="btn">войти</button>
        <div class="link-box">
            <a href="#" class="forget-password-link">забыли пароль?</a>
            <a href="#" class="registration-link">регистрация</a>
        </div>
    </form>
    <span>или</span>
    <form class="auth-form-sn">
        <h3>используйте аккаунт в соц. сети:</h3>
        <div>
            <div class="vk-link"></div>
            <div class="fb-link"></div>
            <div class="tw-link"></div>
            <div class="gg-link"></div>
            <div class="ok-link"></div>
        </div>
    </form>
</div>
</body>
</html>