@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
@if((new \Jenssegers\Agent\Agent())->isDesktop())
<section class="container mt-4 content main-list-user position-relative">
    <h2 class="text-center recharge">Nạp tiền</h2>
    <div class="position-absolute add" style="top:15px;right:15px">
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#bank">Nạp tiền qua ngân hàng</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#card">Nạp tiền bằng thẻ cào</button>
    </div>
    <div class="table-responsive">
        <table class="table text-center mt-4">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Mã giao dịch</th>
                    <th scope="col">Số tiền</th>
                    <th scope="col">Số vàng</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Hình thức nạp</th>
                    <th scope="col">Thời điểm nạp</th>
                </tr>
            </thead>
            <tbody>
                @if(count($recharge))
                @foreach($recharge as $key =>$list)
                <tr>
                    <td><span class="w-100">{{ ++$key}}</span></td>
                    <td><span class="w-100">{{ $list->code }}</span></td>
                    <td><span class="w-100">{{ number_format((int)$list->vnd) }} VND</span></td>
                    <td><span class="w-100">{{ number_format((int)$list->vnd) }} </span></td>
                    <td>
                        @if($list->status == 0)
                        <span class="badge bg-warning w-100">Đang chờ</span>
                        @else
                        <span class="badge bg-success w-100">Đã nạp</span>
                        @endif
                    </td>
                    <td>
                        @if($list->type == 2)
                            Momo
                        @elseif($list->type == 1)
                            Thẻ cào
                        @else
                            Ngân hàng
                        @endif
                    </td>
                    <td><span class="w-100">{{ $list->created_at }}</span> @if($list->type)<a
                            href="{{ route('card.index') }}" style="text-decoration: underline;color: blue;">Chi
                            tiết</a>@endif</td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7">Bạn chưa có giao dịch nạp tiền nào</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if(count($recharge))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $recharge->appends(request()->input())->links() !!}
                    </nav>
                </div>
            @endif
    </div>
    <div class="modal fade" id="card" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Nạp tiền bằng thẻ cào</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" class="form-group">
                        @csrf
                        <div class="errors text-danger"></div><br>
                        <select class="form-select">
                            <option value="">Bảng phí đổi thẻ cào</option>
                            <option value="">VIETTEL - {{ setting('discount_vt',0) }} %</option>
                            <option value="">VINAPHONE - {{ setting('discount_vina',0) }} %</option>
                            <option value="">MOBIFONE - {{ setting('discount_mobile',0) }} %</option>
                            <option value="">VIETNAMOBILE - {{ setting('discount_vm',0) }} %</option>
                            <option value="">ZING - {{ setting('discount_zing',0) }} %</option>
                            <option value="">GATE - {{ setting('discount_gate',0) }} %</option>
                        </select><br>
                        <select name="" id="telco" class="form-select">
                            <option value="VIETTEL">VIETTEL</option>
                            <option value="VINAPHONE">VINAPHONE</option>
                            <option value="VIETNAMOBILE">VIETNAMOBILE</option>
                            <option value="MOBIFONE">MOBIFONE</option>
                            <option value="ZING">ZING</option>
                            <option value="GATE">GATE</option>
                        </select><br>
                        <input type="text" id="serial" placeholder="Mã serial" class="form-control"><br>
                        <input type="text" id="code" placeholder="Mã thẻ" class="form-control"><br>
                        <select name="" id="amount" class="form-select">
                            <option value="">--Mệnh giá--</option>
                            <option value="10000">10,000 đ</option>
                            <option value="20000">20,000 đ</option>
                            <option value="30000">30,000 đ</option>
                            <option value="50000">50,000 đ</option>
                            <option value="100000">100,000 đ</option>
                            <option value="200000">200,000 đ</option>
                            <option value="300000">300,000 đ</option>
                            <option value="500000">500,000 đ</option>
                            <option value="1000000">1,000,000 đ</option>
                        </select>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-gt cards">Nạp ngay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bank" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Nạp tiền qua ngân hàng</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        @csrf
                        <div class="error text-danger"></div>
                        <p class="text-secondary"><span class="text-danger">*</span>Bạn vui lòng chuyển khoản chính xác
                            nội dung chuyển khoản bên dưới hệ thống sẽ tự động cộng tiền cho bạn sau 2-5 phút sau khi
                            nhận được tiền</p>

                        <div class="text-secondary fs-6"><span class="text-danger">*</span>Nạp qua ngân hàng</div>
                        <ul>
                            <li>Số tài khoản : 0962759343</li>
                            <li>Chủ tài khoản : DANG QUOC DAT</li>
                            <li>Ngân hàng : MB Bank </li>
                            <li>Chi nhánh : Nam Định</li>
                        </ul>

                        <div class="text-secondary fs-6"><span class="text-danger">*</span>Nạp qua momo</div>
                        <ul>
                            <li>Số tài khoản : 0962.759.343</li>
                            <li>Chủ tài khoản : DANG QUOC DAT</li>
                        </ul>
                        <p class="text-secondary"><span class="text-danger">Quy đổi : 1.000 đ = 1.000 vàng</p>
                        <p>Bạn đang có {{ number_format((int)$wallet->gold) }} vàng</p>
                        <p>Mã giao dịch</p>
                        <p><input class="form-control code" type="text" value="GT{{ Illuminate\Support\Str::random(6) }}"
                                readonly></p>

                        <p>Số tiền (VNĐ)</p>
                        <p><input class="form-control vnd" type="text" value="0" placeholder="Nhập số tiền muốn nạp">
                        </p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-gt recharges">Nạp ngay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
    @php
    $price_config = [
        [
            'stone' => 200,
            'gold' => 10000,
            'bonus' => 0
        ],
        [
            'stone' => 1000,
            'gold' => 50000,
            'bonus' => 50
        ],
        [
            'stone' => 2000,
            'gold' => 100000,
            'bonus' => 100
        ],
        [
            'stone' => 10000,
            'gold' => 500000,
            'bonus' => 500
        ],
    ];
    @endphp
    <style>
        .btn-custom-action,
        .btn-custom {
            background-color: transparent;
            border-color: #C9B708;
            border-style: solid;
            border-width: 2px;
            min-width: 140px;
            color: #C9B708;
        }
        .btn-custom-action.active,
        .btn-custom.active {
            background-color: #C9B708;
            color: #ffffff;
        }
        .price {
            min-height: 75px;
        }
        .price-items .active {
            background-color: #C9B708 !important;
        }
        .price-items .active .price-stone .full-price span,
        .price-items .active .price-stone .bonus-price {
            color: #ffffff !important;
        }
    </style>
    <section class="container mt-4 content search-section">
        <div class="mt-4">
            <div class="header-find d-flex justify-content-evenly pb-4 mb-3">
                <a href="javascript:void(0)" class="text-danger text- d-block font-weight-bold">Ví của tôi</a>
                <a href="{{ route('user.wallets') }}" class="text-danger d-block">Lịch sử</a>
            </div>
            <div class="wallet py-4 mb-3 bg-white border-2 text-center" style="border-color: #C9B708; border-style: solid; border-radius: 5px">
                <div class="balance gold d-flex justify-content-between mb-2 mx-auto" style="max-width: 120px">
                    <img src="{{ asset('frontend/images/gold-icon.svg') }}" alt="Gold Icon">
                    <span>{{ number_format(currentUser()->get_gold->gold) }}</span>
                </div>
                <div class="balance gold d-flex justify-content-between mb-2 mx-auto" style="max-width: 120px">
                    <img style="width: 30px;"src="{{ asset('frontend/images/money.png') }}" alt="Tien">
                    <span>{{ number_format(currentUser()->get_gold->silver) }} Tệ</span>
                </div>
                <div class="balance stone d-flex justify-content-between mx-auto" style="max-width: 120px">
                    <img src="{{ asset('frontend/images/stone-icon.svg') }}" alt="Stone Icon">
                    @php
                        $tuluyen =  new \App\Traits\TuLuyenTraits(currentUser());
                        $players = $tuluyen->get_details();
                    @endphp
                    @if($players)
                        <span>{{ number_format($players['linh_thach']) }}</span>
                    @else
                        <span>{{ number_format(0) }}</span>
                    @endif
                </div>
            </div>
            <hr>
            <div class="kind-history">
                <div class="tabs d-flex justify-content-evenly mb-3">
                    <button class="btn rounded-pill btn-custom active" data-type="gold">Nạp tiền</button>
                    <button class="btn rounded-pill btn-custom" data-type="stone">Đổi linh thạch</button>
                </div>
                <div class="content">
                    <div class="transfer-form mb-4 active" id="gold">
                        <ul class="item py-2 px-4 mb-2 bg-white border-2"
                            style="border-color: #C9B708; border-style: solid; border-radius: 5px; line-height: 25px">
                            <li class="mb-1"><strong>Lưu ý khi nạp tiền:</strong></li>
                            <li>
                                <ol>
                                    <li style="list-style-position: inside; list-style-type: decimal;">
                                        Điền chính xác nội dung chuyển khoản, chuyển đúng STK.
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: decimal;">
                                        Số dư sẽ được cộng trong 2 phút, nếu không thấy cập nhật số dư vui lòng liên hệ Zalo: 0962.759.343
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: decimal;">
                                        Riêng với nạp thẻ bằng thẻ nạp, sai thông tin sẽ bị trừ 50% giá trị thẻ thật (do bên thứ 3 quy định)
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: decimal;">
                                        Quy đổi số dư: 1 VNĐ = 1 Vàng
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: decimal;">
                                        Số dư đã nạp sẽ không được hoàn trả với mọi lý do.
                                    </li>
                                </ol>
                            </li>
                        </ul>
                        <ul class="item py-2 px-4 mb-2 bg-white border-2"
                            style="border-color: #C9B708; border-style: solid; border-radius: 5px; line-height: 25px">
                            <li class="my-3 text-center">
                                <img src="{{ asset('frontend/images/acb-bank.png') }}" alt="MB Bank" width="200px">
                            </li>
                            <li class="mb-1">
                                <ul class="mx-auto" style="width: max-content">
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        STK: 12832187
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        Người nhận: DANG QUOC DAT
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        Nội dung chuyển khoản:
                                    </li>
                                </ul>
                            </li>
                            <li class="d-flex flex-column justify-content-between align-items-center">
                                <div class="text-copy">
                                    <img src="https://apiqr.web2m.com/api/generate/ACB/12832187/DANG%20QUOC%20DAT?amount=0&amp;memo=NAPTIEN{{ currentUser()->id }}"
                                         alt="" width="270px">
                                </div>
                                <button class="btn btn-custom-action active copy py-1 px-3">Mở app ngân hàng quét mã QR</button>
                            </li>
                        </ul>
{{--                        <ul class="item py-2 px-4 mb-2 bg-white border-2"--}}
{{--                            style="border-color: #C9B708; border-style: solid; border-radius: 5px; line-height: 25px">--}}
{{--                            <li class="my-3 text-center">--}}
{{--                                <img src="{{ asset('frontend/images/logo_Momo.png') }}" alt="MB Bank" width="200px">--}}
{{--                            </li>--}}
{{--                            <li class="mb-1">--}}
{{--                                <ul class="mx-auto" style="width: max-content">--}}
{{--                                    <li style="list-style-position: inside; list-style-type: initial;">--}}
{{--                                        STK: 0962.759.343--}}
{{--                                    </li>--}}
{{--                                    <li style="list-style-position: inside; list-style-type: initial;">--}}
{{--                                        Người nhận: DANG QUOC DAT--}}
{{--                                    </li>--}}
{{--                                    <li style="list-style-position: inside; list-style-type: initial;">--}}
{{--                                        Nội dung chuyển khoản:--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            <li class="d-flex justify-content-between align-items-center">--}}
{{--                                <div style="width: 70px"></div>--}}
{{--                                <div class="text-copy">--}}
{{--                                    <span class="btn" style="border-radius: 10px; background-color: #FFFBD1; color: #988A00">--}}
{{--                                        NAPTIEN{{ currentUser()->id }}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                                <button class="btn btn-custom-action active copy py-1 px-3" style="width: 70px; min-width: unset">Copy</button>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
                        <ul class="item py-2 px-4 mb-2 bg-white border-2"
                            style="border-color: #C9B708; border-style: solid; border-radius: 5px; line-height: 25px">
                            <li class="my-3 text-center">
                                <img src="{{ asset('frontend/images/logo_Paypal.png') }}" alt="MB Bank" width="200px">
                            </li>
                            <li class="mb-1">
                                <ul class="mx-auto" style="width: max-content">
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        STK: quocdatmkt@gmail.com
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        Người nhận: DANG QUOC DAT
                                    </li>
                                    <li style="list-style-position: inside; list-style-type: initial;">
                                        Nội dung chuyển khoản:
                                    </li>
                                </ul>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <div style="width: 70px"></div>
                                <div class="text-copy">
                                    <span class="btn" style="border-radius: 10px; background-color: #FFFBD1; color: #988A00">
                                        NAPTIEN{{ currentUser()->id }}
                                    </span>
                                </div>
                                <button class="btn btn-custom-action active copy py-1 px-3" style="width: 70px; min-width: unset">Copy</button>
                            </li>
                        </ul>
                        <ul class="item py-2 px-4 mb-2 bg-white border-2"
                            style="border-color: #C9B708; border-style: solid; border-radius: 5px; line-height: 25px">
                            <li class="my-3 text-center">
                                <img src="{{ asset('frontend/images/logo_network.png') }}" alt="MB Bank">
                            </li>
                            <li class="mb-1">
                                <p class="errors text-danger"></p>
                                <form method="post" action="" class="form-group text-right">
                                    @csrf
                                    <select class="form-select mb-2">
                                        <option value="">Bảng phí đổi thẻ cào</option>
                                        <option value="">VIETTEL - {{ setting('discount_vt',0) }} %</option>
                                        <option value="">VINAPHONE - {{ setting('discount_vina',0) }} %</option>
                                        <option value="">MOBIFONE - {{ setting('discount_mobile',0) }} %</option>
                                        <option value="">VIETNAMOBILE - {{ setting('discount_vm',0) }} %</option>
                                        <option value="">ZING - {{ setting('discount_zing',0) }} %</option>
                                        <option value="">GATE - {{ setting('discount_gate',0) }} %</option>
                                    </select>
                                    <select name="telco" id="telco" class="form-select mb-2">
                                        <option value="VIETTEL">VIETTEL</option>
                                        <option value="VINAPHONE">VINAPHONE</option>
                                        <option value="VIETNAMOBILE">VIETNAMOBILE</option>
                                        <option value="MOBIFONE">MOBIFONE</option>
                                        <option value="ZING">ZING</option>
                                        <option value="GATE">GATE</option>
                                    </select>
                                    <input type="text" name="serial" id="serial" placeholder="Mã serial" class="form-control mb-2">
                                    <input type="text" name="code" id="code" placeholder="Mã thẻ" class="form-control mb-2">
                                    <select name="amount" id="amount" class="form-select mb-2">
                                        <option value="">--Mệnh giá--</option>
                                        <option value="10000">10,000 đ</option>
                                        <option value="20000">20,000 đ</option>
                                        <option value="30000">30,000 đ</option>
                                        <option value="50000">50,000 đ</option>
                                        <option value="100000">100,000 đ</option>
                                        <option value="200000">200,000 đ</option>
                                        <option value="300000">300,000 đ</option>
                                        <option value="500000">500,000 đ</option>
                                        <option value="1000000">1,000,000 đ</option>
                                    </select>
                                    <button type="button" class="btn btn-gt cards"><strong>Nạp</strong></button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="transfer-form mb-4 hidden" id="stone">
                        <ul class="price-items mb-4">
                            @foreach($price_config as $price)
                            <li class="py-2 px-4 mb-2 price bg-white border-2 d-flex justify-content-between align-items-center"
                                style="border-color: #C9B708; border-style: solid; border-radius: 20px"
                                data-stone="{{ $price['stone'] }}" data-gold="{{ $price['gold'] }}" data-bonus="{{ $price['bonus'] }}">
                                <div class="price-stone d-flex justify-content-center align-items-start flex-column">
                                    <div class="full-price d-flex align-items-center justify-content-between p-1">
                                        <span style="font-size: 20px">{{ number_format($price['stone']) }}</span>
                                        <img src="{{ asset('frontend/images/stone-icon.svg') }}" alt="Stone Icon">
                                    </div>
                                    @if($price['bonus'] > 0)
                                    <span class="bonus-price" style="font-size: 13px; color: #978A0D">+{{ number_format($price['bonus']) }}</span>
                                    @endif
                                </div>
                                <div class="price-gold d-flex align-items-center justify-content-between">
                                    <div class="full-price d-flex align-items-center justify-content-between rounded-pill py-1 px-2"
                                         style="background-color: #FFFBD7">
                                        <span style="font-size: 20px">{{ number_format($price['gold']) }}</span>
                                        <img src="{{ asset('frontend/images/gold-icon.svg') }}" alt="Stone Icon">
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <form action="{{ route('user.transform-stone') }}" method="POST" id="transform-stone">
                            @csrf
                            <input type="hidden" name="linhthach" value="0">
                            <button type="submit" class="btn btn-custom-action active mb-4" style="width: 100%; border-radius: 20px">Đổi</button>
                        </form>
                        <ul class="item p-4 mb-2 bg-white border-2" style="border-color: #C9B708; border-style: solid; border-radius: 5px">
                            <li><strong>Linh thạch để làm gì?</strong></li>
                            <li>
                                <ul>
                                    <li style="list-style: initial; list-style-position: inside">Linh thạch dùng để mua đồ tu luyện</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@endsection
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>
<script>
$(function() {
    $('.recharges').on('click', function() {
        $(this).attr("disabled", true);
        var code = $('.code').val();
        var vnd = $('.vnd').val();
        var url = "{{ route('user.recharge.store') }}";
        var type = 0;
        if (vnd == 0) {
            $('.error').text('Số tiền phải lớn hơn 0');
            $(this).attr("disabled", false);
            return;
        }
        if (!/^[0-9]+$/.test(vnd)) {
            $('.error').text('Số tiền phải là số tự nhiên');
            $(this).attr("disabled", false);
            return;
        }
        $('.error').text('');
        $.post({
            url: url,
            data: {
                vnd: vnd,
                code: code,
                type: type
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.status == 300) {
                    $('.error').text(res.message);
                    $('.recharges').attr("disabled", false);
                } else {
                    toastr.success(res.message, 'Thành Công');
                    setTimeout(window.location.reload(), 2000);
                }
            }
        })
    })

    $('.cards').on('click', function() {
        $(this).attr("disabled", true);
        var telco = $('#telco').val();
        var code = $('#code').val();
        var serial = $('#serial').val();
        var amount = $('#amount').val();
        var type = 1;
        var partner_key = "{{ env('thecao_id','eeffd5103a52f3d419d2083719223134b86e1a04') }}";
        var url_callback = "{{ env('thecao_callback',' https://giangthe.com/callback') }}";
        var transaction_id = parseInt(Math.random() * 1000000000);
        var sign = CryptoJS.MD5(partner_key + code + serial).toString();
        if (!code.length) {
            $('.errors').text('Mã thẻ không được để trống');
            $(this).attr("disabled", false);
            return;
        }
        if (!serial.length) {
            $('.errors').text('Mã seri để trống');
            $(this).attr("disabled", false);
            return;
        }
        if (amount == "") {
            $('.errors').text('Bạn chưa chọn mệnh giá');
            $(this).attr("disabled", false);
            return;
        }
        $('.errors').text('');
        var url = 'https://xulythecao.com/api/partner/add-card';
        var href = "{{ route('card.add') }}";


        $.post({
            url: href,
            data: {
                telco: telco,
                amount: amount,
                serial: serial,
                code: code,
                partner_key: partner_key,
                url_callback: url_callback,
                transaction_id: transaction_id,
                sign: sign,
                type: type
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
            $.post({
                url: url,
                data: {
                    telco: telco,
                    amount: amount,
                    serial: serial,
                    code: code,
                    partner_key: partner_key,
                    url_callback: url_callback,
                    transaction_id: transaction_id,
                    sign: sign
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })

            Swal.fire({
                icon: 'warning',
                title: 'Thành công',
                text: 'Điền thẻ thành công . Vui lòng chờ !',
                confirmButtonText: 'Đi tới trang nạp thẻ',
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = window.location.origin + "/card";
            }
        })
    }
})



    })
})
</script>
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <script>
        $(document).ready(function() {
            $('.tabs .btn-custom').click(function () {
                $('.btn-custom').removeClass('active')
                $(this).addClass('active')
                $('.transfer-form').fadeOut()
                $('#' + $(this).data('type')).fadeIn()
            })
            $('.price-items .price').click(function () {
                $('.price-items .price').removeClass('active')
                $(this).addClass('active')
                $('#transform-stone input[name="linhthach"]').val($(this).data('stone'))
            })
            $('.copy').click(function () {
                navigator.clipboard.writeText($(this).siblings('.text-copy').find('span').text())
            })
            $('#transform-stone').submit(function(ev) {
                ev.preventDefault()
                let form = $(this),
                    dataForm = form.serializeArray()
                if ($('#transform-stone input[name="linhthach"]').val() == 0 || $('#transform-stone input[name="linhthach"]').val() == null) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: 'Bạn phải chọn số linh thạch để đổi',
                        confirmButtonText: 'Đóng',
                    })
                } else {
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        dataType: 'json',
                        data: dataForm,
                        success: function (response) {
                            Swal.fire({
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                                confirmButtonText: 'Đóng',
                            }).then((result) => {
                                window.location.reload()
                            })
                        }
                    })
                }
            })
        })
    </script>
@endif
@endsection

