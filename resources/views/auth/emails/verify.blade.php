<!DOCTYPE html>
<html>
<head>
    <title>Подтвердите Ваш email</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            Спасибо за регистрацию на <a href="http://alterbooks.ru"><strong>AlterBooks</strong></a>!<br>
            Для подтверждения своего email, перейдите по адресу: <a href="{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}">{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}</a><br>
            <small>Если Вы не регистрировались на сайте, то просто проигнорируйте это письмо.</small>
        </p>
    </div>
</div>
</body>
</html>
