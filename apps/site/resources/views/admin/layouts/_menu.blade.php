<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="fal fa-arrow-left"></i>
        </a>
        {{ __('Menu Chính') }}
        <a href="#" class="sidebar-mobile-expand">
            <i class="fal fa-expand"></i>
            <i class="fal fa-compress"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">
                        {{ __('Menu') }}
                        <a href="{{ route('admin.dashboard') }}"
                            class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block menu-nav">
                            <i class="fal fa-bars"></i>
                        </a>
                    </div>
                    <i class="fal fa-bars navbar-nav-link sidebar-control sidebar-main-toggle"
                        title="{{ __('Menu') }}"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fal fa-home"></i>
                        <span>
                            {{ __('Trang chủ') }}
                        </span>
                    </a>
                </li>
                @can('pages.view')
                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}"
                        class="nav-link @if(request()->routeIs('admin.pages*'))active @endif">
                        <i class="fal fa-file"></i>
                        <span>
                            {{ __("Trang") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('categories.view')
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link @if(request()->routeIs('admin.categories*'))active @endif">
                        <i class="fal fa-folder-tree"></i>
                        <span>
                            {{ __("Thể loại") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('types.view')
                <li class="nav-item">
                    <a href="{{ route('admin.types.index') }}"
                        class="nav-link @if(request()->routeIs('admin.types*'))active @endif">
                        <i class="fal fa-tags"></i>
                        <span>
                            {{ __("Loại truyện") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('stories.view')
                <li class="nav-item">
                    <a href="{{ route('admin.stories.index') }}"
                        class="nav-link @if(request()->routeIs('admin.stories*'))active @endif">
                        <i class="fal fa-book-open"></i>
                        <span>
                            {{ __("Truyện") }}
                        </span>
                    </a>
                </li>
                @endcan

                @canany(['game.view','game.list', 'win.view','win.list'])
                <li
                    class="nav-item nav-item-submenu {{ request()->routeIs('admin.game*') || request()->routeIs('admin.win*') ? 'nav-item-expanded nav-item-open' : null }}">
                    <a href="#" class="nav-link"><i class="fal fa-wallet"></i> <span>{{ __('Trò chơi') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Trò chơi') }}">
                        @can('game.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.game.index') }}"
                                class="nav-link @if(request()->routeIs('admin.game.index'))active @endif">
                                <span>{{ __('Người chơi hôm nay') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('win.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.win.index') }}"
                                class="nav-link @if(request()->routeIs('admin.win.index'))active @endif">
                                <span>{{ __('Người trúng thưởng hôm nay') }}</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">{{ __('Chung') }}</div>
                    <i class="fal fa-horizontal-rule" title="{{ __('Chung') }}"></i>
                </li>
                @can('posts.view')
                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}"
                        class="nav-link {{ request()->routeIs('admin.posts*') ? 'active' : null }}">
                        <i class="fal fa-edit"></i>
                        <span>
                            {{ __("Bài viết") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('banners.view')
                <li class="nav-item">
                    <a href="{{ route('admin.banners.index') }}"
                        class="nav-link @if(request()->routeIs('admin.banners*'))active @endif">
                        <i class="fal fa-image"></i>
                        <span> {{ __("Banner") }} </span>
                    </a>
                </li>
                @endcan

                @can('users.view')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link @if(request()->routeIs('admin.users*'))active @endif">
                        <i class="fal fa-users"></i>
                        <span>{{ __('Người dùng') }}</span>
                    </a>
                </li>
                @endcan

                @canany(['wallets.view', 'wallet-transaction.view'])
                <li
                    class="nav-item nav-item-submenu {{ request()->routeIs('admin.wallets*') || request()->routeIs('admin.wallet-transaction*') ? 'nav-item-expanded nav-item-open' : null }}">
                    <a href="#" class="nav-link"><i class="fal fa-wallet"></i> <span>{{ __('Ví') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Ví') }}">
                        @can('wallets.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.wallets.index') }}"
                                class="nav-link @if(request()->routeIs('admin.wallets*'))active @endif">
                                <span>{{ __('Ví') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('wallet-transaction.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.wallet-transaction.index') }}"
                                class="nav-link @if(request()->routeIs('admin.wallet-transaction*'))active @endif">
                                <span>{{ __('Giao dịch ví') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @canany(['recharge_transactions.view'])
                <li class="nav-item">
                    <a href="{{ route('admin.recharge_transactions.index') }}" class="nav-link"><i
                            class="fal fa-money-check-alt"></i> <span>{{ __('Nạp tiền') }}</span></a>
                </li>
                @endcan

                @can('withdraw.view')
                <li class="nav-item">
                    <a href="{{ route('admin.withdraw.index') }}"
                        class="nav-link @if(request()->routeIs('admin.withdraw.index*'))active @endif">
                        <i class="fal fa-money-bill-wave-alt"></i> <span>{{ __('Rút tiền') }}</span>
                    </a>
                </li>
                @endcan

                @can('order.view')
                <li class="nav-item">
                    <a href="{{ route('admin.order.index') }}"
                        class="nav-link @if(request()->routeIs('admin.order*'))active @endif">
                        <i class="fal fa-list"></i>
                        <span>
                            {{ __("Đơn mua chương VIP") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('withdraw.view')
                <li class="nav-item">
                    <a href="{{ route('admin.gold-gift.index') }}"
                        class="nav-link @if(request()->routeIs('admin.gold-gift*'))active @endif">
                        <i class="fal fa-gift"></i>
                        <span>
                            {{ __("Giao dịch tặng vàng") }}
                        </span>
                    </a>
                </li>
                @endcan

                @can('log_search.view')
                <li class="nav-item">
                    <a href="{{ route('admin.log_search.index') }}"
                        class="nav-link @if(request()->routeIs('admin.log_search*'))active @endif">
                        <i class="fal fa-history"></i>
                        <span>
                            {{ __("Lịch sử tìm kiếm") }}
                        </span>
                    </a>
                </li>
                @endcan

                <!-- System -->
                @canany(['admins.view', 'log-activities.index', 'admins.view', 'roles.view'])
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">{{ __('Hệ thống') }}</div>
                    <i class="fal fa-horizontal-rule" title="{{ __('Hệ thống') }}"></i>
                </li>
                @endcan
                @canany(['admins.view', 'roles.view'])
                <li
                    class="nav-item nav-item-submenu {{ request()->routeIs('admin.admins*') || request()->routeIs('admin.roles*') ? 'nav-item-expanded nav-item-open' : null }}">
                    <a href="#" class="nav-link"><i class="fal fa-user"></i> <span>{{ __('Tài khoản') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Tài khoản') }}">
                        @can('admins.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.admins.index') }}"
                                class="nav-link @if(request()->routeIs('admin.admins*'))active @endif">{{ __('Tài khoản') }}</a>
                        </li>
                        @endcan
                        @can('roles.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}"
                                class="nav-link @if(request()->routeIs('admin.roles*'))active @endif">{{ __('Vai trò') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @canany(['admins.view', 'log-activities.index'])
                <li
                    class="nav-item nav-item-submenu {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.log-activities*') ? 'nav-item-expanded nav-item-open' : null }}">
                    <a href="#" class="nav-link"><i class="fal fa-solar-system"></i>
                        <span>{{ __('Hệ Thống') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Hệ Thống') }}">
                        @can('admins.view')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.edit') }}"
                                class="nav-link @if(request()->routeIs('admin.settings*'))active @endif">
                                <span>
                                    {{ __('Cài đặt chung') }}
                                </span>
                            </a>
                        </li>
                        @endcan

                        @can('log-activities.index')
                        <li class="nav-item">
                            <a href="{{ route('admin.log-activities.index') }}"
                                class="nav-link @if(request()->routeIs('admin.log-activities*'))active @endif">
                                <span>
                                    {{ __("Lịch sử thao tác") }}
                                </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
        <!-- /main navigation -->
    </div>
    <!-- /sidebar content -->
</div>