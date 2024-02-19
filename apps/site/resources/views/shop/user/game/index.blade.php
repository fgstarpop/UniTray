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
<div class="container d-flex soxo position-relative ">
    <button class="btn btn-gt mt-3"  data-bs-toggle="modal" data-bs-target="#gift">Lịch sử giải đặc biệt</button>
    <h1 class="text-center mt-3">Xổ Số Giáng Thế</h1>
    <a class=" mt-3 btn btn-gt text-white" href="{{ route('user.game.create')}}"> Đặt số <i class="fal fa-arrow-right"></i></a>
    <div class="modal fade" id="gift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header text-white d-flex" style=" justify-content: center;background-color:#BF0000">
                    <h6 class="modal-title">Lịch sử giải đặc biệt</h6>
                </div>
                <div class="modal-body fs-6">
                @if(count($numberWin))
                @foreach($numberWin as $list)
                <div class="d-flex" style="justify-content: space-between;">
                    <span>Ngày {{ $list->created_at->format('d-m-Y')  }}</span>
                    <span>{{$list->number }}</span>
                </div>
                @endforeach
                @else
                <div class="text-center">Không có dữ liệu</div>
                @endif

                </div>
            </div>
        </div>
    </div>
</div>
<h3 class="text-center mt-2 victory"><span>VINH DANH TOP 10 NGƯỜI TRÚNG GIẢI</span> </h3>
@php
use Carbon\Carbon;
$tomorrow = new Carbon('tomorrow midnight');
$today =  new Carbon('today midnight');
$number = \App\Domain\Admin\Models\Numbers::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
@endphp
@if($number)
<h4 class="text-center mt-2">Ngày {{ \Carbon\Carbon::today()->format('d-m-Y') }}</h4>
@else
<h4 class="text-center mt-2">Ngày {{ \Carbon\Carbon::yesterday()->format('d-m-Y') }}</h4>
@endif
<section class="position-relative mt-5 mb-5 text-center table-responsive" style="min-height:300px">
<table class="table text-center mt-2 winner fs-6">
    <tbody>
            @if(count($win))
            @foreach($win as $key => $list)
           
        <tr>
            <td><span class="w-100">No. {{ ++$key }}</span></td>
            <td><a href="{{ route('user.index',$list->user->id) }}"><span class="w-100 {{$key == 1 ? 'first' :''}}"><img src="{{ pare_url_file($list->user->avatar,'user') }}" class="img-user" />{{ $list->user->name }}</span> </a></td>
            <td><span class="w-100">{{ number_format((int)$list->gold) }} vàng</span></td>
        </tr>
            @endforeach
            @else
        <tr>
            <td colspan="4">Không có ai trúng giải hôm nay</td>
        </tr>
            @endif
    </tbody>
</table>
</section>
@endsection
@section('scripts')
<script src="{{ asset('frontend/js/game.js') }}"></script>
@endsection
