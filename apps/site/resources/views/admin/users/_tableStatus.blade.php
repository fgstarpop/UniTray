<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.users.change.status', $id) }}">
    <option value="{{ \App\Enums\UserState::Block }}" {{ $status == \App\Enums\UserState::Block ? 'selected' : '' }}>{{ __('Khóa tài khoản') }}</option>
    <option value="{{ \App\Enums\UserState::Active }}" {{ $status == \App\Enums\UserState::Active ? 'selected' : '' }}>{{ __('Hoạt động') }}</option>
</select>
