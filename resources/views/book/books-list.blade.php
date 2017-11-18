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
                    <div class="panel-heading">Список книг</div>
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