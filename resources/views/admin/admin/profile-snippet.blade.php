@php
    /** @var \App\Models\User $user **/
@endphp

<a href="#" class="m-nav__link m-dropdown__toggle">
    <span class="m-topbar__userpic">
        <img src="{{ $user->avatar_url }}"
             class="m--img-rounded m--marginless m--img-centered" alt="{{ $user->full_name }}"/>
    </span>
    <span class="m-topbar__username m--hide">
        Nick
    </span>
</a>
<div class="m-dropdown__wrapper">
    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
    <div class="m-dropdown__inner">
        <div class="m-dropdown__header m--align-center"
             style="background: url({{ url('/metronic/img/profile-background.jpg') }}); background-size: cover;">
            <div class="m-card-user m-card-user--skin-dark">
                <div class="m-card-user__pic">
                    <img src="{{ $user->avatar_url }}" class="m--img-rounded m--marginless" alt="{{ $user->full_name }}"/>
                </div>
                <div class="m-card-user__details">
                    <span class="m-card-user__name m--font-weight-500">
                        {{ $user->full_name }}
                    </span>
                    <a href="" class="m-card-user__email m--font-weight-300 m-link">
                        {{ $user->email }}
                    </a>
                </div>
            </div>
        </div>
        <div class="m-dropdown__body">
            <div class="m-dropdown__content">
                <ul class="m-nav m-nav--skin-light">
                    <li class="m-nav__section m--hide">
                        <span class="m-nav__section-text">
                            Section
                        </span>
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                            <span class="m-nav__link-title">
                                <span class="m-nav__link-wrap">
                                    <span class="m-nav__link-text">
                                        Мой профиль
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__separator m-nav__separator--fit"></li>
                    <li class="m-nav__item">
                        <a href="{{ route('logout') }}"
                           class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                            Выход
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
