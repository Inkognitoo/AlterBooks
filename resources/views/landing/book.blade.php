<div class="landing-book">
    <div class="landing-book__cover" style="background-image: url({{ $book->cover_url }});"></div>
    <div class="landing-book__title">
        {{ $book->title }}
    </div>
    <a class="landing-book__read button"
       href="{{ $book->url }}">
        {{ t('book.button', 'Профиль') }}
    </a>
</div>