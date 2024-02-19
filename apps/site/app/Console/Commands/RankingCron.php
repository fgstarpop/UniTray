<?php

namespace App\Console\Commands;

use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use App\Traits\TuLuyenItems;
use App\Traits\TuLuyenTraits;
use App\TuLuyen\Model_charater;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RankingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranking-cron';

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

    const TOP_DEDICATION = 'top_dedication';

    const TOP_GOLD = 'top_gold';

    const TOP_TRAINING = 'top_training';

    const TOP_MASTER = 'top_master';

    const TOP_ATK = 'top_atk';

    const TOP_DEF = 'top_def';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Top dedication
        $currentTopDedication = User::select( 'users.*', 'w.user_id', DB::raw('sum(w.gold) as total'))
            ->join('wallet_transactions as w', 'w.user_id', 'users.id')
            ->whereBetween('w.created_at', [
                Carbon::now()->startOfMonth()->subMonth(2)->format('Y-m-28'),
                Carbon::now()->startOfMonth()->subMonth()->format('Y-m-27'),
            ])->groupBY('w.user_id')
            ->orderByRaw('SUM(w.gold) DESC')
            ->limit(20)
            ->get();
        $this->setGiftForTop(self::TOP_DEDICATION, $currentTopDedication);

        //Exp
        $playersExp = User::select('users.*', 'p.level', 'p.exp', 'p.user_id')
            ->join('players_charater as p', 'users.id', 'p.user_id')
            ->orderBy('p.level', 'DESC')
            ->orderBy('p.exp', 'DESC')
            ->limit(20)
            ->get();
        $this->setGiftForTop(self::TOP_TRAINING, $playersExp);

        //ATK
        $playersAtk = User::select('users.*', 'c.user_id', DB::raw('(c.atk + c.sum_atk) as total_atk'))
            ->join('players_charater as c', 'c.user_id', 'users.id')
            ->orderByRaw('total_atk DESC')
            ->limit(20)
            ->get();
        $this->setGiftForTop(self::TOP_ATK, $playersAtk);

        //DEF
        $playersDef = User::select('users.*', 'c.user_id', DB::raw('(c.def + c.sum_def) as total_def'))
            ->join('players_charater as c', 'c.user_id', 'users.id')
            ->orderByRaw('total_def DESC')
            ->limit(20)
            ->get();
        $this->setGiftForTop(self::TOP_DEF, $playersDef);

        return 0;
    }

    private function setGiftForTop($type, $list)
    {
        if (!empty($list)) {
            foreach ($list as $key => $item) {
                if ($key <= 2) {
                    if ($key == 0) {
                        //Cap huy hieu
                        $currentTop = User::where($type, 1)->first();
                        if ($currentTop) {
                            $currentTop->update([
                                $type => 0
                            ]);
                        }
                        $userTop = User::find($item->user_id);
                        $userTop->update([
                            $type => 1
                        ]);
                        //Cap vang
                        $userWallet = Wallet::where('user_id', $item->user_id)->first();
                        WalletTransaction::create([
                            'user_id' => $item->user_id,
                            'change_type' => 0,
                            'transaction_type' => 9,
                            'gold' => 50000,
                            'gold_balance' => $userWallet->gold + 50000,
                            'yuan' => 0,
                            'yuan_balance' => $userWallet->yuan_balance + 0,
                            'transaction_id' => randCode('App\Domain\Admin\Models\WalletTransaction','transaction_id')
                        ]);
                        $userWallet->update([
                            'gold' => $userWallet->gold + 50000,
                            'vnd' => $userWallet->vnd + 50000
                        ]);
                    }
                    //Cap dan
                    $player = new TuLuyenTraits($item);
                    if (!empty($player->get_details())) {
                        $playerItems = new TuLuyenItems($player);
                        $playerItems->get_collect_with_rare(4, $this->calcStone(2));
                    }
                } elseif ($key >= 3 && $key <= 9) {
                    $player = new TuLuyenTraits($item);
                    if (!empty($player->get_details())) {
                        $playerItems = new TuLuyenItems($player);
                        $playerItems->get_collect_with_rare(4, $this->calcStone(1));
                    }
                } else {
                    $player = new TuLuyenTraits($item);
                    if (!empty($player->get_details())) {
                        $playerItems = new TuLuyenItems($player);
                        $playerItems->get_collect_with_rare(4, $this->calcStone(0.5));
                    }
                }
            }
        }
    }

    private function calcStone($quantityPerDay)
    {
        $dayOfSubMonth = Carbon::now()->subMonth()->endOfMonth()->format('d');
        return (int)$quantityPerDay * $dayOfSubMonth;
    }
}
