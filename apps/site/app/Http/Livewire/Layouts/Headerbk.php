<?php

namespace App\Http\Livewire\Layouts;

use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Ads\Models\GiftAds;
use App\Domain\Ads\Models\GiftAdsUsers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Traits\TuLuyenCfg;
use App\TuLuyen\Model_item;
use App\Traits\TuLuyenItems;
use App\Traits\TuLuyenTraits;
use App\Domain\Story\Models\Story;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Tuluyen\Gift;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use Spatie\SchemaOrg\Car;

// use Illuminate\Support\Carbon;

class Header extends Component
{
    use AuthorizesRequests;
    public $user_online;
    public $chien_bao;
    public $last_request;
    public $list_chien_bao;
    public $players;
    public $uuid_gift;
    public $has_player;
    protected $listeners = ['opengift', 'opengiftsuccess', 'giftcode', 'earnGifts', 'closeEarnPopup', 'confirmEarn', 'showEarnClick'];
    public function giftcode($gift_code)
    {
        if (!auth()->guard('web')->check()) {
            return $this->dispatchBrowserEvent(
                'swal:modal',
                [
                    'type' => 'error',
                    'title' => 'Bạn chưa đăng nhập',
                    'text' => '',
                ]
            );
        }
        $gift_code = strtoupper($gift_code);
        $user = auth()->guard('web')->user();
        $tuluyen = new TuLuyenTraits($user);
        $gift = DB::connection('dbuser')->table('players_giftcode')->where('gift', '=', $gift_code)->first();
        $gift20 = DB::connection('dbuser')->table('players_giftcode_20')->where('gift', '=', $gift_code)->first();
        if (!$gift && !$gift20) {
            return $this->dispatchBrowserEvent(
                'swal:modal',
                [
                    'type' => 'error',
                    'title' => 'Mã quà tặng không tồn tại',
                    'text' => '',
                ]
            );
        }

        if ($gift) {
            if ($gift->active == 1) {
                return $this->dispatchBrowserEvent(
                    'swal:modal',
                    [
                        'type' => 'error',
                        'title' => 'Mã quà tặng đã được sử dụng',
                        'text' => '',
                    ]
                );
            }
            DB::connection('dbuser')->table('players_giftcode')->where('gift', '=', $gift_code)->update([
                'active' => 1,
                'user_id' => $user->id,
            ]);
            $item = new tuLuyenItems($tuluyen->get_player());
            $items = $item->create_item(8, 1, 5, [15], 2);
            $this->dispatchBrowserEvent(
                'swal:modal',
                [
                    'type' => 'success',
                    'title' => 'Bạn đã được cộng 2 Tuyệt phẩm tụ khí đan',
                    'text' => '',
                ]
            );
        } elseif ($gift20) {
            if ($gift20->active == 1) {
                return $this->dispatchBrowserEvent(
                    'swal:modal',
                    [
                        'type' => 'error',
                        'title' => 'Mã quà tặng đã được sử dụng',
                        'text' => '',
                    ]
                );
            }
            DB::connection('dbuser')->table('players_giftcode_20')->where('gift', '=', $gift_code)->update([
                'active' => 1,
                'user_id' => $user->id,
            ]);
            $item = new tuLuyenItems($tuluyen->get_player());
            $items = $item->create_item(8, 1, 5, [15], 20);
            $this->dispatchBrowserEvent(
                'swal:modal',
                [
                    'type' => 'success',
                    'title' => 'Bạn đã được cộng 20 Tuyệt phẩm tụ khí đan',
                    'text' => '',
                ]
            );
        }




        $this->emit('set_player', $tuluyen->get_details(), $tuluyen->get_players_item());
    }
    public function read_chien_bao()
    {
        if (!auth()->guard('web')->check()) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }

        $template = '<div class="row" style="border-bottom: dashed 0.1px">
        <div class="col-12 pb-2">
            <p> {Date}</p>
            <div class="row">
                <div class="col-9">
                    <p
                        style="font-size:18px; font-size: 18px;
                    font-weight: bold;">
                        {title} </p>
                    {win}
                    {result}

                </div>
                <div class="col-3"><button type="button"
                        style="width: 100%;
                height: 40px;
                font-weight: 600;
                font-size: 18px; margin-bottom: 23px;"
                        class="btn btn-gt gift" data-wallet="" onclick="pkbaothu("{iddoithu}")">PK lại
                    </button>
                    <button type="button"
                        style="width: 100%;
            height: 40px;
            font-weight: 600;
            font-size: 18px;"
                        class="btn btn-gt gift" data-bs-toggle="modal"
                        onclick="chienbaoChitiet(\'{idchienbao}\')"   data-bs-target="#chienbao-details" data-wallet="">Chi tiết
                    </button>
                </div>
            </div>
        </div>
    </div>';
        $tempchienbao ="" ;
        $user = auth()->guard('web')->user();
        $players = $user->get_charaters()->first();
        $chien_bao = $user->get_chienbao()->orderByDesc("created_at")
            ->take(100)
            ->get();
        foreach ($chien_bao as $key => $value) {
            $temp = $template ;
            $timetemp = Carbon::parse($value->created_at);
            $temp = str_replace("{Date}",$timetemp->format('H:i d/m/Y'),$temp);
            if ($value->player1_id == $value->user_id) {
                $temp = str_replace("{title}",'Bạn đã tấn công "'. $value->get_users2->name.'" (ID:'."$value->player2_id)",$temp);
                $temp = str_replace("{iddoithu}",$value->player2_id,$temp);
            }else{
                $temp = str_replace("{title}",'Bạn đã phòng thủ "'. $value->get_users1->name.'" (ID:'."$value->player1_id)",$temp);
                $temp = str_replace("{iddoithu}",$value->player1_id,$temp);
            }
            if($value->win){
                $xxx = '<h2 style="color: red;"> Thắng</h2>';
                $temp = str_replace("{win}", $xxx,$temp);
            }else{
                $xxx = '<h2 style="color: rgba(164, 164, 164, 0.901);"> Thua </h2>';
                $temp = str_replace("{win}",$xxx ,$temp);
            }
            $tempresult = explode("\n\n",$value->result);
            $tempresult2 = explode("\n",$tempresult[1]);
            $temptext = "";
            foreach($tempresult2 as $key2 => $value2){
                $temptext .= '<p style="font-size:18px; font-size: 18px;">'.$value2.'</p>';
            }

            $temp = str_replace("{result}",$temptext,$temp);
            $temp = str_replace("{idchienbao}",$value->id,$temp);

            $tempchienbao  .= $temp;

        }

        $this->dispatchBrowserEvent("readallChienbao",$tempchienbao);

        if ($this->chien_bao > 0) {


            $players->update([
                'chien_bao' => 0
            ]);
            $this->chien_bao = 0;
        }

    }
    public function opengiftsuccess()
    {
    }
    public function opengift()
    {
        if (!auth()->guard('web')->check()) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $user = auth()->guard('web')->user();
        $tuluyen = new TuLuyenTraits(auth()->guard('web')->user());
        $players = $tuluyen->get_player();

        if (!$players) {
            return redirect()->route('home');
        }
        $cfg_setting = $tuluyen->get_cfg_setting();
        $time_now = Carbon::now();
        $time_last = Carbon::parse($players->time_next_collect);
        // $time  = 360 - ($players->luk * 2.7);
        // dd($time);
        $time_min = 220;
        $time_max = 900;
        $time_rand = rand($time_min - $players->luk, $time_max - ($players->luk * 6));
        $time = $time_rand < 120 ? 120 : $time_rand;
        // dd($time_last);
        if ($players->is_collect  && $time_last <= $time_now) {
            $item = new TuLuyenItems($players);
            $collect = $item->get_collect();
            // dd($collect);
            if ($collect['type'] == 15) {
                $cfg = new TuLuyenCfg();
                $rand_lt = $cfg->random_weight([1 => 95, 2 => 4, 3 => 1]);
                $op[] = 'Linh thạch: +' . $rand_lt;
                $array_item = [
                    'name' => 'Linh thạch',
                    'info' => "Bạn đang trên đường đi tầm bảo vấp linh thạch do tên nào rớt lại té xấp mặt. Bớ bà con ra đây coi âu hoàng nè (゜▽゜;)",
                    'img' => asset("asset/game/item/linhthach.png"),
                    'type_name' => 'Linh Thạch',
                    'color' => '#ffff',
                    'options' => $op,
                ];

                $players->update([
                    'linh_thach' => $players->linh_thach + $rand_lt,
                    'is_collect' => false,
                    'time_next_collect' => $time_now->copy()->addSeconds($time),
                    'time_last_collect' => $time_now,
                ]);

                $this->dispatchBrowserEvent('opengiftsuccess', [
                    'item' => $array_item,



                ]);
                $this->emit('set_player', $tuluyen->get_details(), $tuluyen->get_players_item());
                return;
            }

            $model_item = Model_item::find($collect['id']);

            $array_op = [];
            foreach ($model_item->get_options as $key => $op) {
                $temparray = [];
                if ($op->is_mul) {
                    $temparray['value'] = $op->op->value * 100;
                } else {
                    $temparray['value'] = $op->op->value;
                }

                if ($op->is_mp) {
                    $temparray['name'] = sprintf($op->name, $temparray['value'], $item->get_mp_name($players->class));
                } else {
                    $temparray['name'] = sprintf($op->name, $temparray['value']);
                }
                $array_op[] = $temparray['name'];
            }


            $array_item = [
                'name' => $collect['name'],
                'info' => $collect['info'],
                'img' => asset("asset/game/item/$collect[type]/$collect[item_id].png"),
                'type_name' => $item->get_item_type_name($collect['type']),
                'color' => $item->get_item_color($collect['type'], $collect['rare']),
                'options' => $array_op,
            ];

            $players->update([
                'is_collect' => false,
                'time_next_collect' => $time_now->copy()->addSeconds($time),
                'time_last_collect' => $time_now,
            ]);

            $this->dispatchBrowserEvent('opengiftsuccess', [
                'item' => $array_item,



            ]);
            $this->emit('set_player', $tuluyen->get_details(), $tuluyen->get_players_item());
        } else {

            return  $this->dispatchBrowserEvent('swal:modal', [
                'icon' => 'error',
                'title' => 'Bạn chưa đến thời gian thu thập',
                'text' => 'Bạn cần chờ thêm ' . $time_last->diffInSeconds($time_now) . ' giây nữa',
            ]);
        }

        // $this->emit('open_gift');
    }
    public function online()
    {
        // echo ($this->chien_bao);
        if (!auth()->guard('web')->check()) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        // $this->user_online = auth()->guard('web')->user();
        $tuluyen = new TuLuyenTraits(auth()->guard('web')->user());

        $players = $tuluyen->online();

        if (!$players) {
            return redirect()->route('home');
        }
        $this->chien_bao = $players->chien_bao;
        $time_now = Carbon::now();
        $time_last = Carbon::parse($this->last_request);
        $cfg_setting = $tuluyen->get_cfg_setting();
        $time_min_online = array_key_exists('min_online', $cfg_setting) ? $cfg_setting['min_online'] :  60;

        if ($players->is_collect && !$players->is_auto) {
            $random_right = rand(10, 300);
            $random_bottom = rand(10, 300);
            $this->dispatchBrowserEvent('opengift', [

                'text' => "<img class=\"img\" src=\"" . asset('asset/game/gift.png') . "\"
                style=\"z-index:999; cursor: pointer;     position:fixed;  bottom: 10px; width: 50px;animation: shake 2s infinite;   height:50px;\" onclick=\"clickopengift(this)\" >"
            ]);
        }
        if ($time_last->addSeconds($time_min_online) <= $time_now) {

            $this->last_request = $players->time_last_online;
            // dd($players->last_online);
            $this->emit('set_player', $tuluyen->get_details(), $tuluyen->get_players_item());
        }
    }

    public function showPopupEarn()
    {
        $content = 0;
        $currentTime = Carbon::now()->format('i');
        if (($currentTime > 5 && $currentTime <= 30) || $currentTime > 35) {
            if ($currentTime < 30 && $currentTime > 5) {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:00:00'),
                    Carbon::now()->format('Y-m-d H:05:00')
                ];
            } else {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:30:00'),
                    Carbon::now()->format('Y-m-d H:35:00')
                ];
            }
            $currentEvent = GiftAdsUsers::where('user_id', currentUser()->id)->whereBetween('created_at', $currentTime);
            if ($currentEvent->exists()) {
                if ($currentEvent->first()->earned == 0) {
                    $content = 1;
                }
            }
        }
        $currentTime = Carbon::now()->format('i');
        if (($currentTime >= 0 && $currentTime <= 5) || ($currentTime >= 30 && $currentTime <= 35)) {
            if (Carbon::now()->format('i') < 30) {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:00:00'),
                    Carbon::now()->format('Y-m-d H:05:00')
                ];
            } else {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:30:00'),
                    Carbon::now()->format('Y-m-d H:35:00')
                ];
            }

            $currentEvent = GiftAds::whereBetween('created_at', $currentTime);
            if (!$currentEvent->exists()) {
                $userVips = User::where('user_vip', 1)->get();
                $currentEvent = GiftAds::create([
                    'sum_joined' => count($userVips),
                    'gold' => 3000 * ((int)((count($userVips) - 1) / 10) + 1),
                    'stone' => 30 * ((int)((count($userVips) - 1) / 10) + 1)
                ]);
                foreach ($userVips as $userVip) {
                    GiftAdsUsers::create([
                        'user_id' => $userVip->id,
                        'gift_ads_id' => $currentEvent->id,
                    ]);
                }
            }
            $this->dispatchBrowserEvent('showEarnClick', [
                'show' => 1
            ]);
        }

        $this->dispatchBrowserEvent('earnGifts', [
            'text' => $content
        ]);
    }

    public function confirmEarn()
    {
        $currentTime = Carbon::now()->format('i');
        if ($currentTime <= 35 && $currentTime >= 5) {
            $currentTime = [
                Carbon::now()->format('Y-m-d H:00:00'),
                Carbon::now()->format('Y-m-d H:05:00')
            ];
        } else {
            $currentTime = [
                Carbon::now()->format('Y-m-d H:30:00'),
                Carbon::now()->format('Y-m-d H:35:00')
            ];
        }
        $currentEvent = GiftAdsUsers::where('user_id', currentUser()->id)->whereBetween('created_at', $currentTime);
        if ($currentEvent->exists()) {
            if ($currentEvent->first()->earned == 0) {
                //Earn
                $player = new TuLuyenTraits(currentUser());
                $event = $currentEvent->first()->giftAds()->first();
                $existGold = $event->gold;
                $existStone = $event->stone;
                $randGold = rand(1, 200);
                $randStone = rand(4, 10);
                if ((empty($player->get_details()) || rand(1, 2) == 1) && currentUser()->user_vip == 0) {
                    $currentWallet = Wallet::where('user_id', currentUser()->id)->first();
                    WalletTransaction::create([
                        'user_id' => currentUser()->id,
                        'change_type' => 0,
                        'transaction_type' => 8,
                        'gold' => $randGold,
                        'gold_balance' => (int)$currentWallet->gold + $randGold,
                        'yuan' => 0,
                        'yuan_balance' => (int)$currentWallet->yuan,
                        'transaction_id' => randCode('App\Domain\Admin\Models\WalletTransaction', 'transaction_id')
                    ]);
                    $currentWallet->update([
                        'gold' => (int)$currentWallet->gold + $randGold
                    ]);
                    $this->dispatchBrowserEvent('swal:modal', [
                        'icon' => 'success',
                        'title' => 'Nhận lì xì thành công.',
                        'text' => 'Bạn nhận được ' . $randGold . ' vàng.',
                    ]);
                    $existGold -= $randGold;
                } else {
                    $item = new TuLuyenItems($player);
                    $collect = $item->get_collect_with_rare(4, $randStone);
                    $this->emit('set_player', $player->get_details(), $player->get_players_item());
                    $existStone -= $randStone;
                    $this->dispatchBrowserEvent('swal:modal', [
                        'icon' => 'success',
                        'title' => 'Nhận lì xì thành công.',
                        'text' => 'Bạn nhận được ' . $randStone . ' viên cực phẩm đan dược bất kỳ.',
                    ]);
                }
                $currentEvent->update([
                    'earned' => 1
                ]);
                $event->update([
                    'gold' => $existGold,
                    'stone' => $existStone
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'icon' => 'error',
                    'title' => 'Nhận lì xì không thành công.',
                    'text' => 'Bạn nhận lì xì.',
                ]);
            }
        }
        $this->dispatchBrowserEvent('closeEarnPopup', [
            'close' => 1
        ]);
    }

    public function earnGifts(Request $request)
    {
        $currentTime = Carbon::now()->format('i');
        if (($currentTime >= 0 && $currentTime <= 5) || ($currentTime >= 30 && $currentTime <= 35)) {
            if (Carbon::now()->format('i') < 30) {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:00:00'),
                    Carbon::now()->format('Y-m-d H:05:00')
                ];
            } else {
                $currentTime = [
                    Carbon::now()->format('Y-m-d H:30:00'),
                    Carbon::now()->format('Y-m-d H:35:00')
                ];
            }

            $currentEvent = GiftAds::whereBetween('created_at', $currentTime);
            if ($currentEvent->exists()) {
                $currentEvent = $currentEvent->first();
            } else {
                $userVips = User::where('user_vip', 1)->get();
                $currentEvent = GiftAds::create([
                    'sum_joined' => count($userVips)
                ]);
                foreach ($userVips as $userVip) {
                    GiftAdsUsers::create([
                        'user_id' => $userVip->id,
                        'gift_ads_id' => $currentEvent->id,
                    ]);
                }
            }

            $currentEarnGift = GiftAdsUsers::where('ip', $request->ip())
                ->whereBetween('created_at', $currentTime);
            if (!$currentEarnGift->exists()) {
                $currentEvent->update([
                    'sum_joined' => $currentEvent->sum_joined + 1,
                    'gold' => 3000 * ((int)($currentEvent->sum_joined / 10) + 1),
                    'stone' => 30 * ((int)($currentEvent->sum_joined / 10) + 1)
                ]);
                GiftAdsUsers::create([
                    'gift_ads_id' => $currentEvent->id,
                    'user_id' => currentUser()->id,
                    'ip' => $request->ip()
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.layouts.header');
    }
}
