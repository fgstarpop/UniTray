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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>
            </div>
        @endif
        <div class="form-group mb-3 row pt-3">

            <div class="col text-left">
                <h4 style="color: blue">Những trận đang cho cược:</h4>
            </div>

        </div>
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead>
                    <tr>

                        <th scope="col">Trận</th>
                        <th scope="col">Thời gian bắt đầu</th>
                        <th scope="col">Tỷ Số</th>

                        <th scope="col">Đội Cược</th>
                        <th scope="col">Vàng Đã Cược</th>
                        <th scope="col">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($matchs->isNotEmpty())
                        @foreach ($matchs as $key => $match)
                            @if (!$match->lock)
                                <tr>

                                    <td style="text-align: center">
                                        <span>
                                            <div style="float: left">

                                                <img src="{{ $match->img_one }}" class="im"
                                                    style="border: 1px solid #0f0d0d;" width="40" height="30">

                                                <a
                                                    @if ($match->id_team_one == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_one }}</a>
                                                <br>
                                                <a> Tỷ lệ vàng thắng: {{ $match->rate_team_one }}</a>
                                                @if ($match->keo == 0)
                                                    <br>
                                                    <a style="color: black">Không chấp 2 đội đá đồng</a>
                                                @elseif ($match->id_team_one == $match->id_keo_tren && $match->id_keo_tren != 0)
                                                    <br>
                                                    <a style="color: blue">*Chấp {{ $match->keo }}@if ($match->keo_text != null)
                                                            ({{ $match->keo_text }})
                                                        @endif trái.</a>
                                                @else
                                                    <br>
                                                    <a style="color: red">*Được chấp {{ $match->keo }}@if ($match->keo_text != null)
                                                            ({{ $match->keo_text }})
                                                        @endif trái.</a>
                                                @endif
                                                <br>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#t{{ $match->uuid }}_1">
                                                    Đặt Cược
                                                </button>
                                                {{-- <a class="btn btn-primary" data-bs-toggle="modal" href="#t{{ $match->uuid }}_1"
                                                role="button">Đặt Cược</a> --}}

                                            </div>

                                            -


                                            <div style="float: right">
                                                <a
                                                    @if ($match->id_team_two == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_two }}</a>


                                                <img src="{{ $match->img_two }}" class="im"
                                                    style="border: 1px solid #0f0d0d" width="40" height="30">
                                                <br>
                                                <a> Tỷ lệ vàng thắng: {{ $match->rate_team_two }}</a>
                                                @if ($match->keo == 0)
                                                    <br>
                                                    <a style="color: black">Không chấp 2 đội đá đồng</a>
                                                @elseif ($match->id_team_two == $match->id_keo_tren && $match->id_keo_tren != 0)
                                                    <br>
                                                    <a style="color: blue">*Chấp {{ $match->keo }}@if ($match->keo_text != null)
                                                            ({{ $match->keo_text }})
                                                        @endif trái.</a>
                                                @else
                                                    <br>
                                                    <a style="color: red">*Được chấp {{ $match->keo }}@if ($match->keo_text != null)
                                                            ({{ $match->keo_text }})
                                                        @endif trái.</a>
                                                @endif
                                                <br>

                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#t{{ $match->uuid }}_2">
                                                    Đặt Cược
                                                </button>
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

                                        @if (count($match->user_match) == 0)
                                            <a>Bạn chưa cược đội nào</a>
                                        @else
                                            @foreach ($match->user_match as $user_m)
                                                <img src="{{ $user_m->img }}" alt="{{ $user_m->name }}"
                                                    style="border: 1px solid #0f0d0d;" width="40" height="30">
                                                {{ $user_m->name }}
                                            @break
                                        @endforeach
                                    @endif
                                </td>
                                <td>

                                    {{ number_format($match->sum_user) }}
                                </td>


                                <td>
                                    Đang cho đặt cược
                                </td>

                            </tr>
                            <div class="modal fade" id="t{{ $match->uuid }}_1" tabindex="-1" aria-hidden="true"
                                style="font-size: 12px;" name="t{{ $match->uuid }}_1">

                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Đặt cược trận: <img src="{{ $match->img_one }}"
                                                    alt="{{ $match->name_one }}" style="border: 1px solid #0f0d0d;"
                                                    width="40" height="25"> {{ $match->name_one }}
                                                - {{ $match->name_two }} <img src="{{ $match->img_two }}"
                                                    alt="{{ $match->name_two }}" style="border: 1px solid #0f0d0d;"
                                                    width="40" height="25"> </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('wcusercreate', [$match->uuid]) }}"
                                                style="text-align:left">
                                                @csrf
                                                <input name="team_cuoc" value={{ $match->id_team_one }} type="hidden">
                                                <div class="error text-danger"></div>
                                                @if (count($match->user_match) > 0)
                                                    @foreach ($match->user_match as $user_m)
                                                        @if ($user_m->id_team == $match->id_team_one)
                                                            <p>Bạn muốn cược thêm cho đội: {{ $match->name_one }} <img
                                                                    src="{{ $match->img_one }}"
                                                                    alt="{{ $match->name_one }}"
                                                                    style="border: 1px solid #0f0d0d;" width="40"
                                                                    height="25">
                                                            </p>
                                                        @else
                                                            <p>Bạn đang cược cho đội: {{ $match->name_two }} <img
                                                                    src="{{ $match->img_two }}"
                                                                    alt="{{ $match->name_two }}"
                                                                    style="border: 1px solid #0f0d0d;" width="40"
                                                                    height="25">
                                                            </p>
                                                            <p>Bạn muốn cược luôn cho đội: {{ $match->name_one }} <img
                                                                    src="{{ $match->img_one }}"
                                                                    alt="{{ $match->name_one }}"
                                                                    style="border: 1px solid #0f0d0d;" width="40"
                                                                    height="25">

                                                            </p>
                                                        @endif
                                                    @break
                                                @endforeach
                                            @else
                                                <p>Bạn muốn cược cho đội: {{ $match->name_one }} <img
                                                        src="{{ $match->img_one }}" alt="{{ $match->name_one }}"
                                                        style="border: 1px solid #0f0d0d;" width="40"
                                                        height="25">
                                                </p>
                                            @endif
                                            <p>Số vàng bạn hiện có</p>
                                            <div class="position-relative">
                                                <input type="text"
                                                    value="{{ number_format((int) $wallet->gold) }}" readonly
                                                    class="wallet form-control" data-wallet=" ">
                                            </div>
                                            <p class="mt-3">Số vàng muốn Cược:</p>
                                            <p><input class="form-control gold" value="0" name="gold"
                                                    type="text" placeholder="Nhập số vàng muốn cược"></p>

                                            <p class="text-danger">*Cược ít nhất 10.000 vàng cho đội của bạn</p>




                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-gt ">Cược Ngay
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="t{{ $match->uuid }}_2" tabindex="-1" aria-hidden="true"
                            style="font-size: 12px;" name="t{{ $match->uuid }}_2">

                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Đặt cược trận: <img src="{{ $match->img_one }}"
                                                alt="{{ $match->name_one }}" style="border: 1px solid #0f0d0d;"
                                                width="40" height="25"> {{ $match->name_one }}
                                            - {{ $match->name_two }} <img src="{{ $match->img_two }}"
                                                alt="{{ $match->name_two }}" style="border: 1px solid #0f0d0d;"
                                                width="40" height="25"> </h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{ route('wcusercreate', [$match->uuid]) }}"
                                            style="text-align:left">
                                            @csrf
                                            <input name="team_cuoc" value={{ $match->id_team_two }}
                                                type="hidden">
                                            <div class="error text-danger"></div>
                                            @if (count($match->user_match) > 0)
                                                @foreach ($match->user_match as $user_m)
                                                    @if ($user_m->id_team == $match->id_team_two)
                                                        <p>Bạn muốn cược thêm cho đội: {{ $match->name_two }} <img
                                                                src="{{ $match->img_two }}"
                                                                alt="{{ $match->name_two }}"
                                                                style="border: 1px solid #0f0d0d;" width="40"
                                                                height="25">
                                                        </p>
                                                    @else
                                                        <p>Bạn đang cược cho đội: {{ $match->name_one }} <img
                                                                src="{{ $match->img_one }}"
                                                                alt="{{ $match->name_one }}"
                                                                style="border: 1px solid #0f0d0d;" width="40"
                                                                height="25">
                                                        </p>
                                                        <p>Bạn muốn cược luôn đội: {{ $match->name_two }} <img
                                                                src="{{ $match->img_two }}"
                                                                alt="{{ $match->name_two }}"
                                                                style="border: 1px solid #0f0d0d;" width="40"
                                                                height="25">

                                                        </p>
                                                    @endif
                                                @break
                                            @endforeach
                                        @else
                                            <p>Bạn muốn cược cho đội: {{ $match->name_two }} <img
                                                    src="{{ $match->img_two }}" alt="{{ $match->name_two }}"
                                                    style="border: 1px solid #0f0d0d;" width="40"
                                                    height="25">
                                            </p>
                                        @endif
                                        <p>Số vàng bạn hiện có</p>
                                        <div class="position-relative">
                                            <input type="text"
                                                value="{{ number_format((int) $wallet->gold) }}" readonly
                                                class="wallet form-control" data-wallet=" ">
                                        </div>
                                        <p class="mt-3">Số vàng muốn Cược:</p>
                                        <p><input class="form-control gold" value="0" name="gold"
                                                type="text" placeholder="Nhập số vàng muốn cược"></p>

                                        <p class="text-danger">*Cược ít nhất 10.000 vàng cho đội của bạn</p>




                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-gt ">Cược Ngay
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="9">Chưa có trận nào để đặt cược!</td>
            </tr>
        @endif
    </tbody>
</table>
</section>




<section class="container mt-4 mb-4 content main-list-user">
<div class="form-group mb-3 row pt-3">

<div class="col text-left">
    <h4 style="color: blue">Lịch sử đặt cược:</h4>
</div>

</div>
<div class="table-responsive mt-4">
<table class="table text-center">
    <thead>
        <tr>

            <th scope="col">Trận</th>
            <th scope="col">Đội Cược</th>
            <th scope="col">Vàng Đã Cược</th>
            <th scope="col">Vàng Thắng</th>
            <th scope="col">Vàng Thua</th>
            <th scope="col">Ngày Cược</th>
            <th scope="col">Trạng thái</th>
        </tr>
    </thead>
    @if ($user_log->isNotEmpty())
        @foreach ($user_log as $key => $match)
            <tr>

                <td style="text-align: center">
                    <span>
                        <div style="float: left">

                            <img src="{{ $match->img_one }}" class="im"
                                style="border: 1px solid #0f0d0d;" width="40" height="30">

                            <a
                                @if ($match->id_team_one == $match->u_keo_tren) style="color: red" @endif>{{ $match->name_one }}</a>
                            <br>
                            @if ($match->id_team == $match->id_team_one)
                            <a> Tỷ lệ vàng thắng: {{ $match->u_rate }}</a>
                            @else
                            <a> Tỷ lệ vàng thắng: {{ $match->u_rate2 }}</a>
                            @endif
                            @if ($match->u_keo == 0)
                                <br>
                                <a style="color: black">Không chấp 2 đội đá đồng</a>
                            @elseif ($match->id_team_one == $match->u_keo_tren && $match->u_keo_tren != 0)
                                <br>
                                <a style="color: blue">*Chấp {{ $match->u_keo }}@if ($match->u_keo_text != null)
                                        ({{ $match->u_keo_text }})
                                    @endif trái.</a>
                            @else
                                <br>
                                <a style="color: red">*Được chấp {{ $match->u_keo }}@if ($match->u_keo_text != null)
                                        ({{ $match->u_keo_text }})
                                    @endif trái.</a>
                            @endif
                            <br>


                        </div>

                        -


                        <div style="float: right">
                            <a
                                @if ($match->id_team_two == $match->u_keo_tren) style="color: red" @endif>{{ $match->name_two }}</a>


                            <img src="{{ $match->img_two }}" class="im"
                                style="border: 1px solid #0f0d0d" width="40" height="30">
                            <br>
                            @if ($match->id_team == $match->id_team_two)
                            <a> Tỷ lệ vàng thắng: {{ $match->u_rate }}</a>
                            @else
                            <a> Tỷ lệ vàng thắng: {{ $match->u_rate2 }}</a>
                            @endif
                            @if ($match->u_keo == 0)
                                <br>
                                <a style="color: black">Không chấp 2 đội đá đồng</a>
                            @elseif ($match->id_team_two == $match->u_keo_tren && $match->u_keo_tren != 0)
                                <br>
                                <a style="color: blue">*Chấp {{ $match->u_keo }}@if ($match->u_keo_text != null)
                                        ({{ $match->u_keo_text }})
                                    @endif trái.</a>
                            @else
                                <br>
                                <a style="color: red">*Được chấp {{ $match->u_keo }}@if ($match->u_keo_text != null)
                                        ({{ $match->u_keo_text }})
                                    @endif trái.</a>
                            @endif
                            <br>


                        </div>

                    </span>

                </td>
                <td>
                    @if ($match->id_team == $match->id_team_one)
                        <img src="{{ $match->img_one }}" alt="{{ $match->name_one }}"
                            style="border: 1px solid #0f0d0d;" width="40" height="30">
                        {{ $match->name_one }}
                    @else
                        <img src="{{ $match->img_two }}" alt="{{ $match->name_two }}"
                            style="border: 1px solid #0f0d0d;" width="40" height="30">
                        {{ $match->name_two }}
                    @endif
                </td>




                <td>

                    {{ number_format($match->u_money) }}
                </td>
                <td>

                    {{ number_format($match->win_money) }}
                </td>
                <td>

                    {{ number_format($match->lose_money) }}
                </td>
                <td>
                    {{ $match->u_time_create }}
                </td>
                <td>
                    @if (empty($match->status))
                        Chưa tính toán kết quả
                    @else
                        {{ $match->status }}
                    @endif
                </td>

            </tr>
        @endforeach
    @endif
</table>
</div>

</section>








<section class="container mt-4 mb-4 content main-list-user">
<div class="form-group mb-3 row pt-3">

<div class="col text-left">
    <h4 style="color: blue">Những trận đã khoá:</h4>
</div>

</div>
<div class="table-responsive mt-4">
<table class="table text-center">
    <thead>
        <tr>

            <th scope="col">Trận</th>
            <th scope="col">Thời gian bắt đầu</th>
            <th scope="col">Tỷ Số</th>

            <th scope="col">Đội Cược</th>
            <th scope="col">Vàng Đã Cược</th>
            <th scope="col">Trạng thái</th>
        </tr>
    </thead>
    @if ($matchs_2->isNotEmpty())
        @foreach ($matchs_2 as $key => $match)
            @if ($match->lock)
                <tr>

                    <td style="text-align: center">
                        <span>
                            <div style="float: left">

                                <img src="{{ $match->img_one }}" class="im"
                                    style="border: 1px solid #0f0d0d;" width="40" height="30">

                                <a
                                    @if ($match->id_team_one == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_one }}</a>
                                <br>
                                <a> Tỷ lệ vàng thắng: {{ $match->rate_team_one }}</a>
                                @if ($match->keo == 0)
                                    <br>
                                    <a style="color: black">Không chấp 2 đội đá đồng</a>
                                @elseif ($match->id_team_one == $match->id_keo_tren && $match->id_keo_tren != 0)
                                    <br>
                                    <a style="color: blue">*Chấp {{ $match->keo }} @if ($match->keo_text != null)
                                            ({{ $match->keo_text }})
                                        @endif trái.</a>
                                @else
                                    <br>
                                    <a style="color: red">*Được chấp {{ $match->keo }}@if ($match->keo_text != null)
                                            ({{ $match->keo_text }})
                                        @endif trái.</a>
                                @endif
                                <br>


                            </div>

                            -


                            <div style="float: right">
                                <a
                                    @if ($match->id_team_two == $match->id_keo_tren) style="color: red" @endif>{{ $match->name_two }}</a>


                                <img src="{{ $match->img_two }}" class="im"
                                    style="border: 1px solid #0f0d0d" width="40" height="30">
                                <br>
                                <a> Tỷ lệ vàng thắng: {{ $match->rate_team_two }}</a>
                                @if ($match->keo == 0)
                                    <br>
                                    <a style="color: black">Không chấp 2 đội đá đồng</a>
                                @elseif ($match->id_team_two == $match->id_keo_tren && $match->id_keo_tren != 0)
                                    <br>
                                    <a style="color: blue">*Chấp {{ $match->keo }}@if ($match->keo_text != null)
                                            ({{ $match->keo_text }})
                                        @endif trái.</a>
                                @else
                                    <br>
                                    <a style="color: red">*Được chấp {{ $match->keo }}@if ($match->keo_text != null)
                                            ({{ $match->keo_text }})
                                        @endif trái.</a>
                                @endif
                                <br>


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

                        @if (count($match->user_match) == 0)
                            <a>Bạn chưa cược đội nào</a>
                        @else
                            @foreach ($match->user_match as $user_m)
                                <img src="{{ $user_m->img }}" alt="{{ $user_m->name }}"
                                    style="border: 1px solid #0f0d0d;" width="40" height="30">
                                {{ $user_m->name }}
                            @break
                        @endforeach
                    @endif
                </td>
                <td>

                    {{ number_format($match->sum_user) }}
                </td>


                <td>
                   @if ($match->chot_so)
                        Đã thanh thoán.
                    @else
                        Đã khoá đặt cược
                    @endif                </td>

            </tr>
        @endif
    @endforeach
@endif
</table>
</div>

</section>



@endsection

@section('scripts')


@endsection
