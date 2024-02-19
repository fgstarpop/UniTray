<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Game extends Model
{
    public const START_DAY = 'yesterday 18:40:00';
    public const END_DAY = 'today 18:00:00';
    public const START_TIME = '18:40:00';
    public const END_TIME = '18:00:00';
    protected $connection = 'dbuser';
    public $guarded = [];
    public $table = 'game';
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
