@extends('shop.layouts.app')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <section class="container pt-2 content book-container">
        <br>
        <h3 class="text-center page-title text-danger" >Tủ truyện</h3>
        <div class="list-status overflow-scroll py-4 hide-scrollbar">
            <ul class="items d-flex flex-row mb-0 w-max-content">
                @php $type = request()->input('type'); @endphp
                <li class="btn rounded-pill item {{$type == 'read-recently' || empty($type) ? 'active' : ''}}">
                    <a class="d-inline-block" href="{{ route('book.mobile', ['type' => 'read-recently']) }}">Đọc gần đây</a>
                </li>
                <li class="btn btn-light rounded-pill item {{$type == 'ordered' ? 'active' : ''}}">
                    <a class="d-inline-block" href="{{ route('book.mobile', ['type' => 'ordered']) }}">Đã mua</a>
                </li>
                <li class="btn btn-light rounded-pill item {{$type == 'embedded' ? 'active' : ''}}">
                    <a class="d-inline-block" href="{{ route('book.mobile', ['type' => 'embedded']) }}">Đã nhúng</a>
                </li>
                <li class="btn btn-light rounded-pill item {{$type == 'follow' ? 'active' : ''}}">
                    <a class="d-inline-block" href="{{ route('book.mobile', ['type' => 'follow']) }}">Đang theo dõi</a>
                </li>
            </ul>
        </div>
        <div class="content-book">
            <div class="tab-content list-content-tabs">
                <div role="tabpanel" class="tab-pane active" id="read-recently" style="min-height: calc(100vh - 530px);">
                    @if($book->isNotEmpty())
                        <div class="list-book-block">
                            @foreach($book as $list)
                                <div class="book border-top border-top-dashed">
                                    <div class="row">
                                        <div class="col-lg-3 col-xl-3 col-md-3 col-3">
                                            <div class="thumbnail overflow-hidden">
                                                @switch($type)
                                                    @case('ordered')
                                                    @if(!empty($list->stories))
                                                    @php
                                                        $stories_id = $list->id;
                                                        $stories_name = $list->name ?? '';
                                                        $stories_desc = $list->description ?? '';
                                                        $count_chapter = $list->count_chapters ?? 0;
                                                        $author = $list->author_vi ?? '';
                                                        $stories_avatar = $list ? ($list->avatar ?? $list->getFirstMediaUrl('default')) : '';
                                                        $order = \App\Domain\Admin\Models\Order::join('giangthe.chapters', 'orders.chapter_id', 'chapters.id')
                                                            ->select('orders.chapter_id')
                                                            ->where('orders.user_id', currentUser()->id)
                                                            ->where('orders.story_id', $stories_id)
                                                            ->orderBy('orders.number', 'DESC')
                                                            ->first();
                                                        $chapters_id = $order->chapter_id;
                                                        $chapter = \App\Domain\Chapter\Models\Chapter::find($chapters_id);
                                                        $stories_desc = 'Mua đến chương: ' . $chapter->name ?? '';
                                                    @endphp
						@endif
                                                    @break
                                                    @case('embedded')
@if(!empty($list->stories))
                                                    @php
                                                        $stories_id = $list->id;
                                                        $stories_name = $list->name ?? '';
                                                        $stories_desc = $list->description ?? '';
                                                        $count_chapter = $list->count_chapters ?? 0;
                                                        $author = $list->author_vi ?? '';
                                                        $stories_avatar = $list ? ($list->avatar ?? $list->getFirstMediaUrl('default')) : '';
                                                        $chapters_id = '[1]';
                                                        $stories_desc = 'Ngày nhúng: ' . $list->created_at ?? '';
                                                    @endphp
@endif
                                                    @break
                                                    @case('follow')
@if(!empty($list->stories))

                                                    @php
                                                        $stories_id = $list->stories_id;
                                                        $stories_name = $list->stories->name ?? '';
                                                        $count_chapter = $list->stories->count_chapters ?? 0;
                                                        $author = $list->stories->author_vi ?? '';
                                                        $stories_avatar = $list->stories ? ($list->stories->avatar ?? $list->stories->getFirstMediaUrl('default')) : '';
                                                        $chapters_id = '[1]';
                                                        $stories_desc = 'Cập nhật mới: ' . $list->stories->updated_at ?? '';
                                                    @endphp
@endif
                                                    @break
                                                    @default
@if(!empty($list->stories))

                                                    @php
                                                        $stories_id = $list->stories_id;
                                                        $stories_name = $list->stories->name ?? '';
                                                        $count_chapter = $list->stories->count_chapters ?? 0;
                                                        $author = $list->stories->author_vi ?? '';
                                                        $stories_avatar = $list->stories ? ($list->stories->avatar ?? $list->stories->getFirstMediaUrl('default')) : '';
                                                        $chapters_id = $list->chapters_id;
                                                        $last_chapter_id = json_decode($chapters_id);
                                                        $chapter = \App\Domain\Chapter\Models\Chapter::find(end($last_chapter_id));
							$stories_desc = 'Đọc đến chương: ' . (isset($chapter->name) && $chapter->name ) ? "..." :  '';
						        @endphp
@endif
                                                @endswitch
                                                <a href="{{ route('story.show', $stories_id) }}">
                                                    <img src="{{ $stories_avatar }}" class="img-thumbnail" alt="{{ $stories_name }}">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-xl-9 col-md-9 col-9">
                                            <div class="book-info">
                                                <h3 class="title">
                                                    <a href="{{ route('story.show',$stories_id) }}">{{ $stories_name }}</a>
                                                </h3>
                                                <p class="author">{{ $author }}</p>
                                                <p class="chapter-quantity">Số chương: {{ $count_chapter ?? 0  }}</p>
                                                <p class="short-description">{!! $stories_desc !!}</p>
                                            </div>
                                            <div class="actions">
                                                @switch($type)
                                                    @case('ordered')
                                                    @php
                                                        $textReadAction = "Đọc tiếp";
                                                        $linkStory = @$list['embed_link'] ?  @$list['link_other'] ?? route('chapters.show', [$stories_id, 'link' => @$list['embed_link']]) : @$list['link_other'] ?? route('chapters.show', [$stories_id, 'id' => $chapters_id ]);
                                                    @endphp
                                                    @break
                                                    @case('embedded')
                                                    @case('follow')
                                                    @php
                                                        $textReadAction = "Đọc";
                                                        $linkStory = @$list['embed_link'] ?  @$list['link_other'] ?? route('chapters.show', [$stories_id, 'link' => @$list['embed_link']]) : @$list['link_other'] ?? route('story.show', $stories_id);
                                                    @endphp
                                                    @break
                                                    @default
                                                    @php
                                                        $textReadAction = "Đọc tiếp";
                                                        $listChapter = json_decode($chapters_id) ;
                                                        $linkStory = @$list['embed_link'] ?  @$list['link_other'] ?? route('chapters.show', [$stories_id, 'link' => @$list['embed_link']]) : @$list['link_other'] ?? route('chapters.show', [$stories_id, 'id' => end($listChapter) ]);
                                                    @endphp
                                                @endswitch
                                                <a href="{{ $linkStory }}" title="Đọc tiếp" class="btn rounded-pill text-white px-4 btn-read">{{ $textReadAction }}</a>
                                                <a href="javascript:void(0)" title="Xóa" data-url="{{ route('book.unread', $stories_id) }}" class="btn text-white rounded-pill px-4 btn-delete">Xóa bỏ</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(count($book) && $book->hasPages())
                            <div class="paginate">
                                {!! $book->appends(request()->input())->links() !!}
                            </div>
                        @endif
                    @else
                        <div class="text-center block-empty-book">
                            @switch($type)
                                @case('ordered')
                                <p>Bạn chưa có truyện đã mua!</p>
                                @break
                                @case('follow')
                                <p>Bạn chưa có truyện đang theo dõi!</p>
                                @break
                                @case('embedded')
                                <p>Bạn chưa có truyện đã nhúng!</p>
                                @break
                                @default
                                <p>Bạn chưa đọc truyện nào gần đây!</p>
                            @endswitch
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.actions .btn-delete').click(function (e) {
                e.preventDefault();
                var id = $(this).attr('data-remove');
                var url = '/tusach/unread/'+id;
                swal({
                    title: "Bạn có chắc chắn muốn xóa ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    timer : 500000
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers:{
                                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                            },
                            success:function(res){
                                window.location.reload();
                            }
                        });
                    }
                });
            })
        })
    </script>
@endsection
