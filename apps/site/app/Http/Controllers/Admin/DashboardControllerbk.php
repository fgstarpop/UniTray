<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Domain\Page\Models\Page;
use App\Domain\Type\Models\Type;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Story\Models\Story;
use App\Domain\Admin\Models\Order;
use App\Domain\Admin\Models\GoldGift;
use App\Domain\Admin\Models\Statistics;
use App\User;
class DashboardController
{
    public function index()
    {
        $statistics = 'App\Domain\Admin\Models\Statistics';
        $rechargeTransaction = 'App\Domain\Recharge\Models\RechargeTransaction';
        $user ='App\User';
        $order = 'App\Domain\Admin\Models\Order';
        $game = 'App\Domain\Admin\Models\Game';
        $win = 'App\Domain\Admin\Models\Win';
        $story = 'App\Domain\Story\Models\Story';
        $withdrawTransaction = 'App\Domain\Admin\Models\WithdrawTransaction';

        $revenueToday = $this->calculate($statistics,'day')->first();
        if(!$revenueToday)
            $revenueToday = Statistics::create([
                'money'   =>  0,
            ]);
        $reChargeToday = $this->calculate($rechargeTransaction,'day')->where('status','1')->sum('vnd');

        $userRegisterToday  = $this->calculate($user,'day');
        $orderToday = $this->calculate($order,'day')->sum('price');
        $goldGameToday = $this->calculate($game,'day')->sum('gold');
        $winGameToday = $this->calculate($win,'day')->sum('gold');
        $storyToday = $this->calculate($story,'day');
        $withdrawToday = $this->calculate($withdrawTransaction,'day')->where('status','1')->sum('silver');
        $userVip = User::where('user_vip',1)->count();
        $orderNews = Order::latest()->limit(10)->get();
        $turnOvers = User::orderBy('turn_over','desc')->limit(10)->get();
        $goldGiftNews = GoldGift::latest()->limit(10)->get();
        $storyNews = Story::latest()->limit(10)->get();
        $pageTops = Page::orderBy('view', 'desc')->take(10)->get();

        return view('admin.dashboards.dashboard', compact('turnOvers', 'userVip', 'revenueToday','reChargeToday','userRegisterToday','orderToday','goldGameToday','winGameToday','storyToday','withdrawToday','orderNews','goldGiftNews','storyNews','pageTops'));
    }

    public function calculate($db,$type){
        $tomorrow = new Carbon('tomorrow midnight');
        $today =  new Carbon('today midnight');

        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();

        switch ( $type ){
            case 'day' :
                return $db::where('created_at','<', $tomorrow)->where('created_at','>', $today)->get();
            case 'week' :
                return $db::where('created_at','<', $endWeek)->where('created_at','>', $startWeek)->get();
            case 'month' :
                return $db::where('created_at','<', $endMonth)->where('created_at','>', $startMonth)->get();
        }
    }
    public function filter(Request $request){
        $statistics = 'App\Domain\Admin\Models\Statistics';
        $rechargeTransaction = 'App\Domain\Recharge\Models\RechargeTransaction';
        $user ='App\User';
        $order = 'App\Domain\Admin\Models\Order';
        $game = 'App\Domain\Admin\Models\Game';
        $win = 'App\Domain\Admin\Models\Win';
        $story = 'App\Domain\Story\Models\Story';
        $withdrawTransaction = 'App\Domain\Admin\Models\WithdrawTransaction';

        $revenue = $this->calculate($statistics,$request->calendar)->first();
        if(!$revenue)
            $revenue = Statistics::create([
                'money'   =>  0,
            ]);
        $reCharge = $this->calculate($rechargeTransaction,$request->calendar)->where('status','1')->sum('vnd');

        $userRegister  = $this->calculate($user,$request->calendar);
        $order = $this->calculate($order,$request->calendar)->sum('price');
        $goldGame = $this->calculate($game,$request->calendar)->sum('gold');
        $winGame = $this->calculate($win,$request->calendar)->sum('gold');
        $story = $this->calculate($story,$request->calendar);
        $withdraw = $this->calculate($withdrawTransaction,$request->calendar)->where('status','1')->sum('silver');


        return response()->json([
            'revenue'  => formatNumber($revenue->sum('money')) ,
            'reCharge' => formatNumber($reCharge),
            'userRegister' => formatNumber(count($userRegister)),
            'order' => formatNumber($order),
            'goldGame' => formatNumber($goldGame),
            'winGame'  => formatNumber($winGame),
            'story'    => formatNumber(count($story)),
            'withdraw' => formatNumber($withdraw),
        ]);

    }
}
