@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
    <section class="container mt-4 content" style="margin-bottom: 50px;">
        <h2 class="text-center" style="margin-bottom: 20px;">THỐNG KÊ</h2>
        <table class="table text-center mt-4">
            <thead>
                <tr>
                    <th scope="col">Sách</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Giá tiền</th>
                    <th scope="col">Giảm giá</th>
                    <th scope="col">Số lượng mua</th>
                </tr>
            </thead>
            <tbody>
              <tr>
                <th>Thủ Tịch Người Thừa Kế Trần Bình</th>
                <td><img src="./images/product.jpg" style="width:100px;height:100px;object-fit:cover"></td>
                <td>3.000.000 ₫	</td>
                <td>10%</td>
                <td>123</td>
              </tr>

            </tbody>
          </table>
    </section>
@endsection
