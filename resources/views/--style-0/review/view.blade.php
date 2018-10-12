@php
    /** @var \App\Models\Review $review */
@endphp

<div class="panel panel-default">
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-3">
                <img src="{{ $review->user->avatar_url }}" class="review-avatar__image img-rounded" alt="{{ $review->user->full_name }}">
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-10">
                        <a href="{{ $review->user->url }}">
                            {{ $review->user->full_name }}
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ t('review', 'Оценка: :estimate', ['estimate' => $review->rating]) }}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {!! $review->text !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right rating-icons">
                        @auth
                            @unless($review->isAuthor(Auth::user()) || $review->isForBookOfUser(Auth::user()))
                                <span name="estimateButton"
                                      class="icon icon_down"
                                      style="opacity : {{ optional($review->usersEstimate(Auth::user()))->estimate == -1 ? '0.15' : '0.4' }};
                                             cursor: {{ optional($review->usersEstimate(Auth::user()))->estimate == -1 ? 'auto' : 'pointer' }};"
                                      data-book-id="{{ $review->book_id }}" data-review-id="{{ $review->id }}"
                                      data-type="negative"></span>
                            @endunless
                        @endauth
                        <span class="rating"
                              data-review-id="{{ $review->id }}"
                              data-type="counter">
                            {{ $review->estimate }}
                        </span>
                        @auth
                            @unless($review->isAuthor(Auth::user()) || $review->isForBookOfUser(Auth::user()))
                                <span name="estimateButton"
                                      class="icon icon_up "
                                      style="opacity : {{ optional($review->usersEstimate(Auth::user()))->estimate == 1 ? '0.15' : '0.4' }};
                                             cursor: {{ optional($review->usersEstimate(Auth::user()))->estimate == 1 ? 'auto' : 'pointer' }};"
                                      data-book-id="{{ $review->book_id }}" data-review-id="{{ $review->id }}"
                                      data-type="positive"></span>
                            @endunless
                        @endauth
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{ $review->created_at->format('Y-m-d H:i')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        @auth
                            @if(Auth::user()->hasReview($review))
                                <button class="btn btn-default" data-toggle="modal" data-target="#deleteReviewModal{{ $review->id }}">
                                    {{ t('review.button', 'Удалить') }}
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            @auth
                @if(Auth::user()->hasReview($review))
                    <div class="modal fade" id="deleteReviewModal{{ $review->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="deleteReviewModalLabel">
                                        {{ t('review.modal', 'Подтвердите удаление') }}
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <p> {!! t('review.modal', 'Вы уверены, что хотите удалить рецензию к книге <strong>:book</strong>?', ['book' => $book->title]) !!}</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-default" data-dismiss="modal">
                                        {{ t('review.button', 'Закрыть') }}
                                    </button>
                                    <a type="button" class="btn btn-danger"
                                       href="{{ route('review.delete', ['book_id' => $book->slug, 'id' => $review->id]) }}">
                                        {{ t('review.button', 'Удалить') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

        </div>
    </div>
</div>