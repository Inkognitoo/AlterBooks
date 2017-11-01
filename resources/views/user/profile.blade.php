@extends('layouts.app')

@section('title', $user->full_name)

@section('content')
<div class="user-content container">
    <div class="user-content__area row">
        <div class="user-block__area col-md-8 col-md-offset-2">
            <div class="user-block panel panel-default">
                <div class="user-block__title panel-heading">Профиль пользователя</div>

                <div class="user-block-content panel-body">
                    <div class="user-info row">
                        <div class="user-info-avatar col-md-4">
                            <img src="{{ $user->avatar_url }}" class="user-info-avatar__image img-rounded"
                                 alt="avatar">
                        </div>
                        <div class="user-info-area col-md-8">
                            <div class="user-info-content panel panel-default">
                                <div class="user-info-content__name-date panel-body">
                                    {{ $user->full_name }}
                                    <br>
                                    @if(filled($user->birthday_date))
                                        {{ date('d.m.Y', strtotime($user->birthday_date)) }}
                                        ({{date('Y', time()) - date('Y', strtotime($user->birthday_date))}})
                                    @endif
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->id == $user->id)
                                    <a type="button" class="user-info-edit__button btn btn-default"
                                       href="{{ route('user.edit.show', ['id' => $user->id]) }}">
                                        Редактировать
                                    </a>
                                @endif
                            @endauth

                        </div>
                    </div>

                    <div class="user-block-books__area row">
                        <div class="user-block-books col-md-12">
                            <div class="user-block-books-content panel panel-default">
                                <div class="user-block-books__title panel-heading">
                                    {{ optional(Auth::user())->id == $user->id ? 'Мои книги' : 'Книги автора' }}
                                </div>
                                <div class="user-block-books-elements panel-body">
                                    @foreach ($user->books as $book)
                                        <a href="{{ $book->url }}" class="user-block-books__element">
                                            {{ $book->title }}
                                        </a>
                                        {{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->id == $user->id)
                                    <a type="button" class="user-block-books-load__button btn btn-default"
                                       href="{{ route('book.create.show') }}">
                                        Загрузить новую
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="user-block-library__area row">
                        <div class="user-block-library col-md-12">
                            <div class="user-block-library-content panel panel-default">
                                <div class="user-block-library__title panel-heading">
                                    {{ optional(Auth::user())->id == $user->id  ? 'Моя библиотека' : 'Библиотека пользователя' }}
                                </div>
                                <div class="user-block-library-elements panel-body">
                                    @foreach ($user->libraryBooks as $book)
                                        <a href="{{ $book->url }}" class="user-block-library__elements">
                                            {{ $book->title }}
                                        </a>
                                        {{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
