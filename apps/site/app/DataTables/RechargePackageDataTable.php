<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Domain\Recharge\Models\RechargePackage;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class RechargePackageDataTable extends BaseDatable
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
            ->addColumn('name', fn (RechargePackage $rechargePackage) => view('admin.recharge_packages._tableTitle', compact('rechargePackage')))
            ->editColumn('status', 'admin.recharge_packages._tableStatus')
            ->editColumn('vnd', fn (RechargePackage $rechargePackage) => formatNumber($rechargePackage->vnd))
            ->editColumn('gold', fn (RechargePackage $rechargePackage) => formatNumber($rechargePackage->gold))
            ->editColumn('created_at', fn (RechargePackage $rechargePackage) => formatDate($rechargePackage->created_at))
            ->editColumn('updated_at', fn (RechargePackage $rechargePackage) => formatDate($rechargePackage->updated_at))
            ->addColumn('action', 'admin.recharge_packages._tableAction')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orderColumn('name', 'name $1')
            ->rawColumns(['action', 'status']);
    }

    public function query(RechargePackage $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('STT'))->data('DT_RowIndex')->searchable(false),
            Column::make('name')->title(__('Tiêu đề'))->width('18%'),
            Column::make('vnd')->title(__('Tiền'))->width('18%'),
            Column::make('gold')->title(__('Vàng'))->width('18%'),
            Column::make('status')->title(__('Trạng thái'))->width('20%'),
            Column::make('created_at')->title(__('Thời gian tạo'))->searchable(false),
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
        return 'RechargePackage_'.date('YmdHis');
    }

    protected function getTableButton(): array
    {
        return [ 
            Button::make('create')->addClass('btn btn-success d-none')->text('<i class="fal fa-plus-circle mr-2"></i>'.__('Tạo mới')),
            Button::make('bulkDelete')->addClass('btn bg-danger d-none')->text('<i class="fal fa-trash-alt mr-2"></i>'.__('Xóa')),
            Button::make('export')->addClass('btn bg-blue')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-blue')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
            Button::make('reset')->addClass('btn bg-blue')->text('<i class="fal fa-undo mr-2"></i>'.__('Tải lại')),
        ];
    }
    public function printPreview()
    {
        $this->request()->merge(['length' => -1]);
        $source = app()->call([$this, 'query']);
        $source = $this->applyScopes($source);
        $data = $source->get();
        return view('admin.recharge_packages.print', compact('data'));
    }
}
