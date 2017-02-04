<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link href="/css/auth_b/style.css" rel="stylesheet" type="text/css">
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
    <div class="auth-social">
        <h3>войдите, используя свой аккаунт в соц. сети:</h3>
        <div>
            <a href="#" class="vk-link"><img src="img/vk.svg"></a>
            <a href="#" class="fb-link"><img src="img/facebook.svg"></a>
            <a href="#" class="tw-link"><img src="img/twitter.svg"></a>
            <a href="#" class="gg-link"><img src="img/google.svg"></a>
            <a href="#" class="ok-link"><img src="img/ok.svg"></a>
        </div>
    </div>
    <span class="text-or">или</span>
    <div class="auth-email">
        <h3>введите логин / электронную почту от существующего аккаунта:</h3>
        <form>
            <input name="name" type="text" placeholder="логин / e-mail">
            <button data-ripple class="btn">далее</button>
        </form>
    </div>
    <span class="text-or">или</span>
    <div class="auth-registration">
        <h3>пройдите регистрацию</h3>
        <button data-ripple class="btn">регистрация</button>
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