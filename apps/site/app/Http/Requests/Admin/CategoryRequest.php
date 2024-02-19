<?php

namespace App\Http\Requests\Admin;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.(isset($this->route('category')->id) ? $this->route('category')->id : '')],
            'status' => ['required', 'in:0,1'],
            'slug' => ['nullable', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:255', 'unique:categories,slug,'.(isset($this->route('category')->id) ? $this->route('category')->id : '')],
        ];
    }

    public function attributes()
    {
        return [ 
            'name' => 'tiêu đề',
            'slug' => 'đường dẫn',
        ];
    }

    public function messages()
    {
        return [
            'slug.regex' => 'Không cho phép nhập ký tự đặc biệt!',
        ];
    }
}
