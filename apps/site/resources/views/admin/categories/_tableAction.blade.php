<div class="list-icons">
    @can('categories.update')
        <a href="{{ route('admin.categories.edit', $id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
    @can('categories.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.categories.destroy', $id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan
</div>
