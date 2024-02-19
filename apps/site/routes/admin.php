<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\AccountSettingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\VerificationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MostVisitedPageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RechargePackageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TopReferrerController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UploadTinymceController;
use App\Http\Controllers\Admin\LogActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WalletsController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\TroChoiController;
use App\Http\Controllers\Admin\WinnerController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\RequestWithdrawController;
use App\Http\Controllers\Admin\RechargeTransactionController;
use App\Http\Controllers\Admin\GoldGiftController;
use App\Http\Controllers\Admin\LogSearchController;
use App\Http\Controllers\Admin\OrderController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Registration Routes...
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Password Confirmation Routes...
    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);

    // Email Verification Routes...
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    // Route Dashboards
    Route::middleware('auth')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/filter', [DashboardController::class, 'filter'])->name('dasboard.filter');

            //Upload Tinymce
            Route::post('uploads-tinymce', UploadTinymceController::class)->name('public.upload-tinymce');

            // System Route
            Route::post('/admins/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admins.bulk-delete');
            Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
            Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
            Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
            Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
            Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
            Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');

            Route::post('/roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
            Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');

            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

            Route::get('/account-settings', [AccountSettingController::class, 'edit'])->name('account-settings.edit');
            Route::put('/account-settings', [AccountSettingController::class, 'update'])->name('account-settings.update');

            Route::get('/analytics', AnalyticsController::class)->name('analytics');
            Route::get('/top-referrers', TopReferrerController::class)->name('top-referrers');
            Route::get('/most-visited-pages', MostVisitedPageController::class)->name('most-visited-pages');

            // CATEGORY
            Route::post('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::post('/categories/{category}/status', [CategoryController::class, 'changeStatus'])->name('categories.change.status');

            // TYPE
            Route::post('/types/bulk-delete', [TypeController::class, 'bulkDelete'])->name('types.bulk-delete');
            Route::get('/types', [TypeController::class, 'index'])->name('types.index');
            Route::get('/types/create', [TypeController::class, 'create'])->name('types.create');
            Route::post('/types', [TypeController::class, 'store'])->name('types.store');
            Route::get('/types/{type}/edit', [TypeController::class, 'edit'])->name('types.edit');
            Route::delete('/types/{type}', [TypeController::class, 'destroy'])->name('types.destroy');
            Route::put('/types/{type}', [TypeController::class, 'update'])->name('types.update');
            Route::post('/types/{type}/status', [TypeController::class, 'changeStatus'])->name('types.change.status');

             // STORY
             Route::post('/stories/bulk-delete', [StoryController::class, 'bulkDelete'])->name('stories.bulk-delete');
             Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
             Route::post('/stories/{story}/status', [StoryController::class, 'changeStatus'])->name('stories.change.status');
             Route::delete('/stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');

            // PAGE
            Route::post('/pages/bulk-delete', [PageController::class, 'bulkDelete'])->name('pages.bulk-delete');
            Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
            Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
            Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
            Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
            Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
            Route::post('/pages/{page}/status', [PageController::class, 'changeStatus'])->name('pages.change.status');
            //Upload Tinymce
            Route::post('pages/upload/image', [PageController::class, 'upLoadFileImage'])->name('pages.upload.image');

            // POST
            Route::post('/posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulk-delete');
            Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
            Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
            Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
            Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
            Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
            Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::post('/posts/{post}/status', [PostController::class, 'changeStatus'])->name('posts.change.status');
            Route::post('/posts/bulk-status', [PostController::class, 'bulkStatus'])->name('posts.bulk.status');

            // BANNER
            Route::post('/banners/bulk-delete', [BannerController::class, 'bulkDelete'])->name('banners.bulk-delete');
            Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
            Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
            Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
            Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');
            Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
            Route::put('/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
            Route::post('/banners/{banner}/status', [BannerController::class, 'changeStatus'])->name('banners.change.status');
            Route::post('/banners/bulk-status', [BannerController::class, 'bulkStatus'])->name('banners.bulk.status');
            //history
            Route::get('/log-search', [LogSearchController::class, 'index'])->name('log_search.index');

            //order
            Route::get('/order', [OrderController::class, 'index'])->name('order.index');
            Route::post('/orders/{order}/status', [OrderController::class, 'changeStatus'])->name('order.change.status');

            // RechargePackage
            Route::post('/recharge-packages/bulk-delete', [RechargePackageController::class, 'bulkDelete'])->name('recharge-packages.bulk-delete');
            Route::get('/recharge-packages', [RechargePackageController::class, 'index'])->name('recharge-packages.index');
            Route::get('/recharge-packages/create', [RechargePackageController::class, 'create'])->name('recharge-packages.create');
            Route::post('/recharge-packages', [RechargePackageController::class, 'store'])->name('recharge-packages.store');
            Route::get('/recharge-packages/{rechargePackage}/edit', [RechargePackageController::class, 'edit'])->name('recharge-packages.edit');
            Route::delete('/recharge-packages/{rechargePackage}', [RechargePackageController::class, 'destroy'])->name('recharge-packages.destroy');
            Route::put('/recharge-packages/{rechargePackage}', [RechargePackageController::class, 'update'])->name('recharge-packages.update');
            Route::post('/recharge-packages/{rechargePackage}/status', [RechargePackageController::class, 'changeStatus'])->name('recharge-packages.change.status');
            //Upload Tinymce
            Route::post('recharge-packages/upload/image', [RechargePackageController::class, 'upLoadFileImage'])->name('recharge-packages.upload.image');

            // Account User
            Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/users/{user}/status', [UserController::class, 'changeStatus'])->name('users.change.status');
            Route::post('/users/bulk-status', [UserController::class, 'bulkStatus'])->name('users.bulk.status');
            Route::post('/users/{user}/type', [UserController::class, 'changeType'])->name('users.change.type');
            Route::post('/users/bulk-type', [UserController::class, 'bulkType'])->name('users.bulk.type');

            //Game
            Route::get('/game', [TroChoiController::class, 'index'])->name('game.index'); 
            Route::post('/game', [TroChoiController::class, 'store'])->name('game.store');

            Route::get('/winner', [WinnerController::class, 'index'])->name('win.index'); 
            //Recharge Transaction
             Route::get('/recharge-transaction', [RechargeTransactionController::class, 'index'])->name('recharge_transactions.index');
             Route::post('/recharge-transaction/{rechargeTransaction}/status', [RechargeTransactionController::class, 'changeStatus'])->name('recharge_transactions.change.status');

            //Wallets
            Route::get('/wallets', [WalletsController::class, 'index'])->name('wallets.index');
            Route::get('/wallets/{wallet}/edit', [WalletsController::class, 'edit'])->name('wallets.edit');
            Route::put('/wallets/{wallet}', [WalletsController::class, 'update'])->name('wallets.update');

            //Wallets
            Route::get('/wallets', [WalletsController::class, 'index'])->name('wallets.index');
            Route::get('/wallets/{wallet}/edit', [WalletsController::class, 'edit'])->name('wallets.edit');
            Route::put('/wallets/{wallet}', [WalletsController::class, 'update'])->name('wallets.update');

             //Wallets Transaction
             Route::get('/wallet-transaction', [WalletTransactionController::class, 'index'])->name('wallet-transaction.index');
             Route::post('/wallet-transaction/undo/{transaction}', [WalletTransactionController::class, 'undo'])->name('wallet-transaction.undo');

            //Withdraw
            Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
            Route::post('/withdraw/{withdraw}/status', [WithdrawController::class, 'changeStatus'])->name('withdraw.change.status');

            //Gold Gift
            Route::get('/gold-gift', [GoldGiftController::class, 'index'])->name('gold-gift.index');

            // LOG ACTIVITY
            Route::get('/log-activities', [LogActivityController::class, 'index'])->name('log-activities.index');
            Route::get('/log-activities/{log_activitiy}', [LogActivityController::class, 'show'])->name('log-activities.show');
            Route::post('/log-activities/bulk-delete', [LogActivityController::class, 'bulkDelete'])->name('log-activities.bulk-delete');
            Route::delete('/log-activities/{log_activitiy}', [LogActivityController::class, 'destroy'])->name('log-activities.destroy');
        });
});
