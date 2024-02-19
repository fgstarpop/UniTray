<?php

namespace App\Http\Requests\Admin;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class RechargePackageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', 'in:0,1'],
            'vnd' => ['required', 'numeric', 'min:0', 'max:100000000'],
            'gold' => ['required', 'numeric', 'min:0', 'max:100000000'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tiêu đề',
            'status' => 'trạng thái',
            'vnd' => 'số tiền',
            'gold' => 'số vàng',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
