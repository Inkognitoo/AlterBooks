@php
    /** @var \App\Models\Review $review */
@endphp

<div class="review-element" data-status="" data-auth="false">
    <div class="review-rating" data-rating ="{{ $review->rating }}">
        <div class="review-rating__header">
            {{ number_format($review->rating, 1) }}
        </div>
        <div class="review-rating-stars">
            @for ($i = 1; $i <= $review->rating; $i++)
                <svg class="review-rating__star review-rating__star_active">
                    <polygon id="star" points="11,0 14.23,6.55  21.46,7.6  16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                </svg>
            @endfor

            @for ($i = $review->rating; $i <= 10; $i++)
                <svg class="review-rating__star">
                    <polygon id="star" points="11,0 14.23,6.55  21.46,7.6  16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                </svg>
            @endfor
        </div>
    </div>
    <div class="review-element__body row row-center">
        <div class="review__aside col-2 col-lg-0">
            <div class="review__avatar"
                 style="background-image: url('{{ $review->user->avatar_url }}')"></div>
        </div>
        <div class="review__main col-10 col-lg-12">
            <div class="row">
                <div class="review__avatar-box col-0 col-lg-2 col-sm-0">
                    <div class="review__avatar"
                         style="background-image: url('{{ $review->user->avatar_url }}')"></div>
                </div>

                <div class="review__info-box col-12 col-clear col-lg-10 col-sm-12 col-sm-clear">
                    <div class="row">
                        <a class="review__user-name col-12 col-clear"
                           href="{{ $review->user->url }}">
                            {{ $review->user->fullname }}
                            {{ $review->id }}
                        </a>
                        <div class="review__date col-12 col-clear">
                            {{ $review->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>

                <div class="review-title col-12 col-clear">
                    {{ $review->header }}
                </div>

                <div class="review-text col-12 col-clear"
                     data-status="close">
                    {!! $review->text !!}
                    <div class="review-text__block"></div>
                    <button class="review-text__more">
                        читать далее
                    </button>
                </div>


                <div class="review-grade col-12 col-clear">
                    @auth
                        @unless($review->isAuthor(Auth::user()) || $review->isForBookOfUser(Auth::user()))
                            <button class="review-grade__button"
                                    style="opacity : {{ optional($review->usersEstimate(Auth::user()))->estimate == -1 ? '0.3' : '1' }};
                                            cursor: {{ optional($review->usersEstimate(Auth::user()))->estimate == -1 ? 'auto' : 'pointer' }};"
                                    name="estimateButton"
                                    data-book-id="{{ $review->book_id }}"
                                    data-review-id="{{ $review->id }}"
                                    data-type="negative">
                                &minus;
                            </button>
                        @endunless
                    @endauth

                    <div class="review-grade__value @guest review-grade__value_guest @endguest"
                         data-review-id="{{ $review->id }}"
                         data-type="counter">
                        @guest
                            Оценка:
                        @endguest
                        {{ $review->estimate }}
                    </div>
                    @auth
                        @unless($review->isAuthor(Auth::user()) || $review->isForBookOfUser(Auth::user()))
                            <button class="review-grade__button"
                                    style="opacity : {{ optional($review->usersEstimate(Auth::user()))->estimate == 1 ? '0.3' : '1' }};
                                           cursor: {{ optional($review->usersEstimate(Auth::user()))->estimate == 1 ? 'auto' : 'pointer' }};"
                                    name="estimateButton"
                                    data-book-id="{{ $review->book_id }}" data-review-id="{{ $review->id }}"
                                    data-type="positive">
                                &plus;
                            </button>
                        @endunless
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>


