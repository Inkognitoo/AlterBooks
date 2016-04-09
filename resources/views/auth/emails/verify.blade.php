{{-- TODO: нормальный шаблон --}}
Для подтверждения своего мыла, перейдите по адресу: <a href="{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}">{{ URL::to('/user/email/verify?code='.$email_verify_code.'&email='.$email) }}</a>
