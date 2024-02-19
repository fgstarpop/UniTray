<form action="{{ $url }}" method="POST" data-block id="story">
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
                                {{ __('Truyện') }} 
                                @if($story->id)
                                    | <a href="{{ $story->url() }}" class="text-primary font-weight-semibold"
                                         target="_blank">{{ Str::limit($story->name, 20) }}</a>
                                @endif
                            </legend>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="name"
                                    :placeholder="__('Tên')"
                                    :label="__('Tên')"
                                    :value="$story->name"
                                    required
                                >
                                </x-text-field>
                                <x-textarea-field
                                        name="description"
                                        :placeholder="__('Mô tả')"
                                        :label="__('Mô tả')"
                                        value="$story->description"
                                        required
                                        class="form-control wysiwyg"
                                    >
                                    </x-textarea-field>
                                    @if(count($relatedStory))
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label text-lg-right" for="group">
                                            Truyện cha:
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="origin" id="origin" class="form-control">
                                                @foreach($relatedStory as $p)
                                                    <option value="{{ $p->id }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label text-lg-right" for="group">
                                            Truyện liên quan :
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="related_stories[]" id="related_stories" multiple="multiple"
                                                    class="form-control">
                                                @foreach($relatedStory as $p)
                                                    <option value="{{ $p->id }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label text-lg-right" for="group">
                                            <span class="text-danger">*</span>
                                            Danh mục :
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="categories_id" id="category" class="form-control " required>
                                                <option value="">
                                                    Chọn danh mục
                                                </option>
                                                @foreach($category as $p)
                                                    <option value="{{ $p->id }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('categories_id')
                                            <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label text-lg-right" for="group">
                                            <span class="text-danger">*</span>
                                            Loại truyện :
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="tags_id" id="types" class="form-control " required>
                                                <option value="">
                                                    Chọn loại truyện
                                                </option>
                                                @foreach($type as $p)
                                                    <option value="{{ $p->id }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('categories_id')
                                            <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label text-lg-right" for="group">
                                            <span class="text-danger">*</span>
                                            Thể loại :
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="type" id="type" class="form-control " required>
                                                <option value="">
                                                    Chọn thể loại
                                                </option>
                                                <option value="0">
                                                    Truyện viết
                                                </option>
                                                <option value="1">
                                                    Truyện nhúng
                                                </option>
                                            </select>
                                            @error('type')
                                            <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <x-check-field
                                    name="is_vip"
                                    :label="__('Chương VIP')"
                                    :value="$story->is_vip"
                                    required
                                >
                                </x-check-field>
                                <x-check-field
                                    name="status"
                                    :label="__('Trạng thái')"
                                    :value="$story->status"
                                    required
                                >
                                </x-check-field>
                            </div>
                        </fieldset>
                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.stories.index') }}" class="btn btn-light">{{ __('Hủy') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-story" data-redirect="{{ route('admin.categories.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-story" data-redirect="{{ route('admin.categories.create') }}">{{ __('Lưu & Thêm mới') }}</a>
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
    $('#related_stories').select2();
    $('#category').select2();
    $('#types').select2();
    $('#origin').select2();

</script>
@endpush()
