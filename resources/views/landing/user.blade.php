<div class="landing-user">
    <div class="landing-user-avatar">
        <img src="{{ $user->avatar_url }}" class="landing-user-avatar__image">
    </div>
    <div class="landing-user__name">
        {{ $user->full_name }}
    </div>
    <div class="landing-user__profile">
        <div class="landing-user__profile landing-form">
            <a type="button" class="landing-button" href="user/id{{ $user->id }}">
                Профиль
            </a>
        </div>
    </div>
</div>