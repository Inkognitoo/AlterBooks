@php
    /** @var \App\Review $review*/
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
                                {{ $review->text }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{ $review->created_at -> format('Y-m-d H:i')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>