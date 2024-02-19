<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.categories.change.status', $id) }}">
    <option value="{{ \App\Enums\CategoryState::NoActive }}" {{ $status == \App\Enums\CategoryState::NoActive ? 'selected' : '' }}>{{ __('Chờ phê duyệt') }}</option>
    <option value="{{ \App\Enums\CategoryState::Active }}" {{ $status == \App\Enums\CategoryState::Active ? 'selected' : '' }}>{{ __('Hoạt động') }}</option>
</select>
