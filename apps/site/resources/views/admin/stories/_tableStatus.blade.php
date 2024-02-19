<select class="form-control w-auto" name="status" id="select_status" data-url="{{ route('admin.stories.change.status', $id) }}">
    <option value="{{ \App\Enums\StoryState::NoActive }}" {{ $status == \App\Enums\StoryState::NoActive ? 'selected' : '' }}>{{ __('Ẩn') }}</option>
    <option value="{{ \App\Enums\StoryState::Active }}" {{ $status == \App\Enums\StoryState::Active ? 'selected' : '' }}>{{ __('Hiển thị') }}</option>
</select>
