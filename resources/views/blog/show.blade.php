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
                <div class="col-9" style="margin-top: 20px">
                    <span style="padding-right: 100px">{{ $article->author->full_name }}</span>
                    <span>{{ $article->created_at->format('d-m-Y') }}</span>
                </div>
                @if(Auth::user()->id == $article->author->id)
                    <div class="col-9" style="margin-top: 20px">
                        <a href="#">Редактировать статью</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection