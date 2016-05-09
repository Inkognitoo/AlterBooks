<!DOCTYPE html>
<html>
<head>
    <title>{{trans('emails.reset_password_request_Request for change of password')}}</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            {{trans('emails.reset_password_request_To change your password, please visit')}}: <a href="{{URL::to('/user/password/reset/?code='.$reset_code.'&email='.$email)}}">{{URL::to('/user/password/reset/?code='.$reset_code.'&email='.$email)}}</a>
        </p>
    </div>
</div>
</body>
</html>
