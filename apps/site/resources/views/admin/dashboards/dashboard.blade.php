@extends('admin.layouts.master')
@section('title', __('Trang chủ'))
@section('page-header')
    <x-page-header>
        <x-slot name='title'>
            <h4><i class="icon-cube mr-2"></i> <span class="font-weight-semibold">{{ __('Trang chủ') }}</span></h4>
        </x-slot>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop
@push('css')
    <link rel="stylesheet" href="/backend/global_assets/js/vendors/vector-map/jquery-jvectormap-2.0.5.css">
    <style>
        .card-body{
            padding: 1.750rem 1rem;
        }

        .card-body .font-size-theme{
            font-size: 0.7875rem;
        }

        .jvectormap-zoomin{
            display: none;
        }

        .jvectormap-zoomout{
            display: none;
        }

        .has-bg-image{
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 20px;
            border-radius: 10px;
        }

        .card-box-analytics{
            box-shadow: 0px 0px 1px 1px #0c213a1a;
            border-radius: 10px;
        }
    </style>
@endpush

@push('js')
    <script src="/backend/global_assets/js/vendors/vector-map/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="/backend/global_assets/js/vendors/vector-map/jquery-jvectormap-world-mill.js"></script>
    <script src="/backend/global_assets/js/vendors/echarts/echarts.min.js"></script>
    <script src="/backend/js/chart.min.js"></script>
    <script !src="">
        $.ajaxSetup({ cache: false });
        $(function () {
            $('.card [data-action=reload]:not(.disabled)').on('click', function (e) {
                e.preventDefault();
                var $target = $(this),
                    block = $target.closest('.card');

                // Block card
                $(block).block({
                    message: '<i class="icon-spinner2 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait',
                        'box-shadow': '0 0 0 1px #ddd'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'none'
                    }
                });
                let url = $(block).data('url');
                $.get(url, function(response, status){
                    $(block).find('.card-body').html(response);
                    $(block).unblock();
                });
            });

            $('.ajax-card').each(function (index, el) {
                $(this).block({
                    message: '<i class="icon-spinner2 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait',
                        'box-shadow': '0 0 0 1px #ddd'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'none'
                    }
                });
                let $this = $(this);

                let url = $(el).data('url');
                $.get(url, function(response, status){
                    $(el).find('.card-body').html(response);
                    $this.unblock();
                });
            })
        });
    </script>

<script>
    $(function(){
    $('#calendar').on('change',function(){
        var calendar = $(this).val();
        var url =  "{{ route('admin.dasboard.filter') }}";
        $.get({
            url:url,
            data:{calendar:calendar},
            success:function(res){
                $('.revenue').text(res.revenue);
                $('.reCharge').text(res.reCharge);
                $('.userRegister').text(res.userRegister);
                $('.order').text(res.order);
                $('.goldGame').text(res.goldGame);
                $('.winGame').text(res.winGame);
                $('.story').text(res.story);
                $('.withdraw').text(res.withdraw);


            }
        })
    })
    })
</script>
@endpush

@section('page-content')
<div class="row">
    <div class="col-sm-6 col-xl-3"><span>Thống kê theo :</span>
        <select name="" id="calendar" class="form-control">
            <option value="day">Ngày hôm nay</option>
            <option value="week">Tuần này</option>
            <option value="month">Tháng này</option>
        </select>
    </div>

</div>
<br>
    <div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body has-bg-image" style="background: linear-gradient(45deg, #ed996d, #b6d3bc)">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0 "><a href="#" class="text-white revenue">{{ formatNumber($userVip) }}</a></h3>
                    <span class="text-uppercase font-size-theme"><a href="#" class="text-white">{{ __('User Vip ') }}</a></span>
                </div>

                <div class="ml-3 align-self-center">
                    <a href="#">
                    <i class="fal fa-2x fa fa-star text-white" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background: linear-gradient(45deg, #ed996d, #ffeb3b)">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.recharge_transactions.index') }}" class="text-white reCharge">{{ formatNumber($reChargeToday) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.recharge_transactions.index') }}" class="text-white">{{ __('Tiền nạp ') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.recharge_transactions.index') }}">
                            <i class="fal fa-2x fa-book-alt text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background: linear-gradient(to right, #6FB1FC, #4364F7, #0052D4);">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.users.index') }}" class="text-white userRegister">{{ number_format(count($userRegisterToday)) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.users.index') }}" class="text-white">{{ __('Đăng ký ') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fal fa-2x fa-user text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background: linear-gradient(to right, #FF512F, #ffeb3b)">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.order.index') }}" class="text-white order">{{ formatNumber($orderToday) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.order.index') }}" class="text-white">{{ __('Vàng mua chương VIP ') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.order.index') }}">
                            <i class="fal fa-2x fa-list text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background:linear-gradient(to right, #FF512F, #9c27b0)">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.stories.index') }}" class="text-white goldGame">{{ formatNumber($goldGameToday) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.stories.index') }}" class="text-white">{{ __('Tiền chơi trò chơi') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.gold-gift.index') }}">
                            <i class="fal fa-2x fa-gift text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background: #b0218b;  /* fallback for old browsers */background: -webkit-linear-gradient(to right, #6dd5ed, #2193b0);  /* Chrome 10-25, Safari 5.1-6 */background: linear-gradient(45deg, #ed996d, #b0218b); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.stories.index') }}" class="text-white  winGame">{{ formatNumber($winGameToday) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.stories.index') }}" class="text-white">{{ __('Tiền trúng thưởng') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                    <a href="#">
                    <i class="fal fa-2x fa-money-bill-wave-alt text-white"></i>
                    </a>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body has-bg-image" style="background:linear-gradient(to right, #FF512F, #ff9800)">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0"><a href="{{ route('admin.stories.index') }}" class="text-white story">{{ formatNumber(count($storyToday)) }}</a></h3>
                        <span class="text-uppercase font-size-theme"><a href="{{ route('admin.stories.index') }}" class="text-white">{{ __('Đầu truyện') }}</a></span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.stories.index') }}">
                            <i class="fal fa-2x fa-books text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body has-bg-image" style="background: #b0218b;  /* fallback for old browsers */background: -webkit-linear-gradient(to right, #6dd5ed, #2193b0);  /* Chrome 10-25, Safari 5.1-6 */background: linear-gradient(45deg, #ed996d, #b0218b); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0"><a href="#" class="text-white withdraw">{{ formatNumber($withdrawToday) }} VND</a></h3>
                    <span class="text-uppercase font-size-theme"><a href="#" class="text-white">{{ __('Số tiền rút') }}</a></span>
                </div>

                <div class="ml-3 align-self-center">
                        <a href="{{ route('admin.recharge_transactions.index') }}">
                        <i class="fal fa-2x fa-credit-card text-white"></i>
                        </a>
                    </div>
            </div>
        </div>
    </div>
    </div>
    @if(setting('analytics', 0) == \App\Enums\AnalyticsState::SHOW)
    <div class="row">
        <div class="col-md-12">
            <div class="card ajax-card" data-url="{{ route('admin.analytics') }}">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title"><i class="fal fa-chart-bar mr-2"></i> {{ __('Phân tích') }}</h6>
                </div>

                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card ajax-card" data-url="{{ route('admin.top-referrers') }}">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title"><i class="far fa-bullseye-pointer"></i> {{ __('Tìm kiếm hàng đầu') }}</h6>
                </div>

                <div class="card-body">

                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="card ajax-card" data-url="{{ route('admin.most-visited-pages') }}">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title"><i class="far fa-bullseye-pointer"></i> {{ __('Trang truy cập nhiều nhất') }}</h6>
                </div>

                <div class="card-body">

                </div>

            </div>
        </div>
    </div>
    @endif
    <div class="row">
            <div class="col-md-6">
                <div class="card" data-url="{{ route('admin.pages.index') }}">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title"><i class="fal fa-file-alt"></i> {{ __('Xếp hạng mod theo doanh thu tháng') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Tên mod') }}</th>
                                    <th>{{ __('Số tiền') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($turnOvers as $user)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="javascript:;" class="text-default font-weight-semibold letter-icon-title">{{ $user->name }}</a>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-sm">{{ number_format($user->turn_over)." tệ" }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" data-url="{{ route('admin.pages.index') }}">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title"><i class="fal fa-file-alt"></i> {{ __('Xếp hạng mod theo chương đã đăng') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('Tên mod') }}</th>
                                    <th>{{ __('Số chương') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($createdChapters as $user)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="javascript:;" class="text-default font-weight-semibold letter-icon-title">{{ $user->name }}</a>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-sm">{{ number_format($user->chapters_created) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @if($goldGiftNews->count() > 0)
            <div class="col-md-6">
                <div class="card" data-url="{{ route('admin.stories.index') }}">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title"><i class="fal fa-file-alt"></i> {{ __('Giao dịch tặng vàng') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th >{{ __('Người gửi') }}</th>
                                    <th>{{ __('Người nhận') }}</th>
                                    <th>{{ __('Số vàng') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($goldGiftNews as $goldGiftNew)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ route('user.index',$goldGiftNew->user->id) }}" class="text-default font-weight-semibold letter-icon-title">{{ $goldGiftNew->user->name }}</a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('user.index',$goldGiftNew->user->id) }}" class="text-default font-weight-semibold letter-icon-title">{{ $goldGiftNew->user->name }}</a>
                                            </td>
                                            <td>
                                                <span>{{number_format((int)$goldGiftNew->gold) }} vàng</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">

        @if($storyNews->count() > 0)
            <div class="col-md-6">
                <div class="card" data-url="{{ route('admin.stories.index') }}">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title"><i class="fal fa-file-alt"></i> {{ __('Truyện mới') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="w-100">{{ __('Tên truyện') }}</th>
                                    <th>{{ __('Người đăng') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($storyNews as $storyNew)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ $storyNew->id }}" class="text-default font-weight-semibold letter-icon-title">{{ $storyNew->name }}</a>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-sm">{{ $storyNew->user->name ?? 'Không có'}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($pageTops->count() > 0)
            <div class="col-md-6">
                <div class="card" data-url="{{ route('admin.pages.index') }}">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title"><i class="fal fa-file-alt"></i> {{ __('Trang được xem nhiều nhất') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="w-100">{{ __('Tên trang') }}</th>
                                    <th>{{ __('Lượt xem') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($pageTops as $pageTop)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{ $pageTop->url() }}" class="text-default font-weight-semibold letter-icon-title">{{ $pageTop->title }}</a>
                                            </td>
                                            <td>
                                                <span class="text-muted font-size-sm">{{ $pageTop->view }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop
