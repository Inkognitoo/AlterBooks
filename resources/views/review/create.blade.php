@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
    /** @var int $book_id */
@endphp

<div class="review-new"
     data-status="open"
     id="review-create">
    <div class="review-new-create"
         data-status="open">
        <div class="review-new-create__text">
            у&nbsp;Вас еще нет рецензии на&nbsp;эту&nbsp;книгу
        </div>
        <button class="review-new-create__button button">
            написать рецензию
        </button>
    </div>
    <form class="review-new-form"
          onsubmit="return false"
          data-status="close">

        {{ csrf_field() }}

        <div class="review-new-form-rating">
            <div class="review-new-form-rating__text">
                Оценка
            </div>
            <div class="review-new-stars"
                 id="create-rating"
                 data-has-error="false">

                @for ($i = 10; $i >= 1; $i--)
                    <input class="review-new-stars__element"
                           type="radio"
                           id="create-nr-{{ $i }}"
                           name="create-rating"
                           value="{{ $i }}"
                           {{ $i === 1 ? 'checked' : ''}}>
                    <label class="review-new-stars__star"
                           for="create-nr-{{ $i }}">
                        <svg class="review-rating__star review-rating__star_create review-rating__star_active">
                            <polygon id="star" points="11,0 14.23,6.55 21.46,7.6 16.23,12.7 17.47,19.9 11,16.5 4.53,19.9 6.77,12.7 0.54,7.6 7.77,6.55"></polygon>
                        </svg>
                    </label>
                @endfor

                <div class="review-new-form-rating__number"></div>
            </div>
            <div class="review-new-form__error"></div>
        </div>
        <div class="review-new-form__field">
            <input type="text"
                   id="create-header"
                   name="header"
                   maxlength="67"
                   placeholder="Заголовок рецензии"
                   data-input-type="nr-field"
                   data-has-error="false">
            <div class="review-new-form__message">
                осталось символов: <span id="review-new-form__remain">67</span>
            </div>
            <div class="review-new-form__error"></div>
        </div>
        <div class="review-new-form__field">
            <textarea id="create-content"
                      name="text"
                      placeholder="Текст рецензии"
                      data-input-type="nr-field"
                      data-has-error="false"></textarea>
            <div class="review-new-form__error"></div>
        </div>
        <input class="review-new-form__button button"
               type="submit"
               id="review-create-button"
               value="оставить рецензию"
               data-book-id="{{ $book_id }}">
    </form>
</div>