<?php

namespace App\Domain\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Win extends Model
{
    public $guarded = [];
    public $table = 'win'; 
    protected $connection = 'dbuser';
    public function user(){
        return $this->belongsTo(User::class,'winner');
    }
}
