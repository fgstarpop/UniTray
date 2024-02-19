<?php

namespace App\Domain\Activity;
use App\Domain\Story\Models\Story;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public $guarded = [];
    protected $table = 'follows';
    protected $connection = 'dbuser';
    public function stories(){
        return $this->belongsTo(Story::class, 'stories_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
