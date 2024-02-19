@extends('shop.layouts.app')
@section('title')
    @if(!empty(setting('store_name')))
        -
    @endif
    {{ setting('store_name') }}
    @if(!empty(setting('store_slogan')))
        -
    @endif
    {{ setting('store_slogan') }}
@endsection

@push('styles')
    <style>
        .bkl {
            width: 84px;
            padding: 12px;
        }

        .bkl img {
            height: 100%;
            width: 100%;
        }

        .bkr {
            color: black;
            padding: 6px;
            font-size: 12px;
        }

        .bkr b {
            text-transform: capitalize;
            font-size: 16px;
        }

        .btnbl {
            border: 1px solid gray;
            border-radius: 0;
            padding: 4px 12px;
            background: none;
        }
    </style>
@endpush

@section('seo')
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="Giangthe.com">
@stop
@section('content')
    <section class="container mt-4 mb-4 content main-list-user">
        <div class="form-group mb-3 row pt-3">
            <div class="col text-left">
                <a href="{{ route('book.nhungs') }}" class="btn btn-danger text-white"><i class="fal fa-arrow-left"></i> Trở lại</a>
                <a href="{{ route('story.show', request('story')) }}" target="_blank" class="btn btn-success text-white">Đọc truyện</a>
            </div>
            <div class="col text-right">
                @if($story->type == 1 && $story->complete_free == \App\Domain\Story\Models\Story::COMPLETE_FREE_INACTIVE)
                    <a href="javascript:void(0)" data-url="{{ route('stories.update.complete.free', request('story')) }}" class="btn btn-gt confirm-complete-free">Xác nhận hoàn thành</a>
                @else
                    <a href="{{ route('chapters.create', request('story')) }}" class="btn btn-gt">Thêm chương</a>
                @endif
            </div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên chương</th>
                    <th scope="col">Cập nhật</th>
                    <th scope="col">Tác vụ</th>
                </tr>
                </thead>
                <tbody>
                @if($chapters->isNotEmpty())
                    @foreach($chapters as $key => $chapter)
                        <tr>
                            <td>{{ $chapter['order'] }}</td>
                            <td style="text-align: left">
                                <span class="w-150 @if(@$chapter['is_vip'] == 1) text-danger @endif">
                                    {{ $chapter['name'] }} @if(@$chapter['is_vip'] == 1) <i class="far fa-sparkles"></i> @endif</span>
                            </td>
                            <td>
                                <span
                                    class="w-100">{{ @$chapter['updated_at'] ? \Carbon\Carbon::parse($chapter['updated_at'])->format('H:s d-m-Y') : 'Chương chưa nhúng' }}</span>
                            </td>
                            <td>
                                @if(@$chapter['id'])
                                <a href="{{ route('chapters.edit', $chapter['id']) }}" class="text-info w-50"
                                   title="Sửa chương">
                            <span class="fa-stack">
                                <i class="fal fa-edit"></i>
                            </span>
                                </a>
                                    <a href="javascript:void(0)" title="Xóa chương"
                                       data-url="{{ route('chapters.delete', $chapter['id']) }}"
                                       class="text-danger remove-chap w-50">
                            <span class="fa-stack">
                               <i class="fal fa-trash-alt"></i>
                            </span>
                                    </a>
                                    @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">Bạn chưa có chương !</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        @if($chapters)
            <div class="paginate">
                {!! $chapters->links() !!}
            </div>
        @endif
    </section>
@endsection
@section('scripts')
    <script>

        $(document).on('click', '.remove-chap', function (e) {
            e.preventDefault();
            var url = $(this).attr('data-url');
            new swal({
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


        $(document).on('click', '.confirm-complete-free', function (e) {
            e.preventDefault();
            var url = $(this).attr('data-url');
            Swal.fire({
                title: "Bạn có chắc chắn muốn xác nhận ?",
                text: "Xác nhận hoàn thành chương miễn phí bạn sẽ có thể đăng chương vip !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                timer : 5000
            }).then((will) => {
                if (will) {
                    $.ajax({
                        url:url,
                        type: 'POST',
                        headers:{
                            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(res){
                            if(res.status == true){
                                window.location.href = '{{ route('chapters.create', request('story')) }}';
                            }else{
                                toastr('Đã sảy ra lỗi ')
                            }
                        }
                    });
                }
            });
        });
    </script>
    {{--    <script>--}}
    {{--        $(document).ready(function() {--}}
    {{--            $('#example').DataTable();--}}
    {{--        } );--}}
    {{--    </script>--}}
@endsection


