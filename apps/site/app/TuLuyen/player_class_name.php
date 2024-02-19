<?php

namespace App\TuLuyen;

use Illuminate\Database\Eloquent\Model;

class player_class_name extends Model
{
    public $guarded = [];
    public $table = 'player_class_name';
    public $primaryKey  = ['lv_id','class_id'];
    public $incrementing = false;
}
