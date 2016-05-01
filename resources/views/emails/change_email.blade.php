<!DOCTYPE html>
<html>
<head>
    <title>Запрос смену email</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            Для смены email перейдите по адресу: <a href="{{URL::to('/user/email/change/?code='.$email_change_code.'&email='.$email)}}">{{URL::to('/user/email/change/?code='.$email_change_code.'&email='.$email)}}</a>
        </p>
    </div>
</div>
</body>
</html>
