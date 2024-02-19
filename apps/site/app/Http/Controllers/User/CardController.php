<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Domain\Activity\Card;
use Carbon\Carbon;
use App\Domain\Recharge\Models\RechargeTransaction;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use DB;
class CardController
{
    public function index(){
        $card = Card::where('user_id',currentUser()->id)->orderBy('updated_at','desc')->paginate(50);
        return view('shop.user.card.create', compact('card'));
    }
    public function top(){
        $first = Carbon::now()->startOfMonth();
        $last = Carbon::now()->lastOfMonth();
        $top = RechargeTransaction::where('created_at','>',$first)->where('created_at','<',$last)->orderBy('vnd_in_month','desc')->get()->unique('user_id');
        return view('shop.top', compact('top'));
    }
    public function add(Request $request){
        try {
            \DB::transaction(function () use ($request) {
                $discount = 0;
                    switch($request->telco){
                        case 'VIETTEL':
                            $discount = setting('discount_vt',0) ;
                        case 'VINAPHONE':
                            $discount = setting('discount_vina',0) ;
                        case 'MOBIFONE':
                            $discount = setting('discount_mobile',0)  ;
                        case 'ZING':
                            $discount = setting('discount_zing',0) ;
                        case 'GATE':
                            $discount = setting('discount_gate',0) ;
                        case 'VIETNAMOBILE':
                            $discount =  setting('discount_vm',0) ;
                    }
                    $gold = $request->amount - $request->amount * $discount / 100 ;
                    $card = Card::create([
                        'telco'     => $request->telco,
                        'code'      => $request->code,
                        'serial'    => $request->serial,
                        'user_id'   => currentUser()->id,
                        'amount'    => $request->amount,
                        'gold'      => $gold,
                        'status'    => '2',
                    ]);
                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage()]);
            }
        return 1;
    }
    public function callback(Request $request){
//        \Log::info($request->all());
    try {
        \DB::transaction(function () use ($request) {
            $first = Carbon::now()->startOfMonth();
            $last = Carbon::now()->lastOfMonth();
            $card = Card::where('serial',$request->serial)->where('code',$request->code)->where('status','2')->first();
//        \Log::info($card);
            if($request->status == "success"){
                $recharge = RechargeTransaction::where('user_id',$card->user_id)->where('created_at','>',$first)->where('created_at','<',$last)->orderBy('updated_at')->first();
                if($recharge){
                    RechargeTransaction::create([
                        'user_id' => $card->user_id,
                        'status'  => 1,
                        'code'    => $request->transaction_id,
                        'vnd'     => $request->real_amount,
                        'type'    => 1,
                        'vnd_in_month' =>  $recharge->vnd_in_month + $request->real_amount
                    ]);
                }else{
                    RechargeTransaction::create([
                        'user_id' => $card->user_id,
                        'status'  => 1,
                        'code'    => $request->transaction_id,
                        'vnd'     => $request->real_amount,
                        'type'    => 1,
                        'vnd_in_month' =>  $request->real_amount
                    ]);
                }
                $card->real_amount = $request->real_amount;
                $card->status = 1;
                $card->save();
                $discount = 0;
                switch($request->telco){
                    case 'VIETTEL':
                        $discount = setting('discount_vt',0) ;
                    case 'VINAPHONE':
                        $discount = setting('discount_vina',0) ;
                    case 'MOBIFONE':
                        $discount = setting('discount_mobile',0)  ;
                    case 'ZING':
                        $discount = setting('discount_zing',0) ;
                    case 'GATE':
                        $discount = setting('discount_gate',0) ;
                    case 'VIETNAMOBILE':
                        $discount =  setting('discount_vm',0) ;
                }
                $wallet = Wallet::where('user_id',$card->user_id)->first();
                $wallet->gold =  $wallet->gold + (int)$request->real_amount - (int)$request->real_amount * $discount / 100;
                $wallet->update();

                WalletTransaction::create([
                    'transaction_id'    => $request->transaction_id,
                    'user_id'           => $card->user_id,
                    'change_type'       => 0,
                    'transaction_type'  => 0,
                    'created_at'        => Carbon::now(),
                    'gold'              => (int)$request->real_amount - (int)$request->real_amount * $discount / 100,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                ]);
            }else{
                $card->status = 0;
                $card->save();
            }
            if(!$card){
                $cards = Card::where('serial',$request->serial)->get();
                foreach($cards as $list){
                    $list->status = 0;
                    $list->save();
                }
            }
                });
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
            return '200';
    }
}
