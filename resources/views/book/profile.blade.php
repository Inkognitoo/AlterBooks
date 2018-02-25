@php
    /** @var \App\Book $book */
@endphp

@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{__('book.profile')}}</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="book-cover col-md-4 col-sm-4">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                 class="book-cover__image img-rounded">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $book->title }}
                                </div>
                                <div class="panel-body">
                                    <a href="{{ route('user.show', ['id' => $book->author->id]) }}">
                                        {{ $book->author->full_name }}
                                    </a>
                                    <br>
                                    {{__('book.estimate')}}: {{ $book->rating }}/10
                                    <br><br>
                                    @if(filled($book->description))
                                        {!! $book->description !!}
                                    @else
                                        <span class="no-description">-{{ __('book.no_description') }}-</span>
                                    @endif

                                    @if(filled($book->genres))
                                        <hr>
                                        <div>
                                            @foreach($book->genres as $genre)
                                                <span class="label label-default">{{ $genre->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(filled($book->mongodb_book_id))
                                <a type="button" class="btn btn-default" href="{{ route('book.page.show', ['id' => $book->id, 'page_number' => 1]) }}">{{__('book.read')}}</a>
                            @endif
                            @auth
                                @if(Auth::user()->isAuthor($book))
                                    <a type="button" class="btn btn-default" href="{{ route('book.edit.show', ['id' => $book->id]) }}">{{__('book.edit')}}</a>
                                    <button class="btn btn-default" data-toggle="modal" data-target="#deleteBookModal">{{__('book.delete')}}</button>
                                @else
                                    @if(Auth::user()->hasBookAtLibrary($book))
                                        <button type="button" class="btn btn-default" data-type="delete" data-book-id="{{ $book->id }}" id="libraryButton">{{__('library.delete_from_library')}}</button>
                                    @else
                                        <button type="button" class="btn btn-default" data-type="add" data-book-id="{{ $book->id }}" id="libraryButton">{{__('library.add_to_library')}}</button>
                                    @endif
                                @endif
                            @endauth

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">{{__('review.reviews')}}</div>
                                <div class="panel-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if(blank($book->reviews))
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{__('review.no_description')}}
                                            </div>
                                        </div>
                                        <br>
                                    @endif

                                    @auth
                                        @unless($book->hasReview(Auth::user()) || Auth::user()->isAuthor($book))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-default" type="button" data-toggle="collapse"
                                                            data-target="#collapseReview" aria-expanded="false" aria-controls="collapseReview">
                                                        {{__('review.add_review')}}
                                                    </button>
                                                    <div class="collapse" id="collapseReview">
                                                        <div class="well">
                                                            @include('review.create')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        @endunless
                                    @endauth

                                    <div class="row">
                                        @foreach($book->reviews as $review)
                                            <div class="col-md-12">
                                                @include('review.view', compact($review))
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="deleteBookModalLabel">{{__('modal.confirm_delete')}}</h4>
            </div>
            <div class="modal-body">
                <p>{{__('modal.do_you_really_want_delete_book')}} <strong>{{ $book->title }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">{{__('modal.close')}}</button>
                <a type="button" class="btn btn-danger" href="{{ route('book.delete', ['id' => $book->id]) }}">{{__('modal.delete')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection
