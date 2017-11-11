@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
    <div class="user-content container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Список книг</div>
                    <div class="panel-body">
                        <div>
                            @if(!$books->isEmpty())
                                @foreach ($books as $book)
                                    @include('book.book-profile-mini')
                                @endforeach
                            @else
                                <div class="text-center">
                                    Нет ни одной книги, доступной для чтения
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection