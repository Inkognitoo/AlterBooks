@php
    /** @var \App\Book $book */
@endphp

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
                        <div class="row">
                            <div class="col-xs-6">
                                <a href="{{ $book->url }}">
                                    {{ $book->title }}
                                </a>
                            </div>
                            <div class="col-xs-6 text-right">
                                @auth
                                    @if(Auth::user()->id == $book->author_id)
                                        <a type="button" class="btn btn-default" title="редактировать страницу"
                                           href="{{ route('book.page.edit.show', ['id' => $book->slug, 'page_number' => $current_page]) }}">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>

                    <div class="book-reader-block-content panel-body">
                        <p>
                            {!! $text !!}
                        </p>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="book-reader-pagination col-md-8 center-block">
                                    @if($current_page - 1 > 1)
                                        <a type="button" class="book-reader-pagination-page book-reader-pagination-page__first btn btn-default text-center"
                                           href="{{  route('book.page.show', ['id' => $book->slug, 'page_number' => (1)]) }}">
                                            1
                                        </a>
                                        <a type="button" class="book-reader-pagination-page book-reader-pagination-page__dots btn btn-default text-center disabled" id="dots-1">
                                            ...
                                        </a>
                                    @endif

                                    @if($current_page != 1)
                                        <a type="button" class="book-reader-pagination-page book-reader-pagination-page__previous btn btn-default text-center {{ ($current_page == 1) ? 'disabled' : '' }}"
                                           href="{{ ($current_page != 1) ? route('book.page.show', ['id' => $book->slug, 'page_number' => ($current_page - 1)]) : '' }}">
                                            {{ ($current_page != 1) ? $current_page - 1 : '&nbsp;' }}
                                        </a>
                                    @endif

                                    <a type="button" class="book-reader-pagination-page book-reader-pagination-page__current btn btn-default text-center disabled"
                                       href="{{ route('book.page.show', ['id' => $book->slug, 'page_number' => ($current_page)]) }}">
                                        {{ $current_page }}
                                    </a>

                                    @if($current_page != $book->page_count)
                                        <a type="button" class="book-reader-pagination-page book-reader-pagination-page_next btn btn-default text-center {{ ($current_page == $book->page_count) ? 'disabled' : '' }}"
                                            href="{{ ($current_page != $book->page_count) ? route('book.page.show', ['id' => $book->slug, 'page_number' => ($current_page + 1)]) : '' }}">
                                                {{ ($current_page != $book->page_count) ? $current_page + 1 : '&nbsp;' }}
                                        </a>
                                    @endif

                                    @if($current_page + 1 < $book->page_count)
                                        <a type="button" class="book-reader-pagination-page book-reader-pagination-page__dots btn btn-default text-center disabled" id="dots-2">
                                            ...
                                        </a>
                                        <a type="button" class="book-reader-pagin   on-page book-reader-pagination-page__last btn btn-default text-center"
                                            href="{{ route('book.page.show', ['id' => $book->slug, 'page_number' => ($book->page_count)])}}">
                                            {{ $book->page_count }}
                                        </a>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
