<?php

namespace App\Http\Controllers\ControllerTuLuyen;

use App\User;
use Carbon\Carbon;
use Carbon\Translator;
use App\Traits\TuLuyenCfg;
use App\Traits\UserDetails;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Traits\TuLuyenItems;
use Illuminate\Http\Request;
use App\Traits\TuLuyenTraits;

use App\TuLuyen\Model_charater;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class User_tuluyen
{
    public function index()
    {


		

        if(!Auth::guard('web')->check()){
            flash()->error('Bạn cần đăng nhập để sử dụng tính năng này');
            return redirect()->route('login');
        }

        $players_user = Auth::guard('web')->user();


        $tuluyen =  new TuLuyenTraits($players_user);
        $players = $tuluyen->get_details();

        if(!$players){
            flash()->error('Bạn chưa tạo nhân vật');
            return redirect()->route('tuluyen.create');
        }


        if($players['is_ban']){
            flash()->error('Bạn đã bị cấm tu luyện');
            return redirect()->route('home');
        }
       $gold = $players_user->get_gold->gold;
       $item_list = $tuluyen->get_players_item();


       return view('TuLuyen.index',compact('players',
       'players_user','item_list','gold'
       ));

    }

    public function create()
    {
        if(!Auth::guard('web')->check()){
            flash()->error('Bạn cần đăng nhập để sử dụng tính năng này');
            return redirect()->route('home');
        }
        $user = Auth::guard('web')->user();


        // $players = players_charater::

        // where('user_id','=',$user->id)->first();
        $players = $user->get_charaters;


        if($players){
            flash()->error('Bạn đã tạo nhân vật');
            return redirect()->route('tuluyen.index');

        }
        return view('TuLuyen.CreateCharater',['user'=>$user]);

    }

    public function store(Request $request)
    {
    }

    public function show(int $id)
    {
    }

    public function edit(int $id)
    {
    }

    public function update(Request $request, int $id)
    {
    }

    public function destroy(int $id)
    {
    }
}
