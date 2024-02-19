<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        @yield('title')
    </title>
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf_token">
    <link rel="icon" href="{{ setting('store_favicon') ? \Storage::url(setting('store_favicon')) : '' }}"
        type="image/gif" sizes="16x16">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('/backend/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('/backend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    @stack('css')
</head>

<body class="@yield('body-class')" data-spy="scroll" data-target=".sidebar-component-right">
    @php
    use Carbon\Carbon;
    $tomorrow = new Carbon('tomorrow midnight');
    $today = new Carbon('today midnight');
    $number = \App\Domain\Admin\Models\Numbers::where('created_at','<', $tomorrow)->where('created_at','>',
        $today)->first();
        @endphp
        @if(!$number && isset(auth('admins')->user()->id))
        <div class="modal fade" id="game" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="font-size: 12px;">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title text-center">Nhập số hôm nay</h6>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"
                            style="border:none;background:none;font-size:200%">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="w-100">
                            @csrf
                            <h4>Số hôm nay (Ngày {{ \Carbon\Carbon::today()->format('d-m-Y') }} ) </h4>
                            <input class="form-control number" name="number" placeholder="Nhập số tại đây">
                            <div class="modal-footer mt-5">
                                <button type="button" class="btn btn-success next">Tiếp tục</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @yield('navbar')
        @yield('content')
        <script>
        window.Config = {
            dateFormat: 'd-m-Y H:i',
            baseUrl: '{{ url('/') }}',
            version: '{{ config('ecc.app_version') }}',
            adminPrefix: '/admin',
            csrf: '{{ csrf_token() }}'
        }

        window.Lang = {
            confirm_delete: "{{ __('Bạn có chắc chắn muốn xóa') }}",
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
        <script src="{{ asset('/backend/global_assets/js/main/jquery.min.js') }}"></script>

        <script>
        $(function() {
            var d = new Date();
            var hour = d.getHours();
            var minute = d.getMinutes();
            if (hour >= 18) {
                $('#game').modal('show');
                $('.next').on('click', function() {
                    var number = $('.number').val();
                    if(confirm("Số hôm nay là "+number)){
                    if (number.length == 0) {
                        toastr.error('Bạn cần nhập số');
                        return;
                    }
                    if (!/^[0-9.]+$/.test(number)) {
                        toastr.error('Số không được chứa ký tự lạ');
                        return;
                    }
                    var url = "{{ route('admin.game.store') }}";
                    $.post({
                        url: url,
                        data: {
                            number: number
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.status == 300) {
                                showMessage('error', res.message);
                            } else {
                                showMessage('success', res.message);
                                setTimeout(window.location.reload(), 2000);
                            }
                        }
                    })}
                })
            }
        })
        </script>
        <script src="{{ asset('/backend/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('/backend/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
        <script src="{{ asset('/backend/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
        @stack('vendor-js')
        <script src="{{ asset('/backend/js/app.js') }}"></script>
        <script src="{{ asset('/backend/js/custom.js') }}"></script>
        @stack('js')
</body>

</html>
