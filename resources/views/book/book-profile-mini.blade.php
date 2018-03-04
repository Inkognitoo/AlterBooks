@php
    /** @var \App\Book $book */
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        {{ $book->title }}
        [
        <a href="{{ $book->author->url }}">
            {{ $book->author->full_name }}
        </a>
        ]
    </div>
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4 book-image">
                    <img src="{{ $book->cover_url }}" class="books-list-cover__img img-rounded" alt="{{ $book->title }}">
                </div>
                <div class="col-md-8 panel panel-default">
                    <div class="panel-heading">

                        {{ t('book', 'Описание книги') }}
                    </div>
                    <div class="panel-body text-justify">
                        @if(filled($book->description))
                            {!! $book->description !!}
                        @else
                            <span class="no-description">{{ t('book', '-описание отсутствует-') }}</span>
                        @endif

                        @if(filled($book->genres))
                            <hr class="hr">
                            <div class="text-left">
                                @foreach($book->genres as $genre)
                                    <span class="label label-default">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-right">
                @if(filled($book->mongodb_book_id))
                    <a type="button" class="btn btn-default" href="{{ route('book.page.show', ['id' => $book->slug, 'page_number' => 1]) }}">
                        {{ t('book.button', 'Читать') }}
                    </a>
                @endif
                <a type="button" class="btn btn-default" href="{{ $book->url }}">
                    {{ t('book.button', 'К профилю книги') }}
                </a>
            </div>
        </div>
    </div>
</div>
