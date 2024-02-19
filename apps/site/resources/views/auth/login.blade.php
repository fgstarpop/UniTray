@extends('shop.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script !src="">
        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false,
            ignore: "",
            lang: "en",
            rules: {
                name: {
                    required: true,
                },
                password: {
                    required: true,
                },
                remember: {
                    required: false
                }
            },
            messages: {
                name: {
                    required: "Hãy nhập tài khoản",
                },
                password: {
                    required: "Hãy nhập mật khẩu",
                },
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            submitHandler: function (form) {
                var validator = this;
                var submitButton = $(form).find('button[type="submit"]')
                submitButton.addClass('m-loader').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/login",
                    data: $(form).serialize(),
                    dataType: "JSON",
                    success: function (res) {
                        if (res.status) {
                            location.href = res.redirect;
                        }
                    },
                    error: function (e) {
                        submitButton.removeClass('m-loader').prop('disabled', false);
                        if (e.status == 422) {
                            $.each(Object.keys(e.responseJSON.errors), function (key, value) {
                                validator.showErrors({
                                    [value]: e.responseJSON.errors[value][0]
                                });
                            });
                        }
                    }
                });
                return false;
            }
        });

        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });

        $('.forget-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit

            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function (form) {
                form.submit();
            }
        });

        $('.forget-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.forget-form').validate().form()) {
                    $('.forget-form').submit();
                }
                return false;
            }
        });

        $('.register-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block first-capitalize', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    maxlength: 255,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 32
                },
                password_confirmation: {
                    equalTo: "#register_password"
                },
                phone: {
                    required: true,
                },
                gender: {
                    required: true
                },
                day_of_birth: {
                    required: true
                },
                month_of_birth: {
                    required: true
                },
                year_of_birth: {
                    required: true
                },
            },
            messages: {
                name: {
                    required: 'Họ tên không được bỏ trống!'
                },
                email: {
                    required: 'Email không được bỏ trống!',
                    maxlength: 'Email không được quá 255 ký tự!',
                    email: 'Email không đúng định dạng!'
                },
                password: {
                    required: 'Mật khẩu không được bỏ trống!',
                    minlength: 'Mật khẩu phải ít nhất 8 ký tự!',
                    maxlength: 'Mật khẩu không được quá 32 ký tự!'
                },
                password_confirmation: {
                    equalTo: 'Mật khẩu mới không trùng khớp!'
                },
                phone: {
                    required: 'Số điện thoại không được bỏ trống!',
                },
                gender: {
                    required: 'Giới tính không được bỏ trống!'
                },
                day_of_birth: {
                    required: 'Ngày sinh không được bỏ trống!'
                },
                month_of_birth: {
                    required: 'Tháng sinh không được bỏ trống!'
                },
                year_of_birth: {
                    required: 'Năm sinh không được bỏ trống!'
                },
            },

            invalidHandler: function (event, validator) { //display error alert on form submit

            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },

            submitHandler: function (form) {
                var validator = this;
                var submitButton = $(form).find('button[type="submit"]')
                $.ajax({
                    type: "POST",
                    url: "/register",
                    data: $(form).serialize(),
                    dataType: "JSON",
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message, 'Thành Công');
                            setTimeout(location.reload.bind(location), 2000);
                        }
                    },
                    error: function (e) {
                        submitButton.removeClass('m-loader');
                        if (e.status === 422) {
                            $.each(Object.keys(e.responseJSON.errors), function (key, value) {
                                validator.showErrors({
                                    [value]: e.responseJSON.errors[value][0]
                                });
                            });
                        }
                    }
                });
                return false;
            }
        });

        $('.register-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.register-form').validate().form()) {
                    $('.register-form').submit();
                }
                return false;
            }
        });
    </script>
@endpush
