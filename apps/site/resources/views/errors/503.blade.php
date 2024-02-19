@extends('shop.layouts.app')
@section('title')
    {{ __('Không có quyền truy cập') }} @if(!empty(setting('store_name')))
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
    <meta name="title" content="{{ setting('store_name') }} - {{ setting('store_slogan') }}">
    <meta name="description" content="{{ setting('store_description') }}">
    <meta name="keywords" content="{{ setting('store_name') }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ setting('store_name') }} - {{ setting('store_slogan') }}">
    <meta property="og:description" content="{{ setting('store_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ setting('store_logo') ? \Storage::url(setting('store_logo')) : '' }}">
    <meta property="og:site_name" content="{{ route('home') }}">
@stop
@section('content')
    <main>
        <div class="container">
            <div class="mt-5 mb-3">
                <h1>{{ __('503') }}</h1>
                <h3>{{ __(' Website đang bảo trì để update chức năng hoặc sửa lỗi. Quá trình sẽ kết thúc trước 7h sáng. Các bạn vui lòng quay lại sau!') }}</h3>

            </div>
        </div>
    </main>
@endsection
