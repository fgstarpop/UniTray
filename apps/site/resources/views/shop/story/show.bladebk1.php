@extends('shop.layouts.app')
@section('title')
    {{ $story->name }} @if (!empty(setting('store_name')))
        -
    @endif
    {{ setting('store_name') }}
    @if (!empty(setting('store_slogan')))
        -
    @endif
    {{ setting('store_slogan') }}
@endsection
@section('seo')
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <meta name="title" content="{{ $story->name }}">
    <meta name="description" content="{{ $story->meta_description }}">
    <meta name="keywords" content="{{ $story->meta_keywords }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $story->meta_title }}">
    <meta property="og:description" content="{{ $story->meta_description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="giangthe.com">
@stop
@push('styles')
    <style>
        .input-group {
            padding: 0px;
        }

        .input-group .form-control {
            padding: 5px;
            height: 39px;
        }

        .input-group .form-control:focus {
            border-color: unset;
            box-shadow: none;
            border: 1px solid #ced4da;
        }

        .tt-box-chapter {
            max-height: 244px;
            overflow: hidden;
            padding: 8px 0px;
        }

        .more-list-chapter {
            text-align: center;
            font-size: 16px;
            padding: 53px 23px;
            position: relative;
            cursor: pointer;
            height: 38px;
            margin-top: 3px;
        }

        .tt-box-list-chapter .more:before {
            /*background-color: rgba(255, 255, 255, .8);*/
            display: block;
            content: "";
            height: 35px;
            position: absolute;
            top: -35px;
            width: 100%;
            left: 0;
        }

        .tt-box-list-chapter .tt-box-chapter.expand {
            height: initial;
            max-height: initial;
        }
    </style>
@endpush
@section('content')
    <section class=" fs-6 bg-story pb-5">
        <div class="container px-md-4 px-sm-0 px-0 bg-main-story">
            @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                <div style="position: relative">
                    <div
                        style="background: url({{ $story->avatar ?? $story->getFirstMediaUrl('default') }});background-size: cover;height: 450px;filter: blur(8px);position: absolute;width: 100%">
                    </div>
                </div>
                <div class="container" style="padding-top: 70px">
                    <div class="row">
                        <div class="col" style="z-index:100">
                            <center><img class="img-story"
                                    src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}"></center>
                        </div>
                        <div class="col-xl-12 pt-5">
                            <center>
                                <span id="book_name2" class="cap story-name" style="color:#004c1f">
                                    {{ ucfirst($story->name) }} </span>
                                <br>
                                <span class="cap pt-5"><i class="fa fa-user"></i> {{ $story->author }}</span>
                            </center>
                        </div>
                    </div>
                </div>
            @endif
            @if ((new \Jenssegers\Agent\Agent())->isMobile())
                @foreach ($story->chapters as $chapter)
                    @php
                        $lastestChap = 0;
                        if (isset($chapter['order'])) {
                            if ($chapter['order'] > $lastestChap) {
                                $lastestChapName = $chapter['name'];
                                $lastestChap = $chapter['order'];
                            }
                        }
                    @endphp
                @endforeach
                <div class="container" style="margin-bottom:15px;">
                    <div class="row">
                        <div class="col-4" style="z-index:100">
                            <center><img class="img-story-new-mobile"
                                    src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}"></center>
                        </div>
                        <div class="col-8 p-0">
                            <span id="book_name2" class="cap new-story-name-mobile "> {{ ucfirst($story->name) }} </span>
                            <br>
                            <span class="cap mt-2" style="display: inline-block; font-size:15px;"><i class="fa fa-user"></i>
                                {{ isset($story->mod->name) ? $story->mod->name : 'Chưa có Mod' }}</span>
                            <br>
                            <div class="row mt-2">
                                <div class="col-3"
                                    style="justify-content: flex-end;
                                display: flex;
                                flex-direction: column;">
                                    <span class="cap new-chap-label-mobile"> Mới</span>
                                </div>
                                <div class="col-9 p-0"> <span class="cap story-title-with-overflow">
                                        {{ $lastestChapName }}</span> </div>
                            </div>
                            <br>
                            <span class="cap"
                                style="color:orange; display: inline-block;margin-top: -17px !important; font-size: 14px;">
                                Thời gian: {{ \Carbon\Carbon::parse($story->chapter_updated) }} </span>
                        </div>
                    </div>
                </div>
            @endif
            @if ((new \Jenssegers\Agent\Agent())->isMobile())
                <div class="row" style="padding-left: 20px;">
                    <div class="col story-mobile-tag">
                        <p style="">
                            @if (!empty($story->categories) && count($story->categories) > 0)
                                @foreach ($story->categories as $key => $category)
                                    @if ($key == 0)
                                    {{ $category->name }} @else, {{ $category->name }}
                                    @endif
                                @endforeach
                            @else
                                Không có
                            @endif
                        </p>
                    </div>
                    <div class="col story-mobile-tag">
                        <p style="">
                            @if (!empty($story->types) && count($story->types) > 0)
                                @foreach ($story->types as $type)
                                    {{ $type->name }}
                                @endforeach
                            @else
                                Không có
                            @endif
                        </p>
                    </div>
                    <div class="col story-mobile-tag">
                        <p style="">
                            @foreach (\App\Domain\Story\Models\Story::STATUS as $key => $status)
                                @if ($story->status == $key)
                                    {{ $status }}
                                @endif
                            @break
                        @endforeach
                    </p>
                </div>
                <br>
            </div>

        @endif
        @if ((new \Jenssegers\Agent\Agent())->isDesktop())
            <div class="mt-4 mb-2 border-top-radius">
                <div class="row justify-content-md-center bg-white" style="margin: 0">
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span><i class="fa fa-eye"></i><br>{{ number_format($story->view) }} lượt xem</span>
                    </div>
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span><i class="fa fa-lg fa-thumbs-up"></i><br><span
                                id="whishlist">{{ number_format($story->whishlist_count) }}</span> lượt like</span>
                    </div>
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span id="bookstatus"><i class="fa fa-star-half"></i><br>Còn tiếp</span>
                    </div>
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span><i class="fa fa-rss"></i><br><span id="follow_story">
                                {{ number_format($story->follow_count) }}</span> Theo dõi</span>
                    </div>
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span><i class="fas fa-gift"></i><br><span id="follow_story">
                                {{ number_format((int) $story->donate) }}</span> vàng</span>
                    </div>
                    <div class="col-4 p-2 text-center col-lg-2">
                        <span><i class="fa fa-award"></i><br><span id="follow_story">
                                {{ number_format((int) $story->nomination) }}</span> Đề cử tuần</span>
                    </div>
                </div>
            </div>
        @endif
        @if ((new \Jenssegers\Agent\Agent())->isMobile())
            <div class="mt-2 mb-2 border-top-radius">
                <div class="row bg-white" style="margin: 0">
                    <div class="col-3 p-2 text-center col-lg-2">
                        <span class="new-menu-mobile">
                            @if ($story->view > 999999)
                                {{ intval($story->view / 100000) }}M
                            @else
                                {{ $story->view }}
                            @endif
                        </span> <br> <span style="font-size:15px;">Lượt đọc </span>
                    </div>
                    <div class="col-3 p-2 text-center col-lg-2">
                        <span><span id="follow_story" class="new-menu-mobile">
                                {{ number_format($story->follow_count) }}</span> <br> <span
                                style="font-size:15px;">Theo dõi </span></span>
                    </div>
                    <div class="col-3 p-2 text-center col-lg-2">
                        <span><span id="follow_story" class="new-menu-mobile">
                                {{ number_format((int) $story->nomination) }}</span> <br> <span
                                style="font-size:15px;">Đề cử tuần </span></span>
                    </div>
                    <div class="col-3 p-2 text-center col-lg-2">
                        <span><span id="follow_story" class="new-menu-mobile">
                                @if ($story->donate > 999 && $story->donate <= 999999)
                                    {{ intval($story->donate / 1000) }}K
                                @else
                                    {{ $story->donate }}
                                @endif
                            </span>
                            <br> <span style="font-size:15px;">Thưởng </span></span>
                    </div>
                </div>
            </div>
        @endif
        @if ((new \Jenssegers\Agent\Agent())->isMobile())
            <div class="row new-mobile-top-nomination" style="font-size:15px;">
                <div class="col-9 p-2 col-lg-2" style="margin-left: 5px;">
                    <span class="new-menu-mobile"> <i class="fa fa-award"></i> Top Đề Cử Tuần</span>
                </div>
                <div class="col-2 p-2 col-lg-2" style="margin-left: auto;">
                    <span class="">
                        @foreach ($listStoryRank as $index => $StoryRank)
                            @if ($story->id == $StoryRank->id)
                                @php
                                    $top = 'Top ' . $index + 1;
                                @endphp
                            @break

                        @else
                            @php
                                $top = 'Không';
                            @endphp
                        @endif
                    @endforeach
                    {{ $top }}
                </span>
            </div>
        </div>
        <hr style="height: 8px; background: #bdbdbd; margin-top: 0px;">
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isDesktop())
        <div class="bg-white" style="font-weight:500">
            <div class="p-1"><i class="fa fa-info-circle"></i> Tóm tắt truyện</div>
            <div class="p-2 bt">
                {!! $story->description !!}
            </div>
        </div>
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isMobile())
        <div class="bg-white">
            <div class="p-2" style="color:rgb(25, 203, 25); font-weight:500; font-size: 15px;"
                data-bs-toggle="modal" data-bs-target="#source-information"> Thông tin truyện gốc</div>
            <div class="p-2 bt now-less" id="mobile-story-des" style="color: gray; overflow: hidden;">
                {!! $story->description !!}
            </div>
            <div class="modal fade" id="source-information" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="border-bottom: 1px dashed #dee2e6;">

                            <h6 class="modal-title"
                                style="line-height: 0; font-weight:bold; color:black; font-size:18px; margin-left: auto;">
                                Thông tin truyện gốc</h6>
                            <button
                                style="padding: 0rem 1rem; background:none; color:black; font-weight: 600; opacity: 15;"
                                type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">Hủy</button>
                        </div>
                        <div class="modal-body" style="color: black;">
                            <p style="font-weight:450; font-size:15px;">&#x2022; Tên gốc:
                                {{ $story->name_chines }}</p>
                            <p style="font-weight:450; font-size:15px;">&#x2022; Tác giả: {{ $story->author_vi }}
                            </p>
                            <p style="font-weight:450; font-size:15px; display:inline-block;">&#x2022; Link gốc: <a
                                    style="display: inline-block;
                                    color: #007a2b; font-weight: 450; font-size: 15px;"
                                    href="{{ $story->origin }}">{{ $story->origin ?? 'Sáng tác' }} </a> </p>
                            <p style="font-weight:450; font-size:15px;">&#x2022; Nhập thời:
                                {{ $story->created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <?php
    $tags = json_decode($story->tags, 1);
    $last_tag = null;
    if (!empty($tags)) {
        $last_tag = end($tags);
    }
    ?>
    @if ((new \Jenssegers\Agent\Agent())->isMobile())
        <div class="row" style="padding-left: 9px; padding-top: 12px;">
            @if (!empty($tags))
                @foreach ($tags as $tag)
                    <div style="float: left; max-width: fit-content; margin-right: -16px;">
                        <p style="background: #d9d9d9; color: gray; border-radius: 4px; font-size: 14px;">
                            {!! $tag !!}</p>
                    </div>
                @endforeach
            @endif
            <div id="read-more-des"
                style="max-width: fit-content;
                    margin-left: auto;
                    padding-right: 32px;">
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isDesktop())
        <div class="bg-white mt-2" style="font-size: 14px;">
            <div class="p-1 bt"><i class="fa fa-info-circle"></i> Thông tin</div>
            <div class="p-1 bt">Tên gốc: <span id="oriname">{{ $story->name_chines }}</span></div>
            <div class="p-1 bt">Hán việt: {{ $story->name }} </div>
            <div class="p-1 bt">Tác giả: {{ $story->author_vi }}</div>
            <div class="p-1 bt">Thể
                loại: @if (!empty($story->categories))
                    @foreach ($story->categories as $category)
                        {{ $category->name }}
                    @endforeach
                @endif
            </div>
            @if (strpos(url()->current(), 'truyenvipfaloo'))
                <div class="p-1 bt">Nguồn truyện: <a class="text-success"
                        href="{{ $story->origin ?? '#' }}">{{ $story->origin ?? 'Sáng tác' }}</a>
                </div>
            @endif
            <div class="p-1 bt">Loại
                truyện: @if (!empty($story->types))
                    @foreach ($story->types as $type)
                        {{ $type->name }}
                    @endforeach
                @endif
            </div>
            @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                <div class="p-1 bt">
                    Tags: @if (!empty($tags))
                        @foreach ($tags as $tag)
                            {!! $tag !!} @if ($tag != $last_tag)
                                ,
                            @endif
                        @endforeach
                    @else
                        Không có
                    @endif
                </div>
            @endif
            <div class="p-1 bt">Nhập thời: {{ $story->created_at }}</div>
        </div>
    @endif
    @if (!empty(currentUser()->id))
        @php
            $userOders = \App\Domain\Admin\Models\Order::where('user_id', currentUser()->id)
                ->where('story_id', $story->id)
                ->get()
                ->collect();
        @endphp
        @if ((new \Jenssegers\Agent\Agent())->isMobile())
            <hr style="height: 8px; background: #bdbdbd; margin-top: 0px;">
        @endif
        <div class="bg-white mt-2">
            @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                <div class="p-1"><i class="fas fa-circle"></i> Thao tác</div>
            @endif
            <div class="row text-center bt pt-2"
                @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-weight: bold; color: #766767;" @endif>
                <div class="col-3 col-lg-3">
                    @if ($story->chapters)
                        @foreach ($story->chapters as $chapter)
                            @php
                                $filteredNumbers = array_filter(preg_split('/\D+/', @$chapter['name']));
                                $firstOccurence = reset($filteredNumbers);
                            @endphp
                            @if ($firstOccurence == 1 || $firstOccurence == 01)
                                @php
                                    $linkStoryFirst = @$chapter['embed_link'] ? @$chapter['link_other'] ?? route('chapters.show', [$story->id, 'link' => @$chapter['embed_link']]) : @$chapter['link_other'] ?? route('chapters.show', [$story->id, 'id' => $chapter['id']]);
                                @endphp
                                <span id="readnowbtn"><a
                                        @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="color: #766767; font-size:16px;" @endif
                                        href="{{ $linkStoryFirst }}"> <i class="fa fa-eye"></i> <br>Đọc
                                        ngay</a></span>
                            @break
                        @endif
                    @endforeach
                    @if ($firstOccurence != 1 && $firstOccurence != 01)
                        @php
                            $linkStoryFirst = @$story->chapters[0]['embed_link'] ? @$story->chapters[0]['link_other'] ?? route('chapters.show', [$story->id, 'link' => @$story->chapters[0]['embed_link']]) : @$story->chapters[0]['link_other'] ?? route('chapters.show', [$story->id, 'id' => $story->chapters[0]['id']]);
                        @endphp
                        <span id="readnowbtn"><a
                                @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="color: #766767; font-size:16px;" @endif
                                href="{{ $linkStoryFirst }}"> <i class="fa fa-eye"></i> <br>Đọc
                                ngay</a></span>
                    @endif
                @endif
            </div>
            @if ((new \Jenssegers\Agent\Agent())->isMobile())
                <div class="col-3 col-lg-3" id="audio">
                    <span><i class="fa fa-headphones"></i><br><span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif> Nghe audio
                        </span></span>
                </div>
            @endif
            @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                <div class="col-3 col-lg-3" id="like">
                    <span><i class="fa fa-thumbs-up"></i><br> <span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Thích
                        </span></span>
                </div>
            @endif
            <div class="col-3 col-lg-3" id="follow">
                @php
                    $followCheck = (new \App\Domain\Activity\Follow())
                        ::where('user_id', currentUser()->id)
                        ->where('stories_id', $story->id)
                        ->first();
                @endphp
                @if (!@$followCheck)
                    <span><i class="fa fa-heart"></i><br><span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Theo dõi
                        </span></span>
                @else
                    <span> <img src="{{ asset('frontend/images/black-heart.png') }}"
                            alt="{{ setting('store_name', 'Giangthe.com') }}"
                            style="width: 22px; margin-top: -4px;"><br><span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Bỏ Th.dõi
                        </span></span>
                @endif
            </div>
            @if ($story->mod_id != null)
                <div class="col-3 col-lg-3" data-bs-toggle="modal" data-bs-target="#donate">
                    <span><i class="fas fa-gift"></i><br><span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif
                            id="follow_story">Thưởng @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                                truyện
                            @endif
                        </span>
                    </span>
                </div>
            @endif
            @php
                $linkAddMoreStory = "/$story->id/index";
                $linkEditStory = "/truyen/sua-truyen/$story->id";
            @endphp
            @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                <div class="col-3 col-lg-3 mt-4" id="nominations">
                    <span><i class="fa fa-ticket"></i><br> <span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif> Đề cử
                        </span></span>
                </div>
            @endif
            @if ($story->mod_id == null && currentUser()->is_vip == 1)
                <div class="col-3 col-lg-3 mt-4" id="nominations">
                    <span onclick="getStory('{{ $story->id }}','{{ currentUser()->id }}')"><i
                            class="fa fa-flag" aria-hidden="true"></i><br> <span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Nhận truyện
                        </span></span>
                </div>
            @endif
            @if ((new \Jenssegers\Agent\Agent())->isMobile())
                @if (!empty(currentUser()->id))
                    <div class="col-3 col-lg-3 mt-4" data-bs-toggle="modal" data-bs-target="#buy_chapters">
                        <span><i class="fa fa-shopping-cart" aria-hidden="true"></i><br><span
                                style="font-size:16px;" id="buy_chapters_button">Mua nhiều</span> </span>
                    </div>
                @endif
            @endif
            @if (currentUser()->id == $story->mod_id)
                <div class="col-3 col-lg-3 mt-4" id="leave-story">
                    <span onclick="leaveStory('{{ $story->id }}','{{ $story->mod_id }}')"><i
                            class="fa fa-sign-out"></i><br><span
                            @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif> Rời truyện
                        </span></span>
                </div>
            @endif
            @if (currentUser()->id == 4 || currentUser()->id == $story->mod_id)
                <div class="col-3 col-lg-3 mt-4" id="up-story">
                    <span><a @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="color: #766767;" @endif
                            href="{{ $linkAddMoreStory }}"><i class="fa fa-sign-in"></i><br><span
                                @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Đăng tiếp
                            </span></a></span>
                </div>
                <div class="col-3 col-lg-3 mt-4" id="edit-story">
                    <span><a @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="color: #766767;" @endif
                            href="{{ $linkEditStory }}"><i class="fa fa-pencil"></i><br><span
                                @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Sửa
                                truyện</span></a></span>
                </div>
                @if ($story->mod_id !== null && currentUser()->id == 4 && currentUser()->id !== $story->mod_id)
                    <div class="col-3 col-lg-3 mt-4" id="nominations">
                        <span onclick="leaveStory('{{ $story->id }}','{{ $story->mod_id }}')"><b><i
                                    class="fa fa-user-times" aria-hidden="true"></i><br> <span
                                    @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="font-size:16px;" @endif>Loại bỏ
                                    mod truyện </span></b></span>
                    </div>
                @endif
            @endif
            <div class="modal fade" id="donate" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Thưởng truyện</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="" style="text-align:left">
                                    @csrf
                                    <div class="error text-danger"></div>
                                    <p>Số vàng bạn hiện có</p>
                                    <div class="position-relative">
                                        <input type="text"
                                            value="{{ number_format((int) $wallet->gold) }}" readonly
                                            class="wallet form-control"
                                            data-wallet="{{ (int) $wallet->gold }} ">
                                    </div>
                                    <p class="mt-3">Số vàng muốn thưởng</p>
                                    <p><input class="form-control gold" value="0" name="gold"
                                            type="text" placeholder="Nhập số vàng muốn tặng"></p>

                                    <p class="text-danger">*Thưởng ít nhất 1000 vàng để ủng hộ người làm truyện
                                    </p>
                                    <p>Kiểm tra lại số vàng sẽ thưởng:</p>
                                    <div class="position-relative">
                                        <input type="text" value="0" readonly
                                            class="form-control request">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-gt gift">Tặng ngay
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @if ((new \Jenssegers\Agent\Agent())->isMobile())
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header"
                                style="flex-direction: column-reverse; border-bottom: 1px dashed #dee2e6;">
                                <h6 class="modal-title"
                                    style="line-height: 0; font-weight:bold; color:black; font-size:18px;">
                                    Thưởng truyện</h6>
                                <button
                                    style="padding: 0rem 1rem; background:none; color:black; font-weight: 600; opacity: 15;"
                                    type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">Hủy</button>
                            </div>
                            <div class="modal-body" style="color: black;">
                                <form method="post" action="" style="text-align:left">
                                    @csrf
                                    <p style="font-weight:500;">- Thưởng truyện là hình thức cho những Mod đã
                                        bỏ công sức của mình chỉnh sửa, edit name cho các bạn nghe những chương
                                        truyện không tỳ vết <br> - Nếu thấy Mod xứng đáng thì hãy thưởng họ một
                                        chút nhé. Khi thưởng truyện thì bạn cũng nhận được một phần quà tương
                                        ứng.</p>
                                    <p class="mt-3">Số vàng muốn thưởng:</p>

                                    <p>
                                    <div class="tgl-radio-tabs"
                                        style="display: flex;
                                                width: 100%;
                                                justify-content: space-between;">
                                        <input id="x" value="5000" type="radio"
                                            class="form-check-input tgl-radio-tab-child" name="gold"><label
                                            for="x" class="radio-inline">5K</label>
                                        <input id="y" value="10000" type="radio"
                                            class="form-check-input tgl-radio-tab-child" name="gold"><label
                                            for="y" class="radio-inline">10K</label>
                                        <input id="z" value="20000" type="radio"
                                            class="form-check-input tgl-radio-tab-child" name="gold"><label
                                            for="z" class="radio-inline">20K</label>
                                        <input id="k" value="50000" type="radio"
                                            class="form-check-input tgl-radio-tab-child" name="gold"><label
                                            for="k" class="radio-inline">50K</label>
                                        <input id="t" value="100000" type="radio"
                                            class="form-check-input tgl-radio-tab-child" name="gold"><label
                                            for="t" class="radio-inline">100K</label>
                                    </div>
                                    </p>
                                    <p class="text-danger not-enought-gold" style="display:none;">Bạn không đủ
                                        vàng để tặng</p>
                                    <p style="display: inline-block;">Quà tương ứng:</p> <span
                                        class="bonus-gift" style="font-weight:500;"> Cộng 500 phiếu đề cử vào
                                        truyện </span>
                                    <div class="modal-footer"
                                        style="border-top:none;display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-gt gift"
                                            data-wallet="{{ (int) $wallet->gold }} ">Tặng ngay
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="modal fade" id="buy_chapters" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="flex-direction: column-reverse;">
                            <h6 class="modal-title"
                                style="line-height: 0; font-weight:bold; color:black; font-size:18px;">Mua
                                nhiều chương</h6>
                            <button
                                style="padding: 0rem 1rem; background:none; color:black; font-weight: 600; opacity: 15;"
                                type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">Hủy</button>
                        </div>
                        <div class="row"
                            style="background: #edeaea;
                       height: 43px;
                       display: flex;
                       flex-direction: column;
                       width: 100%;
                       justify-content: center;
                       margin-left: 0; font-size: 16px;">
                            <div class="col-8" style="text-align: left; padding-top: 15px;">
                                <p> Chương vip hiện chưa có </p>
                            </div>
                            <div class="col-4"
                                style="display: flex;
                                flex-direction: row;
                                justify-content: right;">
                                <button type="button" class="btn btn-gt buy-chaps-button"
                                    data-wallet="{{ (int) $wallet->gold }} "><i class="fa fa-shopping-cart"
                                        aria-hidden="true"></i> Mua </button>
                            </div>

                        </div>
                        <div class="modal-body"
                            style="color: black; color: black; font-weight: 500; font-size: 15px;">
                            <form method="post" action="" style="text-align:left">
                                @csrf
                                <div class="row" style="overflow-y: scroll; height: 395px;">

                                    @foreach ($story->chapters as $index => $chapter)
                                        @if (@$chapter['is_vip'] == 1)
                                            {{-- !\App\Domain\Admin\Models\Order::where(['chapter_id'=>@$chapter['id'],
                                           'user_id' => currentUser()->id
                                           ])->first() && --}}
                                            @if (!$userOders->firstWhere('chapter_id', $chapter['id']) && !currentUser()->user_vip == 1)
                                                <div class="col-9"
                                                    style="display: flex; margin-bottom: 10px;">
                                                    @php
                                                        $price = number_format($chapter['price']);
                                                        $dataChapters = $chapter['id'] . ',' . $story->id . ',' . $price . ',' . $story->mod_id;
                                                    @endphp
                                                    <input type="checkbox" id="chap-{{ $index }}"
                                                        name="chap-{{ $index }}"
                                                        value="{{ $dataChapters }}"
                                                        class="order-many checkbox-round">
                                                    <label
                                                        style="white-space: nowrap;
                                        width: 240px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;"
                                                        for="chap-{{ $index }}">
                                                        {{ $chapter['name'] }}</label>
                                                </div>
                                                <div class="col-3" style="margin-bottom: 10px;">
                                                    @php
                                                        $price = number_format($chapter['price']);
                                                    @endphp
                                                    <label for="chap-{{ $index }}"> 120 vàng</label>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                <p class="text-danger not-enought-gold not-enough-gold-chap"
                                    style="display:none;">Bạn không vàng để mua chương, số dư của bạn là
                                    {{ (int) $wallet->gold }}</p>
                            </form>
                        </div>
                        <div class="row"
                            style="    background: #0bb948;
                       color: white;
                       height: 43px;
                       width: 100%;
                       margin-left: 0;
                       font-weight:500;
                       font-size: 16px;">
                            <div class="col-5" style="text-align: left; padding-top: 9px;">
                                <input type="checkbox" id="chap-all" name="" value=""
                                    class="checkbox-round">
                                <label for="chap-all"> Chọn tất cả</label>
                            </div>
                            <div class="col-7"
                                style="display: flex;
                                flex-direction: row;
                                justify-content: right;
                                padding-top: 9px;">
                                <span id="show-chuong-vang"> 0 chương - 0 vàng </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--
<div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">Thưởng truyện</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="" style="text-align:left">
                @csrf
                <div class="error text-danger"></div>
                <p>Hôm nay bạn có 1 điểm thưởng miễn phí</p>
                <p>Số vàng bạn hiện có</p>
                <div class="position-relative">
                    <input type="text" value="{{ number_format((int) $wallet->gold) }}" readonly
                           class="wallet form-control">
                </div>
                <p>Số điểm thưởng muốn tặng?</p>
                <div class="position-relative">
                    <input type="text" value="1" readonly class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gt gifts">Tặng ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
-->
        </div>
    </div>
@endif
@if ((new \Jenssegers\Agent\Agent())->isDesktop())
    <div class="p-1 bg-white mt-2">
        <div>
            Người Nhúng
        </div>
        <div class="bt">
            <a href="{{ $story->user_id ? route('user.index', $story->user_id) : '' }}" class="d-flex">
                <div class="position-relative">
                    @if ($story->user && @$story->user->avatar)
                        <img src="{{ pare_url_file($story->user->avatar, 'user') }}" class="img-user">
                    @else
                        <img src="frontend/images/no-user.png" class="img-user">
                    @endif
                </div>
                <span style="padding: 10px 0px;">{{ $story->user->name ?? 'Chưa có' }}</span>
                @if ($story->user->is_vip)
                    <span style="padding: 10px 0px">- Mod làm truyện</span>
                @endif
            </a>
        </div>
    </div>
@endif
<div class="p-1 bg-white mt-2">
    @if ((new \Jenssegers\Agent\Agent())->isDesktop())
        <div>
            <i class="fa fa-list"></i> Mục lục
            {{--                    <span style="float: right;color: gray"> --}}
            {{--                        <i class="fa fa-retweet"></i> Cập nhật</span> --}}
            {{--                    <span style="float: right;color: gray;margin-right: 8px"> --}}
            {{--                        <a href="#clicktoexp" style="color: gray;"><i class="fa fa-chevron-down"></i> Xuống</a></span> --}}
        </div>
        @if (strpos(url()->current(), 'truyenvipfaloo'))
            <div class="bt p-1" style="font-size: 15px;">
                Nguồn:
                <span style="border-bottom: 2px solid green">{{ $story->origin ?? 'Sáng tác' }}:
                    {{ $story->chapters_count }} chương</span>
                {{-- <a href="#">aikanshuba(96) </a> --}}
            </div>
        @endif
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isMobile())
    <hr style="height: 16px; background: #eae4d3; margin-top: 0px;">
        <div style="color: red; text-align: center;">
            <h7 class="title">
            <strong>  Đây là quảng cáo </strong>
            @include('shop.layouts.partials.ads')
        </div>
    <hr style="height: 16px; background: #eae4d3; margin-top: 0px;">
        <div>
            <img src="{{ asset('frontend/images/bannermuavip.png') }}"
                alt="{{ setting('store_name', 'Giangthe.com') }}" style=" width: 100%;">
        </div>
    @endif
    @if ((new \Jenssegers\Agent\Agent())->isMobile())
        <div class="row mb-2">
            <div class="col-7 pt-3" style="margin-left: 5px;font-weight:bold; font-size: 16px;">
                Danh sách chương
            </div>

            <div class="bt p-1 col-4" style="font-size: 15px;">
                <div class="choose-source-mobile" data-bs-toggle="modal" data-bs-target="#source-change"
                    data-current="{{ $story->id }}">
                    <a>
                        Nguồn: @foreach ($storySources as $index => $source)
                            @if ($source->id == $story->id)
                                {{ $index + 1 }}
                            @endif
                        @endforeach {{ '(' . count($story->chapters) . ')' }}
                        <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>
            <div class="modal fade" id="source-change" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" style="font-size: 15px;">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header" style="border-bottom: 1px dashed #dee2e6;">

                            <button
                                style="padding: 0rem 1rem; background:none; color:black; font-weight: bold; opacity: 15; margin: 0;"
                                type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">Hủy</button>
                            <h6 class="modal-title" style="line-height: 0; font-weight:bold; color:black;">
                                Chọn nguồn</h6>
                            <button
                                style="padding: 0rem 1rem; background:none; color:black; font-weight: bold; opacity: 15;margin: 0;"
                                type="button" class="btn-close"><a id="redirect-new-source"
                                    href="/truyen/{{ $story->id }}">Gửi</a></button>
                        </div>
                        <div class="modal-body" style="color: black;">
                            <form method="post" action="" style="text-align:left">
                                @csrf
                                @foreach ($storySources as $index => $storySource)
                                    <div class="row">
                                        <div class="col-1"
                                            style="text-align: right;
                                                padding-top: 4px;">
                                            <input type="radio" id="{{ 'source-' . $index + 1 }}"
                                                name="source_choose" value="{{ $storySource->id }}">
                                        </div>
                                        <div class="col-11"
                                            style="font-size: 15px;
                                                font-weight: 500;
                                                padding: 0;">
                                            <label for="{{ 'source-' . $index + 1 }}">Nguồn
                                                {{ $index + 1 }}:
                                                {{ $storySource->host }}</label><br>
                                            <label for="{{ 'source-' . $index + 1 }}">- Mod làm truyện:
                                                {{ isset($storySource->mod->name) ? $storySource->mod->name : 'Chưa có Mod' }}</label><br>
                                            <label for="{{ 'source-' . $index + 1 }}">- Số chương:
                                                {{ $storySource->count_chapters }} chương chữ</label><br>
                                            <label for="{{ 'source-' . $index + 1 }}">- Đánh giá: Chưa
                                                có</label>
                                        </div>

                                    </div>
                                    <br>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div>
        <div class="pt-2 pb-2 tt-box-list-chapter">
            <div class="row tt-box-chapter"
                @if ((new \Jenssegers\Agent\Agent())->isMobile()) style="border-top: dashed 2px #ebe7e7; border-bottom: dashed 2px #ebe7e7;" @endif>
                <?php $readed = true; ?>
                @php
                    $check = 0;
                    $time = 0;
                    if (currentUser()) {
                        foreach (\App\Enums\UserState::Admin as $list) {
                            if (currentUser() && $list === currentUser()->username) {
                                $check = 1;
                            }
                        }
                        if ($story->mod_id == currentUser()->id) {
                            $check = 1;
                        }
                    }
                @endphp
                @if ($story->chapters)
                    @foreach ($story->chapters as $chapter)
                        @if ((@$chapter->timer && @$chapter->timer <= date('Y-m-d H:i:s')) || !@$chapter->timer)
                            @php
                                if (currentUser()) {
                                    if ($story->type == 1) {
                                        if (!$chapLastReaded) {
                                            $readed = false;
                                        }
                                        if ($chapterLastReaded != '' && $chapterLastReaded == $chapter['order']) {
                                            $link = 'chaplastreaded';
                                            $readed = false;
                                        } elseif ($readed) {
                                            $link = 'chapreaded';
                                        } else {
                                            $link = '';
                                        }
                                    } else {
                                        if ($chapterLastReaded == @$chapter['order']) {
                                            $link = 'chaplastreaded';
                                        } elseif ($chapterLastReaded < @$chapter['order']) {
                                            $link = '';
                                        } else {
                                            $link = 'chapreaded';
                                        }
                                    }
                                }
                                $linkStory = @$chapter['embed_link'] ? @$chapter['link_other'] ?? route('chapters.show', [$story->id, 'link' => @$chapter['embed_link']]) : @$chapter['link_other'] ?? route('chapters.show', [$story->id, 'id' => $chapter['id']]);
                            @endphp
                            @if (!empty(currentUser()->id))
                                @if (@$chapter['is_vip'] == 1)
                                    {{-- \App\Domain\Admin\Models\Order::where(['chapter_id'=>@$chapter['id'],
                                                'user_id' => currentUser()->id
                                                ])->first() ||  --}}

                                    @if ($userOders->firstWhere('chapter_id', $chapter['id']) || $check || currentUser()->user_vip == 1)
                                        <div class="col-md-4">
                                            <div class="tt-chapter {{ @$link }}"
                                                @if (@$link == 'chaplastreaded') id="last_readed" @endif>
                                                <a href="{{ $linkStory }}" class="text-success"
                                                    title="{{ $chapter['name'] }}">
                                                    <i class="fal fa-check"></i> {{ @$chapter['name'] }}
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4">
                                            <div class="tt-chapter {{ @$link }}"
                                                @if (@$link == 'chaplastreaded') id="last_readed" @endif>
                                                <a href="javascript:;" data-url="{{ $linkStory }}"
                                                    data-story="{{ $story->id }}"
                                                    data-chapter="{{ @$chapter['id'] }}"
                                                    data-chapter-name="{{ @$chapter['name'] }}"
                                                    data-author="{{ $story->mod_id }}"
                                                    data-price="{{ number_format(@$chapter['price']) }}"
                                                    class="text-success order"
                                                    title="{{ $chapter['name'] }}">
                                                    <i class="fa fa-lock"></i> {{ $chapter['name'] }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-md-4">
                                        <div class="tt-chapter {{ @$link }}"
                                            @if (@$link == 'chaplastreaded') id="last_readed" @endif>
                                            <a href="{{ $linkStory }}" class="text-success"
                                                title="{{ $chapter['name'] }}"> {{ @$chapter['name'] }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if (@$chapter['is_vip'] != 1)
                                    <div class="col-md-4">
                                        <div class="tt-chapter {{ @$link }}"
                                            @if (@$link == 'chaplastreaded') id="last_readed" @endif>
                                            <a href="{{ $linkStory }}" class="text-success "
                                                title="{{ $chapter['name'] }}">
                                                {{ $chapter['name'] }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <div class="tt-chapter">
                                            <a href="{{ @$linkStory }}" class="text-success"
                                                title="{{ $chapter['name'] }}">
                                                <i class="fa fa-lock"></i> {{ $chapter['name'] }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="text-center text-dark">
                <div class='row @if ((new \Jenssegers\Agent\Agent())->isMobile()) pt-3 @endif'
                    style="display: flex; flex-direction: row; justify-content: center;">
                    @if (currentUser() &&
                            $story->type == 1 &&
                            $story->complete_free == \App\Domain\Story\Models\Story::COMPLETE_FREE_INACTIVE)
                        <div class="col-6">
                            <div style="background-color:#f2f2f2; cursor: pointer;"
                                class="pt-2 pb-2 mt-1 updateEmbedStory">Cập nhật chương mới @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                                    <i class="fa fa-sync-alt"></i>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="col-6">
                        <div id="clicktoexp" style="background-color:#f2f2f2;"
                            class="pt-2 pb-2 more-list-chapter more">
                            Xem thêm
                        </div>
                    </div>
                </div>
                @if ((new \Jenssegers\Agent\Agent())->isDesktop())
                    <span>Chương mới
                        :{{ \Carbon\Carbon::parse($story->chapter_updated)->diffForHumans(\Carbon\Carbon::now()) }}</span>
                @endif
            </div>
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

@include('shop.layouts.comment', [
    'type' => 'App/Domain/Chapter/Models/Story',
    'id' => $story->id,
    'author' => $story->mod_id,
])
</div>
</section>
@endsection
<script>
    function getStory(storyID, modID) {
        var url = "{{ route('getStory') }}";
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: storyID,
                modID: modID,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                toastr.success(res.message, 'Thành Công');
                location.reload();
            }
        })
    }

    function leaveStory(storyID, modID) {
        var url = "{{ route('leaveStory') }}";
        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: storyID,
                modID: modID,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                toastr.success(res.message, 'Thành Công');
                location.reload();
            }
        })
    }
</script>
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('input[type=radio][name=gold]').change(function() {
            var bonus = this.value / 10;
            $(".bonus-gift").html(`Cộng ${bonus} phiếu đề cử vào truyện`)
        });

        $('#source-change').on('show.bs.modal', function(e) {
            var currentSource = $(e.relatedTarget).data('current');
            $("input[name=source_choose][value=" + currentSource + "]").prop('checked', true);
            $('input[type=radio][name=source_choose]').change(function() {
                $("#redirect-new-source").attr("href", `/truyen/${this.value}`)
            });
        });
        $(".tt-box-list-chapter .more-list-chapter").click(function(e) {
            if ($('.more-list-chapter').hasClass('more')) {
                $(this).removeClass('more').addClass('less');
                $(this).html('Thu gọn');
            } else {
                $(this).removeClass('less').addClass('more');
                $(this).html('Xem thêm');
                $('html, body').animate({
                    scrollTop: $('.tt-box-list-chapter').offset().top
                }, 200);
            }
            jQuery(".tt-box-list-chapter .tt-box-chapter").toggleClass("expand");
        });
        $(".tt-box-list-chapter .more-list-chapter").click(function(e) {
            $('.tt-chapter').each(function(i, obj) {
                if ($(this).hasClass("chaplastreaded")) {
                    $(window).scrollTop($("#last_readed").offset().top);
                }
            });
        });
    });

    $("#read-more-des").click(function(e) {
        if ($('#mobile-story-des').hasClass('now-less')) {
            $('#mobile-story-des').removeClass('now-less').addClass('now-more');
            $("#read-more-des").html('<i class="fa fa-angle-up"></i>')

        } else {
            $('#mobile-story-des').removeClass('now-more').addClass('now-less');
            $("#read-more-des").html('<i class="fa fa-angle-down"></i>')
        }
    });
    $(function() {
        $('.order').on('click', function() {
            var story = $(this).attr('data-story');
            var chapter = $(this).attr('data-chapter-name');
            var author = $(this).attr('data-author');
            var price = $(this).attr('data-price');
            Swal.fire({
                title: 'Bạn muốn mua chương này ?',
                text: `Chương ${chapter} giá 120 vàng`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tất nhiên rồi!',
                cancelButtonText: 'Chưa phải lúc này!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (!$(this).data("loading")) {
                        var href = $(this).attr('data-url');
                        var chapters = $(this).attr('data-chapter');
                        var url = "{{ route('user.order.chapter') }}";
                        $.post({
                            url: url,
                            data: {
                                chapter: chapters,
                                story: story,
                                author: author
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(res) {
                                if (res.status == 300) {
                                    toastr.error(res.message, 'Cảnh Báo');
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Thành công',
                                        text: res.message,
                                        confirmButtonText: 'Đọc ngay',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = href;
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            })
        })
        var listBuy = [];
        $('.order-many').on('change', function() {
            if (this.checked) {
                var buyCheck = this.value.split(",");
                listBuy.push(buyCheck);
            } else {
                var buyCheck = this.value.split(",");
                for (var i = listBuy.length - 1; i >= 0; i--) {
                    if (listBuy[i][0] == buyCheck[0]) {
                        listBuy.splice(i, 1);
                    }
                }
            }
            window.listBuy = listBuy;
            var countChuong = listBuy.length;
            var tongTien = 120 * countChuong
            $('#show-chuong-vang').html(`${countChuong} chương - ${tongTien} vàng`)
        })

        $('#chap-all').on('change', function() {
            if (this.checked) {
                $(".order-many").each(function() {
                    if ($(this).prop('checked') == false) {
                        $(this).prop('checked', true).trigger('change');
                    }
                })
            } else {
                $(".order-many").each(function() {
                    if ($(this).prop('checked') == true) {
                        $(this).prop('checked', false).trigger('change');
                    }
                })
            }
        })


        $('.buy-chaps-button').on('click', function() {
            var countChuong = listBuy.length;
            var tongTien = 120 * countChuong;
            var tienTrongVi = $(this).attr('data-wallet');
            tiTrongVi = parseInt(tienTrongVi);
            if (tiTrongVi < tongTien) {
                $('.not-enough-gold-chap').show();
            } else {
                $('.not-enough-gold-chap').hide();
                $('.buy-chaps-button').prop('disabled', true);
                Swal.fire({
                    title: 'Xác nhận mua chương',
                    text: `Bạn muốn mua ${countChuong } chương với giá ${tongTien} vàng ?`,
                    showCancelButton: true,
                    confirmButtonColor: '#C9B708',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Không',
                    confirmButtonText: 'Mua'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (!$(this).data("loading")) {
                            var url = "{{ route('user.order.chapter') }}";
                            $.post({
                                url: url,
                                data: {
                                    listBuy: listBuy
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(res) {
                                    if (res.status == 300) {
                                        toastr.error(res.message, 'Cảnh Báo');
                                    } else {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Thành công',
                                            text: res.message,
                                        })
                                    }
                                    window.listBuy = [];
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }
        })
        $('.gold').on('keyup', function() {
            $('button.gift').prop('disabled', false);
            var gold = $(this).val();
            if (!gold.length) {
                $('.error').text('Vàng không được bỏ trống');
                $('button.gift').prop('disabled', true);
                return;
            }
            if (gold == 0) {
                $('.error').text('Số vàng phải lớn hơn 0');
                $('button.gift').prop('disabled', true);
                return;
            }
            if (gold % 10 > 0) {
                $('.error').text('Số vàng phải chia hết cho 10');
                $('button.gift').prop('disabled', true);
                return;
            }
            if (/^[0-9.]+$/.test($(this).val())) {
                $('.error').text('');
                if (parseFloat(gold) > parseFloat($('.wallet').attr('data-wallet'))) {
                    $('.request').val('Bạn không đủ vàng để tặng')
                } else {
                    var number = gold / 1;
                    $('.request').val(number);
                }
                $('button.gift').prop('disabled', false);
            } else {
                $('.error').text('');
                $('.request').val('Số vàng phải là số')
                $('button.gift').prop('disabled', true);
            }
        })
        $('.gift').on('click', function(e) {
            // $(this).attr("disabled", true);
            var gold = document.querySelector('input[name="gold"]:checked').value;
            var request = gold;
            var received = '{{ $story->mod_id ?? 0 }}';
            var story = '{{ $story->id }}';
            var url = "{{ route('user.donate.store') }}";
            var goldWallet = $(this).attr('data-wallet');
            goldWallet = parseInt(goldWallet);
            var goldChoose = $('input[type=radio][name=gold]:checked').val();
            if (goldWallet < goldChoose) {
                $('.not-enought-gold').show();
            } else {
                $('.not-enought-gold').hide();
            }
            $.post({
                url: url,
                data: {
                    num: request,
                    gold: gold,
                    received: received,
                    story: story
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status == 300) {
                        $('.error').text(res.message);
                        $('.gift').attr("disabled", false);
                    } else {
                        toastr.success(res.message, 'Thành Công');
                        // setTimeout(window.location.reload(), 2000);
                    }
                }
            })
        })
        $('.gifts').on('click', function() {
            // $(this).attr("disabled", true);
            var request = 1;
            var gold = 1;
            var received = 0;
            var story = '{{ $story->id }}';
            var url = "{{ route('user.donate.store') }}";
            $.post({
                url: url,
                data: {
                    num: request,
                    gold: gold,
                    received: received,
                    story: story
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status == 300) {
                        $('.error').text(res.message);
                        $('.gifts').attr("disabled", false);
                    } else {
                        toastr.success(res.message, 'Thành Công');
                        // setTimeout(window.location.reload(), 2000);
                    }
                }
            })
        })
        $(document).on('click', function() {
            $('.lists').addClass('d-none');
        })
        $('#like').on('click', function() {
            var id = '{{ $story->id }}';
            var url = '/truyen/' + id + '/whishlist';
            $.post({
                url: url,
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status == 300) {
                        $('.error').text(res.message);
                    } else {
                        toastr.success(res.message, 'Thành Công');
                        $('#whishlist').text(res.all_whishlist);
                    }
                }
            })
        });
        $('#follow').on('click', function() {
            var id = '{{ $story->id }}';
            var url = '/truyen/' + id + '/follow';
            $.post({
                url: url,
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status == 300) {
                        $('.error').text(res.message);
                    } else {
                        toastr.success(res.message, 'Thành Công');
                        $('#follow_story').text(res.all_follow);
                        location.reload();
                    }
                }
            })
        });
        @if ($story->type == 1)
            $('.updateEmbedStory').click(function(el) {
                $.ajax({
                    url: '{{ route('updateEmbedStory') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        story_id: '{{ $story->id }}',
                    },
                    success: function(res) {
                        toastr.success(res.message, 'Thành Công');
                        location.reload();
                    },
                })
            })
        @endif
    })
</script>

@endsection
