@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $category->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render('admin.categories.edit', $category) }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.categories._form', [
        'url' =>  route('admin.categories.update', $category),
        'category' => $category ?? new \App\Domain\Category\Models\Category,
        'method' => 'PUT'
    ])
@stop

@push('js')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $('.form-check-input-styled').uniform();
    </script>
@endpush
