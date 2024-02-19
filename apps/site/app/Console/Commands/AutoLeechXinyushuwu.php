<?php

namespace App\Console\Commands;

use App\Domain\Story\Models\Story;
use App\Scraper\LeechXinyushuwu;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoLeechXinyushuwu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leech:xinyushuwu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leech https://m.xinyushuwu1.com/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const URL =  'https://m.xinyushuwu1.com/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (setting('is_leech_on_xinyushuwu', false)) {
            $count = 0;
            $admin = User::where('id', 16)->first();
            do {
                $bot = new LeechXinyushuwu();
                if (Carbon::now()->diffInDays(setting_custom('leech_xinyushuwu_last_success_date', null, Carbon::now())) > 1) {
                    setting_custom('leech_xinyushuwu_book_id', setting_custom('leech_xinyushuwu_last_success_id', null, 1) + 1);
                }
                $bookId = setting_custom('leech_xinyushuwu_book_id', null, 1);
                if ($bot->scrape($bookId, self::URL, '/0/', $admin)) {
                    $count++;
                    setting_custom('leech_xinyushuwu_last_success_id', setting_custom('leech_xinyushuwu_book_id', null, 1));
                    setting_custom('leech_xinyushuwu_last_success_date', Carbon::now());
                }
                setting_custom('leech_xinyushuwu_book_id', setting_custom('leech_xinyushuwu_book_id', null, 1) + 1);
            } while ($count < 5 || setting_custom('leech_xinyushuwu_book_id', null, 1) - setting_custom('leech_xinyushuwu_last_success_id', null, 1) > 1000000);
        }
        return 0;
    }
}
