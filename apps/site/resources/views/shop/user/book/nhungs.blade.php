@extends('shop.layouts.app')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <section class="container mt-4 mb-4 content main-list-user">
        <h2 style="text-align:center">Truyện nhúng</h2>
        <div class="table-responsive mt-4">
            <table id="example" class="table text-center">
                <thead>
                <tr>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tên truyện</th>
                    <th scope="col">Doanh thu tháng</th>
                    <th scope="col">Số chương</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Cập nhật</th>
                    <th scope="col">Tác vụ</th>
                </tr>
                </thead>
                <tbody>
                @if(count($book))
                    @foreach($book as $story)
                        <tr>
                            <td class="text-center">
                                <div class="img-list">
                                    <a href="{{ route('story.show', $story->id) }}">
                                        <img src="{{ $story->avatar ?? $story->getFirstMediaUrl('default') }}"
                                             class="im">
                                    </a>
                                </div>
                            </td>
                            <td style="text-align: left"><a href="{{ route('story.show', $story->id) }}"
                                                            class="w-150">{{ $story->name }}</a></td>
                            <td><span class="w-100">{{ $story->audio_month }}</span></td>
                            <td><span class="w-100">{{ $story->count_chapters }}</span></td>
                            <td><span class="w-100">{{ \App\Domain\Story\Models\Story::STATUS[$story->status] }}</span>
                            </td>
                            <td>
                                <span class="w-100">{{ $story->updated_at->format('H:s d-m-Y') }}</span>
                            </td>
                            <td>
                                <a href="{{ route('chapters.index', $story) }}" class="text-info w-50"
                                   title="Danh sách chương">
                                <span class="fa-stack">
                                    <i class="fal fa-list"></i>
                                </span>
                                </a>
                                <a href="{{ route('stories.edit', $story->id) }}" class="text-info">
                                <span class="fa-stack">
                                    <i class="fal fa-edit"></i>
                                </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">Chưa có truyện nhúng !</td>
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
        $(document).ready(function () {
            $('#example').DataTable({
                "dom": 'frt'
            });
        });
    </script>
@endsection

