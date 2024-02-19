<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class UserDataTable extends BaseDatable
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
            ->editColumn('id', fn (User $user) => $user->id)
            ->editColumn('username', fn (User $user) => $user->username)
            ->editColumn('email', fn (User $user) => $user->email)
            ->editColumn('is_vip', 'admin.users._tableType')
            ->editColumn('status', 'admin.users._tableStatus')
            ->editColumn('created_at', fn (User $user) => formatDate($user->created_at))
            ->editColumn('updated_at', fn (User $user) => formatDate($user->updated_at))
            ->editColumn('name', fn (User $user) => $user->name)
            ->rawColumns(['action', 'status', 'is_vip']);
    }

    public function query(User $model): Builder
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('ID'))->data('DT_RowIndex')->searchable(false),
            Column::make('username')->title(__('Tên đăng nhập')),
            Column::make('name')->title(__('Tên')),
            Column::make('email')->title(__('Email')),
            Column::make('is_vip')->title(__('Thuộc loại'))->width('20%'),
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
        return 'User_'.date('YmdHis');
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
            Button::make('reset')->addClass('btn bg-primary')->text('<i class="fal fa-undo mr-2"></i>'.__('Tải lại')),
            Button::make('selected')->addClass('btn btn-danger')
                ->text('<i class="fal fa-tasks mr-2"></i>'.__('Cập nhật tài khoản'))
                ->action("
                    var selectedRow = dt.rows( { selected: true } ).data();
                    var selectedId = [];
                    for (var i=0; i < selectedRow.length ;i++){
                        selectedId.push(selectedRow[i].id);
                    }

                    var bulkUrl = window.location.href.replace(/\/+$/, \"\") + '/bulk-type';

                    bootbox.dialog({
                    title: 'Cập nhật tài khoản',
                    message: '<div class=\"row\">  ' +
                        '<div class=\"col-md-12\">' +
                            '<form action=\"\">' +
                                '<div class=\"form-group row\">' +
                                    '<label class=\"col-md-3 col-form-label\">Loại tài khoản</label>' +
                                    '<div class=\"col-md-9\">' +
                                        '<select class=\"form-control\" id=\"change-type\">' +
			                                '<option value=\"0\">Bình thường</option>' +
			                                '<option value=\"1\">Người kiểm duyệt</option>' +
			                            '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</form>' +
                        '</div>' +
                    '</div>',
                    buttons: {
                        success: {
                            label: 'Lưu',
                            className: 'btn-success',
                            callback: function () {
                                var type = $('#change-type').val();
                                $.ajax({
                                    type: 'POST',
                                    data: {
                                        id: selectedId,
                                        type: type
                                    },
                                    url: bulkUrl,
                                    success: function (res) {
                                        dt.ajax.reload()
                                        if(res.status == true){
                                            showMessage('success', res.message);
                                        }else{
                                            showMessage('error', res.message);
                                        }
                                    },
                                })
                            }
                        }
                    }
                }
            );"),
            Button::make('selected')->addClass('btn btn-warning')
                ->text('<i class="fal fa-tasks mr-2"></i>'.__('Cập nhật trạng thái'))
                ->action("
                    var selectedRow = dt.rows( { selected: true } ).data();
                    var selectedId = [];
                    for (var i=0; i < selectedRow.length ;i++){
                        selectedId.push(selectedRow[i].id);
                    }

                    var bulkUrl = window.location.href.replace(/\/+$/, \"\") + '/bulk-status';

                    bootbox.dialog({
                    title: 'Cập nhật trạng thái',
                    message: '<div class=\"row\">  ' +
                        '<div class=\"col-md-12\">' +
                            '<form action=\"\">' +
                                '<div class=\"form-group row\">' +
                                    '<label class=\"col-md-3 col-form-label\">Trạng thái</label>' +
                                    '<div class=\"col-md-9\">' +
                                        '<select class=\"form-control\" id=\"change-state\">' +
			                                '<option value=\"0\">Khóa tài khoản</option>' +
			                                '<option value=\"1\">Hoạt động</option>' +
			                            '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</form>' +
                        '</div>' +
                    '</div>',
                    buttons: {
                        success: {
                            label: 'Lưu',
                            className: 'btn-success',
                            callback: function () {
                                var status = $('#change-state').val();
                                $.ajax({
                                    type: 'POST',
                                    data: {
                                        id: selectedId,
                                        status: status
                                    },
                                    url: bulkUrl,
                                    success: function (res) {
                                        dt.ajax.reload()
                                        if(res.status == true){
                                            showMessage('success', res.message);
                                        }else{
                                            showMessage('error', res.message);
                                        }
                                    },
                                })
                            }
                        }
                    }
                }
            );"),
        ];
    }
}
