@php
    /** @var \App\Book $user */
@endphp

<div class="panel panel-default">
    <div class='panel-body'>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="users-list-avatar" style='background-image: url("{{ $user->avatar_url }}")'></div>
                </div>
            </div>
        </div>
        <div class="col-md-9 users-list-info">
            <div class="users-list-name">{{ $user->full_name }}</div>
            <div class="users-list-nickname">
                @if($user->full_name != $user->nickname)
                    {{ $user->nickname }}
                @else
                    &nbsp;
                @endif
            </div>
            <div class="users-list-button">
                <a type="button" class="btn btn-default" href="{{ route('user.show', ['id' => $user->id]) }}">
                    К профилю пользователя
                </a>
            </div>
        </div>
    </div>
</div>
