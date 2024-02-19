<?php

namespace App\Console\Commands;

use App\Domain\Story\Models\Story;
use Illuminate\Console\Command;

class ResetNominationStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:reset_nomination';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Nomination for a week';

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
            Story::query()->update(['nomination' => 0]);
    }
}
