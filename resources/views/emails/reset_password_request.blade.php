<!DOCTYPE html>
<html>
<head>
    <title>Запрос на сброс пароля</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            Для сброса пароля перейдите по адресу: <a href="{{URL::to('/user/password/reset/?code='.$reset_code.'&email='.$email)}}">{{URL::to('/user/password/reset/?code='.$reset_code.'&email='.$email)}}</a>
        </p>
    </div>
</div>
</body>
</html>
