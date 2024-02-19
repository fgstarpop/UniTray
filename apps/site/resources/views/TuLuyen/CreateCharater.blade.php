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
        content="{{ setting('logo') ? \Storage::url(setting('logo')) : asset('frontend/img/logo/logo.webp') }}">
    <meta property="og:site_name" content="{{ url('/') }}">
@stop
@push('styles')
    <style>
    </style>
@endpush
@section('content')
@livewire('tuluyen.createcharater', ['user_id' => @$user->id])

@endsection
