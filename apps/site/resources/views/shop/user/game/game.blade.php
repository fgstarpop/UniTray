@extends('shop.layouts.app')
@section('title')
Xổ Số Giáng Thế
@endsection
@push('styles')
<style>
.cs{cursor:pointer}
.active{background-color:#88ff99 !important;color:#000}
.number{background-color: #fff;
    width: 100%;}
.border{
    border: unset !important;
}
</style>
@endpush

@push('scripts')

@endpush
@section('content')
<div class="container position-relative"><a class="btn btn-info text-white position-absolute orderss" href="{{ route('user.game')}}"><i class="fal fa-arrow-left"></i> Quay lại</a></div>
<h2 class="text-center">Đặt số</h2>
<h3 class="text-center fs-6" style="justify-content: space-evenly;display: flex;"><span>Ngày {{ \Carbon\Carbon::today()->format('d-m-Y') }}</span>
<p>Bạn đang có : <span class="now">{{ number_format((int)$wallet->gold) }}</span> vàng
</p>
</h3>
<section class="position-relative mt-4 mb-5 container" style="overflow-x:auto">
    <div class="row ">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div style="width:490px">
                <div style="background-color:#c00;color:#fff" class="p-3 text-center fs-3">
                    Chọn số để chơi
                </div>
                @for($i = 0 ; $i < 10 ; $i++)
                    @php if($i == 0)  $i = null  @endphp
                <div class="d-flex">
                    @for($j = 0 ; $j < 10 ; $j++)
                        @php
                            $check = 0;
                        @endphp
                        @foreach($game as $list)
                            @if($list->number == $i*10 + $j && $list->gold)
                                @php
                                    $check = 1;
                                @endphp
                            @endif
                        @endforeach

                        @if($check == 1)
                            <div class="border cs p-3 number active" id="check-active-num-{{ $i }}{{ $j }}" data-num={{ $i }}{{ $j }}>{{ $i }}{{ $j }}</div>
                        @else
                            <div class="border cs p-3 number" id="check-active-num-{{ $i }}{{ $j }}" data-num={{ $i }}{{ $j }}>{{ $i }}{{ $j }}</div>
                        @endif
                    @endfor
            </div>
            @endfor
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h3 class="text-center ordered">Các số bạn đã chọn</h3>
        <p class="errror text-danger text-center"></p>
        <p class="d-flex" style="justify-content: space-around;align-items: center;">Đặt vàng cho tất cả số <input type="text" class="form-control w-50" id="key"><button class="btn btn-info submit">Lưu</button></p>

        <table class="table container text-center" id="box-game">
            <thead>
                <tr>
                    <th scope="col">Số</th>
                    <th scope="col">Số vàng muốn chơi</th>
                    <th scope="col">Tổng vàng</th>
                    <th scope="col">Tùy chỉnh</th>
                </tr>
            </thead>
            <tbody>
                @if($game)
                    @foreach($game as $list)
                        @if($list->gold)
                        <tr  data-stt="{{ $list->number }}" data-goldn="{{$list->gold}}" id="check-tr-active-num-{{ $list->number }}">
                            <td scope="col" class="numbers">{{ $list->number }}</td>
                            <td class="product_quantity">
                                <div class="quantity-product  w-50" style="margin:0 auto">
                                    <input type="text" class="form-control gold" value="{{ $list->gold }}" placeholder="Nhập số vàng tại đây">
                                </div>
                            </td>
                            <td scope="col" class="money" data-gold="{{$list->gold}}">{{ number_format($list->gold) }}</td>
                            <td scope="col" class="text-danger delete cs">X</td>
                        </tr>
                @endif
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="text-center mt-3 mb-3">
            <button class="btn btn-info order" style="border-radius:50px">Lưu</button>
        </div>
    </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $(function(){
        $('#game').show();
        var total_item = [];
        $("#box-game tbody tr").each(function() {
            total_item.push({number : parseInt($(this).attr('data-stt')) , gold : parseInt($(this).attr('data-goldn')) });
        });
        var d = new Date();
        var hour = d.getHours();
        var minute = d.getMinutes();
        if(hour == 18 && ( minute < 0 || minute >= 40) || hour != 18){
        $('body').on('click','.number',function(){
            var number = $(this).text();
            if($(this).hasClass('active')){
                $(this).removeClass('active');
                total_item = total_item.filter((element) => {
                    if(element.number != number){
                        return element;
                    }
                });
                // for( var i = 0; i < total_item.length; i++){
                //     if ( total_item[i].number == number) {
                //         total_item.splice(i, 1);
                //     }
                // }
                $('#box-game').find('#check-tr-active-num-'+number).remove();
                // var url="/user/game";
                // $.ajax({
                //     url:url,
                //     type:'DELETE',
                //     data:{number:number},
                //     headers:{
                //         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                //     },success:function(res){
                //         $('.now').text(res.gold.toFixed(0).replace(/(\d)(?=(\d{3}))/g, '$1,'));
                //     }
                // })
            }else{
                $(this).addClass('active');
                total_item.push({number : parseInt(number) , gold :0 });
                var count = $('tbody').find('tr').last().attr('data-stt') ?? 0;
                var counts =parseInt(count)+1;
                $('tbody').prepend(`
            <tr data-stt="${number}" id="check-tr-active-num-${number}">
                <td scope="col" class="numbers">${number}</td>
                <td class="product_quantity">
                    <div class="quantity-product  w-50" style="margin:0 auto">
                        <input type="text" class="form-control gold" placeholder="Nhập số vàng tại đây">
                    </div>
                </td>
                <td scope="col" class="money" data-gold="${number}">0</td>
                <td scope="col" class="text-danger del cs">X</td>
            </tr>
            `);
            }
        })

        $(function(){
            $('.submit').on('click',function(){
                var req = $('#key').val();
                if(!/^[0-9.]+$/.test(req)){
                    $('.errror').text('Số vàng phải là số');
                    return;
                }else{
                    total_item.forEach(element => {
                        element.gold = parseInt(req);
                    });
                    $('.errror').text('');
                    $('input.gold').val(req);
                    $('.money').text(req);
                    $('.money').attr('data-gold',req);
                }
            })
        })

        $('body').on('click','.del',function(){
            var number =  $(this).parents('tr').first().find('.numbers').text();
            $(this).parents('tr').first().remove();
            total_item = total_item.filter((element) => {
                if(element.number != number){
                    return element;
                }
            });

            // for( var i = 0; i < total_item.length; i++){
            //     if ( total_item[i].number == number) {
            //         $('#check-active-num-'+number).removeClass('active');
            //         total_item.splice(i, 1);
            //     }
            // }
        })

        $('body').on('keyup','.gold',function(){
            var gold = $(this).val();
            var number =  $(this).parents('tr').first().find('.numbers').text();
            if(!/^[0-9.]+$/.test(gold)){
                $(this).parents('tr').first().find('.money').val('Số vàng phải là số');
                return;
            }
            gold = parseInt(gold);
            var check = 0;
            total_item.forEach(element => {
                if(element.number == number) {
                    element.gold = gold;
                    check = 1;
                }
            });
            if(check == 0){
                total_item.push({number : parseInt(number) , gold : gold });
            }
            $(this).parents('tr').first().find('.money').text(gold);
        })
        $('body').on('click','.delete',function(){
            var number =  $(this).parents('tr').first().find('.numbers').text();
            $('.d-flex').find(`.number[data-num='${number}'`).first().removeClass('active');
            $(this).parents('tr').first().remove();
            total_item = total_item.filter((element) => {
                if(element.number != number){
                    return element;
                }
            });
            // for( var i = 0; i < total_item.length; i++){
            //     if ( total_item[i].number == number) {
            //         total_item.splice(i, 1);
            //     }
            // }
            // var url="/user/game";
            // $.ajax({
            //     url:url,
            //     type:'DELETE',
            //     data:{number:number},
            //     headers:{
            //         'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            //     },success:function(res){
            //         $('.now').text(res.gold.toFixed(0).replace(/(\d)(?=(\d{3}))/g, '$1,'));
            //     }
            // })
        })

        $('.order').on('click',function(){
            var url="/user/game";
            $.post({
                url:url,
                data:{total_item:total_item},
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

    }
    })
</script>
@endsection
