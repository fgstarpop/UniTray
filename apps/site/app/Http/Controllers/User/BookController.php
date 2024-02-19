<?php

namespace App\Http\Controllers\User;

use App\Domain\Admin\Models\Order;
use App\Domain\Story\Models\Story;
use App\Http\Controllers\Controller;
use App\Domain\Activity\Follow;
use App\Domain\Activity\Readed;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\Admin\Models\Donate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
class BookController extends Controller
{
    public function following()
    {
        $book = Follow::where('user_id', currentUser()->id)
            ->where('source', get_current_source())
            ->with(['stories' => function ($q) {
                $q->with('media');
            }])->orderBy('id', 'desc')->paginate(50);
        return view('shop.user.book.following', [
            'book' => $book
        ]);
    }

    public function readed()
    {
        $book = Readed::where('user_id', currentUser()->id)
            ->where('source', get_current_source())
            ->orderBy('updated_at', 'desc')->with('stories')->paginate(50);
        return view('shop.user.book.readed', [
            'book' => $book
        ]);
    }

    public function unread($story)
    {
        $story = Story::find($story);
        $user = currentUser()->id;
        if ($story)
            $story->readed()->detach($user);
        return response()->json([
            'status' => '200',
        ]);
    }

    public function unfollow($story)
    {
        $story = Story::find($story);
        $user = currentUser()->id;
        if ($story)
            $story->follow()->detach($user);
        return redirect()->route('book.following');
    }

    public function nhung(Request $request)
    {
        $book = Story::where('user_id',$request->id)->where('type', '1')->with(['user', 'media'])->orderBy('audio_month', 'DESC')->paginate(50);
        $user = User::find($request->id);
        return view('shop.user.book.nhung', [
            'book' => $book,
            'user' => $user
        ]);
    }

    public function nhungs()
    {
        $book = Story::where('type', '1')->with(['user'])->withCount('chapters')
            ->where('mod_id', currentUser()->id)->orderBy('updated_at', 'DESC')->paginate(50);
        return view('shop.user.book.nhungs', [
            'book' => $book
        ]);
    }
    public function donate()
    {
        $tomorrow = new Carbon('tomorrow midnight');
        $today =  new Carbon('today midnight');
        $donate = Story::where('mod_id',currentUser()->id)->where('donate','!=','0')->with('user')->orderBy('updated_at','desc')->paginate(50);

        $donateIds = $donate->getCollection()->pluck('mod_id')->toArray();

        $donateToday = Donate::selectRaw('received_id, SUM(gold) as donate_today')
            ->where('created_at','<', $tomorrow)
            ->where('created_at','>', $today)
            ->whereIn('received_id', $donateIds)
            ->where('source', get_current_source())
            ->groupBy('received_id')
            ->get()
            ->keyBy('received_id');


        $donateList = Donate::selectRaw('received_id, user_id, users.name as name, SUM(gold) as totalGold, donate.updated_at')
            ->join('users', 'users.id', 'donate.user_id')
            ->whereIn('received_id', $donateIds)
            ->where('source', get_current_source())
            ->groupBy('received_id', 'user_id', 'users.name', 'donate.updated_at')
            ->get()
            ->groupBy('received_id');
        $donate->getCollection()->transform(function ($d) use ($donateToday, $donateList){
            $d->donate_today = isset($donateToday[$d->mod_id]) ? $donateToday[$d->mod_id]->donate_today : 0;
            $d->listUser = isset($donateList[$d->mod_id]) ? $donateList[$d->mod_id] : collect();
            return $d;
        });
        return view('shop.user.book.donate', [
            'donate' => $donate
        ]);
    }

    public function bookMobile(Request $request)
    {
        if (empty(currentUser())) {
            flash()->warning('Vui lòng đăng nhập để xem tủ sách!');
            return response()->redirectTo('/');
        }
        switch ($request->get('type')) {
            case 'ordered':
                $order = Order::where('user_id', currentUser()->id)
                    ->where('source', get_current_source())
                    ->with('story')
                    ->select(\DB::raw('sum(price) as total_price'), \DB::raw('count(*) as total_chapter_buy'), \DB::raw('max(created_at) as last_buy_at'), 'story_id')
                    ->groupBy('story_id')
                    ->orderBy('last_buy_at','desc')->paginate(1);

                $storyIds = $order->getCollection()->pluck('story_id')->toArray();
                $book = Story::whereIn('id', $storyIds)->orderBy('id', 'desc')->paginate(10);
                break;
            case 'embedded':
                $book = Story::where('user_id', currentUser()->id)
                    ->where('type', '1')
                    ->with(['user', 'media'])
                    ->orderBy('updated_at', 'DESC')
                    ->paginate(10);
                break;
            case 'follow':
                $book = Follow::where('user_id', currentUser()->id)
                    ->where('source', get_current_source())
                    ->with(['stories' => function ($q) {
                        $q->with('media');
                    }])
                    ->orderBy('id', 'desc')
                    ->paginate(10);
                break;
            case 'read-recently':
            default:
                $book = Readed::where('user_id', currentUser()->id)
                    ->where('source', get_current_source())
                    ->orderBy('updated_at', 'desc')
                    ->with('stories')
                    ->paginate(10);
                break;
        }

        return view('shop.user.book.mobile-book', [
            'book' => $book
        ]);
    }
}
