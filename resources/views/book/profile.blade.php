@extends('layouts.app')

@section('title')
    {{ $book->title }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Профиль книги</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4" style="margin-bottom: 10px">
                            <img src="{{ $book->getCoverUrl() }}" style="width: 200px" alt="cover" class="img-rounded">
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $book->title }}
                                </div>
                                <div class="panel-body">

                                    <a href="{{ route('user_show', ['id' => $book->author->id]) }}">
                                        {{ $book->author->name }}
                                    </a>
                                    <br>
                                    {{ $book->description }}
                                </div>
                            </div>

                            @auth
                                @if(Auth::user()->id == $book->author->id)
                                    <a type="button" class="btn btn-default" href="{{ route('book_edit_show', ['id' => $book->id]) }}">Редактировать</a>
                                @else
                                    @if(Auth::user()->hasBookAtLibrary($book))
                                        <a type="button" class="btn btn-default" href="{{ route('delete_book_from_library', ['id' => $book->id]) }}">Удалить из библиотеки</a>
                                    @else
                                        <a type="button" class="btn btn-default" href="{{ route('add_book_to_library', ['id' => $book->id]) }}">Добавить в библиотеку</a>
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
