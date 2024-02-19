<?php

namespace App\Console\Commands;

use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Recharge\Models\RechargePackage;
use App\Domain\Recharge\Models\RechargeTransaction;
use App\Domain\User\Models\BankAuto;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoConfirmBankTransfer extends Command
{
    use AuthorizesRequests;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-bank-transfer {--refund=} {--amount=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle()
    {
//        BankAuto::truncate();
//        setting()->put('count_transactions', 0);
        if (!empty($this->option('refund'))) {
            $id = $this->option('refund');
            $amount = $this->option('amount');
            $userWallet = Wallet::where('user_id', $id)->first();
            WalletTransaction::create([
                'user_id' => $id,
                'change_type' => 1,
                'transaction_type' => 1,
                'gold' => $amount,
                'gold_balance' => (int)($userWallet->gold - $amount),
                'yuan' => 0,
                'yuan_balance' => $userWallet->yuan_balance + 0,
                'transaction_id' => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id')
            ]);
            $userWallet->update([
                'gold' => (int)($userWallet->gold - $amount),
                'vnd' => (int)($userWallet->gold - $amount)
            ]);
            return 0;
        }
        $response = Http::get('https://api.web2m.com/historyapiacbv3/Ndso2pro/12832187/A1D30191-C9DD-B7EB-8E21-E211651BFE27');
        $response = json_decode($response, true);
        if (!empty($response) && $response['status'] == true) {
//            $times_loop = count($response['transactions']) - (int)setting('count_transactions', 0);
//            setting()->put('count_transactions', count($response['transactions']));
//            $loop_index = 1;
            foreach($response['transactions'] as $data) {
//                if ($times_loop < $loop_index) {
//                    exit;
//                }
                $des = $data['description'];
                $amount = $data['amount'];
                $tid = $data['transactionID'];
                $type = $data['type'];
                $id = $this->parse_order_id($des);
                $userWallet = Wallet::where('user_id', $id)->first();
                if (!empty($userWallet) && $type == 'IN') {
                    $bankTransfer = BankAuto::where([
                        'tid' => $tid,
                        'description' => $des
                    ]);
                    if (!$bankTransfer->exists()) {
                        $bank =  BankAuto::create([
                            'tid' => $tid,
                            'description' => $des,
                            'amount' => $amount,
                            'user_id' => $id
                        ]);
                        if ($bank) {
                            WalletTransaction::create([
                                'user_id' => $id,
                                'change_type' => 0,
                                'transaction_type' => 0,
                                'gold' => $amount,
                                'gold_balance' => $userWallet->gold + $amount,
                                'yuan' => 0,
                                'yuan_balance' => $userWallet->yuan_balance + 0,
                                'transaction_id' => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id')
                            ]);
                            $userWallet->update([
                                'gold' => $userWallet->gold + $amount,
                                'vnd' => $userWallet->vnd + $amount
                            ]);
                            $recharge = RechargeTransaction::where('user_id', $id)
                                ->whereBetween('created_at', [
                                    Carbon::now()->startOfMonth(),
                                    Carbon::now()->lastOfMonth()
                                ])
                                ->orderBy('updated_at')
                                ->first();
                            if($recharge){
                                $resultRecharge = RechargeTransaction::create([
                                    'user_id' => $id,
                                    'code'    => $des,
                                    'vnd'     => $amount,
                                    'type'    => 1,
                                    'vnd_in_month' => $recharge->vnd_in_month +  $amount
                                ]);
                            }else {
                                $resultRecharge = RechargeTransaction::create([
                                    'user_id' => $id,
                                    'code' => $des,
                                    'vnd' => $amount,
                                    'type' => 1,
                                    'vnd_in_month' => $amount
                                ]);
                            }
                            Log::channel('bank')->info(PHP_EOL . "Bank Transfer log." . PHP_EOL, [
                                'tid' => $tid,
                                'description' => $des . "({$id})",
                                'amount' => $amount,
                                'type' => 'IN',
                            ]);
//                            $loop_index++;
                        }
                    }
                }
            }
        }
        return 0;
    }

    private function parse_order_id($des)
    {
        $re = '/NAPTIEN\d+/im';
        preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
        if (count($matches) == 0 )
            return null;
        // Print the entire match result
        $orderCode = $matches[0][0];
        $prefixLength = strlen('NAPTIEN');
        $orderId = intval(substr($orderCode, $prefixLength ));
        return $orderId ;
    }
}
