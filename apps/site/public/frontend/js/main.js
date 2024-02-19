$(function(){
    $('.remove-comment').on('click',function(){
        $(this).parents('.sidebar').first().addClass('d-none');
    })
    $('.show-comment').on('click',function(){
        $(this).parents('.all-comment').first().find('.sidebar').toggleClass('d-none');
    })
    $('.remove-name').on('click',function(){
        $(this).parents('.name').first().addClass('d-none');
    })
    $('.show-name').on('click',function(){
        $(this).parents('.all-name').first().find('.name').toggleClass('d-none');
    })
    $('.new').on('click',function(){
        $(this).parents('div').first().find('.clistdiv').toggle();
    })
})

$(document).on('click', '.remove', function (e) {
    e.preventDefault();
    var url = $(this).attr('data-url');
    swal({
        title: "Bạn có chắc chắn muốn xóa ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        timer : 500000
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url:url,
                type: 'DELETE',
                headers:{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if(res.status == 200)
                        window.location.reload();
                }
            });
        }
    });
});
