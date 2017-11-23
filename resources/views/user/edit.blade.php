@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp

@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
<div class="user-edit-content container">
    <div class="user-edit-content__area row">
        <div class="user-edit-block__area col-md-8 col-md-offset-2">
            <div class="user-edit-block panel panel-default">
                <div class="user-edit-block_title panel-heading">
                    Редактирование профиля
                </div>

                <div class="user-edit-block-content panel-body">

                    @if (!empty($status))
                        <div class="alert alert-success">
                            {{ $status }}
                        </div>
                    @endif

                    <form class="user-edit-block__form form-horizontal" method="POST"
                          action="{{ route('user.edit', ['id' => Auth::user()->id]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Ник</label>

                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control" name="nickname"
                                       value="{{ old('nickname', Auth::user()->nickname) }}" autofocus>

                                @if ($errors->has('nickname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <label for="avatar" class="col-md-4 control-label">Аватар</label>

                            <div class="col-md-6">
                                <input id="avatar" type="file" class="form-control" name="avatar">

                                @if ($errors->has('avatar'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('avatar') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Фамилия</label>

                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control" name="surname"
                                       value="{{ old('surname', Auth::user()->surname) }}">

                                @if ($errors->has('surname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Имя</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"
                                       value="{{ old('name', Auth::user()->name) }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('patronymic') ? ' has-error' : '' }}">
                            <label for="patronymic" class="col-md-4 control-label">Отчество</label>

                            <div class="col-md-6">
                                <input id="patronymic" type="text" class="form-control" name="patronymic"
                                       value="{{ old('patronymic', Auth::user()->patronymic) }}">

                                @if ($errors->has('patronymic'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('patronymic') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label for="gender" class="col-md-4 control-label">Пол</label>

                            <div class="col-md-6">
                                <select id="gender" class="form-control" name="gender">
                                    <option value="{{ \App\User::GENDER_NOT_INDICATED }}"
                                            {{ old('gender', Auth::user()->gender) == \App\User::GENDER_NOT_INDICATED ? 'selected' : ''}} >
                                        Не указан
                                    </option>
                                    <option value="{{ \App\User::GENDER_MALE }}"
                                            {{ old('gender', Auth::user()->gender) == \App\User::GENDER_MALE ? 'selected' : '' }} >
                                        Мужской
                                    </option>
                                    <option value="{{ \App\User::GENDER_FEMALE }}"
                                            {{ old('gender', Auth::user()->gender) == \App\User::GENDER_FEMALE ? 'selected' : '' }} >
                                        Женский
                                    </option>
                                </select>

                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email', Auth::user()->email) }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birthday_date') ? ' has-error' : '' }}">
                            <label for="birthday_date" class="col-md-4 control-label">Дата рождения</label>
                            <div class="col-md-6">
                                <input id="birthday_date" type="date" class="form-control" name="birthday_date"
                                       value="{{ old('birthday_date') ??
                                        filled(Auth::user()->birthday_date)
                                            ? date('Y-m-d', strtotime(Auth::user()->birthday_date))
                                            : '' }}">

                                @if ($errors->has('birthday_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birthday_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Новый пароль</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Повтор пароля</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                            <label for="about" class="col-md-4 control-label">О себе</label>

                            <div class="col-md-6">
                                <textarea id="about" class="form-control user-edit-about" name="about">{{ old('about_plain', Auth::user()->about_plain) }}</textarea>

                                @if ($errors->has('about'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('about') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Сохранить
                                </button>
                                <a type="button" href="{{ route('user.show', ['id' => Auth::user()->id]) }}" class="btn btn-primary">
                                    К профилю
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
