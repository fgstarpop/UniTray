<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class GoldGift extends Model
{
    public $guarded = [];
    public $table = 'gold_gifts';
    protected $connection = 'dbuser';
    public function receiver(){
        return $this->belongsTo(User::class,'received_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
