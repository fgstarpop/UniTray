<?php

namespace App\Http\Controllers\User;

use App\Traits\TuLuyenTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\EditRequest;
use App\User;
use Illuminate\Support\Facades\Validator;
use Str;
use App\Domain\Admin\Models\GoldGift;
use App\Domain\Admin\Models\Donate;
use App\Domain\Admin\Models\Statistics;
use Carbon\Carbon;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Comment\Comment;
use App\Domain\Story\Models\Story;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Activity\Readed;
use App\Domain\Activity\Notification;
use DB;

class AccountController
{
    public function index($user)
    {
        $users = User::findOrFail($user);
        if ($users->is_vip == \App\Enums\UserType::Normal)
            $storyUser  = Story::where('user_id', $users->is_vip)->where('type', '1')->with(['user', 'media'])->withCount('chapters')->orderBy('updated_at', 'DESC')->paginate(20);
        else
            $storyUser = Story::where('mod_id', $users->id)->with(['user', 'media'])->withCount('chapters')->orderBy('updated_at', 'DESC')->paginate(20);
        $comment = Comment::where([
            'commentable_type' => 'App/user',
            'commentable_id'  => $user
        ])
            ->with('users', 'children')
            ->orderBy('created_at', 'desc')
            ->get();
        $wallet = Wallet::where(['user_id' => $users->id])->first();
        $gold = $wallet->gold;

        return view('shop.user.profile', compact('users', 'comment', 'storyUser','gold'));
    }

    public function wallet()
    {
        $wallet = Wallet::where(['user_id' => currentUser()->id])->first();
        $transaction = WalletTransaction::where(['user_id' => currentUser()->id])->orderBy('id', 'desc')->paginate(50);

        return view('shop.user.wallet', compact('wallet', 'transaction'));
    }

    public function change(PasswordRequest $request)
    {
        $currentUser = currentUser();
        $currentUser->password = bcrypt($request->input('password'));
        $currentUser->save();
        return response()->json([
            'status' => true,
            'message' => 'Thay đổi mật khẩu thành công!'
        ]);
    }
    public function edit($user)
    {
        $users = User::findOrFail($user);
        return view('shop.user.edit', compact('users'));
    }
    public function update(EditRequest $request)
    {
        try {
            \DB::transaction(function () use ($request) {
                $currentUser = currentUser();
                $currentUser->name = $request->name;
                $currentUser->email = $request->email;
                $currentUser->bio = $request->bio;
                $currentUser->dob = $request->dob;
                if ($request->hasFile('file')) {
                    $image = upload_image('file', 'user');
                    if ($image['code'] == 1) {
                        $currentUser->avatar = $image['name'];
                    }
                }
                if ($request->password) {
                    $currentUser->password = bcrypt($request->input('password'));
                }
                $currentUser->save();
                flash()->success(__('Thay đổi thông tin thành công'));
            });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
        return redirect()->route('user.index', currentUser()->id);
    }
    public function user(Request $request)
    {
        $user = User::where('id', '!=', currentUser()->id)->where('name', 'like', '%' . $request->user . '%')->get();
        if (!$user->isEmpty())
            return view('shop.user.user', compact('user'))->render();
        else {
            return 0;
        }
    }
    public function gift()
    {

        $user = User::where('id', '!=', currentUser()->id)->get();
        // dd($user);
        $gift = GoldGift::where('user_id', currentUser()->id)->orWhere('received_id', currentUser()->id)->orderBy('id', 'desc')->with('user')->paginate(50);
        // dd($gift);
        foreach ($gift as $list) {
            if ($list->user_id == currentUser()->id)
                $list->type = 0;
            else
                $list->type = 1;
        }
        $wallet = Wallet::where('user_id', currentUser()->id)->first();
        return view('shop.user.gift', compact('gift', 'user', 'wallet'));
    }
    public function gold_gift(Request $request)
    {
        if (currentUser()->id != 4) {
            return response()->json([
                'status' => '300',
                'message' => 'Tạm khoá chức năng',
            ]);
        }
        $user = User::find($request->user_id);
        $wallet = Wallet::where('user_id', currentUser()->id)->first();
        $gold = round($request->gold *  setting('gold_donation_fee', 0) / 100);
        if (!$user) {
            return response()->json([
                'status' => '300',
                'message' => 'Không có người dùng trên hệ thống',
            ]);
        }
        if (!$request->gold) {
            return response()->json([
                'status' => '300',
                'message' => 'Số vàng không được để trống',
            ]);
        }
        if ($request->gold < 0) {
            return response()->json([
                'status' => '300',
                'message' => 'Số vàng phải lớn hơn 0',
            ]);
        }
        if (!is_numeric($request->gold)) {
            return response()->json([
                'status' => '300',
                'message' => 'Số vàng phải là số',
            ]);
        }
        if ($request->gold + $gold > $wallet->gold) {
            return response()->json([
                'status' => '300',
                'message' => 'Bạn không đủ vàng để tặng',
            ]);
        }
        try {
            \DB::transaction(function () use ($request, $user, $wallet, $gold) {
                $tomorrow = new Carbon('tomorrow midnight');
                $today =  new Carbon('today midnight');

                $walletOther = Wallet::where('user_id', $user->id)->first();

                GoldGift::create([
                    'user_id'       =>  currentUser()->id,
                    'gold'          =>  $request->gold,
                    'received_id'   =>  $user->id,
                    'created_at'    =>  Carbon::now()
                ]);
                $text = currentUser()->name . " tặng bạn " . $request->gold . " vàng";
                $link = route('user.gold-gift');
                Notification::create([
                    'id'                =>  Str::uuid(),
                    'type'              =>  'gift',
                    'notifiable_type'   =>  'App\User',
                    'data'              =>  $text,
                    'notifiable_id'     =>  $user->id,
                    'read_at'           =>  new Carbon('first day of January 2018'),
                    'story_id'          =>  0,
                    'count'             =>  1,
                    'link'              =>  $link
                ]);
                $transactionCode = randCode('App\Domain\Admin\Models\WalletTransaction', 'transaction_id');

                WalletTransaction::create([
                    'transaction_id'    => $transactionCode,
                    'user_id'           => currentUser()->id,
                    'change_type'       => 1,
                    'transaction_type'  => 2,
                    'created_at'        => Carbon::now(),
                    'gold'              => $request->gold + $gold,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold - $request->gold - $gold,
                    'yuan_balance'      => $wallet->silver,
                ]);
                WalletTransaction::create([
                    'transaction_id'    => $transactionCode,
                    'user_id'           => $user->id,
                    'change_type'       => 0,
                    'transaction_type'  => 2,
                    'created_at'        => Carbon::now(),
                    'gold'              => $request->gold,
                    'yuan'              => 0,
                    'gold_balance'      => $walletOther->gold + $request->gold,
                    'yuan_balance'      => $walletOther->silver,
                ]);
                $wallet->gold = $wallet->gold - $request->gold - $gold;
                $walletOther->gold = $walletOther->gold + $request->gold;
                $wallet->update();
                $walletOther->update();
                $statistics = Statistics::where('created_at', '<', $tomorrow)->where('created_at', '>', $today)->first();
                if ($statistics) {
                    $statistics->money = $statistics->money + $gold;
                    $statistics->save();
                } else {
                    Statistics::create([
                        'money'   =>  $gold,
                    ]);
                }
                logActivity($wallet, 'create'); // log activity
            });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => '200',
            'message' => 'Tặng vàng thành công',
        ]);
    }
    public function donate(Request $request)
    {
        $wallet = Wallet::where('user_id', currentUser()->id)->first();
        if ($request->gold != 1.8) {
            if (empty($request->gold)) {
                return response()->json([
                    'status' => '300',
                    'message' => 'Số vàng không được để trống',
                ]);
            }
            if ($request->gold < 0) {
                return response()->json([
                    'status' => '300',
                    'message' => 'Số vàng phải lớn hơn 0',
                ]);
            }
            if (!is_numeric($request->gold)) {
                return response()->json([
                    'status' => '300',
                    'message' => 'Số vàng phải là số',
                ]);
            }
            if ($request->gold > $wallet->gold) {
                return response()->json([
                    'status' => '300',
                    'message' => 'Bạn không đủ vàng để yêu cầu',
                ]);
            }
        }
        try {
            \DB::transaction(function () use ($request, $wallet) {
                $received = $request->received;
                $gold = $request->gold;

                //   $gold = $request->gold / 2;
                $tomorrow = new Carbon('tomorrow midnight');
                $today =  new Carbon('today midnight');
                $statistics = Statistics::where('created_at', '<', $tomorrow)->where('created_at', '>', $today)->first();
                if ($statistics) {
                    $statistics->money = $statistics->money + $gold;
                    $statistics->save();
                } else {
                    Statistics::create([
                        'money'   =>  $gold,
                    ]);
                }
                $wallet->gold = $wallet->gold - $request->gold;
                $wallet->update();

                $tranCode = randCode('App\Domain\Admin\Models\WalletTransaction', 'transaction_id');
                WalletTransaction::create([
                    'transaction_id'    => $tranCode,
                    'user_id'           => currentUser()->id,
                    'change_type'       => 1,
                    'transaction_type'  => 3,
                    'created_at'        => Carbon::now(),
                    'gold'              => $request->gold,
                    'yuan'              => 0,
                    'gold_balance'      => $wallet->gold,
                    'yuan_balance'      => $wallet->silver,
                ]);

                //   người nhận sẽ được ghi lịch sử giao dịch ví là nhận donate
                if ($received) {
                    $walletAuthor = Wallet::where('user_id', $received)->first();
                    $walletAuthor->silver = $walletAuthor->silver + $request->num / 2;
                    $walletAuthor->update();
                    $transaction = WalletTransaction::create([
                        'transaction_id'    => $tranCode,
                        'user_id'           => $received,
                        'change_type'       => 0,
                        'transaction_type'  => 3,
                        'created_at'        => Carbon::now(),
                        'gold'              => 0,
                        'yuan'              => $request->num / 2,
                        'gold_balance'      => $walletAuthor->gold,
                        'yuan_balance'      => $walletAuthor->silver,
                    ]);
                    $story = Story::find($request->story);
                    $text = "Truyện " . $story->name . " có " . $request->num . " vàng thưởng từ user";
                    $link = route('story.show', $story);
                    $noti = Notification::where([
                        'type'          => 'donate',
                        'notifiable_id'   => $received,
                        'story_id'        => $story->id,
                    ])->orderBy('updated_at', 'desc')->first();
                    if (isset($noti)) {
                        $noti->count = $noti->count + 1;
                        $noti->read_at = new Carbon('first day of January 2018');
                        $noti->data = "Truyện " . $story->name . " có " . $request->num . " vàng thưởng từ user";
                        $noti->update();
                    } else {
                        Notification::create([
                            'id'        =>  \Str::uuid(),
                            'type'      => 'donate',
                            'notifiable_type' => 'App\User',
                            'data'      => $text,
                            'read_at'   => new Carbon('first day of January 2018'),
                            'notifiable_id' =>  $received,
                            'story_id'  => $story->id,
                            'count'     => 1,
                            'link'      => $link
                        ]);
                    }
                }
                Donate::create([
                    'user_id'       =>  currentUser()->id,
                    'gold'          =>  $request->gold,
                    'received_id'   =>  $received,
                    'stories_id'    =>  $request->story,
                    'created_at'    =>  Carbon::now()
                ]);
                $story = Story::find($request->story);
                $story->donate = $story->donate + $request->num;
                $mod = User::find($story->mod_id);
                $nominationCalculate = $request->num / 10;
                $story->nomination = $story->nomination + $nominationCalculate;
                $turn_over_donate = $request->num / 2;
                $mod->turn_over = $mod->turn_over + $turn_over_donate;
                $story->audio_month = $story->audio_month + $turn_over_donate;
                $mod->save();
                $story->update();

                logActivity($story, 'create'); // log activity
            });
        } catch (\Exception $e) {
            return back()->with(['message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => '200',
            'message' => 'Donate thành công',
        ]);
    }

    public function upgradeVip()
    {
        $vipPrice = 2000;
        if(!Auth::guard('web')->check()){
            flash()->error('Bạn cần đăng nhập để sử dụng tính năng này');
            return redirect()->route('login');
        }

        $user = Auth::guard('web')->user();

        if ($user->user_vip == 1) {
            flash()->warning('Bạn đã đăng ký VIP!');
            return redirect()->route('home');
        }

        if (empty($user->get_charaters)) {
            flash()->error('Bạn chưa tạo nhân vật.');
            return redirect()->route('tuluyen.create');
        }

        //Check if not enough linh thach
        $players = $user->get_charaters;
        $linh_thach = $players->linh_thach;
        if ($linh_thach < $vipPrice) {
            flash()->error('Bạn chưa đủ linh thạch!');
        } else {
            try {
                $players->update([
                    'linh_thach' => $linh_thach - $vipPrice
                ]);
                $user = currentUser();
                $user->user_vip = 1;
                $user->vip_registration_date = Carbon::now();
                $user->vip_expired_date = Carbon::now()->addMonth();
                $user->save();
            } catch (\Exception $e) {
                flash()->error($e->getMessage());
            }

            flash()->success('Chúc mừng bạn đã đăng ký VIP thành công.');
        }
        return redirect()->route('vip');
    }

    public function transformStone(Request $request)
    {
        $user = auth()->guard('web')->user();
        if (empty($user)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Đổi linh thạch thất bại',
                'text' => "Bạn chưa đăng nhập.",
            ]);
        }
//
//        if ($user->get_characters) {
//            return response()->json([
//                'icon' => 'error',
//                'title' => 'Đổi linh thạch thất bại',
//                'text' => "Bạn chưa tạo nhân vật.",
//            ]);
//        }

        $tuluyen = new TuLuyenTraits($user);

        $gold = $user->get_gold->gold;
        $stone = (int)$request->input('linhthach');
        $total = $stone * 50;
        if ($total > $gold) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Đổi linh thạch thất bại',
                'text' => "Bạn không đủ vàng để đổi linh thạch",
            ]);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $tuluyen, $total, $stone) {
                $user->get_gold->gold -= $total;
                $user->get_gold->save();

                $nowtime = Carbon::now();

                WalletTransaction::create([
                    'transaction_id'    => \Illuminate\Support\Str::uuid(),
                    'user_id'           => $user->id,
                    'change_type'       => 1,
                    'transaction_type'  => 7,
                    'created_at'        => $nowtime,
                    'gold'              => $total,
                    'yuan'              => 0,
                    'gold_balance'      => $user->get_gold->gold,
                    'yuan_balance'      => $user->get_gold->silver,
                ]);
                $tuluyen->add_linh_thach($stone + $tuluyen->get_cfg()['doilinhthach'][$stone]['bonus']);

            });
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Đổi linh thạch thất bại',
                'text' => "Quá trình đổi thất bại, sẽ không trừ vàng của bạn.",
            ]);
        }



        $this->players = $tuluyen->get_details();
        $this->gold = $user->get_gold->gold;
        if($total >= 1000){
            if($user->user_vip == 1){
                $tuvi = (floor ($total / 1000)*50) + (((floor ($total / 1000)*50)*10)/100);
            }else{
                $tuvi = floor ($total / 1000)*50;
            }
            $exp = $tuluyen->add_tuvi($tuvi);
        }else{
            $exp = 0;
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'Đổi linh thạch thành công',
            'text' => "Bạn đổi $total vàng thành $stone linh thạch. Bạn được nhận được $exp tu vi. Tu vi nhận được dựa trên căn cốt.",
        ]);
    }

    public function createCharacter(Request $request)
    {
        $request->validate([
            'p_class' => 'required',
            'p_gioitinh' => 'required'
        ] , [
            'p_gioitinh.required' => 'Bạn chưa chọn giới tính'
        ]);
        $tuluyen = new TuLuyenTraits(auth()->guard('web')->user());
        $check = $tuluyen->create_charater($request->input('p_class'), $request->input('p_gioitinh'));
        if ($check) {
            flash()->success('Tạo nhân vật thành công');
            return response()->route('tuluyen.index');
        } else {
            flash()->error('Tạo nhân vật thất bại');
            return response()->back();
        }
    }
}
