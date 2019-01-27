<div class="landing-user">
    <div class="landing-user__avatar" style="background-image: url({{ $user->avatar_url }});"></div>
    <div class="landing-user__name">
        {{ $user->full_name }}
    </div>
    <a class="landing-user__read button"
       href="{{ $user->url }}">
        {{ t('user.button', 'Профиль') }}
    </a>
</div>