@extends('shop.layouts.app')

@section('title')
    {{ setting('store_name') }}
    @if (!empty(setting('store_slogan')))
        -
    @endif
    {{ setting('store_slogan') }}
@endsection
@section('seo')
    <link rel="canonical" href="{{ url('/') }}">
    <meta name="title" content="{{ setting('store_name') }} - {{ setting('store_slogan') }}">
    <meta name="description" content="{{ setting('store_description') }}">
    <meta name="keywords" content="{{ setting('store_name') }}, {{ setting('store_name') }} Việt Nam">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ setting('store_name') }} - {{ setting('store_slogan') }}">
    <meta property="og:description" content="{{ setting('store_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:image"
        content="{{ setting('logo') ? \Storage::url(setting('logo')) : asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="{{ url('/') }}">
@stop
@push('styles')
    <style>
    </style>
@endpush
@section('content')

<div>
</div>
<style>
    .noti-block ol li {
        list-style-type: decimal;
        list-style-position: inside;
    }
    .noti-block ul li {
        list-style-type: initial;
        list-style-position: inside;
    }
</style>
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                    <div class="noti-block p-3 mt-3 border-danger border-3" style="border-style: solid; background-color: #FFFDE8;">
                        <h5 class="text-success font-weight-bold">Thông báo</h5>
                        {!! setting('noti_webpc') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if((new \Jenssegers\Agent\Agent())->isMobile())
        <section class="content container mt-2 search-section" style="min-height: unset">
            <div class="col-12">
                <div class="noti-block p-3 mb-3 border-danger border-3" style="border-style: solid; background-color: #FFF8B7;">
                    <h5 class="text-success font-weight-bold">Thông báo</h5>
                    {!! setting('noti_mobile') !!}
                </div>
            </div>
            <div class="col-12" style="display: grid; grid-template-columns: repeat(4, 1fr); grid-gap: 20px">
                <a href="{{ route('embedChapter') }}" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/icon-category.svg') }}" alt="Danh mục"
                         style="width: fit-content">
                    <span class="name-item mt-2">Danh mục</span>
                </a>
                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/icon-attendance.svg') }}" alt="Điểm danh"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2 active">--}}
                {{--                                        Điểm danh--}}
                {{--                                    </span>--}}
                {{--                                </a>--}}
                @auth('web')
                    <a href="{{ route('vip') }}"  class="d-flex flex-column align-items-center text-danger">
                        <img src="{{ asset('frontend/images/mobile/icon-upvip.svg') }}" alt="Nâng VIP"
                             style="width: fit-content">
                        <span class="name-item mt-2">Nâng VIP</span>
                    </a>
                @endauth
                <a href="{{ route('user.recharge') }}" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/recharge.svg') }}" alt="Nạp tiền"
                         style="width: fit-content">
                    <span class="name-item mt-2">Nạp tiền</span>
                </a>
                {{--                                <!-- Force next columns to break to new line -->--}}
                {{--                                <div class="w-300"></div>--}}

                <a href="{{ route('tuluyen.index') }}" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/practice.svg') }}" alt="Tu luyện"
                         style="width: fit-content">
                    <span class="name-item mt-2">Tu luyện</span>
                </a>
                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/recharge.svg') }}" alt="XSMB"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2">XSMB</span>--}}
                {{--                                </a>--}}
                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/layso.svg') }}" alt="Lấy số"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2">Lấy số</span>--}}
                {{--                                </a>--}}
                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/tambao.svg') }}" alt="Tầm bảo"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2">Tầm bảo</span>--}}
                {{--                                </a>--}}

                {{--                                <!-- Force next columns to break to new line -->--}}
                {{--                                <div class="w-300"></div>--}}

                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/pk.svg') }}" alt="PK"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2">PK</span>--}}
                {{--                                </a>--}}
                {{--                                <a href="#" class="d-flex flex-column align-items-center text-danger">--}}
                {{--                                    <img src="{{ asset('frontend/images/mobile/store.svg') }}" alt="Cửa hàng"--}}
                {{--                                         style="width: fit-content">--}}
                {{--                                    <span class="name-item mt-2">Cửa hàng</span>--}}
                {{--                                </a>--}}
                <a href="https://zalo.me/g/rqfhlz892" target="_blank" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/zalo.svg') }}" alt="Chatbox"
                         style="width: fit-content">
                    <span class="name-item mt-2">Chatbox</span>
                </a>
                <a href="{{ route('post.index') }}" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/guide.svg') }}" alt="Hướng dẫn"
                         style="width: fit-content">
                    <span class="name-item mt-2">Hướng dẫn</span>
                </a>
                <a href="{{ route('pk.packTuLuyen') }}" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/50.png') }}" alt=""
                         style="width: 29px;">
                    <span class="name-item mt-2">Gói ưu đãi</span>
                </a>
                <a data-bs-toggle="modal" data-bs-target="#tim-kiem" class="d-flex flex-column align-items-center text-danger">
                    <img src="{{ asset('frontend/images/mobile/vs.png') }}" alt=""
                         style="width: 29px;">
                    <span class="name-item mt-2">Tìm đối thủ</span>
                </a>
            </div>
        </section>
    @endif
    <br>
@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <section class="content container" style="min-height: 20px">
        <div class="row" style="border-radius: 3px; width: 100%; margin: auto; background-color: #f4f4f4;">
            <div class="col-5">
                <h6 style="">Chọn loại truyện</h6>
            </div>
            <div class="col-lg-6 col-choose-type">
                <button type="button" class="btn btn-success normal-word"
                    style="width:130px; font-size: 80%; background-color:#008800; text-align: center;">VIP</button>
            </div>
        </div>
    </section>
    <section class="content container" style="background: white;">
        <div class="container contents mt-3">
            <div class="body-section row mobile-padding">
                @if ($storyNew->isNotEmpty())
                    <div class="col-sm-7">
                        <div class="home-section">
                            <div class="home-header">
                                <span class="fs-4">{{ 'Chương Mới' }}</span>
                            </div>
                            <div class="row">
                                @foreach ($storyNew as $storyWritten)
                                    @include('shop.story._card_home', ['story' => $storyWritten])
                                @endforeach

                            </div>
                        </div>
                        <div class="paginate">

                        </div>
                    </div>
                @endif
{{--                @if (!empty($storyReaded))--}}
{{--                    <div class="col-sm-7">--}}
{{--                        <div class="home-section">--}}
{{--                            <div class="home-header">--}}
{{--                                <span class="fs-4">{{ 'Truyện vừa đọc' }}</span>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                @foreach ($storyReaded as $storyWritten)--}}
{{--                                    @include('shop.story._card_home', ['story' => $storyWritten])--}}
{{--                                @endforeach--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="paginate">--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
                @if ($storyUpdated->isNotEmpty())
                    <div class="col-sm-5 col-md-5">
                        <div class="update-section">
                            <div class="update-header">
                                <span class="fs-4">Truyện mới</span>
                            </div>

                            <ul style="max-height: 435px;overflow: auto" class="home-scroll">
                                @foreach ($storyNews as $storyUpdate)
                                    <li class="row">
                                        @if ($storyUpdate)
                                            <a href="{{ route('story.show', $storyUpdate) }}" class="">
                                                <div class="img col-md-1" style="margin-left:-10px">
                                                    <img data-src="{{ $storyUpdate->avatar ?? $storyUpdate->getFirstMediaUrl('default') }}"
                                                        class="lazyload" alt="{{ $storyUpdate->name }}">
                                                </div>
                                                <div class="title col-md-9">
                                                    <b> {{ $storyUpdate->name }} </b>
                                                    <p class="fs-12">{{ $storyUpdate->user()->name ?? '' }}</p>
                                                </div>
                                                <div class="time col-md-5">
                                                    <i>{{ formatDayMonthYear($storyUpdate->created_at) }}</i>
                                                </div>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br>
                        <div class="update-section">
                            <div class="update-header">
                                <span class="fs-4">Truyện đọc nhiều trong tuần</span>
                            </div>

                            <ul style="max-height: 435px;overflow: auto" class="home-scroll">
                                @foreach ($story_week as $storyUpdate)
                                    <li class="row">
                                        @if ($storyUpdate)
                                            <a href="{{ route('story.show', $storyUpdate) }}" class="">
                                                <div class="img col-md-1" style="margin-left:-10px">
                                                    <img data-src="{{ $storyUpdate->avatar ?? $storyUpdate->getFirstMediaUrl('default') }}"
                                                        class="lazyload" alt="{{ $storyUpdate->name }}">
                                                </div>
                                                <div class="title col-md-9">
                                                    <b> {{ $storyUpdate->name }} </b>
                                                    <p class="fs-12">{{ $storyUpdate->user()->name ?? '' }}</p>
                                                </div>
                                                <div class="time col-md-5">
                                                    <i>{{ formatDayMonthYear($storyUpdate->created_at) }}</i>
                                                </div>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br>
                        <div class="update-section">
                            <div class="update-header">
                                <span class="fs-4">Truyễn ngẫu nhiên</span>
                            </div>

                            <ul style="max-height: 435px;overflow: auto" class="home-scroll">
                                @foreach ($storyrandomdaerty as $storyUpdate)
                                    <li class="row">
                                        @if ($storyUpdate)
                                            <a href="{{ route('story.show', $storyUpdate) }}" class="">
                                                <div class="img col-md-1" style="margin-left:-10px">
                                                    <img data-src="{{ $storyUpdate->avatar ?? $storyUpdate->getFirstMediaUrl('default') }}"
                                                        class="lazyload" alt="{{ $storyUpdate->name }}">
                                                </div>
                                                <div class="title col-md-9">
                                                    <b> {{ $storyUpdate->name }} </b>
                                                    <p class="fs-12">{{ $storyUpdate->user()->name ?? '' }}</p>
                                                </div>
                                                <div class="time col-md-5">
                                                    <i>{{ formatDayMonthYear($storyUpdate->created_at) }}</i>
                                                </div>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>



        <div class="container contents mt-3">
            <div class="body-section row mobile-padding">
                <div class="col-sm-7 col-md-7">
                    <div class="chat-section">
                        <div class="chat-header">
                            <span class="fs-4">Chat Box</span><br>
                            <span class="mt-4" style="font-size: 13px;">Kênh chính</span>
                            <hr>
                            <div class="chat-body home-scroll">
                                <iframe src="https://www5.cbox.ws/box/?boxid=930287&boxtag=brZbTV" width="100%"
                                    height="450" allowtransparency="yes" allow="autoplay" frameborder="0"
                                    marginheight="0" marginwidth="0" scrolling="auto"></iframe>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-sm-5 col-md-5">
                    <div class="update-section">
                        <div class="update-header">
                            <span class="fs-4">Top 20 Cao Thủ Đang Online (5 phút làm mới 1 lần)</span>
                        </div>

                        <ul style="max-height: 435px;overflow: auto" class="home-scroll">
                            @foreach ($top_level as $list)
                                <li class="row">
                                    <a href="{{ route('user.index', $list->user_id) }}" class="">
                                        <div class="img col-md-1" style="margin-left:-10px">
                                            <img data-src="{{ @$list->get_users->avatar ? pare_url_file($list->get_users->avatar, 'user') : 'frontend/images/no-user.png' }}"
                                                alt="{{ @$list->get_users->name }}" class="lazyload">
                                        </div>
                                        <div class="title col-md-9">
                                            @if( @$list->get_users->user_vip == 1)
                                                <p class="d-flex"><span class="text-danger font-weight-bold">{{ @$list->get_users->name }}</span>
                                                    <span class="circle bg-success d-inline-block mx-1">
                                                        <span class="checkmark"></span>
                                                    </span>
                                                - {{ @$list->lv_name }}</p>
                                            @else
                                                <p>{{ @$list->get_users->name }}</p>
                                            @endif
											{{-- <p>  </p> --}}
                                        </div>
										{{-- <div class="title col-md-5">
                                            <p> {{ @$list->lv_name }} </p>
                                        </div> --}}
                                        <div class="time">

                                            <i>Cấp: {{ $list->level }}</i>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <style>
        .home-header {
            background: url("{{ asset('frontend/images/bg-title-home.png') }}") no-repeat;
            background-size: cover;
        }
        .bg-home-2 {
            background: url("{{ asset('frontend/images/bg-title-home-2.png') }}") no-repeat;
            background-size: cover;
        }
        .slides {
            overflow-x: scroll;
        }
        .slide-section {
            width: max-content;
            display: flex;
            justify-content: space-between;
        }
        .slide-section .bookthumb {
            width: 30vw;
            margin: 0 5px;
        }
        .slide-section.read-recently .bookthumb{
            width: 25vw;
        }
        .bg-none {
            background: unset !important;
        }
        .border-left-3 {
            border-left: 3px solid;
        }
    </style>
    <section class="content container search-section p-0">
    @if ($story_nomination->isNotEmpty())
        <div class="col-12 mb-2">
            <div class="home-section">
                <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                    <span style="width: 70px"></span>
                    <span class="fs-4 text-white font-weight-bold">{{ 'Web Đề Cử' }}</span>
                    <a href="{{ route('embedChapter', ['count_chapter' => 100]) }}"
                       style="font-size: 13px; font-weight: 300"
                       class="view-all text-white"><u></u></a>
                </div>
                <div class="row px-2">
                    @foreach ($story_nomination as $storyUpdate)
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                            @include('shop.story._card_home', ['story' => $storyUpdate])
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        @endif

        @if (!empty($storyReaded))
            <div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header d-flex justify-content-between align-items-center">
                        <span class="fs-4 text-white font-weight-bold">{{ 'Truyện vừa đọc' }}</span>
                    </div>
                    <div class="slides">
                        <div class="slide-section read-recently px-2">
                            @foreach ($storyReaded as $storyWritten)
                                @include('shop.story._card_home', ['story' => $storyWritten])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($story_not_mod->isNotEmpty())
        <div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                        <span style="width: 80px"></span>
            <span class="fs-4 text-white font-weight-bold">{{ 'Truyện Mới Ra' }}</span>
            <span class="fs-4 text-white font-weight-bold"></span>
            <a href="{{ route('embedChapter', ['count_chapter' => 1]) }}"
               style="font-size: 13px; font-weight: 300"
               class="view-all text-white"><u></u></a>
        </div>
        <div class="row px-2">
        @foreach ($storyNews as $storyUpdate)
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                @include('shop.story._card_home', ['story' => $storyUpdate])
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if ($storyNew->isNotEmpty())
<div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                        <span style="width: 70px"></span>
            <span class="fs-4 text-white font-weight-bold">{{ 'Chương Vip Mới' }}</span>
            <span class="fs-4 text-white font-weight-bold"></span>
                        <a href="{{ route('embedChapter', ['count_chapter' => 50, 'sort' => 3]) }}"
                           style="color: #DD330099; font-size: 13px; font-weight: 300"
                           class="view-all text-danger"><u></u></a>
                    </div>
                    <div class="row px-2">
                        @foreach ($storyNew as $storyWritten)
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                                @include('shop.story._card_home', ['story' => $storyWritten])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


@if ($story_not_mod->isNotEmpty())
        <div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                        <span style="width: 70px"></span>
            <span class="fs-4 text-white font-weight-bold">{{ 'Chương Free Mới' }}</span>
            <span class="fs-4 text-white font-weight-bold"></span>
            <a href="{{ route('embedChapter', ['count_chapter' => 1]) }}"
               style="font-size: 13px; font-weight: 300"
               class="view-all text-white"><u></u></a>
        </div>
        <div class="row px-2">
            @foreach ($story_not_mod as $storyUpdate)
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                    @include('shop.story._card_home', ['story' => $storyUpdate])
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<hr style="height: 16px; background: #eae4d3; margin-top: 0px;">
        <div style="font-size: 12px; color: #545454; text-align: center;">
            <h9 class="title">
            <strong>  --------- Quảng cáo --------- </strong>
            @include('shop.layouts.partials.ads')
        </div>
        <hr style="height: 16px; background: #eae4d3; margin-top: 0px;">

        @if ($storyrandomdaerty->isNotEmpty())
            <div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                        <span style="width: 0px"></span>
                        <span class="fs-4 text-white font-weight-bold">{{ 'Đề cử ngẫu nhiên' }}</span>
                        <a href="{{ route('embedChapter', ['count_chapter' => 100]) }}"
                           style="font-size: 13px; font-weight: 300"
                           class="view-all text-white"><u></u></a>
                    </div>
                    <div class="row px-2">
                        @foreach ($storyrandomdaerty as $storyUpdate)
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                                @include('shop.story._card_home', ['story' => $storyUpdate])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if ($story_week->isNotEmpty())
            <div class="col-12 mb-2">
                <div class="home-section">
                    <div class="home-header bg-home-2 d-flex justify-content-between align-items-center">
                        <span style="width: 0px"></span>
                        <span class="fs-4 text-white font-weight-bold">{{ 'Top đọc tuần' }}</span>
                        <a href="{{ route('embedChapter', ['count_chapter' => 100]) }}"
                           style="font-size: 13px; font-weight: 300"
                           class="view-all text-white"><u></u></a>
                    </div>
                    <div class="row px-2">
                        @foreach ($story_week as $storyUpdate)
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-4">
                                @include('shop.story._card_home', ['story' => $storyUpdate])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        
    </section>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
{{--    Ranking--}}
    <style>
        .ranking .modal-dialog .modal-content {
            background: url("{{ asset('frontend/images/ranking-bg.png') }}");
            background-color: transparent !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 800px;
            min-height: 800px;
        }
        .ranking .modal-dialog .modal-content .modal-header{
            padding: 0.7rem 0;
        }
        .ranking .modal-dialog .modal-content .modal-body {
            padding-top: 0;
        }
        .rank-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 10px;
            grid-row-gap: 20px;
        }
        .btn-ranking {
            background-color: #C9B708;
            border: 2px solid #ffffff;
            border-radius: 5px;
            padding: 20px 0;
        }
        .list-user {
            max-height: 550px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1rem 1rem 0 3rem;
        }
        .list-user li {
            height: 110px;
        }
        .user .thumb {
            height: max-content;
        }
        .user .thumb p {
            top: 50%;
            transform: translateY(-50%);
            left: -2.5rem;
            font-size: 20px;
        }
        .user p {
            font-size: 13px;
        }
    </style>
    @php
        $prevMonth = Carbon\Carbon::now()->startOfMonth()->format('m');
        $rankingModal = [
            [
                'title' => 'Cống hiến bảng',
                'sub_title' => 'Xếp hạng nạp nhiều nhất tháng ' . $prevMonth,
                'users' => $userRanking['dedication'] ?? [],
                'target' => 'dedication'
            ],
            /**[
                'title' => 'Đại gia bảng',
                'sub_title' => 'Xếp hạng vàng nhiều nhất tháng ' . $prevMonth,
                'users' => $userRanking['gold'] ?? [],
                'target' => 'gold'
            ], **/
            [
                'title' => 'Tu vi bảng',
                'sub_title' => 'Xếp hạng tu vi nhiều nhất tháng ' . $prevMonth,
                'users' => $userRanking['training'] ?? [],
                'target' => 'training'
            ],
            /**[
                'title' => 'Cao thủ bảng',
                'sub_title' => 'Xếp hạng cao thủ tháng ' . $prevMonth,
                'users' => $userRanking['master'] ?? [],
                'target' => 'master'
            ], **/
            [
                'title' => 'Lực chiến bảng',
                'sub_title' => 'Xếp hạng lực chiến nhiều nhất tháng ' . $prevMonth,
                'users' => $userRanking['atk'] ?? [],
                'target' => 'atk'
            ],
            [
                'title' => 'Phòng thủ bảng',
                'sub_title' => 'Xếp hạng phòng thủ nhiều tháng ' . $prevMonth,
                'users' => $userRanking['def'] ?? [],
                'target' => 'def'
            ],
        ];
    @endphp
    <div class="container content search-section py-5">
        <h3 class="text-danger text-center mb-5 font-weight-bold">Bảng xếp hạng</h3>
        <div class="row rank-container px-4">
            @foreach($rankingModal as $modal)
                <button class="btn btn-ranking text-white font-weight-bold"
                        data-bs-toggle="modal" data-bs-target="#{{ $modal['target'] }}">
                    {{ $modal['title'] }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="chat-section">
                        <div class="chat-header">
                            <span class="fs-4">Chat Box</span><br>
                            <span class="mt-4" style="font-size: 13px;">Kênh chính</span>
                            <hr>
                            <div class="chat-body home-scroll">
                                <iframe src="https://www5.cbox.ws/box/?boxid=930287&boxtag=brZbTV" width="100%"
                                    height="450" allowtransparency="yes" allow="autoplay" frameborder="0"
                                    marginheight="0" marginwidth="0" scrolling="auto"></iframe>
                            </div>

                        </div>
                    </div>

    @if((currentUser() && currentUser()->user_vip == 0) || !currentUser())
    <hr style="height: 16px; background: #eae4d3; margin-top: 0px;">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6402569697449690"
     crossorigin="anonymous"></script>
<!-- dfdsf -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6402569697449690"
     data-ad-slot="3709328762"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<hr style="height: 16px; background: #eae4d3; margin-top: 0px;">
@endif

    @foreach($rankingModal as $modal)
        <div class="modal fade ranking" id="{{ $modal['target'] }}" tabindex="-1" aria-labelledby="{{ $modal['target'] }}"
             aria-hidden="true" style="font-size: 12px;top:50%; left: 50%; transform: translate(-50%, -50%)">
            <div class="modal-dialog" style="margin-top: 25px">
                <div class="modal-content border-0">
                    <div class="modal-header text-white text-center position-relative flex-column m-0 border-0">
                        <button type="button" class="p-0 btn-close close-up position-absolute text-white font-weight-bold"
                                style="top: -1rem; right: 1rem; font-size: 20px; background: unset"
                                data-bs-dismiss="modal">
                            X
                        </button>
                        <h5 class="font-weight-bold">{{ $modal['title'] }}</h5>
                        <p>{{ $modal['sub_title'] }}</p>
                    </div>
                    <div class="modal-body">
                        <ol class="list-user">
                            @if(!empty($modal['users']))
                                @foreach($modal['users'] as $key => $user)
                                    <li class="text-white px-2 py-3">
                                        <a href="{{ route('user.index', $user['id']) }}"
                                           class="user d-flex justify-content-between text-white">
                                            <div class="thumb position-relative">
                                                <img src="{{ $user['avatar'] ? pare_url_file($user['avatar'], 'user') : asset('frontend/images/no-user.png') }}"
                                                     height="45px" width="45px"
                                                     class="rounded-circle" alt="{{ $user['name'] }}">
                                                @if($user['is_vip'] == 1)
                                                    <img src="{{ asset('frontend/images/mod.png') }}"
                                                         height="15px" width="auto"
                                                         style="bottom: 0; left: 50%; transform: translate(-50%, 50%)"
                                                         alt="Honor" class="honor position-absolute">
                                                @elseif($user['user_vip'] == 1)
                                                    <img src="{{ asset('frontend/images/user_vip.png') }}"
                                                         height="15px" width="auto"
                                                         style="bottom: 0; left: 50%; transform: translate(-50%, 50%)"
                                                         alt="Honor" class="honor position-absolute">
                                                @endif
                                                @switch($key)
                                                    @case(0)
                                                    <img src="{{ asset('frontend/images/first-rank.svg') }}" height="40px" width="40px"
                                                         style="top: 0; left: 0; transform: translate(-50%, -50%) scale(1.6)"
                                                         alt="Rank" class="position-absolute high-rank">
                                                    @break
                                                    @case(1)
                                                    <img src="{{ asset('frontend/images/second-rank.svg') }}" height="40px" width="40px"
                                                         style="top: 0; left: 0; transform: translate(-50%, -50%) scale(1.6)"
                                                         alt="Rank" class="position-absolute high-rank">
                                                    @break
                                                    @case(2)
                                                    <img src="{{ asset('frontend/images/third-rank.svg') }}" height="40px" width="40px"
                                                         style="top: 0; left: 0; transform: translate(-50%, -50%) scale(1.6)"
                                                         alt="Rank" class="position-absolute high-rank">
                                                    @break
                                                    @default
                                                    <p class="position-absolute">{{ $key + 1 }}.</p>
                                                @endswitch
                                            </div>
                                            <div class="d-flex flex-column text-right" style="margin-left: 15px">
                                                <p class="font-weight-bold"
                                                   style="line-break: anywhere;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;">
                                                    {{ $user['name'] }}
                                                </p>
                                                <p>{{ $user['text'] }}</p>
                                            </div>
                                        </a>
                                    </li>
                            @endforeach
                            @else
                                <li class="text-white text-center px-2 mb-3">
                                    <h3>Comming soon</h3>
                                </li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection
