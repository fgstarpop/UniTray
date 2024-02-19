<div class="list-icons">
    @can('types.update')
        <a href="{{ route('admin.types.edit', $id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
    @can('types.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.types.destroy', $id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan
</div>
