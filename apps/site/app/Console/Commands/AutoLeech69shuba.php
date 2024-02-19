<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AutoLeech69shuba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:69shuba';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hỗ trợ leech 69shuba.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    const URL = 'https://www.69shuba.com/book/{bookid}.htm';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $regex = '#<li>.*?69shuba.com/book/(?<bookid>\d+).htm">#s';
        $html = Http::timeout(60)->get("https://www.69shuba.com/last")->body();
        if (preg_match_all($regex, $html, $matches, PREG_SET_ORDER, 0)) {
            $admin = User::where('id', 16)->first();
            foreach ($matches as $key => $value) {
                if (setting_custom('69shuba_leech_book_id') == $value['bookid']) {
                    break;
                }
                $url = self::URL;
                $url = str_replace('{bookid}', $value['bookid'], $url);
                embedStoryUukanshu($url, '', $admin);
                sleep(rand(1, 5));
            }
            setting_custom('69shuba_leech_book_id',  $matches[0]['bookid']);
        }
        return 0;
    }
}
