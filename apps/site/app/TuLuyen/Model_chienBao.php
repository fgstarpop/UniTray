<?php

namespace App\TuLuyen;

use App\Traits\UuidTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Model_chienBao extends Model
{
    use UuidTrait;
    public $guarded = [];
    public $table = 'players_chienbao';
    public $primaryKey  = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    // protected  $with = ['get_items'];
    protected $connection = 'dbuser';

    public function get_users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function get_users1()
    {
        // return $this->hasOne(User::class,'player1_id','id');
        return $this->belongsTo('App\User','player1_id','id');
    }
    public function get_users2()
    {
        return $this->belongsTo('App\User','player2_id','id');
    }
}
