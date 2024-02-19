@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $wallet->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render('admin.wallets.edit', $wallet) }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.wallets._form', [
        'url' =>  route('admin.wallets.update', $wallet),
        'wallet' => $wallet ?? new \App\Domain\Admin\Models\Wallet,
        'method' => 'PUT'
    ])
@stop 

@push('js')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $('.form-check-input-styled').uniform();
    </script>
@endpush
