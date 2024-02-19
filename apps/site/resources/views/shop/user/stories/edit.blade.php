@extends('shop.layouts.app')
@section('title')
    {{ $story->name }} - @if(!empty(setting('store_name')))
        -
    @endif
    {{ setting('store_name') }}
    @if(!empty(setting('store_slogan')))
        -
    @endif
    {{ setting('store_slogan') }}
@endsection
@section('seo')
    <link rel="canonical" href="{{ request()->fullUrl() }}">
    <meta name="title" content="{{ $story->name }}">
    <meta name="description" content="{{ $story->description }}">
    <meta name="keywords" content="{{ $story->name }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $story->name }}">
    <meta property="og:description" content="{{ $story->description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="Giangthe.com">
@stop
@section('content')
    <section class="container mt-4 content main-list-user">
        <h5>@if($story->type == 1) Đăng truyện nhúng @else Đăng truyện sáng tác @endif</h5>
        <div class="row">
            <form id="form-author" class="form-horizontal col-md-6" action="{{ route('stories.update', $story) }}"
                  enctype="multipart/form-data" method="POST">
                @csrf
                <div class="form-group">
                    <label class="col-sm-12">Ảnh bìa</label>
                    <div class="col-sm-12">
                        <label class="text-danger hidden" style="display: block" id="error-image-poster"></label>
                        <input class="d-none" id="file" type="file" value="{{ ($story->avatar ?? $story->getFirstMediaUrl('default')) }}" name="file"
                               onchange="document.getElementById('image_url').src = window.URL.createObjectURL(this.files[0])"
                               accept=".jpg,.png,.jpeg">
                        <label for="file" class="w-100">
                            <img
                                src="{{ ($story->avatar ?? $story->getFirstMediaUrl('default')) ?? asset('frontend/images/default.png') }}"
                                style="cursor: pointer;" id="image_url"
                                width="196" height="260" alt="...">
                        </label>
                        <small style="font-size: 12px;opacity:0.5;">Chấp nhận ảnh JPG, JPEG, nặng không quá 2MB.</small>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <label class="col-md-12">Tên truyện</label>
                    <div class="col-md-12">
                        <input type="text" placeholder="Tên truyện" class="form-control" value="{{ $story->name }}"
                               name="name">
                    </div>
                </div>
                <div class="form-group mt-4">
                    <label class="col-md-12">Phân loại</label>
                    <?php
                    $types_db = $story->types ? $story->types->keyBy('id')->toArray() : [];
                    ?>
                    <select class="form-control" name="types[]" multiple="multiple">
                        @if($types)
                            @foreach($types as $type)
                                <option value="{{ $type->id }}"
                                        @if(in_array($type->id, array_keys($types_db))) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label class="col-md-12">Thể loại</label>
                    <?php
                    $categories_db = $story->categories ? $story->categories->keyBy('id')->toArray() : [];
                    ?>
                    <select class="form-control" name="categories[]" multiple="multiple">
                        @if($categories)
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(in_array($category->id, array_keys($categories_db))) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label class="col-md-12">Trạng thái</label>
                    <select class="form-control" name="status">
                        @foreach(\App\Domain\Story\Models\Story::STATUS as $key => $status)
                            <option value="{{ $key }}"
                                    @if($story->status == $key) selected @endif>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label class="col-md-12">Giới thiệu</label>
                    <div class="col-md-12">
                        <textarea class="form-control wysiwyg" rows="5" style="font-size: 12px;" name="description"
                                  placeholder="Tối đa 4000 ký tự. Tóm tắt cho truyện không nên quá dài mà nên ngắn gọn, tập trung, thú vị. Phần này rất quan trọng vì nó quyết định độc giả có đọc hay không.">{!! $story->description !!}</textarea>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <div class="form-check form-switch">
                        <button type="submit" id="add_store_btn"
                                class="btn btn-gt float-end mt-4 mb-4">Lưu lại
                        </button>
                    </div>
                </div>
            </form>
            <div class="col-md-6">
                <div class="w-80" style="float: right;">
                    <div class="btn form-control"
                         style="background-color: #fbd455!important;
                            text-align: left;
                            padding: 10px 25px;
                            color: #191919;">
                        Hướng dẫn
                    </div>
                    <div class="p-4" style="font-size: 14px;opacity:0.6;">
                        <strong>Không có ảnh bìa truyện?</strong>
                        <p>- Vào các trang này để tìm:
                            <a href="http://huaban.com" target="_blank" class="text-primary">huaban.com</a>,
                            <a href="http://duitang.com" target="_blank" class="text-primary">duitang.com</a></p>
                        <p>- Các từ khóa để tìm hình là: 小说 (tiểu thuyết), 小说封面 (bìa tiểu thuyết), 小说插图 (tranh minh họa
                            tiểu thuyết), 小说插画 (tranh minh họa tiểu thuyết), 小说封面底图 (hình nền bìa tiểu thuyết), 言情 hoặc
                            言情小说 (ngôn tình), 玄幻小说 (huyền ảo), 都市小说 (hiện đại đô thị), 武侠小说 (võ hiệp), 仙侠小说 (tiên
                            hiệp)</p>
                        <p>- Khi chọn được hình ưng ý, ấn vào biểu tượng kính lúp để tải hình chất lượng cao</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select').select2();
            $('#form-author').validate({
                errorElement: 'span',
                errorClass: 'invalid-feedback',
                lang: 'vi',
                ignore: 'input[type=hidden], .select2-search__field, input[type=checkbox]', // ignore hidden fields
                errorPlacement: function (error, element) {

                    // Unstyled checkboxes, radios
                    if (element.parents().hasClass('form-check')) {
                        error.appendTo(element.parents('.form-check').parent());
                    }

                    // Input with icons and Select2
                    else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                        error.appendTo(element.parent());
                    }

                    // Input group, styled file input
                    else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                        error.appendTo(element.parent().parent());
                    }

                    // Other elements
                    else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-valid').addClass('is-invalid'); // add the Bootstrap error class to the control group
                },
                ignore: "[contenteditable='true']",
                unhighlight: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
                },
                success: function (element) {
                    $(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid'); // remove the Boostrap error class from the control group
                },
                onkeyup: function (element) {
                    $(element).valid()
                },
                focusInvalid: true,
                rules: {
                    name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    "types[]": {
                        required: true
                    },
                    "categories[]": {
                        required: true
                    },
                },
                messages: {
                    file: {
                        required: 'Hãy chọn một ảnh'
                    },
                    name: {
                        required: 'Hãy nhập tên truyện'
                    },
                    description: {
                        required: 'Hãy nhập mô tả truyện'
                    },
                    "types[]": {
                        required: "Hãy chọn ít nhất một phân loại"
                    },
                    "categories[]": {
                        required: "Hãy chọn ít nhất một thể loại"
                    },
                }
            });
            $('select').on('change', function () {
                $(this).closest('div.form-group').find('span.invalid-feedback').remove()
            })

        });
    </script>
@endsection


