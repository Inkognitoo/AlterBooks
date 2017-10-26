@extends('layouts.app')

@section('title', 'Reader')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" style="max-width: 600px">
                    <div class="panel-heading">{{ $book->title }} | {{ $current_page }}</div>

                    <div class="panel-body">
                        <p>
                            {!! $text !!}
                        </p>
                        <div class="center-block" style="text-align: center">
                        <a type="button" class="btn btn-default text-center"
                           href="{{ route('book.read.page', ['id' => $book->id, 'page_number' => ($current_page - 1)]) }}">{{ $current_page - 1 }}</a>

                        <a type="button" class="btn btn-default text-center"
                           href="{{ route('book.read.page', ['id' => $book->id, 'page_number' => ($current_page + 1)]) }}">{{ $current_page + 1 }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
