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
                                        {{ $book->author->name }}
                                    </a>
                                    <br>
                                    {{ $book->description }}
                                </div>
                            </div>

                            @if(filled($book->mongodb_book_id))
                                <a type="button" class="btn btn-default" href="{{ route('book.read.page', ['id' => $book->id, 'page_number' => 1]) }}">Читать</a>
                            @endif
                            @auth
                                @if(Auth::user()->id == $book->author->id)
                                    <a type="button" class="btn btn-default" href="{{ route('book.edit.show', ['id' => $book->id]) }}">Редактировать</a>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
