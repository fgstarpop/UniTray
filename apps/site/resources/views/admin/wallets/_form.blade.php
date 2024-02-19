<form action="{{ $url }}" method="POST" data-block id="wallet">
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
                                {{ __('Ví của') }}
                                @if($wallet->id)
                                     <a href="#" class="text-primary font-weight-semibold"
                                         target="_blank">{{ Str::limit($wallet->user->name, 20) }}</a>
                                @endif
                            </legend>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="gold"
                                    :label="__('Vàng')"
                                    :value="$wallet->gold"
                                    required
                                >
                                </x-text-field>
                                 
                            </div>
                        </fieldset>
                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.wallets.index') }}" class="btn btn-light">{{ __('Hủy') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-wallet" data-redirect="{{ route('admin.wallets.index') }}">{{ __('Lưu & Thoát') }}</a>
                                 
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
{!! JsValidator::formRequest('App\Http\Requests\Admin\WalletRequest', '#wallet'); !!}
@endpush()
