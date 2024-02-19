<?php

namespace App\TuLuyen;

use App\User;
use App\Traits\UuidTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Model_item extends Model
{
    use UuidTrait;
    public $guarded = [];
    public $primaryKey  = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $table = 'players_items';
    protected $with = ['get_options'];
    protected $connection = 'dbuser';
    public function get_options()
    {
        return $this->belongsToMany(Model_Options::class, 'players_tmp_options', 'item_id', 'options')->as('op')->withPivot('value', 'add_value');

    }
    private function get_options_cache()
    {
        return $this->belongsToMany(Model_Options::class, 'players_tmp_options', 'item_id', 'options')->as('options_value')->withPivot('value', 'add_value');
    }
    public function get_charaters()
    {
        return $this->belongsTo(Model_charater::class, 'player_id', 'id');
    }
    public function get_user()
    {
        return $this->hasOneThrough(User::class, Model_charater::class, 'id', 'id', 'player_id', 'user_id');
    }
}
