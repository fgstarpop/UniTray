<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Domain\Admin\Models\Wallet;
use App\Http\Controllers\Controller;
use App\Rules\PhoneVnRule;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Validator::extend('not_contains', function($attribute, $value, $parameters)
        {
            // Banned words
            $words = array('admin');
            foreach ($words as $word)
            {
                if (stripos($value, $word) !== false) return false;
            }
            return true;
        });
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255','not_contains'],
            'username' => ['required', 'string', 'max:255', 'unique:dbuser.users','alpha','not_contains'],
            'email' => ['required', 'email', 'max:255', 'unique:dbuser.users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
//            'g-recaptcha-response' => 'required',
        ],[
//            'g-recaptcha-response.required' => 'Bạn cần xác minh không phải robot'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'      => $data['name'],
            'username'  => $data['username'],
            'email'     => $data['email'],
            'bio'       => '',
            'password'  => Hash::make($data['password']),
            'avatar'    => 'no-user.png'
        ]);
        Wallet::create([
            'user_id' => $user->id,
            'vnd'     => 0,
            'gold'     => 0,
            'silver'     => 0,
        ]);
        return $user;
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    protected function registered(Request $request, $user)
    {
        flash()->success('Đăng ký tài khoản thành công!');
        return redirect()->to('/');
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Đăng ký tài khoản thành công!'
        // ]);
    }
}
