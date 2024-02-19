<?php

namespace App\Console\Commands;

use App\Scraper\LeechTrxs;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoLeechTrxs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:trxs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leech https://trxs.cc/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL = "https://trxs.cc";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (setting('is_leech_on_trxs', false)) {
            $count = 0;
            $admin = User::where('id', 16)->first();
            do {
                $bot = new LeechTrxs();
                if (Carbon::now()->diffInDays(setting_custom('leech_trxs_last_success_date', null,  Carbon::now())) > 1) {
                    setting_custom('leech_trxs_book_id', setting_custom('leech_trxs_last_success_id', null, 1) + 1);
                }
                $bookId = setting_custom('leech_trxs_book_id', null, 1);
                if ($bot->scrape($bookId, self::URL, '/tongren/', $admin)) {
                    $count++;
                    setting_custom('leech_trxs_last_success_id', setting_custom('leech_trxs_book_id', null, 1));
                    setting_custom('leech_trxs_last_success_date', Carbon::now());
                }
                setting_custom('leech_trxs_book_id', setting_custom('leech_trxs_book_id', null, 1) + 1);
            } while ($count < 5 || setting_custom('leech_trxs_book_id', null, 1) - setting_custom('leech_trxs_last_success_id', null, 1) > 10000);
        }
        return 0;
    }
}
