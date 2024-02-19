@extends('shop.layouts.app')
@section('title')
    {{ $chapter->name }} - @if(!empty(setting('store_name')))
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
    <meta name="title" content="{{ $chapter->name }}">
    <meta name="description" content="{{ $chapter->description }}">
    <meta name="keywords" content="{{ $chapter->name }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $chapter->name }}">
    <meta property="og:description" content="{{ $chapter->description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="Giangthe.com">
@stop
@section('content')
    <section class="container mt-4 content main-list-user">
        <div id="wizard">
            <h2 class="text-center">Sửa nội dung chương</h2>
            <div class="wizard-content">
                <form class="fade show active" method="POST" id="formCreateChapter"
                      action="{{ route('chapters.update', $chapter) }}">
                    @csrf
                    <div class="form-group mt-4">
                        <label class="col-md-12">STT</label>
                        <div class="col-md-12">
                            <input type="number" class="form-control" name="order" value="{{ $chapter->order }}" style="font-size: 14px;"
                                   id="chapterOrder">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="col-md-12">Tên chương</label>
                        <div class="col-md-12">
                            <input type="text" value="{{ $chapter->name }}" class="form-control" name="name"
                                   style="font-size: 14px;"
                                   id="chapterName">
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="col-md-12">Nội dung chương</label>
                        <div class="col-md-12" id="divAuto">
                            <div class="backdrop">
                                <div class="highlights"></div>
                            </div>
                            <textarea class="form-control wysiwyg" rows="10" id="autoMatches"
                                      placeholder="Bạn đang thêm chương cho truyện [[ ASEAN ]] Một lần tối đa đăng được 12k từ"
                                      name="content">{{ $chapter->content }}</textarea>
                        </div>
                    </div>
                    @if(currentUser()->is_vip && $chapter->story->type == 1)
                        <div class="form-group mt-4">
                            <div class="form-check form-switch">
                                <label class="col-md-12" for="text_china">Text Trung</label>
                                <input class="form-check-input" name="text_china" type="checkbox" value="1">
                            </div>
                        </div>
                    @endif
                    <div class="form-group mt-4">
                        <div class="form-check form-switch">
                            <label class="col-md-12" for="timer_checkbox">Hẹn giờ</label>
                            <input class="form-check-input" name="timer_checkbox" type="checkbox"
                                   id="timer_checkbox" value="1" @if($chapter->timer != null) checked @endif>
                        </div>
                        <div class="col-md-12" id="divAuto">
                            <div class="backdrop">
                                <div class="highlights"></div>
                            </div>
                            <input @if($chapter->timer == null) disabled @endif type="datetime-local" class="form-control" name="timer" min="{{ date('Y-m-d\Th:i') }}" value="{{ \Carbon\Carbon::parse($chapter->timer)->format('Y-m-d\Th:i') }}" style="font-size: 14px;"
                                   id="chapterTimer">
                        </div>
                    </div>
                    @if(currentUser()->is_vip && $chapter->story->type == 1)
                        <div class="form-group mt-4">
                            <label class="col-md-12">Đường dẫn tới chương (trang khác)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control"value="{{ $chapter->link_other }}"  name="link_other" style="font-size: 14px;"
                                       id="link_other">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="is_vip" type="checkbox"
                                       id="is_vip" value="1" @if($chapter->is_vip) checked @endif>
                                <label class="form-check-label" for="is_vip">Chương vip</label>
                            </div>
                        </div>
                        <div class="form-group mt-4 @if(!$chapter->is_vip) d-none @endif price">
                            <label class="col-md-12">Giá</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control" value="{{ $chapter->price }}" name="price" style="font-size: 14px;"
                                       id="price">
                            </div>
                        </div>
                    @endif
                    <div class="form-group mt-4" style="margin-bottom: 5em; text-align: right;">
                        <button type="submit" class="btn btn-gt">Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#is_vip').change(function () {
                if ($(this).is(":checked")) {
                    $('.price').removeClass('d-none')
                } else {
                    $('.price').addClass('d-none')
                }
            })
            $('#timer_checkbox').change(function () {
                if ($(this).is(":checked")) {
                    $('#chapterTimer').prop('disabled', false);
                } else {
                    $('#chapterTimer').prop('disabled', true);
                }
            })
            $('#formCreateChapter').validate({
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
                    order: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: 'Hãy nhập tên chương'
                    },
                    order: {
                        required: 'Hãy nhập số thứ tự chương'
                    },
                    content: {
                        required: "Hãy nhập nội dung của chương"
                    },
                }
            });
        });
    </script>
@endsection


