<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Admin\Models\Win;

use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
use Carbon\Carbon;

class WinDataTable extends BaseDatable
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
            ->editColumn('number', fn (Win $win) => $win->number)
            ->editColumn('winner', fn (Win $win) => User::find($win->winner)->name) 
            ->editColumn('gold', fn (Win $win) => formatNumber($win->gold))
            ->editColumn('created_at', fn (Win $win) => formatDate($win->created_at));
    }

    public function query(Win $model): Builder
    { 
        $tomorrow = new Carbon('tomorrow midnight');
        $today =  new Carbon('today midnight');
        return $model->newQuery()->where('created_at','<', $tomorrow)->where('created_at','>', $today);
    }

    protected function getColumns(): array
    { 
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('STT'))->data('DT_RowIndex')->searchable(false),
            Column::make('number')->title(__('Số'))->width('20%'),
            Column::make('winner')->title(__('Người thắng'))->width('20%'),
            Column::make('gold')->title(__('Số vàng'))->width('20%'),
            Column::make('created_at')->title(__('Thời gian đặt'))->searchable(false),
             
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
        return 'Win_'.date('YmdHis');
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('reset')->addClass('btn bg-blue')->text('<i class="fal fa-undo mr-2"></i>'.__('Tải lại')),
        ];
    }
}
