<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Domain\Story\Models\Story;
class Donate extends Model
{
    public $guarded = [];
    public $table = 'donate';
    protected $connection = 'dbuser';
    public function user(){
        return $this->belongsTo(User::class,'received_id');
    }
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function story(){
        return $this->belongsTo(Story::class,'stories_id');
    }
}
