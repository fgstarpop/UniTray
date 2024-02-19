@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
<section class="container mt-4 content main-list-user position-relative">
    <h2 class="text-center" style="margin-bottom: 20px;">Rút tiền</h2>
    <div class="text-right" style="top: 15px;right: 55px;position:absolute">
        <button class="btn btn-gt"  data-bs-toggle="modal" data-bs-target="#gift">Rút tiền</button>
    </div>
    <div class="mt-4 table-responsive">
        <table class="table text-center mt-4">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Người dùng</th>
                    <th scope="col">Số tiền nhận được</th>
                    <th scope="col">Số tiền</th>
                    <th scope="col">Số tiền hiện tại</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Mã giao dịch</th>
                    <th scope="col">Chủ thẻ</th>
                    <th scope="col">Ngân hàng</th>
                    <th scope="col">Số tài khoản</th>
                    <th scope="col">Thời điểm rút</th>
                    <th scope="col">Hủy</th>
                </tr>
            </thead>
            <tbody>
            @if(count($withdraw))
                @foreach($withdraw as $key => $list)
                    <tr>
                        <td><span class="w-100">{{ ++$key }}</span></td>
                        <td><span class="w-100">{{ currentUser()->name }}</span></td>
                        <td><span class="w-100">{{ number_format($list->silver - $list->silver * setting('withdrawal_fee',0)/100) }} VNĐ</span></td>
                        <td><span class="w-100">{{ number_format($list->silver) }} tệ</span></td>
                        <td><span class="w-100">{{ number_format($list->money_current_wallet) }} tệ</span></td>
                        <td>
                        @if($list->status == 0)
                            <span class="badge bg-warning w-100">Đang chờ</span>
                        @elseif($list->status == 1)
                            <span class="badge bg-success w-100">Đã rút</span>
                        @else
                            <span class="badge bg-danger w-100">Đã hủy</span>
                        @endif
                        </td>
                        <td><span class="w-100">{{ $list->code }} </span></td>
                        <td><span class="w-100">{{ $list->name }} </span></td>
                        <td><span class="w-100">{{ $list->bank }} </span></td>
                        <td><span class="w-100">{{ $list->stk }} </span></td>
                        <td><span class="w-100">{{ $list->created_at }}</span></td>
                        @if($list->status == 0)
                        <td><span class="w-100 text-danger delete-withdraw" data-id="{{$list->id}}"><i class="fal fa-trash-alt"></i></span></td>
                        @endif
<td></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">Bạn chưa có giao dịch rút tiền nào</td>
                </tr>
            @endif
            </tbody>
          </table>
          @if(count($withdraw))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $withdraw->appends(request()->input())->links() !!}
                    </nav>
                </div>
            @endif
    </div>
    <div class="modal fade" id="gift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Rút tiền</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        @csrf
                        <div class="error text-danger"></div>
                        <p class="text-secondary"><span class="text-danger">*</span>Bạn chỉ có thể dùng tệ để rút tiền</p>
                        <p>Số tệ bạn hiện có</p>
                        <div class="position-relative">
                            <input type="text" value="{{ number_format((int)$wallet->silver) }}" readonly class="wallet form-control">
                        </div>
                        <p class="mt-2">Chọn ngân hàng</p>
                        <select name="" id="" class="nganhang form-control">
                            <option value="Ngân hàng TMCP Á Châu(ACB)">Ngân hàng TMCP Á Châu(ACB)</option>
                            <option value="Ngân hàng TMCP Việt Nam Thịnh Vượng(VPBank)">Ngân hàng TMCP Việt Nam Thịnh Vượng(VPBank)</option>
                            <option value="Ngân hàng TMCP Quân đội(MBBank)">Ngân hàng TMCP Quân đội(MBBank)</option>
                            <option value="Ngân hàng TMCP Hàng Hải(MSB)">Ngân hàng TMCP Hàng Hải(MSB)</option>
                            <option value="Ngân hàng TMCP Nam Á(NamABank)">Ngân hàng TMCP Nam Á(NamABank)</option>
                            <option value="Ngân hàng TMCP Bưu Điện Liên Việt(LienVietPostBank)">Ngân hàng TMCP Bưu Điện Liên Việt(LienVietPostBank)</option>
                            <option value="Ngân hàng TMCP Công thương Việt Nam(VietinBank)">Ngân hàng TMCP Công thương Việt Nam(VietinBank)</option>
                            <option value="Ngân hàng TMCP Bản Việt(Timo)">Ngân hàng TMCP Bản Việt(Timo)</option>
                            <option value="Ngân hàng TMCP Đầu tư và Phát triển Việt Nam(BIDV)">Ngân hàng TMCP Đầu tư và Phát triển Việt Nam(BIDV)</option>
                            <option value="Ngân hàng TMCP Sài Gòn Thương Tín(Sacombank)">Ngân hàng TMCP Sài Gòn Thương Tín(Sacombank)</option>
                            <option value="Ngân hàng TMCP Đông Nam Á(SeABank)">Ngân hàng TMCP Đông Nam Á(SeABank)</option>
                            <option value="Ngân hàng TMCP Quốc tế Việt Nam(VIB)">Ngân hàng TMCP Quốc tế Việt Nam(VIB)</option>
                            <option value="Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam(Agribank)">Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam(Agribank)</option>
                            <option value="Ngân hàng TMCP Kỹ thương Việt Nam(Techcombank)">Ngân hàng TMCP Kỹ thương Việt Nam(Techcombank)</option>
                            <option value="Ngân hàng TMCP Sài Gòn Công Thương(SaigonBank)">Ngân hàng TMCP Sài Gòn Công Thương(SaigonBank)</option>
                            <option value="Ngân hàng TMCP Đông Á(DongABank)">Ngân hàng TMCP Đông Á(DongABank)</option>
                            <option value="Ngân hàng TMCP Việt Á(VietABank)">Ngân hàng TMCP Việt Á(VietABank)</option>
                            <option value="Ngân hàng TMCP Sài Gòn(SCB)">Ngân hàng TMCP Sài Gòn(SCB)</option>
                            <option value="Ngân hàng TMCP Bảo Việt(BaoVietBank)">Ngân hàng TMCP Bảo Việt(BaoVietBank)</option>
                            <option value="Ngân hàng TNHH MTV HSBC (HSBC)">Ngân hàng TNHH MTV HSBC (HSBC)</option>
                        </select>

                        <p class="mt-2">Chủ tài khoản</p>
                         <input class="form-control name" type="text" placeholder="Nhập tên chủ tài khoản">

                        <p class="mt-2">Số tài khoản</p>
                        <input class="form-control stk" type="text" placeholder="Nhập số tài khoản ngân hàng">

                        <p class="mt-2">Số tệ</p>
                        <p><input class="form-control silver" type="text" value="0" placeholder="Nhập số tệ muốn rút"></p>
                        <p>Phí rút tiền : {{ setting('withdrawal_fee',0) }}%</p>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-gt withdraw">Rút ngay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script>
    $('.delete-withdraw').on('click',function(){
        var id = $(this).attr('data-id');
        var url = "{{ route('user.withdraw.delete') }}";
        $.ajax({
            url:url,
            method:"DELETE",
            data:{id:id},
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            success:function(res){
                if(res.status == 300){
                    toastr.error(res.message, 'Cảnh Báo');
                }else{
                    toastr.success(res.message, 'Thành Công');
                    setTimeout(window.location.reload(), 2000);
                }
            }
        })
    })
    $('.withdraw').on('click',function(){
        $(this).attr("disabled", true);
        var nganhang = $('.nganhang').val();
        var name = $('.name').val();
        var stk = $('.stk').val();
        var silver = $('.silver').val();
        var url = "{{ route('user.withdraw.store') }}";
        if(!name.length){
            $('.error').text('Người nhận không được để trống');
            $(this).attr("disabled", false);
            return;
            }
        if(!stk.length){
            $('.error').text('Số tài khoản không được bỏ trống');
            $(this).attr("disabled", false);
            return;
            }
        if(silver == 0){
            $('.error').text('Số tệ phải lớn hơn 0');
            $(this).attr("disabled", false);
            return;
            }
        if(!/^[0-9]+$/.test(stk)){
            $('.error').text('Số tài khoản không hợp lệ');
            $(this).attr("disabled", false);
            return;
            }
        if(!/^[0-9]+$/.test(silver)){
            $('.error').text('Số tệ phải là số tự nhiên');
            $(this).attr("disabled", false);
            return;
            }
        $(this).attr("disabled", true);
        $('.error').text('')
        $.post({
            url:url,
            data:{nganhang:nganhang,name:name,stk:stk,silver:silver},
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            success:function(res){
                if(res.status == 300){
                    $('.error').text(res.message);
                    $('.withdraw').attr("disabled", false);
                }else{
                    toastr.success(res.message, 'Thành Công');
                    setTimeout(window.location.reload(), 2000);
                }
            }
        })
    })
    $(document).on('click',function(){
        $('.lists').addClass('d-none');
    })
</script>
@endsection
