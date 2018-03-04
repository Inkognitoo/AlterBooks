@php
    /** @var \App\Book $book */
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp

@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="book-reader-content container">
        <div class="book-reader-content__area row">
            <div class="book-reader-block__area col-md-8 col-md-offset-2">
                <div class="book-reader-block book-reader-block panel panel-default">
                    <div class="book-reader-block__title panel-heading">
                        <a href="{{ $book->url }}">
                            {{ $book->title }}
                        </a> |
                        {{ $current_page }}
                    </div>

                    <div class="book-reader-block-content panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('book.page.edit', ['id' => $book->slug, 'current_page' => $current_page]) }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-12">
                                <textarea id="text" class="form-control" name="text" autofocus rows="28">{{ $text }}</textarea>
                                @if ($errors->has('text'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ t('reader.button', 'Сохранить') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
