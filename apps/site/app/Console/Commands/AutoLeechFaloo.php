<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoLeechFaloo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:faloo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leech https://wap.faloo.com/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL = "https://b.faloo.com/{bookid}.html";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $regex =  '#class="TwoBox02_02".*?b\.faloo\.com/(?<bookid>\d+)\.html#';
        $html = Http::timeout(60)->get("https://b.faloo.com/y_0_0_0_2_0_0_1.html")->body();
        if (preg_match_all($regex, $html, $matches, PREG_SET_ORDER, 0)) {
            $admin = User::where('id', 16)->first();
            foreach ($matches as $key => $value) {
                if (setting_custom('faloo_leech_book_id') == $value['bookid']) {
                    break;
                }
                $url = self::URL;
                $url = str_replace('{bookid}', $value['bookid'], $url);
                embedStoryUukanshu($url, '', $admin);
                sleep(rand(1,5));
            }
            setting_custom('faloo_leech_book_id', $matches[0]['bookid']);
        }

        return 0;
    }
}
