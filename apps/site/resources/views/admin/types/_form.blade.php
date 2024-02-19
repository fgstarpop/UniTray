<form action="{{ $url }}" method="POST" data-block id="type">
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
                                {{ __('Thể loại') }}
                                @if($type->id)
                                    | <a href="{{ $type->url() }}" class="text-primary font-weight-semibold"
                                         target="_blank">{{ Str::limit($type->name, 20) }}</a>
                                @endif
                            </legend>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="name"
                                    :placeholder="__('Tên')"
                                    :label="__('Tên')"
                                    :value="$type->name"
                                    required
                                >
                                </x-text-field>

                                <x-check-field
                                    name="status"
                                    :label="__('Trạng thái')"
                                    :value="$type->status"
                                    required
                                >
                                </x-check-field>

                                <x-text-field
                                    name="slug"
                                    :label="__('Đường dẫn')"
                                    type="text"
                                    :value="$type->slug"
                                    :placeholder="__('Đường dẫn sẽ hiển thị trên URL của trang web')"
                                >
                                </x-text-field>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right">
                                        {{ __('Đường dẫn thực tế') }}:
                                    </label>
                                    <div class="col-lg-9">
                                        <div style="line-height: 36px;">
                                        <span id="slug-value" class="text-primary">
                                         @if($type->id)
                                                {{route('type.show', $type->slug)}}
                                            @else
                                                {{route('type.show')}}/
                                            @endif
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">{{ __('Hủy') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.categories.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.categories.create') }}">{{ __('Lưu & Thêm mới') }}</a>
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
{!! JsValidator::formRequest('App\Http\Requests\Admin\TypeRequest', '#type'); !!}
<script>
    $(document).on('keyup', '#slug', function () {
        let slug = $('#slug').val()
        let fullLink = '{{route('type.show')}}' +'/'+ slug;
        $('#slug-value').html(fullLink);
    })
</script>
@endpush()
