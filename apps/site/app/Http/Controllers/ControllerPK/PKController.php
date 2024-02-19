<?php

namespace App\Http\Controllers\ControllerPK;

use App\TuLuyen\Model_charater;
use App\TuLuyen\Model_chienBao;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PKController
{

    public function index()
    {
        $user = $this->checklogin();
        //kiểm tra đăng nhập
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $player = $user->get_charaters()->first();
        //kiểm tra tạo nhân vật tu luyện
        if (!$player) {
            flash()->error('Bạn chưa tạo nhân vật');
            return redirect()->route('tuluyen.create');
        }
        $rank = Model_charater::orderByDesc('tichphan') // Sắp xếp theo tích phân giảm dần
            ->pluck('id') // Lấy danh sách các ID
            ->search($player->id);
        $rank += 1;
        $thanLongBang = Model_charater::with("get_users")
            ->orderByDesc('tichphan')
            ->take(100)
            ->get();

        $vinhDanh = Model_charater::with("get_users")->where('vinh_danh',1)->get();
        // dd($rank);
        return view('pk.index', compact('user', 'player', 'rank', 'thanLongBang', 'vinhDanh'));
    }
    /**
     * timDoiThu
     *
     * @param  mixed $request
     * @return void
     */
    public function timDoiThu()
    {
        $user = $this->checklogin();
        //kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $player1 = $user->get_charaters()->first();
        //kiểm tra tạo nhân vật tu luyện
        if (!$player1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }

        //kiểm tra xác nhận pk
        if (!$player1->is_pkconfirm) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa xác nhận tham gia chiến trường. Vui lòng vào trang chiến trường để xác nhận."
            ]);
        }
        //kiểm tra lượt pk với tài khoản thường
        if ($player1->luotpk <= 0) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đã hết lượt pk"
            ]);
            // flash()->error('Bạn đã hết lượt pk');
            // return view('pk.index');
        }

        //kiểm tra xem có đang pk không
        // kiểm tra thời gian
        $timepk = Carbon::now();

        if ($player1->is_pk &&$player1->datepk > $timepk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đã tìm đối thủ, vui lòng huỷ và tìm lại. Hoặc chờ vài giây nữa để pk tiếp."
            ]);
        }

        //kiểm tra linh thạch

        if ($player1->linh_thach < 25 && $player1->pkmode == 1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ 25 linh thạch."
            ]);
        }



        if ($player1->pkmode == 1) {

            //lấy user phòng thủ (chế độ cao cấp)
            $player2 = Model_charater::whereNotIn('id', [$player1->id])
                ->where(function ($query) {
                    $query->where('is_hodao', false)->orWhere(
                        function ($query2) {
                            $query2->where('is_hodao', true)
                                ->where('is_pk', true);
                        }
                    );
                })
                ->where('pkmode', 1)
                ->where("is_pkconfirm", 1)
                ->where("linh_thach", ">=", 25)
                ->inRandomOrder()
                ->first();
        } elseif ($player1->pkmode == 0) {
            // lấy user phòng thủ để pk (chế độ thường)
            $player2 = Model_charater::whereNotIn('id', [$player1->id])
                ->where(function ($query) {
                    $query->where('is_hodao', false)->orWhere(
                        function ($query2) {
                            $query2->where('is_hodao', true)
                                ->where('is_pk', true);
                        }
                    );
                })
                ->where('pkmode', 0)
                ->where("is_pkconfirm", 1)
                ->inRandomOrder()
                ->first();
        } else {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Chế độ pk không hợp lệ."
            ]);
        }

        //kiểm tra xem có user phòng thủ nào để pk không
        if (!$player2) {
            if ($player1->pkmode == 1) {
                return response()->json([
                    "status" => "error",
                    "code" => 1,
                    "message" => "Không tìm được đối thủ ở chế độ pk cao cấp."
                ]);
            } elseif ($player1->pkmode == 0) {
                return response()->json([
                    "status" => "error",
                    "code" => 1,
                    "message" => "Không tìm được đối thủ ở chế độ pk thường."
                ]);
            } else {
                return response()->json([
                    "status" => "error",
                    "code" => 1,
                    "message" => "Chế độ pk không hợp lệ."
                ]);
            }
        }
        $timepk = Carbon::now();
        $random = rand(0, 0);
        $player1->update([
            "is_pk" => 1,
            "doithu" =>  $player2->id,
            "datepk" => $timepk->addSeconds($random),
        ]);

        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => ($random + 2) * 1000,
            "id" => $player2->id,
        ]);



        return view('pk.index');
    }


    public function timDoiThuId($id)
    {

        $user = $this->checklogin();
        //kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $player1 = $user->get_charaters()->first();
        //kiểm tra tạo nhân vật tu luyện
        if (!$player1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }

        //kiểm tra xác nhận pk
        if (!$player1->is_pkconfirm) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa xác nhận tham gia chiến trường. Vui lòng vào trang chiến trường để xác nhận."
            ]);
        }
        //kiểm tra lượt pk với tài khoản thường
        if ($player1->luotpk <= 0) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đã hết lượt pk"
            ]);
            // flash()->error('Bạn đã hết lượt pk');
            // return view('pk.index');
        }

        //kiểm tra xem có đang pk không
        $timepk = Carbon::now();

        if ($player1->is_pk &&$player1->datepk > $timepk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đã tìm đối thủ, vui lòng huỷ và tìm lại. Hoặc chờ vài giây nữa để pk tiếp."
            ]);
        }


        //kiểm tra linh thạch

        if ($player1->linh_thach < 25 && $player1->pkmode == 1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ 25 linh thạch."
            ]);
        }


        $player2 = Model_charater::where('user_id', $id)->first();

        if (!$player2) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Id user không hợp lệ hoặc chưa tu luyện."
            ]);
        }

        if ($player1->id === $player2->id) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Không thể pk với chính mình."
            ]);
        }
        if (!$player2->is_pkconfirm) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối phương chưa tham gia chiến trường."
            ]);
        }
        if ($player1->pkmode != $player2->pkmode) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối phương đang ở chế độ pk khác bạn."
            ]);
        }

        if ($player2->is_hodao && !$player2->is_pk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối phương đang trong trạng thái hộ đạo."
            ]);
        }
        if ($player2->linh_thach < 25 && $player1->pkmode == 1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối phương không đủ 25 linh thạch."
            ]);
        }

        $timepk = Carbon::now();
        $random = rand(0, 0);
        $player1->update([
            "is_pk" => 1,
            "doithu" =>  $player2->id,
            "datepk" => $timepk->addSeconds($random),
        ]);

        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => ($random + 2) * 1000,
            "id" => $player2->id,
        ]);
    }

     public function battle()
    {
        $user = $this->checklogin();
        //kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $player1 = $user->get_charaters()->first();
        //kiểm tra tạo nhân vật tu luyện
        if (!$player1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }
        //kiểm tra xem có đang pk không
        if (!$player1->is_pk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Vui lòng tìm đối thủ trước khi pk."
            ]);
        }
        $player1->is_pk = false;
        //kiểm tra xác nhận pk
        if (!$player1->is_pkconfirm) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa xác nhận tham gia chiến trường. Vui lòng vào trang chiến trường để xác nhận."
            ]);
        }
        //kiểm tra lượt pk với tài khoản thường
        if ($player1->luotpk <= 0) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đã hết lượt pk"
            ]);
        }



        //kiểm tra linh thạch

        if ($player1->linh_thach < 25 && $player1->pkmode == 1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ 25 linh thạch."
            ]);
        }

        // kiểm tra thời gian
        $timepk = Carbon::now();
        if ($player1->datepk > $timepk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối thủ chưa xác nhận."
            ]);
        }
        $player2 = Model_charater::where('id', $player1->doithu)->first();
        $user2 = $player2->get_users()->first();
        // dd($user2);
        if ($player2->linh_thach < 25 && $player1->pkmode == 1) {
            $player2->update(["pkmode" => false]);
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Đối thủ không đủ 25 linh thạch."
            ]);
        }
        // trừ 25 linh thạch
        if ($player1->pkmode == 1) {
            $player1->decrement('linh_thach', 25);
            $player2->decrement('linh_thach', 25);
        }

        //biến tốc độ đánh
        $atkSpeed1 = $player1->pk_atk_speed(); //người tấn công
        $atkSpeed2 = $player2->pk_atk_speed(); //ngường phòng thủ
        // bắt đầu set hp
        $player1->hp = $player1->pk_hp();
        $player2->hp = $player2->pk_hp();
        // bắt đầu set mp
        $player1->mp = $player1->pk_mp();
        $player2->mp = $player2->pk_mp();
        //chiến báo
        $chienbao1 = [];
        $chienbao2 = [];
        //kiểm tra xem ai tốc độ đánh lớn hơn thì sẽ đánh trước

        $tempspeed2 =  [
            [
                'tempspeed' => 0
            ], [
                'tempspeed' => 0
            ]

        ];
        if ($atkSpeed1 > $atkSpeed2) {
            $tmpturn = &$tempspeed2[0];
            $tmpturn2 = &$tempspeed2[1];
            // $tmpturn['tempspeed'] = 0;
            $attacker = $player1;
            $defender = $player2;
        } else {
            $tmpturn = &$tempspeed2[1];
            $tmpturn2 = &$tempspeed2[0];
            // $tmpturn['tempspeed'] = 0;
            $attacker = $player2;
            $defender = $player1;
        }
        $turn = 0;
        $round = 1;
        $testtest = [];
        while ($attacker->hp > 0 && $defender->hp > 0) {
            if ($turn == 2) {
                $round += 1;
                $turn = 1;
                $text = "Round " . $round . "\n\n";
                $text2 = "Round " . $round . "\n\n";
            } else {
                $turn += 1;
                $text = "Round " . $round . "\n\n";
                $text2 = "Round " . $round . "\n\n";
            }
            if ($attacker->pk_atk_speed() * 10 >= 1) {
                $tmpturn['tempspeed'] += ($attacker->pk_atk_speed() * 10)  - floor($attacker->pk_atk_speed() * 10);
            }
            if ($defender->pk_atk_speed() * 10 >= 1) {
                $tmpturn2['tempspeed'] += ($defender->pk_atk_speed() * 10)  - floor($defender->pk_atk_speed() * 10);
            }
            // Tính toán số lượt tấn công của người tấn công
            $numAttacks = floor($attacker->pk_atk_speed() * 10);
            $numAttacksDefender = floor($defender->pk_atk_speed() * 10);
            $numAttacks = $numAttacks < 1 ? 1 : $numAttacks;
            $numAttacksDefender = $numAttacksDefender < 1 ? 1 : $numAttacksDefender;
            if ($tmpturn['tempspeed'] >= 1) {
                $numAttacks += 1;
                $tmpturn['tempspeed'] -= 1;
            }
            if ($tmpturn2['tempspeed'] >= 1) {
                $numAttacksDefender += 1;
                $tmpturn2['tempspeed'] -= 1;
            }
            $text .= "Lượt " . $turn . "\n\nBạn tấn công " . $defender->get_users->name . "\n";
            $text2 .= "Lượt " . $turn . "\n\nBạn phòng thủ " . $attacker->get_users->name . "\n";

            $text .= "Bạn tung ra được $numAttacks đòn đánh trong lượt này. \n";
            $text2 .= "Đối phương tung ra $numAttacks đòn đánh trong lượt này. \n";

            // Thực hiện các lượt tấn công của người tấn công
            for ($i = 1; $i <= $numAttacks; $i++) {
                $crit = false;
                $dogge = false;
                $critDefender = false;
                $doggeDefender = false;
                $text .= "\nBạn tung ra đòn đánh thứ $i. \n";
                $text2 .= "\nĐối phương tung ra đòn đánh thứ $i. \n";
                // Tính toán sát thương của người tấn công
                $damage = $attacker->pk_damage();
                $damageDefender = $defender->pk_def();
                // Kiểm tra xem người tấn công gây ra sát thương chí mạng hay không
                if (mt_rand(1, 100) <= $attacker->pk_crit() * 100) {
                    $damage *= (1 + $attacker->pk_crit_dmg()); // Áp dụng sát thương chí mạng
                    $crit = true;
                }
                // Kiểm tra xem người phòng thủ gây ra sát thương chí mạng hay không
                if (mt_rand(1, 100) <= $defender->pk_crit_dmg() * 100) {
                    $damageDefender  *= $defender->pk_crit_dmg();
                    $critDefender = true;
                }

                // Tính phòng thủ của người phòng thủ
                $damage -= $defender->pk_def();
                $damageDefender -= $attacker->pk_def();
                $damage = max($damage,100);
                $damageDefender = max($damageDefender,100);
                // Áp dụng khả năng né đòn của người phòng thủ
                if (mt_rand(1, 100) <= $defender->pk_dodge() * 100) {
                    $damage = 0; // Người phòng thủ né đòn thành công
                    $dogge = true;
                }
                // Áp dụng khả năng né đòn của người chơi tấn công
                if (mt_rand(1, 100) <= $attacker->pk_dodge() * 100) {
                    $damageDefender = 0; // Người chơi tấn công né đòn thành công
                    $doggeDefender = true;
                }
                //Nếu mp dưới 200 thì dame còn 50%
                if ($attacker->mp < 200) {
                    $damage = ceil($damage / 2);
                    $attacker->mp = 0;
                } else {
                    // Trừ 200 mp của người tấn công
                    $attacker->mp -= 200;
                }
                if ($defender->mp < 200) {
                    $damageDefender = ceil($damage / 2);
                    $defender->mp = 0;
                } else {
                    // Trừ 200 mp của người tấn công
                    $defender->mp -= 200;
                }
                // Trừ điểm máu của người phòng thủ
                $defender->hp -= $damage;
                //turn chiến báo
                $text .= $crit ? "Bạn đã gây sát thương chí mạng.\n" : "Bạn không có chí mạng. \n";
                $text2 .= $crit ? "Đối phương đã gây sát thương chí mạng.\n" : "Đối phương không có chí mạng. \n";
                $text .=  $dogge ? "Đối phương đã né tránh sát thương bạn gây ra là: 0. \n" : "Sát thương bạn gây ra là: $damage. \n";
                $text2 .= $dogge ? "Bạn đã né được đòn tấn công sát thương gây ra bởi đối phương = 0. \n" : "Bạn không né được sát thương đối phương gây ra là: $damage. \n";
                if ($defender->hp <= 0) {
                    $text .=  'Hp của "' . $defender->get_users->name . '" còn lại: 0.' . "\nBạn thắng.\n ";
                    $text2 .=  "Hp của bạn còn lại: 0. \nBạn thua.";
                } else {
                    $text .=  'Hp của "' . $defender->get_users->name . '" còn lại: ' . $defender->hp . ". \n";
                    $text2 .=  'Hp của bạn còn lại: ' . $defender->hp . ". \n";
                    if ($numAttacksDefender >= 1  && ($i < $numAttacks || ($i == $numAttacks && $numAttacksDefender - 1 == 0))) {
                        $attacker->hp -= $damageDefender;
                        $text .= '"' . $defender->get_users->name . '"' . " cũng tấn công lại bạn.\n";
                        $text2 .= "Bạn cũng tấn công lại " . $attacker->get_users->name . ". \n";
                        $text2 .= $critDefender ? "Bạn đã gây sát thương chí mạng.\n" : "Bạn không có chí mạng. \n";
                        $text .= $critDefender ? "Đối phương đã gây sát thương chí mạng. \n" : "Đối phương không có chí mạng. \n";
                        $text2 .=  $doggeDefender ? "Đối phương đã né tránh sát thương bạn gây ra là: 0. \n" : "Sát thương ban gây ra là: $damageDefender. \n";
                        $text .= $doggeDefender ? "Bạn đã né được đòn tấn công sát thương gây ra bởi đối phương = 0. \n" : "Bạn không né được sát thương đối phương gây ra là: $damageDefender. \n";
                        if ($attacker->hp <= 0) {
                            $text .=  "Hp của bạn còn lại: 0. \nBạn thua.";
                            $text2 .=  'Hp của "' . $attacker->get_users->name . '" còn lại: 0.' . "\nBạn thắng.\n ";
                        } else {
                            $text .=  "Hp của bạn còn lại: $attacker->hp.\n";
                            $text2 .= 'Hp của "' . $attacker->get_users->name . '" còn lại: ' . $attacker->hp . ". \n";
                        }
                        $numAttacksDefender -= 1;
                    } elseif ($numAttacksDefender > 0) {
                        $attacker->hp -= $damageDefender;
                        $text .= '"' . $defender->get_users->name . '"' . " cũng tấn công lại bạn.\n";
                        $text2 .= "Bạn cũng tấn công lại " . $attacker->get_users->name . ". \n";
                        $text2 .= $critDefender ? "Bạn đã gây sát thương chí mạng.\n" : "Bạn không có chí mạng. \n";
                        $text .= $critDefender ? "Đối phương đã gây sát thương chí mạng. \n" : "Đối phương không có chí mạng. \n";
                        $text2 .=  $doggeDefender ? "Đối phương đã né tránh sát thương bạn gây ra là: 0. \n" : "Sát thương ban gây ra là: $damageDefender. \n";
                        $text .= $doggeDefender ? "Bạn đã né được đòn tấn công sát thương gây ra bởi đối phương = 0. \n" : "Bạn không né được sát thương đối phương gây ra là: $damageDefender. \n";
                        if ($attacker->hp <= 0) {
                            $text .=  "Hp của bạn còn lại: 0. \nBạn thua.";
                            $text2 .=  'Hp của "' . $attacker->get_users->name . '" còn lại: 0.' . "\nBạn thắng.\n ";
                        } else {
                            $text .=  "Hp của bạn còn lại: $attacker->hp.\n";
                            $text2 .= 'Hp của "' . $attacker->get_users->name . '" còn lại: ' . $attacker->hp . ". \n";
                        }
                        $numAttacksDefender -= 1;
                        for ($numAttacksDefender; $numAttacksDefender >= 1; $numAttacksDefender--) {
                            $attacker->hp -= $damageDefender;
                            $text .= "\nBạn đã hết lượt ra đòn đánh.\n";
                            $text2 .= "\nĐối phương đã hết lượt ra đòn đánh, giờ bạn là trùm.\n";
                            $text .= '"' . $defender->get_users->name . '"' . " tiêp tục tấn công khi bạn hết lượt.\n";
                            $text2 .= "Bạn tiếp tục tấn công lại khi " . $attacker->get_users->name . ". hết lượt đánh.\n";
                            $text2 .= $critDefender ? "Bạn đã gây sát thương chí mạng.\n" : "Bạn không có chí mạng. \n";
                            $text .= $critDefender ? "Đối phương đã gây sát thương chí mạng. \n" : "Đối phương không có chí mạng. \n";
                            $text2 .=  $doggeDefender ? "Đối phương đã né tránh sát thương bạn gây ra là: 0. \n" : "Sát thương ban gây ra là: $damageDefender. \n";
                            $text .= $doggeDefender ? "Bạn đã né được đòn tấn công sát thương gây ra bởi đối phương = 0. \n" : "Bạn không né được sát thương đối phương gây ra là: $damageDefender. \n";
                            if ($attacker->hp <= 0) {
                                $text .=  "Hp của bạn còn lại: 0. \nBạn thua.";
                                $text2 .=  'Hp của "' . $attacker->get_users->name . '" còn lại: 0.' . "\nBạn thắng.\n ";
                            } else {
                                $text .=  "Hp của bạn còn lại: $attacker->hp.\n";
                                $text2 .= 'Hp của "' . $attacker->get_users->name . '" còn lại: ' . $attacker->hp . ". \n";
                            }
                            // Kiểm tra xem người phòng thủ có còn HP không
                            if ($attacker->hp <= 0) {
                                break; // Người phòng thủ đã bị hạ gục, kết thúc vòng lặp
                            }
                        }
                    }

                }

                // Kiểm tra xem người phòng thủ có còn HP không
                if ($defender->hp <= 0 || $attacker->hp <= 0) {
                    break; // Người phòng thủ đã bị hạ gục, kết thúc vòng lặp
                }
            }

            // Hoán đổi người tấn công và người phòng thủ

            if ($attacker === $player1) {
                $tmpturn = &$tempspeed2[1];
                $tmpturn2 = &$tempspeed2[0];
                $chienbao1[$round][] = [$text];
                $chienbao2[$round][] = [$text2];
                $attacker = $player2;
                $defender = $player1;
            } else {
                $tmpturn = &$tempspeed2[0];
                $tmpturn2 = &$tempspeed2[1];
                $chienbao1[$round][] = [$text2];
                $chienbao2[$round][] = [$text];
                $attacker = $player1;
                $defender = $player2;
            }
        }
        // dd($testtest);
        //debug
        // dd($tmpturn,$chienbao1, $chienbao2);

        // Xác định người thắng cuộc
        //ghi chiến báo
        $notify = new Model_chienBao;
        $notify2 = new Model_chienBao;

        //user tấn công
        $notify->user_id = $player1->user_id;
        $notify->player1_id = $player1->user_id;
        $notify->player2_id = $player2->user_id;
        //user thủ
        $notify2->user_id = $player2->user_id;
        $notify2->player1_id = $player1->user_id;
        $notify2->player2_id = $player2->user_id;
        $linhThach = 0;
        $textResponse = null;
        if ($player1->hp <= 0) {
            //phòng thủ thắng
            $textResponse = 'Bạn tấn công "' . $user2->name . '" nhưng bị phản sát và rớt lại mớ đồ hzzz. Vui lòng xem chiến báo.';
            $notify->win = false;
            $notify2->win = true;
            if ($player1->tichphan < 10) {
                // nhỏ hơn 10 thì trừ toàn bộ tích phân
                $tichphan = $player1->tichphan;
            } elseif ($player1->tichphan < 100) {
                //nhỏ hơn 100 sẽ trừ mỗi lần 10
                $tichphan = 10;
            } else {
                // tính 10% tích phân của người thua để trừ
                $tichphan = round($player1->tichphan * 0.1);
            };
            if ($player1->can_co < 5) {
                $cancot = $player1->can_co;
            } else {
                $cancot = 5; // trừ 5 căn cốt cho người thua
            }
            $tichphanwin = $tichphan;
            //kiểm tra chế độ pk để + điểm
            if ($player1->pkmode == 0) {
                //pk thường
                $tichphanwin += 10; // +10 tích phân và 10% tích phân cho người thắng
            } elseif ($player1->pkmode == 1) {
                //pk cao cấp
                $linhThach += 40; //+40 linh thạch
                if ($user2->user_vip) {
                    $tichphanwin += 90;  //+90 và 10% tích phân
                } else {
                    $tichphanwin += 10; // +10 tích phân và 10% tích phân cho người thắng
                }
            }
            $player1->tichphan -= $tichphan; //trừ tích phân bên thua
            $player1->can_co -= $cancot;
            $player2->tichphan += $tichphanwin;

            $text = 'Bạn đã tấn công "' . "$user2->name" . '"' . " (id: $user2->id) và thua sấp mặt.\n\n";
            $text2 = '"' . "$user->name" . '"' . " (id: $user->id) ăn cướp bạn phản sát thành công, tự nhiên có người ship đồ.\n\n";

            if ($tichphan == 0) {
                $text .= "Bạn không còn tích phân để trừ, bạn còn lại $player1->tichphan tích phân.\n";
            } else {
                $text .= "Bạn bị trừ $tichphan tích phân, bạn còn lại $player1->tichphan tích phân\n";
            }
            if ($cancot == 0) {
                $text .= "Bạn không còn căn cốt để trừ, bạn còn lại $player1->can_co căn cốt.\n";
            } else {
                $text .= "Bạn bị trừ $cancot căn cốt, bạn còn lại $player1->can_co căn cốt.\n";
            }
            if ($linhThach > 0) {
                $player2->linh_thach += $linhThach;
                $text2 .= "Bạn được +$linhThach linh thạch, linh thạch hiện tại là $player2->linh_thach.\n";
            }
            $text2 .= "Bạn được +$tichphanwin tích phân, tích phân hiện tại là $player2->tichphan.\n";
            $player1->lose += 1; // cộng 1 vào lượt thua cho người tấn công
            $player2->win += 1; // cộng 1 vào lượt thắng cho người phòng thủ

        } else {
            //tấn công thắng
            $textResponse = 'Bạn đã giết được "' . $user2->name . '" và cướp được phần thưởng khủng. Vui lòng xem chiến báo.';
            $notify->win = true;
            $notify2->win = false;


            // Trừ tích phân người thua và thêm tích phân cho người thắng
            //kiểm tra tích phân để trừ
            if ($player2->tichphan < 10) {
                // nhỏ hơn 10 thì trừ toàn bộ tích phân
                $tichphan = $player2->tichphan;
            } elseif ($player2->tichphan < 100) {
                //nhỏ hơn 100 sẽ trừ mỗi lần 10
                $tichphan = 10;
            } else {
                // tính 10% tích phân của người thua để trừ
                $tichphan = round($player2->tichphan * 0.1);
            };
            if ($player2->can_co < 5) {
                $cancot = $player2->can_co;
            } else {
                $cancot =  5; // trừ 5 căn cốt cho người thua
            }
            $tichphanwin = $tichphan;
            //kiểm tra chế độ pk để + điểm
            if ($player1->pkmode == 0) {
                //pk thường
                $tichphanwin += 10;
            } elseif ($player1->pkmode == 1) {
                //pk cao cấp
                $linhThach += 40; //+40 linh thạch

                if ($user->user_vip) {
                    $tichphanwin += 90; //+90 và 10% tích phân cho user vip
                } else {
                    $tichphanwin += 10; // +10 tích phân và 10% tích phân cho người thắng
                }
            }

            $player2->tichphan -= $tichphan; //trừ tích phân bên thua
            $player2->can_co -= $cancot;
            $player1->tichphan += $tichphanwin;
            $player1->linh_thach += $linhThach;

            $text = 'Bạn đã tấn công "' . $user2->name . '"' . " (id: $user2->id) và cướp được nhiều thứ. \n\n";
            $text2 = '"' . "$user->name" . '"' . " (id: $user->id) Đã tấn công bạn và cướp của bạn 1 số tài nguyên.  \n\n";

            if ($tichphan == 0) {
                $text2 .= "Bạn không còn tích phân để trừ, bạn còn lại $player2->tichphan tích phân.\n";
            } else {
                $text2 .= "Bạn bị trừ $tichphan tích phân, bạn còn lại $player2->tichphan tích phân\n";
            }
            if ($cancot == 0) {
                $text2 .= "Bạn không còn căn cốt để trừ, bạn còn lại $player2->can_co căn cốt.\n";
            } else {
                $text2 .= "Bạn bị trừ $cancot căn cốt, bạn còn lại $player2->can_co căn cốt.\n";
            }
            if ($linhThach > 0) {
                $player1->linh_thach += $linhThach;
                $text .= "Bạn được +$linhThach linh thạch, linh thạch hiện tại là $player1->linh_thach.\n";
            }
            $text .= "Bạn được +$tichphanwin tích phân, tích phân hiện tại là $player1->tichphan.\n";

            $player1->win += 1; // cộng 1 vào lượt thắng cho người tấn công
            $player2->lose += 1; // cộng 1 vào lượt thua cho người phòng thủ
        }




        $player1->tongpk += 1; // cộng 1 vào tổng số lượt pk cho người tấn công
        $player2->tongpk += 1; // cộng 1 vào tổng  số lượt pk cho người thủ
        $player1->datepk = null;
        $player1->doithu = null;


        $notify->result = $text;
        $notify->turns = json_encode($chienbao1);
        $notify->save();

        $notify2->result = $text2;
        $notify2->turns = json_encode($chienbao2);
        $notify2->save();
        // set lại hp
        $player1->hp = $player1->pk_hp();
        $player2->hp = $player2->pk_hp();
        // set lại mp
        $player1->mp = $player1->pk_mp();
        $player2->mp = $player2->pk_mp();
        //-1 lượt pk
        $player1->luotpk -= 1;




        //+chiến báo
        $player1->chien_bao += 1;
        $player2->chien_bao += 1;

        //lưu vào db
        $player1->save();
        $player2->save();



        // Trả về kết quả
        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => $textResponse,
        ]);
    }

    public function cancelpk()
    {
        $user = $this->checklogin();
        //kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $player1 = $user->get_charaters()->first();
        //kiểm tra tạo nhân vật tu luyện
        if (!$player1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }
        //kiểm tra lượt pk với tài khoản thường
        if ($player1->luotpk <= 0) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đã hết lượt pk"
            ]);
            // flash()->error('Bạn đã hết lượt pk');
            // return view('pk.index');
        }

        //kiểm tra xem có đang pk không
        if (!$player1->is_pk) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Chưa tìm đối thủ không thể huỷ."
            ]);
        }


        $player1->update([
            "doithu" => null,
            "datepk" => null,
            "is_pk" => false,
        ]);
        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => "Huỷ pk thành công"
        ]);
    }


    public function packTuLuyen($pack)
    {
        //pack = 1 gói cơ bản
        //pack = 2 gói bình dân
        //pack = 3 gói
        $user = $this->checklogin();
        //Kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $player = $user->get_charaters()->first();
        //Kiểm tra xem tạo nhân vật chưa
        if (!$player) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }
        //số linh thạch cần để mua
        $linhThach = [1 => 100, 2 => 200, 3 => 1000];

        if ($player->linh_thach < $linhThach[$pack]) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ linh thạch để mua gói đã chọn"
            ]);
        }
        // dd($linhThach[$pack]);
        switch ($pack) {
            case 1:
                if ($player["pack_" . $pack] >= 4) {
                    return response()->json([
                        "status" => "error",
                        "code" => 1,
                        "message" => "Bạn mua 4 gói cơ bản trong tháng này nên không thể mua thêm."
                    ]);
                }
                $player->update([
                    "exp" => $player->exp + 60000, //cộng 60k exp
                    "sum_max_hp" => $player->sum_max_hp + 400, // cộng 400 hp
                    "sum_max_mp" => $player->sum_max_mp + 400, //cộng 400 mp
                    "sum_atk" => $player->sum_atk + 120, //cộng 120 sát thương
                    "sum_def" => $player->sum_def + 80, //cộng 80 phòng thủ
                    "pack_" . $pack => $player["pack_$pack"] + 1, //cộng vào gói pack
                    "linh_thach" => $player->linh_thach -  $linhThach[$pack], //trừ linh thạch
                ]);
                return response()->json([
                    "status" => "success",
                    "code" => 0,
                    "message" => "Bạn mua thành công gói tu luyện cơ bản. Tăng 120 sát thương, 80 phòng thủ, 60k tu vi, 400 hp, 400 mp"
                ]);
                break;
            case 2:
                if ($player["pack_" . $pack] >= 4) {
                    return response()->json([
                        "status" => "error",
                        "code" => 1,
                        "message" => "Bạn mua 4 gói bình dân trong tháng này nên không thể mua thêm."
                    ]);
                }
                $player->update([
                    "exp" => $player->exp + 120000, //cộng 120k exp
                    "sum_max_hp" => $player->sum_max_hp + 900, // cộng 900 hp
                    "sum_max_mp" => $player->sum_max_mp + 900, //cộng 900 mp
                    "sum_atk" => $player->sum_atk + 250, //cộng 250 sát thương
                    "sum_def" => $player->sum_def + 180, //cộng 180 phòng thủ
                    "pack_" . $pack => $player["pack_$pack"] + 1, //cộng vào gói pack
                    "linh_thach" => $player->linh_thach -  $linhThach[$pack], //trừ linh thạch
                ]);
                return response()->json([
                    "status" => "success",
                    "code" => 0,
                    "message" => "Bạn mua thành công gói tu luyện bình dân. Tăng 250 sát thương, 180 phòng thủ, 120k tu vi, 900 hp, 900 mp"
                ]);

                break;
            case 3:

                $topPlayers = Model_charater::where('top', '>=', 1)->where('top', '<=', 10)
                    ->take(10)
                    ->get();
                // Kiểm tra xem người chơi cụ thể có nằm trong top 10 hay không
                $isInTop10 = $topPlayers->contains(function ($playerCheck) use ($player) {
                    return $playerCheck->id === $player->id;
                });
                //biến $isDate để kiểm tra xem có tạo nhân vật dưới 30 ngày không
                $isDate = $player->created_at > Carbon::now()->subDays(30);
                //kiểm tra xem có nằm trong top mười, tạo dưới 30 ngày, là vip hay không
                if (!$isInTop10 && !$isDate && !$user->user_vip) {
                    return response()->json([
                        "status" => "error",
                        "code" => 1,
                        "message" => "Bạn không đủ điều kiện mua gói này."
                    ]);
                }
                //kiểm tra xem các gói có được mua chưa
                if (
                    ($isDate && $player->is_packnew) &&
                    ($isInTop10 && $player->is_packtop) &&
                    ($user->user_vip && $player->is_packvip)
                    && $player->vinh_danh != 1
                ) {
                    return response()->json([
                        "status" => "error",
                        "code" => 1,
                        "message" => "Bạn mua đủ gói tu luyện super trong tháng này."
                    ]);
                }
                // biến lưu lại chỉ số +
                $add = [
                    "exp" => $player->exp + 500000, //cộng 500k exp
                    "sum_max_hp" => $player->sum_max_hp + 4500, // cộng 900 hp
                    "sum_max_mp" => $player->sum_max_mp + 4500, //cộng 900 mp
                    "sum_atk" => $player->sum_atk + 1500, //cộng 250 sát thương
                    "sum_def" => $player->sum_def + 1000, //cộng 180 phòng thủ
                    "pack_" . $pack =>  $player["pack_$pack"] + 1, //cộng vào gói pack
                    "linh_thach" => $player->linh_thach -  $linhThach[$pack], //trừ linh thạch

                ];

                //kiểm tra nếu tạo nhân vật dưới 30 và chua mua thì mua cái này
                if ($isDate && !$player->is_packnew) {
                    $add['is_packnew'] =  true;

                    $player->update($add);
                    return response()->json([
                        "status" => "success",
                        "code" => 0,
                        "message" => "Bạn mua thành công gói tu luyện supper khi tạo nhân vật dưới 30 ngày.Tăng 1500 sát thương, 1000 phòng thủ, 500k tu vi, 4500 hp, 4500 mp"
                    ]);
                }

                //kiểm tra nếu là vip và tháng này chưa mua thì sẽ mua gói này
                elseif ($user->user_vip  && !$player->is_packvip) {
                    $add['is_packvip'] =  true;
                    $player->update($add);
                    return response()->json([
                        "status" => "success",
                        "code" => 0,
                        "message" => "Bạn mua thành công gói tu luyện supper  với tài khoản vip.Tăng 1500 sát thương, 1000 phòng thủ, 500k tu vi, 4500 hp, 4500 mp"
                    ]);
                }

                //kiểm tra nếu nằm trong top 10 và tháng này chưa mua thì sẽ mua gói này
                elseif ($isInTop10 && !$player->is_packtop) {
                    // + chỉ số
                    $add['is_packtop'] =  true;
                    //thêm gói top tháng này mua rồi
                    $player->update($add);
                    $add['is_packvip'] =  true;
                    $player->update($add);
                    return response()->json([
                        "status" => "success",
                        "code" => 0,
                        "message" => "Bạn mua thành công gói tu luyện supper khi bạn nằm trong top 10.Tăng 1500 sát thương, 1000 phòng thủ, 500k tu vi, 4500 hp, 4500 mp"
                    ]);
                } else {
                    return response()->json([
                        "status" => "error",
                        "code" => 1,
                        "message" => "Bạn mua đủ gói tu luyện supper theo yêu cầu trong tháng này."
                    ]);
                }
                break;
            default:

                return response()->json([
                    "status" => "error",
                    "code" => 1,
                    "message" => "Chọn gói tu luyện không hợp lệ."
                ]);


                break;
        }
    }

    public function packTuLuyenIndex()
    {
        return view('pk.uu_dai_tu_luyen');
    }
    public function packKhivan($pack)
    {
        //pack = 1 gói 1 tháng
        //pack = 2 gói 3 tháng
        //pack = 3 gói 6 tháng
        //số linh thạch cần để mua theo gói
        $linhThach = [1 => ["lt" => 1000, "m" => 1], 2 => ["lt" => 2850, "m" => 3], 3 => ["lt" => 5400, "m" => 6]];
        $user = $this->checklogin();
        //Kiểm tra đăng nhập

        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }

        $player = $user->get_charaters()->first();
        //Kiểm tra xem tạo nhân vật chưa
        if (!$player) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }

        // dd($player->linh_thach,$linhThach[$pack]["lt"]);

        if ($player->is_auto) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đang còn thời gian auto không thể mua thêm"
            ]);
        }

        $now = Carbon::now();
        if ($player->date_auto > $now) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn đang còn thời gian auto không thể mua thêm"
            ]);
        }
        if ($player->linh_thach < $linhThach[$pack]["lt"]) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ linh thạch để mua gói auto đã chọn"
            ]);
        }
        $add = [
            "is_auto" => true,
            "linh_thach" => $player->linh_thach - $linhThach[$pack]["lt"],
        ];
        switch ($pack) {

            case 1:
                $add["luk"] = 100;
                $add["date_auto"] = $now->addMonths($linhThach[$pack]["m"]);
                break;
            case 2:
                $add["luk"] = 100;
                $add["date_auto"] = $now->addMonths($linhThach[$pack]["m"]);
                break;
            case 3:
                $add["luk"] = 100;
                $add["date_auto"] = $now->addMonths($linhThach[$pack]["m"]);
                break;
            default:
                return response()->json([
                    "status" => "error",
                    "code" => 1,
                    "message" => "Gói auto không chính xác"
                ]);
        }
        $player->update($add);
        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => "Bạn đã mua thành công gói auto " . $linhThach[$pack]["m"] . " tháng. Sử dụng auto đến ngày: $player->date_auto"
        ]);
    }
    public function packKhivanIndex()
    {
        $user = $this->checklogin();
        return view('pk.uu_dai_khi_van', compact('user'));
    }

    public function pkModeThuong()
    {
        $user = $this->checklogin();
        //Kiểm tra đăng nhập
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $player = $user->get_charaters()->first();
        //Kiểm tra xem tạo nhân vật chưa
        if (!$player) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
            // flash()->error('Bạn chưa tạo nhân vật');
            // return redirect()->route('tuluyen.create');
        }
        //Kiểm tra xem có phải đang ở chế độ thường
        if ($player->pkmode == 0) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Hiện tại đang ở chế độ pk thường"
            ]);
        } else {
            $player->update(['pkmode' => 0]);
            return response()->json([
                "status" => "success",
                "code" => 0,
                "message" => "Chuyển sang chế độ pk thường"
            ]);
            // flash()->success('Chuyển sang chế độ pk thường');

            // return view('pk.index');
        }
    }


    public function pkModeCaoCap()
    {
        $user = $this->checklogin();
        //Kiểm tra đăng nhập
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $player = $user->get_charaters()->first();
        //Kiểm tra xem tạo nhân vật chưa
        if (!$player) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
            // flash()->error('Bạn chưa tạo nhân vật');
            // return redirect()->route('tuluyen.create');
        }
        if ($player->linh_thach < 25) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ 25 linh thạch để chuyển qua pk cao cấp"
            ]);
            // flash()->error('Bạn chưa tạo nhân vật');
            // return redirect()->route('tuluyen.create');
        }
        //Kiểm tra xem có phải đang ở chế cao cấp
        if ($player->pkmode == 1) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Hiện tại đang ở chế độ pk cao cấp"
            ]);
            // flash()->error('Hiện tại đang ở chế độ pk cao cấp');
            // return view('pk.index');
        } else {
            $player->update(['pkmode' => 1]);
            return response()->json([
                "status" => "success",
                "code" => 0,
                "message" => "Chuyển sang chế độ pk cao cấp"
            ]);
            // flash()->success('Chuyển sang chế độ pk cao cấp');
            // $player->update(['pkmode' => 1]);
            // return view('pk.index');
        }
    }

    public function listChienBao()
    {
        $user = $this->checklogin();
        //Kiểm tra đăng nhập
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $listCB = $user->get_chienbao()->take(10)->orderByDesc('created_at')->get();
        return $listCB;
    }
    public function showChienBao($id)
    {
        $user = $this->checklogin();
        //Kiểm tra đăng nhập
        if (!$user) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa đăng nhập"
            ]);
        }
        $listCB = $user->get_chienbao()->where('id', $id)->where('user_id', $user->id)->first();
        if (!$listCB) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "ID Chiến báo không chính xác."
            ]);
        }

        $temptext = $listCB->result;
        $tempturn = json_decode($listCB->turns);
        $temp = "";
        foreach ($tempturn as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $temp .= ".\n$value3.\n";
                }

            }
        }
        if (empty($temp)) {
            if ($listCB->win) {
                $temptext .= "\n Bạn quá mạnh nên mới chỉ trừng mắt đối phương đã xỉu. Không có quá trình pk.";
            } else {
                $temptext .= "\n Bạn quá yếu nên đối phương mới chỉ trừng mắt bạn đã xỉu. Không có quá trình pk.";
            }
        }else{
            $temptext .=$temp;
        }
        $temptext = str_replace("\n","<br>",$temptext);
        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => $temptext
        ]);

        dd($listCB);
        return $listCB;
    }

    public function muaHoDao()
    {
        $user = $this->checklogin();
        $linhThach = 1000; // số linh thạch cần để mua hộ đạo
        //Kiểm tra đăng nhập
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $player = $user->get_charaters()->first();
        //Kiểm tra xem tạo nhân vật chưa
        if (!$player) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn chưa tạo nhân vật"
            ]);
        }

        //Kiểm tra xem đã mua hộ đạo chưa
        if ($player->is_hodao) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Thời gian hộ đạo đang còn hạn sử dụng."
            ]);
        }
        $now = Carbon::now();
        if ($player->date_hodao > $now) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Thời gian hộ đạo đang còn hạn sử dụng."
            ]);
        }
        //kiểm tra xem có đủ linh thạch không
        if ($player->linh_thach < $linhThach) {
            return response()->json([
                "status" => "error",
                "code" => 1,
                "message" => "Bạn không đủ linh thạch để mua hộ đạo."
            ]);
        }
        $player->update([
            "is_hodao" => true,
            "date_hodao" => $now->addMonth(),
            "linh_thach" => $player->linh_thach - $linhThach
        ]);
        return response()->json([
            "status" => "success",
            "code" => 0,
            "message" => 'Bạn đã mua hộ đạo đến ngày: ' . $player->date_hodao
        ]);
    }

    public function is_pkconfirm()
    {
        $user = $this->checklogin();
        if (!$user) {
            flash()->error('Bạn chưa đăng nhập');
            return redirect()->route('home');
        }
        $player =  $user->get_charaters()->first();
        if (!$player) {
            flash()->error('Bạn chưa tạo nhân vật');
            return redirect()->route('tuluyen.create');
        }
        if (!$player->is_pkconfirm) {
            $player->update([
                "is_pkconfirm" => 1,
                "tichphan" => 100
            ]);
        }
    }

    protected function ketquapk($player1,  $player2, $user, $user2, $cb1, $cb2, $revert = false)
    {
        $notify = new Model_chienBao;
        $notify2 = new Model_chienBao;

        //user tấn công
        $notify->user_id = $player1->user_id;
        $notify->player1_id = $player1->user_id;
        $notify->player2_id = $player2->user_id;
        $notify->win = false;
        //user thủ
        $notify2->user_id = $player2->user_id;
        $notify2->player1_id = $player1->user_id;
        $notify2->player2_id = $player2->user_id;
        $notify2->win = true;
        // Trừ tích phân người thua và thêm tích phân cho người thắng
        //kiểm tra tích phân để trừ
        $text = 'Bạn đã tấn công "' . "$user2->name" . '"' . " (id: $user2->id) và thua sấp mặt. \n\n";
        $text2 = '"' . "$user->name" . '"' . " (id: $user->id)" . '" ' . "tấn công bạn và bạn đã phòng thủ thành công, tự nhiên có người ship đồ. \n\n";
        if ($player1->tichphan < 10) {
            // nhỏ hơn 10 thì trừ toàn bộ tích phân
            $tichphan = $player1->tichphan;
        } elseif ($player1->tichphan < 100) {
            //nhỏ hơn 100 sẽ trừ mỗi lần 10
            $tichphan = 10;
        } else {
            // tính 10% tích phân của người thua để trừ
            $tichphan = round($player1->tichphan * 0.1);
        };

        $player1->tichphan -= $tichphan; //trừ tích phân bên thua
        $text .= "Bạn bị trừ $tichphan tích phân, bạn còn lại $player1->tichphan tích phân\n";
        //kiểm tra căn cốt nhỏ hơn 5 sẽ set về 0
        if ($player1->can_co < 5) {
            $player1->can_co = 0;
        } else {
            $player1->can_co -= 5; // trừ 5 căn cốt cho người thua
        }
        $text .= "Bạn bị trừ 5 căn cốt, bạn còn lại $player1->can_co căn cốt";
        //kiểm tra chế độ pk để + điểm
        if ($player1->pkmode == 0) {
            //pk thường
            $temp = 10 + $tichphan;
            $player2->tichphan += $temp; // +10 tích phân và 10% tích phân cho người thắng
            $text2 .= "Bạn được +$temp tích phân, tích phân hiện tại là $player2->tichphan\n";
        } elseif ($player1->pkmode == 1) {
            //pk cao cấp

            $player2->linh_thach += 40; //+40 linh thạch
            $text2 .= "Bạn được +40 linh thạch, linh thạch hiện tại là $player2->linh_thach";

            if ($user2->user_vip) {
                $temp = 90 + $tichphan;
                $player2->tichphan +=   $temp; //+90 và 10% tích phân
                $text2 .= "Bạn được +$temp tích phân, tích phân hiện tại là $player2->tichphan\n";
            } else {
                $temp = 10 + $tichphan;
                $player2->tichphan +=   $temp; // +10 tích phân và 10% tích phân cho người thắng
                $text2 .= "Bạn được +$temp tích phân, tích phân hiện tại là $player2->tichphan\n";
            }
        }
        $player1->lose += 1; // cộng 1 vào lượt thua cho người tấn công
        $player2->win += 1; // cộng 1 vào lượt thắng cho người phòng thủ
        $player1->tongpk += 1; // cộng 1 vào tổng số lượt pk cho người tấn công
        $player2->tongpk += 1; // cộng 1 vào tổng  số lượt pk cho người thủ
        $player1->datepk = null;
        $player1->doithu = null;
        $notify->result = $text;
        $notify->turns = json_encode($cb1);
        $notify->save();

        $notify2->result = $text2;
        $notify2->turns = json_encode($cb2);
        $notify2->save();


        // set lại hp
        $player1->hp = $player1->max_hp + $player1->sum_max_hp;
        $player2->hp = $player2->max_hp + $player2->sum_max_hp;
        // set lại mp
        $player1->mp = $player1->max_mp + $player1->sum_max_mp;
        $player2->mp = $player2->max_mp + $player2->sum_max_mp;
        //-1 lượt pk
        $player1->luotpk -= 1;
        $player1->is_pk = false;



        //+chiến báo
        $player1->chien_bao += 1;
        $player2->chien_bao += 1;

        //lưu vào db
        $player1->save();
        $player2->save();
    }
    protected function checklogin()
    {
        if (!auth()->guard('web')->check()) {
            return false;
        } else {
            return auth()->guard('web')->user();
        }
    }
}
