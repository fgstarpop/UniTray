@extends('shop.layouts.app')
@section('content')
<div class="container">
<div class="row">
    <div class="col-12" >
        <h5 class="pt-3 pb-3" style="text-align:center; border-bottom: solid #ddd9d9 2px; color: #BB2D3B">Gói ưu đãi </h5>
    </div>
    <div class="col-4 pt-2 pb-2" >
        <a href="{{route('pk.packTuLuyen')}}"> <button  type="button" style="border-radius: 20px; background-color:green!important; height: 40px;" class="btn btn-gt gift" data-wallet=""> Gói tu luyện
        </button> </a>
    </div>
    <div class="col-8 pt-2 pb-2" >
        <a href="{{route('pk.packKhiVan')}}">  <button  type="button" style="border-radius: 20px; height: 40px;" class="btn btn-gt gift" data-wallet=""> Khí vận + Auto nhặt quà
        </button>  </a>
    </div>
    <div class="col-12 pb-2" >
        <h6> Gói ưu đãi tu luyện</h6>
        <p> <b> a. Gói cơ bản - 100 linh thạch: </b>
            <br> + Tặng 60k tu vi
            <br> + Tặng lực chiến 120, phòng ngự 80, huyết 400, năng lượng 400.</p>
        <p> <b>b. Gói bình dân - 200 linh thạch: </b>
            <br>   + Tặng 120k tu vi
            <br> + Tặng lực chiến 250, phòng ngự 180, huyết 900, năng lượng 900</p>
        <p> <b>c. Gói Super - 1.000 linh thạch: </b>
            <br>  + Tặng 500k tu vi
            <br>  + Tặng lực chiến 1500, phòng ngự 1000, huyết 4500, năng lượng 4500</p>
        <p style="color: #BB2D3B; font-weight: 500;">* Gói Super:
        <br> - User mới tạo nhân vật tu luyện dưới 1 tháng được mua 1 lần duy nhất.
        <br> - Top 10 Thần Long Bảng mỗi tháng được mua 1 lần/tháng.
        <br> - User VIP mỗi tháng được mua 1 lần/tháng.
        <br>
            <br> * Các gói khác, mỗi tháng được mua tối đa 4 lần/gói.</p>

    </div>
    <div class="col-6 pb-2"  >
        <button onclick="muatuluyen(1)"  type="button" style="width: 100%; height: 40px;" class="btn btn-gt gift" data-wallet="">Mua gói cơ bản
        </button>
    </div>
    <div class="col-6 pb-2" >
        <button onclick="muatuluyen(2)"  type="button" style="width: 100%; height: 40px;" class="btn btn-gt gift" data-wallet="">Mua gói bình dân
        </button>
    </div>
    <div class="col-12" >
        <button  onclick="muatuluyen(3)" type="button" style="width: 100%; height: 40px;" class="btn btn-gt gift" data-wallet="">Mua gói super
        </button>
    </div>
    <div class="col-12" >
        <p class="mt-2" style="text-align:center;"></p>
    </div>
</div>
</div>
</div>
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
<script>

    function muatuluyen(goi) {
        $.get({
            url: "{{ route('pk.packTuLuyen') }}/buy/"+goi,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.code == 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Mua Thành Công',
                        text: response.message,
                        willClose: function() { // Xử lý khi user bấm OK
                            location.reload(); // Reload trang sau khi user bấm OK
                        }
                    })


                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Mua Thất Bại',
                        text: response.message,
                        willClose: function() { // Xử lý khi user bấm OK
                            location.reload(); // Reload trang sau khi user bấm OK
                        }
                    })

                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                    willClose: function() { // Xử lý khi user bấm OK
                        location.reload(); // Reload trang sau khi user bấm OK
                    }
                })
            }
        })
    }
</script>
@endsection
