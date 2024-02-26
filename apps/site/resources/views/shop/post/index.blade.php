@extends('shop.layouts.app')
@section('title')
    {{ 'Hướng Dẫn Sử Dụng' }}
    @if(!empty(setting('store_name')))
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
    <meta name="title" content="{{ 'Hướng Dẫn Sử Dụng' }}">
    <meta name="description" content="{{ 'Hướng Dẫn Sử Dụng' }}">
    <meta name="keywords" content="{{ 'Hướng Dẫn Sử Dụng' }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ 'Hướng Dẫn Sử Dụng' }}">
    <meta property="og:description" content="{{ 'Hướng Dẫn Sử Dụng' }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ setting('store_logo') ? \Storage::url(setting('store_logo')) : '' }}">
    <meta property="og:site_name" content="{{ url('') }}">
@stop
@section('content')

    <!-- main-area -->
    <main>
        <!-- show-content -->
        <div class="offer-area mt-2 mb-5">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{{ 'Hướng Dẫn Sử Dụng' }}</h2>
                </div>

                <div class="row mt-20 main-list">
                    <div class="col-sm-12">
                        @if($posts->isNotEmpty())
                            @foreach($posts as $post)
                                <div class="post-index">
                                    <a href="{{ $post->url() }}">{{ $post->title }}</a>
                                    <p>{{ $post->description }}</p>
                                </div>
                            @endforeach
                        @else
                            <p>Không có dữ liệu !</p>
                        @endif
                    </div>
                    <div class="paginate">
                    {!! $posts->links() !!}
                </div>
                </div>

            </div>
        </div>
        <!-- show-content-end -->
    </main>
    <!-- main-area-end -->
@endsection
