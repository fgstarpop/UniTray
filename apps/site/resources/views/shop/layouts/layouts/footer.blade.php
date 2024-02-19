<footer>


    <div class="row d-flex justify-content-center">
        <div class="col-sm-7 re-col-foot">
            @if (setting('store_logo'))
                <a href="{{ route('home') }}" class="logo">
                    <img src="frontend/images/new-logo.jpg"
                        alt="{{ setting('store_name', 'Giangthe.com') }}">
                </a>
            @else
                <a href="{{ route('home') }}" class="logo logo-footer">
                    <img src="{{ asset('frontend/images/new-logo.jpg') }}"
                        alt="{{ setting('store_name', 'Giangthe.com') }}">
                </a>
            @endif
            <br>
            <p class="foot-text">Đọc Truyện Online, Đọc Truyện Miễn Phí, Đọc Truyện Vip Faloo, Đọc Truyện Convert,
                <br> Đọc Truyện Dịch, Đọc Truyện Hay, Đọc Truyện Hot, Đọc Truyện Vip</p>
            <br>
            <p class="foot-text">Liên hệ bản quyền</p>
            <p class="foot-text">Copyright © 2022 Giáng Thế</p>
        </div>
        <div class="col-sm-4 re-col-foot ">
            <p class="foot-text">Mọi vấn đề xin liên hệ:</p>
            <img src="{{ asset('frontend/images/qr.png') }}" alt="{{ setting('store_name', 'Giangthe.com') }}"
                style="width: 30%; display:inline-block;">
            <p class="foot-text" style="display: inline-block;">Group Giáng thế: <br> https://zalo.me/g/rqfhlz892</p>
        </div>
    </div>
    <br>
    <br>
    <div class="row" style="border-radius: 3px; width: 92%; margin: auto; background-color: #282323;">
        <h5 style="color: white;">Từ khóa</h5>
        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>
        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Đọc truyện vip faloo</p>
        </div>
        <br>

        {{-- <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>
        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>

        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>
        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>

        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>
        <div style="display:inline; float:left; max-width: fit-content; margin-right: -17px;">
            <p class="key-word-word">Web giáng thế</p>
        </div>
        <br>
        <br> --}}
    </div>
    </div>
    </div>


</footer>
