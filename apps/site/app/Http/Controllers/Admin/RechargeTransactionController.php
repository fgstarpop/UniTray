<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RechargeTransactionDataTable;
use App\Domain\Recharge\Models\RechargeTransaction;
use App\Domain\Admin\Models\WalletTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Domain\Admin\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RechargeTransactionController
{
    use AuthorizesRequests;

    public function index(RechargeTransactionDataTable $dataTable)
    { 
        $this->authorize('view', RechargeTransaction::class);
        return $dataTable->render('admin.recharge_transactions.index');
    } 
    
    public function changeStatus(RechargeTransaction $rechargeTransaction, Request $request)
    {
        try { 
            DB::transaction(function () use ($request, $rechargeTransaction) {
                $wallet         = Wallet::where('user_id',$rechargeTransaction->user_id)->first();
                $wallet->gold   = $wallet->gold + $rechargeTransaction->vnd;
                $wallet->update();

                WalletTransaction::create([ 
                    'transaction_id'    => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id'),
                    'user_id'           => $rechargeTransaction->user_id,
                    'change_type'       => '0', 
                    'transaction_type'  => '0',
                    'gold'              => $rechargeTransaction->vnd,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                    'created_at'        => Carbon::now()
                ]);
                $this->authorize('update', $rechargeTransaction);
                
                $rechargeTransaction->update(['status' => $request->status]);
                logActivity($rechargeTransaction, 'update'); // log activity
            });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => true,
            'message' => __('Cập nhật trạng thái thành công !'),
        ]);
    }
}
