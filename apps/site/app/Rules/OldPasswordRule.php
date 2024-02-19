<?php

namespace App\Rules;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OldPasswordRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param Authenticatable $user
     */
    public $user;
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, $this->user->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Bạn đã nhập sai mật khẩu.');
    }
}
