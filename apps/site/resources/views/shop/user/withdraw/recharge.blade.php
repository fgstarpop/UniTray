@extends('shop.layouts.app')

@push('styles') 
<style>
.row{
    margin-top: 50px;width: 80%;margin: auto
}
.text{
    padding: 10px 0px;width: 30%;box-shadow: rgb(0 0 0 / 10%) 0px 0px 20px;border-radius: 4px;margin: 30px 15px;
}
</style>
@endpush 

@push('scripts')
     
@endpush
@section('content')
<section class="coverer mt-4 content" style="margin-bottom: 200px;">
            <h2 class="text-center">VUI LÒNG CHỌN SỐ TIỀN MUỐN NẠP !</h2>
            <div class="row text-center mt-4">
            @foreach($recharge as $list)
            @if($list->status == 1)
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text">
                    <a href="javascript:void(0)"><img src="https://lapo.vn/storage/media/9P3TtzdyEghdMfgAYoQ8WxdsRDhnVovwmaltFJDU.png" class="tt-img" ></a>
                    <h6 class="mt-3 mb-3">{{ $list->name}} {{ formatNumber($list->vnd) }}Đ ({{$list->gold}} vàng)</h6>
                    <p class="m-b-10 m-t-5">Chúc các bạn làm mua sắm vui vẻ !</p>
                        <form action="" method="POST"> 
                        @csrf
                            <input type="hidden" value="{{ $list->id }}" name="package">
                            <button type="submit" class="btn btn-secondary">Chọn gói</button>
                        </form>
                </div>
            @endif
            @endforeach
            </div>
        </section>
@endsection
