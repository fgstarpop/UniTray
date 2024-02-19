<?php
 
namespace App\Domain\Story\Models;


use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Domain\Story\Models\Story;
use App\Domain\Type\Models\Type;
class StoryTypes extends Model
{
    public $guarded = [];
    public $table = 'story_types';
    public function stories(){
        return $this->belongsToMany(Story::class,'stories_id');
    }
    public function types(){
        return $this->belongsToMany(Type::class,'types_id');
    } 
}
