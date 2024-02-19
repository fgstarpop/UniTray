@extends('shop.layouts.app')
@section('title')
    {{ __('Tìm kiếm') }} @if(!empty(setting('store_name')))
        -
    @endif
    {{ setting('store_name') }}
    @if(!empty(setting('store_slogan')))
        -
    @endif
    {{ setting('store_slogan') }}
@endsection
@section('seo')
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <meta name="title" content="{{ request('keyword') }}">
    <meta name="description" content="{{ request('keyword') }}">
    <meta name="keywords" content="{{ request('keyword') }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ request('keyword') }}">
    <meta property="og:description" content="{{ request('keyword') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ setting('store_logo') ? \Storage::url(setting('store_logo')) : '' }}">
    <meta property="og:site_name" content="{{ url('') }}">
@stop
@section('content')
    <section class="container mt-4 content search-section" style="margin-bottom: 50px;">
        <div class="mt-4">
            <form id="searchviewdiv" class="" style="max-width: inherit;" method="GET">
                <input type="hidden" name="count_chapter" value="{{ request('count_chapter') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="type" value="{{ request('type') }}">
                <input type="hidden" name="kieu" value="{{ request('kieu') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div id="tm-p-search-top">
                    <div class="header-find">
                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <h2>Tìm kiếm truyện</h2>
                        @endif
                        @if((new \Jenssegers\Agent\Agent())->isMobile())

                            <h2 class="text-danger text-center"><b>Tìm kiếm truyện</b></h2>
                        @endif
                    </div>
                    <div>
                        <div>
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="input-group mb-3 mt-3">
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="input-group mt-3 p-0 mb-2">
                            @endif
                                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                <div class="input-group-prepend">
                                    <span class="input-group-text select-title">Tìm từ khóa: </span>
                                </div>
                                @endif
                                <input id="keyword" name="keyword" value="{{ request('keyword') ?? request('search') }}"
                                       placeholder=" Tìm trong tên, tên hán việt, tác giả" class="form-control">
                            </div>
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="input-group mb-3 mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text select-title">Tìm tóm tắt: </span>
                                </div>
                                <input id="find" value="{{ request('description') }}" name="description"
                                       placeholder=" Tìm trong phần tóm tắt của truyện" class="form-control"
                                >
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                                <div class="input-group p-0">
                                    <textarea name="description" id="find" cols="30" rows="5"
                                              class="form-control"
                                              placeholder=" Tìm trong phần tóm tắt của truyện"></textarea>
                                </div>
                            @endif
{{--                            @if((new \Jenssegers\Agent\Agent())->isMobile())--}}
{{--                                <span class="select-title px-0">Chọn nguồn</span>--}}
{{--                            @endif--}}
{{--                                @if((new \Jenssegers\Agent\Agent())->isDesktop())--}}
{{--                                <div class="input-group group-origin">--}}
{{--                                    <input id="findinhost" name="origin_link" value="{{ request('origin_link') }}"--}}
{{--                                           placeholder="Chỉ tìm nguồn: " class="form-control">--}}
{{--                                    <div class="input-group-append">--}}
{{--                                        <select name="origin" class="form-control">--}}
{{--                                            <option value="">[Chọn nguồn nhanh]</option>--}}
{{--                                            @foreach(\App\Domain\Story\Models\Story::ORIGINS as $key => $origin)--}}
{{--                                                <option value="{{ $key }}"--}}
{{--                                                        @if($key == request('origin')) selected @endif>{{ $origin }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @endif--}}
{{--                                @if((new \Jenssegers\Agent\Agent())->isMobile())--}}
{{--                                <div class="input-group group-origin p-0">--}}
{{--                                    <input type="radio" class="btn-check" name="origin"--}}
{{--                                       id="origin-all" autocomplete="off" value="" @if (empty(request()->input('origin'))) checked @endif>--}}
{{--                                    <label style="margin: 0 5px 5px 0" class="btn rounded-1 btn-origin @if (empty(request()->input('origin'))) btn-gt @endif" for="origin-all">Tất cả</label>--}}
{{--                                    @foreach(\App\Domain\Story\Models\Story::ORIGINS as $key => $origin)--}}
{{--                                        <input type="radio" class="btn-check" name="origin"--}}
{{--                                               id="origin-{{ $key }}" autocomplete="off" value="{{ $key }}"--}}
{{--                                               @if($key == request('origin')) checked @endif >--}}
{{--                                        <label style="margin: 0 5px 5px 0" class="btn btn-origin @if (!empty(request()->input('origin')) && request()->input('origin') == $key) btn-gt @endif rounded-1" for="origin-{{ $key }}">{{ $origin }}</label>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                @endif--}}
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                <span class="select-title">Số chương: </span>
                                <span id="count_chapter" class="blk-arr">
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 50) btn-gt selected text-white @else btn-light @endif"
                                            data-t="50">&gt; 50</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 100) btn-gt selected text-white @else btn-light @endif"
                                            data-t="100">&gt; 100</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 200) btn-gt selected text-white @else btn-light @endif"
                                            data-t="200">&gt; 200</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="500">&gt; 500</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 1000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1000">&gt; 1000</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 1500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1500">&gt; 1500</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 2000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="2000">&gt; 2000</button>
                                    <button data-t="0" type="button"
                                            class="btn @if(!request('count_chapter')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="py-2 px-0">
                                    <span class="select-title px-0 d-block">Số chương: </span>
                                <style>
                                    #sort button, #type button, #minc button, #category button, #tag button, #bookstatus button, #count_chapter button, #status button, #kieu button {
                                        padding: .375rem .75rem;
                                    }
                                </style>
                                    <span id="count_chapter" class="blk-arr px-0">
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 50) btn-gt selected text-white @else btn-light @endif"
                                            data-t="50">&gt; 50</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 100) btn-gt selected text-white @else btn-light @endif"
                                            data-t="100">&gt; 100</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 200) btn-gt selected text-white @else btn-light @endif"
                                            data-t="200">&gt; 200</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="500">&gt; 500</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 1000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1000">&gt; 1000</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 1500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1500">&gt; 1500</button>
                                    <button type="button"
                                            class="btn @if(request('count_chapter') == 2000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="2000">&gt; 2000</button>
                                    <button data-t="0" type="button"
                                            class="btn @if(!request('count_chapter')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                            <span class="select-title">Sắp xếp</span>
                                            <span id="sort" class="blk-arr sort">
                                    <button data-t="0" type="button"
                                            class="btn @if(!request('sort')) btn-gt selected text-white @else btn-light @endif">Không sắp xếp</button>
                                    @foreach(\App\Domain\Story\Models\Story::SORT as $key => $sort)
                                                    <button data-t="{{ $key }}" type="button"
                                                            class="btn @if(request('sort') == $key) btn-gt selected text-white @else btn-light @endif">{{ $sort }}</button>
                                                @endforeach
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="px-0">
                                    <span class="select-title px-0">Sắp xếp</span>
                                            <span id="sort" class="blk-arr sort px-0">
                                    <button data-t="0" type="button"
                                            class="btn @if(!request('sort')) btn-gt selected text-white @else btn-light @endif">Không sắp xếp</button>
                                    @foreach(\App\Domain\Story\Models\Story::SORT as $key => $sort)
                                                    <button data-t="{{ $key }}" type="button"
                                                            class="btn @if(request('sort') == $key) btn-gt selected text-white @else btn-light @endif">{{ $sort }}</button>
                                                @endforeach
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                <span class="select-title">Loại truyện</span>
                                <span id="type" class="blk-arr type">
                                    <button type="button" data-t=""
                                            class="btn @if(!request('type')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button type="button" data-t="sangtac"
                                            class="btn @if(request('type') == 'sangtac') btn-gt selected text-white @else btn-light @endif">Truyện sáng tác</button>
                                    <button type="button" data-t="conv"
                                            class="btn @if(request('type') == 'conv') btn-gt selected text-white @else btn-light @endif">Truyện Convert</button>
                                    <button type="button" data-t="convAu"
                                            class="btn @if(request('type') == 'convAu') btn-gt selected text-white @else btn-light @endif">Truyện Convert Audio</button>
                                    <button type="button" data-t="sangtacAu"
                                            class="btn @if(request('type') == 'sangtacAu') btn-gt selected text-white @else btn-light @endif">Truyện Sáng Tác Audio</button>
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="p-0">
                                <span class="select-title px-0">Loại truyện</span>
                                <span id="type" class="blk-arr type px-0">
                                    <button type="button" data-t=""
                                            class="btn @if(!request('type')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button type="button" data-t="sangtac"
                                            class="btn @if(request('type') == 'sangtac') btn-gt selected text-white @else btn-light @endif">Truyện sáng tác</button>
                                    <button type="button" data-t="conv"
                                            class="btn @if(request('type') == 'conv') btn-gt selected text-white @else btn-light @endif">Truyện Convert</button>
                                    <button type="button" data-t="convAu"
                                            class="btn @if(request('type') == 'convAu') btn-gt selected text-white @else btn-light @endif">Truyện Convert Audio</button>
                                            <button type="button" data-t="sangtacAu"
                                            class="btn @if(request('type') == 'sangtacAu') btn-gt selected text-white @else btn-light @endif">Truyện Sáng Tác Audio</button>
                                </span>
                            </div>
                            @endif

                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                <span class="select-title d-block">Kiểu truyện</span>
                                <span id="kieu" class="blk-arr type">
                                    <button type="button" data-t=""
                                            class="btn @if(!request('kieu')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button type="button" data-t="vip"
                                            class="btn @if(request('kieu') == 'vip') btn-gt selected text-white @else btn-light @endif">Truyện Vip</button>
                                    <button type="button" data-t="free"
                                            class="btn @if(request('kieu') == 'free') btn-gt selected text-white @else btn-light @endif">Truyện Free</button>
                                </span>
                            </div>
                            @endif

                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="p-0">
                                <span class="select-title px-0 d-block">Kiểu truyện</span>
                                <span id="kieu" class="blk-arr type px-0">
                                    <button type="button" data-t=""
                                            class="btn @if(!request('kieu')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button type="button" data-t="vip"
                                            class="btn @if(request('kieu') == 'vip') btn-gt selected text-white @else btn-light @endif">Truyện Vip</button>
                                    <button type="button" data-t="free"
                                            class="btn @if(request('kieu') == 'free') btn-gt selected text-white @else btn-light @endif">Truyện Free</button>
                                </span>
                            </div>
                            @endif

                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                <span class="select-title">Thể loại</span>
                                <span id="category" class="blk-arr category">
                                    <button data-t="" type="button"
                                            class="btn @if(!request('category')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                            <button data-t="{{ $category->id }}" type="button"
                                                    class="btn @if(request('category') == $category->id) btn-gt selected text-white @else btn-light @endif">{{ $category->name }}</button>
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="px-0">
                                    <span class="select-title px-0">Thể loại</span>
                                    <span id="category" class="blk-arr category px-0">
                                    <button data-t="" type="button"
                                            class="btn @if(!request('category')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    @if($categories->isNotEmpty())
                                                    @foreach($categories as $category)
                                                        <button data-t="{{ $category->id }}" type="button"
                                                                class="btn @if(request('category') == $category->id) btn-gt selected text-white @else btn-light @endif">{{ $category->name }}</button>
                                                    @endforeach
                                                @endif
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="p-all">
                                            <span class="select-title">Trạng thái</span>
                                            <span id="status" class="blk-arr status" style="display: block">
                                    <button data-t="" type="button"
                                            class="btn @if(!request('status')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button data-t="3" type="button"
                                            class="btn @if(request('status') == 3) btn-gt selected text-white @else btn-light @endif">Hoàn thành</button>
                                    <button data-t="2" type="button"
                                            class="btn @if(request('status') == 2) btn-gt selected text-white @else btn-light @endif">Còn tiếp</button>
                                </span>
                            </div>
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="px-0">
                                            <span class="select-title px-0">Trạng thái</span>
                                            <span id="status" class="blk-arr status px-0" style="display: block">
                                    <button data-t="" type="button"
                                            class="btn @if(!request('status')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button data-t="3" type="button"
                                            class="btn @if(request('status') == 3) btn-gt selected text-white @else btn-light @endif">Hoàn thành</button>
                                    <button data-t="2" type="button"
                                            class="btn @if(request('status') == 2) btn-gt selected text-white @else btn-light @endif">Còn tiếp</button>
                                </span>
                            </div>
                            @endif

                            <div class="p-all" style="text-align: right">
                                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                <button id="searchbutton" type="submit" class="btn btn-gt">
                                    <i class="fas fa-search"></i> Tìm
                                </button>
                                @endif
                                @if((new \Jenssegers\Agent\Agent())->isMobile())
                                    <button id="searchbutton" type="submit" class="btn px-4 py-2 btn-gt btn-redirect">
                                        Tìm kiếm
                                    </button>
                                @endif
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>

                @if($stories->isNotEmpty())
                    <div class="mt-3 mb-3 story-search">
                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                        <div class="header-find">
                            <h2>Kết quả tìm kiếm</h2>
                        </div>
                        @endif
                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                        <div class="row">
                            @foreach($stories as $story)
                                @include('shop.story._card_search', ['story' => $story])
                            @endforeach
                            <span hidden=""></span>
                            <div id="endless" class="row" style="margin: 0px;"></div>
                        </div>
                        @endif
                    </div>
                    @if((new \Jenssegers\Agent\Agent())->isMobile())
                    <div style="color: red; text-align: center;">
            <h5 class="title">
            Kết quả tìm kiếm </h5>
            <br>
            </div>
                    <section class="content container search-section p-0">
                    <div class="col-12 mb-2">
                    <div class="home-section">
                        <div class="row">
                            @foreach($stories as $story)
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-4">
                                    @include('shop.story._card_search', ['story' => $story])
                                </div>
                            @endforeach

                    </div>
                </div>
                </div>
            </div>
                    @endif
                    <div style="text-align: center;width: 100%" id="searchpagi"><br>
                        <nav aria-label="..." style="display: inline-block;">
                            {!! $stories->appends(request()->input())->links() !!}
                        </nav>
                    </div>
                @else
                    <div class="mt-3 mb-3 story-search">
                        <div class="row">
                            <h6 class="text-center">Không tìm thấy truyện nào</h6>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.blk-arr button').click(function () {
                $.each($(this).closest('.blk-arr').find('button'), function (key, el) {
                    if ($(el).hasClass('selected')) {
                        $(el).removeClass('btn-gt selected text-white')
                        $(el).addClass('btn-light')
                    }
                })
                $(this).removeClass('btn-light')
                $(this).addClass('btn-gt selected text-white')
                buildQuery($(this), $(this).parent())
            })

            function buildQuery(element, parent) {
                $('input[name=' + $(parent).attr('id') + ']').val($(element).data('t'))
            }

            $('.btn-origin').click(function () {
                let self = $(this)
                $('.btn-origin').removeClass('btn-gt')
                self.addClass('btn-gt')
                $.each($('input[name="origin"]'), function (key, element) {
                    if ($(element).is(':checked')) {
                        $(element).prop('checked', '');
                        $(element).attr('checked', false)
                    }
                    if ($(element).attr('id') == self.attr('for')) {
                        $(element).attr('checked', true)
                    }
                })
            })
        })
    </script>

@endsection

