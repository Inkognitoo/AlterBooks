@php
    /** @var \App\Review $review*/
@endphp

<div class="panel panel-default">
    <div class='panel-body'>
        <div class="row">
            <div class="col-md-4">
                <img src="{{ $review->user->avatar_url }}" alt="avatar">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        Оценка: {{ $review->rating }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{ $review->text }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>