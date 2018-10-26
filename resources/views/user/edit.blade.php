@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp

@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
    <div class="edit col-12">
        <div class="edit-block">
            <div class="edit-block-header">
                <hr class="edit-block-header__hr">
                <div class="edit-block-header__title">
                    информация&nbsp;о&nbsp;пользователе
                </div>
                <hr class="edit-block-header__hr">
            </div>
            <form class="edit-block__main"
                  method="POST"
                  action="{{ route('user.edit.info', ['id' => Auth::user()->nickname]) }}"
                  enctype="multipart/form-data"
                  id="user-edit-info">
                {{ csrf_field() }}

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_nickname">
                        Псевдоним
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_input"
                               type="text"
                               id="change_nickname"
                               name="nickname"
                               value="{{ old('nickname', Auth::user()->nickname) }}"
                               maxlength="30"
                               autofocus
                               required>
                    </div>
                    <div class="edit-block-element__size">
                        {{ mb_strlen(Auth::user()->nickname) }} / 30
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_surname">
                        Фамилия
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_input"
                               type="text"
                               id="change_surname"
                               name="surname"
                               value="{{ old('surname', Auth::user()->surname) }}"
                               maxlength="30">
                    </div>
                    <div class="edit-block-element__size">
                        {{ mb_strlen(Auth::user()->surname) }} / 30
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_name">
                        Имя
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_input"
                               type="text"
                               id="change_name"
                               name="name"
                               value="{{ old('name', Auth::user()->name) }}"
                               maxlength="30">
                    </div>
                    <div class="edit-block-element__size">
                        {{ mb_strlen(Auth::user()->name) }} / 30
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_patronymic">
                        Отчество
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_input"
                               type="text"
                               id="change_patronymic"
                               name="patronymic"
                               value="{{ old('name', Auth::user()->patronymic) }}"
                               maxlength="30">
                    </div>
                    <div class="edit-block-element__size">
                        {{ mb_strlen(Auth::user()->patronymic) }} / 30
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_date">
                        Дата рождения
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_date"
                               type="date"
                               id="change_date"
                               name="birthday_date"
                               value="{{ old('birthday_date', optional(Auth::user()->birthday_date)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="edit-block-element">
                    <div class="edit-block-element__title">
                        Пол
                    </div>
                    <div class="edit-block-element__content">
                        <div class="edit-block-element__content_radio">
                            <div class="radio-group__element">
                                <label class="radio"
                                       for="change_sex_none">
                                    <input type="radio" class="radio__field"
                                           id="change_sex_none"
                                           name="gender"
                                           value="{{ \App\Models\User::GENDER_NOT_INDICATED }}"
                                           {{ old('gender', Auth::user()->gender) == \App\Models\User::GENDER_NOT_INDICATED ? 'checked' : ''}}>
                                    <span class="radio-animation">
                                            <span class="radio-animation__button"></span>
                                        </span>
                                    <span class="radio__content">
                                            не&nbsp;указан
                                        </span>
                                </label>
                            </div>
                            <div class="radio-group__element">
                                <label class="radio"
                                       for="change_sex_male">
                                    <input type="radio" class="radio__field"
                                           id="change_sex_male"
                                           name="gender"
                                           value="{{ \App\Models\User::GENDER_MALE }}"
                                            {{ old('gender', Auth::user()->gender) == \App\Models\User::GENDER_MALE ? 'checked' : ''}}>
                                    <span class="radio-animation">
                                            <span class="radio-animation__button"></span>
                                        </span>
                                    <span class="radio__content">
                                            мужской
                                        </span>
                                </label>
                            </div>
                            <div class="radio-group__element">
                                <label class="radio"
                                       for="change_sex_female">
                                    <input type="radio" class="radio__field"
                                           id="change_sex_female"
                                           name="gender"
                                           value="{{ \App\Models\User::GENDER_FEMALE }}"
                                            {{ old('gender', Auth::user()->gender) == \App\Models\User::GENDER_FEMALE ? 'checked' : ''}}>
                                    <span class="radio-animation">
                                            <span class="radio-animation__button"></span>
                                        </span>
                                    <span class="radio__content">
                                            женский
                                        </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="edit-block-element edit-block-element_wide">
                    <div class="edit-block-header">
                        <hr class="edit-block-header__hr">
                        <label class="edit-block-header__title"
                               for="change_about">
                            о&nbsp;себе
                        </label>
                        <hr class="edit-block-header__hr">
                    </div>
                    <div class="edit-block-element__content edit-block-element__content_wide">
                            <textarea class="edit-block-element__content_date"
                                      type="date"
                                      id="change_about"
                                      name="about"
                                      placeholder="Введите описание">{{ old('about_plain', Auth::user()->about_plain) }}</textarea>
                    </div>
                </div>

                <input class="edit-block-element__button button button_green"
                       type="submit"
                       value="сохранить">
            </form>
        </div>



        <div class="edit-block">
            <div class="edit-block-header">
                <hr class="edit-block-header__hr">
                <div class="edit-block-header__title">
                    данные&nbsp;профиля
                </div>
                <hr class="edit-block-header__hr">
            </div>
            <form class="edit-block__main"
                  method="POST"
                  action="{{ route('user.edit.email', ['id' => Auth::user()->nickname]) }}"
                  id="user-edit-email">
                {{ csrf_field() }}

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="old_password">
                        Пароль
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_old-password"
                               type="password"
                               id="old_password"
                               name="old_password"
                               required>
                    </div>
                </div>

                <div class="edit-block-element">
                    <div class="edit-block-element__title"></div>
                    <div class="edit-block-element__content edit-block-element__content_error">
                        Внимание! Изменение данных профиля приведет к&nbsp;изменению данных для&nbsp;входа на&nbsp;сайт.
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_e-mail">
                        E-mail
                    </label>
                    <div class="edit-block-element__content">
                        <input type="text"
                               id="change_e-mail"
                               name="email"
                               value="{{ old('email', Auth::user()->email) }}"
                               required>
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_password">
                        Новый пароль
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_password"
                               type="password"
                               id="change_password"
                               name="password">
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_re-password">
                        Повтор пароля
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_re-password"
                               type="password"
                               id="change_re-password"
                               name="password_confirmation"
                               data-status="">
                        <div class="edit-block-element__flag"></div>
                    </div>
                </div>

                <input  class="edit-block-element__button button button_green"
                        type="submit"
                        value="изменить">
            </form>
        </div>
        <a class="edit-block__button button"
           href="{{ Auth::user()->url }}">
            вернуться к профилю
        </a>
    </div>
@endsection
