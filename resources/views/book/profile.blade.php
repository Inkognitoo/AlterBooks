@php
    /** @var \App\Book $book */
@endphp

@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ t('book', 'Профиль книги') }}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="book-cover col-md-4 col-sm-4">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                 class="book-cover__image img-rounded">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $book->title }}
                                </div>
                                <div class="panel-body">
                                    <a href="{{ route('user.show', ['id' => $book->author->id]) }}">
                                        {{ $book->author->full_name }}
                                    </a>
                                    <br>
                                    {{ t('book', 'Оценка: :estimate/10', ['estimate' => $book->rating]) }}
                                    <br><br>
                                    @if(filled($book->description))
                                        {!! $book->description !!}
                                    @else
                                        <span class="no-description">{{ t('book', '-описание отсутствует-') }}</span>
                                    @endif

                                    @if(filled($book->genres))
                                        <hr>
                                        <div>
                                            @foreach($book->genres as $genre)
                                                <span class="label label-default">{{ $genre->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(filled($book->mongodb_book_id))
                                <a type="button" class="btn btn-default" href="{{ route('book.page.show', ['id' => $book->id, 'page_number' => 1]) }}">
                                    {{ t('book.button', 'Читать') }}
                                </a>
                            @endif
                            @auth
                                @if(Auth::user()->isAuthor($book))
                                    <a type="button" class="btn btn-default" href="{{ route('book.edit.show', ['id' => $book->id]) }}">
                                        {{ t('book.button', 'Редактировать') }}
                                    </a>
                                    <button class="btn btn-default" data-toggle="modal" data-target="#deleteBookModal">
                                        {{ t('book.button', 'Удалить') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-default"
                                            data-type="{{ Auth::user()->hasBookAtLibrary($book) ? 'delete' : 'add' }}"
                                            data-book-id="{{ $book->id }}" id="libraryButton"
                                            data-delete-text="{{ t('library.button', 'Удалить из библиотеки') }}"
                                            data-add-text="{{ t('library.button', 'Добавить в библиотеку') }}" >
                                        {{ Auth::user()->hasBookAtLibrary($book)
                                            ? t('library.button', 'Удалить из библиотеки')
                                            : t('library.button', 'Добавить в библиотеку') }}
                                    </button>
                                @endif
                            @endauth

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">{{ t('review', 'Рецензии') }}</div>
                                <div class="panel-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if(blank($book->reviews))
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{ t('review', 'Тут пока нет ни одной рецензии. Оставьте отзыв первым!')}}
                                            </div>
                                        </div>
                                        <br>
                                    @endif

                                    @auth
                                        @unless($book->hasReview(Auth::user()) || Auth::user()->isAuthor($book))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-default" type="button" data-toggle="collapse"
                                                            data-target="#collapseReview" aria-expanded="false" aria-controls="collapseReview">
                                                        {{ t('review.button', 'Добавить рецензию') }}
                                                    </button>
                                                    <div class="collapse" id="collapseReview">
                                                        <div class="well">
                                                            @include('review.create')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        @endunless
                                    @endauth

                                    <div class="row">
                                        @foreach($book->reviews as $review)
                                            <div class="col-md-12">
                                                @include('review.view', compact($review))
                                            </div>
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
</div>

<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="deleteBookModalLabel">
                    {{ t('book.modal', 'Подтвердите удаление') }}
                </h4>
            </div>
            <div class="modal-body">
                <p> {!! t('book.modal', 'Вы уверены, что хотите удалить книгу <strong>:book</strong>?', ['book' => $book->title]) !!}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    {{ t('book.button', 'Закрыть') }}
                </button>
                <a type="button" class="btn btn-danger" href="{{ route('book.delete', ['id' => $book->id]) }}">
                    {{ t('book.button', 'Удалить') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
