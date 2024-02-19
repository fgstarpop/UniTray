<?php

namespace App\Console\Commands;
use App\Domain\Story\Models\Story;
use App\User;
use Illuminate\Console\Command;

class ResetOverCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overcount:reset_month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset overcount for user and story';

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
        Story::query()->update(['audio_month' => 0]);
        User::query()->update(['turn_over' => 0, 'chapters_created' => 0]);
    }
}
