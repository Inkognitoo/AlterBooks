@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Профиль книги</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="book-cover col-md-4">
                            <img src="{{ $book->cover_url }}" alt="cover"
                                 class="book-cover__image img-rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $book->title }}
                                </div>
                                <div class="panel-body">

                                    <a href="{{ route('user.show', ['id' => $book->author->id]) }}">
                                        {{ $book->author->full_name }}
                                    </a>
                                    <br>
                                    {{ $book->description }}
                                </div>
                            </div>

                            @if(filled($book->mongodb_book_id))
                                <a type="button" class="btn btn-default" href="{{ route('book.page.show', ['id' => $book->id, 'page_number' => 1]) }}">Читать</a>
                            @endif
                            @auth
                                @if(Auth::user()->id == $book->author->id)
                                    <a type="button" class="btn btn-default" href="{{ route('book.edit.show', ['id' => $book->id]) }}">Редактировать</a>
                                    <button class="btn btn-default" data-toggle="modal" data-target="#deleteBookModal">Удалить</button>
                                @else
                                    @if(Auth::user()->hasBookAtLibrary($book))
                                        <a type="button" class="btn btn-default" href="{{ route('library.delete', ['id' => $book->id]) }}">Удалить из библиотеки</a>
                                    @else
                                        <a type="button" class="btn btn-default" href="{{ route('library.add', ['id' => $book->id]) }}">Добавить в библиотеку</a>
                                    @endif
                                @endif
                            @endauth

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            @include('review.create')
                        </div>
                    </div>

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

<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="deleteBookModalLabel">Подтвердите удаление</h4>
            </div>
            <div class="modal-body">
                <p>Вы уверены, что хотите удалить книгу <strong>{{ $book->title }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <a type="button" class="btn btn-danger" href="{{ route('book.delete', ['id' => $book->id]) }}">Удалить</a>
            </div>
        </div>
    </div>
</div>
@endsection
