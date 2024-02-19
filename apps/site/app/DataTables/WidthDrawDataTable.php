<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Admin\Models\WithdrawTransaction;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class WidthDrawDataTable extends BaseDatable
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
            ->editColumn('user_id', fn (WithdrawTransaction $widthraw) => User::find($widthraw->user_id)->name)
            ->editColumn('silver', fn (WithdrawTransaction $widthraw) =>  number_format((int)$widthraw->silver))
            ->editColumn('silvers', fn (WithdrawTransaction $widthraw) =>  number_format((int)$widthraw->silver - $widthraw->silver * setting('withdrawal_fee',0) / 100))
            ->editColumn('money_current_wallet', fn (WithdrawTransaction $widthraw) => number_format((int)$widthraw->money_current_wallet))
            ->editColumn('code', fn (WithdrawTransaction $widthraw) => $widthraw->code)
            ->editColumn('bank', fn (WithdrawTransaction $widthraw) => $widthraw->bank)
            ->editColumn('stk', fn (WithdrawTransaction $widthraw) => $widthraw->stk)
            ->editColumn('name', fn (WithdrawTransaction $widthraw) => $widthraw->name)
            ->editColumn('status', 'admin.withdraw._tableStatus')
            ->editColumn('created_at', fn (WithdrawTransaction $widthraw) => formatDate($widthraw->created_at))
            ->editColumn('updated_at', fn (WithdrawTransaction $widthraw) => formatDate($widthraw->updated_at))
            ->rawColumns(['action', 'status', 'is_vip']);
    }

    public function query(WithdrawTransaction $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false),
            Column::make('user_id')->title(__('Người dùng')),
            Column::make('silver')->title(__('Số tiền')),
            Column::make('silvers')->title(__('Số tiền thực nhận')),
            Column::make('money_current_wallet')->title(__('Tiền hiện có'))->width('20%'),
            Column::make('code')->title(__('Mã giao dịch'))->width('20%'),
            Column::make('bank')->title(__('Ngân hàng'))->width('20%'),
            Column::make('stk')->title(__('Số tài khoản'))->width('20%'),
            Column::make('name')->title(__('Người nhận'))->width('20%'),
            Column::make('status')->title(__('Trạng thái'))->width('20%'),
            Column::make('created_at')->title(__('Tạo lúc'))->searchable(false),
            Column::make('updated_at')->title(__('Cập nhật lúc'))->searchable(false),
        ];
    }

    protected function getBuilderParameters(): array
    {
        return [
            'order' => [1, 'desc'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'WithdrawTransaction_'.date('YmdHis');
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
