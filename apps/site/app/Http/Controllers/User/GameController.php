<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Domain\Admin\Models\Game;
use Illuminate\Http\Request;
use App\Domain\Admin\Models\Wallet;
use Carbon\Carbon;
use App\Domain\Admin\Models\WalletTransaction;
use DB;
use App\Domain\Admin\Models\Statistics;
use App\Domain\Admin\Models\Win;
use App\Domain\Admin\Models\Numbers;
class GameController extends Controller
{

    public function index()
    {
        if(!auth('web')->check()){
            return redirect()->route('home');
        }
        $timeFirst = new Carbon(Game::START_DAY);
        $timeSecond = new Carbon(Game::END_DAY);
        $tomorrow = new Carbon('tomorrow midnight');
        $today =  new Carbon('today midnight');
        $yesterday =  new Carbon('yesterday midnight');
        $num = Numbers::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
        $win = Win::where('created_at','<', $timeSecond)->where('created_at','>', $timeFirst)->with('user')->orderBy('gold','desc')->take(10)->get();
        if(!$num)
        {
            $win = Win::where('created_at','<', $timeFirst)->where('created_at','>', $timeFirst->subDays(1))->with('user')->orderBy('gold','desc')->take(10)->get();
        }
        $numberWin = Numbers::where('created_at','<=', $tomorrow)->orderBy('created_at','desc')->take(7)->get();
        return view('shop.user.game.index',compact('win','numberWin'));
    }

    public function create()
    {
        $timeFirst = new Carbon(Game::END_DAY);
        $timeSecond = new Carbon(Game::START_DAY);
        $now = Carbon::now();
        if($now < $timeFirst){
            $game = Game::where('user_id',currentUser()->id)
            ->where('created_at', '<', $timeFirst)
            ->where('created_at','>', $timeSecond)->get();
        }else{
            $game = Game::where('user_id',currentUser()->id)
            ->where('created_at', '>', $timeFirst) ->get();
        }

        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        return view('shop.user.game.game',compact('game','wallet'));
    }
    public function order(Request $request)
    {
        $moneyOld = 0;
        $moneyNew = 0;
        $timeFirst = new Carbon(Game::END_TIME);
        $timeSecond = new Carbon(Game::START_TIME);
        $yesterday =  new Carbon(Game::START_DAY);
        $gameOld = DB::table('game')->where('user_id',currentUser()->id)
        ->where(function ($q) use ($timeFirst , $timeSecond) {
            return $q->orWhere('created_at', '<', $timeFirst)
                ->orWhere('created_at', '>', $timeSecond);
        })
        ->where('created_at','>', $yesterday);
        $moneyOld = $gameOld->sum('gold');

        if(!empty($request->total_item)){
    //            dd($request->total_item);
            foreach($request->total_item as $list){
                $moneyNew = $moneyNew + $list['gold'];
            }
        }
        $wallet = Wallet::where('user_id',currentUser()->id)->first();

        if($wallet->gold < $moneyNew - $moneyOld){
            return response()->json([
                'status' => '300',
                'message' => __('Bạn không đủ vàng . Vui lòng thử lại'),
            ]);
        }
        $gameOld->delete();
        try {
            \DB::transaction(function () use ($request,$wallet,$moneyNew,$moneyOld,$timeFirst,$timeSecond,$yesterday) {

        //    $statistics = Statistics::where('created_at','<', $tomorrow)->where('created_at','>', $yesterday)->first();
        //    if($statistics){
        //        $statistics->money = $statistics->money + $money;
        //        $statistics->save();
        //    }else{
        //        Statistics::create([
        //            'money'   =>  $money,
        //        ]);
        //    }

        if(!isset($moneyOld) || empty($moneyOld)){
            $moneyOld = 0;
        }

        if ($moneyOld != 0 && $moneyNew < $moneyOld){
            $changeType = 2;
            $moneyUpdate = $moneyOld - $moneyNew;
            $wallet->gold = $wallet->gold + $moneyUpdate;
        }elseif($moneyOld != 0 && $moneyNew > $moneyOld){
            $changeType = 1;
            $moneyUpdate = $moneyNew - $moneyOld;
            $wallet->gold = $wallet->gold - $moneyUpdate;
        }elseif ($moneyNew == $moneyOld){
            $changeType = 1;
            $moneyUpdate = $moneyNew - $moneyOld;
            $wallet->gold = $wallet->gold - $moneyUpdate;
        }else{
            $changeType = 1;
            $moneyUpdate = $moneyNew - $moneyOld;
            $wallet->gold = $wallet->gold - $moneyUpdate;
        }

        $wallet->save();
        WalletTransaction::create([
            'transaction_id'    => rand(000000,999999),
            'user_id'           =>  currentUser()->id,
            'change_type'       => $changeType,
            'transaction_type'  => 4,
            'created_at'        => Carbon::now(),
            'gold'              => $moneyUpdate,
            'yuan'              => 0,
            'gold_balance'      => $wallet->gold,
            'yuan_balance'      => $wallet->silver,
        ]);
        if(!empty($request->total_item)) {
            foreach ($request->total_item as $list) {
                $number = Game::where('user_id', currentUser()->id)->where('number', $list['number'])
                    ->where(function ($q) use ($timeFirst, $timeSecond) {
                        return $q->orWhere('created_at', '<', $timeFirst)
                            ->orWhere('created_at', '>', $timeSecond);
                    })
                    ->where('created_at', '>', $yesterday)->first();
                if ($number) {
                    $number->gold = $list['gold'];
                    $number->updated_at = Carbon::now();
                    $number->save();
                } else {
                    Game::create([
                        'user_id' => currentUser()->id,
                        'number' => $list['number'],
                        'gold' => $list['gold'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
            });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => true,
            'message' => __('Đặt xổ số thành công !'),
        ]);
    }
    public function delete(Request $request)
    {
        $timeFirst = new Carbon(Game::END_TIME);
        $timeSecond = new Carbon(Game::START_TIME);
        $yesterday =  new Carbon(Game::START_DAY);
        $game = Game::where('user_id',currentUser()->id)->where('number',$request->number)
         ->where(function ($q) use ($timeFirst , $timeSecond) {
            return $q->orWhere('created_at', '<', $timeFirst)
                ->orWhere('created_at', '>', $timeSecond);
        })
        ->where('created_at','>', $yesterday)->first();

        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        $wallet->gold = $wallet->gold + $game->gold ;
        $wallet->update();
        $game->delete();
        return response()->json([
            'status' => true,
            'gold'   => $wallet->gold
        ]);
    }
}
