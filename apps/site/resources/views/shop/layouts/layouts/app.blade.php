<!doctype html>
<html lang="vi">
<head>
    <base href="{{ asset('') }}"/>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @if((currentUser() && currentUser()->user_vip == 0) || !currentUser())
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6402569697449690"
     crossorigin="anonymous"></script>
    @endif

    <link rel="icon" href="{{ setting('store_favicon') ? \Storage::url(setting('store_favicon')) : '' }}" type="image/gif" sizes="16x16">

    <link rel="stylesheet" href="{{ asset('frontend/external/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/external/fontawesome/css/all.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/external/slick/slick.min.css') }}" type="text/css">


    <link rel="stylesheet" href="{{ asset('frontend/css/custom_style.css') }}">
    <link rel="stylesheet" href="{{ asset('common/toastr/toastr.min.css') }}">
    @if((new \Jenssegers\Agent\Agent())->isMobile())
        <link rel="stylesheet" href="{{ asset('frontend/css/custom_style_mobile.css') }}">
    @endif
    <title>
        @hasSection('title')
            @yield('title')
        @else
            {{ __('Giáng thế') }}
        @endif
    </title>
    @yield('seo')
    @stack('styles')
    @livewireStyles
</head>
<body

@if((new \Jenssegers\Agent\Agent())->isMobile()) class="mobile" @endif>

@if((new \Jenssegers\Agent\Agent())->isDesktop())
    @include('shop.layouts.header')
@endif

@if((new \Jenssegers\Agent\Agent())->isMobile())
    @include('shop.layouts.header-mobile')
@endif

@php
        if((currentUser() && currentUser()->user_vip == 0) || !currentUser()) {
        $time = microtime(true);
        if (Session::has('Adwait')){
            if ($time - Session::get('Adwait') > 0){
                echo "<script>(function(d,z,s){s.src='https://'+d+'/401/'+z;try{(document.body||document.documentElement).appendChild(s)}catch(e){}})('glizauvo.net',5419101,document.createElement('script'))</script>";
                Session::put("Adwait",$time + 60);
            }

        }else{


            Session::put("Adwait",$time + 60);

        }
    }
@endphp

@yield('content')

@livewireScripts

@if((new \Jenssegers\Agent\Agent())->isDesktop())
    @include('shop.layouts.footer')
@endif

@if((new \Jenssegers\Agent\Agent())->isMobile())
    @include('shop.layouts.footer-mobile')
@endif

<div class="go-top">
    <i class="las la-hand-point-up"></i>
</div>

<script>
    var token = '{{ csrf_token() }}';
</script>
<script>
    window.Config = {
        dateFormat: 'd-m-Y H:i',
        baseUrl: '{{ url('/') }}',
        version: '{{ config('ecc.app_version') }}',
        adminPrefix: '/admin',
        csrf: '{{ csrf_token() }}'
    }

    window.Lang = {
        confirm_delete: "{{ __('Bạn có chắc chắn muốn xóa !') }}",
        oh_no: "{{ __('Ôi không !') }}",
        system: "{{ __('Hệ thống') }}",
        success: "{{ __('Thành công !') }}",
        confirm: "{{ __('Xác nhận') }}",
        yes: "{{ __('Có') }}",
        no: "{{ __('Không') }}",
        create: "{{ __('Tạo') }}",
        rename: "{{ __('Đổi tên') }}",
        edit: "{{ __('Chỉnh sửa') }}",
        remove: "{{ __('Xóa') }}",
    }
</script>

<script src="{{ mix('frontend/js/all.js') }}"></script>
@stack('scripts')
@yield('scripts')
@if(setting('custom_scripts'))
    {!!  setting('custom_scripts') !!}
@endif


<script>
    toastr.options = {
        "closeButton": true,
        "timeOut": "5000",
        iconClasses: {
            error: 'alert-error',
            info: 'alert-info',
            success: 'alert-success',
            warning: 'alert-warning'
        }
    };
    $(document).ready(function () {
        $('.tt-list-banner-main').slick({
            rows: 1,
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            adaptiveHeight: true,
            prevArrow: false,
            nextArrow: false,
            responsive: [{
                breakpoint: 1025,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
                {
                    breakpoint: 791,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
        });
    });

    (adsbygoogle = window.adsbygoogle || []).push({});

    $('#earn-fade').click(function() {
        $(this).hide();
        $('#btn-earn-gifts').click()
    })
</script>

{{--ĐAT--}}
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N9C5R0DXH2"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-N9C5R0DXH2');
</script>

{{--ĐAT 2--}}
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-P7K5XX4P5Z"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-P7K5XX4P5Z');
</script>

<!-- End Common Js-->
@include('shop.layouts.partials.notification')

</body>

</html>
