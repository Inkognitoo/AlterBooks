@extends('layouts.app')

@section('title')
    {{ $user->surname }}
    {{ $user->name }}
    {{ $user->patronymic }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Профиль пользователя</div>

                <div class="panel-body">
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-4" style="margin-bottom: 10px">
                            <img src="{{ $user->getAvatarUrl() }}" style="width: 200px" alt="avatar" class="img-rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    {{ $user->surname }}
                                    {{ $user->name }}
                                    {{ $user->patronymic }}
                                    <br>
                                    {{ date('d.m.Y', strtotime($user->birthday_date)) }}
                                    ({{date('Y', time()) - date('Y', strtotime($user->birthday_date))}})
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->id == $user->id)
                                    <a type="button" class="btn btn-default" href="{{ route('user_edit_show', ['id' => $user->id]) }}">Редактировать</a>
                                @endif
                            @endauth

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ (Auth::user() && (Auth::user()->id == $user->id)) ? 'Мои книги' : 'Книги автора' }}
                                </div>
                                <div class="panel-body">
                                    @foreach ($user->books as $book)
                                        <a href="{{ $book->getUrl() }}">{{ $book->title }}</a>{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->id == $user->id)
                                    <a type="button" class="btn btn-default" href="{{ route('book_create_show') }}">Загрузить новую</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ (Auth::user() && (Auth::user()->id == $user->id)) ? 'Моя библиотека' : 'Библиотека пользователя' }}
                                </div>
                                <div class="panel-body">
                                    @foreach ($user->libraryBooks as $book)
                                        <a href="{{ $book->getUrl() }}">{{ $book->title }}</a>{{ !$loop->last ? ',' : '' }}
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
