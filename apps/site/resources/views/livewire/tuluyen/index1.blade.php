<div class="container">
    <h5 id="user-class">

        Bạn đang tu luyện hệ: {{ $players['class_name'] }}
    </h5>
    <br>
    <br>
    <div class="row d-flex justify-content-center">
        <div class="col-sm-5">
            <div class="text-center container">
                <img src="{{ asset("$players[img]") }}"
                    style=" width: 80%; vertical-align: bottom; border-radius: 5px; position: relative;">



                <p class="font-weight-normal text-under-image" style="font-size: 2em;margin: 20px 0px 35px 0px;">
                    {{ $players['lv_name'] }} </p>
                <p class="font-weight-normal text-under-image" style="font-size: 1.5em">
                    Linh thạch: {{ number_format($players['linh_thach']) }}<img
                        src="{{asset('asset/game/item/linhthach.png')}}" style="width: 1em;height: 1em;">
                </p>
                <div class="text-center container" style="margin-top:5px">
                    <button type="button" class="btn btn-success normal-word" data-bs-toggle="modal"
                        data-bs-target="#roll_cancot" style="width: 150px;margin-bottom: 5px;">Tăng căn cốt</button>
                    <button type="button" class="btn btn-success normal-word" data-bs-toggle="modal"
                        data-bs-target="#roll_khivan" style="width: 150px; margin-bottom: 5px;">Tăng khí vận</button>
                </div>

                <div class="text-center container" style="margin-top:5px">
                    <button type="button" class="btn btn-success normal-word" data-bs-toggle="modal"
                        data-bs-target="#change_linh_thach" style="width: 150px;margin-bottom: 5px;">Đổi linh
                        thạch</button>
                    <button type="button" class="btn btn-success normal-word" data-bs-toggle="modal"
                        data-bs-target="#tay_diem" style="width: 150px;margin-bottom: 5px;">Tẩy điểm</button>
                </div>

                {{-- @auth('web')
                    @if (currentUser()->id == $user->id)
                        <p class="font-weight-normal text-under-image">Công pháp : Ngũ hành thuật</p>
                        <p class="font-weight-normal text-under-image">Tốc độ tu luyện: 15 tu vi/phút</p>
                    @endif
                @endauth --}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="container pt-4">
                <div class="container-bar">

                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated gray400 2s linear infinite"
                            role="progressbar" aria-valuenow="{{ $players['exp'] }}" aria-valuemin="0"
                            aria-valuemax="100" style="width: {{ $players['percent_exp'] }}%">
                            @if ($players['is_lv_up'])
                                {{ $players['exp'] }}/{{ $players['max_exp'] }}
                            @endif
                        </div>

                    </div>

                    {{-- <div class="level level-up"></div> --}}
                    <p>
                    <div class="d-flex justify-content-between">
                        <span
                            style="font-size: 16px; font-weight: bold;@if ($players['is_lv_up']) align-self: center; @endif">Lv{{ $players['lv'] }}</span>

                        @if ($players['is_lv_up'])
                            <button type="button" class="btn btn-success normal-word" id="bdotpha"
                                style="width: 150px; padding:auto" onclick="dotpha()">
                                Đột phá
                            </button>
                        @else
                            <span style="font-weight: 500;">{{ $players['exp'] }}/{{ $players['max_exp'] }} Tu
                                vi</span>
                        @endif
                        <span
                            style="font-size: 16px; font-weight: bold; @if ($players['is_lv_up']) align-self: center; @endif">Lv{{ $players['lv'] + 1 }}</span>
                    </div>

                    </p>



                </div>
            </div>
            <div class="row d-flex justify-content-center" style="margin-top: 80px;">
                <div class="col-sm-5">

                    <div>
                        <p class="normal-word">Sinh Lực:
                        <div class="progress">
                            <div class="progress-bar  progress-bar-striped progress-bar-animated gray400 2s linear infinite"
                                role="progressbar" aria-valuenow="{{ $players['hp'] }}" aria-valuemin="0"
                                aria-valuemax="100"
                                style="width: {{ $players['percent_hp'] }}%; background-color:red ">
                                {{ number_format($players['hp']) }}/{{ number_format($players['max_hp']) }}</div>
                        </div>
                        </p>
                        <p class="normal-word">Tốc độ hồi sinh lực: {{ $players['hp_regen'] * 100 }}% mỗi phút</p>

                        </p>

                        <p class="normal-word">{{ Str::title($players['mp_name']) }}:



                        <div class="progress">
                            <div class="progress-bar  progress-bar-striped progress-bar-animated gray400 2s linear infinite"
                                role="progressbar" aria-valuenow="{{ $players['mp'] }}" aria-valuemin="0"
                                aria-valuemax="100"
                                style="width: {{ $players['percent_mp'] }}%; background-color:orchid ">
                                {{ number_format($players['mp']) }}/{{ number_format($players['max_mp']) }}</div>
                        </div>
                        </p>


                        <p class="normal-word">Tốc độ hồi {{ $players['mp_name'] }}: {{ $players['mp_regen'] * 100 }}%
                            mỗi phút</p>

                    </div>
                    <div class="">

                        <p class="normal-word">Tỷ lệ né đòn: {{ $players['dodge'] * 100 }}% </p>
                        <p class="normal-word">Tỷ lệ chí mạng: {{ $players['crit'] * 100 }}% </p>
                        <p class="normal-word">Sát thương chí mạng: {{ $players['crit_dmg'] * 100 }}% </p>
                    </div>

                </div>
                <div class="col-sm-5">
                    <div class="">
                        <p class="normal-word">Sát thương: {{ number_format($players['atk'], 2) }} </p>
                        <p class="normal-word">Phòng thủ: {{ $players['def'] }} </p>
                        <p class="normal-word">Tốc độ đánh: {{ number_format($players['atk_speed'], 2) }} </p>
                        <p class="normal-word">Căn cốt: {{ $players['can_co'] }} </p>
                        <p class="normal-word">Khí vận: {{ $players['luk'] }} </p>

                        <p class="normal-word">Sức mạnh: {{ $players['str'] }}
                            @if ($players['point'] > 0)
                                <i wire:loading.remove class="fad fa-plus-circle re-plus"
                                    wire:click="addpoint(1,'str')"></i>
                            @endif

                        </p>
                        <p class="normal-word">Nhanh nhẹn: {{ $players['agi'] }}
                            @if ($players['point'] > 0)
                                {{-- <i class="fad fa-plus-circle re-plus"></i> --}}
                                <i wire:loading.remove class="fad fa-plus-circle re-plus"
                                    wire:click="addpoint(1,'agi')"></i>
                            @endif

                        </p>

                        <p class="normal-word">Thể lực: {{ $players['vit'] }}
                            @if ($players['point'] > 0)
                                <i wire:loading.remove class="fad fa-plus-circle re-plus"
                                    wire:click="addpoint(1,'vit')"></i>
                            @endif

                        </p>
                        <p class="normal-word">Năng lượng: {{ $players['ene'] }}
                            @if ($players['point'] > 0)
                                <i wire:loading.remove class="fad fa-plus-circle re-plus"
                                    wire:click="addpoint(1,'ene')"></i>
                            @endif

                        </p>
                        <div wire:loading wire:target="addpoint">
                            <p class="normal-word" style="color: red">
                                Hệ thống đang xử lý ....
                            </p>
                        </div>

                        <p class="normal-word">Điểm chưa phân phối: {{ $players['point'] }} </p>
                        <p>
                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#add_point_mul" style="width: 150px; float:left">
                    Tẩy điểm --}}
                            @if ($players['point'] > 0)
                                <button type="button" class="btn btn-success normal-word" data-bs-toggle="modal"
                                    data-bs-target="#add_point_mul" style="width: 150px;">
                                    Cộng nhiều điểm
                                </button>
                            @endif

                        </p>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div wire:ignore.self class="row d-flex justify-content-center">
        <div class="col-sm-6">
            <p class="normal-word">Tủ đồ cá nhân</p>
            <div class="row" id="ruongvatpham">
                <!-- vat pham -->
                {{-- @dd($item_list) --}}
                @foreach ($item_list as $id => $item)
                    <div class="col-2 book-own id-vatpham">
                        <span  onclick="showitem('im{{$id}}')">{{ $item['sl'] }}</span>
                        <img class="kungfu-book" rare="{{ $item['item_rare'] }}"
                            src="{{ asset("asset/game/item/$item[item_type]/$item[item_id].png") }}"
                            style="width:100%; "  onclick="showitem('im{{$id}}')">

                        <div wire:ignore.self class="modal" id="im{{ $id }}" role="dialog"
						data-backdrop="true" data-keyboard="true">
                            <div class="modal-dialog  modal-dialog-centered">
                                <div class="modal-content" >
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{$item['name']}}</h4>
                                        <button type="button" class="btn-close re-btn-vip" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" >
                                        Số lượng hiện có: {{ $item['sl'] }}
                                        <p class="font-vip-modal text-center">{{ $item['info'] }}</p>
                                        @if(isset($item['op']))
                                            @foreach ($item['op'] as $op)

                                                <p class="font-vip-modal text-center">{{ $op['name'] }}</p>
                                            @endforeach
                                        @endif



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success btn-re-vatpham btn-re-color"
                                            style="width: 20%" onclick="sudungvp('{{ $id }}')"
                                            >Dùng</button>
                                        <button type="button" class="btn btn-success btn-re-vatpham btn-re-color"
                                            style="width: 50%" onclick="dungnhieu('{{ $id}}' , {{$item['sl']}})">Dùng
                                            Nhiều</button>

                                        <button type="button" class="btn btn btn-danger btn-re-vatpham"
                                            style="width: 20%" onclick="xoa_vatpham('{{ $id }}')"
                                            >Vứt</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                @endforeach

            </div>
        </div>
        <div class="col-sm-5 using-mobile-col">
            <p class="normal-word">Đang sử dụng</p>
            {{-- <div class="container book-using">
                <img class="" src="frontend/images/book-test.png" style="width: 23%;">
                <span class="normal-word">Ngũ hành thuật (quyển 1)</span>

            </div> --}}



        </div>
    </div>
    <br>
    {{-- modal cộng nhiều điểm --}}
    <div class="modal fade" id="add_point_mul" tabindex="-1" aria-hidden="true" style="font-size: 12px;"
        name="add_point_mul" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Cộng nhiều điểm </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <form style="text-align:left"> --}}


                    <label>
                        @error('add_mul_point')
                            <div class="alert alert-error">

                                {{ $message }}

                            </div>
                        @enderror
                        @error('add_mul_type')
                            <div class="alert alert-error">

                                {{ $message }}

                            </div>
                        @enderror

                        <p class="normal-word">Điểm hiện có: {{ $players['point'] }}</p>
                        <p class="normal-word">Sức mạnh: {{ $players['str'] }}</p>
                        <p class="normal-word">Nhanh nhẹn: {{ $players['agi'] }}</p>
                        <p class="normal-word">Thể lực: {{ $players['vit'] }}</p>
                        <p class="normal-word">Năng lượng: {{ $players['ene'] }}</p>
                        <p class="normal-word">Số điểm muốn cộng</p>
                        <input type="number" min="1" max="{{ $players['point'] }}" value="1"
                            wire:model.lazy="add_mul_point">
                    </label>


                    <select wire:model="add_mul_type">
                        <option>Chọn chỉ số muốn cộng</option>
                        <option value="str">Sức mạnh</option>
                        <option value="agi">Nhanh nhẹn</option>
                        <option value="vit">Sinh lực</option>
                        <option value="ene">Năng lượng</option>

                    </select>


                    <p>
                    <div wire:loading wire:target="addpointmul">
                        <p class="normal-word" style="color: red">
                            Hệ thống đang xử lý ....
                        </p>
                    </div>
                    </p>


                    <div class="modal-footer">
                        <button class="btn btn-gt " wire:click="addpointmul" wire:loading.attr="disabled">Cộng Điểm
                        </button>
                        <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"
                            style="border-radius: 6px;
                        font-weight: normal;border: none;padding: 6px 15px;font-size: 14px;">Huỷ</button>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>



    {{-- modal đổi vàng sang linh thạch --}}
    <div class="modal fade" id="change_linh_thach" tabindex="-1" aria-hidden="true" style="font-size: 12px;"
        name="change_linh_thach" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Đổi linh thạch</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <form style="text-align:left"> --}}

                    @error('i_lt')
                        <div class="alert alert-error">

                            {{ $message }}

                        </div>
                    @enderror

                    <p class="text-center" style="font-size: 2em">50 vàng = 1 linh thạch</p>
                    <p class="normal-word">Vàng hiện có: {{ number_format($gold) }}</p>
                    <p class="normal-word">Linh thạch hiện có: {{ number_format($players['linh_thach']) }}</p>
                    <p class="normal-word">Số linh thạch muốn đổi:
                        <input type="number" wire:model.debounce.500ms="i_lt">
                    </p>

                    <p class="normal-word">

                        Số vàng tiêu hao: <a wire:model="total_gold"> {{ number_format($total_gold) }} vàng</a>

                    </p>
					<p class="normal-word">

                        Tu vi: <a wire:model="total_tuvi"> +{{ number_format($total_tuvi) }} {{currentUser()->user_vip == 1 ? '(+10%)' : ''}}</a>
						<br>
						(Căn cốt càng cao tu vi tu vi luyện hoá được càng nhiều.)
                    </p>
                    <div wire:loading wire:target="doilinhthach">
                        <p class="normal-word" style="color: red">
                            Hệ thống đang xử lý ....
                        </p>
                    </div> <!-- 1000ms -->
                    <div class="modal-footer">
                        <button class="btn btn-gt " wire:click="doilinhthach"
                            wire:loading.attr="disabled">Đổi</button>
                        <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"
                            style="border-radius: 6px;
                        font-weight: normal;border: none;padding: 6px 15px;font-size: 14px;">Huỷ</button>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roll_cancot" tabindex="-1" aria-hidden="true" style="font-size: 12px;top:75px"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-vip-head ">

                    <button type="button" class="btn-close re-btn-vip" data-bs-dismiss="modal"
                        style="float: right;"></button>
                    <br>
                    <br>
                    <br>
                    <h5 class="text-center">Bạn muốn tăng căn cốt chứ ?</h5>

                </div>
                <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                    <br>
                    <p class="font-vip-modal text-center">Căn cốt sẽ ảnh hưởng đến tốc độ tăng tu vi, căn
                        cốt càng cao thì tu vi nhận được càng nhiều</p>
                    <div wire:loading wire:target="tangcancot">
                        <p class="normal-word" style="color: red">
                            Hệ thống đang xử lý ....
                        </p>
                    </div>
                </div>

                <div class="modal-vip-foot text-center">
                    <button type="button" class="btn btn-success btn-re-color" wire:click="tangcancot"
                        wire:loading.attr="disabled">Tăng Căn Cốt <br>(Phí 10
                        linh thạch)</button>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roll_khivan" tabindex="-1" aria-hidden="true" style="font-size: 12px;top:75px"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-vip-head ">

                    <button type="button" class="btn-close re-btn-vip" data-bs-dismiss="modal"
                        style="float: right;"></button>
                    <br>
                    <br>
                    <br>
                    <h5 class="text-center">Bạn muốn tăng khí vận chứ ?</h5>

                </div>
                <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                    <br>
                    <p class="font-vip-modal text-center">Khí vận sẽ ảnh hưởng đến thời gian được nhặt vật phẩm,
                        và tỷ lệ ra đồ hiếm.
                    </p>
                    <div wire:loading wire:target="tangkhivan">
                        <p class="normal-word" style="color: red">
                            Hệ thống đang xử lý ....
                        </p>
                    </div>
                </div>

                <div class="modal-vip-foot text-center">
                    <button type="button" class="btn btn-success btn-re-color" wire:click="tangkhivan"
                        wire:loading.attr="disabled">Tăng Khí Vận <br>(Phí 10
                        linh thạch)</button>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tay_diem" tabindex="-1" aria-hidden="true" style="font-size: 12px;top:75px"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-vip-head ">

                    <button type="button" class="btn-close re-btn-vip" data-bs-dismiss="modal"
                        style="float: right;"></button>
                    <br>
                    <br>
                    <br>
                    <h5 class="text-center">Bạn muốn tẩy điểm chứ ?</h5>

                </div>
                <div class="modal-body" style="max-height: 325px; overflow-y: auto;">
                    <br>
                    <p class="font-vip-modal text-center">
                        Tẩy điểm sẽ xoá hết điểm của 4 thuộc tính cơ bản trả về điểm chưa cộng cho bạn.
                    </p>
                    <div wire:loading wire:target="resetpoint">
                        <p class="normal-word" style="color: red">
                            Hệ thống đang xử lý ....
                        </p>
                    </div>
                </div>

                <div class="modal-vip-foot text-center">
                    <button type="button" class="btn btn-success btn-re-color" wire:click="resetpoint"
                        wire:loading.attr="disabled">Tẩy Điểm <br>(Phí 50
                        linh thạch)</button>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>



</div>

@section('scripts')

    <script>


		function sudungvp(id) {


			$('#im'+id).modal('hide');
				 Livewire.emit('sudung_vatphamnn', id,1)







		}
		function xoa_vatpham(id) {
			$('#im'+id).modal('hide');
            Livewire.emit('xoa_vatpham', id)


		}
        function dungnhieu(id,count) {

            (async () => {

                const {
                    value: sl
                } = await Swal.fire({
                    title: 'Dùng Nhiều',
                    background: '#EEEEEE',
                    input: 'text',
                    inputLabel: "Số lượng hiện có: " + count,
                    inputPlaceholder: 'Nhập số lượng'
                })

                if (sl) {
					$('#im'+id).modal('hide');
                    Livewire.emit('sudung_vatphamnn', id, sl)


                }

            })()
        }

		function showitem(id){

			$('#'+id).modal('show');
		}


        function dotpha(a) {

            Swal.fire({
                title: 'Đột phá ?',
                text: `Bạn xác định muốn tốn 50 linh thạch để đột phá lên đẳng cấp cao hơn`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Đột Phá =.='",
                cancelButtonText: 'Em hơi sợ...'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (!$(this).data("loading")) {
                        Livewire.emit('dotpha')
                    }
                }
            })
        }

        // });
    </script>
@endsection
