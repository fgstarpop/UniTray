$(function(){
    $('#game').show();
    var total_item=[];
    var d = new Date();
    var hour = d.getHours();
    var minute = d.getMinutes();
    if(hour == 18 && ( minute < 0 || minute >= 40) || hour != 18){
    $('body').on('click','.number',function(){
        var number = $(this).text();
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            for( var i = 0; i < total_item.length; i++){
                if ( total_item[i].number == number) {
                    total_item.splice(i, 1);
                }
            }
            $('#box-game').find('#check-tr-active-num-'+number).remove();
            var url="/user/game";
            $.ajax({
                url:url,
                type:'DELETE',
                data:{number:number},
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },success:function(res){
                    $('.now').text(res.gold.toFixed(0).replace(/(\d)(?=(\d{3}))/g, '$1,'));
                }
            })
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
        for( var i = 0; i < total_item.length; i++){
            if ( total_item[i].number == number) {
                $('#check-active-num-'+number).removeClass('active');
                total_item.splice(i, 1);
            }
        }
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
    $('.order').on('click',function(){
        // console.log(total_item);
        // var nums = [];
        // var gold = $('.gold').val();
        // var amount = function(){
        //     var sum = 0;
        //     $('.money').each(function(){
        //         var num = $(this).attr('data-gold');
        //         nums.push(num);
        //         if(num !== 0){
        //             sum+=parseInt(num);
        //         }
        //     });
        //     return sum;
        // }
        // if(gold.length == 0){
        //     toastr.error('Bạn cần nhập số vàng');
        //     return;
        // }
        // if(!/^[0-9.]+$/.test(gold)){
        //     toastr.error('Số vàng phải là số');
        //     return;
        // }
        // if(gold == 0){
        //     toastr.error('Vàng phải lớn hơn 0');
        //     return;
        // }
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
    $('body').on('click','.delete',function(){
        var number =  $(this).parents('tr').first().find('.numbers').text();
        $('.d-flex').find(`.number[data-num='${number}'`).first().removeClass('active');
        $(this).parents('tr').first().remove();
        var url="/user/game";
        $.ajax({
            url:url,
            type:'DELETE',
            data:{number:number},
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },success:function(res){
                $('.now').text(res.gold.toFixed(0).replace(/(\d)(?=(\d{3}))/g, '$1,'));
            }
        })
    })
}
})
