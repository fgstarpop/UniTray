<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Admin\Models\WithdrawTransaction;
use App\Domain\Recharge\Models\RechargeTransaction;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
class WalletTransactionDataTable extends BaseDatable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', fn (WalletTransaction $wallet) => formatDate($wallet->created_at))
            ->addColumn('user_name', fn (WalletTransaction $wallet) => $wallet->user->name)
            ->editColumn('transaction_id', fn (WalletTransaction $wallet) =>  $wallet->transaction_id)
            ->editColumn('change_type', fn (WalletTransaction $wallet) => $wallet->change_type ? 'Giảm' : 'Tăng')
            ->editColumn('transaction_type', fn (WalletTransaction $wallet) => $wallet->transaction_type ? 'Rút tiền'  :  'Nạp tiền')
            ->editColumn('gold', fn (WalletTransaction $wallet) => formatNumber((int)$wallet->gold))
            ->editColumn('yuan', fn (WalletTransaction $wallet) => formatNumber((int)$wallet->yuan))
            ->editColumn('balance', fn (WalletTransaction $wallet) => 'Vàng: '.formatNumber((int)$wallet->gold_balance).' Tệ :'.formatNumber((int)$wallet->yuan_balance))
            ->addColumn('action', 'admin.wallet-transaction._tableAction')
            ->rawColumns(['action']);
    }

    public function query(WalletTransaction $model): Builder
    {
        return $model->newQuery()->with('user');
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false),
            Column::make('user_name')->name('user.name')->title(__('Người dùng')),
            Column::make('transaction_id')->title(__('Mã giao dịch')),
            Column::make('gold')->title(__('Vàng')),
            Column::make('yuan')->title(__('Tiền')),
            Column::make('change_type')->title(__('Trạng thái')),
            Column::make('transaction_type')->title(__('Loại giao dịch')),
            Column::make('balance')->title(__('Số dư'))->searchable(false),
            Column::make('created_at')->title(__('Thời gian'))->searchable(false),
            Column::make('action')->title(__('Tác vụ'))->searchable(false),
        ];
    }

    protected function getBuilderParameters(): array
    {
        return [
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Wallet_'.date('YmdHis');
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('export')->addClass('btn btn-success')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
            Button::make('reset')->addClass('btn bg-primary')->text('<i class="fal fa-undo mr-2"></i>'.__('Tải lại')),
        ];
    }
}
