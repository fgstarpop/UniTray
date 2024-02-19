<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WidthDrawDataTable;
use App\Domain\Admin\Models\WithdrawTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Domain\Admin\Models\Statistics;
use DB;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use Carbon\Carbon;
class WithdrawController
{
    use AuthorizesRequests;

    public function index(WidthDrawDataTable $dataTable)
    {
        $this->authorize('view', WithdrawTransaction::class);

        return $dataTable->render('admin.withdraw.index');
    }
    public function changeStatus(WithdrawTransaction $withdraw, Request $request)
    {
        try {
            \DB::transaction(function () use ($request, $withdraw) {
                $this->authorize('update', $withdraw);
                $wallet = Wallet::where('user_id',$withdraw->user_id)->first();
                $code = randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id');
                
                WalletTransaction::create([
                    'transaction_id'    => $code,
                    'user_id'           =>  $withdraw->user_id,
                    'change_type'       => 1,
                    'transaction_type'  => 1,
                    'created_at'        => Carbon::now(),
                    'gold'              => 0,
                    'yuan'              => $withdraw->silver,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                ]);
                $tomorrow = new Carbon('tomorrow midnight');
                $today =  new Carbon('today midnight');
                $withdraw->update(['status' => $request->status]);
                logActivity($withdraw, 'update'); // log activity
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
