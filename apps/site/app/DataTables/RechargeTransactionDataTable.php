<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Recharge\Models\RechargeTransaction;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
class RechargeTransactionDataTable extends BaseDatable
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
            ->editColumn('user_id', fn (RechargeTransaction $recharge) => User::find($recharge->user_id)->name.'('.User::find($recharge->user_id)->username.')')
            ->editColumn('status', 'admin.recharge_transactions._tableStatus')
            ->editColumn('code', fn (RechargeTransaction $recharge) => $recharge->code)
            ->editColumn('type', fn (RechargeTransaction $recharge) => ( $recharge->type == 2 ? 'Momo' : $recharge->type == 1) ? 'Thẻ cào' : 'Ngân hàng' )
            ->editColumn('vnd', fn (RechargeTransaction $recharge) => number_format((int)$recharge->vnd))
            ->editColumn('created_at', fn (RechargeTransaction $recharge) => formatDate($recharge->created_at))
            ->editColumn('updated_at', fn (RechargeTransaction $recharge) => formatDate($recharge->updated_at))
            ->rawColumns(['status']);
    }

    public function query(RechargeTransaction $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false),
            Column::make('user_id')->title(__('Người dùng')),
            Column::make('status')->title(__('Trạng thái'))->width('20%'),
            Column::make('code')->title(__('Mã giao dịch'))->width('20%'),
            Column::make('type')->title(__('Loại giao dịch'))->width('20%'),
            Column::make('vnd')->title(__('Số tiền'))->width('20%'),
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
        return 'RechargeTransaction_'.date('YmdHis');
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
