<?php

namespace App\Http\Controllers\User;

use App\Domain\Chapter\Models\Chapter;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Domain\Story\Models\Story;
use App\Domain\Activity\Follow;
use App\Domain\Activity\Notification;
use App\User;
use Mews\Purifier\Facades\Purifier;
use Str;
use Illuminate\Database\Eloquent\Collection;
Carbon::setLocale('vi');
class ChapterController extends Controller
{
    public function index(Story $story)
    {
        //$check_mod = Story::where('user_id', currentUser()->id)->where('mod_id',currentUser()->id)->get();
        // if (!empty($check_mod )) {
        //    if ($story->mod_id != currentUser()->id){
        //     return redirect()->route('home');
        //  }

        // }

        if ($story->type == 1) {
            $chapters = collect(json_decode($story->chapters_json, true))->sortByDesc('order')->paginateCollection(50);
        } else {
            $chapters = Chapter::where('story_id', $story->id)->orderBy('order', 'ASC')->orderBy('created_at', 'desc')->paginate(50);
        }

        return view('shop.user.chapters.index', [
            'chapters' => $chapters,
            'story' => $story,
        ]);
    }

    public function create(Story $story)
    {
        if (strpos(url()->current(), 'truyenvipfaloo')) {
            if ((!empty($story->id) && $story->type == 1) && ($story->mod_id != currentUser()->id || currentUser()->is_vip != 1)) {
                if (currentUser()->id != 4) {
                    flash()->error('Bạn không có quyền tạo chương');
                    return redirect()->route('home');
                }
            }
        } else {
            if (!empty($story->id) && $story->type == 1 && $story->mod_id != currentUser()->id &&currentUser()->id != 4) {
                flash()->error('Bạn không có quyền tạo chương');
                return redirect()->route('home');
            }
        }
        if ($story->type == 0) {
            $chapter = Chapter::where('story_id', $story->id)->orderBy('order', 'desc')->first();
            $order = !empty($chapter) ? $chapter->order + 1 : 0;
        } else {
            if ($story->complete_free == Story::COMPLETE_FREE_INACTIVE) {
                flash('Chưa xác nhận hoàn thành chương miễn phí !','danger');
                return back();
            }
            $chapters = $story->chapters_json ? json_decode($story->chapters_json, 1) : [];
            $chapters = collect($chapters);
            if ($chapters) {
                $lastest_chapters = $chapters->sortByDesc('order')->first();
                $order = $lastest_chapters['order'] + 1;
            } else {
                $order = 0;
            }
        }

        $chapterItem = view('shop.user.chapters.item',[
            'story' => $story,
        ])->render();

        return view('shop.user.chapters.create', [
            'story' => $story,
            'order' => $order,
            'chapterItem' => $chapterItem,
        ]);
    }

    public function store(Request $request)
    {


//        if(currentUser()->id != 4){
//            flash('Bạn không có quyền tạo chương', 'danger');
//            return redirect()->route('home');
//        }
        $story = Story::findOrFail($request->story_id);

        if (strpos(url()->current(), 'truyenvipfaloo')) {
            if ($story->mod_id != currentUser()->id ){
                if (currentUser()->id != 4) {
                    return redirect()->route('home');
                }
            }
        } else {
            if ($story->mod_id != currentUser()->id && currentUser()->id != 4 ) {
                return redirect()->route('home');
            }
        }

//        try {
//            \DB::transaction(function () use ($request) {
        if(!empty($request->input('chapters')[0]['text_china_mul']) && $request->input('chapters')[0]['text_china_mul'] == "1"){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://103.116.104.174:8000/upload/',
                CURLOPT_POST => 1,
                CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm tra SSL
                CURLOPT_CONNECTTIMEOUT => 25,
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'listtext' => $request->input('chapters')[0]['content'],
                    'orders' => $request->input('chapters')[0]['order']

                ))
            ));
            $resp = curl_exec($curl);

            curl_close($curl);
            $decode_chuong = json_decode($resp,true);
            if($decode_chuong=="error"){
                flash("Lỗi sai cấu trúc đăng nhiều chương", 'danger');
                return back();
            }
            $message = '';
            $decode_chuong = json_decode($resp,true);

            $folow = Follow::where('stories_id', $request->story_id)->get();
            foreach ($decode_chuong as $multiup) {
                $chapter = $this->createChaptermulti( $request->input('chapters')[0],$multiup);
                $text = "Truyện " . $story->name . " của tác giả " . currentUser()->name . " đã có 1 chương mới";
                $link = route('story.show', $story);
                $this->createBatchNotification($folow, $text, $link, $chapter);
                if ($story->type == 1) {
                    $this->updateStory($story, $chapter);
                }

                $message .= __('Chương ":name" đã được tạo', ['name' => $chapter->name]) . '\n';

            }

            flash()->success($message);
        }else{
            if(!empty($request->text_china) && $request->text_china == 1){
                $content = translate(str_replace('<br />','<br>',  $request->input('content')));
            }else{
                $content = Purifier::clean($request->input('content'));
            }

            $folow = Follow::where('stories_id', $request->story_id)->get();
            $message = '';
            foreach ($request->input('chapters') as $chapter) {
                $chapter = $this->createChapter($chapter);
                $text = "Truyện " . $story->name . " của tác giả " . currentUser()->name . " đã có 1 chương mới";
                $link = route('story.show', $story);
                $this->createBatchNotification($folow, $text, $link, $chapter);
                if ($story->type == 1) {
                    $this->updateStory($story, $chapter);
                }

                $message .= __('Chương ":name" đã được tạo', ['name' => $chapter->name]) . '\n';
            }

        }

        $countChapters = count(collect(json_decode($story->chapters_json, 1)));
        $story->update([
            'count_chapters' => $countChapters
        ]);

        flash()->success($message);
        $story->chapter_updated = Carbon::now();
        $story->update();
        $chapters = $story->chapters_json ? json_decode($story->chapters_json, true) : [];
        $dupValue = 0;
        for($i=0;$i<count($chapters);$i++){
            if(isset($chapters[$i]['order']))
            {
                if($dupValue == $chapters[$i]['order'])
                {
                    $chapters[$i]['order'] = $chapters[$i]['order'] + 1;
                }

                if($dupValue > $chapters[$i]['order']) {

                    $chapters[$i]['order'] = $dupValue+1;
                }

                $dupValue = $chapters[$i]['order'];
            }
        }
        $story->update([
            'chapters_json' => $chapters,
        ]);
//            });
//            }

//        } catch (\Exception $e) {
//            return back()->with(['message' => $e->getMessage()]);
//        }
        return redirect()->route('chapters.index', $request->story_id);
    }
    public function edit($chapter)
    {
        $chapter = Chapter::find($chapter);
        if($chapter == NULL) {
            return view('errors.404');
        }
        if(currentUser()->id == 4 || optional($chapter->story)->mod_id == currentUser()->id) {
            return view('shop.user.chapters.edit', [
                'chapter' => $chapter
            ]);
        }
         else {
            flash('Bạn không có quyền tạo chương', 'danger');
            return redirect()->route('home');
        }
    }

    public function update(Request $request, Chapter $chapter)
    {
//        try {
//            \DB::transaction(function () use ($request, $chapter) {
        if(!empty($request->text_china) && $request->text_china == 1){
            $content = translate(str_replace('<br />','<br>',  $request->input('content')));
//                    $content = translate(str_replace('<br />','<br>',  Purifier::clean($request->input('content'))));
        }else{
            $content = Purifier::clean($request->input('content'));
        }
        $chapter->update([
            'name' => $request->name,
            'order' => $request->order,
            'content' => $content,
            'is_vip' => currentUser()->is_vip ? ($request->is_vip ?? 0) : 0,
            'link_other' => currentUser()->is_vip == 1 ? $request->link_other : null,
            'price' => currentUser()->is_vip == 1 ? (int)$request->price : null,
            'timer' => $request->timer ? Carbon::parse($request->timer)->format('Y-m-d H:i:s') : null
        ]);

        $story = $chapter->story;
        if ($story->type == 1) {
            $new_chapter = $chapter->toArray();
            if (key_exists('content', $new_chapter)) {
                unset($new_chapter['content']);
            }
            if (key_exists('story', $new_chapter)) {
                unset($new_chapter['story']);
            }
            $chapters = $story->chapters_json ? json_decode($story->chapters_json, true) : [];

            if ($chapters) {
                foreach ($chapters as &$chap) {
                    if (@$chap['id'] && $chap['id'] == $new_chapter['id']) {
                        $chap = $new_chapter;
                    }
                }

            }
            $orders = array_column($chapters, 'order');
            array_multisort($orders, SORT_ASC, $chapters);
            $story->update(['chapters_json' => json_encode($chapters)]);
        }

        flash()->success(__('Chương ":name" đã cập nhật', ['name' => $chapter->name]));
//            });
//        } catch (\Exception $e) {
//            return back()->with(['message' => $e->getMessage()]);
//        }
        return redirect()->route('chapters.index', $chapter->story_id);
    }

    public function delete(Chapter $chapter)
    {
        if(currentUser()->id == 4 || optional($chapter->story)->mod_id == currentUser()->id) {
            $story = $chapter->story;
            $valueIndex = 0;
            $chapters = $story->chapters_json ? json_decode($story->chapters_json, true) : [];
            foreach ($chapters as $chap => $chap_value) {
                if(isset($chap_value['id'])){
                    if ($chap_value['id'] == $chapter->id) {
                        $valueIndex = $chap;
                        break;
                    }
                }
             }
            for($i = $valueIndex; $i<count($chapters); $i++) {
                $chapters[$i]['order'] = $chapters[$i]['order'] - 1;
            }
            $chapters = array_filter($chapters, function ($c) use ($chapter){
                if(isset($c['id'])){
                    return $chapter->id != $c['id'];
                }
                return true;
            });

            $countChapters = count($chapters);
            $story->update([
                'chapters_json' => $chapters,
                'count_chapters' => $countChapters
            ]);
            $chapter->delete();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => __('Chương đã được xóa thành công!'),
            ]);
        }
        else {
            flash('Bạn không có quyền xoá chương', 'danger');
            return redirect()->route('home');
        }

    }

    /**
     * @param Story $story
     * @param Chapter $chapter
     * @return void
     */
    private function updateStory(Story $story, Chapter $chapter): void
    {
        $new_chapter = $chapter->toArray();
        if (key_exists('content', $new_chapter)) {
            unset($new_chapter['content']);
        }
        if (key_exists('story', $new_chapter)) {
            unset($new_chapter['story']);
        }
        $chapters = $story->chapters_json ? json_decode($story->chapters_json, true) : [];
        $chapters[] = $new_chapter;
        $orders = array_column($chapters, 'order');
        array_multisort($orders, SORT_ASC, $chapters);
        $story->update(['chapters_json' => json_encode($chapters)]);
    }

    /**
     * @param Collection $folow
     * @param string $text
     * @param string $link
     * @param Chapter $chapter
     * @return void
     */
    private function createBatchNotification(Collection $folow, string $text, string $link, Chapter $chapter): void
    {
        foreach ($folow as $list) {
            $noti = Notification::where(['type' => 'chapter',
                'notifiable_id' => $list->user_id,
                'story_id' => request()->get('story_id'),
            ])->orderBy('updated_at', 'desc')->first();
            if ($noti) {
                $noti->count = $noti->count + 1;
                $noti->read_at = new Carbon('first day of January 2018');
                $noti->data = "Truyện " . $chapter->story->name . " của tác giả " . currentUser()->name . " đã có " . $noti->count . " chương mới";
                $noti->save();
            } else {
                Notification::create([
                    'id' => Str::uuid(),
                    'type' => 'chapter',
                    'notifiable_type' => 'App\User',
                    'data' => $text,
                    'read_at' => new Carbon('first day of January 2018'),
                    'notifiable_id' => $list->user_id,
                    'story_id' => request()->get('story_id'),
                    'count' => 1,
                    'link' => $link
                ]);
            }
        }
    }

    /**
     * @param array $chapters
     * @return Chapter
     */
    private function createChapter(array $chapter): Chapter
    {
        if(!empty($chapter['text_china']) && $chapter['text_china'] == 1){
            $content = translate(str_replace('<br />','<br>',  $chapter['content']));
            $title = _vp_viet($chapter['name']);
        }else{
            $content = Purifier::clean($chapter['content']);
            $title = $chapter['name'];
        }

        if(!isset($chapter['link_other'])){
            $chapter['link_other'] = null;
        }

        return Chapter::create([
            'name' => $title,
            'order' => $chapter['order'],
            'content' => $content,
            'status' => Chapter::ACTIVE,
            'story_id' => request()->get('story_id'),
            'is_vip' => currentUser()->is_vip ? ($chapter['is_vip'] ?? 0) : 0,
            'link_other ' => currentUser()->is_vip ? ($chapter['link_other']) : null,
            'mod_id' => currentUser()->is_vip ? (currentUser()->id) : null,
            'price' => currentUser()->is_vip == 1 ? $chapter['price'] ?? 0 : 0,
            'timer' => array_key_exists('timer', $chapter) ? Carbon::parse($chapter['timer'])->format('Y-m-d H:i:s') : null
        ]);
    }

    private function createChaptermulti(array $chapter, $multiup): Chapter
    {
        if(!empty($chapter['text_china']) && $chapter['text_china'] == 1){
            $content = translate(str_replace('<br />','<br>',  Purifier::clean($multiup['content'])));
            $title = _vp_viet($multiup['title']);
        }else{
            $content = Purifier::clean($multiup['content']);
            $title = $multiup['title'];
        }

        if(!isset($chapter['link_other'])){
            $chapter['link_other'] = null;
        }

        return Chapter::create([
            'name' => $title,
            'order' => $multiup['orders'],
            'content' => $content,
            'status' => Chapter::ACTIVE,
            'story_id' => request()->get('story_id'),
            'is_vip' => currentUser()->is_vip ? ($chapter['is_vip'] ?? 0) : 0,
            'link_other ' => currentUser()->is_vip ? ($chapter['link_other']) : null,
            'mod_id' => currentUser()->is_vip ? (currentUser()->id) : null,
            'price' => currentUser()->is_vip == 1 ? $chapter['price'] ?? 0 : 0,
            'timer' => array_key_exists('timer', $chapter) ? Carbon::parse($chapter['timer'])->format('Y-m-d H:i:s') : null
        ]);
    }
}
