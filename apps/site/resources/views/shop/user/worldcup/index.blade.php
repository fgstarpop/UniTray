@extends('shop.layouts.app')
@section('title')
    @if (!empty(setting('store_name')))
        -
    @endif
    {{ setting('store_name') }}
    @if (!empty(setting('store_slogan')))
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

            </div>
            <div class="col text-right">

                <a href="{{ route('wcadmincreate') }}" class="btn btn-gt">Thêm Trận</a>

            </div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                    <tr>

                        <th scope="col">Trận</th>
                        <th scope="col">Thời gian bắt đầu</th>
                        <th scope="col">Tỷ Số</th>
                        <th scope="col">Tỷ Lệ Kèo</th>
                        <th scope="col">Tổng Mem Cược</th>
                        <th scope="col">Tổng Vàng Cược</th>
                        <th scope="col">Trạng Thái</th>
                        <th scope="col">Lời Lỗ</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($matchs->isNotEmpty())
                        @foreach ($matchs as $key => $match)
                            <tr>

                                <td style="text-align: center">
                                    <span>
                                        <div>
                                            {{ number_format($match->tong_one) }} Vàng
                                            <img src="{{ $match->img_one }}" class="im"
                                                style="border: 1px solid #0f0d0d;" width="25" height="25">

                                            <a
                                                @if ($match->id_team_one == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_one }}</a>

                                            -
                                            <a
                                                @if ($match->id_team_two == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_two }}</a>


                                            <img src="{{ $match->img_two }}" class="im"
                                                style="border: 1px solid #0f0d0d" width="25" height="25">
                                            {{ number_format($match->tong_two) }} Vàng
                                        </div>

                                    </span>

                                </td>
                                <td>
                                    {{ $match->time_start }}
                                </td>
                                <td>
                                    @if (Carbon\Carbon::now() >= $match->time_start)
                                        {{ $match->score_team_one }} - {{ $match->score_team_two }}
                                    @else
                                        Chưa bắt đầu
                                    @endif

                                </td>
                                <td>
                                    <div>
                                        @if ($match->keo == 0)
                                            {{ number_format((int) $match->keo) }}
                                        @else
                                            {{ $match->keo }}
                                        @endif
                                    </div>
                                    <div>
                                        <a
                                            @if ($match->id_team_one == $match->id_keo_tren) style="color: red" @endif>{{ $match->rate_team_one }}</a>
                                        |
                                        <a
                                            @if ($match->id_team_two == $match->id_keo_tren) style="color: red" @endif>{{ $match->rate_team_two }}</a>


                                    </div>

                                </td>
                                <td>
                                    {{ number_format($match->tong_mem) }}
                                </td>
                                <td>

                                    {{ number_format($match->tong_one + $match->tong_two) }}
                                </td>
                                <td>
                                    @if ($match->lock == 0)
                                        Đang cho đặt cược
                                    @else
                                        Khoá đặt cược
                                    @endif
                                </td>
                                <td>
                                    @if ($match->chot_so)
                                        @if ($match->loi_lo)
                                            <a style="color: #ff0000">Lời {{ number_format($match->money ) }} Vàng</a>
                                        @else
                                            <a style="color: black">Lỗ {{ number_format($match->money )}} Vàng</a>
                                        @endif
                                    @else
                                        Chưa chốt sổ
                                    @endif

                                </td>
                                <td>
                                    @if ($match->chot_so)
                                        Đã chốt sổ
                                    @else
                                        <a href="{{ route('wcadminedit', [$match->uuid]) }}" class="text-info w-50"
                                            title="Sửa đặt cược">
                                            <span class="fa-stack">
                                                <i class="fal fa-edit"></i>
                                            </span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">Bạn chưa tạo trận nào!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>


    </section>
@endsection
@section('scripts')

    {{--    <script> --}}
    {{--        $(document).ready(function() { --}}
    {{--            $('#example').DataTable(); --}}
    {{--        } ); --}}
    {{--    </script> --}}
@endsection
