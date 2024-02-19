<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.withdraw.change.status', $id) }}" @if($status == \App\Enums\RechargeTransactionState::Active || $status == \App\Enums\RechargeTransactionState::Block) disabled @endif >
    <option value="{{ \App\Enums\RechargeTransactionState::NoActive }}" {{ $status == \App\Enums\RechargeTransactionState::NoActive ? 'selected' : '' }}>{{ __('Đang chờ') }}</option>
    <option value="{{ \App\Enums\RechargeTransactionState::Active }}" {{ $status == \App\Enums\RechargeTransactionState::Active ? 'selected' : '' }}>{{ __('Đã rút') }}</option>
    <option value="{{ \App\Enums\RechargeTransactionState::Block }}" {{ $status == \App\Enums\RechargeTransactionState::Block ? 'selected' : '' }}>{{ __('Đã hủy') }}</option>
</select>
