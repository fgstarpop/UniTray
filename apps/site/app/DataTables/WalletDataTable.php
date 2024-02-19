<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Admin\Models\Wallet;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class WalletDataTable extends BaseDatable
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
            ->editColumn('created_at', fn (Wallet $wallet) => formatDate($wallet->created_at))
            ->editColumn('updated_at', fn (Wallet $wallet) => formatDate($wallet->updated_at))
            ->addColumn('user_name', fn (Wallet $wallet) => $wallet->user->name)
            ->editColumn('vnd', fn (Wallet $wallet) => formatNumber($wallet->vnd))
            ->editColumn('silver', fn (Wallet $wallet) => formatNumber($wallet->silver))
            ->editColumn('gold', fn (Wallet $wallet) => formatNumber($wallet->gold))
            ->addColumn('action',fn (Wallet $wallet) =>  view('admin.wallets._tableAction', compact('wallet')) )

            ->rawColumns(['action']);
    }

    public function query(Wallet $model): Builder
    {
        return $model->newQuery()->with('user');
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false),
            Column::make('user_name')->name('user.name')->title(__('Người dùng')),
            Column::make('gold')->title(__('Vàng')),
            Column::make('silver')->title(__('Tệ')),
            Column::make('vnd')->title(__('Tiền')),
            Column::make('created_at')->title(__('Tạo lúc'))->searchable(false),
            Column::make('updated_at')->title(__('Cập nhật lúc'))->searchable(false),
            Column::computed('action')
                ->title(__('Tác vụ'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
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
