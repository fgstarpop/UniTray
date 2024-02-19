<?php

namespace App\Domain\Activity;
use App\Domain\Story\Models\Story;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $guarded = [];
    protected $table = 'card';
    protected $connection = 'dbuser';
    
}
