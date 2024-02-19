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
@section('seo')
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="Giangthe.com">
@stop
@section('content')
    <section class="container mt-4 content main-list-user">
        <div id="wizard">
            <h2 class="text-center">Sửa trận
                <br>
                <img src="{{ $match->img_one }}" class="im" style="border: 1px solid #0f0d0d;" width="25"
                    height="25"> {{ $match->name_one }}-{{ $match->name_two }}
                <img src="{{ $match->img_two }}" class="im" style="border: 1px solid #0f0d0d;" width="25"
                    height="25">
            </h2>
            <div class="wizard-content">
                <form class="fade show active" method="POST" id="formCreatewc"
                    action="{{ route('wcadminedit', ['uuid' => $match->uuid]) }}">

                    @csrf
                    <div class="error"></div>
                    <div id="chapters">
                        <div class="chapter">
                            <div class="form-group mt-12">


                                <label>Đội 1 :</label>
                                <div>
                                    <select name="wc_team_1" id="wc_team_1" class="col-md-4">
                                        <option value="" selected disabled hidden>Chọn đội 1</option>
                                        @foreach ($list_teams as $list_team)
                                            <option value="{{ $list_team->id }}"
                                                @if ($list_team->id == $match->id_team_one) selected="selected" @endif>
                                                {{ $list_team->name }}</option>
                                        @endforeach
                                    </select>
                                    <label> Tỷ lệ ăn đội 1:</label>
                                    <input id="rate_one" name="rate_one" value={{ $match->rate_team_one }}>
                                    <label> Set đội 1 kèo trên</label>
                                    <input name="h_one" type="checkbox" id="h_one"
                                        @if ($match->id_team_one == $match->id_keo_tren) checked @endif>
                                </div>





                            </div>
                            <hr class="my-4">
                            <div class="form-group mt-12">
                                <label>Đội 2:</label>
                                <div>
                                    <select name="wc_team_2" id="wc_team_2" class="col-md-4">
                                        <option value="" selected disabled hidden>Chọn đội 2</option>
                                        @foreach ($list_teams as $list_team)
                                            <option value="{{ $list_team->id }}"
                                                @if ($list_team->id == $match->id_team_two) selected="selected" @endif>
                                                {{ $list_team->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    <label>Tỷ lệ ăn đội 2:</label>
                                    <input id="rate_two" name="rate_two" value={{ $match->rate_team_two }}>
                                    <label> Set đội 2 kèo trên</label>
                                    <input name="h_two" type="checkbox" id="h_two"
                                        @if ($match->id_team_two == $match->id_keo_tren) checked @endif>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="form-group mt-12">
                                <label>Set kèo cho 2 đội:</label>
                                <div>

                                    <input id="set_keo" name="set_keo" value=@if(!empty($match->keo_text))
                                                                        {{$match->keo_text}}
                                                                        @else
                                                                        {{$match->keo}}
                                                                        @endif>
                                    <label>Thời gian đá :</label>
                                    <input id="time_start" name="time_start" value="{{ $match->time_start }}">
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="form-group mt-12">
                                <label>Tỷ Số</label>
                                <div>
                                    <label>Tỷ số đội 1:</label>
                                    <input id="ty_so_1" name="ty_so_1" value={{ $match->score_team_one }}>
                                    <label>Tỷ số đội 2:</label>
                                    <input id="ty_so_2" name="ty_so_2" value={{ $match->score_team_two }}>

                                    @if (!$match->chot_so)
                                        <label>Chốt sổ</label>
                                        <input name="chot_so" type="checkbox" id="chot_so">
                                    @else
                                        <label style="color: red"> Đã chốt sổ</label>
                                    @endif
                                    <br>
                                    @if (!$match->lock)
                                    <label >Khoá trận không cho cược:</label>
                                    <input name="lock" type="checkbox" id="lock">
                                @else
                                <label style="color: red"> Đã Khoá Trận Đấu Không Cho Cược</label>
                                @endif
                                </div>
                            </div>
                            <hr class="my-4">
                            <hr class="my-4">

                        </div>
                        <div class="form-group mt-4" style="margin-bottom: 5em; text-align: right;">
                            <button type="submit" class="btn btn-gt">Lưu lại</button>
                        </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('scripts')

@endsection
