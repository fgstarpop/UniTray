@extends('admin.layouts.master')

@section('title', __('Giao dịch tặng vàng'))
@section('page-header')
    <x-page-header>
        <x-slot name='title'>
            <h4><i class="icon-people icon-2x mr-2"></i> <span class="font-weight-semibold">{{ __('Users') }}</span></h4>
        </x-slot>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content') 
    <x-card>
        {{$dataTable->table()}}
    </x-card>
 
@stop

@push('js')
    {{$dataTable->scripts()}}
    <script>
        $(document).on('change','#select_status', function () {
            var status = $(this).val();
            var url = $(this).attr('data-url');
            confirmAction('Bạn có muốn thay đổi trạng thái ?', function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        data: {
                            'status': status
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if(res.status == true){
                                showMessage('success', res.message);
                            }else{
                                showMessage('error', res.message);
                            }
                            window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                        },
                    });
                }else{
                    window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                }
            });
        });

        $(document).on('change','#select_type', function () {
            var type = $(this).val();
            var url = $(this).attr('data-url');
            confirmAction('Bạn có muốn thay đổi trạng thái ?', function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        data: {
                            'type': type
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if(res.status == true){
                                showMessage('success', res.message);
                            }else{
                                showMessage('error', res.message);
                            }
                            window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                        },
                    });
                }else{
                    window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                }
            });
        });

        @can('users.create')
        $('.buttons-create').removeClass('d-none')
        @endcan
        @can('users.delete')
        $('.btn-danger').removeClass('d-none')
        @endcan
        @can('users.update')
        $('.btn-warning').removeClass('d-none')
        @endcan

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
