<header>


    <div class="header">
        <div class="container">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-lg-3 col-xl-3 col-md-3 col-3 mobile-nav"
                    style="display:none; justify-content:space-between; align-items: center; text-align: center; font-weight:500;">
                    <a class="nav-icon"><img src="frontend/images/nav-icon.png" alt=""></a>
                </div>
                <nav class="mobile-nav-container">
                    <div class="container">
                        <div class="modal-header">
                            <button type="button" class="btn-close nav-close" style="width:20px;"></button>
                        </div>
                        <form class="search" action="{{ route('embedChapter') }}" style="margin-top: 8px;">
                            <input type="text" required name="search" placeholder="Nhúng link/ Tìm truyện">
                            <button type="submit" class="btn-search"><i
                                    style="color: gray; font-size: 20px;"class="fa fa-paper-plane"></i></button>
                        </form>

                        <ul>
                            <li><a style="color: gray;" href="{{ route('home') }}">Trang Chủ</a> </li>
                            <li><a style="color: gray;" href="{{ route('embedChapter') }}">Tìm truyện</a> </li>
                            <li><a style="color: gray;" href="{{ route('tuluyen.index') }}">Tu luyện</a> </li>
                            {{-- <li><a style="color: gray;" href="" data-bs-toggle="modal" data-bs-target="#vip" id="up-vip-but">Nâng VIP </a> </li> --}}
                            <li><a style="color: gray;" href="{{ route('user.game') }}">Trò chơi</a> </li>
                            <li> <a style="color: gray;" href="{{ route('post.index') }}">Hướng dẫn</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="col-lg-2 col-xl-2 col-md-2 col-4 re_col_logo">
                    <div class="div-logo">
                        @if (setting('store_logo'))
                            <a href="{{ route('home') }}"><img src="{{ \Storage::url(setting('store_logo')) }}"
                                    alt="" class="logo"></a>
                        @else
                            <a href="{{ route('home') }}"><img src="frontend/images/new-logo.jpg" alt=""
                                    class="logo"></a>
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
                        <div class="f-right">
                            @php
                                $time = new \Carbon\Carbon('2018-01-01 00:00:00');
                                $countActivity = \DB::table('notifications')
                                    ->where('notifiable_id', currentUser()->id)
                                    ->where('read_at', $time)
                                    ->where('type', '!=', 'comment')
                                    ->orderBy('updated_at', 'desc')
                                    ->count();
                                $countComment = \DB::table('notifications')
                                    ->where('notifiable_id', currentUser()->id)
                                    ->where('read_at', $time)
                                    ->where('type', 'comment')
                                    ->orderBy('updated_at', 'desc')
                                    ->count();
                            @endphp
                            <button class="btn notification position-relative" data-bs-toggle="modal" data-bs-target="#noti"
                                onclick="$.get({url:'./readAllNoti',success:function(){ $('.notiss').remove() }})"><i
                                    style="color: gray; font-size: 17px;" class="fa fa-bell"></i>
                                @if ($countActivity)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notiss">{{ $countActivity > 99 ? '99+' : $countActivity }}</span>
                                @endif
                            </button>
                            {{-- <i class="fa-regular fa-comment" style="color: gray; font-size: 15px;"></i> --}}

                            <button class="btn notification position-relative" data-bs-toggle="modal"
                                data-bs-target="#comment"
                                onclick="$.get({url:'./readAllComment',success:function(){ $('.notisss').remove() }})"><i
                                    style="color: gray; font-size: 17px;" class="far fa-comment-lines"></i>
                                @if ($countComment)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notisss">{{ $countComment > 99 ? '99+' : $countComment }}</span>
                                @endif
                            </button>
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
                                        ])
                                    @endif
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
                                    <a href="{{ route('user.wallets') }}">Ví của tôi</a>
                                </li>
                                <!-- <li>
                                                                                                    <a href="{{ route('card.top') }}" class="text-danger">TOP nạp thẻ</a>
                                                                                                </li> -->
                                <li>
                                    <a href="{{ route('user.recharge') }}">Nạp tiền</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.gold-gift') }}">Tặng vàng</a>
                                </li>
                                <li>
                                    <a href="{{ route('user.game') }}" style="color: #2cb15b">Trò chơi</a>
                                </li>
                                <li>
                                    <a href="{{ route('stories.index') }}">Đăng truyện</a>
                                </li>
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
                    <div class="col-lg-2 col-xl-2 col-md-2 col-2 logins">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#login"
                            class="header-login">Đăng nhập</a>
                        <!-- <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#register" class="text-success" >Đăng ký</a> -->
                    </div>
                @endauth
            </div>
            @auth('web')
                @php
                    $activity = \DB::table('notifications')
                        ->where('notifiable_id', currentUser()->id)
                        ->where('type', '!=', 'comment')
                        ->orderBy('updated_at', 'desc')
                        ->get();
                    $comment = \DB::table('notifications')
                        ->where('notifiable_id', currentUser()->id)
                        ->where('type', 'comment')
                        ->orderBy('updated_at', 'desc')
                        ->get();
                @endphp
            @endauth
            <!-- Modal Notification -->
            @auth('web')
                <div class="modal fade" id="noti" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 12px;top:75px">
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
                                                        <span>{{ $list->updated_at }}</span>
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
                    aria-hidden="true" style="font-size: 12px;top:75px">
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
            {{-- modal chiến báo --}}



            <!-- Modal Login -->
            <div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 12px;">
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
            <div class="modal fade" id="vip" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 12px;top:75px">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-vip-head ">
                            <p class="font-vip-modal close-up-vip"
                                style="float: right; padding-right: 1em; padding-top: 0.3em;">Đóng</p>
                            <button type="button" class="btn-close re-btn-vip close-up-vip" data-bs-dismiss="modal"
                                style="float: right;"></button>
                            <br>
                            <br>
                            <br>
                            <h5 class="text-center">Nâng VIP</h5>

                        </div>
                        <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                            <h5>- Quyền lợi:</h5>
                            <br>
                            <ul style="list-style-type: circle;">
                                <li class="font-vip-modal">Không quảng cáo</li>
                                <li class="font-vip-modal">Đọc free tất cả các chương VIP</li>
                                <li class="font-vip-modal">Nhân đôi tu vi tu luyện</li>
                                <li class="font-vip-modal">Được tặng 50 phiếu vote truyện mỗi ngày</li>
                                <li class="font-vip-modal">Có huy hiệu VIP</li>
                            </ul>
                        </div>
                        <div class="modal-vip-foot text-center">
                            <button type="button" class="btn btn-success btn-re-color">Nâng cấp VIP ngay với 50.000
                                vàng/tháng</button>
                            <p class="font-vip-modal">Số dư của bạn: 100 vàng</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Register -->
            <div class="modal fade" id="register" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 12px;">
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
                aria-hidden="true" style="font-size: 12px">
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
            <div wire:ignore.self class="modal fade" id="chienbao" tabindex="-1" aria-hidden="true"
                style="font-size: 12px;top:75px">
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
                                        {{-- <div class="d-flex justify-content-between">
                                Hệ thống
                                <span>2022-12-12 00:00:00</span>
                            </div> --}}
                                        Sẽ mở khi có hệ thống pk
                                    </li>
                                </a>



                            </ul>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="giftvatpham" tabindex="-1" aria-hidden="true"
                style="font-size: 12px;top:75px">
                <div class="modal-dialog modal-dialog-centered modal-md">

                    <div class="modal-content" style="background: #eee9bf;  font-size: large;">
                        <div class="modal-header" style="border-bottom:1px solid #fff">
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
@push('scripts')
    <script !src="">
        $('#up-vip-but').click(function(e) {
            $(".mobile-nav-container").removeClass("active");
        });

        $('.close-up-vip').click(function(e) {
            $(".mobile-nav-container").addClass("active");
        });




        $('.nav-icon').click(function(e) {
            $(".mobile-nav-container").toggleClass("active");
        });

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
    </script>
@endpush
