@extends('admin.layouts.master')

@section('title', __('Trò chơi'))
  
@section('page-content')
<div class="text-center mt-2" style="width:70%;margin:0 auto">
    <h2>Người chơi hôm nay</h2>
        @for($i = 0 ; $i < 10 ; $i++)
            <div class="d-flex">
            @for($j = 0 ; $j < 10 ; $j++)
			@php 
			    $check = 0;
                if(isset($game[ $i * 10 + $j] ) && $game[ $i * 10 + $j])
                    $check = 1 ;
		    @endphp
            @if($check == 1)
                <div class="border cs p-4 number actives position-relative" data-num={{ $i }}{{ $j }}>{{ $i }}{{ $j }}
                <p class="font">{{ $game[ $i * 10 + $j] }} vàng</p>
			@else
            	<div class="border cs p-4 number" data-num={{ $i }}{{ $j }}>{{ $i }}{{ $j }}
			@endif
            </div>
            @endfor
            </div>
        @endfor
</div>
@stop

@push('js') 
    <script src="{{ asset('backend/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script>
        $(document).on('change', '#select_status', function () {
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
                        success: function (res) {
                            if (res.status == true) {
                                showMessage('success', res.message);
                            } else {
                                showMessage('error', res.message);
                            }
                        },
                    });
                } else {
                }
            });
        });
        @can('pages.create')
        $('.buttons-create').removeClass('d-none')
        @endcan
        @can('pages.delete')
        $('.buttons-selected').removeClass('d-none')
        @endcan
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
