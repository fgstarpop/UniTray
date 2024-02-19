<?php

declare(strict_types=1);

namespace App\Domain\Admin\Models;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Domain\Chapter\Models\Chapter;
use App\Domain\Story\Models\Story;
class Order extends Model
{
    public $guarded = [];
    protected $connection = 'dbuser';
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function story(){
        return $this->belongsTo(Story::class,'story_id');
    }
    public function chapter(){
        return $this->belongsTo(Chapter::class,'chapter_id');
    }
}
 