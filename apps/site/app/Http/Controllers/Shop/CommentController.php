<?php

namespace App\Http\Controllers\Shop;

use App\Domain\Chapter\Models\Chapter;
use App\Domain\Story\Models\Story;
use App\Domain\Comment\Comment;
use Illuminate\Http\Request;
use App\User;
use  App\Domain\Activity\Notification;
use Str;
use Carbon\Carbon;
class CommentController
{
    public function create(Request $request){
        $comment = Comment::create([
            'user_id'           => $request->user,
            'parent_id'         => $request->parent,
            'commentable_id'    => $request->id,
            'commentable_type'  => $request->type,
            'body'              => $request->text
        ]);
        $user = User::find($request->user);

            if($request->type == 'App/User' ){
            // $text = currentUser()->name." đã bình luận về truyện trong trang cá nhân của bạn";
            // $link = route('user.index',$request->id) ;
            // Notification::create([
            //     'id'        =>  Str::uuid(),
            //     'type'      =>  'comment',
            //     'notifiable_type'   => 'App\User',
            //     'data'      =>  $text,
            //     'notifiable_id'     =>  $request->id,
            //     'read_at' => new Carbon('first day of January 2018'),
            //     'story_id'  =>  0,
            //     'count'     =>  1,
            //     'link'      =>  $link
            // ]);
            if($request->user_parent && $request->user_parent != currentUser()->id){
                $users = User::find($request->id);
                $text = currentUser()->name." đã trả lời về bình luận của bạn trong trang cá nhân của ".$users->name;
                $link = route('user.index',$request->id) ;
                Notification::create([
                    'id'        =>  Str::uuid(),
                    'type'      =>  'comment',
                    'notifiable_type' => 'App\User',
                    'data'      =>  $text,
                    'notifiable_id' =>  $request->user_parent,
                    'read_at' => new Carbon('first day of January 2018'),
                    'story_id'  =>  0,
                    'count'     =>  1,
                    'link'      =>  $link
                    ]);
                }
            }
            else{
                $story = Story::find($request->id);
                $text = currentUser()->name." đã bình luận về truyện ".$story->name;
                $link = route('story.show', $story) ;
                Notification::create([
                    'id'        =>  Str::uuid(),
                    'type'      =>  'comment',
                    'notifiable_type' => 'App\User',
                    'data'      =>  $text,
                    'notifiable_id' =>  $story->mod_id,
                    'story_id'  =>  $request->id,
                    'read_at' => new Carbon('first day of January 2018'),
                    'count'     =>  1,
                    'link'      =>  $link
                ]);
                if($request->user_parent && $request->user_parent != currentUser()->id){
                    $text = currentUser()->name." đã trả lời về bình luận của bạn trong truyện ".$story->name;
                    $link = route('story.show', $story) ;
                    Notification::create([
                        'id'        =>  Str::uuid(),
                        'type'      =>  'comment',
                        'notifiable_type' => 'App\User',
                        'data'      =>  $text,
                        'notifiable_id' =>  $request->user_parent,
                        'read_at' => new Carbon('first day of January 2018'),
                        'story_id'  =>  $request->id,
                        'count'     =>  1,
                        'link'      =>  $link
                        ]);
                }
            }
        $comment->time = $comment->created_at->diffForHumans();
        return response()->json([
            'status' => '200',
            'user' => $user,
            'comment' => $comment,
        ]);
    }
    public function delete(Request $request){
        if(!empty($request->storyCheck) || !empty($request->userCheck)){
            $comment = Comment::where(['id'=>$request->id])->first();
            $children = Comment::where(['parent_id'=>$request->id ])->get();

            if($comment){
                $comment->delete();
                if($children)
                    foreach($children as $list)
                        $list->delete();
                return response()->json([
                    'status' => '200',
                    'message' => 'Bạn đã xóa bình luận thành công !'
                ]);
            }
            else{
                return response()->json([
                    'status' => '300',
                    'comment' => 'Lỗi không xác định',
                ]);
            }
        }else{
            $comment = Comment::where(['id'=>$request->id , 'user_id'=>currentUser()->id])->first();
            if($comment->commentable_type == 'App/User'){
                $user = User::find($comment->commentable_id)->id;
            }
            if($comment->commentable_type == 'App/Domain/Chapter/Models/Story'){
                $user = Story::find($comment->commentable_id)->mod_id;
            }
            if($user == currentUser()->id){
                $comment = Comment::where(['id'=>$request->id ])->first();
                $children = Comment::where(['parent_id'=>$request->id ])->get();
            }else{
                $comment = Comment::where(['id'=>$request->id , 'user_id'=>currentUser()->id])->first();
                $children = Comment::where(['parent_id'=>$request->id ])->get();
            }
            if($comment){
                $comment->delete();
                if($children)
                    foreach($children as $list)
                        $list->delete();
                return response()->json([
                    'status' => '200',
                    'message' => 'Bạn đã xóa bình luận thành công !'
                ]);
            }
            else{
                return response()->json([
                    'status' => '300',
                    'comment' => 'Lỗi không xác định',
                ]);
            }
        }
    }
}
