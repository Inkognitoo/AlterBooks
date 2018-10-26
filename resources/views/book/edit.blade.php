@php
    /** @var \App\Models\Book $book */
    /** @var \Illuminate\Support\ViewErrorBag $errors */
    if (session('book_id')) {
        $book = App\Models\Book::findAny(['id' => session('book_id')]);
    }
@endphp

@extends('layouts.app')

@section('title', 'Редактировать книгу')

@section('content')
    <div class="edit col-12">
        <div class="edit-block book-edit">
            <div class="edit-block-header">
                <hr class="edit-block-header__hr">
                <div class="edit-block-header__title">
                    информация&nbsp;о&nbsp;книге
                </div>
                <hr class="edit-block-header__hr">
            </div>

            <form class="edit-block__main"
                  method="POST"
                  action="{{ route('book.edit', ['id' => $book->slug]) }}"
                  enctype="multipart/form-data">

                {{ csrf_field() }}

                @if (session('status'))
                    <div class="edit-block-status">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="title">
                        Название
                    </label>
                    <div class="edit-block-element__content">
                        <input class="edit-block-element__content_input"
                               type="text"
                               id="title"
                               name="title"
                               maxlength="50"
                               value="{{ old('title', $book->title) }}">
                        <div class="edit-block-element__size">
                            15 / 50
                        </div>
                    </div>
                </div>

                <div class="edit-block-element edit-block-element_string">
                    <label class="edit-block-element__title"
                           for="change_text">
                        Текст книги
                    </label>
                    <div class="edit-block-element__content">
                        <input type="file"
                               id="change_text"
                               name="text">
                    </div>
                </div>

                <div class="edit-block-element">
                    <label class="edit-block-element__title"
                           for="change_status">
                        Статус
                    </label>
                    <select class="edit-block-element__content edit-block-element__content_select"
                            id="status"
                            name="status">
                        <option value="{{ \App\Models\Book::STATUS_OPEN }}"
                                {{ old('status', $book->status) == \App\Models\Book::STATUS_OPEN ? 'selected' : ''}}>
                            чистовик (видят все)
                        </option>
                        <option value="{{ \App\Models\Book::STATUS_CLOSE }}"
                                {{ old('status', $book->status) == \App\Models\Book::STATUS_CLOSE ? 'selected' : '' }}>
                            черновик (видите только вы)
                        </option>
                    </select>
                </div>

                <div class="edit-block-element">
                    <div class="edit-block-element__title">
                        Жанры
                    </div>
                    <div class="edit-block-element__content">

                        <div class="edit-block-element__content_checkbox">
                            @foreach(\App\Models\Genre::all() as $genre)
                                <div class="edit-block-element__checkbox">
                                    <label for="genre-{{ $genre->id }}"
                                           class="col-xs-12 checkbox">
                                        <input type="checkbox"
                                               class="checkbox__field"
                                               id="genre-{{ $genre->id }}"
                                               name="genres[]"
                                               value="{{ $genre->slug }}"
                                                {{ $book->hasGenre($genre) ? 'checked' : null }} >
                                        <span class="checkbox-animation">
                                            <span class="checkbox-animation__button"></span>
                                            <span class="checkbox-animation__icon"></span>
                                            <span class="checkbox-animation__ripple"></span>
                                        </span>
                                        <span class="checkbox__content">
                                            {{ $genre->name }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="edit-block-element edit-block-element_wide">
                    <div class="edit-block-header">
                        <hr class="edit-block-header__hr">
                        <label class="edit-block-header__title"
                               for="date">
                            описание&nbsp;книги
                        </label>
                        <hr class="edit-block-header__hr">
                    </div>
                    <div class="edit-block-element__content edit-block-element__content_wide">
                            <textarea class="edit-block-element__content_date"
                                      type="date"
                                      id="date"
                                      name="description"
                                      placeholder="Введите описание">{{ old('description_plain', $book->description_plain) }}</textarea>
                    </div>
                </div>

                <div class="edit-block_buttons row row-center">
                    <input  class="edit-block-element__button edit-block-element__button_sm button button_green "
                            type="submit"
                            value="сохранить">
                    <a class="edit-block-element__button edit-block-element__button_sm button"
                       href="{{ $book->url }}">
                        вернуться к профилю
                    </a>
                </div>
            </form>
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
