@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="register-content container">
    <div class="register-content__area row">
        <div class="register-block col-md-8 col-md-offset-2">
            <div class="register-block-header-panel panel panel-default">
                <div class="register-block-header-panel__title panel-heading">Регистрация</div>

                <div class="register-block-content panel-body">
                    <form class="register-block-content__form form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="register-block-content__element form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                            <label for="name" class="register-block-content__input-label col-md-4 control-label">
                                Ник
                            </label>

                            <div class="register-block-content__input-field col-md-6">
                                <input id="nickname" type="text" class="register-block-content__input form-control" 
                                       name="nickname" value="{{ old('nickname') }}" required autofocus>

                                @if ($errors->has('nickname'))
                                    <span class="register-block-content__input-help help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="register-block-content__element form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="register-block-content__input-label col-md-4 control-label">
                                E-Mail
                            </label>

                            <div class="register-block-content__input-field col-md-6">
                                <input id="email" type="email" class="register-block-content__input form-control" 
                                       name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="register-block-content__input-help help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="register-block-content__element form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="register-block-content__input col-md-4 control-label">
                                Пароль
                            </label>

                            <div class="register-block-content__input-field col-md-6">
                                <input id="password" type="password" class="register-block-content__input form-control"
                                       name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="register-block-content__element register-block-content__element form-group">
                            <label for="password-confirm" class="register-block-content__input col-md-4 control-label">
                                Повтор пароля
                            </label>

                            <div class="register-block-content__input-field col-md-6">
                                <input id="password-confirm" type="password"
                                       class="register-block-content__input form-control"
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="register-block-content__element form-group">
                            <div class="register-block-content-submit col-md-6 col-md-offset-4">
                                <button type="submit" class="register-block-content-submit_button btn btn-primary">
                                    Регистрация
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
