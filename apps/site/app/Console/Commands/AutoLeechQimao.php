<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoLeechQimao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:qimao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hỗ trợ leech qimao.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL = 'https://www.qimao.com/shuku/{bookid}/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $regex = '#<li>.*?href="/shuku/(?<bookid>\d+)/"><img#s';
        $html = Http::timeout(60)->get("https://www.qimao.com/shuku/a-a-a-a-a-a-a-update_time-1/")->body();

        if (preg_match_all($regex, $html, $matches, PREG_SET_ORDER, 0)) {
            $admin = User::where('id', 16)->first();
            foreach ($matches as $key => $value) {
                if (setting_custom('qimao_leech_book_id') == $value['bookid']) {
                    break;
                }
                $url = self::URL;
                $url = str_replace('{bookid}', $value['bookid'], $url);
                embedStoryUukanshu($url, '', $admin);
                sleep(rand(1, 5));
            }
            setting_custom('qimao_leech_book_id',  $matches[0]['bookid']);
        }
        return 0;
    }
}
