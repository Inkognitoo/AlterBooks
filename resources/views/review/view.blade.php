@php
    /** @var \App\Review $review */
@endphp

<div class="panel panel-default">
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-3">
                <img src="{{ $review->user->avatar_url }}" class="review-avatar__image img-rounded" alt="{{ $review->user->full_name }}">
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('user.show', ['id' => $review->user->id]) }}">
                            {{ $review->user->full_name }}
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Оценка: {{ $review->rating }}
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
                    <div class="col-md-12 text-right">
                        {{ $review->created_at -> format('Y-m-d H:i')}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        @auth
                            @if(Auth::user()->id == $review->user_id)
                                <button class="btn btn-default" data-toggle="modal" data-target="#deleteReviewModal">Удалить</button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteReviewModal" tabindex="-1" role="dialog"
                 aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="deleteReviewModalLabel">Подтвердите удаление</h4>
                        </div>
                        <div class="modal-body">
                            <p>Вы уверены, что хотите удалить рецензию к книге <strong>{{ $book->title }}</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            <a type="button" class="btn btn-danger"
                               href="{{ route('review.delete', ['book_id' => $book->id, 'id' => $review->id]) }}">
                                Удалить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>