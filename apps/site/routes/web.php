<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Shop\PageController;
use App\Http\Controllers\Shop\PostController;
use App\Http\Controllers\User\BookController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\User\ChapterController;
use App\Http\Controllers\User\StoryController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\SitemapGenerator;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\GameController;
use App\Http\Controllers\User\PackageController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WithDrawController;
use App\Http\Controllers\Shop\CommentController;
use App\Http\Controllers\Shop\NotificationController;
use App\Http\Controllers\User\WorldCup;
use App\Http\Controllers\ControllerTuLuyen\User_tuluyen;
use App\Http\Controllers\ControllerPK\PKController;
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
//  SitemapGenerator::create(config('app.url')->writeToFile(public_path('sitemap.xml')));

Route::any('thueapis/momo', [PackageController::class, 'thueapimomo'])->name('thueapimomo');
Route::any('thueapis/mbbank', [PackageController::class, 'thueapimb'])->name('thueapimb');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::any('/editname', [HomeController::class, 'editname']);
Route::get('/card', [CardController::class, 'index'])->name('card.index');
Route::get('/card/top', [CardController::class, 'top'])->name('card.top');
Route::post('/card/add', [CardController::class, 'add'])->name('card.add');
Route::post('/callback', [CardController::class, 'callback'])->name('card.callback');
Route::post('/binh-luan', [CommentController::class, 'create'])->name('comment.create');
Route::delete('/binh-luan', [CommentController::class, 'delete'])->name('comment.delete');
Route::get('/truyen/{story}', [\App\Http\Controllers\Shop\StoryController::class, 'show'])->name('story.show');
Route::post('/truyen/{story}/follow', [\App\Http\Controllers\Shop\StoryController::class, 'follow'])->name('story.follow')->middleware('user');
Route::post('/truyen/{story}/whishlist', [\App\Http\Controllers\Shop\StoryController::class, 'whishlist'])->name('story.whishlist')->middleware('user');
Route::post('/change-password', [\App\Http\Controllers\Shop\StoryController::class, 'change'])->name('change.password')->middleware('user');
Route::get('/tim-kiem-truyen', [\App\Http\Controllers\Shop\StoryController::class, 'search'])->name('embedChapter');
Route::get('/cap-nhat-truyen', [\App\Http\Controllers\Shop\StoryController::class, 'updateEmbedStory'])->name('updateEmbedStory');
Route::get('/translate', [ChapterController::class, 'translate']);
Route::get('/readAllComment', [NotificationController::class, 'allComment'])->middleware('user');
Route::get('/readAllPk', [NotificationController::class, 'allComment'])->middleware('user');
Route::get('/readAllPk', [NotificationController::class, 'allComment'])->middleware('user');
Route::get('/readAllNoti', [NotificationController::class, 'allNoti'])->middleware('user');
Route::get('/noti/{id}', [NotificationController::class, 'noti'])->middleware('user');

Route::post('/logins', [LoginController::class, 'postLogin']);
Route::namespace('App\Http\Controllers')->group(function () {
    Auth::routes();
});
Route::get('/trang/{page:slug?}', [PageController::class, 'show'])->name('page.show');
Route::get('/the-loai/{category:slug?}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/loai-truyen/{type:slug?}', [CategoryController::class, 'show'])->name('type.show');
Route::post('/nhan-truyen', [StoryController::class, 'getStory'])->name('getStory');
Route::post('/roi-truyen', [StoryController::class, 'leaveStory'])->name('leaveStory');
Route::prefix('truyen')->name('stories.')->middleware('user')->group(function () {
    Route::get('/', [StoryController::class, 'index'])->name('index');
    Route::get('/them-truyen/moi', [StoryController::class, 'create'])->name('create');
    Route::post('/store', [StoryController::class, 'store'])->name('store');
    Route::get('/sua-truyen/{story:id}', [StoryController::class, 'edit'])->name('edit');
    Route::post('/update/{story:id}', [StoryController::class, 'update'])->name('update');
    Route::post('/update/complete/free/{story:id}', [StoryController::class, 'updateCompleteFree'])->name('update.complete.free');
    Route::delete('/delete/{story:id}', [StoryController::class, 'delete'])->name('delete');
});
Route::middleware(['filter','throttle:120,3'])->get('/doc-truyen/{story:id}', [\App\Http\Controllers\Shop\ChapterController::class, 'show'])->name('chapters.show');

Route::get('/{story:id}/index', [ChapterController::class, 'index'])->name('chapters.index');

Route::prefix('chuong')->name('chapters.')->middleware('user')->group(function () {
    Route::get('/{story:id}/them-chuong', [ChapterController::class, 'create'])->name('create');
    Route::post('/store', [ChapterController::class, 'store'])->name('store');
    Route::get('/sua-chuong/{chapter:id}', [ChapterController::class, 'edit'])->name('edit');
    Route::post('/update/{chapter:id}', [ChapterController::class, 'update'])->name('update');
    Route::delete('/delete/{chapter:id}', [ChapterController::class, 'delete'])->name('delete');
});

    Route::get('/game', [GameController::class, 'index'])->name('user.game');
Route::prefix('user')->name('user.')->middleware('user')->group(function () {
    Route::get('/wallet', [AccountController::class, 'wallet'])->name('wallets');
    Route::get('/game/dat-so', [GameController::class, 'create'])->name('game.create');
    Route::post('/game', [GameController::class, 'order'])->name('game.store');
    Route::delete('/game', [GameController::class, 'delete']);
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order/chapter', [OrderController::class, 'chapter'])->middleware(["throttle:3,1"])->name('order.chapter');
    Route::get('/order/statistic', [OrderController::class, 'statistic'])->name('order.statistic');
    Route::get('/gold-gift', [AccountController::class, 'gift'])->name('gold-gift');
    Route::post('/gold-gift/store', [AccountController::class, 'gold_gift'])->name('gift.store');
    Route::post('/donate/store', [AccountController::class, 'donate'])->name('donate.store');
    Route::get('/user', [AccountController::class, 'user'])->name('user');
    Route::post('/package/delete', [PackageController::class, 'delete'])->name('package.delete');
    Route::get('/recharge', [PackageController::class, 'recharge'])->name('recharge');
    Route::post('/recharge', [PackageController::class, 'store'])->name('recharge.store');
    Route::get('/withdraw', [WithDrawController::class, 'index'])->name('withdraw');
    Route::post('/withdraw/store', [WithDrawController::class, 'store'])->name('withdraw.store');
    Route::delete('/withdraw/store/delete', [WithDrawController::class, 'delete'])->name('withdraw.delete');
    Route::get('/{user}/edit', [AccountController::class, 'edit'])->name('edit');
    Route::put('/{user}/update', [AccountController::class, 'update'])->name('update');
    Route::post('/transform-stone', [AccountController::class, 'transformStone'])->name('transform-stone');
    Route::post('/create-character', [AccountController::class, 'createCharacter'])->name('create-character');
});

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/{user}', [AccountController::class, 'index'])->name('index');
});

Route::prefix('tusach')->name('book.')->middleware('user')->group(function () {
    Route::get('/following', [BookController::class, 'following'])->name('following');
    Route::get('/readed', [BookController::class, 'readed'])->name('readed');
    Route::delete('/unread/{story}', [BookController::class, 'unread'])->name('unread');
    Route::get('/unfollow/{story}', [BookController::class, 'unfollow'])->name('unfollow');
    Route::get('/users/nhung', [BookController::class, 'nhungs'])->name('nhungs');
    Route::get('/users/donate', [BookController::class, 'donate'])->name('donate');
});
Route::get('/{id}/nhung', [BookController::class, 'nhung'])->name('book.nhung');

Route::get('/huong-dan-su-dung', [PostController::class, 'index'])->name('post.index');
Route::get('/huong-dan-su-dung/{post:slug?}', [PostController::class, 'show'])->name('post.show');

// Route::get('/worldcup/admin',[WorldCup::class,'admin_index'])->name('wcadmin');
// Route::get('/worldcup/admin/create',[WorldCup::class,'admin_create'])->name('wcadmincreate');
// Route::post('/worldcup/admin/create',[WorldCup::class,'admin_store']);
// Route::get('/worldcup/admin/edit/{uuid}', [WorldCup::class,'admin_edit'])->name('wcadminedit');
// Route::post('/worldcup/admin/edit/{uuid}',[WorldCup::class,'admin_edit_store']);


// Route::get('/worldcup',[WorldCup::class,'index'])->name('worldcup');
// Route::post('/worldcup/{uuid}',[WorldCup::class,'create'])->name('wcusercreate');
// Route::get('/test',[User_tuluyen::class,'index'])->name('test');
Route::get('/tu-luyen',[User_tuluyen::class,'index'])->name('tuluyen.index');

Route::get('/tu-luyen/create-charater',[User_tuluyen::class,'create'])->name('tuluyen.create');

Route::get('/tu-sach', [BookController::class, 'bookMobile'])->name('book.mobile');

Route::get('/nang-vip', [PageController::class, 'vipPage'])->name('vip');
Route::get('/update-vip', [AccountController::class, 'upgradeVip'])->name('upgrade.vip');

Route::get('/pk',[PKController::class,'index'])->name('pk.index');
Route::get('/pk/actionpk',[PKController::class,'timDoiThu'])->name('pk.actionpk');//tìm đối thủ ngẫu nhiên
Route::get('/pk/actionpk/{id}',[PKController::class,'timDoiThuId'])->name('pk.actionpkId');// tìm đối thủ theo id
Route::get('/pk/battle',[PKController::class,'battle'])->name('pk.battle');// bắt đầu chiến đấu
Route::get('/pk/cancel',[PKController::class,'cancelpk'])->name('pk.cancelbattle');// huỷ pk



Route::get('/uu-dai-tu-luyen',[PKController::class,'packTuLuyenIndex'])->name('pk.packTuLuyen');
Route::get('/uu-dai-tu-luyen/buy/{pack}',[PKController::class,'packTuLuyen'])->name('pk.muapack');
Route::get('/uu-dai-khi-van',[PKController::class,'packKhiVanIndex'])->name('pk.packKhiVan');
Route::get('/uu-dai-khi-van/buy/{pack}',[PKController::class,'packKhivan'])->name('pk.muaauto');//mua gói auto vận khí


//đường dẫn test local, sửa lại theo ý muốn
Route::get('/tu-luyen/pkthuong',[PKController::class,'pkModeThuong'])->name('pk.modethuong');//chuyển sang chế độ pk thường
Route::get('/tu-luyen/pkcc',[PKController::class,'pkModeCaoCap'])->name('pk.modecc');// chuyển sang chế độ pk cao cấp

Route::get('/tu-luyen/cb',[PKController::class,'listChienBao'])->name('pk.listcb');//show danh sách chiến báo
Route::get('/tu-luyen/cb/{id}',[PKController::class,'showChienBao'])->name('pk.cb');//show chiến báo theo id
Route::get('/tu-luyen/muahodao',[PKController::class,'muaHoDao'])->name('pk.muahodao');//mua hộ đạo
Route::get('/pk/confirm',[PKController::class,'is_pkconfirm'])->name('pk.isconfirm');// xác nhận pk
Route::get("/xor",[PKController::class,'xorIndex'])->name("test.xor");
Route::post("/xor",[PKController::class,'xor']);
