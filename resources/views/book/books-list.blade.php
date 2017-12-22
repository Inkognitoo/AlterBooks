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
                        <div>Список книг</div>

                        <div class="btn-group books-list-sort">
                            <button type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @switch(Request::get('sort'))
                                    @case('rating')
                                        По рейтингу <span class="caret"></span>
                                        @break
                                    @case('date')
                                        По дате добавления <span class="caret"></span>
                                        @break
                                    @default
                                        По рейтингу <span class="caret"></span>
                                @endswitch
                            </button>
                            <ul class="dropdown-menu books-list-sort__open">
                                <li><a href="{{ route('book.books-list', ['sort' => 'rating']) }}">По рейтингу</a></li>
                                <li><a href="{{ route('book.books-list', ['sort' => 'date']) }}">По дате добавления</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            @if($books->isEmpty())
                                <div class="text-center">
                                    Нет ни одной книги, доступной для чтения
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