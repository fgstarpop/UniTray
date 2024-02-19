<div class="list-icons">
    @can('wallets.update')
        <a href="{{ route('admin.wallets.edit', $wallet->id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
</div>
