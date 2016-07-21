<!Doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"content="width=device-width">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-social.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Kurale&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <title>
        Login-Form
    </title>
</head>
<body>
<div class="logotype"></div>
<div class="header">
			<span class="header-title">
				<span class="redletter">A</span>lter<span class="redletter">B</span>ooks
			</span>
</div>
<div class="loginform">
    <form action="/echo" method="post">
        <span class="loginform-title">Авторизация</span>
        <hr class="loginform-title-separator">
        <input class="loginform-loginfield" type="text" placeholder="Ваш логин" required> <br>
        <input class="loginform-passwordfield" type="text" placeholder="Ваш пароль" required> <br>
        <input class="loginform-checkremember" type="checkbox" id="remember-me">
        <label class="loginform-labelremember" for="remember-me">Запомнить меня</label> <br>
        <button class="loginform-button">Войти</button><br>
        <a class="loginform-reglink" href="#">Регистрация</a><a class="loginform-reslink" href="#">Не можете войти?</a><br>

        <div class="social">
            <a href="#" class="btn btn-social-icon btn-vk"><i class="fa fa-vk" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
            <a href="#" class="btn btn-social-icon btn-google"><i class="fa fa-google" aria-hidden="true"></i></a>
        </div>

    </form>
</div>
<div class="footer">
    <span class="footer-copy">(c) AlterBooks, 2016</span>
</div>
</body>
</html>