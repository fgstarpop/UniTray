<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;
use App\Domain\Admin\Models\WithdrawTransaction;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\Statistics;
use DB;
use App\Domain\Admin\Models\WalletTransaction;
class WithDrawController
{
    public function index(){
        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        $withdraw = WithdrawTransaction::where('user_id',currentUser()->id)->orderBy('id','desc')->paginate(50);
        return view('shop.user.withdraw.index',compact('withdraw','wallet'));
    }
    public function delete(Request $request){
        $status = 0;
        try {
            \DB::transaction(function () use ($request,$status) {
        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        $withdraw = WithdrawTransaction::where('user_id',currentUser()->id)->where('id',$request->id)->first();
        if($withdraw->status == 0){
            $withdraw->status = 2;
            $withdraw->update();
            $wallet->silver = $wallet->silver + $withdraw->silver;
            $wallet->update();
            WalletTransaction::create([
                'transaction_id'    => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id'),
                'user_id'           => $withdraw->user_id,
                'change_type'       => 0,
                'transaction_type'  => 1,
                'created_at'        => Carbon::now(),
                'gold'              => 0,
                'yuan'              => $withdraw->silver,
                'gold_balance'      => $wallet->gold,
                'yuan_balance'      => $wallet->silver,
            ]);
            $status = 1;
        }
                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage(),'status'=>'200']);
            }
        if($status == 1)
        return response()->json([
            'status' => '200',
            'message' => 'Hủy rút tiền thành công',
        ]);
        else
        return response()->json([
            'status' => '300',
            'message' => 'Lỗi không xác định',
        ]);
    }
    public function store(Request $request){

        $wallet = Wallet::where('user_id',currentUser()->id)->first();
        if(!$request->silver){
            return response()->json([
                'status' => '300',
                'message' => 'Số tệ không được để trống',
            ]);
        }
        if($request->silver < 0){
            return response()->json([
                'status' => '300',
                'message' => 'Số tệ phải lớn hơn 0',
            ]);
        }
        if(!is_numeric($request->silver)){
            return response()->json([
                'status' => '300',
                'message' => 'Số tệ phải là số',
            ]);
        }
        if( $wallet->silver < $request->silver){
            return response()->json([
                'status' => '300',
                'message' => 'Bạn không đủ tiền để rút',
            ]);
        }
        try {
            DB::transaction(function () use ($request,$wallet) {
            $wallet->silver =  $wallet->silver - $request->silver;
            $wallet->update();
            WithdrawTransaction::create([
                'user_id'       =>  currentUser()->id,
                'silver'        =>  $request->silver,
                'money_current_wallet'   =>  $wallet->silver,
                'code'          =>  randCode('App\Domain\Admin\Models\WithdrawTransaction','code'),
                'bank'          =>  $request->nganhang,
                'stk'           =>  $request->stk,
                'name'          =>  $request->name,
                'created_at'    =>  Carbon::now()
            ]);
        });
        } catch (\Exception $e) {
            return response()->json([
                'status' => '300',
                'message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => '200',
            'message' => 'Tạo giao dịch thành công . Vui lòng chờ !',
        ]);
    }
}
