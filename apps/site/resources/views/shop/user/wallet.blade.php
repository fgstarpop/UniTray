@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <section class="container mt-4 content main-list-user">
        <h2 class="text-center">Ví của tôi</h2>
        <div class="container d-flex " style="justify-content: space-evenly;">
            <p><a href="javascript:;" title="Vàng"><img src="{{ asset('frontend/images/gold.jpg') }}" alt="Vàng"></a> :
                {{ number_format((int)$wallet->gold) }}</p>
            @if(currentUser()->is_vip == 1)
            <p><a href="javascript:;" title="Tệ"><img src="{{ asset('frontend/images/silver.jpg') }}" alt="Tệ"></a> :
                {{ number_format((int)$wallet->silver) }}</p>
            @endif
        </div>
        <h3 class="text-center">Lịch sử giao dịch</h3>
        <div class="table-responsive">
            <table class="table text-center" style="min-width:1000px">
                <thead>
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Mã giao dịch</th>
                        <th scope="col">Loại giao dịch</th>
                        <th scope="col">Số vàng</th>
                        @if(currentUser()->is_vip == 1)
                        <th scope="col">Số tệ</th>
                        @endif
                        <th scope="col">Số dư tài khoản</th>
                        <th scope="col">Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($transaction))
                    @foreach($transaction as $key => $list)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $list->transaction_id }}</td>
                        <td>
                            @if($list->transaction_type == 5)
                            Mua chương VIP
                            @elseif($list->transaction_type == 4)
                            Trò chơi
                            @elseif($list->transaction_type == 3)
                            Donate
                            @elseif($list->transaction_type == 2)
                            Tặng vàng
                            @elseif($list->transaction_type == 1)
                            Rút tiền
                            @elseif($list->transaction_type == 0)
                            Nạp tiền
                            @elseif($list->transaction_type == 6)
                            Cá cược world cup
                            @elseif($list->transaction_type == 7)
                            Đổi linh thạch
                            @elseif($list->transaction_type == 8)
                            Thưởng click quảng cáo và user đăng ký VIP
                            @endif
                        </td>
                        @if($list->change_type == 1 )
                        <td class="text-danger">- {{ number_format($list->gold) }}</td>
                        @if(currentUser()->is_vip == 1)
                        <td class="text-danger">- {{ number_format($list->yuan) }}</td>
                        @endif
                        @else
                        <td class="text-success">+ {{ number_format($list->gold) }}</td>
                        @if(currentUser()->is_vip == 1)
                        <td class="text-success">+ {{ number_format($list->yuan) }}</td>
                        @endif
                        @endif
                        <td class="d-flex" style="justify-content:space-around">
                            <span>Vàng : {{ number_format((int)$list->gold_balance) }}</span>
                            @if(currentUser()->is_vip == 1)
                            <span>Tệ : {{  number_format((int)$list->yuan_balance) }}</span>
                            @endif
                        </td>
                        <td>{{ $list->created_at }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7">Bạn chưa có giao dịch nào !</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @if(count($transaction))
                    <div style="text-align: center;width: 100%" id="searchpagi"><br>
                        <nav aria-label="..." style="display: inline-block;">
                            {!! $transaction->appends(request()->input())->links() !!}
                        </nav>
                    </div>
                @endif
        </div>
    </section>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
<style>
    .btn-custom {
        background-color: transparent;
        border-color: #C9B708;
        border-style: solid;
        border-width: 2px;
        min-width: 140px;
        color: #C9B708;
    }
    .btn-custom.active {
        background-color: #C9B708;
        color: #ffffff;
    }
</style>
<section class="container mt-4 content search-section">
    <div class="mt-4">
        <div class="header-find d-flex justify-content-evenly pb-4 mb-4 border-bottom">
            <a href="{{ route('user.recharge') }}" class="text-danger text- d-block">Ví của tôi</a>
            <a href="javascript:void(0)" class="text-danger d-block font-weight-bold">Lịch sử</a>
        </div>
        <div class="kind-history">
            <div class="tabs d-flex justify-content-evenly mb-3">
                <button class="btn rounded-pill btn-custom active" data-type="gold">Vàng</button>
                <button class="btn rounded-pill btn-custom" data-type="stone">Linh thạch</button>
            </div>
            <div class="content">
                <div id="gold" class="history-list gold-history">
                    @if(count($transaction))
                        @foreach($transaction as $key => $list)
                            <ul class="item py-2 px-4 mb-2 bg-white border-2" style="border-color: #C9B708; border-style: solid; border-radius: 5px">
                                <li>Thời gian: {{ date('H:i d/m/Y', strtotime($list->created_at)) }}</li>
                                <li>Thay đổi:
                                    @if($list->change_type == 1 )
                                        <span class="text-danger">- {{ number_format($list->gold) }}</span>
                                        @if(currentUser()->is_vip == 1)
                                            <br>
                                            <span class="text-danger">- {{ number_format($list->yuan) }}</span>
                                        @endif
                                    @else
                                        <span class="text-success">+ {{ number_format($list->gold) }}</span>
                                        @if(currentUser()->is_vip == 1)
                                            <br>
                                            <span class="text-success">+ {{ number_format($list->yuan) }}</span>
                                        @endif
                                    @endif
                                </li>
                                <li>Số dư: <strong>{{ number_format((int)$list->gold_balance) }}</strong></li>
                                @if(currentUser()->is_vip == 1)
                                <li>Số tệ: <strong>{{ number_format((int)$list->yuan_balance) }}</strong></li>
                                @endif
                                <li>Nội dung:
                                    @if($list->transaction_type == 5)
                                        Mua chương VIP
                                    @elseif($list->transaction_type == 4)
                                        Trò chơi
                                    @elseif($list->transaction_type == 3)
                                        Donate
                                    @elseif($list->transaction_type == 2)
                                        Tặng vàng
                                    @elseif($list->transaction_type == 1)
                                        Rút tiền
                                    @elseif($list->transaction_type == 0)
                                        Nạp tiền
                                    @elseif($list->transaction_type == 6)
                                        Cá cược world cup
                                    @elseif($list->transaction_type == 7)
                                        Đổi linh thạch
                                    @elseif($list->transaction_type == 8)
                                        Thưởng click quảng cáo và user đăng ký VIP
                                    @elseif($list->transaction_type == 9)
                                        Thưởng TOP bảng xếp hạng tháng
                                    @endif
                                </li>
                            </ul>
                        @endforeach
                    @else
                        <ul class="item py-2 px-4 mb-2 bg-white border-2" style="border-color: #C9B708; border-style: solid; border-radius: 5px">
                            <li>Bạn chưa có giao dịch nào !</li>
                        </ul>
                    @endif
                    @if(count($transaction))
                        <div style="text-align: center;width: 100%" id="searchpagi"><br>
                            <nav aria-label="..." style="display: inline-block;">
                                {!! $transaction->appends(request()->input())->links() !!}
                            </nav>
                        </div>
                    @endif
                </div>
                <div id="stone" class="history-list stone-history hidden">
{{--                    @if(count($transaction))--}}
{{--                        @foreach($transaction as $key => $list)--}}
{{--                            <ul class="item py-2 px-4 mb-2 bg-white border-2" style="border-color: #C9B708; border-style: solid; border-radius: 5px">--}}
{{--                                <li>Thời gian: {{ date('H:i d/m/Y', strtotime($list->created_at)) }}</li>--}}
{{--                                <li>Thay đổi:--}}
{{--                                    @if($list->change_type == 1 )--}}
{{--                                        <span class="text-danger">- {{ number_format($list->gold) }}</span>--}}
{{--                                        @if(currentUser()->is_vip == 1)--}}
{{--                                            <br>--}}
{{--                                            <span class="text-danger">- {{ number_format($list->yuan) }}</span>--}}
{{--                                        @endif--}}
{{--                                    @else--}}
{{--                                        <span class="text-success">+ {{ number_format($list->gold) }}</span>--}}
{{--                                        @if(currentUser()->is_vip == 1)--}}
{{--                                            <br>--}}
{{--                                            <span class="text-success">+ {{ number_format($list->yuan) }}</span>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}
{{--                                </li>--}}
{{--                                <li>Số dư: <strong>{{ number_format((int)$list->gold_balance) }}</strong></li>--}}
{{--                                @if(currentUser()->is_vip == 1)--}}
{{--                                    <li>Số tệ: <strong>{{ number_format((int)$list->yuan_balance) }}</strong></li>--}}
{{--                                @endif--}}
{{--                                <li>Nội dung:--}}
{{--                                    @if($list->transaction_type == 5)--}}
{{--                                        Mua chương VIP--}}
{{--                                    @elseif($list->transaction_type == 4)--}}
{{--                                        Trò chơi--}}
{{--                                    @elseif($list->transaction_type == 3)--}}
{{--                                        Donate--}}
{{--                                    @elseif($list->transaction_type == 2)--}}
{{--                                        Tặng vàng--}}
{{--                                    @elseif($list->transaction_type == 1)--}}
{{--                                        Rút tiền--}}
{{--                                    @elseif($list->transaction_type == 0)--}}
{{--                                        Nạp tiền--}}
{{--                                    @elseif($list->transaction_type == 6)--}}
{{--                                        Cá cược world cup--}}
{{--                                    @elseif($list->transaction_type == 7)--}}
{{--                                        Đổi linh thạch--}}
{{--                                    @endif--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        @endforeach--}}
{{--                    @else--}}
{{--                        <ul class="item py-2 px-4 mb-2 bg-white border-2" style="border-color: #C9B708; border-style: solid; border-radius: 5px">--}}
{{--                            <li>Bạn chưa có giao dịch nào !</li>--}}
{{--                        </ul>--}}
{{--                    @endif--}}

{{--                    @if(count($transaction))--}}
{{--                        <div style="text-align: center;width: 100%" id="searchpagi"><br>--}}
{{--                            <nav aria-label="..." style="display: inline-block;">--}}
{{--                                {!! $transaction->appends(request()->input())->links() !!}--}}
{{--                            </nav>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
@section('scripts')
<script>
$('.remove').on('click', function() {
    var val = $(this).attr('data-id');
    var url = "{{ route('user.package.delete') }}";
    $.post({
        url: url,
        data: {
            id: val
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $(this).parents('tr').hide();
        }
    })
})
</script>
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <script>
        $(document).ready(function() {
            $('.tabs .btn-custom').click(function () {
                $('.btn-custom').removeClass('active')
                $(this).addClass('active')
                $('.history-list').hide()
                $('#' + $(this).data('type')).show()
            })
        })
    </script>
@endif
@endsection
