<?php

namespace App\Console\Commands;

use App\Domain\Activity\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckExpiredVip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-vip-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check vip user expired';

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
        $users = User::where('user_vip', '1')->get();

        if (count($users)) {
            $currentDate = Carbon::now();
            foreach ($users as $user) {
                if ($user->vip_expired_date < $currentDate) {
                    try {
                        $user->user_vip = 0;
                        $user->save();
                        Notification::create([
                            'id' => Str::uuid(),
                            'type' => 'vip',
                            'notifiable_type' => 'App\User',
                            'data' => 'Bạn đã hết hạn VIP',
                            'notifiable_id' => 4,
                            'story_id' => 1,
                            'read_at' => null,
                            'count' => 1,
                            'link' => route('user.index', $user->id)
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                    }
                }
            }
        }
        return true;
    }
}
