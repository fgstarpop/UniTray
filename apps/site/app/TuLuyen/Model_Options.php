<?php

namespace App\TuLuyen;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Model_Options extends Model
{
    use UuidTrait;
    public $guarded = ['id'];
    public $table = 'players_items_options';
    protected $connection = 'dbuser';
    public function item(){
        $this->belongsToMany(Model_item::class,'players_tmp_options','options','item_id');
    }
}
