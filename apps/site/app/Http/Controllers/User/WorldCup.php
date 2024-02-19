<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\WorldCupMatch;
use Illuminate\Support\Str;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Domain\Admin\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Redis;
use App\Domain\Admin\Models\WalletTransaction;

class WorldCup
{
    public function admin_index()
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }
        if (currentUser()->id != 4 && currentUser()->id != 43) {
            flash()->error("Không đủ quyền");
            return redirect()->route('home');
        }

        $match = DB::table('worldcups', 'wc')
        ->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')
        ->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->orderBy('id', 'desc')
            ->get([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);


        return view('shop.user.worldcup.index', [
            'matchs' => $match,
        ]);
    }




    public function admin_create()
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }
        if (currentUser()->id != 4 && currentUser()->id != 43) {
            flash()->error("Không đủ quyền");
            return redirect()->route('home');
        }

        $list_teams = DB::table('worldcupteams')->get();
        return view('shop.user.worldcup.create', [
            'list_teams' => $list_teams,
        ]);
    }



    public function admin_store(Request $request)
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }

        if (currentUser()->id != 4 && currentUser()->id != 43) {
            flash()->error("Không đủ quyền");
            return redirect()->route('home');
        }

        $id_one = $request->wc_team_1;
        $id_two = $request->wc_team_2;
        $tien_1 = $request->rate_one;
        $tien_2 = $request->rate_two;
        $keo = $request->set_keo;
        $keo_text = $keo;
        $time = $request->time_start;
        $keo_tren = 0;
        if (empty($id_one)) {
            flash()->error("Chưa chọn đội 1");
            return redirect()->back();
        } elseif (empty($id_two)) {
            flash()->error("Chưa chọn đội 2");
            return redirect()->back();
        }
        if ($request->h_one && $request->h_two) {
            flash()->error("Chỉ được chọn 1 trong 2 đội là kèo trên");
            return redirect()->back();
        } elseif ($request->h_one) {
            $keo_tren = $id_one;
        } elseif ($request->h_two) {
            $keo_tren = $id_two;
        } elseif (!$request->h_one && !$request->h_two && $keo != '0') {
            flash()->error("Vui lòng chọn đội kèo trên");
            return redirect()->back();
        }

        if ($id_one == $id_two) {
            flash()->error("Đội 1 và đội 2 giống nhau");
            return redirect()->back();
        }
        $check_keo = Str::of($keo)->contains('/');
        if ($check_keo) {
            $keo = explode('/', $keo);
            $keo = ($keo[1] + $keo[0]) / 2;
        } else {
            $keo_text = null;
            $keo =  $keo;
        }




        $data = DB::table('worldcups')->insert([
            'uuid' => Str::uuid(),
            'id_team_one' => $id_one,
            'id_team_two' => $id_two,
            'rate_team_one' => $tien_1,
            'rate_team_two' => $tien_2,
            'keo' => $keo,
            'id_keo_tren' => $keo_tren,
            'time_start' => $time,
            'keo_text' => $keo_text,

        ]);
        flash()->success("Tạo trận đấu để đặt cược thành công");
        return redirect()->route('wcadmin');
    }




    public function admin_edit($uuid)
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }
        if (currentUser()->id != 4 && currentUser()->id != 43) {
            flash()->error("Không đủ quyền");
            return redirect()->route('home');
        }

        $match = DB::table('worldcups', 'wc')->where('uuid', '=', $uuid)->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->first([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);

        if (!$match) {
            flash()->error("Không đúng uuid");
            return redirect()->route('wcadmin');
        }
        $list_teams = DB::table('worldcupteams')->get();
        return view('shop.user.worldcup.edit', [
            'match' => $match,
            'list_teams' => $list_teams,
        ]);
    }




    public function admin_edit_store($uuid, Request $request)
    {

        if (!Auth::guard('web')->check()) {
            flash()->success("Chưa đăng nhập");
            return redirect()->route('home');
        }
        if (currentUser()->id != 4 && currentUser()->id != 43) {
            flash()->error("Không đủ quyền");
            return redirect()->route('home');
        }
        $match = DB::table('worldcups', 'wc')->where('uuid', '=', $uuid)->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->first([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);



        if (!$match) {
            flash()->error("Không đúng uuid");
            return redirect()->route('wcadmin');
        }
        $list_teams = DB::table('worldcupteams')->get();
        $id_one = $request->wc_team_1;
        $id_two = $request->wc_team_2;
        $tien_1 = $request->rate_one;
        $tien_2 = $request->rate_two;
        $keo = $request->set_keo;
        $keo_text = $keo;
        $time = $request->time_start;
        $keo_tren = 0;
        $ty_so_1 = $request->ty_so_1;
        $ty_so_2 = $request->ty_so_2;
        $chot_so = $request->chot_so;
        $lock = 0;
        // $test = $ty_so_1 - $ty_so_2;
        if ($request->h_one && $request->h_two) {
            flash()->error("Chỉ được chọn 1 trong 2 đội là kèo trên");
            return redirect()->back();
        } elseif ($request->h_one) {
            $keo_tren = $id_one;
        } elseif ($request->h_two) {
            $keo_tren = $id_two;
        } elseif (!$request->h_one && !$request->h_two && $keo != 0) {
            flash()->error("Vui lòng chọn đội kèo trên");
            return redirect()->back();
        }
        if($request->lock){
            $lock = 1;
        }
        if ($chot_so) {
            try {
                DB::transaction(function () use ($match, $uuid, $ty_so_1, $ty_so_2) {
                    $array_tyso = [
                        $match->id_team_one => $ty_so_1,
                        $match->id_team_two => $ty_so_2,
                    ];
                    $array_money = [
                        $match->id_team_one => 'tong_one',
                        $match->id_team_two => 'tong_two',
                    ];
                    $money_match = $match->tong_one + $match->tong_two;
                    $get_thanh_toan = DB::table('worldcupusers')
                        ->where('match_uuid', '=', $uuid)->get();
                    $time_end = Carbon::now();
                    foreach ($get_thanh_toan as $row) {
                        if ($row->u_keo_tren == 0) {
                            $ty_so = $array_tyso[$row->id_team] - $array_tyso[$row->id_team2];
                            $keo_id = True;
                        } elseif ($row->id_team == $row->u_keo_tren) {
                            $ty_so = $array_tyso[$row->id_team] - ($array_tyso[$row->id_team2] + $row->u_keo);
                            $keo_id = True;
                        } else {
                            $ty_so = $array_tyso[$row->id_team2] - ($array_tyso[$row->id_team] + $row->u_keo);
                            $keo_id = False;
                        }
                        //win_lose 0=hoà,1=thắng,2=thắng nửa tiền', 3=thua nửa tiển,4=thua đủ

                        $wallet = Wallet::where('user_id', $row->user_id)->first();
                        if ($ty_so == -0.25) {
                            if ($keo_id) {
                                $money_lose = $row->u_money / 2;
                                $money = $money_lose;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 3,
                                    'status' => "Trận này bạn thua một nửa số vàng,đã trả " . number_format($money) . " vàng về ví",
                                    'lose_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            } else {
                                $money_win = (($row->u_money * $row->u_rate) - $row->u_money) / 2;
                                $money = $row->u_money + $money_win;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 2,
                                    'status' => "Trận này bạn thắng một nửa số vàng,đã chung " . number_format($money) . " vàng về ví",
                                    'win_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            }

                            // dd('kèo trên thua nửa tiền', 'kèo dưới ăn nửa tiền', $ty_so);
                            //win_lose 0=hoà,1=thắng,2=thắng nửa tiền', 3=thua nửa tiển,4=thua đủ

                        } elseif ($ty_so <= -0.5) {
                            if ($keo_id) {
                                $money_lose = $row->u_money;
                                $money = $money_lose;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 4,
                                    'status' => "Trận này bạn thua " . number_format($money) . " vàng",
                                    'lose_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                            } else {
                                $money_win = $row->u_money * $row->u_rate;
                                $money = $money_win;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 1,
                                    'status' => "Trận này bạn thắng   " . number_format($money) . ", đã chung vàng về ví",
                                    'win_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            }
                            // dd('trên thua đủ', 'kèo dưới thua đủ', $ty_so);
                            //win_lose 0=hoà,1=thắng,2=thắng nửa tiền', 3=thua nửa tiển,4=thua đủ

                        } elseif ($ty_so == 0.25) {
                            if (!$keo_id) {
                                $money_lose = $row->u_money / 2;
                                $money = $money_lose;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 3,
                                    'status' => "Trận này bạn thua một nửa số vàng, đã trả " . number_format($money) . " vàng về ví",
                                    'lose_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            } else {
                                $money_win = (($row->u_money * $row->u_rate) - $row->u_money) / 2;
                                $money = $row->u_money + $money_win;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 2,
                                    'status' => "Trận này bạn thắng một nửa số vàng, đã chung " . number_format($money) . " vàng về ví",
                                    'win_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            }
                            // dd('kèo trên  ăn nửa tiền', 'kèo dưới thua nửa', $ty_so);
                        } elseif ($ty_so >= 0.5) {
                            if (!$keo_id) {
                                $money_lose = $row->u_money;
                                $money = $money_lose;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 4,
                                    'status' => "Trận này bạn thua " . number_format($money) . " vàng",
                                    'lose_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                            } else {
                                $money_win = $row->u_money * $row->u_rate;
                                $money = $money_win;
                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 1,
                                    'status' => "Trận này bạn thắng " . number_format($money) . ", đã chung vàng về ví",
                                    'win_money' => $money,
                                    'u_time_end' => $time_end,
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            }
                            // dd('kèo trên ăn đủ tiền', 'kèo dưới thua đủ tiền', $ty_so);
                        } elseif ($ty_so == 0) {
                            if ($keo_id) {
                                $money = $row->u_money;
                                $money_match -= $money;
                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 0,
                                    'status' => "Trận này bạn hoà, đã trả về ví " . number_format($money) . " vàng",
                                    'u_time_end' => $time_end,
                                    // 'lose_money' => $money
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            } else {

                                $money = $row->u_money;

                                $wallet->gold = $wallet->gold + $money;
                                $wallet->save();
                                $money_match -= $money;
                                DB::table('worldcupusers')->where('id', '=', $row->id)->update([
                                    'win_lose' => 0,
                                    'status' => "Trận này bạn hoà, đã trả về ví " . number_format($money) . " vàng",
                                    'u_time_end' => $time_end,
                                    // 'win_money' => $money
                                ]);
                                WalletTransaction::create([
                                    'transaction_id'    => Str::uuid(),
                                    'user_id'           => $row->user_id,
                                    'change_type'       => 0,
                                    'transaction_type'  => 6,
                                    'created_at'        => $time_end,
                                    'gold'              => $money,
                                    'yuan'              => 0,
                                    'gold_balance'      => $wallet->gold,
                                    'yuan_balance'      => $wallet->silver,
                                ]);
                            }
                        }
                    }
                    if ($money_match < 0) {
                        DB::table('worldcups')->where('uuid', '=', $uuid)->update([
                            'loi_lo' => 0,
                            'money' => $money_match,
                            'chot_so' => 1,
                            'lock' => 1,
                            'score_team_two' => $ty_so_2,
                            'score_team_one' => $ty_so_1,
                            'time_end' => $time_end
                        ]);
                    } else {
                        DB::table('worldcups')->where('uuid', '=', $uuid)->update([
                            'loi_lo' => 1,
                            'money' => $money_match,
                            'chot_so' => 1,
                            'lock' => 1,
                            'score_team_two' => $ty_so_2,
                            'score_team_one' => $ty_so_1,
                            'time_end' => $time_end
                        ]);
                    }
                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage()]);
            }
            flash()->success("Đã chung tiền xong");
            return redirect()->route('wcadmin');
        } else {
            $check_keo = Str::of($keo)->contains('/');
            if ($check_keo) {

                $keo = explode('/', $keo);
                $keo = ($keo[1] + $keo[0]) / 2;
            } else {
                $keo_text = null;
                $keo =  $keo;
            }
            $datamatch = DB::table('worldcups')->where('uuid', '=', $uuid)->update([
                'id_team_one' => $id_one,
                'id_team_two' => $id_two,
                'rate_team_one' => $tien_1,
                'rate_team_two' => $tien_2,
                'keo' => $keo,
                'id_keo_tren' => $keo_tren,
                'time_start' => $time,
                'score_team_two' => $ty_so_2,
                'score_team_one' => $ty_so_1,
                'keo_text' => $keo_text,
                'lock' =>$lock  ,

            ]);

            if ($datamatch == 1) {
                flash()->success("Sữa trận thành công");
                return redirect()->route('wcadmin');
            } else {
                flash()->error("Sữa trận thất bại");
                return redirect()->back();
            }
        }
    }




    public function index()
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }
        $match2 = DB::table('worldcups', 'wc')
            ->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')
            ->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->orderByDesc('id')->get([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);
        $match = DB::table('worldcups', 'wc')
            ->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')
            ->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->get([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);
        $array_user = [];
        $user = currentUser();
        $wallet   = Wallet::where('user_id', currentUser()->id)->first();
        foreach ($match as $matchs) {
            $sum = DB::table('worldcupusers')
                ->where('match_uuid', '=', $matchs->uuid)
                ->where('user_id', '=', $user->id)
                ->sum('u_money');
            $user_matchs = DB::table('worldcupusers', 'wu')
                ->join('worldcups as wc', 'wu.match_uuid', '=', 'wc.uuid')
                ->join('worldcupteams as wct', 'wu.id_team', '=', 'wct.id')
                ->where('user_id', '=', currentUser()->id)
                ->where('wu.match_uuid', '=', $matchs->uuid)
                ->get(['wc.*', 'wu.*', 'wct.*']);
            $matchs->sum_user = $sum;
            $matchs->user_match = $user_matchs;

            if ($matchs->time_start < Carbon::now()->addMinutes(30)) {
                DB::table('worldcups')->where('uuid', '=', $matchs->uuid)->update([
                    'lock' => 1,
                ]);
                $matchs->lock = 1;
            }
        }
        foreach ($match2 as $matchs) {
            $sum = DB::table('worldcupusers')
                ->where('match_uuid', '=', $matchs->uuid)
                ->where('user_id', '=', $user->id)
                ->sum('u_money');
            $user_matchs = DB::table('worldcupusers', 'wu')
                ->join('worldcups as wc', 'wu.match_uuid', '=', 'wc.uuid')
                ->join('worldcupteams as wct', 'wu.id_team', '=', 'wct.id')
                ->where('user_id', '=', currentUser()->id)
                ->where('wu.match_uuid', '=', $matchs->uuid)
                ->get(['wc.*', 'wu.*', 'wct.*']);
            $matchs->sum_user = $sum;
            $matchs->user_match = $user_matchs;
        }
        // dd($match);
        $user_log = DB::table('worldcupusers', 'wu')
            ->join('worldcups as wc', 'wu.match_uuid', '=', 'wc.uuid')
            ->join('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')
            ->join('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->where('wu.user_id', '=', currentUser()->id)
            ->orderByDesc('wu.id')
            ->get([
                'wc.*', 'wu.*', 'wct1.name as name_one', 'wct1.img as img_one',
                'wct2.name as name_two', 'wct2.img as img_two'



            ]);
        // dd($user_log);
        return view('shop.user.worldcup.indexu', [
            'matchs' => $match,
            'wallet' => $wallet,
            'user_log' =>  $user_log,
            'matchs_2' => $match2
        ]);
    }




    public function create($uuid, Request $request)
    {
        if (!Auth::guard('web')->check()) {
            flash()->error("Chưa đăng nhập");
            return redirect()->route('home');
        }

        Validator(['uuid' => $uuid]);


        $request->validate([
            'gold' => "integer",
            'team_cuoc' => "integer",
            'uuid' => $uuid,
        ]);

        $match =   DB::table('worldcups', 'wc')->where('uuid', '=', $uuid)
            ->leftJoin('worldcupteams as wct1', 'wc.id_team_one', '=', 'wct1.id')
            ->leftJoin('worldcupteams as wct2', 'wc.id_team_two', '=', 'wct2.id')
            ->first([
                'wc.*',
                'wct1.name as name_one',
                'wct1.img as img_one',
                'wct2.name as name_two',
                'wct2.img as img_two'
            ]);
        if (!$match) {
            flash()->error("Sai uuid");
            return redirect()->route('worldcup');
        }
        if ($match->lock) {
            flash()->error("Đã khoá trận không thể đặt cược");
            return redirect()->route('worldcup');
        }
        if (Carbon::now()->addMinutes(30) > $match->time_start) {
            DB::table('worldcups')->update([
                'lock' => 1
            ]);
            flash()->error("Đã hết giờ đặt cược");
            return redirect()->route('worldcup');
        }

        $team1 = $match->id_team_one;
        $team2 = $match->id_team_two;
        $gold = $request->gold;
        $teamid = $request->team_cuoc;
        $user = currentUser();
        $wallet = Wallet::where('user_id', $user->id)->first();
        $first_cuoc = DB::table('worldcupusers')
            ->where('match_uuid', '=', $uuid)
            ->where('user_id', '=', $user->id)
            ->first();
        $array_tien = [
            $team1 => 'tong_one',
            $team2 => 'tong_two',
        ];
        $array_name = [
            $team1 => $match->name_one,
            $team2 => $match->name_two,
        ];

        if ($teamid != $team1 && $teamid != $team2) {
            flash()->error("Không đúng đội của trận đấu.");
            return redirect()->route('worldcup');
        }





        if ($gold < 10000) {
            flash()->error("Cược vàng nhỏ hơn 10k");
            return redirect()->route('worldcup');
        }
        if ($gold > $wallet->gold) {
            flash()->error("Không đủ vàng trong tài khoản");
            return redirect()->route('worldcup');
        }
        if ($gold % 1000 != 0) {
            flash()->error("Vàng phải chia hết cho 1000");
            return redirect()->route('worldcup');
        }
        if ($first_cuoc) {
            // if ($first_cuoc->id_team != $teamid) {
            //     flash()->error("Bạn chỉ được cược 1 đội duy nhất");
            //     return redirect()->route('worldcup');
            // }

            try {
                DB::transaction(function () use ($match, $wallet, $user, $gold, $teamid) {
                    $wallet->gold = $wallet->gold - $gold;
                    $wallet->save();
                    $nowtime = Carbon::now();
                    if ($teamid == $match->id_team_one) {
                        $temp_rate = $match->rate_team_one;
                        $temp_rate2 = $match->rate_team_two;
                        $id1 = $match->id_team_one;
                        $id2 = $match->id_team_two;
                        $tien = [
                            $id1 => 'tong_one',
                            $id2 => 'tong_two'
                        ];
                    } else {
                        $temp_rate = $match->rate_team_two;
                        $temp_rate2 = $match->rate_team_one;
                        $id1 = $match->id_team_two;
                        $id2 = $match->id_team_one;
                        $tien = [
                            $id1 => 'tong_two',
                            $id2 => 'tong_one'
                        ];
                    }
                    $insert = DB::table('worldcupusers')->insert([
                        'u_uuid' => Str::uuid(),
                        'user_id' => $user->id,
                        'match_uuid' => $match->uuid,
                        'u_keo' =>  $match->keo,
                        'u_rate' => $temp_rate,
                        'id_team' => $id1,
                        'u_rate2' => $temp_rate2,
                        'id_team2' => $id2,
                        'u_keo_tren' => $match->id_keo_tren,
                        'u_time_create'       => $nowtime,
                        'u_money' => $gold,
                        'u_keo_text' => $match->keo_text


                    ]);
                    WalletTransaction::create([
                        'transaction_id'    => Str::uuid(),
                        'user_id'           => $user->id,
                        'change_type'       => 1,
                        'transaction_type'  => 6,
                        'created_at'        => $nowtime,
                        'gold'              => $gold,
                        'yuan'              => 0,
                        'gold_balance'      => $wallet->gold,
                        'yuan_balance'      => $wallet->silver,
                    ]);
                    DB::table('worldcups')->where('uuid', $match->uuid)->increment($tien[$id1], $gold);
                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage()]);
            }
            flash()->success("Đặt cược thành công");
            return redirect()->route('worldcup');
        } else {


            try {
                DB::transaction(function () use ($match, $wallet, $user, $gold, $teamid, $array_name) {
                    $wallet->gold = $wallet->gold - $gold;
                    $wallet->save();
                    $nowtime = Carbon::now();
                    if ($teamid == $match->id_team_one) {
                        $temp_rate = $match->rate_team_one;
                        $temp_rate2 = $match->rate_team_two;
                        $id1 = $match->id_team_one;
                        $id2 = $match->id_team_two;
                        $tien = [
                            $id1 => 'tong_one',
                            $id2 => 'tong_two'
                        ];
                    } else {
                        $temp_rate = $match->rate_team_two;
                        $temp_rate2 = $match->rate_team_one;
                        $id1 = $match->id_team_two;
                        $id2 = $match->id_team_one;
                        $tien = [
                            $id1 => 'tong_two',
                            $id2 => 'tong_one'
                        ];
                    }

                    $insert = DB::table('worldcupusers')->insert([
                        'u_uuid' => Str::uuid(),
                        'user_id' => $user->id,
                        'match_uuid' => $match->uuid,
                        'u_keo' =>  $match->keo,
                        'u_rate' => $temp_rate,
                        'id_team' => $id1,
                        'u_rate2' => $temp_rate2,
                        'id_team2' => $id2,
                        'u_keo_tren' => $match->id_keo_tren,
                        'u_time_create'       => $nowtime,
                        'u_money' => $gold,
                        'u_keo_text' => $match->keo_text


                    ]);
                    WalletTransaction::create([
                        'transaction_id'    => Str::uuid(),
                        'user_id'           => $user->id,
                        'change_type'       => 1,
                        'transaction_type'  => 6,
                        'created_at'        => $nowtime,
                        'gold'              => $gold,
                        'yuan'              => 0,
                        'gold_balance'      => $wallet->gold,
                        'yuan_balance'      => $wallet->silver,
                    ]);
                    DB::table('worldcups')->where('uuid', $match->uuid)->increment($tien[$id1], $gold);
                    DB::table('worldcups')->where('uuid', $match->uuid)->increment('tong_mem');
                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage()]);
            }

            flash()->success("Đặt cược thành công");
            return redirect()->route('worldcup');
        }
    }

    public function store(Request $request)
    {
    }

    public function show(int $id)
    {
    }

    public function edit(int $id)
    {
    }

    public function update(Request $request, int $id)
    {
    }

    public function destroy(int $id)
    {
    }
}
