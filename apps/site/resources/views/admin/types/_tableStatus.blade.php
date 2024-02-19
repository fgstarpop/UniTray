<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.types.change.status', $id) }}">
    <option value="{{ \App\Enums\TypeState::NoActive }}" {{ $status == \App\Enums\TypeState::NoActive ? 'selected' : '' }}>{{ __('Chờ phê duyệt') }}</option>
    <option value="{{ \App\Enums\TypeState::Active }}" {{ $status == \App\Enums\TypeState::Active ? 'selected' : '' }}>{{ __('Hoạt động') }}</option>
</select>
