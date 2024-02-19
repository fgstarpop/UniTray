<?php

namespace App\Http\Requests\Admin;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class StoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required'],
            'categories_id' => ['required'],
            'tags_id' => ['required'],
            'type' => ['required'],
            'is_vip' => ['in:0,1'],
            'status' => ['in:0,1'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tiêu đề',
            'description' => 'mô tả',
            'is_vip' => 'Chương VIP',
            'status' => 'trạng thái',
            'categories_id' => 'danh mục',
            'tags_id' => 'Loại truyện',
            'type' => 'Thể loại',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
