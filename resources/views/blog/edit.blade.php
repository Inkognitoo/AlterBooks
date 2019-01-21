@extends('layouts.app')

@section('title', 'Редактировать статью')

@section('content')
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ t('article', 'Редактирование статьи') }}</h1>
                    </div>

                    <div class="panel-body">

                        <form class="form-horizontal" method="POST" action="{{ route('book.edit', ['slug' => $article->slug]) }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">
                                    {{ t('article', 'Название') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control" name="title"
                                           value="{{ old('title', $article->title) }}" autofocus >

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                                <label for="text" class="col-md-4 control-label">
                                    {{ t('article', 'Текст') }}
                                </label>

                                <div class="col-md-6">
                                <textarea id="description" class="form-control" name="text"
                                          autofocus rows="5">{{ old('text', $article->text) }}</textarea>

                                    @if ($errors->has('text'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ t('blog.button', 'Создать') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@endsection