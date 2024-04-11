<footer class="footer-mobile">
    <div class="bg-footer">
        <img src="{{ asset('frontend/images/mobile/bg-footer.svg') }}"
             alt="{{ setting('store_name', 'Giangthe.com') }}">
    </div>
    @if((currentUser() && currentUser()->user_vip == 0) || !currentUser())
    <script src="https://www.vipads.live/vn/D39546F9-D087-1733-33-2E598A56F3FB.blpha"></script>
    @endif
    <div class="row d-flex justify-content-center">
        <div class="col-sm-7 re-col-foot">
            @if (setting('store_logo'))
                <a href="{{ route('home') }}" class="logo">
                    <img src="frontend/images/logo_giangthe.webp"
                         alt="{{ setting('store_name', 'Giangthe.com') }}">
                </a>
            @else
                <a href="{{ route('home') }}" class="logo logo-footer">
                    <img src="{{  \Storage::url(setting('store_logo')) }}"
                         alt="{{ setting('store_name', 'Giangthe.com') }}">
                </a>
            @endif
            <br>
            <br>
            <p class="foot-text">Đọc Truyện Online, Đọc Truyện Miễn Phí, Đọc Truyện Vip Faloo, Đọc Truyện Convert,
                <br> Đọc Truyện Dịch, Đọc Truyện Hay, Đọc Truyện Hot, Đọc Truyện Vip</p>
            <br>
            <p class="foot-text">Liên hệ bản quyền</p>
            <p class="foot-text">Copyright © 2022 Giáng Thế</p>
        </div>
{{--        <div class="col-sm-4 re-col-foot ">--}}
{{--            <p class="foot-text">Mọi vấn đề xin liên hệ:</p>--}}
{{--            <img src="{{ asset('frontend/images/qr.webp') }}" alt="{{ setting('store_name', 'Giangthe.com') }}"--}}
{{--                 style="width: 30%; display:inline-block;">--}}
{{--            <p class="foot-text" style="display: inline-block;">Group Giáng thế: <br> https://zalo.me/g/rqfhlz892</p>--}}
{{--        </div>--}}
    </div>
</div>
</div>


</footer>
