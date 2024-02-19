<div class="list-icons">
    @can('recharge_packages.update')
        <a href="{{ route('admin.recharge-packages.edit', $id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
    @can('recharge_packages.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.recharge-packages.destroy', $id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan
</div>
