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
            <a href="#" class="vk-link"></a>
            <a href="#" class="fb-link"></a>
            <a href="#" class="tw-link"></a>
            <a href="#" class="gg-link"></a>
            <a href="#" class="ok-link"></a>
        </div>
    </div>
    <span class="text-or">или</span>
    <form class="auth-email">
        <h3>введите логин / электронную почту от существующего аккаунта:</h3>
        <div>
            <input name="name" type="text" placeholder="логин / e-mail">
            <button data-ripple class="btn">далее</button>
        </div>
    </form>
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