<div class="list-icons">
    @can('wallet-transaction.update')
    @if(\App\Domain\Admin\Models\WalletTransaction::find($id)->undo == 0)
        <a href="javascript:;"  data-url="{{ route('admin.wallet-transaction.undo', $id) }}" class="item-action btn-primary undo" title="{{ __('Hoàn lại') }}"><i class="far fa-undo"></i></a>
     @else
     <p>Đã hoàn tác</p>
     @endif
    @endcan
</div>
