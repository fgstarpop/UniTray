@extends('shop.layouts.app')
@section('title')
{{ $chapter->name }} @if(!empty(setting('store_name')))
-
@endif
{{ setting('store_name') }}
@if(!empty(setting('store_slogan')))
-
@endif
{{ setting('store_slogan') }}
@endsection
@push('styles')
<style>
/*.main-list {*/
/*    background: #f4f4f4;*/
/*}*/

/*#maincontent,*/
/*#content-container {*/
/*    background: #f4f4f4;*/
/*}*/

@media only screen and (min-width: 600px) {
    body {
        background-color: #ddd;
    }
}
</style>
@endpush

@section('content')
@include('shop.layouts.partials.ads')

@php
    $check = 0;
    if(currentUser()){
        foreach(\App\Enums\UserState::Admin as $list){
            if(currentUser() && $list === currentUser()->username)
                $check = 1;
        }
        if($story->mod_id == currentUser()->id)
            $check = 1;
        if (currentUser()->user_vip == 1)
            $check = 1;
    }
@endphp
{{--Desktop--}}
@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <style>
        a{
            color: #191919 !important;
        }
    </style>
    <section class="container mt-4 content main-list contentbox notSelectable" id="full" style="margin-bottom: 50px; background: rgba(234, 228, 211, 0.7);" oncopy="return false"
             oncut="return false" onpaste="return false">
        <div id="inner">
            {{--        <div class="container bg-light tm-reader-top-br" id="breadcum" style="font-size: 14px;color: gray;padding: 6px 16px;">{{ $story->name}}--}}
            {{--            / {{ $chapter->name}}--}}
            {{--        </div>--}}
            <br class="tm-reader-top-br">
            <a href="{{ route('story.show', $story) }}" id="booknameholder"
               style="font-size: 24px;font-weight: 700;color: gray;display: block;text-align: center;">{{ $story->name}}
            </a>
            <center class="text-secondary">{{ $chapter->name}}</center>
            <br>
            @php
                if($chapterNext)
                $nextChapter = @$chapterNext['embed_link'] ? route('chapters.show', [$story->id, 'link' =>
                $chapterNext['embed_link']]) : route('chapters.show', [$story->id, 'id' => $chapterNext['id']]);
                if($chapterPre)
                $preChapter = @$chapterPre['embed_link'] ? route('chapters.show', [$story->id, 'link' =>
                $chapterPre['embed_link']]) : route('chapters.show', [$story->id, 'id' => $chapterPre['id']])
            @endphp
            <div class="tm-reader-top-nav" style="font-size: 16px;padding: 6px 0;font-weight: 500">
                <div class="container d-flex" style="justify-content: space-between;">

                    @if($chapterPre != null)
                        @if(@$chapterPre['is_vip'] == 0)
                            <a href="{{ $preChapter }}" id="prevchap" class="text-primary"><i class="fas fa-chevron-left"> </i>Chương
                                trước</a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterPre['id'],
                            'user_id' => currentUser()->id
                            ])->first() || $check || currentUser()->user_vip == 1)
                                <a href="{{ $preChapter }}" id="prevchap" class="text-primary" title="{{ $chapterPre['name'] }}">
                                    <i class="fas fa-chevron-left"></i>Chương trước <i class="fa fa-check"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $preChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterPre['id'] }}" data-chapter-name="{{ $chapterPre['name'] }}"
                                   data-price="{{ number_format($chapterPre['price']) }}" class="text-primary order"
                                   title="{{ $chapterPre['name'] }}" id="prevchap">
                                    <i class="fas fa-chevron-left"></i> Chương trước <i class="fa fa-lock"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}" id="prevchap" class="text-primary"><i class="fas fa-chevron-left"></i> Chương trước
                        </a>
                    @endif

                    <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list"> Mục lục </a>

                    @if($chapterNext != null)
                        @if(@$chapterNext['is_vip'] == 0)
                            <a href="{{ $nextChapter }}"  id="nextchap" class="text-primary">Chương sau <i class="fas fa-chevron-right"></i></a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterNext['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check || currentUser()->user_vip == 1)
                                <a href="{{ $nextChapter }}"  id="nextchap" class="text-primary" title="{{ $chapterNext['name'] }}">
                                    <i class="fa fa-check"></i>Chương sau <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $nextChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterNext['id'] }}" data-chapter-name="{{ $chapterNext['name'] }}"
                                   data-price="{{ number_format($chapterNext['price']) }}" class="text-primary order"
                                   title="{{ $chapterNext['name'] }}"  id="nextchap" >
                                    <i class="fa fa-lock"></i> Chương sau <i class="fas fa-chevron-right"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}"  id="nextchap"> Chương sau <i class="fas fa-chevron-right"></i> </a>
                    @endif
                    <div class="modal fade" id="list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                         style="font-size: 12px;">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Mục lục</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body"
                                     style="max-height:80vh;overflow:auto;font-size:16px;line-height:26px">
                                    <?php $readed = true ?>
                                    @if(!empty($chapters))
                                        @foreach($chapters as $chap)
                                            @if((@$chap->timer && @$chap->timer <= date('Y-m-d H:i:s')) || !@$chap->timer)
                                                @php
                                                    if (@$chap['embed_link']) {
                                                    if (!$chapterLastReaded) {
                                                    $readed = false;
                                                    }
                                                    if($chapterLastReaded != '' && ($chapterLastReaded == $chap['order'])) {
                                                    $link = 'chaplastreaded';
                                                    $readed = false;
                                                    }
                                                    else if($readed) $link = 'chapreaded';
                                                    else $link = '';
                                                    } else {
                                                    if($chapterLastReaded == $chap['order']) $link = 'chaplastreaded';
                                                    else if($chapterLastReaded < $chap['order'] ) $link='' ; else $link='chapreaded' ; }
                                                        $linkStory=@$chap['embed_link'] ? @$chap['link_other'] ?? route('chapters.show',
                                                        [$story->id, 'link' => @$chap['embed_link']]) : @$chap['link_other'] ??
                                                        route('chapters.show', [$story->id, 'id' => $chap['id']]);
                                                @endphp
                                                <div class="listss {{ $link }}">
                                                    @if(currentUser())
                                                        @if(@$chap['is_vip'] == 1)
                                                            @if( $check || currentUser()->user_vip == 1)
                                                                <a href="{{ $linkStory }}" class="text-success" title="{{ $chap['name'] }}">
                                                                    <i class="fal fa-check"></i> {{ Str::limit($chap['name'], 35) }}
                                                                </a>
                                                            @else
                                                                <a href="javascript:;" data-url="{{ $linkStory }}"
                                                                   data-story="{{ $story->id }}" data-chapter="{{ @$chap['id'] }}"
                                                                   data-chapter-name="{{ @$chap['name'] }}"
                                                                   data-price="{{ number_format(@$chap['price']) }}"
                                                                   class="text-success order" title="{{ $chap['name'] }}">
                                                                    {{ Str::limit($chap['name'], 35) }}
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{ $linkStory }}" class="text-success" title="{{ $chap['name'] }}">
                                                                {{ Str::limit($chap['name'], 35) }} </a>

                                                        @endif
                                                    @else
                                                        <a href="{{ $linkStory }}" class="text-success "
                                                           title="{{ $chap['name'] }}">
                                                            {{ Str::limit($chap['name'], 35) }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content-container" class="container" style="min-height: 300px;padding: 0px;">
                <div class="contentbox" id="maincontent" style="font-family:sans-serif;padding-top: 24px;font-size:18px; background: rgba(234, 228, 211, 0.7);">
                    <div class="p-2">
                        @if((currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=>@$chapter->id, 'user_id' => currentUser()->id ])->first() && $chapter->is_vip) || !$chapter->is_vip || $check)
                            {!! $chapter->content !!}
                        @else
                            Chương chưa được mua!
                        @endif
                    </div>
                </div>
            </div>
            <div class="tm-reader-top-nav" style="background: #eeeeee;font-size: 16px;padding: 6px 0;font-weight: 500">
                <div class="container d-flex" style="justify-content: space-between;">
                    @if($chapterPre != null)
                        @if(@$chapterPre['is_vip'] == 0)
                            <a href="{{ $preChapter }}" class="text-primary"><i class="fas fa-chevron-left"></i> Chương trước</a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> $chapterPre['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check || currentUser()->user_vip == 1)
                                <a href="{{ $preChapter }}" class="text-primary" title="{{ $chapterPre['name'] }}">
                                    <i class="fas fa-chevron-left"></i> Chương trước <i class="fa fa-check"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $preChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterPre['id'] }}" data-chapter-name="{{ $chapterPre['name'] }}"
                                   data-price="{{ number_format($chapterPre['price']) }}" class="text-primary order"
                                   title="{{ $chapter['name'] }}">
                                    <i class="fas fa-chevron-left"></i> Chương trước <i class="fa fa-lock"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}" class="text-primary"><i class="fas fa-chevron-left"></i> Chương trước
                        </a>
                    @endif
                    <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list"> Mục lục </a>
                    @if($chapterNext != null)
                        @if(@$chapterNext['is_vip'] == 0)
                            <a href="{{ $nextChapter }}" class="text-primary">Chương sau <i class="fas fa-chevron-right"></i></a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterNext['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check || currentUser()->user_vip == 1)
                                <a href="{{ $nextChapter }}" class="text-primary" title="{{ $chapterNext['name'] }}">
                                    <i class="fa fa-check"></i>Chương sau <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $nextChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterNext['id'] }}" data-chapter-name="{{ $chapterNext['name'] }}"
                                   data-price="{{ number_format($chapterNext['price']) }}" class="text-primary order"
                                   title="{{ $chapter['name'] }}">
                                    <i class="fa fa-lock"></i> Chương sau <i class="fas fa-chevron-right"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}"> Chương sau <i class="fas fa-chevron-right"></i></a>
                    @endif
                </div>
            </div>
            {{--        <div style="width: 100%;" class="container mt-3">--}}
            {{--            <button id="originbutton" class="btn bg-light"><i class="fas fa-external-link-alt"></i> Xem trang gốc</button>--}}
            {{--            <select class="btn bg-light">--}}
            {{--                <option value="name">Dịch thuật</option>--}}
            {{--                <option value="name">Bật name(Mặc định)</option>--}}
            {{--                <option value="noname">Tắt name(Giảm lag)</option>--}}
            {{--                <option value="morehan">Hán Việt nhiều</option>--}}
            {{--                <option value="extrahan">Style wikidich</option>--}}
            {{--                <option value="chinese">Văn bản gốc</option>--}}
            {{--            </select>--}}
            {{--        </div>--}}
            {{--        <br>--}}
            {{--       @include('shop.layouts.comment',['type' => "App/Domain/Chapter/Models/Chapter",'id' => $chapter->id ])--}}
        </div>
        <?php
        $origin = 'dich';
        if ($story->origin) {
            if (strpos($story->origin, 'faloo') !== false) {
                $origin = 'faloo';
            }
            if (strpos($story->origin, 'uukanshu') !== false) {
                $origin = 'uukanshu';
            }
            if (strpos($story->origin, 'qidian') !== false) {
                $origin = 'qidian';
            }
            if (strpos($story->origin, 'bxwxorg') !== false) {
                $origin = 'bxwxorg';
            }
        }
        ?>
        <span id="hiddenid" hidden="">{{$story->id}};{{ $chapter['id'] }};{{ $origin }}</span>
    </section>
    @if(!empty(currentUser()->id))
        @include('shop.chapter.stv')
    @endif
@endif
{{--Mobile--}}
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <style>
        a{
            color: #191919 !important;
        }
    </style>
    <section class="container content main-list contentbox notSelectable reader-book" id="full" style="margin-bottom: 50px;" oncopy="return false"
             oncut="return false" onpaste="return false">
        <div id="inner">
            <br class="tm-reader-top-br">
            <div class="top-link px-3 mb-2">
                <a href="{{ route('story.show', $story) }}" class="d-flex align-items-center" style="font-size: 16px">
                    <i class="fa fa-file-alt" style="font-size: 25px; margin-right: 5px"></i>
                    <u class="text-success" style="font-weight: bold; margin-left: 10px">{{ $story->name }}</u>
                </a>
            </div>
            <br>
            @php
                if($chapterNext)
                $nextChapter = @$chapterNext['embed_link'] ? route('chapters.show', [$story->id, 'link' =>
                $chapterNext['embed_link']]) : route('chapters.show', [$story->id, 'id' => $chapterNext['id']]);
                if($chapterPre)
                $preChapter = @$chapterPre['embed_link'] ? route('chapters.show', [$story->id, 'link' =>
                $chapterPre['embed_link']]) : route('chapters.show', [$story->id, 'id' => $chapterPre['id']])
            @endphp
            <div class="tm-reader-top-nav" style="font-size: 16px;padding: 6px 0;font-weight: 500">
                <div class="container d-flex" style="justify-content: space-between;">
                    @if($chapterPre != null)
                        @if(@$chapterPre['is_vip'] == 0)
                            <a href="{{ $preChapter }}" id="prevchap" class="text-dark"><span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương
                                trước</a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterPre['id'],
                            'user_id' => currentUser()->id
                            ])->first() || $check)
                                <a href="{{ $preChapter }}" id="prevchap" class="text-dark" title="{{ $chapterPre['name'] }}">
                                    <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span>Chương trước <i class="fa fa-check"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $preChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterPre['id'] }}" data-chapter-name="{{ $chapterPre['name'] }}"
                                   data-price="{{ number_format($chapterPre['price']) }}" class="text-primary order"
                                   title="{{ $chapterPre['name'] }}" id="prevchap">
                                    <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước <i class="fa fa-lock"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}" id="prevchap" class="text-dark"><span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước
                        </a>
                    @endif
                    @if((new \Jenssegers\Agent\Agent())->isDesktop())
                        <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list"> Mục lục </a>
                    @endif
                    @if((new \Jenssegers\Agent\Agent())->isMobile())
                        <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list">
                            <img src="{{ asset('frontend/images/mobile/menu-icon.svg') }}" alt="">
                        </a>
                    @endif
                    @if($chapterNext != null)
                        @if(@$chapterNext['is_vip'] == 0)
                            <a href="{{ $nextChapter }}"  id="nextchap" class="text-dark">Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span></a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterNext['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check)
                                <a href="{{ $nextChapter }}"  id="nextchap" class="text-dark" title="{{ $chapterNext['name'] }}">
                                    Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $nextChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterNext['id'] }}" data-chapter-name="{{ $chapterNext['name'] }}"
                                   data-price="{{ number_format($chapterNext['price']) }}" class="text-primary order"
                                   title="{{ $chapterNext['name'] }}"  id="nextchap" >
                                    <i class="fa fa-lock"></i> Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}"  id="nextchap"> Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span> </a>
                    @endif
                    <div class="modal fade menu-modal list-chap-mobi" id="list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                         style="font-size: 12px;">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title text-danger text-center d-inline-block w-100">Danh sách chương</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body"
                                     style="max-height:80vh;overflow:auto;font-size:16px;line-height:26px">
                                    <?php $readed = true ?>
                                    @if(!empty($chapters))
                                        @foreach($chapters as $chap)
                                            @if((@$chap->timer && @$chap->timer <= date('Y-m-d H:i:s')) || !@$chap->timer)
                                                @php
                                                    if (@$chap['embed_link']) {
                                                    if (!$chapterLastReaded) {
                                                    $readed = false;
                                                    }
                                                    if($chapterLastReaded != '' && ($chapterLastReaded == $chap['order'])) {
                                                    $link = 'chaplastreaded';
                                                    $readed = false;
                                                    }
                                                    else if($readed) $link = 'chapreaded';
                                                    else $link = '';
                                                    } else {
                                                    if($chapterLastReaded == $chap['order']) $link = 'chaplastreaded';
                                                    else if($chapterLastReaded < $chap['order'] ) $link='' ; else $link='chapreaded' ; }
                                                    if (!empty(app('request')->input('id')) && !empty($chap['id']) && app('request')->input('id') == $chap['id']){
                                                        $link = 'chapreading';
                                                    } elseif(!empty(app('request')->input('link'))) {
                                                        $chapterLink = $chap['link_other'] ?? ($chap['embed_link'] ?? '');
                                                        if (app('request')->input('link') == $chapterLink){
                                                            $link = 'chapreading';
                                                        }
                                                    }
                                                        $linkStory=@$chap['embed_link'] ? @$chap['link_other'] ?? route('chapters.show',
                                                        [$story->id, 'link' => @$chap['embed_link']]) : @$chap['link_other'] ??
                                                        route('chapters.show', [$story->id, 'id' => $chap['id']]);
                                                @endphp
                                                <div class="listss {{ $link }}">

                                                    @if(currentUser())
                                                        @if(@$chap['is_vip'] == 1)
                                                            @if( $check)
                                                                <a href="{{ $linkStory }}" class="text-success {{ empty($link) ? 'text-vip' : '' }}" title="{{ $chap['name'] }}">
                                                                    <b>VIP</b> {{ Str::limit($chap['name'], 100) }}
                                                                </a>
                                                            @else
                                                                <a href="javascript:;" data-url="{{ $linkStory }}"
                                                                   data-story="{{ $story->id }}" data-chapter="{{ @$chap['id'] }}"
                                                                   data-chapter-name="{{ @$chap['name'] }}"
                                                                   data-price="{{ number_format(@$chap['price']) }}"
                                                                   class="text-success order" title="{{ $chap['name'] }}">
                                                                    {{ Str::limit($chap['name'], 100) }}
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="{{ $linkStory }}" class="text-success" title="{{ $chap['name'] }}">
                                                                {{ Str::limit($chap['name'], 100) }} </a>

                                                        @endif
                                                    @else
                                                        <a href="{{ $linkStory }}" class="text-success "
                                                           title="{{ $chap['name'] }}">
                                                            {{ Str::limit($chap['name'], 100) }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content-container " class="container" style="border-top: 1px dashed #eee;border-bottom: 1px dashed #eee;min-height: 300px;padding: 0px;">
                <div class="chapter-info">
                    <p class="text-secondary">{{ $chapter->name}}</p>
                </div>
                <div class="contentbox" id="maincontent" style="font-family:sans-serif;padding-top: 24px;font-size:18px; background: rgba(234, 228, 211, 0.7);">
                    <div class="p-2">
                        @if((currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=>@$chapter->id, 'user_id' => currentUser()->id ])->first() && $chapter->is_vip) || !$chapter->is_vip || $check)
                            {!! $chapter->content !!}
                        @else
                            Chương chưa được mua!
                        @endif
                    </div>
                </div>
            </div>
            <div class="tm-reader-top-nav" style="font-size: 16px;padding: 6px 0;font-weight: 500">
                <div class="container d-flex" style="justify-content: space-between;">
                    @if($chapterPre != null)
                        @if(@$chapterPre['is_vip'] == 0)
                            <a href="{{ $preChapter }}" class="text-dark"><span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước</a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> $chapterPre['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check)
                                <a href="{{ $preChapter }}" class="text-dark" title="{{ $chapterPre['name'] }}">
                                    <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước <i class="fa fa-check"></i>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $preChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterPre['id'] }}" data-chapter-name="{{ $chapterPre['name'] }}"
                                   data-price="{{ number_format($chapterPre['price']) }}" class="text-dark order"
                                   title="{{ $chapter['name'] }}">
                                    <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước <i class="fa fa-lock"></i>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}" class="text-dark"><span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-left"></i></span> Chương trước
                        </a>
                    @endif
                    @if((new \Jenssegers\Agent\Agent())->isDesktop())
                        <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list"> Mục lục </a>
                    @endif
                    @if((new \Jenssegers\Agent\Agent())->isMobile())
                        <a href="javascript:void(0);" class="text-primary" data-bs-toggle="modal" data-bs-target="#list">
                            <img src="{{ asset('frontend/images/mobile/menu-icon.svg') }}" alt="">
                        </a>
                    @endif
                    @if($chapterNext != null)
                        @if(@$chapterNext['is_vip'] == 0)
                            <a href="{{ $nextChapter }}" class="text-dark">Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span></a>
                        @else
                            @if(currentUser() && \App\Domain\Admin\Models\Order::where(['chapter_id'=> @$chapterNext['id'],
                            'user_id' => currentUser()->id
                            ])->first() && currentUser()->id || $check )
                                <a href="{{ $nextChapter }}" class="text-dark" title="{{ $chapterNext['name'] }}">
                                    Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span>
                                </a>
                            @else
                                <a href="javascript:;" data-url="{{ $nextChapter }}" data-story="{{ $story->id }}"
                                   data-chapter="{{ $chapterNext['id'] }}" data-chapter-name="{{ $chapterNext['name'] }}"
                                   data-price="{{ number_format($chapterNext['price']) }}" class="text-dark order"
                                   title="{{ $chapter['name'] }}">
                                    <i class="fa fa-lock"></i> Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span>
                                </a>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('story.show', $story) }}" class="text-dark"> Chương sau <span class="btn btn-light rounded-circle btn-redirect"><i class="fa fa-arrow-right"></i></span></a>
                    @endif
                </div>
            </div>
            {{--        <div style="width: 100%;" class="container mt-3">--}}
            {{--            <button id="originbutton" class="btn bg-light"><i class="fas fa-external-link-alt"></i> Xem trang gốc</button>--}}
            {{--            <select class="btn bg-light">--}}
            {{--                <option value="name">Dịch thuật</option>--}}
            {{--                <option value="name">Bật name(Mặc định)</option>--}}
            {{--                <option value="noname">Tắt name(Giảm lag)</option>--}}
            {{--                <option value="morehan">Hán Việt nhiều</option>--}}
            {{--                <option value="extrahan">Style wikidich</option>--}}
            {{--                <option value="chinese">Văn bản gốc</option>--}}
            {{--            </select>--}}
            {{--        </div>--}}
            {{--        <br>--}}
            {{--       @include('shop.layouts.comment',['type' => "App/Domain/Chapter/Models/Chapter",'id' => $chapter->id ])--}}
        </div>
        <?php
        $origin = 'dich';
        if ($story->origin) {
            if (strpos($story->origin, 'faloo') !== false) {
                $origin = 'faloo';
            }
            if (strpos($story->origin, 'uukanshu') !== false) {
                $origin = 'uukanshu';
            }
            if (strpos($story->origin, 'qidian') !== false) {
                $origin = 'qidian';
            }
            if (strpos($story->origin, 'bxwxorg') !== false) {
                $origin = 'bxwxorg';
            }
        }
        ?>
        <span id="hiddenid" hidden="">{{$story->id}};{{ $chapter['id'] }};{{ $origin }}</span>
        <div class="related-stories">
            <h4 class="title">
                Có thể bạn sẽ thích
            </h4>
            <div class="all-book-related">
                <div class="group-book d-flex">
                    @foreach($listRelated as $items)
                            <div class="cap book-item">
                                <a href="{{ route('story.show', $items) }}" class="box-card-story">
                                <span class="card-img d-block position-relative">
                                    <img data-src="{{ $items->avatar ?? $items->getFirstMediaUrl('default') }}" class="lazyload" alt="{{ $items->name }}">
                                    <span class="count-chapter btn btn-danger position-absolute">{{ $items->count_chapters }}</span>
                                </span>
                                    <span>{{ $items->name }} </span>
                                </a>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
        <?php
        $origin = 'dich';
        if ($story->origin) {
            if (strpos($story->origin, 'faloo') !== false) {
                $origin = 'faloo';
            }
            if (strpos($story->origin, 'uukanshu') !== false) {
                $origin = 'uukanshu';
            }
            if (strpos($story->origin, 'qidian') !== false) {
                $origin = 'qidian';
            }
            if (strpos($story->origin, 'bxwxorg') !== false) {
                $origin = 'bxwxorg';
            }
        }
        ?>
        <span id="hiddenid" hidden="">{{$story->id}};{{ $chapter['id'] }};{{ $origin }}</span>

    </section>
    @if(!empty(currentUser()->id))
        @include('shop.chapter.stv')
    @endif
@endif
@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.fn.extend({
            disableSelection: function() {
                this.each(function() {
                    this.onselectstart = function() {
                        return false;
                    };
                    this.unselectable = "on";
                    $(this).css('-moz-user-select', 'none');
                    $(this).css('-webkit-user-select', 'none');
                });
                return this;
            }
        });
        $(function() {
            $('.notSelectable').disableSelection();

            $('.order').on('click', function() {
                var story = $(this).attr('data-story');
                var chapter = $(this).attr('data-chapter-name');
                var price = $(this).attr('data-price');

                // Swal.fire({
                //     title: 'Bạn muốn mua chương này ?',
                //     text: `Chương ${chapter} giá 99 vàng`,
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Tất nhiên rồi!',
                //     cancelButtonText: 'Chưa phải lúc này!'
                // }).then((result) => {
                //     if (result.isConfirmed) {
                $(this).attr("disabled", true);
                var href = $(this).attr('data-url');
                var chapter = $(this).attr('data-chapter');
                var url = "{{ route('user.order.chapter') }}";
                $.post({
                    url: url,
                    data: {
                        chapter: chapter,
                        story: story
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                //     }
                // })

            })

            $(document).keydown(function(event) {
                var event = document.all ? window.event : arguments[0];
                console.log(event.keyCode)
                if (event.keyCode == 123 || event.keyCode == 44) { // Prevent F12 and print screen
                    return false;
                } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I
                    return false;
                }else{

                    if (event.keyCode == 37) document.getElementById("prevchap").click();

                    if (event.keyCode == 39) document.getElementById("nextchap").click();
                }
            });

            $('body').bind('copy cut', function(e) {
                e.preventDefault();
                return false;
            });
            window.addEventListener('selectstart', function(e) {
                e.preventDefault();
            });
            document.addEventListener('contextmenu', event => event.preventDefault()); //block chuột phải và bôi đen
        })
    </script>
@endsection
