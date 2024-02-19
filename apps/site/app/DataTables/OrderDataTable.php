<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Admin\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
use App\Domain\Chapter\Models\Chapter;
class OrderDataTable extends BaseDatable
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
            ->editColumn('user_id', fn (Order $order) => User::find($order->user_id)->name);
            // ->editColumn('chapter_id', fn (Order $order) =>  Chapter::find($order->chapter_id)->name."(Giá chương: ".Chapter::find($order->chapter_id)->price." vàng )")
            // ->editColumn('number', fn (Order $order) => $order->number)
            // ->editColumn('total', fn (Order $order) => formatNumber($order->total))
            // ->editColumn('created_at', fn (Order $order) => formatDate($order->created_at));
    }

    public function query(Order $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('Thứ tự'))->data('DT_RowIndex')->searchable(false),
            Column::make('number')->title(__('Mã giao dịch')),
            Column::make('user_id')->title(__('Người dùng')),
            Column::make('chapter_id')->title(__('Chương truyện')),
            Column::make('total')->title(__('Tổng tiền'))->width('20%'),
            Column::make('created_at')->title(__('Thời gian'))->searchable(false),
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
        return 'Order_'.date('YmdHis');
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
