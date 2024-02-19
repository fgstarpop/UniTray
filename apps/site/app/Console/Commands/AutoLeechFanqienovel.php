<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class AutoLeechFanqienovel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:fanqienovel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leech https://fanqienovel.com/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL = 'https://fanqienovel.com/page/';

    /**
     * Execute the console command.
     *
     * @return int
     */


    public function handle()
    {
        $admin = User::where('id', 16)->first();
        $baseList = Http::timeout(60)->get("https://fanqienovel.com/api/author/library/book_list/v0/?page_count=20&page_index=0&gender=-1&category_id=-1&creation_status=-1&word_count=1&book_type=-1&sort=1")
            ->json();
        if ($baseList['code'] == 0) {
            foreach ($baseList['data']['book_list'] as $datum) {
                if (setting_custom('fanqie_leech_book_id') == $datum['book_id']) {
                    break;
                }
                $url = self::URL . $datum['book_id'];
                embedStoryUukanshu($url, '', $admin);
                sleep(rand(1, 5));
            }
            setting_custom('fanqie_leech_book_id', $baseList['data']['book_list'][0]['book_id']);
        }
        return 0;
    }
}
