@extends('shop.layouts.app')
@section('title')
    {{ $post->title }}
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
    <meta name="title" content="{{ $post->title }}">
    <meta name="description" content="{{ $post->title }}">
    <meta name="keywords" content="{{ $post->title }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->title }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ $post->getFirstMediaUrl('image') }}">
    <meta property="og:site_name" content="{{ url('') }}">
@stop
@push('styles')
@endpush
@section('content')
    <!-- main-area -->
    <main>
        <!-- show-content -->
        <div class="offer-area mt-5 mb-5">
            <div class="container">
                <div class="section-title text-center">
                    <h2>{{ $post->title }}</h2>
                </div>
                <div class="row mt-20 main-list">
                    <div class="col-sm-12"> 
                        {!! $post->body !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- show-content-end -->
    </main>
    <!-- main-area-end -->
@endsection
