@extends('admin.layouts.master')

@section('title', __('Tạo'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.categories._form', [
        'url' =>  route('admin.categories.store'),
        'category' => new \App\Domain\Category\Models\Category,
    ])
@stop

@push('js')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#title').focus();
        });
        $('.form-check-input-styled').uniform();
    </script>
@endpush
