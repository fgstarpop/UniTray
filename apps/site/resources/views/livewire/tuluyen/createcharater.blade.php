@if((new \Jenssegers\Agent\Agent())->isDesktop())
    <div style="margin: auto; width:800px">

        <div class="card">
            <div class="card-header">Bắt đầu tu luyện</div>
            <div class="card-body">

                <form>
                    <!-- select -->
                    <div class="form-group row ">
                        @if($errors->has('p_class'))

                            <span>{{ $errors->first('p_class') }}</span>

                        @endif
                        <label for="select_id_0" class="col-sm-2 col-form-label">Chọn Hệ</label>
                        <div class="col-sm-10">
                            <select class="custom-select" name="select_id_0" id="select_id_0" wire:model="p_class">
                                <option value="">Chọn hệ</option>
                                @foreach ($players as $key => $class)
                                    <option value="{{ $key  }}">{{ $class['name_class'] }}</option>

                            @endforeach
                            {{-- @error('p_class')
                            <small class="text-muted form-text" style="color:red ">{{$errors->p_class}}</small>
                            @enderror --}}

                        </div>
                        </select>
                        <small class="text-muted form-text">Vui lòng chọn hệ để tu luyện</small>
                    </div>
            </div>

            <!-- Input type checkbox -->
            <div class="form-group row ">
                <label class="col-sm-2 col-form-label">Giới Tính</label>
                <div class="col-sm-10">
                    <select class="custom-select" name="select_id_0" id="select_id_0" wire:model="p_gioitinh">
                        <option value="">Chọn giới tính</option>

                        <option value="1">Nam</option>

                        <option value="2">Nữ</option>
                        <small class="text-muted form-text">Chọn giới tính</small>
                    </select>
                </div>
            </div>

            <!-- columns -->
            <div class="form-group row ">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <div class="form-row align-items-center">
                        <div class="col-md-6 mb-3">
                            <!-- button -->
                            <button type="button" class="btn btn-primary" wire:click="savecharater">Xác Nhận</button>
                        </div>

                        <div class="col-md-6 mb-3">
                            <!-- button -->
                            <button type="button" class="btn btn-danger" wire:click="backurl">Huỷ Bỏ</button>
                        </div>

                    </div>

                </div>
            </div>

            </form>


        </div>
    </div>

    </div>
@endif
@if((new \Jenssegers\Agent\Agent())->isMobile())
    <style>
        .btn-custom-action,
        .btn-custom {
            background-color: transparent;
            border-color: #C9B708;
            border-style: solid;
            border-width: 2px;
            min-width: 70px;
            color: #C9B708;
        }
        .btn-custom-action {
            min-width: 140px;
        }
        .btn-custom-action.active,
        .btn-custom.active {
            background-color: #C9B708;
            color: #ffffff;
            position: relative;
            padding-right: calc(0.75rem + 12px);
        }
        .btn-custom.active::after {
            content: '';
            position: absolute;
            bottom: 50%;
            right: 2px;
            width: 12px;
            height: 6px;
            border-bottom: 3px solid #ffffff;
            border-left: 4px solid #ffffff;
            transform: rotate(-45deg) translate(-25%, 0);
        }
        .btn-check:focus+.btn,
        .btn:focus {
            box-shadow: unset;
        }
    </style>
    <section class="container mt-4 content search-section" style="margin-bottom: 50px;">
        <div class="mt-4">
            <div class="header">
                <p>Ngươi muốn tu luyện ư?...</p>
                <p>
                    Lão phu cũng thấy ngươi cũng có căn cốt tu đấy, tuy nhiên tu luyện là quá trình không hề dễ dàng.
                    Ngươi hiểu gì về các hệ tu luyện chưa? Nếu chưa hãy tìm hiểu kỹ trước khi bắt đầu nhé
                </p>
            </div>
            <div class="body">
                <form action="{{ route('user.create-character') }}" method="POST">
                    @csrf
                    <!-- Input type checkbox -->
                    <div class="form-group d-flex flex-row justify-content-between">
                        <label class="col-sm-2 col-form-label">Ngươi là</label>
                        <div class="d-flex">
                            <div class="form-check px-1">
                                <input type="checkbox" class="btn-check" id="p_gioitinh" value="1" name="p_gioitinh" autocomplete="off">
                                <label class="btn btn-custom rounded-pill" for="p_gioitinh">Nam</label><br>
                            </div>
                            <div class="form-check px-1">
                                <input type="checkbox" class="btn-check" id="p_gioitinh" value="2" name="p_gioitinh" autocomplete="off">
                                <label class="btn btn-custom rounded-pill" for="p_gioitinh">Nữ</label><br>
                            </div>
                        </div>
                    </div>
                    @error('p_gioitinh')
                        <span class="form-text text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                    <!-- select -->
                    <div class="form-group d-flex flex-column">
                        <label for="select_id_0" class="col-sm-2 col-form-label">Ngươi muốn tu luyện hệ?</label>
                        <select class="form-select form-select-sm border-4 my-4 mx-5" style="width: unset" aria-label=".form-select-sm example" name="p_class" id="p_class">
                            @foreach ($players as $key => $class)
                                <option value="{{ $key  }}">{{ $class['name_class'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('p_class')
                        <span class="form-text text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                    <!-- columns -->
{{--                    <div class="form-group">--}}
{{--                        <div class="clan-description">--}}
{{--                            <div class="clan py-3 px-5 bg-white">--}}
{{--                                <p>--}}
{{--                                    Hệ tu tiên có công pháp riêng biệt tăng tỷ lệ chí mạng và có sát thương chí mạng cực cao.--}}
{{--                                </p>--}}
{{--                                <p class="text-danger">--}}
{{--                                    Gợi ý: Kết hợp với công pháp có tỷ lệ chí mạng cao và tập trung tăng sát thương--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                            <div class="clan py-3 px-5 bg-white hidden">--}}
{{--                                <p>--}}
{{--                                    Hệ tu tiên có công pháp riêng biệt tăng tỷ lệ chí mạng và có sát thương chí mạng cực cao.--}}
{{--                                </p>--}}
{{--                                <p class="text-danger">--}}
{{--                                    Gợi ý: Kết hợp với công pháp có tỷ lệ chí mạng cao và tập trung tăng sát thương--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                            <div class="clan py-3 px-5 bg-white hidden">--}}
{{--                                <p>--}}
{{--                                    Hệ tu tiên có công pháp riêng biệt tăng tỷ lệ chí mạng và có sát thương chí mạng cực cao.--}}
{{--                                </p>--}}
{{--                                <p class="text-danger">--}}
{{--                                    Gợi ý: Kết hợp với công pháp có tỷ lệ chí mạng cao và tập trung tăng sát thương--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group text-center mt-3">
                        <!-- button -->
                        <button type="submit" class="btn btn-custom-action rounded-pill active">Chọn</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @section('scripts')
        <script>
            $(document).ready(function () {
                $('.btn-custom').click(function() {
                    $('.btn-custom').removeClass('active')
                    $(this).addClass('active')
                })
            })
        </script>
    @endsection
@endif
