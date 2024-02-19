<?php

namespace App\Http\Requests\User;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
            'name' => ['required', Rule::unique('users')->ignore(auth()->id())],
            'password' => 'confirmed',
        ];
    }
}