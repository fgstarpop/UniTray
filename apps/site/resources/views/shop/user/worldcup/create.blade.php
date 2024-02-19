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
            <h2 class="text-center">Thêm Trận mới</h2>
            <div class="wizard-content">
                <form class="fade show active" method="POST" id="formCreatewc" action="{{ route('wcadmincreate') }}">

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
                                            <option value="{{ $list_team->id }}">{{ $list_team->name }}</option>
                                        @endforeach
                                    </select>
                                    <label> Tỷ lệ ăn đội 1:</label>
                                    <input id="rate_one" name="rate_one" value="1.98">
                                    <label > Set đội 1 kèo trên</label>
                                    <input name="h_one" type="checkbox" id="h_one">
                                </div>





                            </div>
                            <hr class="my-4">
                            <div class="form-group mt-12">
                                <label>Đội 2:</label>
                                <div>
                                    <select name="wc_team_2" id="wc_team_2" class="col-md-4">
                                        <option value="" selected disabled hidden>Chọn đội 2</option>
                                        @foreach ($list_teams as $list_team)
                                            <option value="{{ $list_team->id }}">{{ $list_team->name }}</option>
                                        @endforeach

                                    </select>
                                    <label>Tỷ lệ ăn đội 2:</label>
                                    <input id="rate_two" name="rate_two" value="1.50">
                                    <label > Set đội 2 kèo trên</label>
                                    <input name="h_two" type="checkbox" id="h_two">
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="form-group mt-12">
                                <label>Set kèo cho 2 đội:</label>
                                <div>

                                    <input id="set_keo" name="set_keo" value="0">
                                    <label>Thời gian đá:</label>
                                    <input id="time_start" name="time_start" value="{{Carbon\Carbon::now()}}">
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
