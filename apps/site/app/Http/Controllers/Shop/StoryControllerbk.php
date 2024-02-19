<?php

namespace App\Http\Controllers\Shop;

use App\Domain\Category\Models\Category;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\LogSearch\Models\LogSearch;
use App\Domain\Story\Models\Story;
use App\Events\StoryViewed;
use App\Http\Controllers\Controller;
use App\Domain\Activity\Follow;
use App\Domain\Activity\Whishlist;
use App\Domain\Comment\Comment;
use App\Domain\Admin\Models\Wallet;
use App\Domain\Admin\Models\Donate;
use App\Scraper\LeechTrxs;
use App\Scraper\LeechXinyushuwu;
use Carbon\Carbon;
use App\Domain\Activity\Readed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class StoryController extends Controller
{
    public function show($story)
    {
        $story = Story::find($story);
        if($story == NULL) {
            return view('errors.404');
        }
        if ($story && empty($story->idhost)) {
            $datahost = Http::get("http://103.116.104.174:8000/gethost?link=$story->origin")->json();
            if (isset($datahost) && isset($datahost['bookid']) && isset($datahost['host'])) {
                $story->idhost = $datahost['bookid'];
                $story->host = $datahost['host'];
                $story->save();
            }

        }
        $tomorrow = new Carbon('tomorrow midnight');
        $today = new Carbon('today midnight');
        $comment = Comment::where(['commentable_type' => 'App/Domain/Chapter/Models/Story',
            'commentable_id' => $story->id,
            'parent_id' => '0'
        ])
            ->with('users', 'children')
            ->orderBy('created_at', 'desc')
            ->get();
        $wallet = 0;
        $donate = 0;
        $chapLastReaded = 0;

        if (!empty(currentUser()->id)) {
//            if(currentUser()->is_vip == 1){
//                if($story->type == 1 && $story->mod_id == null){
//                    $story->update(['mod_id' => currentUser()->id]);
//                }
//            }
            $wallet = Wallet::where('user_id', currentUser()->id)->first();
            $donate = Donate::where('created_at', '<', $tomorrow)->where('created_at', '>', $today)->where('user_id', currentUser()->id)->where('stories_id', $story->id)->count();
            $readeds = Readed::where('user_id', currentUser()->id)->where('stories_id', $story->id)->first();
            $readeds = ($readeds && $readeds->chapters_id) ? json_decode($readeds->chapters_id, true) : [];
            $chapLastReaded = !empty($readeds) ? Chapter::whereIn('id', $readeds)->orderBy('order', 'DESC')->first() : null;
        }
        $story->chapters = $story->chapters_json ? json_decode($story->chapters_json, 1) : $story->chapters;
        // if(strcmp($story->origin,'uukanshu') == 1)
        //     $story->chapters =  array_reverse($story->chapters);
        $story->chapters_count = $story->chapters ? count($story->chapters) : 0;
        $storySources = Story::where('author',$story->author)->where('name_chines',$story->name_chines)->orderBy('created_at', 'DESC')->get();
        $chapterLastReaded = $chapLastReaded ? $chapLastReaded->order : '';
        $listStoryRank = Story::orderBy('nomination', 'desc')->limit(100)->get();
        return view('shop.story.show', compact('story', 'listStoryRank', 'storySources','comment', 'wallet', 'donate', 'chapLastReaded', 'chapterLastReaded'));
    }

    public function follow($story)
    {
        try {
            $follow = Follow::where('user_id', currentUser()->id)->where('stories_id', $story)->first();
            $stories = Story::find($story);
            if (!$follow) {
                $stories->follow()->attach(currentUser()->id, ['source' => get_current_source()]);
//                $stories->follow()->create([
//                    'user_id' => currentUser()->id,
//                    'source' => get_current_source()
//                ]);
//                $all_follow = Follow::where('stories_id', $story)->count();
                $stories->follow_count = $stories->follow_count + 1;
                $stories->update();
                $all_follow = $stories->follow_count;
                return response()->json([
                    'status' => '200',
                    'all_follow' => $all_follow,
                    'message' => 'Theo dõi thành công',
                ]);
            } else {
                $stories->follow()->detach(currentUser()->id);
//                $all_follow = Follow::where('stories_id', $story)->count();
                $stories->follow_count = $stories->follow_count - 1;
                $stories->update();
                $all_follow = $stories->follow_count;
                return response()->json([
                    'status' => '200',
                    'all_follow' => $all_follow,
                    'message' => 'Bỏ theo dõi thành công',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => '300',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function whishlist($story)
    {
        $whishlist = Whishlist::where('user_id', currentUser()->id)->where('stories_id', $story)->first();
        $stories = Story::find($story);
        if (!$whishlist) {
            $stories->whishlist()->attach(currentUser()->id);
//            $all_whishlist = Whishlist::where('stories_id', $story)->count();
            $stories->whishlist_count = $stories->whishlist_count + 1;
            $stories->update();
            $all_whishlist = $stories->whishlist_count;
            return response()->json([
                'all_whishlist' => $all_whishlist,
                'message' => 'Yêu thích thành công',
            ]);
        } else {
            $stories->whishlist()->detach(currentUser()->id);
//            $all_whishlist = Whishlist::where('stories_id', $story)->count();
            $stories->whishlist_count = $stories->whishlist_count - 1;
            $stories->update();
            $all_whishlist = $stories->whishlist_count;
            return response()->json([
                'all_whishlist' => $all_whishlist,
                'message' => 'Bỏ yêu thích thành công',
            ]);
        }
    }

    public function search(Request $request)
    {
        /**
         * if(!empty($request->search)){
         * LogSearch::updateOrCreate([
         * 'key_word' => $request->search
         * ])->increment('hits');
         * }
         */
        $story = null;
        $url = filter_var($request->search, FILTER_VALIDATE_URL);


//      Nhung
        if ($url) {
            if (empty(currentUser())) {
                return response()->redirectTo('/');
            }
            if (strpos($url, 'xinyushuwu') || strpos($url, 'trxs')) {
                $bookId = '';
                $domain = '';
                $parse = parse_url($url);
                $host = $parse['scheme'] . '://' . $parse['host'];
                $idChapter = explode('/', $parse['path'])[2];
                $storyExists = Story::where('host', Story::ORIGINS[$host])
                    ->where('idhost', $idChapter);
                if ($storyExists->exists()) {
                    $storyExists = $storyExists->first();
                    return redirect()->route('story.show', $storyExists);
                }
            }
            if (strpos($url, 'xinyushuwu')) {
                $embedChapter = new LeechXinyushuwu();
                if ($embedChapter->scrape('', '', '', currentUser(), $url, true)) {
                    $story = Story::where('origin', $url)->first();
                    return redirect()->route('story.show', $story->id);
                } else {
                    dd('Không nhúng được');
                }
            } elseif (strpos($url, 'trxs')) {
                $embedChapter = new LeechTrxs();
                if ($embedChapter->scrape('', '', '', currentUser(), $url, true)) {
                    $story = Story::where('origin', $url)->first();
                    return redirect()->route('story.show', $story->id);
                } else {
                    dd('Không nhúng được');
                }
            }
            $datahost = Http::timeout(10)->get("http://103.116.104.174:8000/gethost?link=$url")->json();
            if (isset($datahost) && isset($datahost['bookid']) && isset($datahost['host'])) {
                $story = Story::where([['idhost', '=', $datahost['bookid']], ['host', '=', $datahost['host']]])->first();
                if (!empty($story)) {
                    return redirect()->route('story.show', $story);
                }

            }

            if (empty($story)) {
                $story = Story::where('origin', 'like', $url . '%')->first();
                if ($story && empty($story->hostid)) {

                    if (isset($datahost) && isset($datahost['bookid']) && isset($datahost['host'])) {
                        $story->idhost = $datahost['bookid'];
                        $story->host = $datahost['host'];
                        $story->save();
                    }

                }
            }
            // die(var_dump($story));
//            if (empty($story)){
//                $parseCheck = parse_url($url);
//                $baseUrlCheck = $parseCheck['scheme'] . '://' . $parseCheck['host'];
//                if ($baseUrlCheck == 'https://b.faloo.com'){
//                    $urlFaloo = str_replace('https://b.faloo.com', 'https://wap.faloo.com', $url);
//                    $story = Story::where('origin', 'like', $urlFaloo . '%')->first();
//                }
//
//                if ($baseUrlCheck == 'https://www.bxwxorg.com'){
//                    $urlBxwxorg = str_replace('https://www.bxwxorg.com', 'https://m.bxwxorg.com', $url);
//                    $story = Story::where('origin', 'like', $urlBxwxorg . '%')->first();
//                }
//            }
        }
        //return ['error'=> "lock"];
        if ($url && currentUser() && !isset($story->mod_id)) {

            $parse = parse_url($url);
            $base_url = $parse['scheme'] . '://' . $parse['host'];

            embedStoryUukanshu($url, $base_url, currentUser());
            $storyNew = Story::where('origin', $url)->first();
            if (!empty($storyNew)) {
                return redirect()->route('story.show', $storyNew->id);
            }


        }

        $categories = Category::where('status', Story::ACTIVE)->get();
        if(!$url  && !empty($request->search)){
            $stories = Story::where('name', 'like', '%' . $request->search . '%')->paginate(24);
            foreach($stories as &$list){
                $chapters = $list->chapters_json ? json_decode($list->chapters_json, true) : $list->chapters;

                $list->chapters_view = Chapter::where('story_id', $list->id)->sum('view');
                $list->chapters_count = count($chapters);
            }
            return view('shop.search', [
                'stories' => $stories,
                'categories' => $categories,
            ]);
        }



        if ($story && $url) {

            return redirect()->route('story.show', $story);
        }

        $stories = $this->filters($request);

        $count_chapter = $request->count_chapter ?? 0;
        if ($count_chapter) {
            $stories = $stories->where('count_chapters', '>', $count_chapter);
        }

        $stories = $stories->with('media')->select(['id', 'avatar', 'name', 'type', 'is_vip', 'origin', 'view', 'count_chapters', 'status'])->paginate(24);

        $stories->getCollection()->transform(function ($story) {
            $origin = '';
            if ($story->origin) {
                if (strpos($story->origin, 'faloo') !== false) {
                    $origin = 'faloo';
                }
                if (strpos($story->origin, 'uukanshu') !== false) {
                    $origin = 'uukanshu';
                }
                if (strpos($story->origin, 'qidian') !== false) {
                    $origin = 'qidian';
                }
                if (strpos($story->origin, 'bxwxorg') !== false) {
                    $origin = 'bxwxorg';
                }
                if (strpos($story->origin, 'fanqie') !== false) {
                    $origin = 'fanqie';
                }
            }
            $story->from = $origin;
            $story->chapters_count = $story->count_chapters;
            return $story;
        });
        return view('shop.search', [
            'stories' => $stories,
            'categories' => $categories,
        ]);
    }

    public function filters(Request $request)
    {

        $sort = $request->sort;
        $type = $request->type;
        $category = $request->category;
        $status = $request->status;
        $origin_link = $request->origin_link;
        $origin = $request->origin;
        $keyword = $request->keyword;
        $description = $request->description;

        $stories = Story::with(['categories'])->withCount(['chapters', 'follow', 'whishlist']);
        if(!empty($keyword)) {
            $stories->orWhere('name', 'like', '%' . $keyword . '%');
        }
        if (!empty($description)) {
            $stories->orWhere('description', 'like', '%' . $description . '%');
        }

        if ($origin_link || $origin) {
            if ($origin_link) {
                $parseLink = parse_url($origin_link);
                $base_url_link = $parseLink['scheme'] . '://' . $parseLink['host'];
                if ($base_url_link == 'https://b.faloo.com') {
                    $origin_link = str_replace('https://b.faloo.com', 'https://wap.faloo.com', $origin_link);
                }
            }

            $stories = $stories->where('origin', 'like', "%" . ($origin_link ?? $origin) . "%");
        }

        if ($status) {
            $stories = $stories->where('status', $status);
        }

        if ($category) {
            $stories = $stories->whereHas('categories', function ($q) use ($category) {
                return $q->where('id', $category);
            });
        }

        if ($type) {
            /*
            if ($type == 'sangtac') {
                $stories = $stories->where('type', 0);
            }
            if ($type == 'trans') {
                $stories = $stories->where('type', 1);
            }
            if ($type == 'vip') {
                $stories = $stories->where('type', 1)->whereHas('chapters', function ($q) {
                    $q->where('is_vip', 1);
                });
            }*/
            if ($type == 'conv') {
                    $stories = $stories->whereHas('types', function ($q) {
                        return $q->where('id', 1);
                    });
            }
            if ($type == 'convAu') {
                $stories = $stories->whereHas('types', function ($q) {
                    return $q->where('id', 2);
                });
            }
            if ($type == 'sangtac') {
                $stories = $stories->whereHas('types', function ($q) {
                    return $q->where('id', 3);
                });
            }
            if ($type == 'sangtacAu') {
                $stories = $stories->whereHas('types', function ($q) {
                    return $q->where('id', 4);
                });
            }
        }

        if ($sort) {
            if ($sort == Story::SORT_DESC) {
                $stories = $stories->latest();
            }
            if ($sort == Story::SORT_VIEW) {
                $stories = $stories->orderByDesc('view');
            }
            if ($sort == Story::SORT_UPDATE) {
                $stories = $stories->orderByDesc('chapter_updated');
            }
            if ($sort == Story::SORT_FOLLOW) {
                $stories = $stories->orderByDesc('follow_count');
            }
            if ($sort == Story::SORT_LIKE) {
                $stories = $stories->orderByDesc('whishlist_count');
            }
            if ($sort == Story::SORT_VIP_REQUEST) {
                $stories = $stories->orderByDesc('donate');
            }
            if ($sort == Story::SORT_VIEW_DAY) {
                $stories = $stories->orderByDesc('view_day');
            }
            if ($sort == Story::SORT_VIEW_WEEK) {
                $stories = $stories->orderByDesc('view_week');
            }
            if ($sort == Story::SORT_NOMINATION) {
                $stories = $stories->where('nomination','>',0)->orderByDesc('nomination');
            }
            if ($sort == Story::SORT_DONATE) {
                $stories = $stories->where('donate','>',0)->orderByDesc('donate');
            }
        }

        return $stories;
    }

    public function updateEmbedStory(Request $request)
    {
        if ($request->story_id) {
            $story = Story::findOrFail($request->story_id);
            $timenow = Carbon::now();
            $time_update = Carbon::parse($story->chapter_updated)->addMinutes(5);
            if ($timenow < $time_update) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Vừa mới cập nhật, không thể cập nhật thêm!'
                ]);
            }
            if ($story->type == 1 && $story->origin) {
                $url = $story->origin;
                $parse = parse_url($url);
                $base_url = $parse['scheme'] . '://' . $parse['host'];
                $datahost = Http::get("http://103.116.104.174:8000/gethost?link=$url")->json();
                if (isset($datahost['bookid']) && isset($datahost['host'])) {
                    embedStoryUukanshu($url, $base_url, currentUser(), $story);
                }


                return response()->json([
                    'code' => 200,
                    'message' => 'Cập nhật thành công!'
                ]);
            }
        }
    }

}
