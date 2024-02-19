<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cookie;
use App\User;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest:web')->except('logout');
    }

    public function showLoginForm(){
        return redirect()->route('home');
    }
    protected function authenticated(Request $request, $user)
    {
        $result = ['status' => true];
        if ($request->filled('redirect_url')) {
            $result['redirect_url'] = $request->input('redirect_url');
        }
        return response()->json($result);
    }

    protected function credentials(Request $request)
    {
        $fieldType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'email';

        return [
            $fieldType => $request->input('email'),
            'password' => $request->input('password'),
        ];
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request){
        $remember = $request->input('remember');
        $data =$request->only('username','password');
        $minutes = 60800;
        if($remember){
            Cookie::queue('username', $request->username, $minutes);
            Cookie::queue('password', $request->password, $minutes);
        } 
        if(Auth::guard('web')->attempt($data,true)){
            Auth::guard('web')->logoutOtherDevices($request->password);
            if(currentUser()->status == \App\Enums\UserState::Block){
                Auth::guard('web')->logout();
                return response()->json([
                    'status' => '400',
            ]);
            }
            return response()->json([
                'status' => '300',
        ]);
        }
        
        else{
            return response()->json([
                'status' => '200',
        ]);
        }
    }
}
