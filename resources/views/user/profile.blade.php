@php
    /** @var \App\Models\User $user */
    /** @var Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books */
@endphp

@extends('layouts.app')

@section('title', $user->full_name)

@section('canonical', $user->canonical_url)

@section('content')
    <div class="user col-12">
        <div class="row">
            <div class="user__aside col-4 col-md-0">
                <div class="row row-center">
                    <div class="user__avatar col-12"
                         style="background-image: url('{{ $user->avatar_url }}')"></div>
                    <div class="user-buttons col-12">
                        <div class="row">
                            {{--<button class="user-buttons__element button col-12">--}}
                                {{--<span>библиотека</span>--}}
                            {{--</button>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="user__main col-8 col-md-12">
                <div class="row row-center">
                    <div class="user__name col-12 col-clear col-md-11 col-md-center col-sm-12">
                        {{ $user->full_name }}
                    </div>
                    <div class="user__status col-12 col-clear col-md-11 col-md-center col-sm-12">
                        @switch($user->gender)
                            @case('n')
                                был
                                @break
                            @case('m')
                                был
                                @break
                            @case('f')
                                была
                                @break
                        @endswitch
                        на сайте {{ $user->updated_at->format('d.m.Y') }}
                    </div>
                    <div class="user__avatar col-0 col-clear col-md-8 col-sm-12"
                         style="background-image: url('{{ $user->avatar_url }}')"></div>
                    <div class="block-info col-12 col-clear col-md-11 col-md-center col-sm-12">
                        <div class="block-info-element">
                            <div class="block-info-element__main">
                                {{ $user->rating == 0 ? 0 : number_format($user->rating, 1) }}
                            </div>
                            <div class="block-info-element__comment">
                                рейтинг
                            </div>
                        </div>
                        <div class="block-info-element">
                            <div class="block-info-element__main">
                                {{ $books->count() }}
                            </div>
                            <div class="block-info-element__comment">
                                книг написано
                            </div>
                        </div>
                        <div class="block-info-element">
                            <div class="block-info-element__main">
                                {{ $user->reviews->count() }}
                            </div>
                            <div class="block-info-element__comment">
                                рецензий оставлено
                            </div>
                        </div>
                    </div>
                    <div class="col-0 col-clear col-md-11 col-sm-12">
                        <div class="row">
                            <button class="user-buttons__element button col-12">
                                <span>библиотека</span>
                            </button>
                        </div>
                    </div>

                    <div class="user-content block-content col-12 col-clear col-md-11 col-sm-12">
                        <div class="block-content-header">
                            <div class="block-content-header__title">
                                данные&nbsp;пользователя
                            </div>
                            <hr class="block-content-header__hr">
                        </div>
                        <div class="block-content-main">
                            <div class="block-content-main__element">
                                <div class="block-content-main__title">
                                    Псевдоним
                                </div>
                                <div class="block-content-main__info">
                                    Family@Cross
                                </div>
                            </div>

                            @if(filled($user->birthday_date))
                                <div class="block-content-main__element">
                                    <div class="block-content-main__title">
                                        Дата рождения
                                    </div>
                                    <div class="block-content-main__info">
                                        {{ $user->birthday_date->format('d.m.Y') }}
                                        ({{ $user->birthday_date->diffInYears(\Carbon\Carbon::now()) }})
                                    </div>
                                </div>
                            @endif

                            <div class="block-content-main__element">
                                <div class="block-content-main__title">
                                    Пол
                                </div>
                                <div class="block-content-main__info">
                                    @switch($user->gender)
                                        @case('n')
                                            не определен
                                            @break
                                        @case('m')
                                            мужской
                                            @break
                                        @case('f')
                                            женский
                                            @break
                                    @endswitch
                                </div>
                            </div>

                            @if(filled($user->about))
                                <div class="block-content-main__element">
                                    <div class="block-content-main__title block-content-main__title_large">
                                        О себе
                                    </div>
                                    <div class="block-content-main__info block-content-main__info_large">
                                        {!! $user->about !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row-center">
                <div class="user__books col-12 col-md-11 col-sm-12">
                    <div class="col-12 col-clear">
                        <div class="block-content row">
                            <div class="col-12 col-clear col-md-0">
                                <div class="block-content-header block-content-header_center">
                                    <div class="block-content-header__title">
                                        книги&nbsp;автора
                                    </div>
                                    <hr class="block-content-header__hr">
                                </div>
                            </div>
                            <div class="col-0 col-clear col-md-12">
                                <div class="block-content-header">
                                    <div class="block-content-header__title">
                                        книги&nbsp;автора
                                    </div>
                                    <hr class="block-content-header__hr">
                                </div>
                            </div>
                            <div class="col-12 col-clear">
                                <div class="user-books row row-between"
                                     id="user-books"
                                     data-status="close">

                                    @if(count($books) == 0)
                                        <div class="user-books__no-books">
                                            здесь пока нет книг
                                        </div>
                                    @endif

                                    @foreach ($books as $book)
                                        <div class="user-book col-6 col-md-12 row">
                                            <div class="user-book__main col-12">
                                                <div class="user-book__cover"
                                                     style="background-image: url('{{ $book->cover_url }}')"></div>
                                                <div class="user-book__info">
                                                    <a class="user-book__title"
                                                       href="{{ $book->url }}">
                                                        {{ $book->title }}
                                                    </a>
                                                    <div class="block-content-main user-book-content">
                                                        <div class="block-content-main__element user-book-content__element">
                                                            <div class="block-content-main__title user-book-content__title">
                                                                Опубликовано
                                                            </div>
                                                            <div class="block-content-main__info user-book-content__info">
                                                                {{ $book->created_at->format('d.m.Y') }}
                                                            </div>
                                                        </div>
                                                        <div class="block-content-main__element user-book-content__element">
                                                            <div class="block-content-main__title user-book-content__title">
                                                                Страниц
                                                            </div>
                                                            <div class="block-content-main__info user-book-content__info">
                                                                {{ $book->page_count }}
                                                            </div>
                                                        </div>
                                                        <div class="block-content-main__element user-book-content__element">
                                                            <div class="block-content-main__title user-book-content__title">
                                                                Рейтинг
                                                            </div>
                                                            <div class="block-content-main__info user-book-content__info">
                                                                {{ $book->rating == 0 ? 0 : number_format($user->rating, 1) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($books->count() > 2)
                                            <div class="user-books__button_close col-12 col-clear"
                                                 id="user-books-close">
                                                <div class="row row-end">
                                                    <button class="user-book__button button button_second col-4 col-md-12">
                                                        <span>
                                                            и еще {{ $books->count() - 2 }}
                                                            {{ Lang::choice('книга|книги|книг', $books->count() - 2) }}
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="user-books__button_open col-12 col-clear"
                                                 id="user-books-open">
                                                <div class="row">
                                                    <button class="user-book__button button button_second col-4 col-md-6 col-sm-12">
                                                        <span>скрыть</span>
                                                    </button>
                                                </div>
                                            </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
