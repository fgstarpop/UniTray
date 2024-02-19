<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use  App\Domain\Activity\Notification;
use Carbon\Carbon;
class NotificationController
{
    public function noti(Request $request){
        $noti = Notification::find($request->id);
        $noti->read_at = Carbon::now();
        $noti->update();
        return '200';
    }

    public function allComment(){
        $noti = Notification::where('notifiable_id',currentUser()->id)->where('type','comment')->orderBy('created_at','desc')->get();
        foreach($noti as $list){
            $list->read_at = Carbon::now();
            $list->update();
        }
        return '200';
    }
    public function allNoti(){
        $noti = Notification::where('notifiable_id',currentUser()->id)->where('type','!=','comment')->orderBy('created_at','desc')->get();
        foreach($noti as $list){
            $list->read_at = Carbon::now();
            $list->update();
        }
        return '200';
    }

    public function allPk() {
        $noti = Notification::where('notifiable_id',currentUser()->id)->where('type','pk')->get();
        foreach($noti as $list){
            $list->read_at = Carbon::now();
            $list->update();
        }
        return '200';
    }
}
