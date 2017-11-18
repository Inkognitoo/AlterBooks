<div class="landing-book">
    <div class="landing-book-cover">
        <img src="
            @if (filled($book['cover']))
                {{ $book['cover']}}
            @else
                /img/default_book_cover.png
            @endif
        " class="landing-book-cover__image">
    </div>
    <div class="landing-book__title">
        {{ $book['title']}}
    </div>
    <div class="landing-book__read">
        <div class="landing-book__read landing-form">
            <a type="button" class="landing-button" href="{{ route('book.show', ['id' => $book['id']]) }}">
                Профиль
            </a>
        </div>
    </div>
</div>