<?php

namespace App\Console\Commands;

use App\Domain\Story\Models\Story;
use Illuminate\Console\Command;

class UpdateViewDayStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:reset_view_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset view day story';

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
        Story::query()->update(['view_day' => 0]);
    }
}
