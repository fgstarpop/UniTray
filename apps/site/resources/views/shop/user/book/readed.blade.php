@extends('shop.layouts.app')

@push('styles')
<style>
</style>
@endpush

@section('content')
<section class="container mt-4 mb-4 content main-list-user">
    <h2 style="text-align:center" >Truyện đã đọc</h2>
    <div class="table-responsive mt-4">
        <table class="table text-center">
        <thead>
        <tr>
            <th scope="col">Ảnh</th>
            <th scope="col">Tên truyện</th>
            <th scope="col">Người tạo</th>
            <th scope="col">Số chương</th>
            <th scope="col">Cập nhật</th>
            <th scope="col">Tác vụ</th>
        </tr>
        </thead>
        <tbody>
        @if($book->isNotEmpty())
            @foreach($book as $list)
{{--            @php --}}
{{--                $list->stories->chapters = $list->stories->chapters_json ? json_decode($list->stories->chapters_json, true) : $list->stories->chapters;--}}
{{--            @endphp--}}
            @if(!empty($list->stories))
                <tr>
                    <td class="text-center">
                        <div class="img-list">
                            <a href="{{ route('story.show',$list->stories_id) }}">
                                <img src="{{ $list->stories ? ($list->stories->avatar ?? $list->stories->getFirstMediaUrl('default')) : '' }}" class="im">
                            </a>
                        </div>
                    </td>
                    <td style="text-align: left"><a href="{{ route('story.show',$list->stories_id) }}" class="w-150">{{ $list->stories->name }}</a></td>
                    <td><span class="w-100">{{ $list->user->name }}</span></td>
                    <td><span class="w-100">{{ $list->stories->count_chapters ?? 0  }}</span></td>
                    <td>
                        <span class="w-100">{{ $list->stories->updated_at->format('H:s d-m-Y') }}</span>
                    </td>
                    <td >
                    <div class="d-flex" style="justify-content: space-between;align-items: center;">
                    @php
                        $listChapter = json_decode($list->chapters_id) ;
                        $linkStory = @$list['embed_link'] ?  @$list['link_other'] ?? route('chapters.show', [$list->stories_id, 'link' => @$list['embed_link']]) : @$list['link_other'] ?? route('chapters.show', [$list->stories_id, 'id' => end($listChapter) ]);
                    @endphp
                         <a href="{{ $linkStory }}" title="Đọc tiếp" class="text-primary"><i class="fa fa-eye"></i></a>
                         <a href="javascript:void(0)" title="Xóa" data-url="{{ route('book.unread', $list->stories_id) }}" class="text-danger remove">
                            <span class="fa-stack w-100">
                                <i class="fal fa-trash-alt"></i>
                            </span>
                        </a>
                        </div>
                    </td>
                </tr>
            @endif
            @endforeach
        @else
            <tr>
                <td colspan="6">Bạn chưa có truyện đã đọc!</td>
            </tr>
        @endif
        </tbody>
    </table>
    @if(count($book))
        <div style="text-align: center;width: 100%" id="searchpagi"><br>
            <nav aria-label="..." style="display: inline-block;">
                {!! $book->appends(request()->input())->links() !!}
            </nav>
        </div>
    @endif
    </div>
</section>
@endsection
@section('scripts')
<script>
// $('.remove').on('click',function(e){
//     e.preventDefault();
//     var id = $(this).attr('data-remove');
//     var url = '/tusach/unread/'+id;
//     swal({
//         title: "Bạn có chắc chắn muốn xóa ?",
//         icon: "warning",
//         buttons: true,
//         dangerMode: true,
//         timer : 500000
//     }).then((willDelete) => {
//         if (willDelete) {
//             $.ajax({
//                 url:url,
//                 type: 'DELETE',
//                 headers:{
//                     'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
//                 },
//                 success:function(res){
//                     if(res.status == 300)
//                         window.location.reload();
//                 }
//             });
//         }
//     });
// })
</script>
@endsection
