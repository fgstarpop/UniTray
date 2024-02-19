<?php

declare(strict_types=1);

use App\Domain\Acl\Models\Role;
use App\Domain\Admin\Models\Admin;
use App\Domain\Banner\Models\Banner;
use App\Domain\Category\Models\Category;
use App\Domain\Option\Models\OptionType;
use App\Domain\Page\Models\Page;
use App\Domain\Post\Models\Post;
use App\Domain\Recharge\Models\RechargePackage;
use App\Domain\Slider\Models\Slider;
use App\Domain\Taxonomy\Models\Taxon;
use App\Domain\Taxonomy\Models\Taxonomy;
use App\Domain\Type\Models\Type;
use App\Domain\Story\Models\Story;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Spatie\Activitylog\Models\Activity;
use App\Domain\Menu\Models\Menu;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Admin\Models\GoldGift;
// Home
Breadcrumbs::for('admin.dashboard', function (BreadcrumbsGenerator $trail) {
    $trail->push(__('Trang chủ'), route('admin.dashboard'), ['icon' => 'fal fa-home']);
});

// Home => Account Settings
Breadcrumbs::for('admin.account-settings.edit', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Tải tài khoản'), route('admin.account-settings.edit'));
});
/*
|--------------------------------------------------------------------------
| System Breadcrumbs
|--------------------------------------------------------------------------
*/

// Home > Admins
Breadcrumbs::for('admin.admins.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Tài khoản'), route('admin.admins.index'), ['icon' => 'fal fa-user']);
});

// Home > Admins > Create

Breadcrumbs::for('admin.admins.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.admins.index');
    $trail->push(__('Tạo'), route('admin.admins.create'));
});

// Home > Admins > [admin] > Edit
Breadcrumbs::for('admin.admins.edit', function (BreadcrumbsGenerator $trail, Admin $admin) {
    $trail->parent('admin.admins.index');
    $trail->push($admin->email, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.admins.edit', $admin));
});

// Home > Roles
Breadcrumbs::for('admin.roles.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Vai trò'), route('admin.roles.index'), ['icon' => 'fal fa-project-diagram']);
});

// Home > Roles > Create

Breadcrumbs::for('admin.roles.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.roles.index');
    $trail->push(__('Tạo'), route('admin.roles.create'));
});

// Home > Roles > [role] > Edit
Breadcrumbs::for('admin.roles.edit', function (BreadcrumbsGenerator $trail, Role $role) {
    $trail->parent('admin.roles.index');
    $trail->push($role->display_name, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.roles.edit', $role));
});

// Home > Categories
Breadcrumbs::for('admin.categories.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Thể loại'), route('admin.categories.index'), ['icon' => 'fal fa-folder-tree']);
});

// Home > Categories > Create

Breadcrumbs::for('admin.categories.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.categories.index');
    $trail->push(__('Tạo'), route('admin.categories.create'));
});

// Home > Categories > [Category] > Edit
Breadcrumbs::for('admin.categories.edit', function (BreadcrumbsGenerator $trail, Category $category) {
    $trail->parent('admin.categories.index');
    $trail->push(__('Chỉnh sửa'), route('admin.categories.edit', $category));
});

// Home > Categories > [Category] > Update
Breadcrumbs::for('admin.categories.update', function (BreadcrumbsGenerator $trail, Category $category) {
    $trail->parent('admin.categories.index');
    $trail->push(__('Cập nhật'), route('admin.categories.update', $category));
});


// Home > Types
Breadcrumbs::for('admin.types.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Loại truyện'), route('admin.types.index'), ['icon' => 'fal fa-tags']);
});

// Home > Types > Create

Breadcrumbs::for('admin.types.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.types.index');
    $trail->push(__('Tạo'), route('admin.types.create'));
});

// Home > Types > [Type] > Edit
Breadcrumbs::for('admin.types.edit', function (BreadcrumbsGenerator $trail, Type $type) {
    $trail->parent('admin.types.index');
    $trail->push(__('Chỉnh sửa'), route('admin.types.edit', $type));
});

// Home > Types > [Type] > Update
Breadcrumbs::for('admin.types.update', function (BreadcrumbsGenerator $trail, Type $type) {
    $trail->parent('admin.types.index');
    $trail->push(__('Cập nhật'), route('admin.types.update', $type));
});

// Home > Order
Breadcrumbs::for('admin.order.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Đơn mua chương VIP'), route('admin.order.index'), ['icon' => 'fal fa-list']);
});
// Home > Stories
Breadcrumbs::for('admin.stories.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Truyện'), route('admin.stories.index'), ['icon' => 'fal fa-book-open']);
});

// Home > Recharge Transactions
Breadcrumbs::for('admin.recharge_transactions.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Giao dịch nạp tiền'), route('admin.pages.index'), ['icon' => 'fal fa-money-check-alt']);
});

// Home > Log Search
Breadcrumbs::for('admin.log_search.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Lịch sử tìm kiếm'), route('admin.log_search.index'), ['icon' => 'fal fa-history']);
});

// Home > Pages
Breadcrumbs::for('admin.pages.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Trang'), route('admin.pages.index'), ['icon' => 'fal fa-file']);
});

// Home > Pages > Create

Breadcrumbs::for('admin.pages.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.pages.index');
    $trail->push(__('Tạo'), route('admin.pages.create'));
});

// Home > Pages > [page] > Edit
Breadcrumbs::for('admin.pages.edit', function (BreadcrumbsGenerator $trail, Page $page) {
    $trail->parent('admin.pages.index');
    $trail->push(__('Chỉnh sửa'), route('admin.pages.edit', $page));
});

// Home > Pages > [page] > Update
Breadcrumbs::for('admin.pages.update', function (BreadcrumbsGenerator $trail, Page $page) {
    $trail->parent('admin.pages.index');
    $trail->push(__('Cập nhật'), route('admin.pages.update', $page));
});

// Home > RechargePackage
Breadcrumbs::for('admin.recharge-packages.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Gói nạp'), route('admin.recharge-packages.index'), ['icon' => 'fal fa-money-check-alt']);
});

// Home > RechargePackage > Create

Breadcrumbs::for('admin.recharge-packages.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.recharge-packages.index');
    $trail->push(__('Tạo'), route('admin.recharge-packages.create'));
});

// Home > RechargePackage > [RechargePackage] > Edit
Breadcrumbs::for('admin.recharge-packages.edit', function (BreadcrumbsGenerator $trail, RechargePackage $rechargePackage) {
    $trail->parent('admin.recharge-packages.index');
    $trail->push(__('Chỉnh sửa'), route('admin.recharge-packages.edit', $rechargePackage));
});

// Home > RechargePackage > [RechargePackage] > Update
Breadcrumbs::for('admin.recharge-packages.update', function (BreadcrumbsGenerator $trail, RechargePackage $rechargePackage) {
    $trail->parent('admin.recharge-packages.index');
    $trail->push(__('Cập nhật'), route('admin.recharge-packages.update', $rechargePackage));
});


// Home > Posts
Breadcrumbs::for('admin.posts.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Bài viết'), route('admin.posts.index'), ['icon' => 'fal fa-edit']);
});

// Home > Posts > Create

Breadcrumbs::for('admin.posts.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.posts.index');
    $trail->push(__('Tạo'), route('admin.posts.create'));
});

// Home > Posts > [admin] > Edit
Breadcrumbs::for('admin.posts.edit', function (BreadcrumbsGenerator $trail, Post $post) {
    $trail->parent('admin.posts.index');
    $trail->push($post->title, '#');
    $trail->push(__('Chỉnh sửa'), route('admin.posts.edit', $post));
});

// Home > Banners
Breadcrumbs::for('admin.banners.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Banner'), route('admin.banners.index'), ['icon' => 'fal fa-image']);
});

// Home > Banners > Create

Breadcrumbs::for('admin.banners.create', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.banners.index');
    $trail->push(__('Tạo'), route('admin.banners.create'));
});

// Home > Admins > [banner] > Edit
Breadcrumbs::for('admin.banners.edit', function (BreadcrumbsGenerator $trail, Banner $banner) {
    $trail->parent('admin.banners.index');
    $trail->push(__('Chỉnh sửa'), route('admin.banners.edit', $banner));
});

// Home > Admins > [banner] > Update
Breadcrumbs::for('admin.banners.update', function (BreadcrumbsGenerator $trail, Banner $banner) {
    $trail->parent('admin.banners.index');
    $trail->push(__('Cập nhật'), route('admin.banners.update', $banner));
});

// Home > [Setting] > Edit
Breadcrumbs::for('admin.settings.edit', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Cài đặt chung'), route('admin.settings.edit'), ['icon' => 'fal fa-money-check-alt']);
});

// Home > Users
Breadcrumbs::for('admin.users.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Người dùng'), route('admin.users.index'), ['icon' => 'fal fa-users']);
});

// Home > Withdraw
Breadcrumbs::for('admin.withdraw.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Rút tiền'), route('admin.withdraw.index'), ['icon' => 'fal fa-money-bill-wave-alt']);
});


// Home > WalletTransaction
Breadcrumbs::for('admin.wallet-transaction.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Giao dịch ví'), route('admin.wallet-transaction.index'), ['icon' => 'fal fa-wallet']);
});

// Home > Wallet
Breadcrumbs::for('admin.wallets.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Ví'), route('admin.wallets.index'), ['icon' => 'fal fa-wallet']);
});

// Home > Wallet > Show
Breadcrumbs::for('admin.wallets.show', function (BreadcrumbsGenerator $trail, Wallet $wallet) {
    $trail->parent('admin.wallets.index');
    $trail->push('Lịch sử giao dịch', route('admin.wallets.show', $wallet));
});


// Home > Wallets > [wallet] > Edit
Breadcrumbs::for('admin.wallets.edit', function (BreadcrumbsGenerator $trail, Wallet $wallets) {
    $trail->parent('admin.wallets.index');
    $trail->push(__('Chỉnh sửa'), route('admin.wallets.edit', $wallets));
});

// Home > Wallets > [wallet] > Update
Breadcrumbs::for('admin.wallets.update', function (BreadcrumbsGenerator $trail, Wallet $wallets) {
    $trail->parent('admin.wallets.index');
    $trail->push(__('Cập nhật'), route('admin.wallets.update', $wallets));
});



// Home > GoldGift
Breadcrumbs::for('admin.gold-gift.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('Giao dịch tặng vàng'), route('admin.gold-gift.index'), ['icon' => 'fal fa-gift']);
});

// Home > Log Activities
Breadcrumbs::for('admin.log-activities.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Lịch sử thao tác ', route('admin.log-activities.index'), ['icon' => 'fal fa-history']);
});
Breadcrumbs::for('admin.log-activities.show', function (BreadcrumbsGenerator $trail, Activity $activity) {
    $trail->parent('admin.log-activities.index');
    $trail->push('Chi tiết thao tác', route('admin.log-activities.show', $activity));
});
