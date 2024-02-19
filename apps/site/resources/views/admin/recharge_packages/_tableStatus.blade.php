<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.recharge-packages.change.status', $id) }}">
    <option value="{{ \App\Enums\RechargePackageState::NoActive }}" {{ $status == \App\Enums\RechargePackageState::NoActive ? 'selected' : '' }}>{{ __('Không hoạt động') }}</option>
    <option value="{{ \App\Enums\RechargePackageState::Active }}" {{ $status == \App\Enums\RechargePackageState::Active ? 'selected' : '' }}>{{ __('Hoạt động') }}</option>
</select>
