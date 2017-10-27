@extends('layouts.app')

@section('title', 'Авторизация')

@section('content')
<div class="login-content container">
    <div class="login-content__area row">
        <div class="login-block__area col-md-8 col-md-offset-2">
            <div class="login-block panel panel-default">
                <div class="login-block__title panel-heading">Вход</div>

                <div class="login-block-content panel-body">
                    <form class="login-block__form form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="login-block-element form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="login-block-element__input-label col-md-4 control-label">
                                E-Mail
                            </label>

                            <div class="login-block-element__input-field col-md-6">
                                <input id="email" type="email" class="login-block-element__input form-control"
                                       name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="login-block-element__input-help help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="login-block-element form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="login-block-element__input-label col-md-4 control-label">
                                Пароль
                            </label>

                            <div class="login-block-element__input-field col-md-6">
                                <input id="password" type="password" class="login-block-element__input form-control"
                                       name="password" required>

                                @if ($errors->has('password'))
                                    <span class="login-block-element__input-help help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="login-block-element form-group">
                            <div class="login-block-element__checkbox-field col-md-6 col-md-offset-4">
                                <div class="login-block-element__checkbox checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        Запомнить меня
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="login-block-element form-group">
                            <div class="login-block-submit col-md-8 col-md-offset-4">
                                <button type="submit" class="login-block-submit__button btn btn-primary">
                                    Вход
                                </button>

                                <a class="login-block-submit__help btn btn-link"
                                   href="{{ route('password.request') }}">
                                    Забыли пароль?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
