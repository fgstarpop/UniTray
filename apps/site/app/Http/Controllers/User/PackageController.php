<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Domain\Recharge\Models\RechargePackage;
use App\Domain\Recharge\Models\RechargeTransaction;
use Str;
use Carbon\Carbon;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Admin\Models\Wallet;
use DB;
class PackageController
{
    public function thueapimb(Request $request){
        try {
            \DB::transaction(function () use ($request) {
                \Log::info($request->all());
                $tokenMB = 'fMWZRpKJJAB5vpf6D5W3SRWyxNnEmkfjWJYvJsKmKuzhgCyke7';
                $thueapiToken = $request->header('X-Thueapi');
                if ($tokenMB !== $thueapiToken) {

                    return response([
                        'success' => false,
                        'message' => 'Token missmatch !'
                    ], 401);
                }
                $content = $request->input('content');
                if($request->gateway == 'mbbank'){
                    $content = explode(' ', $content);
//                    $content = current($content);
                }
//                \Log::info($content);

                foreach ($content as $code) {
                    $rechargeTransactionQuery = RechargeTransaction::orWhere('code', $code)->where('status', 0);
                }

                $rechargeTransaction = $rechargeTransactionQuery->first();
                if (!$rechargeTransaction) {
                    return response([
                        'success' => false,
                        'message' => 'Mã không chính xác ' . $content . ' !'
                    ], 401);
                }
                \Log::info($rechargeTransaction);
                $now = Carbon::now();
                $event =Carbon::create( 2022,2,1,0,0,0);
                $event_end = Carbon::create( 2022,2,4,0,0,0);
                $money = $request->money;
                if ($now->greaterThanOrEqualTo($event)  && $now->lessThan($event_end) ) {
                    if ($money < 200000){
                        $money += $money*0.1;
                    }elseif ($money >= 200000 and $money<1000000){
                        $money += $money*0.2;
                    }elseif ($money >= 1000000){
                        $money += $money*0.3;
                    }

                }
                $wallet         = Wallet::where('user_id',$rechargeTransaction->user_id)->first();
                $wallet->gold   = $wallet->gold + $money;
                $wallet->update();
                $transactionCode = randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id');

                WalletTransaction::create([
                    'transaction_id'    => $transactionCode,
                    'user_id'           => $rechargeTransaction->user_id,
                    'change_type'       => '0',
                    'transaction_type'  => '0',
                    'gold'              => $money,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                    'created_at'        => $request->datetime
                ]);
                if($request->name == 'momo')
                    $rechargeTransaction->type = 2;
                else
                    $rechargeTransaction->type = 0;
                $rechargeTransaction->status = 1;
                $rechargeTransaction->save();
                    });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
    }

    public function thueapimomo(Request $request){
        try {
            \DB::transaction(function () use ($request) {
                \Log::info($request->all());
                $tokenMomo = 'EogoCTPbOdw0eKHVKfrCeoRGjijYkbk6k1iRcBZgOxSjjkGGYA';
                $thueapiToken = $request->header('X-Thueapi');
                if ($tokenMomo !== $thueapiToken) {

                    return response([
                        'success' => false,
                        'message' => 'Token missmatch !'
                    ], 401);
                }
                $content = $request->input('content');
                \Log::info($content);
                $rechargeTransaction = RechargeTransaction::where('code', $content)->where('status', 0)->first();
                \Log::info($rechargeTransaction);
                $now = Carbon::now();
                $event =Carbon::create( 2022,2,1,0,0,0);
                $event_end = Carbon::create( 2022,2,4,0,0,0);
                $money = $request->money;
                if ($now->greaterThanOrEqualTo($event)  && $now->lessThan($event_end) ) {
                    if ($money < 200000){
                        $money += $money*0.1;
                    }elseif ($money >= 200000 and $money<1000000){
                        $money += $money*0.2;
                    }elseif ($money >= 1000000){
                        $money += $money*0.3;
                    }

                }
                $wallet         = Wallet::where('user_id',$rechargeTransaction->user_id)->first();
                $wallet->gold   = $wallet->gold + $money;
                $wallet->update();
                $transactionCode = randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id');
                WalletTransaction::create([
                    'transaction_id'    => $transactionCode,
                    'user_id'           => $rechargeTransaction->user_id,
                    'change_type'       => '0',
                    'transaction_type'  => '0',
                    'gold'              => $money,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                    'created_at'        => $request->datetime
                ]);
                if($request->name == 'momo')
                    $rechargeTransaction->type = 2;
                else
                    $rechargeTransaction->type = 0;
                $rechargeTransaction->status = 1;
                $rechargeTransaction->save();
                    });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
    }

    public function recharge(){
        $recharge = RechargeTransaction::where('user_id',currentUser()->id)->orderBy('updated_at','desc')->paginate(50);
        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        return view('shop.user.package.recharge',compact('recharge','wallet'));
    }

    public function store(Request $request){
        if(!$request->vnd){
            return response()->json([
                'status' => '300',
                'message' => 'Số tiền không được để trống',
            ]);
        }
        if($request->vnd < 0){
            return response()->json([
                'status' => '300',
                'message' => 'Số tiền phải lớn hơn 0',
            ]);
        }
        if(!is_numeric($request->vnd)){
            return response()->json([
                'status' => '300',
                'message' => 'Số tiền phải là số',
            ]);
        }
        $wallet = Wallet::where('user_id',currentUser()->id)->first();

        try {
            \DB::transaction(function () use ($request) {
                $first = Carbon::now()->startOfMonth();
                $last = Carbon::now()->lastOfMonth();
                $now = Carbon::now();
                $event =Carbon::create( 2022,2,1,0,0,0);
                $event_end = Carbon::create( 2022,2,4,0,0,0);


                $money = $request->vnd;
                if ($now->greaterThanOrEqualTo($event)  && $now->lessThan($event_end) ) {
                    if ($money < 200000){
                        $money += $money*0.1;
                    }elseif ($money >= 200000 and $money<1000000){
                        $money += $money*0.2;
                    }elseif ($money >= 1000000){
                        $money += $money*0.3;
                    }

                }

                $recharge = RechargeTransaction::where('user_id', currentUser()->id)->where('created_at','>',$first)->where('created_at','<',$last)->orderBy('updated_at')->first();
                if($recharge){
                    RechargeTransaction::create([
                        'user_id' => currentUser()->id,
                        'code'    => $request->code,
                        'vnd'     => $money,
                        'type'    => $request->type,
                        'vnd_in_month' => $recharge->vnd_in_month +  $money
                    ]);
                }else{
                    RechargeTransaction::create([
                        'user_id' => currentUser()->id,
                        'code'    => $request->code,
                        'vnd'     => $money,
                        'type'    => $request->type,
                        'vnd_in_month' => $money
                    ]);
                }
            });
            return response()->json([
                'status' => '200',
                'message' => 'Thành công . Vàng của bạn sẽ được cập nhật sau khi admin kiểm tra thành công !',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '300',
                'message' => $e->getMessage(),
            ]);
        }
        return response()->json([
            'status' => '200',
            'message' => 'Thành công . Vàng của bạn sẽ được cập nhật sau khi admin kiểm tra thành công !',
        ]);
        }
        public function delete(Request $request){
            return response()->json([
                'status' => true,
                'message' => __('Xóa gói thành công'),
            ]);
        }
}
