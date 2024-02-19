@extends('shop.layouts.app')
@push('styles')
<style>
</style>
@endpush
@section('content')
    <section class="container mt-4 mb-4 content main-list-user">
        <h2 style="text-align:center" >Truyện đang theo dõi</h2>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tên truyện</th>
                    <th scope="col">Người tạo</th>
                    <th scope="col">Số chương</th>
                    <th scope="col">Tác vụ</th>
                </tr>
                </thead>
                <tbody>
                @if(count($book))
                    @foreach($book as $list)
{{--                    @php--}}
{{--                        $list->stories->chapters = !empty(optional($list->stories)->chapters_json) ? json_decode($list->stories->chapters_json, true) : !empty(optional($list->stories)->chapters) ?? null;--}}
{{--                    @endphp--}}
                        @if(!empty($list->stories))
                        <tr>
                            <td class="text-center">
                                <div class="img-list">
                                    <a href="{{ route('story.show',$list->stories_id) }}">
                                        <img src="{{ $list->stories ? ($list->stories->avatar ?? $list->stories->getFirstMediaUrl('default')) : '' }}" class="im">
                                    </a>
                                </div>
                            </td>
                            <td><a href="{{ route('story.show',$list->stories_id) }}" class="w-150">{{ optional($list->stories)->name }}</a></td>
                            <td><span class="w-100">{{ isset($list->stories->user->name) ? optional($list->stories->user)->name : 'Không có'}}</span></td>
                            <td><span class="w-100">{{ $list->stories->count_chapters ?? 0  }}</span></td>
                            <td>
                                <a href="{{ route('book.unfollow', $list->stories_id) }}" class="text-danger removes">
                                    <span class="fa-stack w-100">
                                        <i class="fal fa-trash-alt"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">Bạn chưa có truyện đang theo dõi!</td>
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
