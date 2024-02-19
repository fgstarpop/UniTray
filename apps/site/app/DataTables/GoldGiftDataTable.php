<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
use App\Domain\Admin\Models\GoldGift;
class GoldGiftDataTable extends BaseDatable
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
            ->editColumn('user_id', fn (GoldGift $gift) => User::find($gift->user_id)->name)
            ->editColumn('gold', fn (GoldGift $gift) => formatNumber((int)$gift->gold))
            ->editColumn('received_id', fn (GoldGift $gift) => User::find($gift->received_id)->name)
            ->editColumn('created_at', fn (GoldGift $gift) => formatDate($gift->created_at));
    }

    public function query(GoldGift $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false), 
            Column::make('user_id')->title(__('Người dùng')),
            Column::make('gold')->title(__('Vàng')),
            Column::make('received_id')->title(__('Người nhận'))->width('20%'),
            Column::make('created_at')->title(__('Thởi gian'))->searchable(false),
        ];
    }

    protected function getBuilderParameters(): array
    {
        return [
            'gift' => [1, 'desc'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'GoldGift_'.date('YmdHis');
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
