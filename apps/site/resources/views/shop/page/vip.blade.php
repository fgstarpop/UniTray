@extends('shop.layouts.app')
@section('title')
    {{ 'Nâng VIP' }}
@endsection

@section('content')
    <section class="container mt-4 content search-section" style="margin-bottom: 50px;">
        <div class="mt-4">
            <div class="header-find">
                <h2 class="text-danger text-center"><b>Quyền lợi của VIP</b></h2>
            </div>
            <div class="vip-section p-4 m-auto" style="max-width: 700px">
                <img src="{{ asset('frontend/images/logo-vip.webp') }}" alt="Logo vip"
                     class="img-vip m-auto d-block" width="146px">
                <br>
                <ul style="list-style-type: circle;">
                    <li class="font-vip-modal">Không quảng cáo</li>
                    <li class="font-vip-modal">Đọc free tất cả các chương VIP đang có</li>
                    {{-- <li class="font-vip-modal">Được tặng 2 lì xì đặc biệt mỗi giờ</li> --}}
                    <li class="font-vip-modal">Tên và bình luận nổi bật</li>
                    {{-- <li class="font-vip-modal">Tỉ lệ nhặt hạ phẩm đan = 0%</li> --}}
                    {{-- <li class="font-vip-modal">Tăng 10% tu vi khi tiêu vàng</li> --}}
                    <li class="font-vip-modal">Có huy hiệu VIP, tích xanh</li>
                    {{-- <li class="font-vip-modal">Được ưu đãi khi mua gói tu luyện</li> --}}
                </ul>
                <a href="{{ route('upgrade.vip') }}" type="button" style="width: 100%; border-color: #F8C23A; padding: 1rem 0.5rem; font-size: 17px; box-shadow: 0 5px 5px 5px rgb(51 51 51 / 10%);"
                   class="btn btn-success text-white btn-re-color btn-lg btn-block btn-register-vip">
                    Đăng ký VIP ngay với 100K vàng/tháng
                </a>
            </div>
        </div>
    </section>
@endsection
