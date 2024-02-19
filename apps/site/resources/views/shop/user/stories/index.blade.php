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
    .bkl img{
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
    <section class="container mt-4 mb-4 content main-list-user pt-2">
        <div class="form-group mb-3 text-right">
            <a href="{{ route('stories.create') }}" class="btn btn-gt">Thêm truyện</a>
        </div>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Tên truyện</th>
                    <th scope="col">Số chương</th>
                    <th scope="col">Cập nhật</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Tác vụ</th>
                </tr>
                </thead>
                <tbody>
                @if($stories->isNotEmpty())
                    @foreach($stories as $key => $story)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td class="text-center">
                                <div class="img-list">
                                    <img src="{{ $story->getFirstMediaUrl('default') }}" class="im">
                                </div>
                            </td>
                            <td style="text-align: left"><span class="w-150">{{ $story->name }}</span></td>
                            <td><span class="w-100">{{ $story->chapters_count ? $story->chapters_count : 0 }}</span></td>
                            <td>
                                <span class="w-100">{{ \Carbon\Carbon::parse($story->updated_at)->format('H:s d-m-Y') }}</span>
                            </td>
                            <td><span class="w-100">{{ \App\Domain\Story\Models\Story::STATUS[$story->status] }}</span></td>
                            <td>
                                <a href="{{ route('chapters.index', $story) }}" class="text-info w-50" title="Danh sách chương">
                                <span class="fa-stack">
                                    <i class="fal fa-list"></i>
                                </span>
                                </a>
                                <a href="{{ route('stories.edit', $story) }}" class="text-info w-50" title="Sửa truyện">
                                <span class="fa-stack">
                                    <i class="fal fa-edit"></i>
                                </span>
                                </a>
                                <a href="javascript:void(0)" title="Xóa truyện" data-url="{{ route('stories.delete', $story) }}" class="text-danger remove w-50">
                                <span class="fa-stack">
                                   <i class="fal fa-trash-alt"></i>
                                </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">Bạn chưa có truyện !</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="paginate">
            {!! $stories->links() !!}
        </div>
    </section>
@endsection
@section('scripts')
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#example').DataTable();--}}
{{--        } );--}}
{{--    </script>--}}
@endsection


