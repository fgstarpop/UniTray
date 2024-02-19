<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\TuLuyen\Model_charater;
use App\TuLuyen\Model_chienBao;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VinhDanh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'character:vinhdanh';

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
     */
    public function handle()
    {
        Model_charater::query()->update(['vinh_danh' => 0]);
        $update = Model_charater::orderByDesc('tichphan')
        ->take(10);
        $update->update([
            'vinh_danh'=> 1,
        ]);
        $linhThach = 1000;
        $id = Model_charater::where('vinh_danh', 1)->orderByDesc('tichphan')->pluck('user_id');
        for($i = 0;$i< count($id); $i++) {
            $updateLT = Model_charater::where("user_id",$id[$i])->first();
            $updateLT->update([
                'linh_thach'=> $updateLT->linh_thach +  $linhThach,
            ]);
            $linhThach = $linhThach - 50;
        }
    }
}
