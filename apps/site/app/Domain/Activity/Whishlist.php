<?php

namespace App\Domain\Activity;

use Illuminate\Database\Eloquent\Model;

class Whishlist extends Model
{
    public $guarded = [];
    protected $table = 'whishlist';
    protected $connection = 'dbuser';
    public function stories(){
        return $this->belongsTo(Story::class, 'stories_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
