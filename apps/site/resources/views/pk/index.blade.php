@extends('shop.layouts.app')
@section('content')


    @if (!$player->is_pkconfirm)
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: 'Thiên ngoại chiến trường',
                    text: 'Bạn muốn tham gia so tài PK chứ?',
                    showCancelButton: true,
                    confirmButtonColor: 'rgb(217 175 7)',
                    cancelButtonColor: 'rgb(225 91 91)',
                    confirmButtonText: 'Tham gia',
                    background: '#536280',
                    cancelButtonText: 'Không',
                    confirmButton: 'confirm-button-class',
                    cancelButton: 'cancel-button-class',
                    customClass: {
                        popup: 'text-pk',
                        confirmButton: 'pk-button-class',
                        cancelButton: 'pk-button-class',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var urlConfirm = "{{ route('pk.isconfirm') }}";
                        var url = "{{ route('home') }}";
                        var urlpk = "{{ route('pk.index') }}";
                        $.get({

                            url: urlConfirm,
                            // dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                window.location.replace(urlpk);
                            },
                            error: function() {
                                window.location.replace(url);
                            }
                        })


                    }
                });
            })
        </script>
    @else
        <div class="container pk-page">
            <div class="row top-pk">
                <h2 style="padding-bottom: 14px;"> THIÊN NGOẠI CHIẾN TRƯỜNG </h2>
            </div>
            <div class="row">
                <div class="row body-top-pk">
                    <div class="col-12">
                        <h6 style="margin-top:10px;">Chiến tích</h6>
                    </div>
                    <div class="col-12">

                        <p style="margin-bottom: 0.1rem;">- Xếp hạng tháng này: Top {{ $rank }}</p>
                    </div>
                    <div class="col-12">
                        <p style="margin-bottom: 0.1rem;">- Số trận thắng: {{ $player->win }}</p>
                    </div>
                    <div class="col-12">
                        <p style="margin-bottom: 0.1rem;">- Số trận thua: {{ $player->lose }}</p>
                    </div>
                    <div class="col-12">
                        <p style="margin-bottom: 0.1rem;">- Số trận trong tháng này: {{ $player->tongpk }}</p>
                    </div>
                    <div class="col-12">
                        <p style="margin-bottom: 0.1rem;">- Số tích phân đang có: {{ $player->tichphan }}</p>
                    </div>
                </div>
               
                <div class="row body-mid-pk">
                    <div class="col-12">
                        <p> Chế độ PK: Có 2 chế độ Thường và Cao cấp
                            <br> - Khi ở chế độ thường hay cao cấp, bạn đều có thể chủ động đi PK hoặc được hệ thống phân
                            phối
                            làm đối thủ của người khác.
                            <br> - Nếu bạn không muốn bị động bị hệ thống phân phối làm đối thủ của người khác thì có thể
                            thuê
                            người hộ đạo ở phần Shop.
                            <br> - Khi thắng ở chế độ thường sẽ chỉ nhận điểm tích phân và không có phần quà khác. Khi thua
                            sẽ bị mất 5 điểm căn cốt.
                            <br> - Ở chế độ cao cấp, bạn cần 25 linh thạch mỗi lần PK. Thắng bạn sẽ nhận được gấp 9 lần điểm
                            tích phân so với chế độ thường và 40 linh thạch. Thua bạn sẽ bị mất 25 linh thạch dùng để tham
                            gia PK, mất điểm tích phân và mất 5 điểm căn cốt.
                            <br> - Mỗi ngày user thường sẽ có 10 lượt chủ động PK, user VIP sẽ có 20 lượt chủ động PK.
                            <br> - Ấn vào quảng cáo để có thêm 5 lượt PK mới.
                            <br> - Muốn mạnh lên thì có thể mua các gói tu luyện ở phần gói ưu đãi.
                        </p>
                    </div>
                </div>
                <div class="row body-bot-pk">
                    <div class="col-6 pt-4" style="padding-left: 0;">
                        <button type="button" class="btn btn-gt gift btn-pk" data-bs-toggle="modal"
                            data-bs-target="#tim-kiem">Tìm đối thủ
                        </button>
                    </div>
                    <div class="col-6 pt-4" style="padding-right: 0;">
                        <button type="button" class="btn btn-gt gift btn-pk" data-bs-toggle="modal"
                            data-bs-target="#tutorial">Hướng dẫn
                        </button>
                    </div>
                    <div class="col-6 pt-4 pb-4" style="padding-left: 0;">
                        <button type="button" class="btn btn-gt gift btn-pk" data-bs-toggle="modal"
                            data-bs-target="#shop">Shop
                        </button>
                    </div>
                    <div class="col-6 pt-4 pb-4" style="padding-right: 0;">
                        <button type="button" class="btn btn-gt gift btn-pk" data-bs-toggle="modal"
                            data-bs-target="#change-mod">Đổi chế độ Pk
                        </button>
                        <p style="color:white; font-weight:400;">
                            @if ($player->pkmode == 0)
                                Thường
                            @else
                                Cao Cấp
                            @endif
                        </p>
                    </div>

                </div>

                <!-- modal shop -->
                <div class="modal fade" id="shop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                    style="font-size: 16px;">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content" style="background: #3c4f79;
     border: solid #cdcd1b 1px;">
                            <div class="modal-header" style="flex-direction: column-reverse;">
                                <h6 class="modal-title"
                                    style="    line-height: 0;
             font-weight: 600;
             color: #ffea1f;
             font-size: 18px;">
                                    Shop</h6>
                                <button
                                    style="    padding: 0rem 1rem;
             background: none;
             color: white;
             font-weight: 600;
             opacity: 15;"
                                    type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                            </div>
                            <div class="modal-body" style="color: white;">
                                <form method="post" action="" style="text-align:left">
                                    @csrf
                                    @if ($player->is_hodao)
                                        <p style="font-weight:400;">Đã mua hộ đạo</p>
                                        <p style="font-weight: 400;
                            margin-top: -15px;">Thời
                                            gian
                                            còn
                                            lại: {{ \Carbon\Carbon::parse($player->date_hodao)->diffInHours() }} giờ.</p>
                                        </p>
                                    @else
                                        <p style="font-weight:400;">Chưa mua hộ đạo</p>
                                    @endif
                                    <div class="row"
                                        style="border-bottom: 1px dashed #dee2e6;
                 border-top: 1px dashed #dee2e6;
                 padding-top: 18px;
                 padding-bottom: 18px;">

                                        <div class="col-3"
                                            style="display: flex;
                    align-items: center;
                    justify-content: end;">
                                            <img src="\frontend\images\mobile\nguoihodao.webp" alt="no-image" class=""
                                                style="height: 50px;
                    width: 50px;" />

                                        </div>
                                        <div class="col-9">
                                            <p class="fix-p" style="font-weight:600;"> Người hộ đạo</p>
                                            <p class="fix-p" style="font-weight:400;"> Khi sử dụng sẽ không bị người khác
                                                đánh
                                                lén trong vòng 30 ngày tới </p>
                                            <p class="fix-p"
                                                style="font-weight: 400;
                        display: inline-block;
                        margin-top: 19px;">
                                                Giá: 1000 <img src="\frontend\images\mobile\linhthach.webp" alt="no-image"
                                                    class="" style="height: 20px; width: 25px;" />
                                            </p>
                                            <button type="button"
                                                style="border-radius: 20px;
                        display: inline-block;
                        float: right;
                        margin-top: 16px;
                        width: 50%;
                        height: 43px;"
                                                class="btn btn-gt gift mua-ho-dao" data-wallet="">Mua
                                            </button>
                                        </div>

                                    </div>

                                </form>
                                <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- huong dan -->
                <div class="modal fade" id="tutorial" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 16px;">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content" style="background: #3c4f79;
      border: solid #cdcd1b 1px;">
                            <div class="modal-header" style="flex-direction: column-reverse;">
                                <h6 class="modal-title"
                                    style="    line-height: 0;
              font-weight: 600;
              color: #ffea1f;
              font-size: 18px;">
                                    Hướng dẫn</h6>
                                <button
                                    style="    padding: 0rem 1rem;
              background: none;
              color: white;
              font-weight: 600;
              opacity: 15;"
                                    type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">X</button>
                            </div>
                            <div class="modal-body" style="color: white;">
                                <p>- Ở Thiên Ngoại Chiến Trường, người chơi có thể thỏa sức mà PK với người chơi khác </p>
                                <br>
                                <p> <b>1. Chế độ PK: Có 2 chế độ Thường và Cao cấp </b>
                                    <br>- Khi ở chế độ thường hay cao cấp, bạn đều có thể chủ động đi PK hoặc được hệ thống
                                    phân
                                    phối làm đối thủ của người khác.
                                    <br> - Nếu bạn không muốn bị động bị hệ thống phân phối làm đối thủ của người khác thì
                                    có
                                    thể thuê người hộ đạo ở phần Shop.
                                    <br> - Khi thắng ở chế độ thường sẽ chỉ nhận điểm tích phân và không có phần quà khác.
                                    Khi thua sẽ bị mất 5 điểm căn cốt.
                                    <br> - Ở chế độ cao cấp, bạn cần 25 linh thạch mỗi lần PK. Thắng bạn sẽ nhận được gấp 9
                                    lần điểm tích phân so với chế độ thường và 40 linh thạch. Thua bạn sẽ bị mất 25 linh thạch
                                    dùng để tham gia PK, mất điểm tích phân và mất 5 điểm căn cốt.
                                    <br> - Mỗi ngày user thường sẽ có 10 lượt chủ động PK, user VIP sẽ có 20 lượt chủ động PK.
                                    <br> - Ấn vào quảng cáo để có thêm 5 lượt PK mới.
                                    <br> - Muốn mạnh lên thì có thể mua các gói tu luyện ở phần gói ưu đãi.
                                </p>
                                <br>
                                <p> <b>2. Điểm tích phân nhận sau PK: </b>
                                    <br> - Thắng: Nhận 10 tích phân từ hệ thống và 10% tích phân của user thua.
                                    <br> - Thua: Mất 10% tích phân đang có.
                                    <br> - Lưu ý: User thắng ở chế độ cao cấp sẽ nhận được 90 tích phân từ hệ thống và 10%
                                    tích
                                    phân của user thua.
                                </p>
                                <br>
                                <p><b> 3. Thưởng Thần Long Bảng:</b>
                                    <br> - Top 10 Thần Long Bảng sau mỗi tháng sẽ được quyền mua 1 gói SUPER trong gói ưu
                                    đãi tu
                                    luyện vào tháng tiếp theo.
                                    <br> - Những đặc quyền khác sẽ đợi quá trình phát triển sau
                                </p>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal search -->
                <div class="modal fade" id="change-mod" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" style="font-size: 16px;">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content" style="background: #596785;
         border: solid #cdcd1b 1px;">
                            <div class="modal-header"
                                style="flex-direction: column-reverse;
             border-bottom: none;">
                                <h6 class="modal-title"
                                    style="line-height: 0;
                 font-weight: 600;
                 color: #ffea1f;
                 font-size: 18px;">
                                    Đổi chế độ PK</h6>
                                <button
                                    style="    padding: 0rem 1rem;
                 background: none;
                 color: white;
                 font-weight: 600;
                 opacity: 15;"
                                    type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close">X</button>
                            </div>
                            <div class="modal-body"
                                style="
                display: flex;
                justify-content: center;">
                                <form method="post" action="" style="text-align:left">
                                    @csrf
                                </form>
                            </div>
                            @if ($player->pkmode == 0)
                                <div class="row"
                                    style="justify-content: center;
                            margin-bottom: 23px;">
                                    <div class="col-5">
                                        <button type="button" id="chon-thuong"
                                            style="border-radius: 20px; background-color:#a7a7a7!important; width:100%"
                                            class="btn btn-gt gift" disabled data-chedo="0"
                                            onclick="changeModepk(this)">Thường
                                        </button>
                                    </div>
                                    <div class="col-5">
                                        <button type="button" id="chon-cao-cap" style="border-radius: 20px; width:100%"
                                            class="btn btn-gt gift" data-chedo="1" onclick="changeModepk(this)">Cao Cấp
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row"
                                    style="justify-content: center;
                            margin-bottom: 23px;">
                                    <div class="col-5">
                                        <button type="button" id="chon-thuong" style="border-radius: 20px; width:100%"
                                            class="btn btn-gt gift" data-chedo="0" onclick="changeModepk(this)">Thường
                                        </button>
                                    </div>
                                    <div class="col-5">
                                        <button type="button" id="chon-cao-cap"
                                            style="border-radius: 20px; background-color:#a7a7a7!important; width:100%"
                                            class="btn btn-gt gift" disabled data-chedo="1"
                                            onclick="changeModepk(this)">Cao
                                            Cấp
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-section">
                        <div class="chat-header">
                            <span class="fs-4">Chat Box</span><br>
                            <span class="mt-4" style="font-size: 13px;">Kênh chính</span>
                            <hr>
                            <div class="chat-body home-scroll">
                                <iframe src="https://www5.cbox.ws/box/?boxid=930287&boxtag=brZbTV" width="100%"
                                    height="620" allowtransparency="yes" allow="autoplay" frameborder="0"
                                    marginheight="0" marginwidth="0" scrolling="auto"></iframe>
                            </div>

                        </div>
                    </div>
                    <hr style="height: 45px; background: #596785; margin-top: 0px;">

                    <div class="row table-than-long" style="">
                        <button style="margin-top: -36px!important;" type="button" class="btn btn-gt gift than-long-button">Bảng vinh danh
                        </button>
                        <div class="col-12" style="padding-left: 0;">

                            @foreach ($vinhDanh as $key => $playerTLB)
                                <div class="row table-than-long-element">

                                    <div class="col-3 than-long-user">
                                        <p class="fix-p" style="font-weight:bold;"> {{ $key + 1 }} </p>
                                        <img src="{{ $playerTLB->get_users->avatar ? pare_url_file($playerTLB->get_users->avatar, 'user') : 'frontend/images/no-user.webp' }}"
                                            alt="" class="comment-avatar" style="margin-left: 0.8rem;" />
                                    </div>
                                    <div class="col-7">
                                        <p class="fix-p" style="font-weight:500;"> {{ $playerTLB->get_users->name }} </p>
                                    </div>
                                    <div class="col-2 than-long-user">
                                        <p class="fix-p" style="font-weight:500;"> {{ $playerTLB->tichphan }} </p>
                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>


            <div class="row table-than-long" style="">
                <button style="margin-top: -36px!important;" type="button" class="btn btn-gt gift than-long-button">Thần
                    long bảng
                </button>
                <div class="col-12" style="padding-left: 0;">

                    @foreach ($thanLongBang as $key => $playerTLB)
                        <div class="row table-than-long-element">

                            <div class="col-3 than-long-user">
                                <p class="fix-p" style="font-weight:bold;"> {{ $key + 1 }} </p>
                                <img src="{{ $playerTLB->get_users->avatar ? pare_url_file($playerTLB->get_users->avatar, 'user') : 'frontend/images/no-user.webp' }}"
                                    alt="" class="comment-avatar" style="margin-left: 0.8rem;" />
                            </div>
                            <div class="col-7">
                                <p class="fix-p" style="font-weight:500;"> {{ $playerTLB->get_users->name }} </p>
                            </div>
                            <div class="col-2 than-long-user">
                                <p class="fix-p" style="font-weight:500;"> {{ $playerTLB->tichphan }} </p>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>
        <!-- modal search -->
        {{-- <div class="modal fade" id="tim-kiem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="font-size: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content" style="background: #596785;
            border: solid #cdcd1b 1px;">
                    <div class="modal-header"
                        style="flex-direction: column-reverse;
                border-bottom: none;">
                        <h6 class="modal-title"
                            style="    line-height: 0;
                    font-weight: 600;
                    color: #ffea1f;
                    font-size: 18px;">
                            Tìm đối thủ</h6>
                        <button
                            style="    padding: 0rem 1rem;
                    background: none;
                    color: white;
                    font-weight: 600;
                    opacity: 15;"
                            type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body" style="color: white;">
                        <form method="post" action="" style="text-align:left">
                            @csrf
                            <p style="font-weight:500;">Chọn đối thủ theo ID</p>
                            <textarea id="id-pk" class="form-control text" style="font-size:15px" placeholder="Nhập ID đối thủ..."></textarea>
                            <p style="margin-top: 20px;font-weight:500;">Số lượt pk còn lại trong ngày:
                                {{ $player->luotpk }}</p>
                            <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                                <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button"
                                    style="border-radius: 20px;" class="btn btn-gt gift" data-wallet="" data-pkm="0"
                                    onclick="pk(this)">PK ngẫu nhiên
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#in-pk" type="button" data-pkm="1"
                                    style="border-radius: 20px;" class="btn btn-gt gift" data-wallet=""
                                    onclick="pk(this)">PK theo tên
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}



        <!-- modal in pk -->
        {{-- <div class="modal fade" id="in-pk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="font-size: 15px;">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content"
                    style="background-image: url(/asset/pk/lh.webp);
             background-size: cover;
             border: solid #cdcd1b 1px;
             height: 540px;">
                    <div class="modal-header"
                        style="flex-direction: column-reverse;
                 border-bottom: none;">
                        <button
                            style="background: #eee!important;
                     width: fit-content;
                     font-weight: 600;
                     height: fit-content;
                     opacity: 15;
                     color: black;"
                            type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">Ẩn</button>
                    </div>
                    <div class="modal-body" style="color: white;">
                        <form method="post" action=""
                            style="background: #061653c9; border-radius: 5px; height: 86%;">
                            @csrf
                            <p
                                style="font-weight: 500;
                         text-align: center;
                         padding-top: 21px; margin-bottom: 0;">
                                Quá trình PK</p>
                            <input class="pk-radio" type="radio" id="tim-doi-thu" value="">
                            <label for="tim-doi-thu">Đang tìm đối thủ</label><br>
                            <input class="pk-radio" type="radio" id="wait-doi-thu" value="">
                            <label for="wait-doi-thu">Đợi đối thủ phản hồi</label><br>
                            <input class="pk-radio" type="radio" id="chien-dau" value="">
                            <label for="chien-dau">Chiến đấu</label><br>
                            <input class="pk-radio" type="radio" id="hoan-thanh-pk" value="">
                            <label for="hoan-thanh-pk">Gửi kết quả vào chiến báo</label><br>
                            <p style="background: #ab0e0e;
                         color: #f1e9e9;
                         margin-top: 24px;
                         font-weight: bold;
                         border-radius: 5px;
                         width: 100%;
                         text-align: center;display: none"
                                id="thong-bao-pk">
                            </p>
                        </form>
                        <div class="modal-footer" style="border-top:none;display: flex; justify-content: center;">
                            <button data-bs-dismiss="modal" aria-label="Close" type="button"
                                style="border-radius: 20px; background-color: #c9412e;" class="btn btn-gt gift"
                                data-wallet="" id="cancel-pk">Hủy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>
        <script>
            $(document).ready(function() {
                //your stuff
            });
            //Mua ho dao
            // let is_pk = false;
            // let timeoutSearch, timeoutWait, timeoutPk, timeoutChienbao;

            // function pk(e) {
            //     $('#thong-bao-pk').hide();
            //     $('#cancel-pk').show();
            //     $('#thong-bao-pk').text("");


            //     if (is_pk === false) {
            //         is_pk = true;

            //         $('#tim-doi-thu').prop('checked', true);
            //         $('#thong-bao-pk').show();
            //         $('#thong-bao-pk').text("Đang tìm đối thủ.");
            //         var urlsearch;
            //         if (e.getAttribute("data-pkm") == 1) {
            //             iddoithu = $('#id-pk').val();
            //             if (!Number.isInteger(parseInt(iddoithu)) && iddoithu !== null) {
            //                 Swal.fire({
            //                     icon: 'warning',
            //                     title: 'Thất bại',
            //                     text: "Vui lòng nhập đúng định dạng id.",
            //                     willClose: function() {
            //                         location.reload();
            //                     }
            //                 })

            //             } else {

            //                 urlsearch = "{{ route('pk.actionpk') }}/" + iddoithu;
            //             }
            //         } else {
            //             urlsearch = "{{ route('pk.actionpk') }}";
            //         }

            //         timeoutSearch = setTimeout(() => {
            //             $('#wait-doi-thu').prop('checked', true);
            //             $('#thong-bao-pk').show();
            //             $('#thong-bao-pk').text("Đang chờ đối thủ xác nhận. Vui lòng chờ.");
            //             $.get({

            //                 url: urlsearch,
            //                 dataType: 'json',
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 },
            //                 success: function(response) {
            //                     if (response.code === 0) {



            //                         timeoutWait = setTimeout(() => {

            //                             $('#chien-dau').prop('checked', true);
            //                             $('#cancel-pk').prop('disabled', true);
            //                             $('#thong-bao-pk').show();
            //                             $('#thong-bao-pk').text("Đang chiến đấu.");

            //                             $.get({
            //                                 url: "{{ route('pk.battle') }}",
            //                                 dataType: 'json',
            //                                 headers: {
            //                                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
            //                                         .attr('content')
            //                                 },
            //                                 success: function(response) {
            //                                     if (response.code == 0) {
            //                                         setTimeout(() => {
            //                                             Swal.fire({
            //                                                 icon: 'success',
            //                                                 title: 'Thành Công',
            //                                                 text: response
            //                                                     .message,
            //                                                 willClose: function() { // Xử lý khi user bấm OK
            //                                                     location
            //                                                         .reload(); // Reload trang sau khi user bấm OK
            //                                                 }
            //                                             }), 3000
            //                                         })
            //                                     } else {
            //                                         Swal.fire({
            //                                             icon: 'error',
            //                                             title: 'Lỗi',
            //                                             text: 'Có lỗi xảy trong quá trình pk, reload lại trang.',
            //                                             willClose: function() { // Xử lý khi user bấm OK
            //                                                 location
            //                                                     .reload(); // Reload trang sau khi user bấm OK
            //                                             }
            //                                         })

            //                                     }
            //                                 },
            //                                 error: function() {
            //                                     Swal.fire({
            //                                         icon: 'error',
            //                                         title: 'Lỗi',
            //                                         text: 'Có lỗi xảy ra khi gửi yêu cầu.',
            //                                         willClose: function() { // Xử lý khi user bấm OK
            //                                             location
            //                                                 .reload(); // Reload trang sau khi user bấm OK
            //                                         }
            //                                     })
            //                                 }
            //                             })
            //                         }, response.message);

            //                     } else {
            //                         $('#tim-doi-thu').prop('checked', true);
            //                         $('#thong-bao-pk').show();
            //                         $('#cancel-pk').hide();
            //                         $('#thong-bao-pk').text(response.message);
            //                         is_pk = false;

            //                     }
            //                 },
            //                 error: function() {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Lỗi',
            //                         text: 'Có lỗi xảy ra khi gửi yêu cầu pk.',
            //                         willClose: function() { // Xử lý khi user bấm OK
            //                             location.reload(); // Reload trang sau khi user bấm OK
            //                         }
            //                     })
            //                 }
            //             })
            //         }, 2000);


            //     }




            // }


            // $("#cancel-pk").click(function() {
            //     $.get({
            //         url: "{{ route('pk.cancelbattle') }}",
            //         dataType: 'json',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.code == 0) {
            //                 clearTimeout(timeoutWait);

            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Thành Công',
            //                     text: response.message,
            //                     willClose: function() { // Xử lý khi user bấm OK
            //                         location.reload(); // Reload trang sau khi user bấm OK
            //                     }
            //                 })


            //             } else {
            //                 location.reload();

            //             }
            //         },
            //         error: function() {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Lỗi',
            //                 text: 'Có lỗi xảy ra khi gửi yêu cầu.',
            //                 willClose: function() { // Xử lý khi user bấm OK
            //                     location.reload(); // Reload trang sau khi user bấm OK
            //                 }
            //             })
            //         }
            //     })



            // })


            $('.mua-ho-dao').click(function() {
                urlMuaHoDao = "{{ route('pk.muahodao') }}";
                $.get({
                    url: urlMuaHoDao,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.code == 0) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Mua Thành công',
                                text: response.message,
                                willClose: function() { // Xử lý khi user bấm OK
                                    location.reload(); // Reload trang sau khi user bấm OK
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Mua Thất Bại',
                                text: response.message,
                                willClose: function() { // Xử lý khi user bấm OK
                                    location.reload(); // Reload trang sau khi user bấm OK
                                }
                            })
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                            willClose: function() { // Xử lý khi user bấm OK
                                location.reload(); // Reload trang sau khi user bấm OK
                            }
                        })
                    }
                })
            })

            //Doi che do
            function changeModepk(e) {
                var url = "";
                if (e.getAttribute("data-chedo") == 0) {
                    url = "{{ route('pk.modethuong') }}";
                } else {
                    url = "{{ route('pk.modecc') }}";
                }
                $.get({
                    url: url,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.code === 0) { // Kiểm tra mã code từ phản hồi JSON
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành Công',
                                text: response.message,
                                willClose: function() { // Xử lý khi user bấm OK
                                    location.reload(); // Reload trang sau khi user bấm OK
                                }
                            })

                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Thất bại',
                                text: response.message,
                            })
                        }

                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                            willClose: function() { // Xử lý khi user bấm OK
                                location.reload(); // Reload trang sau khi user bấm OK
                            }
                        })
                    }
                    // error: function() {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Lỗi',
                    //         text: 'Có lỗi xảy ra khi gửi yêu cầu.',
                    //     })
                    // }
                })


            }

            function messagepk(code, message) {
                if (code === 0) { // Kiểm tra mã code từ phản hồi JSON
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành Công',
                        text: message,

                    })

                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thất bại',
                        text: message,
                    })
                }

            }
        </script>
    @endif
@endsection
