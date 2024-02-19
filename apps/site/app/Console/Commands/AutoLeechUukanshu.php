<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoLeechUukanshu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:uukanshu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leech https://www.uukanshu.com/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL = 'https://www.uukanshu.com/b/{bookid}/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $regex = '#<li>.*?class="sm".*?class="poptext".*?/b/(?<bookid>\d+)/"#s';
        $html = Http::timeout(60)->get("https://www.uukanshu.com/")->body();
        if (preg_match_all($regex, $html, $matches, PREG_SET_ORDER, 0)) {
            $admin = User::where('id', 16)->first();
            foreach ($matches as $key => $value) {
                if (setting_custom('uukanshu_leech_book_id') == $value['bookid']) {
                    break;
                }
                $url = self::URL;
                $url = str_replace('{bookid}', $value['bookid'], $url);
                $html_list = Http::timeout(60)->get($url);
                $re = '#<li><a href="/b/\d+/(\d+).html#s';
                preg_match_all($re, $html_list, $list, PREG_SET_ORDER, 0);
                if (sizeof($list) >= 120) {
                    embedStoryUukanshu($url, '', $admin);
                    sleep(rand(1, 5));
                }
            }
            setting_custom('uukanshu_leech_book_id',  $matches[0]['bookid']);
        }

        return 0;
    }
}
