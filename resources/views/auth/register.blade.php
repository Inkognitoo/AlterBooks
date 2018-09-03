@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <form class="registration col-8 col-clear col-md-10 col-md-clear"
          method="POST"
          action="{{ route('register') }}"
          autocomplete="off">
        {{ csrf_field() }}

        <div class="row row-center">
            <div class="registration__title col-12 col-clear col-center">
                регистрация
            </div>

            <div class="registration-element col-12 col-clear"
                 data-status="{{ $errors->has('email') ? 'error' : 'correct' }}">
                <div class="registration-element__logo"
                     style="background-image: url('/img/icons/user_grey.svg')"></div>
                <input class="registration-element__field"
                       type="text"
                       id="registration-email"
                       name="email"
                       placeholder="электронная почта / логин"
                       required autofocus
                       value="{{ old('email') }}">
                <div class="registration-element__flag"></div>
                <div class="registration-element__error">
                    пользователь с таким логином существует
                </div>
                <div class="registration-element__star">*</div>
            </div>

            <div class="registration-element col-12 col-clear"
                 data-status="">
                <div class="registration-element__logo"
                     style="background-image: url('/img/icons/lock-closed_grey.svg')"></div>
                <input class="registration-element__field registration-element__field_logo"
                       type="password"
                       id="registration-password"
                       name="password"
                       placeholder="пароль"
                       required
                       value="">
                <div class="registration-element__star">*</div>
            </div>

            <div class="registration-element col-12 col-clear"
                 data-status="">
                <div class="registration-element__logo"
                     style="background-image: url('/img/icons/lock-closed_grey.svg')"></div>
                <input class="registration-element__field registration-element__field_logo"
                       type="password"
                       id="registration-password_confirmation"
                       name="password_confirmation"
                       placeholder="повторите пароль"
                       required
                       value="">
                <div class="registration-element__flag"></div>
                <div class="registration-element__error">
                    пароли не совпадают
                </div>
                <div class="registration-element__star">*</div>
            </div>

            <div class="registration-element col-12 col-clear">
                <input class="registration-element__field"
                       type="text"
                       id="registration-surname"
                       name="surname"
                       placeholder="фамилия">
            </div>

            <div class="registration-element col-12 col-clear">
                <input class="registration-element__field"
                       type="text"
                       id="registration-name"
                       name="name"
                       placeholder="имя">
            </div>
            <div class="registration-element col-12 col-clear"
                 data-status="{{ $errors->has('nickname') ? 'error' : '' }}">
                <input class="registration-element__field"
                       type="text"
                       id="registration-nickname"
                       name="nickname"
                       placeholder="псевдоним"
                       value="{{ old('nickname') }}">
            </div>

            <div class="registration-element registration-element_agreement col-12 col-clear">
                <div class="authentication__checkbox">
                    <label for="registration-checkbox"
                           class="col-xs-12 checkbox">
                        <input type="checkbox"
                               class="checkbox__field"
                               id="registration-checkbox"
                               name="checkbox">
                        <span class="checkbox-animation">
                                    <span class="checkbox-animation__button"></span>
                                    <span class="checkbox-animation__icon"></span>
                                    <span class="checkbox-animation__ripple"></span>
                        </span>
                        <span class="checkbox__content">
                            я согласен с
                            <a class="registration-agreement__href">
                                пользовательским соглашением
                            </a>
                        </span>
                    </label>
                </div>
            </div>

            <div class="col-12 col-clear col-center">
                <button class="registration__button button button_green"
                        type="submit"
                        id="registration-button"
                        disabled>
                    регистрация
                </button>
            </div>

            <div class="block-content col-12 col-clear">
                <div class="registration__hr block-content-header block-content-header_center">
                    <hr class="block-content-header__hr">
                    <div class="registration__separator block-content-header__title">
                        или
                    </div>
                    <hr class="block-content-header__hr">
                </div>
                <div class="registration-more block-content-main">
                    <div class="registration-more__element"
                         style="background-image: url('/img/social/vk.svg')"></div>
                    <div class="registration-more__element"
                         style="background-image: url('/img/social/facebook.svg')"></div>
                    <div class="registration-more__element"
                         style="background-image: url('/img/social/twitter.svg')"></div>
                    <div class="registration-more__element"
                         style="background-image: url('/img/social/google.svg')"></div>
                </div>
            </div>
        </div>
    </form>
@endsection
