<?php

namespace App\Domain\Activity;
use App\Domain\Story\Models\Story;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $guarded = [];
    protected $table = 'notifications';
    protected $connection = 'dbuser';

}
