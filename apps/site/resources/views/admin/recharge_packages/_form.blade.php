<form action="{{ $url }}" method="POST" data-block id="recharge-package">
    @csrf
    @method($method ?? 'POST')
    <div class="d-flex align-items-start flex-column flex-md-row">
        <!-- Left content -->
        <div class="w-100 order-2 order-md-1 left-content">
            <div class="row">
                <div class="col-md-12">
                    <x-card>
                        <fieldset>
                            <legend class="font-weight-semibold text-uppercase font-size-sm">
                                {{ __('Gói nạp') }}
                            </legend>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="name"
                                    :placeholder="__('Tiêu đề')"
                                    :label="__('Tiêu đề')"
                                    :value="$rechargePackage->name"
                                    required
                                >
                                </x-text-field>
                                <x-text-field
                                    name="vnd"
                                    :placeholder="__('Số tiền')"
                                    :label="__('Số tiền')"
                                    :value="$rechargePackage->vnd ?? 0"
                                    type="number"
                                    min="0"
                                    required
                                >
                                </x-text-field>
                                <x-text-field
                                    name="gold"
                                    :placeholder="__('Số vàng')"
                                    :label="__('Số vàng')"
                                    type="number"
                                    :value="$rechargePackage->gold ?? 0"
                                    required
                                >
                                </x-text-field>
                                <x-check-field
                                    name="status"
                                    :label="__('Trạng thái')"
                                    :value="$rechargePackage->status"
                                    required
                                >
                                </x-check-field>
                            </div>
                        </fieldset>
                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.recharge-packages.index') }}" class="btn btn-light">{{ __('Hủy') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.recharge-packages.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.recharge-packages.create') }}">{{ __('Lưu & Thêm mới') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /left content -->
    </div>
</form>

@push('js')
{!! JsValidator::formRequest('App\Http\Requests\Admin\RechargePackageRequest', '#recharge-package'); !!}
@endpush()
