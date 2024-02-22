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
    <meta name="title" content="{{ request('$story->name') }}">
    <meta name="description" content="{{ request('$story->description') }}">
    <meta name="keywords" content="{{ request('$story->name') }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ request('$story->name') }}">
    <meta property="og:description" content="{{ request('$story->description') }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ setting('store_logo') ? \Storage::url(setting('store_logo')) : '' }}">
    <meta property="og:site_name" content="{{ url('') }}">
@stop
<style>
    .blk-arr::before {
        display: none;
    }

    .head-mobie {
        padding-left: 60px
    }

    .story-search-mob {
        padding-left: 60px; padding-right: 50px; padding-top: 50px
    }

    .modal-header-mob {
        display: none !important;
    }

    .input-search-mob {
        margin-left: 20px !important;
        border-radius: 3px !important; 
        height: 38px  !important;
    }

    @media only screen and (max-width: 600px) {
        .no-mobie {
            display: none;
        }

        .btn-mob {
            margin-right: 10px
        }

        .head-mobie {
            padding-left: 10px;
            font-size: 16px;
            font-weight: 700;
        }

        .story-search-mob {
            display: none;
        }

        #tm-p-search-top {
            padding: 10px !important;
        }

        .cus-modal {
            margin-left: 0 !important
        }

        .btn-mob {
            background: #0C9A00 !important;
        }

        .modal-header-mob {
            display: block !important;
        }

        .input-search-mob {
            margin-left: 0px !important;
        }
    }

</style>

@section('content')

    <div style="display: flex; justify-content: space-between; margin-top: 30px" class="align-items-center">
        <div class="head-mobie">Kết quả tìm kiếm</div>
        <button style="margin-left: -120px" type="button" class="btn btn-gt btn-mob" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
            <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
        </svg></i> 
        Bộ lọc truyện
        </button>
        <div class="no-mobie"></div>















        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header modal-header-mob">
                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Tìm kiếm truyện</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    </div>
                    <div class="">
                    <form id="searchviewdiv" class="" style="max-width: inherit;" method="GET">
                <input type="hidden" name="count_chapter" value="{{ request('count_chapter') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="type" value="{{ request('type') }}">
                <input type="hidden" name="kieu" value="{{ request('kieu') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div id="tm-p-search-top">
                    <!-- <div class="header-find">
                        
                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <h2>Tìm kiếm truyện</h2>
                        @endif
                        @if((new \Jenssegers\Agent\Agent())->isMobile())

                            <h2 class="text-danger text-center"><b>Tìm kiếm truyện</b></h2>
                        @endif
                    </div> -->
                    <div>
                        <div>
                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="input-group mb-3 mt-3 align-items-center">
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="d-flex align-items-center">
                            @endif
                                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                
                                <div class="" style="font-weight: 500; color: #515151">
                                    Tìm tên truyện
                                </div>
                                @endif
                                <input id="keyword" name="keyword" value="{{ request('keyword') ?? request('search') }}"
                                       placeholder="Nhập từ khóa tên truyện cần tìm...." class="input-search-mob form-control">
                            </div>
                            <!-- @if((new \Jenssegers\Agent\Agent())->isDesktop()) 
                            <div class="input-group mb-3 mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text select-title">Tìm tóm tắt: </span>
                                </div>
                                <input id="find" value="{{ request('description') }}" name="description"
                                       placeholder=" Tìm trong phần tóm tắt của truyện" class="form-control"
                                >
                            </div>-->
                            @endif
                            <!-- @if((new \Jenssegers\Agent\Agent())->isMobile()) 
                                <div class="input-group p-0">
                                    <textarea name="description" id="find" cols="30" rows="5"
                                              class="form-control"
                                              placeholder=" Tìm trong phần tóm tắt của truyện"></textarea>
                                </div>
                            @endif -->

                            @if((new \Jenssegers\Agent\Agent())->isDesktop())
                            <div class="input-group mb-3 mt-3 align-items-center">
                            @endif
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="d-flex align-items-center source-mb">
                            @endif
                                @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                
                                <div class="" style="font-weight: 500; color: #515151">
                                    Nguồn truyện
                                </div>
                                @endif

                                @if((new \Jenssegers\Agent\Agent())->isMobile())
                                
                                <div class="" style="font-weight: 500; color: #515151">
                                    Nguồn truyện
                                </div>
                                @endif
                                <!-- <input style="margin-left: 20px; border-radius: 3px; height: 38px" id="keyword" name="keyword" value="{{ request('keyword') ?? request('search') }}"
                                       placeholder="Nhập từ khóa tên truyện cần tìm...." class="form-control"> -->
                                @include('shop.modal')
                            </div>
                            <!-- @if((new \Jenssegers\Agent\Agent())->isDesktop()) 
                            <div class="input-group mb-3 mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text select-title">Tìm tóm tắt: </span>
                                </div>
                                <input id="find" value="{{ request('description') }}" name="description"
                                       placeholder=" Tìm trong phần tóm tắt của truyện" class="form-control"
                                >
                            </div>-->
                            @endif

                            
                            
                            <!-- @if((new \Jenssegers\Agent\Agent())->isDesktop()) -->
                            <div class="">
                                            <span class="select-title" style="font-weight: 500; color: #515151">Sắp xếp</span>
                                            <span id="sort" class="blk-arr sort" style="display: block; margin-left: 5px; background: #fff !important">
                                    <button data-t="0" type="button"
                                            style="padding: 7px, 12px, 7px; border-radius: 3px; border-radius: 3px; border: 0.5px solid #515151;"
                                            class="btn @if(!request('sort')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    @foreach(\App\Domain\Story\Models\Story::SORT as $key => $sort)
                                                    <button data-t="{{ $key }}" type="button"
                                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px"
                                                            class="btn @if(request('sort') == $key) btn-gt selected text-white @else btn-light @endif">{{ $sort }}</button>
                                                @endforeach
                                </span>
                            </div>
                            <!-- @endif -->
                            @if((new \Jenssegers\Agent\Agent())->isMobile())
                            <div class="px-0">
                                    <span class="">Sắp xếp</span>
                                            <span id="sort" class="blk-arr sort px-0">
                                    <button data-t="0" type="button"
                                            class="btn @if(!request('sort')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    @foreach(\App\Domain\Story\Models\Story::SORT as $key => $sort)
                                                    <button data-t="{{ $key }}" type="button"
                                                            class="btn @if(request('sort') == $key) btn-gt selected text-white @else btn-light @endif">{{ $sort }}</button>
                                                @endforeach
                                </span>
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
                            <div class="" style="margin-top: 10px">
                                <span class="select-title" style="font-weight: 500; color: #515151">Số chương: </span>
                                <span id="count_chapter" class="blk-arr" style="display: block; margin-left: 5px; background: #fff !important">
                                    <button data-t="0" type="button"
                                            style="padding: 7px, 12px, 7px; border-radius: 3px; border-radius: 3px; border: 0.5px solid #515151;"
                                            class="btn @if(!request('count_chapter')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button type="button"
                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 50) btn-gt selected text-white @else btn-light @endif"
                                            data-t="50">&gt; 50</button>
                                    <button type="button"
                                    style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 100) btn-gt selected text-white @else btn-light @endif"
                                            data-t="100">&gt; 100</button>
                                    <button type="button"
                                    style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 200) btn-gt selected text-white @else btn-light @endif"
                                            data-t="200">&gt; 200</button>
                                    <button type="button"
                                    style="border-radius: 3px; border: 0.5px solid #515151;  padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="500">&gt; 500</button>
                                    <button type="button"
                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 1000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1000">&gt; 1000</button>
                                    <button type="button"
                                    style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 1500) btn-gt selected text-white @else btn-light @endif"
                                            data-t="1500">&gt; 1500</button>
                                    <button type="button"
                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('count_chapter') == 2000) btn-gt selected text-white @else btn-light @endif"
                                            data-t="2000">&gt; 2000</button>
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
                            <div class="" style="margin-top: 10px">
                                <span class="select-title" style="font-weight: 500; color: #515151">Thể loại</span>
                                <span id="category" class="blk-arr" style="display: block; margin-left: 5px; background: #fff !important">
                                    <button data-t="" type="button"
                                    style="padding: 7px, 12px, 7px; border-radius: 3px; border-radius: 3px; border: 0.5px solid #515151;"
                                            class="btn @if(!request('category')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                            <button data-t="{{ $category->id }}" type="button"
                                                    style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
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
                            <!-- @if((new \Jenssegers\Agent\Agent())->isDesktop()) 
                            <div class="" style="margin-top: 10px">
                                            <span class="select-title" style="font-weight: 500; color: #515151">Trạng thái</span>
                                            <span id="status" class="status blk-arr" style="display: block; margin-left: 5px; background: #fff !important">
                                    <button data-t="" type="button"
                                            style="padding: 7px, 12px, 7px; border-radius: 3px; border-radius: 3px; border: 0.5px solid #515151;"
                                            class="btn @if(!request('status')) btn-gt selected text-white @else btn-light @endif">Tất cả</button>
                                    <button data-t="3" type="button"
                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
                                            class="btn @if(request('status') == 3) btn-gt selected text-white @else btn-light @endif">Hoàn thành</button>
                                    <button data-t="2" type="button"
                                            style="border-radius: 3px; border: 0.5px solid #515151; padding: 7px, 12px, 7px, 12px; font-size: 13px"
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
                            @endif -->

                            <!-- <div class="p-all" style="text-align: right">
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
                            </div> -->
                            <hr>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <button type="button" class="btn  btn-gt" style="width: 100px; background: #6c757d !important"  data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" style="width: 120px; margin-left: 30px" class="btn btn-gt">Lọc</button>
                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cus-view" id="cus-view">
        <div class="cus-view-mo">
            <div class="cus-view-it">
                <div style="text-align: center; font-size: 17px; font-weight: 700">Nguồn Truyện</div>
                <div class="cus-view-it-header">
                    <div class="cus-view-it-item">
                    <input type="checkbox" class="custom-checkbox" name="source[]" id="source_faloo" value="faloo" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('faloo', request('source'))) checked @endif/>
                        <label class="checkbox-label" for="source_faloo" style="font-size: 15px; margin-left: 3px">Faloo</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_fanqie" value="fanqie" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('fanqie', request('source'))) checked @endif/>
                        <label for="source_fanqie" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Fanqie</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_qimao" value="qimao" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('qimao', request('source'))) checked @endif/>
                        <label for="source_qimao" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Qimao</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_uukanshu" value="uukansu" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('uukansu', request('source'))) checked @endif/>
                        <label for="source_uukanshu" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Uukansu</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_69shu" value="69shu" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('69shu', request('source'))) checked @endif/>
                        <label for="source_69shu" class="checkbox-label" style="font-size: 15px; margin-left: 3px">69shu</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_qidian" value="qidian" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('qidian', request('source'))) checked @endif/>
                        <label for="source_qidian" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Qidian</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_trxs" value="trxs" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('trxs', request('source'))) checked @endif/>
                        <label for="source_trxs" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Trxs</label>
                    </div>
                    <div class="cus-view-it-item">
                        <input type="checkbox" class="custom-checkbox" name="source[]" id="source_tadu" value="tadu" onchange="handleCheckboxChange(this)" @if(request()->has('source') && in_array('tadu', request('source'))) checked @endif/>
                        <label for="source_tadu" class="checkbox-label" style="font-size: 15px; margin-left: 3px">Tadu</label>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <div class="cus-view-it-footer">
                    <div id="find-sou">Chọn</div>
                    <div id="find-all-sou">Chọn tất cả</div>
                    <div id="close-sou">Huỷ</div>
                </div>
            </div>
        </div>
    </div>


    <script>
            let selectedValues = [];

            if (window.location.search.includes('source')) {
                const sourceParam = new URLSearchParams(window.location.search)
                const sourceArray = sourceParam.getAll('source[]');
                selectedValues = sourceArray;

                const span = document.getElementById("text-me");
                let btnText = "";
                if (selectedValues.length == 0) {
                    span.innerHTML = "Tất cả"
                } else {
                    span.innerHTML = selectedValues.join(', ');
                }
            }

    function handleCheckboxChange(checkbox) {
    if (checkbox.checked) {
        selectedValues.push(checkbox.value);
    } else {
        const index = selectedValues.indexOf(checkbox.value);
        if (index !== -1) {
            selectedValues.splice(index, 1);
        }
    }

    const span = document.getElementById("text-me");
    span.innerHTML = selectedValues.join(', ');
    }
    </script>
    </form>
    













    <section class="mt-4 content search-section" style="margin-bottom: 50px;">
        <div class="mt-4">
            <form id="searchviewdiv" class="" style="max-width: inherit;" method="GET">
                
                @if($stories->isNotEmpty())
                    <div class="mt-3 mb-3 story-search story-search-mob">
                        @if((new \Jenssegers\Agent\Agent())->isDesktop())
                        <!-- <div class="header-find">
                            <h2>Kết quả tìm kiếm</h2>
                        </div> -->
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

                    <section class="content container search-section p-0">
                    <div class="col-12 mb-2">
                    <div class="home-section">
                        <div class="row">
                            @foreach($stories as $story)
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-12">
                                    @include('shop.story._card_search', ['story' => $story])
                                </div>
                            @endforeach

                    </div>
                </div>
                </div>
            </div>
                    @endif
                    <div style="text-align: center;width: 100%;" id="searchpagi"><br>
                        <nav aria-label="..." style="display: inline-block;">
                            {!! $stories->appends(request()->input())->links() !!}
                        </nav>
                        <span>
                            <form action="">
                                <input name="page" value="{{ request('page') }}" style="width: 60px; height: 29px; border: 1px solid #ccc; border-radius: 3px; text-align: center"/>
                                <button style="height: 29px; width: 40px; border: 1px solid #1cb15c; color: #1cb15c; border-radius: 3px; text-align: center">Đến</button>
                            </form>
                        </span>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById("cus-modal");
        var view = document.getElementById("cus-view");
        var close = document.getElementById("close-sou");
        var findAll = document.getElementById("find-all-sou");
        var checkboxes = document.querySelectorAll(".custom-checkbox");
        var findSou = document.getElementById("find-sou");

        modal.addEventListener("click", function () {
            view.style.display = "block";
        });

        close.addEventListener("click", function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            })
            view.style.display = "none";
        });

        findSou.addEventListener("click", function () {
            view.style.display = "none";
        });


        findAll.addEventListener("click", function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = true;
            })

            const span = document.getElementById("text-me");
            span.innerHTML = "Chọn tất cả";
        });

    });
</script>