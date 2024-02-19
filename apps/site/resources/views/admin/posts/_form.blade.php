<form action="{{ $url }}" method="POST" data-block enctype="multipart/form-data" id="post-form">
    @csrf
    @method($method ?? 'POST')

    <div class="d-flex align-items-start flex-column flex-md-row">

        <!-- Left content -->
        <div class="w-100 order-2 order-md-1 left-content">
            <div class="row">
                <div class="col-md-12">
                    <x-card>
                        <fieldset>
                            <legend class="font-weight-semibold text-uppercase font-size-sm">
                                {{ __('Chung') }}
                                @if($post->id)
                                    | <a href="{{ $post->url() }}" class="text-primary font-weight-semibold"
                                         target="_blank">{{ Str::limit($post->title, 20) }}</a>
                                @endif
                            </legend>
                            <div class="collapse show" id="general">
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-lg-2 col-form-label text-lg-right"><span class="text-danger">*</span> {{ __('Ảnh') }}:</label>--}}
{{--                                    <div class="col-lg-9">--}}
{{--                                        <div id="thumbnail">--}}
{{--                                            <div class="single-image">--}}
{{--                                                <div class="image-holder" onclick="document.getElementById('image').click();">--}}
{{--                                                    <img id="image_url" width="170" height="170" src="{{ $post->getFirstMediaUrl('image') ?? '/backend/global_assets/images/placeholders/placeholder.jpg'}}" />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <input type="file" name="image" id="image"--}}
{{--                                               accept="image/*"--}}
{{--                                               class="form-control inputfile hide"--}}
{{--                                               onchange="document.getElementById('image_url').src = window.URL.createObjectURL(this.files[0])">--}}
{{--                                        @error('image')--}}
{{--                                        <span class="form-text text-danger">--}}
{{--                                                    {{ $message }}--}}
{{--                                                </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <x-text-field
                                    name="title"
                                    :placeholder="__('Tiêu đề ')"
                                    :label="__('Tiêu đề')"
                                    :value="$post->title"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="description"
                                    :placeholder="__('Mô tả')"
                                    :label="__('Mô tả')"
                                    :value="$post->description"
                                    required
                                >
                                    {!! $post->description ?? null !!}
                                </x-text-field>

{{--                                <div class="form-group row">--}}
{{--                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">--}}
{{--                                        <span class="text-danger">*</span> {{ __('Danh mục') }}--}}
{{--                                    </label>--}}
{{--                                    <div class="col-lg-9" id="select2">--}}
{{--                                        <select name="category[]" id="category" class="form-control select2" data-width="100%"--}}
{{--                                                multiple>--}}
{{--                                            <option value="">--}}
{{--                                                {{ __('Chọn danh mục') }}--}}
{{--                                            </option>--}}
{{--                                            @foreach($taxons as $taxon)--}}
{{--                                                <option value="{{ $taxon->id }}"--}}
{{--                                                        @if(in_array($taxon->id, $post->taxons->pluck('id')->toArray())) selected @endif>--}}
{{--                                                    {{ $taxon->selectText() }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        <div class="clearfix"></div>--}}
{{--                                        @error('category')--}}
{{--                                            <span class="form-text text-danger">--}}
{{--                                                {{ $message }}--}}
{{--                                            </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <x-textarea-field
                                    name="body"
                                    :placeholder="__('Nội dung')"
                                    :label="__('Nội dung')"
                                    :value="$post->body"
                                    class="wysiwyg"
                                    required
                                >
                                </x-textarea-field>

{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-lg-2 col-form-label text-lg-right col-form-label">--}}
{{--                                        {{ __('Bài viết liên quan:') }}--}}
{{--                                    </label>--}}
{{--                                    <div class="col-lg-9">--}}
{{--                                        <select id="related_posts" name="related_posts[]" class="form-control select2"--}}
{{--                                                multiple>--}}
{{--                                            @foreach($relatedPosts as $relatedPost)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $relatedPost->id }}" {{ \in_array($relatedPost->id, $selectedRelatePost) ? 'selected' : null }}>--}}
{{--                                                    {{ $relatedPost->title }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Trạng thái') }}:
                                    </label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="status">
                                            <option
                                                value="{{ \App\Enums\PostState::Pending }}" {{ $post->status == \App\Enums\PostState::Pending ? 'selected' : '' }}>{{ __('Chờ phê duyệt') }}</option>
                                            <option
                                                value="{{ \App\Enums\PostState::Active }}" {{ $post->status == \App\Enums\PostState::Active ? 'selected' : '' }}>{{ __('Hoạt động') }}</option>
                                            <option
                                                value="{{ \App\Enums\PostState::Disabled }}" {{ $post->status == \App\Enums\PostState::Disabled ? 'selected' : '' }} >{{ __('Hủy') }}</option>
                                        </select>
                                        @error('status')
                                            <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <legend class="font-weight-semibold text-uppercase font-size-sm">
                                {{ __('SEO') }}
                            </legend>
                            <div class="collapse show" id="seo">
                                <x-text-field
                                    name="meta_title"
                                    :label="__('Tiêu đề')"
                                    type="text"
                                    :value="$post->meta_title"
                                    :placeholder="__('Tiêu đề nên nhập từ 10 đến 70 ký tự trở lên')"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="meta_description"
                                    :label="__('Mô tả')"
                                    type="text"
                                    :value="$post->meta_description"
                                    :placeholder="__('Mô tả nên nhập từ 160 đến 255 ký tự trở lên')"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="meta_keywords"
                                    :label="__('Từ khóa')"
                                    type="text"
                                    :value="$post->meta_keywords"
                                    :placeholder="__('Từ khóa nên nhập 12 ký tự trong 1 từ khóa, cách nhau bằng dấu \',\'')"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="slug"
                                    :label="__('Đường dẫn')"
                                    type="text"
                                    :value="$post->slug"
                                    :placeholder="__('Đường dẫn sẽ hiển thị trên URL của trang web')"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right">
                                        Đường dẫn thực tế:
                                    </label>
                                    <div class="col-lg-9">
                                        <div style="line-height: 36px;">
                                            <span id="slug-value" class="text-primary">
                                                @if($post->id)
                                                    {{route('post.show', $post->slug)}}
                                                @else
                                                    {{route('post.show')}}/
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-light">{{ __('Trở lại') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i
                                    class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.posts.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.posts.create') }}">{{ __('Lưu & Thêm mới') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /left content -->
    </div>
</form>
@push('js')
    <script>
        $(document).on('keyup', '#slug', function () {
            let slug = $('#slug').val()
            let fullLink = '{{route('post.show')}}' +'/'+ slug;
            $('#slug-value').html(fullLink);
        })
    </script>
@endpush()
