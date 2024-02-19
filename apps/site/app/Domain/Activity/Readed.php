<?php

namespace App\Domain\Activity;

use App\Domain\Chapter\Models\Chapter;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Story\Models\Story;
use App\User;
class Readed extends Model
{
    public $guarded = [];
    protected $table = 'readed';
    protected $connection = 'dbuser';

    public function stories(){
        return $this->belongsTo(Story::class, 'stories_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function chapter() {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}
