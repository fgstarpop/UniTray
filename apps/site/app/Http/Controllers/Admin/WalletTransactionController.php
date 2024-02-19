<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WalletTransactionDataTable;
use Illuminate\Http\Request;
use App\Domain\Admin\Models\WalletTransaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Domain\Admin\Models\Wallet;
class WalletTransactionController
{
    use AuthorizesRequests;

    public function index(WalletTransactionDataTable $dataTable,WalletTransaction $wallets)
    {
        $this->authorize('view', WalletTransaction::class);
        return $dataTable->render('admin.wallet-transaction.index');
    }

   
    public function destroy($id)
    {
          $transaction = WalletTransaction::find($id);
          if($transaction->undo == 0){
               $wallets = Wallet::where('user_id',$transaction->user_id)->first();
               if($transaction->transaction_type == 0){
                    $wallets->gold = $wallets->gold - $transaction->gold;
                    $wallets->silver = $wallets->silver - $transaction->yuan;
               }else{
                    $wallets->gold = $wallets->gold + $transaction->gold;
                    $wallets->silver = $wallets->silver + $transaction->yuan;
               }
               $wallets->update();

               
               $transaction->undo = 1;
               $transaction->gold_balance = $wallets->gold;
               $transaction->yuan_balance = $wallets->silver;
               $transaction->updated_at = Carbon::now();
               $transaction->update();
          }

          return response()->json([
               'success' => true,
               'message' => __('Hoàn tác thành công !'),
          ]);
    }

}
