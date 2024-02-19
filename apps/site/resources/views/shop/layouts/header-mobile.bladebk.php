<style>
    .comment-vip-icon {
        height: 15px;
        width: 15px;
    }

    .comment-vip-icon .checkmark {
        height: 8px;
        width: 4px;
        border-bottom: 2px solid white;
        border-right: 2px solid white;
    }
</style>
<header>


    <div class="header header-mobile">
        <div class="back-gr-mobile" style="height: 100%;overflow: hidden;"><img
                src="{{ asset('frontend/images/mobile/backgroundmobile.svg') }}"
                alt="{{ setting('store_name', 'Giangthe.com') }}"></div>
        <div class="container">
            <div class="row" style="display: flex; align-items: center;">
                {{--                <div class="col-lg-3 col-xl-3 col-md-3 col-3 mobile-nav" --}}
                {{--                    style="display:none; justify-content:space-between; align-items: center; text-align: center; font-weight:500;"> --}}
                {{--                    <a class="nav-icon"><img src="frontend/images/nav-icon.png" alt=""></a> --}}
                {{--                </div> --}}
                {{--                <nav class="mobile-nav-container"> --}}
                {{--                    <div class="container"> --}}
                {{--                        <div class="modal-header"> --}}
                {{--                            <button type="button" class="btn-close nav-close" style="width:20px;"></button> --}}
                {{--                        </div> --}}
                {{--                        <form class="search" action="{{ route('embedChapter') }}" style="margin-top: 8px;"> --}}
                {{--                            <input type="text" required name="search" placeholder="Nhúng link/ Tìm truyện"> --}}
                {{--                            <button type="submit" class="btn-search"><i --}}
                {{--                                    style="color: gray; font-size: 20px;"class="fa fa-paper-plane"></i></button> --}}
                {{--                        </form> --}}

                {{--                        <ul> --}}
                {{--                            <li><a style="color: gray;" href="{{ route('home') }}">Trang Chủ</a> </li> --}}
                {{--                            <li><a style="color: gray;" href="{{ route('embedChapter') }}">Tìm truyện</a> </li> --}}
                {{--                            <li><a style="color: gray;" href="{{ route('tuluyen.index') }}">Tu luyện</a> </li> --}}
                {{--                            --}}{{-- <li><a style="color: gray;" href="" data-bs-toggle="modal" data-bs-target="#vip" id="up-vip-but">Nâng VIP </a> </li> --}}
                {{--                            <li><a style="color: gray;" href="{{ route('user.game') }}">Trò chơi</a> </li> --}}
                {{--                            <li> <a style="color: gray;" href="{{ route('post.index') }}">Hướng dẫn</a></li> --}}
                {{--                        </ul> --}}
                {{--                    </div> --}}
                {{--                </nav> --}}
                <div class="col-lg-2 col-xl-2 col-md-2 col-4 logo-header">
                    <div class="div-logo">
                        @if (setting('store_logo'))
                            <a href="{{ route('home') }}">
                                {{--                                <img src="{{ \Storage::url(setting('store_logo')) }}" --}}
                                {{--                                    alt="" class="logo"> --}}
                                <img src="{{ asset('frontend/images/mobile/logo-header-mobile.svg') }}" alt=""
                                    class="logo">
                            </a>
                        @else
                            <a href="{{ route('home') }}">
                                {{--                                <img src="{{ \Storage::url(setting('store_logo')) }}" --}}
                                {{--                                    alt="" class="logo"> --}}
                                <img src="{{ asset('frontend/images/mobile/logo-header-mobile.svg') }}" alt=""
                                    class="logo">
                            </a>
                            {{--                            <a href="{{ route('home') }}"><img src="frontend/images/new-logo.jpg" alt="" --}}
                            {{--                                    class="logo"></a> --}}
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-xl-4 col-md-4 col-4 main-nav"
                    style="display:flex; justify-content:space-between; align-items: center; text-align: center; font-weight:500;">
                    <a style="color: gray;" href="{{ route('embedChapter') }}">Tìm truyện</a>
                    {{-- <a style="color: gray;" href="{{ route('tuluyen.index') }}">Tu luyện</a> --}}

                    {{-- <a style="color: gray;" href="" data-bs-toggle="modal" data-bs-target="#vip">Nâng VIP </a> --}}
                    <a style="color: gray;" href="{{ route('user.game') }}">Trò chơi</a>
                    <a style="color: gray;" href="{{ route('post.index') }}">Hướng dẫn</a>
                </div>
                <div class="col-lg-4 col-xl-4 col-md-4 col-12 main-nav">
                    <form class="search" action="{{ route('embedChapter') }}">
                        <input type="text" required name="search" placeholder="Nhúng link/ Tìm truyện">
                        <button type="submit" class="btn-search"><i
                                style="color: gray; font-size: 20px;"class="fa fa-paper-plane"></i></button>
                    </form>
                </div>
                <!-- Đã đăng nhập -->
                @auth('web')

                    <div class="col-lg-2 col-xl-2 col-md-5 col-3 dropdown">
                        <div class="f-right align-items-center">
                            @php
                                $time = new \Carbon\Carbon('2018-01-01 00:00:00');
                                $countActivity = \DB::connection('dbuser')
                                    ->table('notifications')
                                    ->where('notifiable_id', currentUser()->id)
                                    ->where('read_at', $time)
                                    ->where('type', '!=', 'comment')
                                    ->orderBy('updated_at', 'desc')
                                    ->count();
                                $countComment = \DB::connection('dbuser')
                                    ->table('notifications')
                                    ->where('notifiable_id', currentUser()->id)
                                    ->where('read_at', $time)
                                    ->where('type', 'comment')
                                    ->orderBy('updated_at', 'desc')
                                    ->count();
                            @endphp
                            <button class="btn search-mobile position-relative mx-1" data-bs-toggle="modal"
                                data-bs-target="#search" onclick="$('.notisss').remove()">
                                <img src="{{ asset('frontend/images/mobile/Search.svg') }}" alt="">
                            </button>
                            <button class="btn notification position-relative mx-1" data-bs-toggle="modal"
                                data-bs-target="#noti"
                                onclick="$.get({url:'./readAllNoti',success:function(){ $('.notiss').remove() }})">
                                <img src="{{ asset('frontend/images/mobile/Notification.svg') }}" alt="">
                                @if ($countActivity)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill notiss">{{ $countActivity > 99 ? '99+' : $countActivity }}</span>
                                @endif
                            </button>

                            {{-- <i class="fa-regular fa-comment" style="color: gray; font-size: 15px;"></i> --}}

                            <button class="btn notification position-relative mx-1" data-bs-toggle="modal"
                                data-bs-target="#comment"
                                onclick="$.get({url:'./readAllComment',success:function(){ $('.notisss').remove() }})">
                                <img src="{{ asset('frontend/images/mobile/Comment-minus.svg') }}" alt="">
                                @if ($countComment)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill  notisss">{{ $countComment > 99 ? '99+' : $countComment }}</span>
                                @endif
                            </button>
                            {{--                            <button class="btn flag position-relative" data-bs-toggle="modal" --}}
                            {{--                                    data-bs-target="#pk" --}}
                            {{--                                    onclick="$.get({url:'./readAllPk',success:function(){ $('.notisss').remove() }})"> --}}
                            {{--                                <img src="{{ asset('frontend/images/mobile/Flag.svg') }}" alt=""> --}}
                            {{--                                @if ($countComment) --}}
                            {{--                                    <span --}}
                            {{--                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill  notisss">0</span> --}}
                            {{--                                @endif --}}
                            {{--                            </button> --}}
                            @auth('web')
                                {{-- @dd($players) --}}
                                @php
                                    if (!isset($players_user)) {
                                        $players_user = auth()
                                            ->guard('web')
                                            ->user();
                                    }
                                    if (!isset($players)) {
                                        $tuluyen = new App\Traits\TuLuyenTraits($players_user);

                                        $players = $tuluyen->get_details();
                                        // if(!$players){
                                        //     $players = false;
                                        // }
                                        # code...
                                    }
                                    // $players_user = auth()->guard('web')->user();
                                @endphp
                                {{-- @dd($players_user) --}}


                                @if (isset($players) && !empty($players))
                                    @if (!$players['is_ban'])
                                        @livewire('layouts.header', [
                                            'user_online' => $players_user->id,
                                            'chien_bao' => @$players['chien_bao'],
                                            'last_request' => @$players['last_online'],
                                            'has_player' => 1,
                                        ])
                                    @endif
                                @else
                                    @livewire('layouts.header', [
                                        'has_player' => 0,
                                    ])
                                @endif
                            @endauth

                            <img src="{{ currentUser()->avatar ? pare_url_file(currentUser()->avatar, 'user') : 'frontend/images/no-user.png' }}"
                                alt="" class="img-user dropdown-toggle" id="user-info" data-bs-toggle="dropdown">
                            <ul class="dropdown-menu user-info">
                                <span>{{ currentUser()->name }}</span>
                                <hr>
                                <li>
                                    <a href="{{ route('user.index', currentUser()->id) }}">Thông tin cá nhân</a>
                                </li>
                                <li>
                                    <a href="{{ route('tuluyen.index') }}">Tu luyện</a>
                                </li>
                                <li>
                                    <a href="{{ route('pk.index') }}">PK</a>
                                    {{-- <a onclick ="goToPk()">PK</a> --}}
                                </li>
                                <li>
                                    <a href="{{ route('user.recharge') }}">Ví của tôi</a>
                                </li>
                                <!-- <li>
                                                                        <a href="{{-- route('card.top') --}}" class="text-danger">TOP nạp thẻ</a>
                                                                    </li> -->
                                {{--                                <li> --}}
                                {{--                                    <a href="{{ route('user.recharge') }}">Nạp tiền</a> --}}
                                {{--                                </li> --}}
                                <li>
                                    <a href="{{ route('user.gold-gift') }}">Tặng vàng</a>
                                </li>
                                @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                                    <li>
                                        <a href="{{ route('user.game') }}" style="color: #2cb15b">Trò chơi</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('stories.index') }}">Đăng truyện</a>
                                </li>

                                @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                                    <li>
                                        <a href="{{ route('user.order.index') }}">Truyện đã mua</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('book.nhung', currentUser()->id) }}">Truyện đã nhúng</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('book.following') }}">Truyện đang theo dõi</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('book.readed') }}">Truyện đã đọc</a>
                                    </li>
                                @endif

                                @if ((new \Jenssegers\Agent\Agent())->isMobile())
                                    <li>
                                        <a href="{{ route('book.mobile') }}">Tủ sách</a>
                                    </li>
                                @endif


                                <hr>
                                @if (currentUser()->is_vip == \App\Enums\UserType::Mod)
                                    <li>
                                        <a href="{{ route('book.nhungs') }}">Đăng chương vip</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('book.donate') }}">DONATE</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.order.statistic') }}">Thống kê mua VIP</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.withdraw') }}">Rút tiền</a>
                                    </li>
                                @endif
                                <hr>
                                <li>
                                    <a href="javascript:void(0)" onclick="nhapgift()">Nhập gift code</a>
                                </li>
                                <hr>
                                <li>
                                    <a href="javascript:void(0)" style="color: #DD0000"
                                        onclick="event.preventDefault(); $('#logout-form').submit();">Đăng xuất</a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="col-lg-4 col-xl-4 col-md-4 col-4 logins">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#login"
                            class="header-login text-white">Đăng nhập</a>
                        <!-- <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#register" class="text-success" >Đăng ký</a> -->
                    </div>
                @endauth
            </div>
            @auth('web')
                @php
                    $activity = \DB::connection('dbuser')
                        ->table('notifications')
                        ->where('notifiable_id', currentUser()->id)
                        ->where('type', '!=', 'comment')
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $comment = \DB::connection('dbuser')
                        ->table('notifications')
                        ->where('notifiable_id', currentUser()->id)
                        ->where('type', 'comment')
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
            @endauth
            <!-- Modal Notification -->
            @auth('web')
                <div class="modal fade" id="noti" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 14px;top:75px">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Thông báo</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                                <ul>
                                    @if (count($activity))
                                        @foreach ($activity as $list)
                                            <a href="{{ $list->link }}" class="text-secondary">
                                                <li class="border-bottom p-1 ">
                                                    <div class="d-flex justify-content-between">
                                                        Hệ thống
                                                        <span>{{ $list->created_at }}</span>
                                                    </div>
                                                    {{ $list->data }}
                                                </li>
                                            </a>
                                        @endforeach
                                    @else
                                        <li class="p-1 text-center">
                                            <span>Bạn không có thông báo mới</span>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            @endauth
            <!-- Modal Comment -->
            @auth('web')
                <div class="modal fade" id="comment" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 14px;top:75px">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Bình luận</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                                <ul>
                                    @if (count($comment))
                                        @foreach ($comment as $list)
                                            <a href="{{ $list->link }}" class="text-secondary">
                                                <li class="border-bottom p-1">
                                                    <div class="d-flex justify-content-between">
                                                        Hệ thống
                                                        <span>{{ $list->created_at }}</span>
                                                    </div>
                                                    {{ $list->data }}
                                                </li>
                                            </a>
                                        @endforeach
                                    @else
                                        <li class="p-1 text-center">
                                            <span>Bạn không có bình luận mới</span>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            @endauth
            {{-- modal chiến báo --}}
            @auth('web')
                <div class="modal fade" id="pk" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 12px;top:75px">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Chiến báo</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                                <ul>
                                    <li class="p-1 text-center">
                                        <span>Sẽ mở khi có hệ thống pk</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Modal Search -->
            <div class="modal fade search-modal" id="search" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 12px;top:0">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="padding-bottom: 1.5rem">
                            <form class="search" action="{{ route('embedChapter') }}">
                                <input type="text" class="rounded-pill px-3" required name="search"
                                    placeholder="Nhúng link/ Tìm truyện">
                                <button type="submit" class="btn-search">
                                    <img src="{{ asset('frontend/images/mobile/Icon-search.svg') }}" alt="">
                                </button>
                            </form>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFF"
                                    class="bi bi-x" viewBox="0 0 16 16">
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                <span class="text-white">Đóng</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <a href="{{ route('embedChapter') }}"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/icon-category.svg') }}" alt="Danh mục"
                                        style="width: fit-content">
                                    <span class="name-item">Danh mục</span>
                                </a>
                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/icon-attendance.svg') }}" alt="Điểm danh" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item active"> --}}
                                {{--                                        Điểm danh --}}
                                {{--                                    </span> --}}
                                {{--                                </a> --}}
                                @auth('web')
                                    <a href="{{ route('vip') }}"
                                        class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                        <img src="{{ asset('frontend/images/mobile/icon-upvip.svg') }}" alt="Nâng VIP"
                                            style="width: fit-content">
                                        <button class="btn search-mobile position-relative mt-2 p-0">
                                            <span class="name-item">Nâng VIP</span>
                                        </button>
                                    </a>
                                @endauth
                                <a href="{{ route('user.recharge') }}"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/recharge.svg') }}" alt="Nạp tiền"
                                        style="width: fit-content">
                                    <span class="name-item">Nạp tiền</span>
                                </a>
                                {{--                                <!-- Force next columns to break to new line --> --}}
                                {{--                                <div class="w-300"></div> --}}

                                <a href="{{ route('tuluyen.index') }}"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/practice.svg') }}" alt="Tu luyện"
                                        style="width: fit-content">
                                    <span class="name-item">Tu luyện</span>
                                </a>
                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/recharge.svg') }}" alt="XSMB" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item">XSMB</span> --}}
                                {{--                                </a> --}}
                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/layso.svg') }}" alt="Lấy số" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item">Lấy số</span> --}}
                                {{--                                </a> --}}
                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/tambao.svg') }}" alt="Tầm bảo" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item">Tầm bảo</span> --}}
                                {{--                                </a> --}}

                                {{--                                <!-- Force next columns to break to new line --> --}}
                                {{--                                <div class="w-300"></div> --}}

                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/pk.svg') }}" alt="PK" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item">PK</span> --}}
                                {{--                                </a> --}}
                                {{--                                <a href="#" class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center"> --}}
                                {{--                                    <img src="{{ asset('frontend/images/mobile/store.svg') }}" alt="Cửa hàng" --}}
                                {{--                                         style="width: fit-content"> --}}
                                {{--                                    <span class="name-item">Cửa hàng</span> --}}
                                {{--                                </a> --}}
                                <a href="https://zalo.me/g/rqfhlz892" target="_blank"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/zalo.svg') }}" alt="Chatbox"
                                        style="width: fit-content">
                                    <span class="name-item">Chatbox</span>
                                </a>
                                <a href="{{ route('post.index') }}"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/guide.svg') }}" alt="Hướng dẫn"
                                        style="width: fit-content">
                                    <span class="name-item">Hướng dẫn</span>
                                </a>
                                <a href="{{ route('pk.packTuLuyen') }}"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/50.png') }}" alt=""
                                        style="width: 29px;">
                                    <span class="name-item mt-2">Gói ưu đãi</span>
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#tim-kiem"
                                    class="mb-3 col-3 col-sm-3 d-flex flex-column align-items-center">
                                    <img src="{{ asset('frontend/images/mobile/vs.png') }}" alt=""
                                        style="width: 29px;">
                                    <span class="name-item mt-2">Tìm đối thủ</span>
                                </a>



                            </div>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="tim-kiem" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content" style="background: #596785;
            border: solid #cdcd1b 1px;">
                        <div class="modal-header"
                            style="flex-direction: column-reverse;
                border-bottom: none;">
                            <h6 class="modal-title"
                                style="    line-height: 0;
                    font-weight: 600;
                    color: #ffea1f;
                    font-size: 18px;">
                                Tìm đối thủ</h6>
                            <button
                                style="    padding: 0rem 1rem;
                    background: none;
                    color: white;
                    font-weight: 600;
                    opacity: 15;"
                                type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">X</button>
                        </div>
                        <div class="modal-body" style="color: white;">
                            <form method="post" action="" style="text-align:left">
                                @csrf
                                <p style="font-weight:500;">Chọn đối thủ theo ID</p>
                                <textarea id="id-pk" class="form-control text" style="font-size:15px" placeholder="Nhập ID đối thủ..."></textarea>

                                @auth('web')
                                @if (isset($players) && !empty($players))
                                    <p style="margin-top: 20px;font-weight:500;">Số lượt pk còn lại trong ngày:

                                        {{ $players_user->get_charaters->luotpk }}</p>
                                @endif
                                @endauth
                                <div class="modal-footer"
                                    style="border-top:none;display: flex; justify-content: center;">
                                    <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button"
                                        style="border-radius: 20px;" class="btn btn-gt gift" data-wallet=""
                                        data-pkm="0" onclick="pk(this)">PK ngẫu nhiên
                                    </button>
                                    <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button"
                                        data-pkm="1" style="border-radius: 20px;" class="btn btn-gt gift"
                                        data-wallet="" onclick="pk(this)">PK theo tên
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="in-pk" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content"
                        style="background-image: url(/asset/pk/lh.png);
             background-size: cover;
             border: solid #cdcd1b 1px;
             height: 540px;">
                        <div class="modal-header"
                            style="flex-direction: column-reverse;
                 border-bottom: none;">
                            <button
                                style="background: #eee!important;
                     width: fit-content;
                     font-weight: 600;
                     height: fit-content;
                     opacity: 15;
                     color: black;"
                                type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">Ẩn</button>
                        </div>
                        <div class="modal-body" style="color: white;">
                            <form method="post" action=""
                                style="background: #061653c9; border-radius: 5px; height: 86%;">
                                @csrf
                                <p
                                    style="font-weight: 500;
                         text-align: center;
                         padding-top: 21px; margin-bottom: 0;">
                                    Quá trình PK</p>
                                <input class="pk-radio" type="radio" id="tim-doi-thu" value="">
                                <label for="tim-doi-thu">Đang tìm đối thủ</label><br>
                                {{-- <input class="pk-radio" type="radio" id="wait-doi-thu" value="">
                                <label for="wait-doi-thu">Đợi đối thủ phản hồi</label><br> --}}
                                <input class="pk-radio" type="radio" id="chien-dau" value="">
                                <label for="chien-dau">Chiến đấu</label><br>
                                <input class="pk-radio" type="radio" id="hoan-thanh-pk" value="">
                                <label for="hoan-thanh-pk">Gửi kết quả vào chiến báo</label><br>
                                <p style="background: #ab0e0e;
                         color: #f1e9e9;
                         margin-top: 24px;
                         font-weight: bold;
                         border-radius: 5px;
                         width: 100%;
                         text-align: center;display: none"
                                    id="thong-bao-pk">
                                </p>
                            </form>
                            <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                                <button data-bs-dismiss="modal" aria-label="Close" type="button"
                                    style="border-radius: 20px; background-color: #c9412e;" class="btn btn-gt gift"
                                    data-wallet="" id="cancel-pk">Hủy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- modal search -->
            <div class="modal fade" id="tim-kiem" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="font-size: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content" style="background: #596785;
                border: solid #cdcd1b 1px;">
                    <div class="modal-header" style="flex-direction: column-reverse;
                    border-bottom: none;">
                        <h6 class="modal-title" style="    line-height: 0;
                        font-weight: 600;
                        color: #ffea1f;
                        font-size: 18px;">Tìm đối thủ</h6>
                        <button style="    padding: 0rem 1rem;
                        background: none;
                        color: white;
                        font-weight: 600;
                        opacity: 15;" type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">X</button>
                    </div>
                    <div class="modal-body" style="color: white;">
                        <form method="post" action="" style="text-align:left">
                            @csrf
                            <p style="font-weight:500;">Chọn đối thủ theo tên</p>
                            <textarea class="form-control text" style="font-size:15px" placeholder="Nhập tên đối thủ..."></textarea>
                            <p style="font-weight:500;">Số lượt pk còn lại</p>
                            <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                                <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button" style="border-radius: 20px;" class="btn btn-gt gift" data-wallet="">PK ngẫu nhiên
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button" style="border-radius: 20px;" class="btn btn-gt gift" data-wallet="">PK theo tên
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>



             <!-- modal in pk -->


             <div class="modal fade" id="in-pk" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true" style="font-size: 15px;">
             <div class="modal-dialog modal-dialog-centered modal-md">
                 <div class="modal-content" style="background-image: url(/asset/pk/lh.png);
                 background-size: cover;
                 border: solid #cdcd1b 1px;
                 height: 540px;">
                     <div class="modal-header" style="flex-direction: column-reverse;
                     border-bottom: none;">
                         <button style="background: #eee!important;
                         width: fit-content;
                         font-weight: 600;
                         height: fit-content;
                         opacity: 15;
                         color: black;" type="button" class="btn-close" data-bs-dismiss="modal"
                                 aria-label="Close">Ẩn</button>
                     </div>
                     <div class="modal-body" style="color: white;">
                         <form method="post" action="" style="background: #061653c9; border-radius: 5px; height: 86%;">
                             @csrf
                             <p style="font-weight: 500;
                             text-align: center;
                             padding-top: 21px; margin-bottom: 0;">Quá trình PK</p>
                                 <input class="pk-radio" type="radio" id="ngau-nhien" name="thuong-cao" value="">
                             <label for="ngau-nhien">Đang tìm đối thủ</label><br>
                             <input class="pk-radio" type="radio" id="theo-ten" name="thuong-cao" value="">
                             <label for="theo-ten">Đợi đối thủ phản hồi</label><br>
                             <input class="pk-radio" type="radio" id="theo-ten" name="thuong-cao" value="">
                             <label for="theo-ten">Chiến đấu</label><br>
                             <input class="pk-radio" type="radio" id="theo-ten" name="thuong-cao" value="">
                             <label for="theo-ten">Gửi kết quả vào chiến báo</label><br>
                             <p style="background: #ab0e0e;
                             color: #f1e9e9;
                             margin-top: 24px;
                             font-weight: bold;
                             border-radius: 5px;
                             width: 100%;
                             text-align: center;"> Đối thủ không dám ứng chiến!</p>
                         </form>
                         <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                            <button data-bs-dismiss="modal"
                            aria-label="Close" type="button" style="border-radius: 20px; background-color: #c9412e;" class="btn btn-gt gift" data-wallet="">Hủy
                            </button>
                        </div>
                     </div>
                 </div>
             </div>
             </div> --}}

            <!-- Modal Login -->
            <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Đăng nhập</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="login login-form" method="post" action="" id="loginform"
                                name="login">
                                @csrf
                                <p id="error" class="text-danger"></p>
                                <p>Tài khoản</p>
                                <p><input class="form-control" value="{{ Cookie::get('username') }}" name="username"
                                        placeholder="Nhập tài khoản"></p>
                                <p>Mật khẩu:</p>
                                <p><input class="form-control" name="password" value="{{ Cookie::get('password') }}"
                                        type="password" placeholder="Nhập mật khẩu"></p>
                                <p><input type="checkbox" name="remember" id="remember"><label for="remember"> Nhớ
                                        mật khẩu</label></p>
                                <p>Nếu chưa có tài khoản , đăng ký tại <a href="{{ route('register') }}"
                                        class="text-success" style="text-decoration:underline">đây</a></p>
                                <p>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" style="color:blue">
                                            {{ __('Quên mật khẩu') }}
                                        </a>
                                    @endif
                                </p>
                                <div class="modal-footer">
                                    <button type="submit" class="btn return-customer-btn">Đăng nhập</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal VIP -->
            @auth('web')
                <div class="modal fade vip-modal" id="vip" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 12px;top:75px">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-vip-head ">
                                <button type="button" class="btn-close re-btn-vip close-up-vip text-white rounded-circle"
                                    data-bs-dismiss="modal" style="float: right;">X</button>
                            </div>
                            <div class="modal-body position-relative">
                                <img src="{{ asset('frontend/images/logo-vip.png') }}" alt="Logo vip"
                                    class="position-absolute img-vip" width="146px">
                                <br>
                                <ul style="list-style-type: circle;">
                                    <li class="font-vip-modal">Không quảng cáo</li>
                                    <li class="font-vip-modal">Đọc free tất cả các chương VIP</li>
                                    <li class="font-vip-modal">Tên và bình luận nổi bật</li>
                                    <li class="font-vip-modal">Tỉ lệ nhặt hạ phẩm đan = 0%</li>
                                    <li class="font-vip-modal">Tăng 10% tu vi khi tiêu vàng</li>
                                    <li class="font-vip-modal">Có huy hiệu VIP, tích xanh</li>
                                </ul>
                                <a href="{{ route('upgrade.vip') }}" type="button"
                                    class="btn btn-success text-white btn-re-color position-absolute btn-register-vip">
                                    Đăng ký VIP ngay với 2.000 linh thạch/tháng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
            <!-- Modal Register -->
            <div class="modal fade" id="register" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Đăng ký</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="register register-form" method="post" action="{{ route('register') }}"
                                id="registerform" name="register">
                                @csrf
                                <p>Username</p>
                                <p><input class="form-control" name="name" placeholder="Nhập tài khoản"></p>
                                <p>Email</p>
                                <p><input class="form-control" name="email" placeholder="Nhập tài khoản"></p>
                                <p>Mật khẩu:</p>
                                <p><input class="form-control" name="password" type="password"
                                        placeholder="Nhập mật khẩu"></p>
                                <p>Xác nhận mật khẩu</p>
                                <input class="form-control" name="password_confirmation" type="password"
                                    placeholder="Nhập lại mật khẩu">
                                <div class="modal-footer">
                                    <button type="submit" class="btn return-customer-btn">Đăng ký</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Change Pasword -->
            <div class="modal fade" id="change-password" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <form class="change-password" method="post" action="{{ route('change.password') }}"
                            id="changepasswordform">
                            @csrf
                            <div class="modal-header">
                                <h6 class="modal-title">Thay đổi mật khẩu</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Mật khẩu cũ</p>
                                <p><input type="password" class="form-control" name="old_password"
                                        placeholder="Nhập tài khoản"></p>
                                <p>Mật khẩu mới:</p>
                                <p><input class="form-control" name="password" id="password" type="password"
                                        placeholder="Nhập mật khẩu"></p>
                                <p>Xác nhận mật khẩu mới:</p>
                                <input class="form-control" name="password_confirmation" type="password"
                                    placeholder="Nhập lại mật khẩu">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($players) && !empty($players))
        @if (!$players['is_ban'])
            {{-- modal chiến báo --}}
            {{-- <div wire:ignore.self class="modal fade" id="chienbao" tabindex="-1" aria-hidden="true"
                style="font-size: 14px;top:75px">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Chiến Báo</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                            <ul>

                                <a href="" class="text-secondary">
                                    <li class="border-bottom p-1">
                                        <div class="d-flex justify-content-between">
                                Hệ thống
                                <span>2022-12-12 00:00:00</span>
                            </div>
                                        Sẽ mở khi có hệ thống pk
                                    </li>
                                </a>



                            </ul>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div> --}}
            <div wire:ignore.self class="modal fade" id="chienbao" tabindex="-1" aria-hidden="true"
                style="font-size: 14px;top:75px">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="align">
                            <h6 class="modal-title" style="font-size: 18px;
                        color: #d91a1a;">
                                Chiến Báo</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                            <!-- load data here-->
                            <div class="row" style="border-bottom: dashed 0.1px" id="showchienbao">


                                {{-- <div class="col-12 pb-2">
                                    <p> 17:30 25/02/2023</p>
                                    <div class="row">
                                        <div class="col-9">
                                            <p
                                                style="font-size:18px; font-size: 18px;
                                            font-weight: bold;">
                                                Bạn vừa pk với tên (ID) </p>
                                            <h2 style="color: rgba(164, 164, 164, 0.901);"> Thắng</h2>
                                            <p style="font-size:18px; font-size: 18px;">Chiến tích: bạn được số điểm
                                                tích phân và 40 linh thạch</p>
                                        </div>
                                        <div class="col-3"><button type="button"
                                                style="width: 100%;
                                        height: 40px;
                                        font-weight: 600;
                                        font-size: 18px; margin-bottom: 23px;"
                                                class="btn btn-gt gift" data-wallet="">PK lại
                                            </button>
                                            <button type="button"
                                                style="width: 100%;
                                    height: 40px;
                                    font-weight: 600;
                                    font-size: 18px;"
                                                class="btn btn-gt gift" data-bs-toggle="modal"
                                                data-bs-target="#chienbao-details" data-wallet="">Chi tiết
                                            </button>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="chienbao-details" aria-hidden="true" style="font-size: 14px;top:75px">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="align">
                            <h6 class="modal-title" style="font-size: 18px;
                    color: #d91a1a;">Chi
                                tiết chiến báo</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div id="chienbao-details-data" class="modal-body" style="max-height: 325px; overflow-y: auto;">

                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="giftvatpham" tabindex="-1" aria-hidden="true"
                style="font-size: 12px;top:75px">
                <div class="modal-dialog modal-dialog-centered modal-md">

                    <div class="modal-content" style="background: #fff;  font-size: large;">
                        <div class="modal-header" style="border-bottom:1px solid #000">
                            <h6 class="modal-title">Nhặt vật phẩm</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        {{-- <button type="button " class="btn-close float-right" data-dismiss="modal" style="float: right"></button> --}}
                        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="  position: absolute; right: 0px;"> --}}

                        <div class="modal-body" style=" text-align:center  ">
                            <img src="" style="width: 50px;height:50px; " id="vatpham_img">
                            <h4 style="color: #ff6000;font-weight: bold;" id="vatpham_name"></h4>
                            <p id="vatpham_info"></p>

                            <p id="vatpham_op"></p>
                        </div>

                    </div>
                </div>
            </div>
            @php
                $uuid_gift = Str::Uuid()->toString();
            @endphp
            <style>



            </style>
            <div id="a{{ $uuid_gift }}">

            </div>
        @endif
    @endif
    <div class="modal fade" id="earn-gift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="background: transparent;border: none;">
                <div class="modal-header" style="border-bottom: unset">
                    <button type="button" class="rounded-circle text-white" id="close-btn" data-bs-dismiss="modal"
                        style="margin-left: auto;margin-right: 1.5rem;width: 30px;height: 30px;font-size: 16px;background: transparent;border: 2px solid #ffffff;"
                        aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="position-relative text-center" id="lucky-modal" onclick="confirmEarn()">
                        <img src="{{ asset('frontend/images/lixi_ads_gift.png') }}" alt="Lixi" height="400px">
                        <h3 class="text-white position-absolute"
                            style="top: 180px;left: 50%;transform: translateX(-50%);font-size: 18px;font-weight: 800;width: 284px;text-transform: uppercase;">
                            Lì xì tặng thưởng
                        </h3>
                        <p class="text-white position-absolute"
                            style="top: 230px;left: 50%;transform: translateX(-50%);width: 284px;font-size: 16px;line-height: 26px;">
                            Quà tặng thưởng random dành cho user click quảng cáo và user đăng ký VIP.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
@if (isset($players) && !empty($players))
    @if (!$players['is_ban'])
        @push('scripts')
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                function nhapgift() {

                    (async () => {

                        const {
                            value: gift
                        } = await Swal.fire({
                            title: 'Gift',
                            background: '#EEEEEE',
                            input: 'text',
                            inputLabel: "Nhập gift code: ",
                            inputPlaceholder: 'Nhập mã'
                        })

                        if (gift) {

                            Livewire.emit('giftcode', gift)


                        }

                    })()
                }
                window.addEventListener('swal:modal', event => {
                    Swal.fire({

                        icon: event.detail.icon,
                        title: event.detail.title,
                        text: event.detail.text,
                        timer: 5000
                    })
                });
                window.addEventListener('showEarnClick', event => {
                    if (event.detail.show == 1) {
                        $('#earn-fade').show()
                    }
                });
                window.addEventListener('opengift', event => {
                    let uuid = "#a{{ $uuid_gift }}"
                    if ($(uuid).find('img').length == 0) {

                        $(uuid).html(event.detail.text);

                    }


                });

                window.addEventListener('opengiftsuccess', event => {

                    $('#vatpham_name').html(event.detail.item.name);

                    $('#vatpham_info').html(event.detail.item.info);
                    $('#vatpham_img').attr('src', event.detail.item.img);
                    $('#vatpham_op').html('');
                    event.detail.item.options.forEach(element => {
                        $('#vatpham_op').append(element + '<br>');
                    });

                    $("#giftvatpham").modal('show');


                });

                function clickopengift(a) {
                    if ($(a).is(':visible')) {
                        $(a).remove()
                        Livewire.emit('opengift')

                    }

                }
            </script>
        @endpush
    @endif
@endif
@auth('web')
    @push('scripts')
        <script>
            function goToPk() {
                Swal.fire({
                    title: 'Thiên ngoại chiến trường',
                    text: 'Bạn muốn tham gia so tài PK chứ?',
                    showCancelButton: true,
                    confirmButtonColor: 'rgb(217 175 7)',
                    cancelButtonColor: 'rgb(225 91 91)',
                    confirmButtonText: 'Tham gia',
                    background: '#536280',
                    cancelButtonText: 'Không',
                    confirmButton: 'confirm-button-class',
                    cancelButton: 'cancel-button-class',
                    customClass: {
                        popup: 'text-pk',
                        confirmButton: 'pk-button-class',
                        cancelButton: 'pk-button-class',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('pk.index') }}";
                        window.location.replace(url);
                    }
                });
            }
            window.addEventListener('earnGifts', event => {
                if (event.detail.text == 1 && !$('#earn-gift').hasClass('show')) {
                    $("#btn-popup-earn-gift").click()
                }
            });
            window.addEventListener('closeEarnPopup', event => {
                if (event.detail.close == 1 && $('#earn-gift').hasClass('show')) {
                    $("#earn-gift #close-btn").click()
                }
            });
            window.addEventListener('readallChienbao', event => {
                $("#showchienbao").empty();
                $("#showchienbao").html(event.detail);

            })

            function clickEarnGifts() {
                Livewire.emit('earnGifts')
            }

            function confirmEarn() {
                $('#earn-gift #close-btn').click()
                Livewire.emit('confirmEarn')
            }
        </script>
    @endpush
@endauth
@push('scripts')
    <script !src="">
        // $('#up-vip-but').click(function(e) {
        //     $(".mobile-nav-container").removeClass("active");
        // });
        //
        // $('.close-up-vip').click(function(e) {
        //     $(".mobile-nav-container").addClass("active");
        // });




        // $('.nav-icon').click(function(e) {
        //     $(".mobile-nav-container").toggleClass("active");
        // });

        $('.nav-close').click(function(e) {
            $(".mobile-nav-container").toggleClass("active");
        });


        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false,
            ignore: "",
            lang: "en",
            rules: {
                username: {
                    required: true,
                },
                password: {
                    required: true,
                },
                remember: {
                    required: false
                }
            },
            messages: {
                username: {
                    required: "Hãy nhập tài khoản",
                },
                password: {
                    required: "Hãy nhập mật khẩu",
                },
            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                var validator = this;
                var submitButton = $(form).find('button[type="submit"]')
                submitButton.addClass('m-loader').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/logins",
                    data: $(form).serialize(),
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status == 300) {
                            window.location.reload();
                        } else if (res.status == 400) {
                            $('#error').text('Tài khoản của bạn đã bị khóa');
                            submitButton.addClass('m-loader').prop('disabled', false);
                        } else {
                            $('#error').text('Sai tài khoản hoặc mật khẩu');
                            submitButton.addClass('m-loader').prop('disabled', false);
                        }
                    },
                    error: function(e) {
                        submitButton.removeClass('m-loader').prop('disabled', false);
                        if (e.status == 422) {
                            $.each(Object.keys(e.responseJSON.errors), function(key, value) {
                                validator.showErrors({
                                    [value]: e.responseJSON.errors[value][0]
                                });
                            });
                        }
                    }
                });
                return false;
            }
        });
        $('.login-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
        $('.register-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false,
            ignore: "",
            lang: "en",
            rules: {},
            messages: {

            },
            invalidHandler: function(event, validator) { //display error alert on form submit
                $('.alert-danger', $('.register-form')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                var validator = this;
                var submitButton = $(form).find('button[type="submit"]')
                submitButton.addClass('m-loader').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/register",
                    data: $(form).serialize(),
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status) {
                            toastr.success(res.message, 'Thành Công');
                            setTimeout(location.reload.bind(location), 2000);
                        }
                    },
                    error: function(e) {
                        submitButton.removeClass('m-loader').prop('disabled', false);
                        if (e.status == 422) {
                            $.each(Object.keys(e.responseJSON.errors), function(key, value) {
                                validator.showErrors({
                                    [value]: e.responseJSON.errors[value][0]
                                });
                            });
                        }
                    }
                });
                return false;
            }
        });
        $('.register-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.register-form').validate().form()) {
                    $('.register-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });

        $('.change-password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false,
            ignore: "",
            lang: "en",
            rules: {

            },
            messages: {},
            invalidHandler: function(event, validator) { //display error alert on form submit
                $('.alert-danger', $('.change-password')).show();
            },
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                var validator = this;
                var submitButton = $(form).find('button[type="submit"]')
                submitButton.addClass('m-loader').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/change-password",
                    data: $(form).serialize(),
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status) {
                            toastr.success(res.message, 'Thành Công');
                            setTimeout(location.reload.bind(location), 2000);
                        }
                    },
                    error: function(e) {
                        submitButton.removeClass('m-loader').prop('disabled', false);
                        if (e.status == 422) {
                            $.each(Object.keys(e.responseJSON.errors), function(key, value) {
                                validator.showErrors({
                                    [value]: e.responseJSON.errors[value][0]
                                });
                            });
                        }
                    }
                });
                return false;
            }
        });
        $('.change-password input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.change-password').validate().form()) {
                    $('.change-password').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });

        let is_pk = false;
        let timeoutSearch, timeoutWait, timeoutPk, timeoutChienbao;

        function pkbaothu(idpk){
            $("#chienbao").modal("hide");
            $("#tim-kiem").modal("show");
            $('#id-pk').empty();
            $('#id-pk').html(idpk);

        }
        function chienbaoChitiet(idcb){
            console.log(idcb);
            $("#chienbao-details-data").empty();
            $.get({
                url: "{{ route('pk.listcb') }}/"+idcb,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.code == 0) {
                        $("#chienbao").modal("hide");
                        $("#chienbao-details-data").html(response.message);
                    } else {
                       $("#chienbao-details").modal("hide");
                       $("#chienbao-details-data").empty();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                        willClose: function() { // Xử lý khi user bấm OK
                            location.reload(); // Reload trang sau khi user bấm OK
                        }
                    })
                }
            })

        }

        function pk(e) {
            $('#thong-bao-pk').hide();
            $('#cancel-pk').show();
            $('#thong-bao-pk').text("");


            if (is_pk === false) {
                is_pk = true;

                $('#tim-doi-thu').prop('checked', true);
                $('#thong-bao-pk').show();
                $('#thong-bao-pk').text("Đang tìm đối thủ.");
                var urlsearch;
                if (e.getAttribute("data-pkm") == 1) {
                    iddoithu = $('#id-pk').val();
                    if (!Number.isInteger(parseInt(iddoithu)) && iddoithu !== null) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thất bại',
                            text: "Vui lòng nhập đúng định dạng id.",
                            willClose: function() {
                                location.reload();
                            }
                        })

                    } else {

                        urlsearch = "{{ route('pk.actionpk') }}/" + iddoithu;
                    }
                } else {
                    urlsearch = "{{ route('pk.actionpk') }}";
                }

                timeoutSearch = setTimeout(() => {
                    $('#chien-dau').prop('checked', true);
                    $('#cancel-pk').prop('disabled', true);
                    $('#thong-bao-pk').show();
                    $('#thong-bao-pk').text("Đang chiến đấu.");
                    $.get({

                        url: urlsearch,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.code === 0) {



                                timeoutWait = setTimeout(() => {



                                    $.get({
                                        url: "{{ route('pk.battle') }}",
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                                .attr('content')
                                        },
                                        success: function(response) {
                                            if (response.code == 0) {
                                                $('#hoan-thanh-pk').prop('checked',
                                                    true);
                                                $('#thong-bao-pk').show();
                                                $('#thong-bao-pk').text(
                                                    "Pk hoàn thành. Vui lòng xem chiến báo."
                                                );
                                                setTimeout(() => {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Thành Công',
                                                        text: response
                                                            .message,
                                                        willClose: function() { // Xử lý khi user bấm OK
                                                            location
                                                                .reload(); // Reload trang sau khi user bấm OK
                                                        }
                                                    })
                                                }, 1500)
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Lỗi',
                                                    text: 'Có lỗi xảy trong quá trình pk, reload lại trang.',
                                                    willClose: function() { // Xử lý khi user bấm OK
                                                        cancelpk()
                                                        setTimeout(() => { location
                                                            .reload(); // Reload trang sau khi user bấm OK
                                                        },3000)

                                                    }
                                                })

                                            }
                                        },
                                        error: function() {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Lỗi',
                                                text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                                                willClose: function() { // Xử lý khi user bấm OK
                                                    cancelpk()
                                                    location
                                                        .reload(); // Reload trang sau khi user bấm OK
                                                }
                                            })
                                        }
                                    })
                                }, response.message);

                            } else {
                                $('#tim-doi-thu').prop('checked', true);
                                $('#thong-bao-pk').show();
                                $('#cancel-pk').hide();
                                $('#thong-bao-pk').text(response.message);
                                is_pk = false;

                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có lỗi xảy ra khi gửi yêu cầu pk.',
                                willClose: function() { // Xử lý khi user bấm OK
                                    location.reload(); // Reload trang sau khi user bấm OK
                                }
                            })
                        }
                    })
                }, 2000);


            }




        }

        function cancelpk() {
            $.get({
                url: "{{ route('pk.cancelbattle') }}",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.code == 0) {
                        clearTimeout(timeoutWait);

                        Swal.fire({
                            icon: 'success',
                            title: 'Thành Công',
                            text: response.message,
                            willClose: function() { // Xử lý khi user bấm OK
                                location.reload(); // Reload trang sau khi user bấm OK
                            }
                        })


                    } else {
                        location.reload();

                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                        willClose: function() { // Xử lý khi user bấm OK
                            location.reload(); // Reload trang sau khi user bấm OK
                        }
                    })
                }
            })



        }
        $("#cancel-pk").click(function(){
            cancelpk();
        })
    </script>
@endpush
