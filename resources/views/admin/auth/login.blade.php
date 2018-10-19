@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        AlterBooks Admin | Login
    </title>
    <meta name="description" content="Gate to the admin's palace">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="{{ url('/metronic/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/metronic/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->

    <!--Icons-->
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-180.png') }}" sizes="180x180">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-192.png') }}" sizes="192x192">
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3" id="m_login" style="background-image: url({{ url('/metronic/img/background.jpg') }});">
        <div class="m-grid__item m-grid__item--fluid m-login__wrapper">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <img src="/metronic/img/logo-180.png">
                    </a>
                </div>
                <div class="m-login__signin">
                    <div class="m-login__head">
                        <h3 class="m-login__title">
                            Войти в кабинет администратора
                        </h3>
                    </div>
                    <form class="m-login__form m-form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group m-form__group {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <input class="form-control m-input" type="text" placeholder="Email"
                                   name="email" autocomplete="on" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <div class="form-control-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group m-form__group {{ $errors->has('password') ? 'has-danger' : '' }}">
                            <input class="form-control m-input m-login__form-input--last" type="password"
                                   placeholder="Пароль" name="password" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <div class="form-control-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        <div class="row m-login__form-sub">
                            <div class="col m--align-left m-login__form-left">
                                <label class="m-checkbox  m-checkbox--light">
                                    <input type="checkbox" name="remember">
                                    Запомнить меня
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="m-login__form-action">
                            <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn" type="submit">
                                Войти
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="{{ url('/metronic/js/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('/metronic/js/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Snippets -->
{{--<script src="/metronic/js/login.js" type="text/javascript"></script>--}}
<!--end::Page Snippets -->
</body>
<!-- end::Body -->
</html>