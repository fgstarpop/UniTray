<?php

namespace App\Http\Requests\User;

use App\Rules\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;

class EditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'         => ['required', 'email'],
            'name'          => ['required'],
            'password'      => 'confirmed',
            'old_password'  => ['nullable', 'required_with:new_password', 'max:255', new OldPasswordRule(currentUser())],
        ];
    }
}