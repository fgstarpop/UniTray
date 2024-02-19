@extends('shop.layouts.app')

@push('styles')
<style>
</style>
@endpush
@section('content')
    <section class="container mt-4 mb-4 content main-list-user">
        <h2 style="text-align:center" >Truyện nhúng của {{ $user->id != currentUser()->id ? $user->name : 'tôi' }}</h2>
            <div class="table-responsive mt-4">
                <table id="example" class="table text-center">
                    <thead>
                    <tr>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Tên truyện</th>
                        <th scope="col">Người tạo</th>
                        <th scope="col">Số chương</th>
                        <th scope="col">Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($book))
                        @foreach($book as $list)
                            <tr>
                                <td class="text-center">
                                    <div class="img-list">
                                        <a href="{{ route('story.show', $list) }}">
                                            <img src="{{ $list->avatar }}" class="im">
                                        </a>
                                    </div>
                                </td>
                                <td><a href="{{ route('story.show', $list->id) }}" class="w-150">{{ $list->name }}</a></td>
                                <td><span class="w-100">{{ currentUser()->name }}</span></td>
                                <td><span class="w-100">{{ $list->count_chapters ?? 0 }}</span></td>
                                <td><span class="w-100">{{ \App\Domain\Story\Models\Story::STATUS[$list->status] }}</span></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">Chưa có truyện nhúng !</td>
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
        $(document).ready(function() {
            $('#example').DataTable({
                "dom": 'frt'
            });
        } );
    </script>
@endsection
