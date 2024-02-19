<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Admin\Models\Game;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Domain\Admin\Models\Win;
use App\Domain\Admin\Models\Numbers;
use App\Domain\Admin\Models\Wallet;
use Carbon\Carbon;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Admin\Models\Statistics;
use DB;
class TroChoiController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', Game::class);
        $startTime = new Carbon(Game::START_DAY);
        $endTime = new Carbon(Game::END_DAY);

        $game = Game::whereBetween('created_at', [$startTime, $endTime])
            ->select('number',DB::raw('sum(gold) as total'))
            ->groupBy('number')->pluck('total','number')->all();
        return view('admin.game.index',compact('game'));
    }

    public function store(Request $request)
    {
        $money = 0;  
        $startTime = new Carbon(Game::START_DAY);
        $endTime = new Carbon(Game::END_DAY);

        $num = Numbers::whereBetween('created_at', [$startTime, $endTime])->first();
        $number = $request->number % 100 ;
        if(!$num){
            Numbers::create([
                'number'   => $number,
            ]);
        }else{
            return response()->json([
                'status' => '200',
                'message' => __('Đã có số của hôm nay'),
            ]);
        }
        $winner = Game::where('number', $number)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->get();
        foreach($winner as $list){
            $wallet = Wallet::where('user_id',$list->user_id)->first();
            $wallet->gold = $wallet->gold + $list->gold * 70;
            $wallet->updated_at = Carbon::now();
            $wallet->update();


            WalletTransaction::create([
                'transaction_id'    => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id'),
                'user_id'           => $list->user_id,
                'change_type'       => 0,
                'transaction_type'  => 4,
                'created_at'        => Carbon::now(),
                'gold'              => $list->gold * 70,
                'yuan'              => 0,
                'gold_balance'      => $wallet->gold,
                'yuan_balance'      => $wallet->silver,
            ]);

            Win::create([
                'number'   => $number,
                'gold'    => $list->gold * 70,
                'winner'   => $list->user_id,
            ]);
        }

      
        // $money = $money + $list->gold * 70; 
     //    $statistics = Statistics::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
     //    if($statistics){
     //        $statistics->money = $statistics->money - $money ;
     //        $statistics->save();
     //    }else{
     //        Statistics::create([
     //            'money'   => -$money,
     //        ]);
     //    }
        return response()->json([
          'status' => '200',
          'message' => __('Thêm số thành công'),
      ]);
    }

  
}
