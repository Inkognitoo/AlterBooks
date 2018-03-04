@php
    /** @var Illuminate\Database\Eloquent\Collection|\App\Book[] $books */
@endphp

@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
    <div class="user-content container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center books-list-heading">
                        <div> {{ t('book', 'Список книг') }}</div>
                        <hr class="hr">

                        <form class="form-inline">
                            <div class="row">
                                <div class="col-md-12 genre">
                                    <div class="sort__title">
                                        {{ t('book', 'Выберите жанры') }}
                                    </div>
                                    <div class="genre-content">
                                        @php
                                            $genres = \App\Genre::all();
                                        @endphp

                                        @foreach($genres as $genre)
                                            <input type="checkbox"
                                                   id="{{ $genre->id }}"
                                                   value="{{ $genre->slug }}"
                                                   name="genres[]"
                                                   class="genre-content__checkbox"
                                                    {{ in_array($genre->slug, Request::get('genres', [])) ? 'checked' : null }}>
                                            <label for="{{ $genre->id }}"
                                                   class="genre-content__label btn btn-default">
                                                {{ $genre->name }}
                                            </label>
                                            <br>
                                        @endforeach
                                    </div>
                                    <hr class="hr">
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="sort__title col-md-2">
                                            {{ t('book', 'Сортировать') }}
                                        </div>
                                        <div class="col-md-8">
                                            <input id="sort" name="sort" type="hidden" value="{{Request::get('sort')}}">
                                            <div class="btn-group books-list-sort">
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                        id="sort-placeholder">
                                                    @switch(Request::get('sort'))
                                                        @case('rating')
                                                            {{ t('book', 'по рейтингу') }} <span class="caret"></span>
                                                        @break
                                                        @case('date')
                                                            {{ t('book', 'по дате добавления') }}<span class="caret"></span>
                                                        @break
                                                        @default
                                                            {{ t('book', 'по рейтингу') }} <span class="caret"></span>
                                                    @endswitch
                                                </button>
                                                <ul class="dropdown-menu books-list-sort__open">
                                                    <li onclick="document.getElementById('sort').value='rating';
                                                             document.getElementById('sort-placeholder').innerHTML='{{ t('book', 'по рейтингу') }} <span class=\'caret\'></span>'">
                                                        <a href="#">{{ t('book', 'по рейтингу') }}</a>
                                                    </li>
                                                    <li onclick="document.getElementById('sort').value='date';
                                                             document.getElementById('sort-placeholder').innerHTML='{{ t('book', 'по дате добавления') }} <span class=\'caret\'></span>'">
                                                        <a href="#">{{ t('book', 'по дате добавления') }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button class="btn btn-default pull-right" type="submit">
                                        {{ t('book.button', 'Найти') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body">
                        <div>
                            @if($books->isEmpty())
                                <div class="text-center">
                                    {{ t('book', 'Нет ни одной книги, доступной для чтения') }}
                                </div>
                            @endif

                            @foreach ($books as $book)
                                @include('book.book-profile-mini')
                            @endforeach
                        </div>
                        <div class="col-md-12 text-center">
                            {{ $books->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection