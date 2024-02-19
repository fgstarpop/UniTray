<?php
namespace App\Domain\Story\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Domain\Story\Models\Story;
use App\Domain\Category\Models\Category;
class StoryCategories extends Model
{
    public $guarded = [];
    public $table = 'story_categories';
    public function stories(){
        return $this->belongsToMany(Story::class,'stories_id');
    }
    public function categories(){
        return $this->belongsToMany(Category::class,'categories_id');
    }
}
