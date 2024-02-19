<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Str;
use Carbon\Carbon;
use App\Domain\Admin\Models\Order;
use App\User;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\Story\Models\Story;
use App\Domain\Admin\Models\Statistics;
use App\Domain\Admin\Models\WalletTransaction;
use App\Domain\Activity\Follow;
use App\Domain\Activity\Whishlist;
use DB;
use App\Domain\Activity\Notification;
class OrderController
{
    public function index(){
        $order = Order::where('user_id', currentUser()->id)
            ->where('source', get_current_source())
            ->with('story')
            ->select(\DB::raw('sum(price) as total_price'), \DB::raw('count(*) as total_chapter_buy'), \DB::raw('max(created_at) as last_buy_at'), 'story_id')
            ->groupBy('story_id')
            ->orderBy('last_buy_at','desc')->paginate(50);

        $storyIds = $order->getCollection()->pluck('story_id')->toArray();
        $orderChapter = Order::whereIn('orders.story_id', $storyIds)
            ->where('source', get_current_source())
            ->where('orders.user_id', currentUser()->id)
            ->with('chapter')
            // ->get()
            // ->groupBy('story_id');

            ->select('chapters.price', 'chapter_id', 'orders.story_id')
            ->join(env('DB_DATABASE', 'forge') . '.chapters', 'chapters.id', 'orders.chapter_id')
            ->orderBy('chapters.order', 'desc')
            ->get()
            ->groupBy('story_id');
        // $orderChapter = Order::whereIn('orders.story_id', $storyIds)
        //     ->where('orders.user_id', currentUser()->id)
        //     ->with('chapter')
        //     ->select('chapters.price', 'chapter_id', 'orders.story_id')
        //     ->join('chapters', 'chapters.id', 'orders.chapter_id')
        //     ->orderBy('chapters.order', 'desc')
        //     ->get()
        //     ->groupBy('story_id');

        $order->getCollection()->transform(function ($d) use ($orderChapter){
            $d->orderChapter = isset($orderChapter[$d->story_id]) ? $orderChapter[$d->story_id] : collect();
            return $d;
        });
        return view('shop.user.order.index',compact('order'));
    }

    public function chapter(Request $req){
        if($req->listBuy) {
            $listBuy = $req->listBuy;
            for($i = 0; $i < count($listBuy); $i++) {
                $chapter  = Chapter::find($listBuy[$i][0]);
                $wallet   = Wallet::where('user_id',currentUser()->id)->first();
                $vip = currentUser()->user_vip;

                if($vip == 1){
                    $price_chapters = 0;
                }else{
                    $price_chapters = 150;
                }
                if($wallet->gold < $price_chapters){
                    return response()->json([
                        'status' => '300',
                        'message' => __('Bạn không đủ vàng để mua chương này!'),
                    ]);
                }
                $orderss = Order::where(['chapter_id'=>$listBuy[$i][0],
                    'user_id' => currentUser()->id
                ])
                    ->where('source', get_current_source())
                    ->first();
                if($orderss){
                    return response()->json([
                        'status' => '200',
                        'message' => __('Bạn đã mua chương này rồi !'),
                    ]);
                }
                $listBuyConnect = $listBuy[$i];
                try {
                    \DB::transaction(function () use ($listBuyConnect,$chapter,$wallet,$price_chapters) {
                        $story    = Story::find($listBuyConnect[1]);
                        $currentMod = $listBuyConnect[3];
                        if($listBuyConnect[3] == NULL || $listBuyConnect[3] == '') {
                            $currentMod = 4;
                        }
                        $userTurnOver = User::find($currentMod);
                        $moneyAuthorReceived = $price_chapters - $price_chapters *  setting('fee_order_vip',0) / 100;
                        $moneyAuthorReceived = $moneyAuthorReceived / 2;
                        $tomorrow = new Carbon('tomorrow midnight');
                        $today    = new Carbon('today midnight');
                        $wallet->gold = $wallet->gold - $price_chapters;
                        $walletAuthor = Wallet::where('user_id',$currentMod)->first();
                        $walletAuthor->silver = $walletAuthor->silver + $moneyAuthorReceived;
                        $wallet->update();
                        $walletAuthor->update();
                        $statistics = Statistics::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
                        if($statistics){
                            $statistics->money = $statistics->money + $price_chapters *  setting('fee_order_vip',0) / 100;
                            $statistics->save();
                        }else{
                            Statistics::create([
                                'money'   =>  $price_chapters *  setting('fee_order_vip',0) / 100,
                            ]);
                        }

                        $orderCode = Str::orderedUuid()->toString();
                        $transactionCode = Str::orderedUuid()->toString();
                        //check xem người dùng đã mua hay chưa
                        $order = Order::where([
                            'user_id'  =>  currentUser()->id,
                            'story_id' => $story->id
                        ])->where('source', get_current_source())
                            ->orderBy('updated_at','desc')->first();
                        //check xem truyện đó đã được mua hay chưa
                        $allOrder = Order::where([
                            'story_id' => $story->id
                        ])->where('source', get_current_source())
                            ->orderBy('updated_at','asc')->first();
                        //check xem chương đó đã được mua hay chưa
                        $orderChapter = Order::where([
                            'story_id' => $story->id,
                            'chapter_id' => $chapter->id
                        ])->where('source', get_current_source())
                            ->orderBy('updated_at','desc')->first();
                        $checkStory = Order::where([
                            'story_id' => $story->id,
                            'type' => 1,
                        ])->where('source', get_current_source())
                            ->first();
                        if($checkStory){
                            $checkStory->type = 0;
                            $checkStory->update();
                        }

                        $priceOrderChapter = $orderChapter ? $orderChapter->total_money_per_chapter : 0;
                        $countOrderChapter = $orderChapter ? $orderChapter->total_order_per_chapter : 0;
                        if($order){
                            $saveOrder = Order::create([
                                'number'        => $orderCode,
                                'chapter_id'    => $chapter->id ,
                                'user_id'       => currentUser()->id,
                                'price'         => $price_chapters,
                                'total'         => $order->total + $price_chapters,
                                'story_id'      => $story->id,
                                'total_chapter' => $order->total_chapter + 1,
                                'total_all_price' => $allOrder->total_all_price + $moneyAuthorReceived,
                                'total_all_chapter' => $allOrder->total_all_chapter + 1,
                                'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                                'total_order_per_chapter' => $countOrderChapter + 1,
                                'type' => 1,
                                'source' => get_current_source()
                            ]);
                        }else if($allOrder){
                            $saveOrder = Order::create([
                                'number'        => $orderCode,
                                'chapter_id'    => $chapter->id ,
                                'user_id'       => currentUser()->id,
                                'price'         => $price_chapters,
                                'total'         => $price_chapters,
                                'story_id'      => $story->id,
                                'total_chapter' => 1,
                                'total_all_price' => $allOrder->total_all_price + $moneyAuthorReceived,
                                'total_all_chapter' => $allOrder->total_all_chapter + 1,
                                'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                                'total_order_per_chapter' => $countOrderChapter + 1,
                                'type' => 1,
                                'source' => get_current_source()
                            ]);
                        }
                        else{
                            $saveOrder = Order::create([
                                'number'        => $orderCode,
                                'chapter_id'    => $chapter->id ,
                                'user_id'       => currentUser()->id,
                                'price'         => $price_chapters,
                                'total'         => $price_chapters,
                                'story_id'      => $story->id,
                                'total_chapter' => 1,
                                'total_all_price' => $moneyAuthorReceived,
                                'total_all_chapter' => 1,
                                'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                                'total_order_per_chapter' => $countOrderChapter + 1,
                                'type' => 1,
                                'source' => get_current_source()
                            ]);
                        }
                        WalletTransaction::create([
                            'transaction_id'    => $transactionCode,
                            'user_id'           => $currentMod,
                            'change_type'       => 0,
                            'transaction_type'  => 5,
                            'created_at'        => Carbon::now(),
                            'gold'              => 0,
                            'yuan'              => $moneyAuthorReceived,
                            'gold_balance'      => $walletAuthor->gold,
                            'yuan_balance'      => $walletAuthor->silver,
                        ]);
                        $userTurnOver->turn_over = $userTurnOver->turn_over + $moneyAuthorReceived;
                        $story->audio_month = $story->audio_month + $moneyAuthorReceived;
                        $story->save();
                        $userTurnOver->save();
                        WalletTransaction::create([
                            'transaction_id'    => $transactionCode,
                            'user_id'           => currentUser()->id,
                            'change_type'       => 1,
                            'transaction_type'  => 5,
                            'created_at'        => Carbon::now(),
                            'gold'              => $price_chapters,
                            'yuan'              => 0,
                            'gold_balance'      => $wallet->gold,
                            'yuan_balance'      => $wallet->silver,
                        ]);

                    });
                } catch (\Exception $e) {
                    return back()->with(['message' => $e->getMessage()]);
                }


            }
        }
        else {
            $chapter  = Chapter::find($req->chapter);
            $wallet   = Wallet::where('user_id',currentUser()->id)->first();
            $vip = currentUser()->user_vip;

            if($vip == 1){
                $price_chapters = 0;
            }else{
                $price_chapters = 150;
            }
            if($wallet->gold < $price_chapters){
                return response()->json([
                    'status' => '300',
                    'message' => __('Bạn không đủ vàng để mua chương này!'),
                ]);
            }
            $orderss = Order::where(['chapter_id'=>$req->chapter,
                'user_id' => currentUser()->id
            ])
                ->where('source', get_current_source())
                ->first();
            if($orderss){
                return response()->json([
                    'status' => '200',
                    'message' => __('Bạn đã mua chương này rồi !'),
                ]);
            }
            try {
                \DB::transaction(function () use ($req,$chapter,$wallet,$price_chapters) {
                    $story    = Story::find($req->story);
                    $currentMod = $req->author;
                    if($story->mod_id == NULL) {
                        $currentMod = 4;
                    }
                    $userTurnOver = User::find($currentMod);
                    $moneyAuthorReceived = $price_chapters - $price_chapters *  setting('fee_order_vip',0) / 100;
                    $moneyAuthorReceived = $moneyAuthorReceived / 2;
                    $tomorrow = new Carbon('tomorrow midnight');
                    $today    = new Carbon('today midnight');
                    $wallet->gold = $wallet->gold - $price_chapters;
                    $walletAuthor = Wallet::where('user_id',$currentMod)->first();
                    $walletAuthor->silver = $walletAuthor->silver + $moneyAuthorReceived;
                    $wallet->update();
                    $walletAuthor->update();
                    $statistics = Statistics::where('created_at','<', $tomorrow)->where('created_at','>', $today)->first();
                    if($statistics){
                        $statistics->money = $statistics->money + $price_chapters *  setting('fee_order_vip',0) / 100;
                        $statistics->save();
                    }else{
                        Statistics::create([
                            'money'   =>  $price_chapters *  setting('fee_order_vip',0) / 100,
                        ]);
                    }

                    $orderCode = Str::orderedUuid()->toString();
                    $transactionCode = Str::orderedUuid()->toString();
                    //check xem người dùng đã mua hay chưa
                    $order = Order::where([
                        'user_id'  =>  currentUser()->id,
                        'story_id' => $story->id
                    ])->where('source', get_current_source())
                        ->orderBy('updated_at','desc')->first();
                    //check xem truyện đó đã được mua hay chưa
                    $allOrder = Order::where([
                        'story_id' => $story->id
                    ])->where('source', get_current_source())
                        ->orderBy('updated_at','asc')->first();
                    //check xem chương đó đã được mua hay chưa
                    $orderChapter = Order::where([
                        'story_id' => $story->id,
                        'chapter_id' => $chapter->id
                    ])->where('source', get_current_source())
                        ->orderBy('updated_at','desc')->first();
                    $checkStory = Order::where([
                        'story_id' => $story->id,
                        'type' => 1,
                    ])->where('source', get_current_source())
                        ->first();
                    if($checkStory){
                        $checkStory->type = 0;
                        $checkStory->update();
                    }

                    $priceOrderChapter = $orderChapter ? $orderChapter->total_money_per_chapter : 0;
                    $countOrderChapter = $orderChapter ? $orderChapter->total_order_per_chapter : 0;
                    if($order){
                        $saveOrder = Order::create([
                            'number'        => $orderCode,
                            'chapter_id'    => $chapter->id ,
                            'user_id'       => currentUser()->id,
                            'price'         => $price_chapters,
                            'total'         => $order->total + $price_chapters,
                            'story_id'      => $story->id,
                            'total_chapter' => $order->total_chapter + 1,
                            'total_all_price' => $allOrder->total_all_price + $moneyAuthorReceived,
                            'total_all_chapter' => $allOrder->total_all_chapter + 1,
                            'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                            'total_order_per_chapter' => $countOrderChapter + 1,
                            'type' => 1,
                            'source' => get_current_source()
                        ]);
                    }else if($allOrder){
                        $saveOrder = Order::create([
                            'number'        => $orderCode,
                            'chapter_id'    => $chapter->id ,
                            'user_id'       => currentUser()->id,
                            'price'         => $price_chapters,
                            'total'         => $price_chapters,
                            'story_id'      => $story->id,
                            'total_chapter' => 1,
                            'total_all_price' => $allOrder->total_all_price + $moneyAuthorReceived,
                            'total_all_chapter' => $allOrder->total_all_chapter + 1,
                            'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                            'total_order_per_chapter' => $countOrderChapter + 1,
                            'type' => 1,
                            'source' => get_current_source()
                        ]);
                    }
                    else{
                        $saveOrder = Order::create([
                            'number'        => $orderCode,
                            'chapter_id'    => $chapter->id ,
                            'user_id'       => currentUser()->id,
                            'price'         => $price_chapters,
                            'total'         => $price_chapters,
                            'story_id'      => $story->id,
                            'total_chapter' => 1,
                            'total_all_price' => $moneyAuthorReceived,
                            'total_all_chapter' => 1,
                            'total_money_per_chapter' => $priceOrderChapter + $moneyAuthorReceived,
                            'total_order_per_chapter' => $countOrderChapter + 1,
                            'type' => 1,
                            'source' => get_current_source()
                        ]);
                    }
                    WalletTransaction::create([
                        'transaction_id'    => $transactionCode,
                        'user_id'           => $currentMod,
                        'change_type'       => 0,
                        'transaction_type'  => 5,
                        'created_at'        => Carbon::now(),
                        'gold'              => 0,
                        'yuan'              => $moneyAuthorReceived,
                        'gold_balance'      => $walletAuthor->gold,
                        'yuan_balance'      => $walletAuthor->silver,
                    ]);
                    $userTurnOver->turn_over = $userTurnOver->turn_over + $moneyAuthorReceived;
                    $story->audio_month = $story->audio_month + $moneyAuthorReceived;
                    $story->save();
                    $userTurnOver->save();
                    WalletTransaction::create([
                        'transaction_id'    => $transactionCode,
                        'user_id'           => currentUser()->id,
                        'change_type'       => 1,
                        'transaction_type'  => 5,
                        'created_at'        => Carbon::now(),
                        'gold'              => $price_chapters,
                        'yuan'              => 0,
                        'gold_balance'      => $wallet->gold,
                        'yuan_balance'      => $wallet->silver,
                    ]);

                });
            } catch (\Exception $e) {
                return back()->with(['message' => $e->getMessage()]);
            }
        }
        return response()->json([
            'status' => '200',
            'message' => __('Mua chương VIP thành công !'),
        ]);
    }

    public function statistic(){
        $myStoryIds = Story::where('mod_id', currentUser()->id)->pluck('id')->toArray();
        $order = Order::whereIn('story_id', $myStoryIds)
            ->where('source', get_current_source())
            ->with('story')
            ->select(\DB::raw('sum(price) as total_price'), \DB::raw('count(*) as total_chapter_buy'), \DB::raw('max(created_at) as last_buy_at'), 'story_id')
            ->groupBy('story_id')
            ->orderBy('total_price','desc')->paginate(50);

        $storyIds = $order->getCollection()->pluck('story_id')->toArray();
        $orderChapter = Order::whereIn('story_id', $storyIds)
            ->where('source', get_current_source())
            ->with('chapter')
            ->select(DB::raw('sum(price) as total_price'), \DB::raw('count(*) as total_chapter_buy'), 'chapter_id', 'story_id')
            ->groupBy('chapter_id', 'story_id')
            ->get()
            ->groupBy('story_id');

        $order->getCollection()->transform(function ($d) use ($orderChapter){
            $d->orderChapter = isset($orderChapter[$d->story_id]) ? $orderChapter[$d->story_id] : collect();
            return $d;
        });

        return view('shop.user.order.statistic',compact('order'));
    }

}
