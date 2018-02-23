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


                            <form class="form-inline">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @php
                                                    $genres = \App\Genre::all();
                                                @endphp

                                                @foreach($genres as $genre)
                                                    @if ($loop->iteration == round($genres->count() / 2))
                                                        </div>
                                                        <div class="col-md-6">
                                                    @endif

                                                    <label class="checkbox-inline pull-left">
                                                        <input type="checkbox" value="{{ $genre->slug }}"
                                                               name="genres[]"
                                                                {{ in_array($genre->slug, Request::get('genres', [])) ? 'checked' : null }}>
                                                        {{ $genre->name }}
                                                    </label><br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <input id="sort" name="sort" type="hidden" value="{{Request::get('sort')}}">
                                        <div class="btn-group books-list-sort">
                                            <button type="button" class="btn btn-default dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    id="sort-placeholder">
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
                                                <li onclick="document.getElementById('sort').value='rating';
                                                             document.getElementById('sort-placeholder').innerHTML='По рейтингу <span class=\'caret\'></span>'"><a href="#">По рейтингу</a></li>
                                                <li onclick="document.getElementById('sort').value='date';
                                                             document.getElementById('sort-placeholder').innerHTML='По дате добавления <span class=\'caret\'></span>'"><a href="#">По дате добавления</a></li>
                                            </ul>
                                        </div>


                                    </div>

                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-md-12">
                                        <button class="btn btn-default pull-right" type="submit">найти</button>
                                    </div>
                                </div>

                            </form>


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