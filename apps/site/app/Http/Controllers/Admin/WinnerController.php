<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\WinDataTable; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Domain\Admin\Models\Win;
use App\Domain\Admin\Models\Wallet;
use Carbon\Carbon;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Admin\Models\Statistics;
class WinnerController
{
    use AuthorizesRequests;

    public function index(WinDataTable $dataTable)
    {
        $this->authorize('view', Win::class);
        return $dataTable->render('admin.win.index');
    }

    
    public function store(Request $request)
    {
        $money = 0 ;
        $user =[];
        $tomorrow = new Carbon('tomorrow midnight');
        $timeSecond = new Carbon('18:40:20');
        $today =  new Carbon('today midnight');
        $winner = Win::where('number', $request->number)->where('created_at','<', $timeSecond)->where('created_at','>', $today)->get();
        foreach($winner as $list){
            $wallet = Wallet::where('user_id',$list->user_id)->first();
            $wallet->gold = $wallet->gold + $list->gold * 70;
            $wallet->updated_at = Carbon::now();
            $wallet->save();

            $money = $money + $list->gold * 70;
            array_push($user,$winner->user_id);

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
        }

        Win::create([
            'number'   => $request->number,
            'money'    => $money,
            'winner'   => json_encode($user),
        ]);

        // $statistics = Statistics::where('created_at','<', $timeSecond)->where('created_at','>', $today)->first();
        // if($statistics){
        //     $statistics->money = $statistics->money - $money ;
        //     $statistics->save();
        // }else{
        //     Statistics::create([
        //         'money'   => -$money,
        //     ]);
        // }
    }

  
}
