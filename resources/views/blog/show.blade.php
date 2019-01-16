@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-center">
                <div class="page-header">
                    <h1>{{ $article->title }}</h1>
                </div>
            </div>
            <div class="row row-center">
                <div class="col-9">
                    <div class="text">
                        {!! $article->text !!}
                    </div>
                </div>
                <div class="col-9">
                    <span><i class="glyphicon glyphicon-user"></i>{{ $article->author->full_name }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection