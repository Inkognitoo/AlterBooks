@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp

@extends('layouts.app')

@section('title', 'Создать книгу')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ t('book', 'Создание книги') }}
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('book.create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">
                                {{ t('book', 'Название') }}
                            </label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title"
                                       value="{{ old('title') }}" autofocus >

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
                                <input id="text" type="file" class="form-control" name="text">

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
                                <textarea id="description" class="form-control" name="description"
                                          autofocus rows="5">
                                </textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
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
                                            {{ (old('status')) == \App\Book::STATUS_CLOSE ? 'selected' : '' }} >
                                        {{ t('book', 'Черновик (видите только вы)') }}
                                    </option>
                                    <option value="{{ \App\Book::STATUS_OPEN }}"
                                            {{ (old('status')) == \App\Book::STATUS_OPEN ? 'selected' : ''}} >
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
                                    {{ t('book.button', 'Создать') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
