<?php

namespace App\TuLuyen;

use Illuminate\Database\Eloquent\Model;

class Model_Temp_Options extends Model
{
    public $guarded = [];
    public $table = 'players_tmp_options';
    protected $connection = 'dbuser';
}
