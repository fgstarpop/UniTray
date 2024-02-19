@extends('shop.layouts.app')
@section('title')
    {{ __('Không tìm thấy trang') }} @if(!empty(setting('store_name')))
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
    <div class="container mt-4 mb-4 content main-list-user">
        <div class="d-table text-center w-100">
            <div class="d-table-cell">
                <div class="error" style="padding: 50px 0px;">
                    {{--<img class="error-image" src="{{ asset('frontend/img/error.png') }}" alt="image">--}}
                    <div class="section-title">
                        <h2>{{ __('Lỗi 404 - Không tìm thấy trang') }}</h2>
                        <p>{{ __('Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không có sẵn.') }}</p>
                    </div>
                    <div class="error-btn">
                        <a href="{{ route('home') }}" class="default-btn">{{ __('Về trang chủ') }}<span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
