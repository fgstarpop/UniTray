<select class="form-control w-auto" name="type" id="select_type" data-url="{{ route('admin.users.change.type', $id) }}">
    <option value="{{ \App\Enums\UserType::Normal }}" {{ $is_vip == \App\Enums\UserType::Normal ? 'selected' : '' }}>{{ __('Bình thường') }}</option>
    <option value="{{ \App\Enums\UserType::Mod }}" {{ $is_vip == \App\Enums\UserType::Mod ? 'selected' : '' }}>{{ __('Người bật kiếm tiền') }}</option> 
</select>
