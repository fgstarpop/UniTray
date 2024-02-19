<?php


namespace App\Domain\Comment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
class Comment extends Model
{
    public $guarded = [];
    protected $table = 'comments';
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
