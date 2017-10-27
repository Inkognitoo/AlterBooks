@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="max-width: 600px">
                    <div class="panel-heading">
                        <a href="{{ route('book.show', ['id' => $book->id]) }}">
                            {{ $book->title }}
                        </a> |
                        {{ $current_page }}
                    </div>

                    <div class="panel-body">
                        <p>
                            {!! $text !!}
                        </p>
                        <div class="center-block" style="text-align: center">
                        <a type="button" class="btn btn-default text-center {{ ($current_page == 1) ? 'disabled' : '' }}"
                           href="{{ ($current_page != 1) ? route('book.read.page', ['id' => $book->id, 'page_number' => ($current_page - 1)]) : '' }}">
                            {{ ($current_page != 1) ? $current_page - 1 : '&nbsp;' }}
                        </a>

                        <a type="button" class="btn btn-default text-center {{ ($current_page == $book->page_count) ? 'disabled' : '' }}"
                           href="{{ ($current_page != $book->page_count) ? route('book.read.page', ['id' => $book->id, 'page_number' => ($current_page + 1)]) : '' }}">
                            {{ ($current_page != $book->page_count) ? $current_page + 1 : '&nbsp;' }}
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
