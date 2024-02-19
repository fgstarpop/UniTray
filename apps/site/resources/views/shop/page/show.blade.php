@extends('shop.layouts.app')
@section('title')
    {{ $page->title }} @if(!empty(setting('store_name')))
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
    <meta name="title" content="{{ $page->title }}">
    <meta name="description" content="{{ $page->title }}">
    <meta name="keywords" content="{{ $page->title }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:description" content="{{ $page->title }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.webp') }}">
    <meta property="og:site_name" content="giangthe.com">
@stop
@section('content')
    <!-- main-area -->
    <main>
        <!-- breadcrumb-area -->
        {{--@include('shop.layouts.partials.breadcrumb', [--}}
             {{--'name' => $page->title--}}
        {{--])--}}
        <!-- breadcrumb-area-end -->
        <!-- show-content -->
        <div class="offer-area mt-5 mb-5">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{{ $page->title }}</h2>
                </div>
                <div class="row mt-20">
                    <div class="col-sm-12">
                        {!! $page->body !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- show-content-end -->
    </main>
    <!-- main-area-end -->
@endsection
