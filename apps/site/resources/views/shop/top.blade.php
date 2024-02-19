@extends('shop.layouts.app')
@section('title')
Xổ Số Giáng Thế
@endsection
@push('styles')
<style>
.cs{cursor:pointer}
.active{background-color:#88ff99 !important;color:#000}
.number{background-color: #fff;}
</style>
@endpush

@push('scripts')

@endpush
@section('content')
 
<h3 class="text-center mt-2 victory"><span>TOP NẠP THẺ</span> </h3>
@php
use Carbon\Carbon;
$tomorrow = new Carbon('tomorrow midnight');
$today =  new Carbon('today midnight');
$number = \App\Domain\Admin\Models\Numbers::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
@endphp
 
<section class="position-relative mt-5 mb-5 text-center table-responsive" style="min-height:300px">
<table class="table text-center winner fs-6">
    <tbody>
            @if(count($top))
            @foreach($top as $key => $list)
           
        <li>
        <tr>
            <td><a href="{{ route('user.index',$list->user->id) }}"><span class="w-100 {{ $key == 0 ? 'first':'' }}"><img src="{{ pare_url_file($list->user->avatar,'user') }}" class="img-user" />{{ $list->user->name }}</span> </a></td>
            <td><span class="w-100">{{ number_format((int)$list->vnd_in_month) }} vàng</span></td>
        </tr>
            @endforeach
            @else
        <tr>
            <td colspan="4">Không có dữ liệu</td>
        </tr>
        </li>
            @endif
    </tbody>
</table>
</section>
@endsection
@section('scripts')
<script src="{{ asset('frontend/js/game.js') }}"></script>
@endsection
