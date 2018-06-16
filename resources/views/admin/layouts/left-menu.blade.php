<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
     data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
        <li class="m-menu__item {{ Request::is('dashboard') ? 'm-menu__item--active' : '' }}" aria-haspopup="true" >
            <a href="{{ route('dashboard') }}" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-line-graph"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Dashboard
                        </span>
                    </span>
                </span>
            </a>
        </li>
        <li class="m-menu__section">
            <h4 class="m-menu__section-text">
                Инструменты
            </h4>
            <i class="m-menu__section-icon flaticon-more-v3"></i>
        </li>
        <li class="m-menu__item " aria-haspopup="true" >
            <a href="{{ route('logs') }}" class="m-menu__link" target="_blank">
                <i class="m-menu__link-icon flaticon-statistics"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Просмотр логов
                        </span>
                    </span>
                </span>
            </a>
        </li>
        <li class="m-menu__item " aria-haspopup="true" >
            <a href="{{ url('horizon') }}" class="m-menu__link" target="_blank">
                <i class="m-menu__link-icon flaticon-tabs"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Менеджер очередей
                        </span>
                    </span>
                </span>
            </a>
        </li>
        <li class="m-menu__item {{ Request::is('users') ? 'm-menu__item--active' : '' }}" aria-haspopup="true" >
            <a href="{{ route('users') }}" class="m-menu__link">
                <i class="m-menu__link-icon flaticon-users"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Пользователи
                        </span>
                    </span>
                </span>
            </a>
        </li>
        <li class="m-menu__item {{ Request::is('books') ? 'm-menu__item--active' : '' }}" aria-haspopup="true" >
            <a href="{{ route('books') }}" class="m-menu__link">
                <i class="m-menu__link-icon flaticon-book"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Книги
                        </span>
                    </span>
                </span>
            </a>
        </li>
        <li class="m-menu__item {{ Request::is('reviews') ? 'm-menu__item--active' : '' }}" aria-haspopup="true" >
            <a href="{{ route('reviews') }}" class="m-menu__link">
                <i class="m-menu__link-icon flaticon-file"></i>
                <span class="m-menu__link-title">
                    <span class="m-menu__link-wrap">
                        <span class="m-menu__link-text">
                            Рецензии
                        </span>
                    </span>
                </span>
            </a>
        </li>
    </ul>
</div>