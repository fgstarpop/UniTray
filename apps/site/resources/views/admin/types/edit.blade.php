@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $type->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render('admin.types.edit', $type) }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.types._form', [
        'url' =>  route('admin.types.update', $type),
        'type' => $type ?? new \App\Domain\Type\Models\Type,
        'method' => 'PUT'
    ])
@stop

@push('js')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $('.form-check-input-styled').uniform();
    </script>
@endpush
