@extends('shop.layouts.app')

@push('styles')
@endpush

@push('scripts')

@endpush
@section('content')
<section class="container mt-4 content main-list-user position-relative">
    <h2 class="text-center" style="margin-bottom: 20px;">Tặng vàng</h2>
    <div class="text-right position-absolute" style="top:15px;right:15px">
        <button class="btn btn-gt"  data-bs-toggle="modal" data-bs-target="#gift">Tặng vàng</button>
    </div>
    <div class="mt-4"  style="overflow:auto">
        <table class="table text-center mt-4"  style="min-width:500px;">
            <thead>
                <tr>
                    <th scope="col">Người tặng</th>
                    <th scope="col">Người nhận</th>
                    <th scope="col">Số vàng</th>
                    <th scope="col">Thời gian</th>
                </tr>
            </thead>
            <tbody>
            @if(count($gift))
            @foreach($gift as $list)
                <tr>
                    <td><a  href="{{ route('user.index',$list->user->id) }}"><span class="w-100 text-success">{{ $list->user->name }}</span></a></td>
                    <td><a  href="{{ route('user.index',$list->receiver->id) }}"><span class="w-100 text-success">{{ $list->receiver->name }}</span></a></td>
                    <td><span class="w-100 {{ $list->type ? 'text-success' : 'text-danger' }}">{{ $list->type ? '+' : '-' }}{{ (int)$list->gold }} vàng</span></td>
                    <td><span class="w-100">{{ $list->created_at }}</span></td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="4">Bạn chưa tặng vàng cho ai</td>
                </tr>
            @endif
            </tbody>
          </table>
          @if(count($gift))
                <div style="text-align: center;width: 100%" id="searchpagi"><br>
                    <nav aria-label="..." style="display: inline-block;">
                        {!! $gift->appends(request()->input())->links() !!}
                    </nav>
                </div>
            @endif
    </div>
    <div class="modal fade" id="gift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Tặng vàng</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        @csrf
                        <div class="error text-danger"></div>
                        <p>Số vàng bạn hiện có</p>
                        <div class="position-relative">
                            <input type="text" value="{{ number_format((int)$wallet->gold) }}" readonly class="wallet form-control">
                        </div>
                        <p class="pt-2">Người nhận</p>
                        <div class="position-relative">
                            <input type="text" name="search" data-id="" id="input-search" class="user form-control" placeholder="Tìm người dùng" autocomplete="off">
                            <ul class="lists d-none">
                                <div class="div">
                                </div>
                            </ul>
                        </div>

                        <p class="pt-2">Số vàng</p>
                        <p><input class="form-control gold"  name="gold" type="text" value="0" placeholder="Nhập số vàng muốn tặng"></p>
                        <p>Phí tặng vàng : {{ setting('gold_donation_fee',0) }}%</p>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-gt gift">Tặng ngay</button>
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
    $('.user').on('keyup',function(){
        var user = $(this).val();
        if(!user.length)
            $('.lists').addClass('d-none');
        var url = "{{ route('user.user') }}";
        $.get({
            url:url,
            data:{user:user},
            success:function(res){
                $('.div').empty();
                $('.lists').removeClass('d-none');
                if(res == 0){
                    $(".div").prepend(`
                        <li class="no p-15">Không có kết quả</li>
                    `);
                }
                else{
                    $(".div").prepend(res);
                }
            }
        })
    })
    $('body').on('click','.lists li',function(){
        $('.user').val($(this).text());
        $('.user').attr('data-id',$(this).attr('data-ids'));
        $('.lists').addClass('d-none');
    })
    $('.gift').on('click',function(){
        $(this).attr("disabled", true);
        var user = $('.user').val();
        var user_id = $('.user').attr('data-id');
        var gold = $('.gold').val();
        var url = "{{ route('user.gift.store') }}";
        if(!user.length){
            $('.error').text('Không có người nhận');
        $(this).attr("disabled", false);
            return;
        }
        if(!gold.length){
            $('.error').text('Vàng không được bỏ trống');
            $(this).attr("disabled", false);
            return;
        }
        if(gold == 0){
            $('.error').text('Số vàng phải lớn hơn 0');
        $(this).attr("disabled", false);
            return;
        }
        if(!/^[0-9.]+$/.test(gold)){
            $('.error').text('Số vàng phải là số');
            $(this).attr("disabled", false);
            return;
        }
        $(this).attr("disabled", true);
        $.post({
            url:url,
            data:{user_id:user_id,gold:gold},
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
            success:function(res){
                if(res.status == 300){
                    $('.error').text(res.message);
                    $('.gift').attr("disabled", false);
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
