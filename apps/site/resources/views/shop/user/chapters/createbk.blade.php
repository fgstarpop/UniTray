@extends('shop.layouts.app')
@section('title')
    @if(!empty(setting('store_name')))
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
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('frontend/img/logo/logo.png') }}">
    <meta property="og:site_name" content="Giangthe.com">
@stop
@section('content')
    <section class="container mt-4 content main-list-user">
        <div id="wizard">
            <h2 class="text-center">Thêm chương mới</h2>
            <div class="wizard-content">
                <form class="fade show active" method="POST" id="formCreateChapter"
                    data-chapter-item="{{ $chapterItem }}"
                    data-order="{{ $order }}"
                      action="{{ route('chapters.store') }}">
                    <input type="hidden" name="story_id" value="{{ request('story') ? request('story')->id : 0}}">
                    @csrf
                    <div class="error"></div>
                    <div id="chapters">
                        <div class="chapter">
                            <div class="form-group mt-4">
                                <label class="col-md-12">STT</label>
                                <div class="col-md-12">
                                    <input type="number" class="form-control order" name="chapters[0][order]" style="font-size: 14px;" value="{{ $order }}"
                                        id="chapterOrder">
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label class="col-md-12">Tên chương</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control name" name="chapters[0][name]" style="font-size: 14px;"
                                        id="chapterName">
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label class="col-md-12">Nội dung chương</label>
                                <div class="col-md-12" id="divAuto">
                                    <div class="backdrop">
                                        <div class="highlights"></div>
                                    </div>
                                    <textarea class="form-control wysiwyg content" rows="10" id="autoMatches"
                                            placeholder="Bạn đang thêm chương cho truyện [[ {{ $story->name }} ]]. Một lần tối đa đăng được 12k từ"
                                            name="chapters[0][content]"></textarea>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <div class="form-check form-switch">
                                <label class="col-md-12" for="text_china_mul">Đăng nhiều chương</label>
                                    <input class="form-check-input" name="chapters[0][text_china_mul]" type="checkbox" value="1">
                                </div>
                            </div>
                            @if(currentUser()->is_vip && $story->type == 1)
                            <div class="form-group mt-4">
                                <div class="form-check form-switch">
                                    <label class="col-md-12" for="text_china">Text Trung</label>
                                    <input class="form-check-input" name="chapters[0][text_china]" type="checkbox" checked value="1">
                                </div>
                            </div>
                            @endif
                            <div class="form-group mt-4">
                                <div class="form-check form-switch">
                                    <label class="col-md-12" for="timer_checkbox">Hẹn giờ</label>
                                    <input class="form-check-input" name="chapters[0][timer_checkbox]" type="checkbox"
                                        id="timer_checkbox" value="1">
                                </div>
                                <div class="col-md-12" id="divAuto">
                                    <div class="backdrop">
                                        <div class="highlights"></div>
                                    </div>
                                    <input type="datetime-local" disabled class="form-control timer" name="chapters[0][timer]" min="{{ date('Y-m-d\Th:i') }}" style="font-size: 14px;"
                                        id="chapterTimer">
                                </div>
                            </div>
                        </div>

                    @if(currentUser()->is_vip && $story->type == 1)
                        <div class="form-group mt-4">
                            <label class="col-md-12">Đường dẫn tới chương (trang khác)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="chapters[0][link_other]" style="font-size: 14px;"
                                       id="link_other">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="chapters[0][is_vip]" type="checkbox"
                                       id="is_vip" value="1">
                                <label class="form-check-label" for="is_vip">Chương vip</label>
                            </div>
                        </div>
                        <div class="form-group mt-4 d-none price">
                            <label class="col-md-12">Giá</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control"  name="chapters[0][price]" style="font-size: 14px;"
                                       id="price">
                            </div>
                        </div>
{{--                        <div class="form-group mt-4">--}}
{{--                            <div class="form-check form-switch">--}}
{{--                                <input class="form-check-input" type="checkbox"--}}
{{--                                       id="flexSwitchCheckDefault" value="1">--}}
{{--                                <label class="form-check-label" for="flexSwitchCheckDefault">Nhúng chương</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group mt-4">--}}
{{--                            <label class="col-md-12">Đường dẫn chương nhúng</label>--}}
{{--                            <div class="col-md-12">--}}
{{--                                <input type="text" class="form-control" name="link_embed" style="font-size: 14px;"--}}
{{--                                       id="link_embed" placeholder="URL chương truyện trung quốc" disabled>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    @endif
                    <hr class="my-4">
                    </div>
                    <div class="form-group mt-4" style="margin-bottom: 5em; text-align: right;">
                        <button id="add-new" type="button" class="btn btn-sm btn-primary">Thêm chương</button>
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
        let isRTL = $('body').hasClass('rtl');
        let direction = (isRTL) ? 'rtl' : 'ltr';
        let langURl = `${Config.baseUrl}/backend/js/vi_VN.js`;
        function initTinymce(selector){
            tinyMCE.init({
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '/uploads-tinymce');

                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };

                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', window.Config.csrf)

                    xhr.send(formData);
                },
                automatic_uploads: true,
                paste_data_images: true,
                selector: selector,
                theme: 'modern',
                mobile: { theme: 'mobile' },
                height: 300,
                menubar: false,
                branding: false,
                image_advtab: true,
                image_title: true,
                relative_urls: false,
                directionality: direction,
                cache_suffix: `?v=1`,
                plugins: 'lists, link, table, paste, autosave, autolink, wordcount, code, image, fullscreen, preview',
                toolbar: 'styleselect bold italic underline | bullist numlist | alignleft aligncenter alignright | outdent indent | link table code image fullscreen preview',
                language_url : langURl
            });
        }

        $(document).ready(function () {
            $('#add-new').on('click', function() {
                let chapter = $('#formCreateChapter').data('chapter-item');
                let order = $('#formCreateChapter').data('order');
                order = order + 1;
                $('#formCreateChapter').data('order', order);
                chapter = chapter.replaceAll('{INDEX}', order);
                $('#chapters').append(chapter);
                $(`input[name="chapters[${order}][is_vip]"]`).change(function () {
                    if ($(this).is(":checked")) {
                        $(`input[name="chapters[${order}][price]"]`)
                            .closest('.form-group')
                            .removeClass('d-none');
                    } else {
                        $(`input[name="chapters[${order}][price]"]`)
                            .closest('.form-group')
                            .addClass('d-none');
                    }
                });

                $(`input[name="chapters[${order}][timer_checkbox]"]`).change(function () {
                    if ($(this).is(":checked")) {
                        $(`input[name="chapters[${order}][timer]"]`).prop('disabled', false);
                    } else {
                        $(`input[name="chapters[${order}][timer]"]`).prop('disabled', true);
                    }
                });

                if ($('textarea[name="chapters[${order}][content]"]')) {
                    initTinymce(`textarea[name="chapters[${order}][content]"]`);
                }

                if ($('.chapter').length == 20) {
                    $(this).prop('disabled', true);
                    return;
                }
            });

            $('input[name="chapters[0][text_china_mul]"]').on('change', function(e) {
                if ($(this).is(':checked') ) {
                    $('#add-new').hide();
                    $('#autoMatches').hide();
                    $('#divAuto').html('<textarea class="form-control" id="listtext" name="chapters[0][content]" rows="12"></textarea>');
                } else {
                    $('#add-new').show();
                    initTinymce('textarea[name="chapters[0][content]"]');
                }
            });

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
            });

            $.validator.addClassRules({
                name: {
                    required: true
                },
                order: {
                    required: true
                },
                content: {
                    required: true
                },
                timer: {
                    required: true
                }
            });

            $.validator.messages.required = 'Trường này là bắt buộc.';
            $.validator.messages.min = $.validator.format('Vui lòng nhập giá trị lớn hơn hoặc bằng {0}.');

            $('#flexSwitchCheckDefault').change(function () {
                if ($(this).is(":checked")) {
                    $('#link_embed').prop('disabled', false)
                    $('input[name=name]').prop('disabled', true)
                    $('textarea[name=order]').prop('disabled', true)
                    $('textarea[name=content]').prop('disabled', true)
                } else {
                    $('#link_embed').prop('disabled', true)
                    $('input[name=name]').prop('disabled', false)
                    $('textarea[name=order]').prop('disabled', false)
                    $('textarea[name=content]').prop('disabled', false)
                }
            })

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
        });
    </script>
@endsection


