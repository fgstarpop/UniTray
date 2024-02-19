<?php

namespace App\TuLuyen;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Model_charater extends Model
{
    use UuidTrait;

    public $guarded = [];
    public $table = 'players_charater';
    public $primaryKey  = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    // protected  $with = ['get_items'];
    protected $connection = 'dbuser';
    public function get_users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function get_items()
    {
        return $this->hasMany('App\TuLuyen\Model_item', 'player_id', 'id');
    }

    public  function get_name()
    {
        return $this->get_users->name;
    }

    public function pk_damage()
    {
        return $this->atk + $this->sum_atk;
    }

    public function pk_def()
    {
        return $this->def + $this->sum_def;
    }

    public function pk_atk_speed()
    {
        return $this->atk_speed + $this->sum_atk_speed;
    }
    public function pk_hp()
    {
        return $this->max_hp + $this->sum_max_hp;
    }

    public function pk_mp()
    {
        return $this->max_mp + $this->sum_max_mp;
    }
    public function pk_crit()
    {
        return $this->crit + $this->sum_crit;
    }

    public function pk_crit_dmg()
    {
        return $this->crit_dmg + $this->sum_crit_dmg;
    }
    public function pk_dodge(){
        return $this->dodge + $this->sum_dodge;
    }
}
