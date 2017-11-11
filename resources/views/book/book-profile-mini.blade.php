@section('profile-book-mini')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $book->title }}
            [
            <a href="{{ route('user.show', ['id' => $book->author->id]) }}">
                {{ $book->author->full_name }}
            </a>
            ]
        </div>
        <div class='panel-body'>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <img src="{{ $book->cover_url }}" class="books-list-cover__img img-rounded" alt="cover">
                    </div>
                    <div class="col-md-8 panel panel-default">
                        <div class="panel-heading">Описание книги</div>
                        <div class="panel-body text-justify">
                            @if(filled($book->description))
                                {{ $book->description }}
                            @else
                                <span class="no-description">-описание отсутствует-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-offset-0 text-right">
                        @if(filled($book->mongodb_book_id))
                            <a type="button" class="btn btn-default" href="{{ route('book.page.show', ['id' => $book->id, 'page_number' => 1]) }}">
                                Читать
                            </a>
                        @endif
                        <a type="button" class="btn btn-default" href="{{ route('book.show', ['id' => $book->id]) }}">
                            К профилю книги
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@overwrite

@section('profile-book-mini')
@show