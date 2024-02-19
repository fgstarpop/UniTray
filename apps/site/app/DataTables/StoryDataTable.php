<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Story\Models\Story;
use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\User;
class StoryDataTable extends BaseDatable
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
            ->addColumn('name', fn (Story $story) => view('admin.stories._tableTitle', compact('story')))
            ->editColumn('user_id', fn (Story $story) => $story->user->name ?? 'Không có')
            ->editColumn('type', fn (Story $story) => $story->type ? 'Truyện nhúng' : 'Truyện viết')
            ->editColumn('is_vip',  fn (Story $story) => $story->is_vip ? 'VIP' : 'Truyện thường')
            ->editColumn('status', 'admin.stories._tableStatus')
            ->editColumn('created_at', fn (Story $story) => formatDate($story->created_at))
            ->editColumn('updated_at', fn (Story $story) => formatDate($story->updated_at))
            ->addColumn('action', 'admin.stories._tableAction')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orderColumn('name', 'name $1')
            ->rawColumns(['action', 'status']);
    }

    public function query(Story $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('STT'))->data('DT_RowIndex')->searchable(false),
            Column::make('name')->title(__('Tên'))->width('20%'),
            Column::make('user_id')->title(__('Người dùng'))->width('20%'),
            Column::make('type')->title(__('Thể loại'))->width('20%'),
            Column::make('is_vip')->title(__('VIP'))->width('20%'),
            Column::make('status')->title(__('Trạng thái'))->width('20%'),
            Column::make('created_at')->title(__('Thời gian tạo'))->searchable(false),
            Column::make('created_at')->title(__('Thời gian cập nhật'))->searchable(false),
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
        return 'Story_'.date('YmdHis');
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('bulkDelete')->addClass('btn bg-danger d-none')->text('<i class="fal fa-trash-alt mr-2"></i>'.__('Xóa')),
            Button::make('export')->addClass('btn bg-blue')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-blue')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
            Button::make('reset')->addClass('btn bg-blue')->text('<i class="fal fa-undo mr-2"></i>'.__('Tải lại')),
        ];
    }
}
