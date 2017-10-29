@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="book-reader-content container">
        <div class="book-reader-content__area row">
            <div class="book-reader-block__area col-md-8 col-md-offset-2">
                @if (session('status'))
                    <div class="alert alert-success book-reader-block">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="book-reader-block book-reader-block panel panel-default">
                    <div class="book-reader-block__title panel-heading">
                        <a href="{{ route('book.show', ['id' => $book->id]) }}">
                            {{ $book->title }}
                        </a> |
                        {{ $current_page }}
                    </div>

                    <div class="book-reader-block-content panel-body">
                        <p>
                            {!! $text !!}
                        </p>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4 book-reader-pagination center-block">
                                    <a type="button" class="book-reader-pagination-page book-reader-pagination-page__current btn btn-default text-center {{ ($current_page == 1) ? 'disabled' : '' }}"
                                       href="{{ ($current_page != 1) ? route('book.page.show', ['id' => $book->id, 'page_number' => ($current_page - 1)]) : '' }}">
                                        {{ ($current_page != 1) ? $current_page - 1 : '&nbsp;' }}
                                    </a>

                                    <a type="button" class="book-reader-pagination-page btn btn-default text-center {{ ($current_page == $book->page_count) ? 'disabled' : '' }}"
                                       href="{{ ($current_page != $book->page_count) ? route('book.page.show', ['id' => $book->id, 'page_number' => ($current_page + 1)]) : '' }}">
                                        {{ ($current_page != $book->page_count) ? $current_page + 1 : '&nbsp;' }}
                                    </a>
                            </div>
                            <div class="col-md-4 text-right">
                                @auth
                                    @if(Auth::user()->id == $book->author_id)
                                        <a type="button" class="btn btn-default text-right"
                                           href="{{ route('book.page.edit.show', ['id' => $book->id, 'page_number' => $current_page]) }}">
                                            Редактировать
                                        </a>
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
