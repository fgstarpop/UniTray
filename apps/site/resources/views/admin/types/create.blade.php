@extends('admin.layouts.master')

@section('title', __('Tạo'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.types._form', [
        'url' =>  route('admin.types.store'),
        'type' => new \App\Domain\Type\Models\Type,
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
