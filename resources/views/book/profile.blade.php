@php
    /** @var \App\Models\Book $book */
@endphp

@extends('layouts.app')

@section('title', $book->title)

@section('canonical', $book->canonical_url)

@section('content')
    <div class="book col-12">
        <div class="row">
            <div class="book__aside col-4 col-md-0">
                <div class="row row-center">
                    <div class="book__cover col-12"
                         style="background-image: url('{{ $book->cover_url }}')"></div>
                    <div class="book-buttons col-12">
                        <div class="book-buttons__tab book-buttons__tab_3"></div>
                        <div class="row">
                            <a class="book-buttons__element button button_green col-12"
                               href="{{ route('book.page.show', ['id' => $book->slug, 'page_number' => 1]) }}">
                                читать
                            </a>

                            @if(Auth::user())
                                @if(Auth::user()->isAuthor($book))
                                    <a class="book-buttons__element button col-12"
                                       href="{{ route('book.edit.show', ['id' => $book->slug]) }}">
                                        редактировать
                                    </a>
                                @else
                                    <button class="book-buttons__element book-buttons__element_small button col-12"
                                            data-type="{{ Auth::user()->hasBookAtLibrary($book) ? 'delete' : 'add' }}"
                                            data-book-id="{{ $book->id }}" id="libraryButton"
                                            data-delete-text="{{ t('library.button', 'Удалить из библиотеки') }}"
                                            data-add-text="{{ t('library.button', 'Добавить в библиотеку') }}" >
                                        {{ Auth::user()->hasBookAtLibrary($book)
                                            ? t('library.button', 'Удалить из библиотеки')
                                            : t('library.button', 'Добавить в библиотеку') }}
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="book-download col-8 col-center">
                        <div class="row">
                            <div class="book-download__title col-12 col-clear col-center">
                                Скачать
                            </div>

                            <button class="book-download__format button col-12 col-clear col-center"
                                    disabled>
                                <span>txt</span>
                            </button>
                            <div class="book-download__size col-12 col-clear col-center"></div>

                            <button class="book-download__format button col-12 col-clear col-center"
                                    disabled>
                                <span>epub</span>
                            </button>
                            <div class="book-download__size col-12 col-clear col-center"></div>

                            <button class="book-download__format button col-12 col-clear col-center"
                                    disabled>
                                <span>fb2</span>
                            </button>
                            <div class="book-download__size col-12 col-clear col-center"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="book__main col-8 col-md-12">
                <div class="row row-center">
                    <div class="book__title col-12 col-clear col-md-center">
                        {{ $book->title }}
                    </div>
                    <a class="book__author col-12 col-clear col-md-center"
                       href="{{ $book->author->url }}">
                        {{ $book->author->name }} {{ $book->author->surname }}
                    </a>
                    <div class="book__cover col-0 col-clear col-md-12"
                         style="background-image: url('{{ $book->cover_url }}')"></div>
                    <div class="block-info col-12 col-clear col-md-11 col-md-center col-sm-12">
                        <div class="block-info-element">
                            <div class="block-info-element__main block-info-element__main_small">
                                {{ $book->created_at->format('d.m.Y') }}
                            </div>
                            <div class="block-info-element__comment">
                                дата публикации
                            </div>
                        </div>
                        <div class="block-info-element">
                            <div class="block-info-element__main">
                                {{ $book->rating == 0 ? 0 : number_format($book->rating, 1) }}
                            </div>
                            <div class="block-info-element__comment">
                                рейтинг
                            </div>
                        </div>
                        <div class="block-info-element">
                            <div class="block-info-element__main">
                                {{ $book->users->count() }}
                            </div>
                            <div class="block-info-element__comment">
                                добавили в библиотеку
                            </div>
                        </div>
                    </div>

                    <div class="book-buttons col-0 col-clear col-md-12 col-md-11 col-sm-12">
                        <a class="book-buttons__element button button_green"
                           href="{{ route('book.page.show', ['id' => $book->slug, 'page_number' => 1]) }}">
                            читать
                        </a>

                        @if(Auth::user())
                            @if(Auth::user()->isAuthor($book))
                                <a class="book-buttons__element button"
                                   href="{{ route('book.edit.show', ['id' => $book->slug]) }}">
                                    редактировать
                                </a>
                            @else
                                <button class="book-buttons__element book-buttons__element_small button"
                                        data-type="{{ Auth::user()->hasBookAtLibrary($book) ? 'delete' : 'add' }}"
                                        data-book-id="{{ $book->id }}" id="libraryButton"
                                        data-delete-text="{{ t('library.button', 'Удалить из библиотеки') }}"
                                        data-add-text="{{ t('library.button', 'Добавить в библиотеку') }}" >
                                    {{ Auth::user()->hasBookAtLibrary($book)
                                        ? t('library.button', 'Удалить из библиотеки')
                                        : t('library.button', 'Добавить в библиотеку') }}
                                </button>
                            @endif
                        @endif
                    </div>

                    <div class="book__description col-12 col-clear col-md-11 col-sm-12">
                        @if(filled($book->description))
                            {!! $book->description !!}
                        @else
                            <span class="no-description">
                                @if(Auth::user())
                                    @if(Auth::user()->isAuthor($book))
                                        добавьте описание произведению
                                    @else
                                        -описание отсутствует-
                                    @endif
                                @else
                                    -описание отсутствует-
                                @endif
                            </span>
                        @endif
                    </div>

                    <hr class="hr col-md-11 col-sm-12">
                    <div class="book-genres col-12 col-clear col-md-11 col-sm-12">
                        <div class="row">
                            <div class="book-genres__title col-12 col-clear">
                                жанры
                            </div>
                            <div class="book-genres-content col-12 col-clear">
                                @if(filled($book->genres))
                                    @foreach($book->genres as $genre)
                                        <a class="book-genres-content__element">{{ $genre->name }}</a>
                                    @endforeach
                                @else
                                    @if(Auth::user())
                                        @if(Auth::user()->isAuthor($book))
                                            <div class="book-genres__no-genres">
                                                добавьте жанры, чтобы ваше произведение легче было найти
                                            </div>
                                        @else
                                            <div class="book-genres__no-genres">
                                                -здесь пока нет жанров-
                                            </div>
                                        @endif
                                    @else
                                        <div class="book-genres__no-genres">
                                            -здесь пока нет жанров-
                                        </div>
                                    @endif
                                @endif
                        </div>
                        </div>
                    </div>


                    <hr class="hr col-0 col-md-11 col-sm-12">
                    <div class="book-download col-0 col-clear col-md-12 col-md-11 col-sm-12">
                        <div class="row">
                            <div class="book-download__title col-12 col-clear">
                                Скачать
                            </div>
                            <button class="book-download__format button"
                                    disabled>
                                <span>txt</span>
                            </button>

                            <button class="book-download__format button"
                                    disabled>
                                <span>epub</span>
                            </button>

                            <button class="book-download__format button"
                                    disabled>
                                <span>fb2</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="review block-content col-12">
                <div class="block-content-header" id="review">
                    <div class="review__header block-content-header__title block-content-header__title_center">
                        Рецензии
                    </div>
                    <hr class="block-content-header__hr">
                </div>
                <div class="block-content-main">
                    @auth
                        @if(Auth::user()->hasBookReview($book))
                            @include('review.view-self', ['review' => Auth::user()->getBookReview($book)])
                        @else
                            @include('review.create')
                        @endif
                    @endauth

                    @foreach($book->reviews as $review)
                        @if(Auth::user())
                            @if(Auth::user()->id !== $review->user->id)
                                @include('review.view', compact($review))
                            @endif
                        @else
                                @include('review.view', compact($review))
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
