@extends('shop.layouts.app')

@push('styles')
    <style>
        .modal-body::-webkit-scrollbar {
            width: 1px;
            height: 4px;
        }
    </style>
@endpush

@push('scripts')

@endpush
@section('content')
    <section class="container mt-4 content main-list-user">
        <h2 class="text-center" style="margin-bottom: 20px;">Đặt mua chương vip</h2>
        <div class="mt-4 table-responsive">

            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Tên truyện</th>
                    <th scope="col">Số tiền</th>
                    <th scope="col">Số lượt mua</th>
                    <th scope="col">Thời gian mua mới nhất</th>
                    <th scope="col">Tác vụ</th>
                </tr>
                </thead>
                <tbody>
                @if(count($order))
                    @foreach($order as $key => $list)
                        @if(!empty($list->story))
                            <tr>
                                <td><span class="w-150"><a href="{{ route('story.show', $list->story) }}" class="text-success">{{ $list->story->name }}</a></span></td>
                                <td><span class="w-100">{{ number_format($list->total_price) }}</span></td>
                                <td><span class="w-100">{{ number_format($list->total_chapter_buy) }}</span></td>
                                <td><span class="w-100">{{ $list->last_buy_at }}</span></td>
                                <td><span class="w-100" style="color: rgb(112 192 223);text-decoration-line: underline;cursor:pointer"  data-bs-toggle="modal" data-bs-target="#order{{$key}}">Chi tiết</span></td>
                                <td>
                                    <div class="modal fade" id="order{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;top:75px">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Danh sách mua truyện {{ $list->story->name }}</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
                                                </div>
                                                <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                                                    <table class="table text-center mt-4"  style="min-width:600px;">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">STT</th>
                                                            <th scope="col">Tên chương</th>
                                                            <th scope="col">Số tiền</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($list->orderChapter as $val => $value)
                                                            <tr>
                                                                <td><span class="w-100">{{ ++$val }}</span></td>
                                                                @if(!empty($value->chapter))
                                                                    <td><span class="w-100"><a href="{{ !empty(optional($value->chapter)->embed_link) ?  $value->chapter->link_other ?? route('chapters.show', [ $list->story->id, 'link' => $value->chapter->embed_link]) : $value->chapter->link_other ?? route('chapters.show', [ $list->story->id, 'id' => $value->chapter->id]) }}" class="text-success">{{ $value->chapter->name }}</a></span></td>
                                                                @else
                                                                    <td><span class="w-100"><a href="#" class="text-success">Không có</a></span></td>
                                                                @endif
                                                                <td><span class="w-100">{{ number_format((int)$value->price) }} vàng</span></td>
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
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">Bạn chưa mua chương truyện nào !</td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if(count($order))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $order->appends(request()->input())->links() !!}
                    </nav>
                </div>
            @endif
        </div>
    </section>
@endsection
