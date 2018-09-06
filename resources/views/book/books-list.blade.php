@php
    /** @var Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books */
@endphp

@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
    <div class="book-list-aside col-3 col-clear col-lg-0">
        <div class="book-list-genres" data-status="close">
            <div class="book-list-genres__title">
                список&nbsp;жанров
                <hr class="book-list-genres__hr">
            </div>
            <form class="book-list-genres__content">
                @php
                    $genres = \App\Models\Genre::all()->sortBy('name');
                @endphp

                @foreach($genres as $genre)
                    <input type="checkbox"
                           id="genre-1-{{ $genre->id }}"
                           name="genre-1"
                           data-id="{{ $genre->id }}"
                           value="{{ $genre->slug }}">
                    <label class="book-list-genres__element" for="genre-1-{{ $genre->id }}">
                        {{ $genre->name }}
                    </label>
                @endforeach
            </form>
            <div class="book-list-genres__stripe"></div>
            <div class="book-list-genres__more">
                больше жанров
            </div>
        </div>
    </div>

    <div class="book-list-main col-8 col-clear col-lg-10 col-md-11 col-sm-12">
        <div class="row">
            <div class="col-12 col-clear">
                <form class="book-list-search" onsubmit="return false">
                    <input class="book-list-search__input" id="book-search" type="text"
                           placeholder="название произведения">
                    <input class="book-list-search__button" type="submit" value="">
                    {{--<div class="book-list-search-variants">--}}
                        {{-- тут могли бы быть ваши варианты --}}
                    {{--</div>--}}
                </form>
            </div>

            <div class="book-list-sort col-12 col-clear">
                <div class="book-list-sort__title">
                    сортировать
                </div>
                <form class="book-list-sort__content">
                    <input type="radio" id="sort-date" name="sort" value="date" checked>
                    <label class="book-list-sort__element" for="sort-date" disabled>
                        по новизне
                    </label>
                    <input type="radio" id="sort-rating" name="sort" value="rating" disabled>
                    <label class="book-list-sort__element" for="sort-rating">
                        по рейтингу
                    </label>
                    <input type="radio" id="sort-size" name="sort" value="size" disabled>
                    <label class="book-list-sort__element" for="sort-size">
                        по объему
                    </label>
                </form>
            </div>

            <div class="col-0 col-lg-12 col-lg-clear">
                <div class="book-list-genres book-list-genres_mini" data-status="close">
                    <div class="book-list-genres__title">
                        список&nbsp;жанров
                        <hr class="book-list-genres__hr">
                    </div>
                    <form class="book-list-genres__content">
                        @foreach($genres as $genre)
                            <input type="checkbox"
                                   id="genre-2-{{ $genre->id }}"
                                   name="genre-2"
                                   data-id="{{ $genre->id }}"
                                   value="{{ $genre->slug }}">
                            <label class="book-list-genres__element" for="genre-2-{{ $genre->id }}">
                                {{ $genre->name }}
                            </label>
                        @endforeach
                    </form>
                    <div class="book-list-genres__stripe"></div>
                    <div class="book-list-genres__more">
                        больше жанров
                    </div>
                </div>
            </div>


            @if($books->isEmpty())
                <div class="text-center">
                    {{ t('book', 'Нет ни одной книги, доступной для чтения') }}
                </div>
            @endif

            @foreach ($books as $book)
                @include('book.book-profile-mini')
            @endforeach

            <div class="row row-center">
                <div class="col-12 col-clear col-center">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection