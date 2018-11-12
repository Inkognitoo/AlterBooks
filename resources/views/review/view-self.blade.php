@php
    /** @var \App\Models\Review $review */
    /** @var int $book_id */

    $rating = 1;
    $header = '';
    $text = '';
    $created_at = \Carbon\Carbon::now();
    $estimate = 0;

    if ($review !== null) {
        $rating = $review->rating;
        $header = $review->header;
        $text = $review->text;
        $created_at = $review->created_at;
        $book_id = $review->book_id;
        $estimate = $review->estimate;
    }
@endphp


<div class="review-element"
     @if($review === null)
        data-status="close"
     @else
        data-status="open"
     @endif
     data-auth="true"
     id="review-self">

    <div class="review-rating"
         id="review-rating"
         data-rating ="{{ $rating }}">
        <div class="review-rating__header"
             id="rating-header">
            {{ number_format($rating, 1) }}
        </div>
        <div class="review-rating-stars">
            @for ($i = 1; $i <= $rating; $i++)
                <svg class="review-rating__star review-rating__star_active"
                     data-number="{{ $i }}">
                    <polygon points="11,0 14.23,6.55  21.46,7.6  16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                </svg>
            @endfor

            @for ($i = $rating + 1; $i <= 10; $i++)
                <svg class="review-rating__star"
                     data-number="{{ $i }}">
                    <polygon points="11,0 14.23,6.55  21.46,7.6  16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                </svg>
            @endfor
        </div>
    </div>

    <div class="review-element__body row row-center">
        <div class="review__main col-12">
            <div class="row">
                <div class="review__info-box col-12">
                    <div class="review__date">
                        {{ $created_at->format('d.m.Y') }}
                    </div>
                    <div class="review__icon review__icon_edit"
                         id="review-edit"></div>

                    <a class="review__icon review__icon_delete"
                       id="review-delete"
                       data-book-id="{{ $book_id }}"></a>

                    <button class="review__button button"
                            id="review-restore"
                            data-book-id="">
                        восстановить
                    </button>
                </div>
                <div class="review-title col-12 col-clear">
                    <span id="review-header-content">{{ $header }}</span>
                    <div class="review-title__shield"
                         id="review-shield"></div>
                </div>
                <div class="review-text col-12 col-clear"
                     data-status="close"
                     id="review-text">
                    <span id="review-text-content">{!! $text !!}</span>
                    <div class="review-text__block" id="review-text-block"></div>
                    <button class="review-text__more" id="review-text-more">
                        читать далее
                    </button>
                </div>
                <div class="review-grade col-12 col-clear">
                    <button class="review-grade__button" disabled>
                        &minus;
                    </button>
                    <div class="review-grade__value">
                        {{ $estimate }}
                    </div>
                    <button class="review-grade__button" disabled>
                        &plus;
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr class="review-element__hr">
</div>

<!-- Создание рецензии -->
@if ($review === null)
    @include ('review.create', ['book_id' => $book_id])
@endif

<!-- Редактирование и рецензии -->
<div class="review-new"
     data-status="close"
     id="review-edit-module">
    <form class="review-new-form"
          onsubmit="return false"
          data-status="open">

        {{ csrf_field() }}

        <div class="review-new-form-rating">
            <div class="review-new-form-rating__text">
                Оценка
            </div>
            <div class="review-new-stars"
                 id="er-rating"
                 data-has-error="false">

                @for ($i = 10; $i >= 1; $i--)
                    <input class="review-new-stars__element"
                           type="radio"
                           id="er-{{ $i }}"
                           name="rating"
                           value="{{ $i }}"
                           {{ $i === $rating ? 'checked' : ''}}>
                    <label class="review-new-stars__star"
                           for="er-{{ $i }}">
                        <svg class="review-rating__star review-rating__star_active">
                            <polygon id="star"
                                     points="11,0 14.23,6.55 21.46,7.6 16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                        </svg>
                    </label>
                @endfor

                <div class="review-new-form-rating__number"></div>
            </div>
            <div class="review-new-form__error"></div>
        </div>

        <div class="review-new-form__field">
            <input type="text"
                   id="er-header"
                   name="header"
                   maxlength="67"
                   placeholder="Заголовок рецензии"
                   data-input-type="nr-field"
                   data-has-error="false"
                   value="{{ $header }}">
            <div class="review-new-form__message">
                осталось символов: <span id="review-edit-form__remain">67</span>
            </div>
            <div class="review-new-form__error">
                укажите заголовок рецензии
            </div>
        </div>

        <div class="review-new-form__field">
            <textarea id="er-content"
                      name="text"
                      placeholder="Текст рецензии"
                      data-input-type="er-field"
                      data-has-error="false">{!! $text !!}</textarea>
            <div class="review-new-form__error">
                напишите текст рецензии
            </div>
        </div>
        <input class="review-new-form__button button"
               type="submit"
               id="review-edit-save"
               data-book-id="{{ $book_id }}"
               value="сохранить">
    </form>
</div>
