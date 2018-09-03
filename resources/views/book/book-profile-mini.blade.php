@php
    /** @var \App\Models\Book $book */
@endphp

<div class="book-list-element col-12 col-clear">
    <div class="row">
        <div class="col-12 col-clear col-lg-12">
            <div class="book-list-element__aside">
                <a class="book-list-element__cover col-2 col-end col-lg-3 col-lg-end"
                   style="background-image: url('{{ $book->cover_url }}')"
                   href="{{ $book->author->url }}"></a>
            </div>

            <div class="book-list-element__main">
                <div class="row">
                    <a class="book-list-element__title col-8 col-lg-12 col-sm-clear"
                       href="{{ $book->author->url }}">
                        {{ $book->title }}
                    </a>

                    <div class="book-list-element-rating col-3 col-lg-0">
                        <div class="book-list-element-rating-stars">
                            <div class="book-list-element-rating-stars__fill
                                       {{ $book->rating <= 2 ? 'book-list-element-rating-stars__fill_red': '' }}
                                       {{ $book->rating > 2 && $book->rating <= 3.5 ? 'book-list-element-rating-stars__fill_yellow': '' }}
                                       {{ $book->rating > 3.5 ? 'book-list-element-rating-stars__fill_green': '' }}"
                                 style="width: {{ $book->rating * 100 / 5 }}%"></div>
                            <div class="book-list-element-rating-stars__form"></div>
                        </div>
                        <div class="book-list-element-rating__number">
                            ({{ number_format($book->rating, 1) }})
                        </div>
                    </div>

                    <a class="book-list-element__author col-12 col-sm-clear"
                       href="{{ $book->author->url }}">
                        {{ $book->author->surname }} {{ $book->author->name }}
                    </a>

                    <div class="book-list-element-rating col-0 col-lg-12 col-sm-clear">
                        <div class="book-list-element-rating-stars">
                            <div class="book-list-element-rating-stars__fill
                                       {{ $book->rating <= 2 ? 'book-list-element-rating-stars__fill_red': '' }}
                            {{ $book->rating > 2 && $book->rating <= 3.5 ? 'book-list-element-rating-stars__fill_yellow': '' }}
                            {{ $book->rating > 3.5 ? 'book-list-element-rating-stars__fill_green': '' }}"
                                 style="width: {{ $book->rating * 100 / 5 }}%"></div>
                            <div class="book-list-element-rating-stars__form"></div>
                        </div>
                        <div class="book-list-element-rating__number">
                            ({{ number_format($book->rating, 1) }})
                        </div>
                    </div>

                    <div class="book-list-element-description col-12 col-md-0" data-status="close">
                        @if(filled($book->description))
                            {!! $book->description !!}
                        @else
                            <span class="no-description">{{ t('book', '-описание отсутствует-') }}</span>
                        @endif

                        <div class="book-list-element-description__block"></div>
                        <div class="book-list-element-description__more">
                            читать далее
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-clear">
            <div class="book-list-element__description row">
                <div class="book-list-element-description col-0 col-md-12" data-status="close">
                    @if(filled($book->description))
                        {!! $book->description !!}
                    @else
                        <span class="no-description">{{ t('book', '-описание отсутствует-') }}</span>
                    @endif
                    <div class="book-list-element-description__block"></div>
                    <div class="book-list-element-description__more">
                        читать далее
                    </div>
                </div>
            </div>
        </div>

        <div class="book-list-element__details col-12 col-clear">
            <div class="row">
                <div class="book-list-element-genres col-12 col-clear">
                    <div class="row row-end">
                        <div class="book-list-element-genres__title col-2 col-end col-md-12 col-md-start">
                            жанры
                        </div>
                        <div class="book-list-element-genres__container col-10 col-md-12">
                            @if(filled($book->genres))
                                @foreach($book->genres as $genre)
                                    <a class="book-list-element-genres__element"
                                       name="{{ $genre->slug }}">
                                        {{ $genre->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-2 col-md-0"></div>
                <div class="book-list-element-info col-10 col-md-12">
                    <div class="book-list-element-info__element book-list-element-info__element_not-important">
                        <div class="book-list-element-info__icon
                                    icon icon__calendar_grey"></div>
                        <div class="book-list-element-info__content">
                            {{ $book->updated_at->format('d.m.Y') }}
                        </div>
                    </div>
                    <div class="book-list-element-info__element book-list-element-info__element_not-important">
                        <div class="book-list-element-info__icon
                                    icon icon__book-reference_grey"></div>
                        <div class="book-list-element-info__content">
                            {{ $book->page_count }} {{ trans_choice('multiply.pages', $book->page_count)}}
                        </div>
                    </div>
                    <a class="book-list-element-info__element" href="{{ $book->url }}#review">
                        <div class="book-list-element-info__icon
                                    icon icon__calendar icon__conversation_grey"></div>
                        <div class="book-list-element-info__content">
                            {{ $book->reviews->count() }} {{ trans_choice('multiply.reviews', $book->reviews->count()) }}
                        </div>
                    </a>
                </div>
                <div class="col-12 col-clear">
                    <div class="row row-end">
                        <div class="col-3 col-clear col-lg-4 col-lg-clear col-md-5 col-md-clear col-sm-12 col-sm-clear">
                            <a class="book-list-element__button button" href="{{ $book->url }}">
                                профиль
                            </a>
                        </div>
                        <div class="col-3 col-clear col-lg-4 col-lg-clear col-md-5 col-md-clear col-sm-12 col-sm-clear">
                            <a class="book-list-element__button button button_green" href="">
                                читать
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
