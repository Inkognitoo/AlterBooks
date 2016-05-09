<!DOCTYPE html>
<html>
<head>
    <title>{{trans('emails.change_email_Request for change of email')}}</title>
</head>
<body>
<div class="container">
    <div class="content">
        <p>
            {{trans('emails.change_email_To change your email, please visit')}}: <a href="{{URL::to('/user/email/change/?code='.$email_change_code.'&email='.$email)}}">{{URL::to('/user/email/change/?code='.$email_change_code.'&email='.$email)}}</a>
        </p>
    </div>
</div>
</body>
</html>
