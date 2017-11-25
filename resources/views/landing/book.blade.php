<div class="landing-book">
    <div class="landing-book-cover" style="background-image: url({{ $book->cover_url }});"></div>
    <div class="landing-book__title">
        {{ $book->title }}
    </div>
    <div class="landing-book__read">
        <div class="landing-book__read landing-form">
            <a type="button" class="landing-button" href="{{ $book->url }}">
                Профиль
            </a>
        </div>
    </div>
</div>