@php
    /** @var \App\Book $book */
    /** @var \Illuminate\Support\ViewErrorBag $errors */
    if (session('book_id')) {
        $book = App\Book::findAny(['id' => session('book_id')]);
    }
@endphp

@extends('layouts.app')

@section('title', 'Редактировать книгу')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ t('book', 'Редактирование профиля') }}
                </div>

                <div class="panel-body">

                    <div id="notify-place"></div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('book.edit', ['id' => $book->slug]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">
                                {{ t('book', 'Название') }}
                            </label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title"
                                       value="{{ old('title', $book->title) }}" autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cover') ? ' has-error' : '' }}">
                            <label for="cover" class="col-md-4 control-label">
                                {{ t('book', 'Обложка') }}
                            </label>

                            <div class="col-md-6">
                                <input id="cover" type="file" class="form-control" name="cover">

                                @if ($errors->has('cover'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cover') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                            <label for="text" class="col-md-4 control-label">
                                {{ t('book', 'Текст книги') }}
                            </label>

                            <div class="col-md-6">
                                <input id="text" type="file" class="form-control" name="text" {{ $book->is_processing ? 'disabled' : null }}>

                                @if ($errors->has('text'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">
                                {{ t('book', 'Описание') }}
                            </label>

                            <div class="col-md-6">
                                <textarea id="description" class="book-edit-description form-control" name="description" rows="5">{{ old('description_plain', $book->description_plain) }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
                            <label for="genres" class="col-md-4 control-label">
                                {{ t('book', 'Жанры') }}
                            </label>

                            <div class="col-md-6">
                                @foreach(\App\Genre::all() as $genre)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="genres[]"
                                                   value="{{ $genre->slug }}"
                                                   {{ $book->hasGenre($genre) ? 'checked' : null }} >
                                            {{ $genre->name }}
                                        </label>
                                    </div>
                                @endforeach

                                @if ($errors->has('genres'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('genres') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-4 control-label">
                                {{ t('book', 'Статус') }}
                            </label>

                            <div class="col-md-6">
                                <select id="status" class="form-control" name="status">
                                    <option value="{{ \App\Book::STATUS_CLOSE }}"
                                            {{ old('status', $book->status) == \App\Book::STATUS_CLOSE ? 'selected' : '' }} >
                                        {{ t('book', 'Черновик (видите только вы)') }}
                                    </option>
                                    <option value="{{ \App\Book::STATUS_OPEN }}"
                                            {{ old('status', $book->status) == \App\Book::STATUS_OPEN ? 'selected' : ''}} >
                                        {{ t('book', 'Чистовик (видят все)') }}
                                    </option>
                                </select>

                                @if ($errors->has('status'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ t('book.button', 'Сохранить') }}
                                </button>
                                <a type="button" href="{{ $book->url}}" class="btn btn-primary">
                                    {{ t('book.button', 'К профилю') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    const book_id = "{{ $book->id }}";

    window.onload = function() {
        window.Echo.private(`App.Book.${book_id}`)
            .listen('BookProcessed', (e) => {
                const notify = `
                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        {{ t('book', 'Книга была успешно загружена!') }}
                    </div>`;

                document.getElementById('notify-place').innerHTML = notify;
                document.getElementById('text').disabled = false;

                console.log(e);
            });
    }
</script>
