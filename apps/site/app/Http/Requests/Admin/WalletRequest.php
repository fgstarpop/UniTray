<?php

namespace App\Http\Requests\Admin;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class WalletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gold'   => ['required', 'integer'],
            'silver' => ['required', 'integer'],
            'vnd'    => ['required', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'gold' => 'vàng',
            'silver' => 'tệ',
            'vnd' => 'tiền',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
