@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
<section class="container mt-4 content main-list-user position-relative">
    <h2 class="text-center">Danh sách thẻ nạp</h2>
    <div class="text-right position-absolute" style="top:15px;right:15px">
        <a class="btn btn-warning" href="{{ route('user.recharge') }}">Nạp tiền</a>
    </div>
    <div style="overflow:auto">
        <table class="table text-center mt-4" style="min-width:500px;">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Loại thẻ</th>
                    <th scope="col">Mã thẻ</th>
                    <th scope="col">Mã seri</th>
                    <th scope="col">Mệnh giá</th>
                    <th scope="col">Mệnh giá thực</th>
                    <th scope="col">Số vàng</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @if(count($card))
                @foreach($card as $key => $list)
                <tr>
                    <td><span class="w-100">{{ ++$key }}</span></td>
                    <td><span class="w-100">{{ $list->telco }}</span></td>
                    <td><span class="w-100">{{ $list->code }}</span></td>
                    <td><span class="w-100">{{ $list->serial }}</span></td>
                    <td><span class="w-100">{{ number_format((int)$list->amount) }} VNĐ</span></td>
                    <td><span class="w-100">{{ number_format((int)$list->real_amount) }} VNĐ</span></td>
                    <td><span class="w-100">{{ number_format($list->gold) }} vàng</span></td>
                    <td>
                        @if($list->status == 2)
                        <label class="badge bg-warning">Đang xử lý</label>
                        @elseif($list->status == 1)
                        <label class="badge bg-success"> Thành công</label>
                        @else
                        <label class="badge bg-danger">Hủy</label>
                        @endif
                    </td>
                    <td><span class="w-100">{{ $list->created_at }}</span></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8">Bạn chưa nạp thẻ nào vào hệ thống</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if(count($card))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $card->appends(request()->input())->links() !!}
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
                        <div class="errors text-danger"></div>
                        <div class="text-danger">* Bảng phí đổi thẻ cào : {{ setting('discount_card',0) }} %<br><br>
                        </div>
                        <select name="" id="telco" class="form-select">
                            <option value="VIETTEL">VIETTEL</option>
                            <option value="VINAPHONE">VINAPHONE</option>
                            <option value="VIETNAMOBILE">VIETNAMOBILE</option>
                            <option value="MOBIFONE">MOBIFONE</option>
                            <option value="ZING">ZING</option>
                            <option value="GATE">GATE</option>
                            <option value="GARENA">GARENA</option>
                        </select><br>
                        <input type="text" id="code" placeholder="Mã thẻ" class="form-control"><br>
                        <input type="text" id="serial" placeholder="Mã serial" class="form-control"><br>
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
</section>

@endsection
@section('scripts') 
@endsection