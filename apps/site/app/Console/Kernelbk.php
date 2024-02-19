<?php

namespace App\Console;
// use App\Console\Commands\AutoLeechFaloo;
// use App\Console\Commands\AutoLeechFanqie;
use Carbon\Carbon;
use App\TuLuyen\Model_charater;
use App\Traits\TuLuyenItems;
use App\Traits\TuLuyenCfg;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\UpdateViewDayStory;
use App\Console\Commands\ResetNominationStory;
use App\Console\Commands\UpdateViewWeekStory;
use App\Console\Commands\ResetOverCount;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		UpdateViewDayStory::class,
		UpdateViewWeekStory::class,
        ResetNominationStory::class,
        ResetOverCount::class,
		// AutoLeechFaloo::class,
		// AutoLeechFanqie::class

	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('story:reset_view_day')->daily();
		$schedule->command('check-vip-expired')->daily()->at('23:59');
		$schedule->command('story:reset_view_week')->weekly();
        $schedule->command('story:reset_nomination')->weekly();
		$schedule->command('ranking-cron')->monthlyOn(28)->at('00:00');
        $schedule->command('overcount:reset_month')->monthly();
		$schedule->command('check-bank-transfer')->everyMinute()->withoutOverlapping();
		//$schedule->command('leech:faloo')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('leech:fanqienovel')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('leech:uukanshu')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('leech:trxs')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('leech:xinyushuwu')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('autoleech:faloo')->everyFiveMinutes()->withoutOverlapping();
		//$schedule->command('autoleech:fanqie')->everyFiveMinutes()->withoutOverlapping();
		// $schedule->command('inspire')->hourly();

		$schedule->call(function () {
            $now = Carbon::now();
            //user is offline after 5 minutes
            Model_charater::where('time_last_online', '<', $now->copy()->subMinutes(5)->toDateTimeString())
                ->update(['is_online' => false]);

            //kiểm tra hộ đạo, nếu hết thời gian sẽ set is_hodao = false
            Model_charater::where('date_hodao', '<', $now)
                ->update(['is_hodao' => false]);
            //kiểm tra gói auto nếu thời gian hết hạn sẽ set is_auto = false
            Model_charater::where('date_auto', '<', $now)
                ->update(['is_auto' => false]);
            //kiểm tra nếu linh thạch dưới 25 và đang ở pk cc thì chuyển về pk thường
            Model_charater::where('linh_thach', '<', 25)
            ->where('pkmode',true)
            ->update(['pkmode' => 0]);
            //kiểm tra có auto và đến thời gian nhặt sẽ tự nhặt

            $players = Model_charater::where("is_auto", 1)
                ->where("time_next_collect" ,'<', $now)
                ->get();
            foreach ($players as $player) {
                $time_min = 220;
                $time_max = 800;
                $time_rand = rand($time_min - $player->luk, $time_max - ($player->luk * 6));
                $time = $time_rand < 120 ? 120 : $time_rand;
                $item = new TuLuyenItems($player);
                $collect = $item->get_collect();
                // dd($collect);
                if ($collect['type'] == 15) {
                    $cfg = new TuLuyenCfg();
                    $rand_lt = $cfg->random_weight([1 => 95, 2 => 4, 3 => 1]);
                    $player->update([
                        'linh_thach' => $player->linh_thach + $rand_lt,
                        'is_collect' => false,
                        'time_next_collect' => $now->copy()->addSeconds($time),
                        'time_last_collect' => $now,
                    ]);
                }else{
                    $player->update([
                        'is_collect' => false,
                        'time_next_collect' => $now->copy()->addSeconds($time),
                        'time_last_collect' => $now,
                    ]);
                }
            }
        })->everyMinute();


        // hàng tháng reset lại tích phân và set top dựa vào tích phân tháng trươc
        $schedule->call(function () {

            $top = 1;
            $players = Model_charater::orderByDesc("tichphan")->get();

            foreach ($players  as $player) {
                $tichphan = $player->tichphan;
                $player->update([
                    "top" => $top, //set top cho user dựa vào tích phân tháng trước
                    "tichphan_month" =>$tichphan ,// lưu lại tích phân để lấy top
                    "pack_1" => 0, //set gói cơ bản về 0
                    "pack_2" => 0, //set gói bình dân về 0
                    "pack_3" => 0, //set gói supper về 0
                    "tichphan" => 100, //set tích phân về lại 100
                    "win" => 0,//set số trận thắng về 0
                    "lose" =>0,//set số trận thua về 0
                    "tongpk" => 0, //set tổng số trận pk về 0
                    "is_packtop" => false, // set kiểm tra mua gói supper cho top hàng tháng về false
                    "is_packvip" => false, // set kiểm tra mua gói supper cho user vip hàng tháng về false
                ]);
                $top += 1;
            }
        })->monthly();




        $schedule->call(function () {
            $users = Model_charater::get();
            foreach ($users as $user) {
                $add = [  ];
                if ($user->luk > 1) {
                    $rand = rand(1, $user->luk);
                    if ($user->is_auto) {
                        $add["luk"] = 100;
                    } else {
                        $add["luk"] = $rand;

                    }

                }

                if($user->get_users->user_vip){
                    $add["luotpk"] = 20; // lượt pk của vip
                }else{
                    $add["luotpk"] = 10;//lượt pk của user thường
                }
                $user->update($add);
            }

        })->daily();
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
