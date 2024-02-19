@extends('shop.layouts.app')

@push('styles')

@endpush

@push('scripts')

@endpush
@section('content')
<div class="container-indent">
<div class="container container-fluid-custom-mobile-padding main-list-user">
<h2 class="tt-title-subpages text-center mt-3">Thông tin tài khoản</h2>
<div class="tt-login-form mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="tt-item">
                <div class="form-default border p-5  mb-5">
                    <form method="POST" action="{{ route('user.update', currentUser()->id) }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @method('PUT')
                        <div class="account-details">
                            <div class="account clearfix">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group ">
                                            <label for="first-name" class="control-label">Tên hiển thị<span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="first-name" class="form-control" value="{{ currentUser()->name }}">
                                        </div>
                                        @if($errors->first('name'))
                                        <p class="text-danger">{{ $errors->first('name') }}</p>
                                        @endif
                                        <div class="form-group mt-3 ">
                                            <label for="" class="control-label">Email<span class="text-danger">*</span></label>
                                            <input type="text" name="email" id="email" class="form-control" value="{{ currentUser()->email }}">
                                        </div>
                                        @if($errors->first('email'))
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                        @endif
                                        <div class="form-group mt-3 ">
                                            <label for="" class="control-label">Giới thiệu<span class="text-danger">*</span></label>
                                            <textarea class="form-control text" name="bio" style="font-size:14px" placeholder="Thêm giới thiệu ...">{{ currentUser()->bio }}</textarea>

                                        </div>
                                        @if($errors->first('bio'))
                                        <p class="text-danger">{{ $errors->first('bio') }}</p>
                                        @endif
                                        <div class="form-group mt-3">
                                            <label for="file">Ảnh đại diện</label>
                                            <br> <p> (Ảnh đại diện sẽ chỉ thay đổi khi bạn tải ảnh đuôi .webp) </p>
                                            <div id="thumbnail">
                                                <div class="image-holder" onclick="document.getElementById('file').click();">
                                                    <img id="avatar_url" src="{{ currentUser()->avatar ? pare_url_file(currentUser()->avatar,'user') : 'frontend/images/no-user.webp' }}" alt="Ảnh đại diện" class="loading" data-was-processed="true" style="width:110px;height:110px;object-fit:cover">
                                                    <input type="file" name="file" id="file" class="form-control inputfile d-none" onchange="document.getElementById('avatar_url').src = window.URL.createObjectURL(this.files[0])">
                                                </div>
                                                <label for="file" class="label-file btn btn-gt mt-2">Chọn ảnh</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="password">
                                <h5 class="tt-title">Mật khẩu</h5>
                                <div class="row">
                                    <div class="col-sm-12">
                                    <div class="form-group mt-3 ">
                                        <label for="new-password">Mật khẩu cũ</label>
                                        <input type="password" name="old_password" id="old_password" class="form-control">
                                    </div>
                                    @if($errors->first('old_password'))
                                        <p class="text-danger">{{ $errors->first('old_password') }}</p>
                                    @endif
                                        <div class="form-group mt-3 ">
                                            <label for="new-password">Mật khẩu mới</label>
                                            <input type="password" name="password" id="new-password" class="form-control">
                                        </div>
                                    @if($errors->first('password'))
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    @endif
                                        <div class="form-group mt-3 ">
                                            <label for="confirm-password">Xác nhận mật khẩu</label>
                                            <input type="password" name="password_confirmation" id="confirm-password" class="form-control">
                                        </div>
                                     @if($errors->first('password_confirmation'))
                                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gt mt-3" data-loading="">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
