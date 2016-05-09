<!DOCTYPE html>
<html>
<head>
    <title>{{trans('emails.verify_Confirm your email')}}</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            {{trans('emails.verify_Thank you for registering on the')}} <a href="http://alterbooks.ru"><strong>AlterBooks</strong></a>!<br>
            {{trans('emails.verify_To confirm your email, please visit')}}: <a href="{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}">{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}</a><br>
            <small>{{trans('emails.verify_If you have not registered on the site, then just ignore this email')}}</small>
        </p>
    </div>
</div>
</body>
</html>
