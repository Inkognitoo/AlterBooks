@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
    <div class="user-content container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Список книг</div>
                    <div class="panel-body">
                        @section('book-profile-mini', '123')
                        @overwrite
                        @include('book.book-profile-mini')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

