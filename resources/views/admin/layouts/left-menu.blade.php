<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
     data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
        <li class="m-menu__item  m-menu__item{{ Request::is('dashboard') ? '--active' : '' }}" aria-haspopup="true" >
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
            <a href="{{ route('dashboard') }}" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-file-1"></i>
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
            <a href="{{ route('dashboard') }}" class="m-menu__link ">
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
    </ul>
</div>