@extends('shop.layouts.app')
@section('title')
Xổ Số Giáng Thế
@endsection
@push('styles')
<style>
.cs{cursor:pointer}
.active{background-color:#88ff99 !important;color:#000}
.number{background-color: #fff;}
.modal-body::-webkit-scrollbar {
    width: 1px;
    height: 4px;}
</style>
@endpush

@push('scripts')

@endpush
@section('content')

<h3 class="text-center mt-2"><span>DONATE</span> </h3>

<section class="position-relative mb-5 text-center table-responsive container mt-4 mb-4 content main-list-user" style="min-height:300px">

<table class="table text-center mt-4 container"  style="min-width:500px;">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Truyện</th>
                    <th scope="col">Số tiền donate</th>
                    <th scope="col">Số tiền nhận được</th>
                    <th scope="col">Số tiền donate hôm nay</th>
                    <th scope="col">Thời gian</th>
                    <th></th> 
                </tr>
            </thead>
            <tbody>
            @if(count($donate))
            @foreach($donate as $key => $list)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td style="text-align:left"><a  href="{{ route('story.show', $list) }}"><span class="w-100 text-success"><img src="{{ $list->avatar ?? $list->getFirstMediaUrl('default') }}" class="img-user" />{{ $list->name }}</span></a></td>
                    <td><span class="w-100">{{ number_format($list->donate) }} vàng</span></td>
                    <td><span class="w-100">{{ number_format($list->donate - $list->donate * 15 /100 ) }} tệ</span></td>
                    <td><span class="w-100">{{ number_format((int)$list->donate_today) }} vàng</span></td>
                    <td>{{ $list->created_at }}</td>
                    <td><span class="w-100" style="color: rgb(112 192 223);text-decoration-line: underline;cursor:pointer"  data-bs-toggle="modal" data-bs-target="#donate{{$key}}">Chi tiết</span></td>
                    <td>
                                <div class="modal fade" id="donate{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;top:75px">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Danh sách donate truyện {{ $list->name }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
                                            </div>
                                            <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                                                <table class="table text-center mt-4"  style="min-width:600px;">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">STT</th>
                                                            <th scope="col">Người tặng</th>
                                                            <th scope="col">Số tiền</th>
                                                            <th scope="col">Thời gian</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($list->listUser as $val => $value)
                                                        <tr>
                                                            <td><span class="w-100">{{ ++$val }}</span></td>
                                                            <td><span class="w-100"><a href="{{ route('user.index',currentUser()->id) }}" class="text-success">{{ $value->name }}</a></span></td>
                                                            <td><span class="w-100">{{ number_format((int)$value->totalGold) }} vàng</span></td>
                                                            <td><span class="w-100">{{ $value->updated_at }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="modal-footer"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                </tr>
            @endforeach
            @else
                <tr>
                <td colspan="6">Không có dữ liệu</td>
                </tr>
            @endif
            </tbody>
          </table>
          @if(count($donate))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $donate->appends(request()->input())->links() !!}
                    </nav>
                </div>
            @endif
</section>
@endsection
{{--@section('scripts')--}}
{{--<script src="{{ asset('frontend/js/game.js') }}"></script>--}}
{{--@endsection--}}
