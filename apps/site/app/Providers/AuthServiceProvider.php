<?php

namespace App\Providers;

use App\Domain\Acl\Models\Role;
use App\Domain\Admin\Models\Admin;
use App\Domain\Admin\Policies\AdminPolicy;
use App\Domain\Acl\Policies\RolePolicy;
use App\Domain\Banner\Models\Banner;
use App\Domain\Banner\Policies\BannerPolicy;
use App\Domain\LogActivity\Policies\LogActivityPolicy;
use App\Domain\LogSearch\Models\LogSearch;
use App\Domain\LogSearch\Policies\LogSearchPolicy;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Policies\PagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

use App\Domain\Policy\WalletsPolicy;
use App\Domain\Category\Policies\CategoryPolicy;
use App\Domain\Type\Policies\TypePolicy;
use App\Domain\Story\Policies\StoryPolicy;
use App\Domain\Policy\GamePolicy;
use App\Domain\Policy\WinPolicy;
use App\Domain\Policy\UserPolicy;
use App\Domain\Policy\WalletTransactionPolicy;
use App\Domain\Recharge\Policies\RechargeTransactionPolicy;
use App\Domain\Policy\WithdrawTransactionPolicy;
use App\Domain\Policy\OrderPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        Admin::class => AdminPolicy::class,
        Page::class => PagePolicy::class,
        Activity::class => LogActivityPolicy::class,
        LogSearch::class => LogSearchPolicy::class,
        Banner::class => BannerPolicy::class,

        Wallet::class => WalletsPolicy::class,
        Category::class => CategoryPolicy::class,
        Type::class => TypePolicy::class, 
        Story::class => StoryPolicy::class,
        Game::class => GamePolicy::class,
        Win::class => WinPolicy::class,
        User::class => UserPolicy::class,
        WalletTransaction::class => WalletTransactionPolicy::class,
        RechargeTransaction::class => RechargeTransactionPolicy::class,
        WithdrawTransaction::class => WithdrawTransactionPolicy::class,
        Order::class => OrderPolicy::class,
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Gate::define('settings', function ($user) {
//            return $user->isAdmin;
//        });

        Gate::before(function (Admin $admin, $ability) {
            return $admin->email == config('ecc.admin_email') || $admin->hasRole('superadmin') ? true : null;
        });
    }
}
